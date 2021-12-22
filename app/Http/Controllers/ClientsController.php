<?php

namespace App\Http\Controllers;

use App\Client;
use App\ClientCustomer;
use App\ClientProduct;
use App\ClientTypes;
use App\ContactTypes;
use App\Country;
use App\Helpers\AppHelper;
use App\Investigations;
use App\Invoice;
use App\Mail\EmailClientApproval;
use App\Mail\EmailVerify;
use App\PaymentMode;
use App\PaymentTerm;
use App\Product;
use App\User;
use App\UserAddress;
use App\UserContact;
use App\UserEmail;
use App\UserPhone;
use App\UserTypes;
use App\InvestigationTransition;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Session;
use View;
use DB, Log;
use Illuminate\Support\Facades\DB as FacadesDB;

class ClientsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Generate and return listing of the clients
     * @return mixed
     * @throws \Exception
     */
    public function clientList(Request $request)
    {
        $data = $request->all();
        //        $userType = UserTypes::where('type_name', env('USER_TYPE_CLIENT'))->pluck('id');
        //
        //        if ($userType->isEmpty()) {
        //            return DataTables::of([])->make(true);
        //        }
        // print_r($data['order']);die;
        $clients = User::whereHas(
            'user_type',
            function ($query) {
                return $query->where('type_name', env('USER_TYPE_CLIENT'));
            }
        )
            ->with([
                'client' => function ($q) {
                    $q->select(['id', 'user_id', 'client_type_id', 'printname', 'legal_entity_no', 'website']);
                },
                'client.client_type' => function ($q) {
                    $q->select(['id', 'type_name', 'hr_type_name']);
                },
                'userAddresses' => function ($q) {
                    $q->select(['id', 'user_id', 'address1', 'address2', 'street', 'city', 'state', 'zipcode']);
                },
                'userEmails' => function ($q) {
                    $q->select(['id', 'user_id', 'value']);
                },
                'userPhones' => function ($q) {
                    $q->select(['id', 'user_id', 'value', 'type']);
                },
            ]);

        return DataTables::of($clients)
            ->addColumn('type_name', function ($client) {
                if(config('app.locale') == 'hr') {
                    return !empty($client->client->client_type->type_name) ? $client->client->client_type->hr_type_name : '';
                } else {
                    return !empty($client->client->client_type->type_name) ? $client->client->client_type->type_name : '';
                }
            })
            ->addColumn('printname', function ($client) {
                return !empty($client->client->printname) ? $client->client->printname : '';
            })
            ->addColumn('legal_entity_no', function ($client) {
                return !empty($client->client->legal_entity_no) ? $client->client->legal_entity_no : '';
            })
            ->addColumn('website', function ($client) {
                return !empty($client->client->website) ? $client->client->website : '';
            })
            ->addColumn('phone', function ($client) {
                return !$client->userPhones->isEmpty() ? $client->userPhones[0]->value : '';
            })
            ->addColumn('address', function ($client) {
                return !$client->userAddresses->isEmpty() ? $client->userAddresses[0]->address1 : '';
            })
            ->editColumn('created_at', function ($client) {
                return Carbon::parse($client->created_at)->format('d/m/Y');
            })
            ->editColumn('status', function ($client) {
                if ($client->status) {
                    if ($client->status == 'approved') {
                        return '<td>
                            <span class="badge dt-badge badge-success">' . trans('form.timeline_status.'.ucwords($client->status)) . '</span>
                        </td>';
                    } else if ($client->status == 'pending') {
                        return '<td>
                            <span class="badge dt-badge badge-warning">' . trans('form.timeline_status.'.ucwords($client->status)) . '</span>
                        </td>';
                    } else if ($client->status == 'disabled') {
                        return '<td>
                            <span class="badge dt-badge badge-dark">' . trans('form.timeline_status.'.ucwords($client->status)) . '</span>
                        </td>';
                    }
                }

                return "";
            })
            ->addColumn('action', function ($client) {
                $btns = "<span class='d-inline-flex'>";
                if (check_perm('client_edit')) {



                    if ($client->status == 'pending' || ($client->status == 'disabled')) {
                        $btns .= '<a href="' . route('client.showApproveForm', ['userId' => Crypt::encrypt($client->id)]) . '" class="dt-btn-action" title="' . trans('general.approve') . '">
                            <i class="mdi mdi-account-check mdi-18px"></i>
                        </a>';
                    }

                    if ($client->status == 'approved') {
                        $btns .= '<a href="' . route('client.showApproveForm', ['userId' => Crypt::encrypt($client->id)]) . '" class="dt-btn-action" title="' . trans('form.edit_payment_details') . '">
                            <i class="mdi mdi-content-save-edit-outline mdi-18px"></i>
                        </a>';
                    }

                    if ($client->status == 'approved') {
                        $btns .= '<a href="javascript:void(0)" class="dt-btn-action" title="' . trans('general.disable') . '" onclick="changeStatus(\'disabled\',' . $client->id . ')">
                            <i class="mdi mdi-account-off mdi-18px"></i>
                        </a>';
                    }

                    $btns .= '<a href="' . route('client.edit', ['userId' => Crypt::encrypt($client->id)]) . '" class="dt-btn-action" title="' . trans('general.edit') . '">
                                <i class="mdi mdi-account-edit mdi-18px"></i>
                            </a>';
                }
                if (check_perm('client_delete')) {
                    $btns .= '<a href="javascript:void(0)" id="deleteClient" class="dt-btn-action" title="' . trans('general.delete') . '" data-id="' . $client->id . '"">
                                <i class="mdi mdi-account-remove mdi-18px"></i>
                            </a>';
                }
                if (check_perm('client_show')) {
                    $btns .= '<a href="' . route('client.detail', ['userId' => Crypt::encrypt($client->id)]) . '"class="dt-btn-action" title="' . trans('general.view') . '">
                                <i class="mdi mdi-account-details mdi-18px"></i>
                            </a>';
                }
                $btns .= '</span>';

                return $btns;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('clients.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userType = UserTypes::where('type_name', env('USER_TYPE_CLIENT'))->pluck('id');
        $typeid   = $userType[0];
        $countries = Country::select('id', 'en_name', 'code', 'hr_name')->get();
        $clienttypes = ClientTypes::select('id', 'type_name', 'hr_type_name')->orderBy('id', 'ASC')->get();
        $contacttypes = ContactTypes::select('id', 'type_name', 'hr_type_name')->where('type', env('CONTACT_TYPE_DEFAULT'))->orderBy('id', 'ASC')->get();
        $contactContactTypes = ContactTypes::select('id', 'type_name', 'hr_type_name')->where('type', env('CONTACT_TYPE_CONTACT'))->orderBy('id', 'ASC')->get();
        return view('clients.create', compact('typeid', 'countries', 'clienttypes', 'contacttypes', 'contactContactTypes'));
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
    public function clientValidator(array $data)
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

    public function clientUpdateValidator(array $data, $userId)
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
        try{

            $this->clientValidator($request->all())->validate();

            $userData = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'verify_token' => $this->generateVerifyToken(),
                'type_id' => $request->type_id,
            ]);

            $clientData = Client::create([
                'user_id' => $userData->id,
                'client_type_id' => $request->client_type_id,
                'printname' => $request->printname,
                'legal_entity_no' => $request->legal_entity_no,
                'website' => $request->website,

            ]);

            $arr_address = $arr_contacts = $arr_email = $arr_phones = $arr_mobile = $arr_fax = [];

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

            if ($request->filled('contacts')) {
                foreach ($request->input('contacts') as $contact) {
                    if(empty($contact['phone'])){
                        continue;
                    }
                    $contypeid = ContactTypes::where('type_name', $contact['contact_type_id'])->pluck('id');
                    $fullname = explode(" ", $request->name);
                    $contact['first_name'] = !empty($fullname[0]) ? $fullname[0] : NULL;
                    $contact['last_name'] = !empty($fullname[1]) ? $fullname[1] : NULL;
                    $contact['is_default'] = isset($contact['is_default']) ? 1 : 0;
                    $contact['user_type_id'] = $request->type_id;
                    $contact['contact_type'] = (($contact['contact_type_id'] == 'Other' || $contact['contact_type_id'] == 'Contact') && $contact['other_text'] != '') ? $contact['other_text'] : $contact['contact_type_id'];
                    $contact['contact_type_id'] = $contypeid[0];
                    $contact['user_id'] = $userData->id;
                    $contact['primary_email'] = $contact['primary_email'];
                    $contact['secondary_email'] = $contact['secondary_email'];
                    unset($contact['other_text']);
                    $arr_contacts[] = $contact;
                }
                UserContact::insert($arr_contacts);
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
                    if(empty($contact['fax'])){
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

            $customerdata['client_id'] = $clientData->id;
            $customerdata['customer_id'] = $userData->id;
            $custupdate = ClientCustomer::create($customerdata);

            $invtransdata = [
                'event' => 'client_created',
                'data' => json_encode(array('data' => array('id' => $userData->id, 'type' => 'client_registration'))),
            ];
            InvestigationTransition::addTransion($invtransdata, NULL);

            $mailVerify = Mail::to($userData->email)->queue(new EmailVerify($userData));

            return redirect('/clients')->with('success', trans('form.registration.client.new_client_added'));

        } catch (Exception $exception) {
            return back()->with('warning', trans('form.registration.something_went_wrong'));
        }
    }

    /**
     * Show the Clients Data
     *
     * @param  int  $userid = Crypt::encrypt($userId)
     * @return \Illuminate\Http\Response
     */
    public function show($userId)
    {
        try {
            $userType = UserTypes::where('type_name', env('USER_TYPE_CLIENT'))->pluck('id');
            $userId = Crypt::decrypt($userId);
            $typeid   = $userType[0];
            $user = User::where('id', '=', $userId)
                ->where('type_id', '=', $typeid)->first();
            if (!$user) {
                return back()->with('warning', trans('general.no_record_found'));
            }
            return view('clients.show', compact('user'));
        } catch (Exception $exception) {
            return back()->with('warning', trans('form.registration.something_went_wrong'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Clients  $clients
     * @return \Illuminate\Http\Response
     */
    public function edit($userId)
    {
        $userId = Crypt::decrypt($userId);
        $userType = UserTypes::where('type_name', env('USER_TYPE_CLIENT'))->pluck('id');
        $typeid   = $userType[0];
        $countries = Country::select('id', 'en_name', 'code', 'hr_name')->get();
        $clienttypes = ClientTypes::select('type_name', 'hr_type_name', 'id')->orderBy('id', 'ASC')->get();
        $contacttypes = ContactTypes::select('type_name', 'id', 'hr_type_name')->where('type', env('CONTACT_TYPE_DEFAULT'))->orderBy('id', 'ASC')->get();
        $contactContactTypes = ContactTypes::select('type_name', 'id', 'hr_type_name')->where('type', env('CONTACT_TYPE_CONTACT'))->orderBy('id', 'ASC')->get();

        $client = User::find($userId);

        $phones = $mobiles = $faxes = [];

        foreach ($client->userPhones as $phone) {
            if ($phone->type == 'phone') {
                $phones[] = $phone;
            } else if ($phone->type == 'mobile') {
                $mobiles[] = $phone;
            } else {
                $faxes[] = $phone;
            }
        }

        return view('clients.edit', compact('typeid', 'countries', 'clienttypes', 'contacttypes', 'client', 'phones', 'mobiles', 'faxes', 'contactContactTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Clients  $clients
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $userId)
    {
        $this->clientUpdateValidator($request->all(), $userId)->validate();

        $user = User::with('client')->find($userId);

        $userData = $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $client = $user->client;
        $clientData = $client->update([
            'user_id' => $userId,
            'client_type_id' => $request->client_type_id,
            'printname' => $request->printname,
            'legal_entity_no' => $request->legal_entity_no,
            'website' => $request->website,

        ]);


        $arr_address = $arr_contacts = $arr_email = $arr_phones = $arr_mobile = $arr_fax = [];

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
        
        if ($request->filled('contacts')) {
            UserContact::where('user_id', $userId)->delete();
            foreach ($request->input('contacts') as $contact) {
                if(empty($contact['phone'])){
                    continue;
                }
                $contypeid = ContactTypes::where('type_name', $contact['contact_type_id'])->pluck('id');
                $fullname = explode(" ", $request->name);
                $contact['first_name'] = !empty($fullname[0]) ? $fullname[0] : NULL;
                $contact['last_name'] = !empty($fullname[1]) ? $fullname[1] : NULL;
                $contact['is_default'] = isset($contact['is_default']) ? 1 : 0;
                $contact['user_type_id'] = $request->type_id;
                $contact['contact_type'] = (($contact['contact_type_id'] == 'Other' || $contact['contact_type_id'] == 'Contact') && $contact['other_text'] != '') ? $contact['other_text'] : $contact['contact_type_id'];
                $contact['contact_type_id'] = $contypeid[0];
                $contact['user_id'] = $userId;
                $contact['primary_email'] = $contact['primary_email'];
                $contact['secondary_email'] = $contact['secondary_email'];
                unset($contact['other_text']);
                $arr_contacts[] = $contact;
            }
            UserContact::insert($arr_contacts);
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

        return redirect('/clients')->with('success', trans('form.registration.client.client_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Clients  $clients
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $isInv = Investigations::where('user_id', $id)->exists();
            $clientId = Client::where('user_id', $id)->pluck('id')->first();
            $isInvoice = Invoice::where('client_id', $clientId)->exists();
            
            if($isInv || $isInvoice){
                return response()->json([
                    'status' => 'warning',
                    'message' => trans('form.registration.client.no_client_deleted'),
                ]);
            } else {
                User::find($id)->delete();

                return response()->json([
                    'status' => 'success',
                    'message' => trans('form.registration.client.client_deleted'),
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


    public function deleteMultiple(Request $request)
    {

        try {
            if (!empty($request->ids)) {
                User::whereIn('id', $request->ids)->delete();

                return response()->json([
                    'status' => 'success',
                    'message' => trans('form.registration.client.client_deleted'),
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

                $user->save();  // Update the data

                return response()->json([
                    'status' => 'success',
                    'message' => trans('form.registration.client.confirm_statuschanged'),
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
     * Show form for approving client status
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

        $payment_modes = PaymentMode::orderBy('mode_name')->get();
        $payment_terms = PaymentTerm::orderBy('term_name')->get();

        $client_products = $user->client->products()->get()->keyBy('id')->toArray();
        return view('clients.client-approval', compact('user', 'products', 'payment_modes', 'payment_terms', 'client_products'));
    }

    /**
     * Update client details and change status to Approved
     *
     * @param  \Illuminate\Http\Request
     * @param $userId
     * @return mixed
     */
    public function approveClient(Request $request, $userId)
    {
        $user = User::with('client')->find($userId);

        $this->clientApprovalValidator($request->all())->validate();

        $client = $user->client;

        $client->update($request->only(['credit_limit', 'payment_mode_id', 'payment_term_id']));

        if ($request->filled('arr_product')) {
            foreach ($request->input('arr_product') as $key => $value) {
                ClientProduct::updateOrCreate([
                    'client_id' => $user->client->id,
                    'product_id' => $key
                ], ['price' => $value]);
            }
        }

        $success_message = trans('form.client_approval.payment_details_updated_successfully');

        if ($user->status !== 'approved') {
            $user->status = 'approved';
            $user->approved_at = Carbon::now();
            $user->approved_by = Auth::user()->id;
            $user->save();

            Mail::to($user->email)->queue(new EmailClientApproval($user));

            $success_message = trans('form.client_approval.client_approved');
        }

        $invtransdata = [
            'event' => 'user_approve',
            'data' => json_encode(array('data' => array('id' => $user->id, 'name' => $user->name, 'type' => 'client_approve'))),
        ];
        InvestigationTransition::addTransion($invtransdata, NULL);

        return redirect('/clients')->with('success', $success_message);
    }

    public function clientApprovalValidator(array $data)
    {
        return Validator::make($data, [
            'credit_limit' => ['required', 'numeric'],
            'payment_term_id' => ['required', 'integer', 'exists:payment_terms,id'],
            'payment_mode_id' => ['required', 'integer', 'exists:payment_modes,id'],
        ]);
    }



    public function getProducts(Request $request)
    {

        if (!empty($request->userId)) {
            $clientId = Client::where('user_id', $request->userId)->pluck('id')->first();
            $products = ClientProduct::with('product:id,name,is_delivery,delivery_cost,spouse_cost')
                ->whereHas('product')
                ->where('client_id', $clientId)
                ->where('price', '>', 0)->get();

            return response()->json([
                'status' => 'success',
                'message' => "",
                'data' => $products,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => trans('general.something_wrong'),
            ]);
        }
    }

    /**
     * Get Currently Client Available Credit 
     *
     * @param  \Illuminate\Http\Request
     * @param $userId
     * @return mixed
     */
    public function getCredit(Request $request)
    {
        if (!empty($request->userId)) {
            $invId = isset($request->invid) && !empty($request->invid) ? $request->invid : null;
            $totavailcredit = AppHelper::getUsersAvailableCredit($request->userId, $invId);

            return response()->json([
                'status' => 'success',
                'message' => "",
                'credit' => $totavailcredit,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => trans('general.something_wrong'),
            ]);
        }
    }

    public function clientAutocomplete(Request $request)
    {
        $clientUsers = [];

        if ($request->has('q')) {
            $search = $request->q;

            $clientUsers = User::whereHas(
                'user_type',
                function ($query) {
                    $query->where('type_name', env('USER_TYPE_CLIENT'));
                }
            )
                ->where('name', 'LIKE', "%$search%")
                ->select("id", "name")
                ->orderBy('name', 'asc')
                ->get();
        }
        return response()->json($clientUsers);
    }

    /**
     * this function is common for add add/edit views as per request .
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function customerDataAppends(Request $request)
    {

        $type = $request->type;

        if ($type == 'checkData') {
            $clientId = Client::where('user_id', $request->id)->first();
            //$customer = ClientCustomer::where('client_id', $clientId->id)->get();
            $customer = DB::table('client_customers')
                ->select('users.name', 'client_customers.customer_id as id')
                ->leftjoin('users', 'users.id', '=', 'client_customers.customer_id')

                ->where('client_customers.client_id', $clientId->id)
                ->where('client_customers.deleted_at', NULL)
                ->get();
            if (count($customer) > 0) {
                return response()->json(
                    array(
                        'status'        => 1,
                        'data' => $customer,
                        'clientname' => $clientId->user->name,
                    )
                );
            } else {
                return response()->json(
                    array(
                        'status'        => 0,
                        'data' => $customer,
                        'clientname' => $clientId->user->name,
                    )
                );
            }
        } else {
            if ($request->modeltype == 'add') {
                $client = Client::find($request->id);
                $customers = User::leftjoin('user_types', 'user_types.id', '=', 'users.type_id')
                ->leftjoin('client_customers', 'client_customers.customer_id', '=', 'users.id')
                ->join('clients', 'clients.user_id', '=', 'users.id')
                ->where('user_types.type_name', '=', env('USER_TYPE_CLIENT'))
                ->where('users.status',  'approved')
                ->whereNull('users.deleted_at')
                ->whereNotIn('users.id', DB::table('client_customers')->where('client_id', $request->id)->whereNull('deleted_at')->pluck('customer_id'))
                ->pluck('users.name', 'users.id');


                $returnHTML = view('clients.clientcustomerajaxmodel', compact('type', 'client', 'customers'))->render();
                return response()->json(
                    array(
                        'status'        => 1,
                        'html'          => $returnHTML
                    )
                );
            } else {
                $client = Client::find($request->id);
                if ($request->type == 'editcustomer') {
                    $customer = ClientCustomer::where('id', $request->id)->first();
                    $customers = DB::table('users')
                        ->leftjoin('user_types', 'user_types.id', '=', 'users.type_id')
                        ->leftjoin('client_customers', 'client_customers.customer_id', '=', 'users.id')
                        ->where('user_types.type_name', '=', env('USER_TYPE_CLIENT'))
                        ->where('users.status',  'approved')
                        ->whereNotIn('users.id', DB::table('client_customers')->where('client_id', $request->id)->where('deleted_at', NULL)->pluck('customer_id'))
                        ->pluck('users.name', 'users.id');
                    $returnHTML = view('clients.clientcustomerajaxmodel', compact('type', 'customer', 'customers'))->render();
                    return response()->json(
                        array(
                            'status'        => 1,
                            'html'          => $returnHTML
                        )
                    );
                }
            }
        }
    }

    /**
     * this function is common for Update/Add/Delete the  data as per requested .
     * ADD/UPDATE/DELETE Customers
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function customerDataUpdate(Request $request)
    {
        $type = $request->type;
        if ($request->optype == 'delete') {
            $client = Client::find($request->clientid);
            if ($type == 'viewcustomer') {
                $delcust = ClientCustomer::where('id', $request->id)->first();
                $delcust->delete();
                $user = $client->user;
                $returnHTML = view('clients.clientcustomerajaxmodel', compact('type', 'user'))->render();
                return response()->json(
                    array(
                        'status'        => 1,
                        'html'          => $returnHTML,
                        'msg'          => trans("form.my_profile.deleted_successfully"),
                    )
                );
            }
        } else {

            if ($request->type == 'viewcustomer') {
                if ($request->optype == 'add') {

                    $customerdata['client_id'] = $request->clientid;
                    $customerdata['customer_id'] = $request->customerid;
                    $custupdate = ClientCustomer::create($customerdata);
                    $client = Client::find($request->clientid);
                    $user = $client->user;
                } else {

                    $customerdata['customer_id'] = $request->customerid;
                    $client = Client::find($request->clientid);
                    $custupdate = ClientCustomer::where('id', '=', $request->id)
                        ->update($customerdata);
                    $user = $client->user;
                }
                $returnHTML = view('clients.clientcustomerajaxmodel', compact('type', 'user'))->render();
                if (!$custupdate) {
                    return response()->json(
                        array(
                            'status'        => 2,
                            'msg'          => trans('form.registration.something_went_wrong'),
                        )
                    );
                }
            }
            return response()->json(
                array(
                    'status'        => 1,
                    'html'          => $returnHTML,
                    'msg'          => ($request->optype == 'add') ? trans("form.my_profile.added_successfully") : trans("form.my_profile.updated_successfully"),
                )
            );
        }
    }
}
