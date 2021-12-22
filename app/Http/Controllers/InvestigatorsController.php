<?php

namespace App\Http\Controllers;

use App;
use App\AssignedInvestigator;
use App\ClientProduct;
use App\ContactTypes;
use App\Country;
use App\Helpers\AppHelper;
use App\Investigations;
use App\InvestigationTransition;
use App\InvestigatorBankDetail;
use App\InvestigatorInvestigations;
use App\InvestigatorProduct;
use App\Investigators;
use App\InvestigatorSpecilization;
use App\Mail\EmailClientApproval;
use App\Mail\EmailVerify;
use App\PaymentMode;
use App\PaymentTerm;
use App\Product;
use App\Specialization;
use App\User;
use App\UserAddress;
use App\UserEmail;
use App\UserPhone;
use App\UserTypes;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Crypt;
use View, DB, Log;
use Auth;

class InvestigatorsController extends Controller
{

    /**
     * Generate and return listing of the investigators
     * @return mixed
     * @throws \Exception
     */
    public function investigatorList()
    {

        $userType = UserTypes::where('type_name', env('USER_TYPE_INVESTIGATOR'))->pluck('id');

        if ($userType->isEmpty()) {
            return DataTables::of([])->make(true);
        }

        $investigators = User::whereHas(
            'user_type',
            function ($query) {
                return $query->where('type_name', env('USER_TYPE_INVESTIGATOR'));
            }
        )
            ->with([
                'investigator' => function ($q) {
                    $q->select(['id', 'user_id', 'family', 'idnumber', 'website', 'dob', 'company'])->with([
                        'investigations' => function ($query) {
                            $query->select(['id', 'investigator_id', 'investigation_id', 'status']);
                        }, 'invoice' => function ($query) {
                            $query->select(['id', 'investigation_id', 'investigator_id', 'invoice_no']);
                        }
                    ]);
                },
                'userAddresses' => function ($q) {
                    $q->select(['id as addr_id', 'user_id', 'address1', 'address2', 'street', 'city', 'state', 'zipcode']);
                },
                'userEmails' => function ($q) {
                    $q->select(['id as mails_id', 'user_id', 'value']);
                },
                'userPhones' => function ($q) {
                    $q->select(['id as phn_id', 'user_id', 'value', 'type']);
                },
                'specializations' => function ($q) {
                    $q->select(['name', 'hr_name']);
                },
            ])
            ->orderBy('created_at', 'desc');

        $result = DataTables::of($investigators)
            ->addColumn('family', function ($investigator) {
                return !empty($investigator->investigator->family) ? $investigator->investigator->family : '';
            })
            ->addColumn('idnumber', function ($investigator) {
                return !empty($investigator->investigator->idnumber) ? $investigator->investigator->idnumber : '';
            })
            ->addColumn('website', function ($investigator) {
                return !empty($investigator->investigator->website) ? $investigator->investigator->website : '';
            })
            ->addColumn('specializations', function ($investigator) {

                $html = '<ul class="speclist-ul">';
                if(App::isLocale('hr')){
                    $specArr = !empty($investigator->specializations) ? array_column($investigator->specializations->toArray(), 'hr_name') : '';
                } else {
                    $specArr = !empty($investigator->specializations) ? array_column($investigator->specializations->toArray(), 'name') : '';
                }

                if ($specArr) {
                    foreach ($specArr as $spec) {
                        $html .= '<li>' . ucwords($spec) . '</li>';
                    }
                }
                $html .= '</ul>';

                return $html;
            })
            ->addColumn('investigations', function ($investigator) {

                $invStatus = null;
                $html = '<ul class="spec-ul">';

                $invStatus = InvestigatorInvestigations::whereHas('investigation')
                    ->select(
                        'investigator_id',
                        'investigation_id',
                        \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN status = 'Completed With Findings' OR status = 'Report Accepted' OR status = 'Final Report Submitted' THEN 1 else 0 end) AS total_completed"),
                        DB::raw("SUM(CASE WHEN status NOT IN ('Completed With Findings', 'Report Accepted', 'Final Report Submitted', 'Completed Without Findings', 'Report Declined') THEN 1 else 0 end) AS total_open")
                    )
                    ->where('investigator_id', $investigator->investigator->id)
                    ->first();

                $active = $invStatus->total_open ?? 0;
                $completed = $invStatus->total_completed ?? 0;

                $html .= "<li>".trans('form.investigation.search.open_cases')." ({$active})</li>";
                $html .= "<li>".trans('form.investigation_status.Completed')." ({$completed})</li>";

                $html .= '</ul>';

                return $html;
            })
            ->addColumn('phone', function ($investigator) {
                return !$investigator->userPhones->isEmpty() ? $investigator->userPhones[0]->value : '';
            })
            ->addColumn('address', function ($investigator) {
                return !$investigator->userAddresses->isEmpty() ? $investigator->userAddresses[0]->address1 : '';
            })
            ->editColumn('status', function ($investigator) {
                if ($investigator->status) {
                    if ($investigator->status == 'approved') {
                        return '<td>
                            <span class="badge dt-badge badge-success">' . ucwords($investigator->status) . '</span>
                        </td>';
                    } else if ($investigator->status == 'pending') {
                        return '<td>
                            <span class="badge dt-badge badge-warning">' . ucwords($investigator->status) . '</span>
                        </td>';
                    } else if ($investigator->status == 'disabled') {
                        return '<td>
                            <span class="badge dt-badge badge-dark">' . ucwords($investigator->status) . '</span>
                        </td>';
                    }
                }

                return "";
            })
            ->addColumn('action', function ($investigator) {
                $btns = "<span class='d-inline-flex'>";
                if (check_perm('investigator_edit')) {
//                    if ($investigator->status == 'pending' || ($investigator->status == 'disabled')) {
//                        $btns .= '<a href="javascript:void(0)" class="dt-btn-action" title="' . trans('general.approve') . '" onclick="changeStatus(\'approved\',' . $investigator->id . ')">
//                            <i class="mdi mdi-account-check mdi-18px"></i>
//                        </a>';
//                    }

                    if ($investigator->status == 'pending' || $investigator->status == 'disabled') {
                        $btns .= '<a href="' . route('investigator.showApproveForm', ['userId' => Crypt::encrypt($investigator->id)]) . '" class="dt-btn-action" title="' . trans('general.approve') . '">
                            <i class="mdi mdi-account-check mdi-18px"></i>
                        </a>';
                    }

                    if ($investigator->status == 'approved') {
                        $btns .= '<a href="' . route('investigator.showApproveForm', ['userId' => Crypt::encrypt($investigator->id)]) . '" class="dt-btn-action" title="' . trans('form.edit_payment_details') . '">
                            <i class="mdi mdi-content-save-edit-outline mdi-18px"></i>
                        </a>';
                    }

                    if ($investigator->status == 'approved') {
                        $btns .= '<a href="javascript:void(0)" class="dt-btn-action" title="' . trans('general.disable') . '" onclick="changeStatus(\'disabled\',' . $investigator->id . ')">
                            <i class="mdi mdi-account-off mdi-18px"></i>
                        </a>';
                    }

                    $btns .= '<a href="' . route('investigator.edit', ['userId' => Crypt::encrypt($investigator->id)]) . '" class="dt-btn-action" title="' . trans('general.edit') . '">
                                <i class="mdi mdi-account-edit mdi-18px"></i>
                            </a>';
                }
                if (check_perm('investigator_delete')) {
                    if(isAdmin() || (count($investigator->investigator->investigations) == 0 && count($investigator->investigator->invoice) == 0)){
                        $btns .= '<a href="javascript:void(0)" id="deleteInvestigator" class="dt-btn-action" title="' . trans('general.delete') . '" data-id="' . $investigator->id . '"">
                                <i class="mdi mdi-account-remove mdi-18px"></i>
                            </a>';
                    }
                }
                if (check_perm('investigator_show')) {
                    $btns .= '<a href="' . route('investigator.detail', ['userId' => Crypt::encrypt($investigator->id)]) . '" class="dt-btn-action" title="' . trans('general.view') . '">
                                <i class="mdi mdi-account-details mdi-18px"></i>
                            </a>';
                }
                $btns .= '</span>';

                return $btns;
            })
            ->rawColumns(['specializations', 'investigations','status', 'action'])
            ->make(true);

        return $result;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \View
     */
    public function index()
    {
        $topInvestigators = InvestigatorInvestigations::with(['investigator:id,user_id', 'investigator.user:id,name,email'])
            ->select(
                'investigator_id',
                \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN status = 'Completed With Findings' OR status = 'Report Accepted' OR status = 'Final Report Submitted' THEN 1 else 0 end) AS total_completed"),
                DB::raw("SUM(CASE WHEN status NOT IN ('Completed With Findings', 'Report Accepted', 'Final Report Submitted', 'Completed Without Findings', 'Report Declined') THEN 1 else 0 end) AS total_open")
            )
            ->groupBy('investigator_id')
            ->orderBy('total_completed', 'DESC')
            ->orderBy('total_open', 'DESC')
            ->limit(4)
            ->get();

        $activeInvestigators = DB::select("SELECT i.id, u.name, u.email,
        SUM(case when ii.status not in ('Completed With Findings', 'Report Accepted', 'Final Report Submitted', 'Completed Without Findings', 'Report Declined') then 1 else 0 end) AS total_open,
        SUM(case when ii.status = 'Completed With Findings' or ii.status = 'Report Accepted' or ii.status = 'Final Report Submitted' then 1 else 0 end) AS total_completed,
        count(ii.id) AS all_assigned,
        round(SUM(case when ii.status = 'Completed With Findings' or ii.status = 'Report Accepted' or ii.status = 'Final Report Submitted' then 1 else 0 end) * 100 / count(ii.id), 2) as percentage
        from investigators i
        left join investigator_investigations ii on ii.investigator_id = i.id
        left join users u on u.id = i.user_id
        group by i.id  
        having total_open = 0  and all_assigned > 0
        order by percentage desc LIMIT 4");

        return view('investigators.index', compact('topInvestigators', 'activeInvestigators'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userType = UserTypes::where('type_name', env('USER_TYPE_INVESTIGATOR'))->pluck('id');
        $typeid   = $userType[0];
        $countries = Country::select('id', 'en_name', 'code', 'hr_name')->get();
        $specializations = Specialization::select('name', 'id', 'hr_name')->orderBy('id', 'ASC')->get();
        $contacttypes = ContactTypes::select('type_name', 'id', 'hr_type_name')->where('type', env('CONTACT_TYPE_DEFAULT'))->orderBy('id', 'ASC')->get();

        return view('investigators.create', compact('typeid', 'countries', 'specializations', 'contacttypes'));
    }

    /**
     * Generate unique verification token
     */
    public function generateVerifyToken()
    {
        // Generate new token
        return Str::random(10) . sha1(time());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function investigatorValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed'],
        ]);
    }

    public function investigatorUpdateValidator(array $data, $userId)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => 'unique:users,email,' . $userId,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->investigatorValidator($request->all())->validate();

        $userData = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verify_token' => $this->generateVerifyToken(),
            'type_id' => $request->type_id,
        ]);

        $invstData = Investigators::create([
            'user_id' => $userData->id,
            'family' => $request->family,
            'idnumber' => $request->idnumber,
            'website' => $request->website,
            'dob' => date("Y-m-d", strtotime($request->dob))
        ]);

        if ($request->filled('name_of_bank')) {
            InvestigatorBankDetail::create([
                'investigator_id' => $invstData->id,
                'name' => $request->name_of_bank,
                'company' => $request->company,
                'number' => $request->bank_number,
                'branch_name' => $request->branch_name,
                'branch_number' => $request->branch_no,
                'account_no' => $request->account_no,
            ]);
        }

        if ($request->filled('specializations')) {
            foreach ($request->input('specializations') as $value) {
                InvestigatorSpecilization::create([
                    'user_id' => $userData->id,
                    'specialization_id' => $value,
                ]);
            }
        }

        $arr_address = $arr_email = $arr_phones = $arr_mobile = $arr_fax = [];

        if ($request->filled('address')) {
            foreach ($request->input('address') as $address) {
                $address['user_type_id'] = $request->type_id;
                $address['address_type'] = (($address['address_type'] == 'Other' || $address['address_type'] == 'Contact') && $address['other_text'] != '') ? $address['other_text'] : $address['address_type'];
                $address['user_id'] = $userData->id;
                unset($address['other_text']);
                $arr_address[] = $address;
            }
            UserAddress::insert($arr_address);
        }

        if ($request->filled('otheremail')) {
            foreach ($request->input('otheremail') as $value) {
                if(empty($value['email'])){
                    continue;
                }
                $value['user_type_id'] = $request->type_id;
                $value['email_type'] = (($value['email_type'] == 'Other' || $value['email_type'] == 'Contact') && $value['other_text'] != '') ? $value['other_text'] : $value['email_type'];
                $value['user_id'] = $userData->id;
                $value['value'] = $value['email'];
                unset($value['other_text']);
                unset($value['email']);
                $arr_email[] = $value;
            }
            UserEmail::insert($arr_email);
        }

        if ($request->filled('otherphone')) {
            foreach ($request->input('otherphone') as $value) {
                if(empty($value['phone'])){
                    continue;
                }
                $value['user_type_id'] = $request->type_id;
                $value['phone_type'] = (($value['phone_type'] == 'Other' || $value['phone_type'] == 'Contact') && $value['other_text'] != '') ? $value['other_text'] : $value['phone_type'];
                $value['type'] = 'phone';
                $value['user_id'] = $userData->id;
                $value['value'] = $value['phone'];
                unset($value['other_text']);
                unset($value['phone']);
                $arr_phones[] = $value;
            }
            UserPhone::insert($arr_phones);
        }

        if ($request->filled('othermobile')) {
            foreach ($request->input('othermobile') as $value) {
                if(empty($value['mobile'])){
                    continue;
                }
                $value['user_type_id'] = $request->type_id;
                $value['phone_type'] = (($value['mobile_type'] == 'Other' || $value['mobile_type'] == 'Contact') && $value['other_text'] != '') ? $value['other_text'] : $value['mobile_type'];
                $value['type'] = 'mobile';
                $value['user_id'] = $userData->id;
                $value['value'] = $value['mobile'];
                unset($value['other_text']);
                unset($value['mobile_type']);
                unset($value['mobile']);
                $arr_mobile[] = $value;
            }
            UserPhone::insert($arr_mobile);
        }

        if ($request->filled('otherfax')) {
            foreach ($request->input('otherfax') as $value) {
                if(empty($value['fax'])){
                    continue;
                }
                $value['user_type_id'] = $request->type_id;
                $value['phone_type'] = (($value['fax_type'] == 'Other' || $value['fax_type'] == 'Contact') && $value['other_text'] != '') ? $value['other_text'] : $value['fax_type'];
                $value['type'] = 'fax';
                $value['user_id'] = $userData->id;
                $value['value'] = $value['fax'];
                unset($value['other_text']);
                unset($value['fax_type']);
                unset($value['fax']);
                $arr_fax[] = $value;
            }
            UserPhone::insert($arr_fax);
        }

        $invtransdata = [
            'event' => 'investigator_created',
            'data' => json_encode(array('data' => array('id' => $userData->id, 'type' => 'investigator_registration'))),
        ];
        InvestigationTransition::addTransion($invtransdata, NULL);

        $mailVerify = Mail::to($userData->email)->queue(new EmailVerify($userData));

        return redirect('/investigators')->with('success', trans('form.registration.investigator.new_investigator_added'));
    }

    /**
     * Show the Investigator Detail Data
     *
     * @param  int  $userid = Crypt::encrypt($userId)
     * @return \Illuminate\Http\Response
     */
    public function show($userId)
    {
        try {
            $userType = UserTypes::where('type_name', env('USER_TYPE_INVESTIGATOR'))->pluck('id');
            $userId = Crypt::decrypt($userId);
            $typeid   = $userType[0];
            $user = User::where('id', '=', $userId)
                ->where('type_id', '=', $typeid)->first();
            if (!$user) {
                return back()->with('warning', trans('general.no_record_found'));
            }
            return view('investigators.show', compact('user'));
        } catch (Exception $exception) {
            return back()->with('warning', trans('form.registration.something_went_wrong'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Investigators  $investigators
     * @return \Illuminate\Http\Response
     */
    public function edit($userId)
    {
        $userId = Crypt::decrypt($userId);
        $userType = UserTypes::where('type_name', env('USER_TYPE_INVESTIGATOR'))->pluck('id');
        $typeid   = $userType[0];
        $countries = Country::select('id', 'en_name', 'code', 'hr_name')->get();
        $specializations = Specialization::select('name', 'id', 'hr_name')->orderBy('id', 'ASC')->get();
        $contacttypes = ContactTypes::select('type_name', 'id', 'hr_type_name')->where('type', env('CONTACT_TYPE_DEFAULT'))->orderBy('id', 'ASC')->get();
        $client = User::find($userId);

        $phones = $mobiles = $faxes = $specs = [];

        foreach ($client->userPhones as $phone) {
            if ($phone->type == 'phone') {
                $phones[] = $phone;
            } else if ($phone->type == 'mobile') {
                $mobiles[] = $phone;
            } else {
                $faxes[] = $phone;
            }
        }

        $specs = array_column($client->specializations->toArray(), 'id');

        return view('investigators.edit', compact('typeid', 'countries', 'specializations', 'client', 'phones', 'mobiles', 'faxes', 'specs', 'contacttypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Investigators  $investigators
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $userId)
    {
        $this->investigatorUpdateValidator($request->all(), $userId)->validate();

        $user = User::with('client')->find($userId);

        $userData = $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $investigator = $user->investigator;

        $invstData = $investigator->update([
            'user_id' => $userId,
            'family' => $request->family,
            'idnumber' => $request->idnumber,
            'website' => $request->website,
            'dob' => date("Y-m-d", strtotime($request->dob))
        ]);

        $investigatorBankDetail = $investigator->investigatorBankDetail;

        if ($request->filled('name_of_bank') && !is_null($investigatorBankDetail)) {
            $investigatorBankDetail->update([
                'investigator_id' => $investigator->id,
                'name' => $request->name_of_bank,
                'company' => $request->company,
                'number' => $request->bank_number,
                'branch_name' => $request->branch_name,
                'branch_number' => $request->branch_no,
                'account_no' => $request->account_no,
            ]);
        }

        if ($request->filled('specializations')) {
            //            InvestigatorSpecilization::where('user_id', $userId)->delete();

            $user->specializations()->detach();

            foreach ($request->input('specializations') as $value) {
                InvestigatorSpecilization::create([
                    'user_id' => $userId,
                    'specialization_id' => $value,
                ]);
            }
        }

        $arr_address = $arr_email = $arr_phones = $arr_mobile = $arr_fax = [];

        if ($request->filled('address')) {
            UserAddress::where('user_id', $userId)->delete();
            foreach ($request->input('address') as $address) {
                $address['user_type_id'] = $request->type_id;
                $address['address_type'] = (($address['address_type'] == 'Other' || $address['address_type'] == 'Contact') && $address['other_text'] != '') ? $address['other_text'] : $address['address_type'];
                $address['user_id'] = $userId;
                unset($address['other_text']);
                $arr_address[] = $address;
            }
            UserAddress::insert($arr_address);
        }

        if ($request->filled('otheremail')) {
            UserEmail::where('user_id', $userId)->delete();
            foreach ($request->input('otheremail') as $value) {
                if(empty($value['email'])){
                    continue;
                }
                $value['user_type_id'] = $request->type_id;
                $value['email_type'] = (($value['email_type'] == 'Other' || $value['email_type'] == 'Contact') && $value['other_text'] != '') ? $value['other_text'] : $value['email_type'];
                $value['user_id'] = $userId;
                $value['value'] = $value['email'];
                unset($value['other_text']);
                unset($value['email']);
                $arr_email[] = $value;
            }
            UserEmail::insert($arr_email);
        }

        if ($request->filled('otherphone')) {
            UserPhone::where('user_id', $userId)->where('type', 'phone')->delete();
            foreach ($request->input('otherphone') as $value) {
                if(empty($value['phone'])){
                    continue;
                }
                $value['user_type_id'] = $request->type_id;
                $value['phone_type'] = (($value['phone_type'] == 'Other' || $value['phone_type'] == 'Contact') && $value['other_text'] != '') ? $value['other_text'] : $value['phone_type'];
                $value['type'] = 'phone';
                $value['user_id'] = $userId;
                $value['value'] = $value['phone'];
                unset($value['other_text']);
                unset($value['phone']);
                $arr_phones[] = $value;
            }
            UserPhone::insert($arr_phones);
        }

        if ($request->filled('othermobile')) {
            UserPhone::where('user_id', $userId)->where('type', 'mobile')->delete();
            foreach ($request->input('othermobile') as $value) {
                if(empty($value['mobile'])){
                    continue;
                }
                $value['user_type_id'] = $request->type_id;
                $value['phone_type'] = (($value['mobile_type'] == 'Other' || $value['mobile_type'] == 'Contact') && $value['other_text'] != '') ? $value['other_text'] : $value['mobile_type'];
                $value['type'] = 'mobile';
                $value['user_id'] = $userId;
                $value['value'] = $value['mobile'];
                unset($value['other_text']);
                unset($value['mobile_type']);
                unset($value['mobile']);
                $arr_mobile[] = $value;
            }
            UserPhone::insert($arr_mobile);
        }

        if ($request->filled('otherfax')) {
            UserPhone::where('user_id', $userId)->where('type', 'fax')->delete();
            foreach ($request->input('otherfax') as $value) {
                if(empty($value['fax'])){
                    continue;
                }
                $value['user_type_id'] = $request->type_id;
                $value['phone_type'] = (($value['fax_type'] == 'Other' || $value['fax_type'] == 'Contact') && $value['other_text'] != '') ? $value['other_text'] : $value['fax_type'];
                $value['type'] = 'fax';
                $value['user_id'] = $userId;
                $value['value'] = $value['fax'];
                unset($value['other_text']);
                unset($value['fax_type']);
                unset($value['fax']);
                $arr_fax[] = $value;
            }
            UserPhone::insert($arr_fax);
        }

        return redirect('/investigators')->with('success', trans('form.registration.investigator.investigator_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Investigators  $investigators
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            User::find($id)->delete();

            return response()->json([
                'status' => 'success',
                'message' => trans('form.registration.investigator.investigator_deleted'),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => $th->getMessage(),
            ]);
        }
    }

    public function deleteMultiple(Request $request)
    {

        try {
            if (!empty($request->ids)) {
                User::whereIn('id', $request->ids)->delete();

                return response()->json([
                    'status' => 'success',
                    'message' => trans('form.registration.investigator.investigator_deleted'),
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('general.something_wrong'),
                    'exception' => trans('general.something_wrong'),
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => $th->getMessage(),
            ]);
        }
    }

    public function changeStatus(Request $request)
    {
        try {
            if (!empty($request->id) && !empty($request->action)) {
                $user = User::find($request->id);

                $user->status = $request->action;

                $user->save();

                return response()->json([
                    'status' => 'success',
                    'message' => trans('form.registration.investigator.confirm_statuschanged'),
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('general.something_wrong'),
                    'exception' => trans('general.something_wrong'),
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => $th->getMessage(),
            ]);
        }
    }

    public function searchInvestigations($investigatorId, $search)
    {
        $investigatorsList = null;
        $assignedInvestigations = InvestigatorInvestigations::where('investigator_id', $investigatorId)->get(['investigation_id'])->toArray();

        $investigations = Investigations::with(['subjects', 'client_type', 'product', 'user'])
            ->whereHas('subjects', function ($query) use ($search) {
                $query->where('family_name', 'like', '%' . $search . '%')->orWhere('first_name', 'like', '%' . $search . '%')->orWhere('id_number', 'like', '%' . $search . '%');
            })
            ->orWhereHas('client_type', function ($query) use ($search) {
                $query->where('type_name', 'like', '%' . $search . '%');
            })
            ->orWhereHas('product', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orWhereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orWhere('user_inquiry', 'like', '%' . $search . '%')
            ->orWhere('work_order_number', 'like', '%' . $search . '%')
            ->orWhere('ex_file_claim_no', 'like', '%' . $search . '%')
            ->orWhere('claim_number', 'like', '%' . $search . '%')
            ->get();

        $investigationList = View::make(
            'investigators.partials.investigation-search-list',
            [
                'investigations' => $investigations,
                'investigatorId' => $investigatorId,
                'assigned' => count($assignedInvestigations) ? array_column($assignedInvestigations, 'investigation_id') : [],
            ]
        )->render();

        return response()->json([
            'status' => 'success',
            'data' => $investigationList,
        ]);
    }

    public function assignInvestigation(Request $request)
    {
        try {
            $assigned = null;
            $exist = InvestigatorInvestigations::where('investigation_id', $request->investigationId)->where('investigator_id', $request->investigatorId)->first();

            if (empty($exist)) {
                $assigned = InvestigatorInvestigations::create([
                    'investigation_id' => $request->investigationId,
                    'investigator_id' => $request->investigatorId,
                    'note' => $request->note,
                    'status' => 'Assigned',
                    'status_hr' => trans('form.timeline_status.Assigned', [], 'hr'),
                    'assigned_by' => Auth::id(),
                ]);

                Investigations::where('id', $request->investigationId)->update(['status' => 'Assigned', 'status_hr' => trans('form.timeline_status.Assigned', [], 'hr')]);
            }

            return response()->json([
                'status' => 'success',
                'message' => trans('form.investigation.inv_assign_success'),
                'data' => $assigned
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => $th->getMessage(),
            ]);
        }
    }

    /**
     * View Investigations for investigator
     * @param $investigatorId
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    function viewInvestigations($investigatorId, Request $request)
    {
        $queryBuilder = InvestigatorInvestigations::with(['investigation.product'])->where('investigator_id', $investigatorId);

        if ($request->filled('status')) {
            if ($request->get('status') == 'Completed With Findings' || $request->get('status') == 'Completed Without Findings') {
                $queryBuilder->where('status', $request->get('status'));
            } else {
                $queryBuilder->whereNotIn('status', ['Completed With Findings', 'Completed Without Findings']);
            }
        }
        $investigations = $queryBuilder->groupBy('investigation_id')
            ->orderBy('investigation_id')
            ->latest()
            ->get();

        $returnHTML = view('investigators.view_investigations', compact('investigations'))->render();

        return response()->json(['status' => true, 'html' => $returnHTML]);
    }

    /**
     * Show form for approving investigator status
     *
     * @param  \Illuminate\Http\Request
     * @param $userId
     * @return mixed
     */
    public function showApproveForm(Request $request, $userId)
    {
        $userId = Crypt::decrypt($userId);
        $user = User::find($userId);

        if (!$user) {
            return back()->with('warning', trans('general.no_record_found'));
        }

        $products = Product::all();

        $client_products = $user->investigator->products()->get()->keyBy('id')->toArray();
        return view('investigators.investigator-approval', compact('user', 'products', 'client_products'));
    }

    /**
     * Update investigator details and change status to Approved
     *
     * @param  \Illuminate\Http\Request
     * @param $userId
     * @return mixed
     */
    public function approveClient(Request $request, $userId)
    {
        $user = User::with('investigator')->find($userId);

        if ($request->filled('arr_product')) {
            foreach ($request->input('arr_product') as $key => $value) {
                InvestigatorProduct::updateOrCreate([
                    'investigator_id' => $user->investigator->id,
                    'product_id' => $key
                ], ['price' => $value]);
            }
        }

        $success_message = trans('form.client_approval.payment_details_updated_successfully');

        if ($user->status !== 'approved') {
            $user->status = 'approved';
            $user->approved_at = Carbon::now();
            $user->approved_by = \Illuminate\Support\Facades\Auth::user()->id;
            $user->save();

            $invtransdata = [
                'event' => 'user_approve',
                'data' => json_encode(array('data' => array('id' => $user->id, 'name' => $user->name, 'type' => 'investigator_approve'))),
            ];
            InvestigationTransition::addTransion($invtransdata, NULL);

            Mail::to($user->email)->queue(new EmailClientApproval($user));
            $success_message = trans('form.client_approval.investigator_approved');
        }

        return redirect('/investigators')->with('success', $success_message);
    }
}
