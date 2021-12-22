<?php

namespace App\Http\Controllers;

use App\ContactTypes;
use App\Country;
use App\DeliveryArea;
use App\DeliveryboyAreas;
use App\DeliveryboyBankDetail;
use App\DeliveryboyProduct;
use App\DeliveryBoys;
use App\InvestigatorProduct;
use App\Mail\EmailVerify;
use App\PaymentMode;
use App\PaymentTerm;
use App\Product;
use App\User;
use App\InvestigationTransition;
use App\UserAddress;
use App\UserEmail;
use App\UserPhone;
use App\UserTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Exception;
use Illuminate\Support\Facades\Crypt;
use App\Mail\EmailClientApproval;

class DeliveryBoysController extends Controller
{

    /**
     * Generate and return listing of the investigators
     * @return mixed
     * @throws \Exception
     */
    public function deliveryBoyList()
    {

        $userType = UserTypes::where('type_name', env('USER_TYPE_DELIVERY_BOY'))->pluck('id');

        if ($userType->isEmpty()) {
            return DataTables::of([])->make(true);
        }

        $deliveryboys = User::where('type_id', $userType[0] ?? 0)
            ->with([
                'deliveryboy' => function ($q) {
                    $q->select(['id as inv_id', 'user_id', 'family', 'idnumber', 'website', 'dob', 'company', 'id'])->with([
                        'investigations' => function ($query) {
                            $query->select(['id', 'deliveryboy_id', 'investigation_id', 'status']);
                        }, 'invoice' => function ($query) {
                            $query->select(['id', 'investigation_id', 'deliveryboy_id', 'invoice_no']);
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
                'delivery_areas' => function ($q) {
                    $q->select(['area_name', 'hr_area_name']);
                },
            ]);

        return DataTables::of($deliveryboys)
            ->addColumn('family', function ($deliveryboy) {
                return !empty($deliveryboy->deliveryboy->family) ? $deliveryboy->deliveryboy->family : '';
            })
            ->addColumn('idnumber', function ($deliveryboy) {
                return !empty($deliveryboy->deliveryboy->idnumber) ? $deliveryboy->deliveryboy->idnumber : '';
            })
            ->addColumn('website', function ($deliveryboy) {
                return !empty($deliveryboy->deliveryboy->website) ? $deliveryboy->deliveryboy->website : '';
            })
            ->addColumn('deliveryareas', function ($deliveryboy) {
                $specHtml = '';
                if(config('app.locale') == 'hr'){
                    $specArr =  !empty($deliveryboy->delivery_areas) ? array_column($deliveryboy->delivery_areas->toArray(), 'hr_area_name') : '';

                    if ($specArr) {
                        foreach ($specArr as $spec) {
                            $specHtml .= '<span class="badge dt-badge badge-info">' . ucwords($spec) . '</span>&nbsp;';
                        }
                    }
                } else {
                    $specArr =  !empty($deliveryboy->delivery_areas) ? array_column($deliveryboy->delivery_areas->toArray(), 'area_name') : '';

                    if ($specArr) {
                        foreach ($specArr as $spec) {
                            $specHtml .= '<span class="badge dt-badge badge-info">' . ucwords($spec) . '</span>&nbsp;';
                        }
                    }
                }

                return $specHtml;
            })
            ->addColumn('phone', function ($deliveryboy) {
                return !$deliveryboy->userPhones->isEmpty() ? $deliveryboy->userPhones[0]->value : '';
            })
            ->addColumn('address', function ($deliveryboy) {
                return !$deliveryboy->userAddresses->isEmpty() ? $deliveryboy->userAddresses[0]->address1 : '';
            })
            ->editColumn('created_at', function ($client) {
                return Carbon::parse($client->created_at)->format('d/m/Y');
            })
            ->editColumn('status', function ($deliveryboy) {
                if ($deliveryboy->status) {
                    if ($deliveryboy->status == 'approved') {
                        return '<td>
                            <span class="badge dt-badge badge-success">' . trans('form.timeline_status.'.ucwords($deliveryboy->status)) . '</span>
                        </td>';
                    } else if ($deliveryboy->status == 'pending') {
                        return '<td>
                            <span class="badge dt-badge badge-warning">' . trans('form.timeline_status.'.ucwords($deliveryboy->status)) . '</span>
                        </td>';
                    } else if ($deliveryboy->status == 'disabled') {
                        return '<td>
                            <span class="badge dt-badge badge-dark">' . trans('form.timeline_status.'.ucwords($deliveryboy->status)) . '</span>
                        </td>';
                    }
                }

                return "";
            })
            ->addColumn('action', function ($deliveryboy) {
                $btns = "<span class='d-inline-flex'>";
                if (check_perm('deliveryboy_edit')) {
//                    if ($deliveryboy->status == 'pending' || ($deliveryboy->status == 'disabled')) {
//                        $btns .= '<a href="javascript:void(0)" class="dt-btn-action" title="' . trans('general.approve') . '" onclick="changeStatus(\'approved\',' . $deliveryboy->id . ')">
//                            <i class="mdi mdi-account-check mdi-18px"></i>
//                        </a>';
//                    }

                    if ($deliveryboy->status == 'pending' || $deliveryboy->status == 'disabled') {
                        $btns .= '<a href="' . route('deliveryboy.showApproveForm', ['userId' => Crypt::encrypt($deliveryboy->id)]) . '" class="dt-btn-action" title="' . trans('general.approve') . '">
                            <i class="mdi mdi-account-check mdi-18px"></i>
                        </a>';
                    }

                    if ($deliveryboy->status == 'approved') {
                        $btns .= '<a href="' . route('deliveryboy.showApproveForm', ['userId' => Crypt::encrypt($deliveryboy->id)]) . '" class="dt-btn-action" title="' . trans('form.edit_payment_details') . '">
                            <i class="mdi mdi-content-save-edit-outline mdi-18px"></i>
                        </a>';
                    }

                    if ($deliveryboy->status == 'approved') {
                        $btns .= '<a href="javascript:void(0)" class="dt-btn-action" title="' . trans('general.disable') . '" onclick="changeStatus(\'disabled\',' . $deliveryboy->id . ')">
                            <i class="mdi mdi-account-off mdi-18px"></i>
                        </a>';
                    }

                    $btns .= '<a href="' . route('deliveryboy.edit', ['userId' => Crypt::encrypt($deliveryboy->id)]) . '" class="dt-btn-action" title="' . trans('general.edit') . '">
                                <i class="mdi mdi-account-edit mdi-18px"></i>
                            </a>';
                }
                if (check_perm('deliveryboy_delete')) {
                    if(isAdmin() || (count($deliveryboy->deliveryboy->investigations) == 0 && count($deliveryboy->deliveryboy->invoice) == 0)){
                        $btns .= '<a href="javascript:void(0)" id="deleteDeliveryboy" class="dt-btn-action" title="' . trans('general.delete') . '" data-id="' . $deliveryboy->id . '"">
                                <i class="mdi mdi-account-remove mdi-18px"></i>
                            </a>';
                    }
                }
                if (check_perm('deliveryboy_show')) {
                    $btns .= '<a href="' . route('deliveryboy.detail', ['userId' => Crypt::encrypt($deliveryboy->id)]) . '" class="dt-btn-action" title="' . trans('general.view') . '">
                                <i class="mdi mdi-account-details mdi-18px"></i>
                            </a>';
                }
                $btns .= '</span>';

                return $btns;
            })
            ->rawColumns(['deliveryareas', 'status', 'action'])
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('deliveryboys.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userType = UserTypes::where('type_name', env('USER_TYPE_DELIVERY_BOY'))->pluck('id');
        $typeid   = $userType[0];
        $countries = Country::select('id', 'en_name', 'code', 'hr_name')->get();
        $deliveryareas = DeliveryArea::select('area_name', 'id', 'hr_area_name')->orderBy('id', 'ASC')->get();
        $contacttypes = ContactTypes::select('type_name', 'id', 'hr_type_name')->where('type', env('CONTACT_TYPE_DEFAULT'))->orderBy('id', 'ASC')->get();

        return view('deliveryboys.create', compact('typeid', 'countries', 'deliveryareas', 'contacttypes'));
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
    public function deliveryBoyValidator(array $data)
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

    public function deliveryBoyUpdateValidator(array $data, $userId)
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
        $this->deliveryBoyValidator($request->all())->validate();

        try {
            DB::beginTransaction();

            $userData = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'verify_token' => $this->generateVerifyToken(),
                'type_id' => $request->type_id,
            ]);

            $deliveryData = DeliveryBoys::create([
                'user_id' => $userData->id,
                'family' => $request->family,
                'idnumber' => $request->idnumber,
                'website' => $request->website,
                'dob' => date("Y-m-d", strtotime($request->dob)),
                'company' => $request->company,
            ]);

            if ($request->filled('deliveryarea_id')) {
                foreach ($request->input('deliveryarea_id') as $value) {
                    $arr_delarear[] = [
                        'user_id' => $userData->id,
                        'delivery_area_id' => $value,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                DeliveryboyAreas::insert($arr_delarear);
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

            if ($request->filled('name_of_bank')) {
                DeliveryboyBankDetail::create([
                    'deliveryboy_id' => $deliveryData->id,
                    'name' => $request->name_of_bank,
                    'company' => $request->company,
                    'number' => $request->bank_number,
                    'branch_name' => $request->branch_name,
                    'branch_number' => $request->branch_no,
                    'account_no' => $request->account_no,
                ]);
            }

            DB::commit();

            $invtransdata = [
                'event' => 'deliveryboy_created',
                'data' => json_encode(array('data' => array('id' => $userData->id, 'type' => 'deliveryboy_registration'))),
            ];
            InvestigationTransition::addTransion($invtransdata, NULL);

            Mail::to($userData->email)->queue(new EmailVerify($userData));

            return redirect('/deliveryboys')->with('success', trans('form.registration.deliveryboy.new_deliveryboy_added'));
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();

            return redirect('/deliveryboys')->with('warning', trans('general.something_wrong'));
        }
    }

    /**
     * Show the DleiveryBoy Data
     *
     * @param  int  $userid = Crypt::encrypt($userId)
     * @return \Illuminate\Http\Response
     */
    public function show($userId)
    {
        try {
            $userType = UserTypes::where('type_name', env('USER_TYPE_DELIVERY_BOY'))->pluck('id');
            $userId = Crypt::decrypt($userId);
            $typeid = $userType[0];
            $user = User::where('id', '=', $userId)
                ->where('type_id', '=', $typeid)->first();
            if (!$user) {
                return back()->with('warning', trans('general.no_record_found'));
            }
            return view('deliveryboys.show', compact('user'));
        } catch (Exception $exception) {
            return back()->with('warning', trans('form.registration.something_went_wrong'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DeliveryBoys  $deliveryboys
     * @return \Illuminate\Http\Response
     */
    public function edit($userId)
    {
        $userId = Crypt::decrypt($userId);
        $userType = UserTypes::where('type_name', env('USER_TYPE_DELIVERY_BOY'))->pluck('id');
        $typeid = $userType[0];
        $countries = Country::select('id', 'en_name', 'code', 'hr_name')->get();
        $deliveryareas = DeliveryArea::select('area_name', 'id', 'hr_area_name')->orderBy('id', 'ASC')->get();
        $contacttypes = ContactTypes::select('type_name', 'id', 'hr_type_name')->where('type', env('CONTACT_TYPE_DEFAULT'))->orderBy('id', 'ASC')->get();

        $client = User::find($userId);

        $phones = $mobiles = $faxes = $areas = [];

        foreach ($client->userPhones as $phone) {
            if ($phone->type == 'phone') {
                $phones[] = $phone;
            } else if ($phone->type == 'mobile') {
                $mobiles[] = $phone;
            } else {
                $faxes[] = $phone;
            }
        }

        $areas = array_column($client->delivery_areas->toArray(), 'id');

        return view('deliveryboys.edit', compact('typeid', 'countries', 'deliveryareas', 'contacttypes', 'client', 'phones', 'mobiles', 'faxes', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DeliveryBoys  $deliveryboys
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $userId)
    {
        $this->deliveryBoyUpdateValidator($request->all(), $userId)->validate();

        //try {
            DB::beginTransaction();

            $user = User::with('deliveryboy')->find($userId);

            $userData = $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $deliveryboy = $user->deliveryboy;

            $deliData = $deliveryboy->update([
                'user_id' => $userId,
                'family' => $request->family,
                'idnumber' => $request->idnumber,
                'website' => $request->website,
                'dob' => date("Y-m-d", strtotime($request->dob))
            ]);

            $deliveryboyBankDetail = $deliveryboy->deliveryboyBankDetail;

            if ($request->filled('name_of_bank') && !is_null($deliveryboyBankDetail)) {
                $deliveryboyBankDetail->update([
                    'deliveryboy_id' => $deliveryboy->id,
                    'name' => $request->name_of_bank,
                    'company' => $request->company,
                    'number' => $request->bank_number,
                    'branch_name' => $request->branch_name,
                    'branch_number' => $request->branch_no,
                    'account_no' => $request->account_no,
                ]);
            }

            if ($request->filled('deliveryarea_id')) {

                $user->delivery_areas()->detach();

                foreach ($request->input('deliveryarea_id') as $value) {
                    $arr_delarear[] = [
                        'user_id' => $userId,
                        'delivery_area_id' => $value,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                DeliveryboyAreas::insert($arr_delarear);
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
            DB::commit();

            //Mail::to($user->email)->queue(new EmailVerify($userData));

            return redirect('/deliveryboys')->with('success', trans('form.registration.deliveryboy.deliveryboy_updated'));
        // } catch (\Throwable $th) {
        //     Log::error($th->getMessage());
        //     DB::rollBack();

        //     return redirect('/deliveryboys')->with('error', trans('general.something_wrong') . ' ' . $th->getMessage());
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DeliveryBoys  $deliveryboys
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            User::find($id)->delete();

            return response()->json([
                'status' => 'success',
                'message' => trans('form.registration.deliveryboy.deliveryboy_deleted'),
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
                    'message' => trans('form.registration.deliveryboy.deliveryboy_deleted'),
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
                    'message' => trans('form.registration.deliveryboy.confirm_statuschanged'),
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

    /**
     * Show form for approving deliveryboy status
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

        $client_products = $user->deliveryboy->products()->get()->keyBy('id')->toArray();
        return view('deliveryboys.deliveryboy-approval', compact('user', 'products', 'client_products'));
    }

    /**
     * Update deliveryboy details and change status to Approved
     *
     * @param  \Illuminate\Http\Request
     * @param $userId
     * @return mixed
     */
    public function approveClient(Request $request, $userId)
    {
        $user = User::with('deliveryboy')->find($userId);

        if ($request->filled('arr_product')) {
            foreach ($request->input('arr_product') as $key => $value) {
                DeliveryboyProduct::updateOrCreate([
                    'deliveryboy_id' => $user->deliveryboy->id,
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
                'data' => json_encode(array('data' => array('id' => $user->id, 'name' => $user->name, 'type' => 'deliveryboy_approve'))),
            ];
            InvestigationTransition::addTransion($invtransdata, NULL);

            Mail::to($user->email)->queue(new EmailClientApproval($user));
            $success_message = trans('form.client_approval.deliveryboy_approved');
        }

        return redirect('/deliveryboys')->with('success', $success_message);
    }
}
