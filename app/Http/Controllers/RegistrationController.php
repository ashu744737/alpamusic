<?php

namespace App\Http\Controllers;

use App\Client;
use App\ClientCustomer;
use App\ClientBankDetails;
use App\ClientTypes;
use App\ContactTypes;
use App\Country;
use App\InvestigatorBankDetail;
use App\DeliveryArea;
use App\DeliveryboyAreas;
use App\DeliveryboyBankDetail;
use App\DeliveryBoys;
use App\Investigators;
use App\InvestigatorSpecilization;
use App\Mail\EmailVerify;
use App\Providers\RouteServiceProvider;
use App\Specialization;
use App\User;
use App\UserAddress;
use App\UserContact;
use App\UserEmail;
use App\UserPhone;
use App\InvestigationTransition;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class RegistrationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of clients,investigator,delivery boy and external users as well as their
    | validation and creation.
    |
    */

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function clientvalidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed'],
            'g-recaptcha-response' => ['required'],
        ], [
            'g-recaptcha-response.required' => trans('form.captcha_is_required'),
        ]);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $userData = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'verify_token' => $this->generateVerifyToken(),
            'type_id' => $data['type_id'],
        ]);
        $mailVerify = Mail::to($userData->email)->queue(new EmailVerify($userData));
    }

    /**
     * show client registration form.
     *
     * @return \Illuminate\Http\Response
     */
    protected function showclientform()
    {
        $typeid   = 2;
        $countries = Country::select('id', 'en_name', 'code', 'hr_name')->get();
        $clienttypes = ClientTypes::select('id', 'type_name', 'hr_type_name')->orderBy('id', 'ASC')->get();
        $contacttypes = ContactTypes::select('id', 'type_name', 'hr_type_name')->where('type', env('CONTACT_TYPE_DEFAULT'))->orderBy('id', 'ASC')->get();
        $contactContactTypes = ContactTypes::select('id', 'type_name', 'hr_type_name')->where('type', env('CONTACT_TYPE_CONTACT'))->orderBy('id', 'ASC')->get();
        return view('auth.client', compact('typeid', 'countries', 'clienttypes', 'contacttypes', 'contactContactTypes'));
    }

    /**
     * Store a Client Registration Data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function storeClient(Request $request)
    {
        $this->clientvalidator($request->all())->validate();
        try {
            DB::beginTransaction();
            $userData = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'verify_token' => $this->generateVerifyToken(),
                'type_id' => $request->type_id,
                'referred_by' => $request->referred_by,
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
                if(!empty($arr_contacts)){
                    UserContact::insert($arr_contacts);
                }
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
                if(!empty($arr_email)){
                    UserEmail::insert($arr_email);
                }
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
                if(!empty($arr_phones)){
                    UserPhone::insert($arr_phones);
                }
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
                if(!empty($arr_mobile)){
                    UserPhone::insert($arr_mobile);
                }
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
                if(!empty($arr_fax)){
                    UserPhone::insert($arr_fax);
                }
            }

            $customerdata['client_id'] = $clientData->id;
            $customerdata['customer_id'] = $userData->id;
            $custupdate = ClientCustomer::create($customerdata);

            DB::commit();
            
            $invtransdata = [
                'event' => 'client_created',
                'data' => json_encode(array('data' => array('id' => $userData->id, 'type' => 'client_registration'))),
            ];
            InvestigationTransition::addTransion($invtransdata, NULL);

            $mailVerify = Mail::to($userData->email)->queue(new EmailVerify($userData));
            return redirect('/login')->with('status', trans('form.registration.thank_you_for_registering'));
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
            return redirect('/register/client')->with('warning', trans('form.registration.something_went_wrong'));
        }
    }

    /**
     * show investigator registration form.
     *
     * @return \Illuminate\Http\Response
     */
    protected function showinvestigatorform()
    {
        $typeid   = 3;
        $countries = Country::select('id', 'en_name', 'code', 'hr_name')->get();
        $specializations = Specialization::select('name', 'hr_name', 'id')->orderBy('id', 'ASC')->get();
        $contacttypes = ContactTypes::select('id', 'type_name', 'hr_type_name')->where('type', env('CONTACT_TYPE_DEFAULT'))->orderBy('id', 'ASC')->get();
        return view('auth.investigator', compact('typeid', 'countries', 'specializations', 'contacttypes'));
    }

    /**
     * Store a Investigator Registration Data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function storeInvestigator(Request $request)
    {
        $this->clientvalidator($request->all())->validate();
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
            'dob' => date("Y-m-d", strtotime($request->dob)),
            'company' => $request->company,
        ]);

        if ($request->filled('name_of_bank')) {
            InvestigatorBankDetail::create([
                'investigator_id' => $invstData->id,
                'name' => $request->name_of_bank,
                'number' => $request->bank_number,
                'branch_name' => $request->branch_name,
                'branch_number' => $request->branch_no,
                'account_no' => $request->account_no,
                'company' => $request->company,
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
                $address['address_type'] = ((($address['address_type'] == 'Other' || $address['address_type'] == 'Contact') || $address['address_type'] == 'Contact') && $address['other_text'] != '') ? $address['other_text'] : $address['address_type'];
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
            if(!empty($arr_email)){
                UserEmail::insert($arr_email);
            }
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
            if(!empty($arr_phones)){
                UserPhone::insert($arr_phones);
            }
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
            if(!empty($arr_mobile)){
                UserPhone::insert($arr_mobile);
            }
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
            if(!empty($arr_fax)){
                UserPhone::insert($arr_fax);
            }
        }
        $invtransdata = [
            'event' => 'investigator_created',
            'data' => json_encode(array('data' => array('id' => $userData->id, 'type' => 'investigator_registration'))),
        ];
        InvestigationTransition::addTransion($invtransdata, NULL);

        $mailVerify = Mail::to($userData->email)->queue(new EmailVerify($userData));

        return redirect('/login')->with('status', trans('form.registration.thank_you_for_registering'));
    }

    /**
     * show deliveryboy registration form.
     *
     * @return \Illuminate\Http\Response
     */
    protected function showdeliveryboyform()
    {
        $typeid   = 4;
        $countries = Country::select('id', 'en_name', 'code', 'hr_name')->get();
        $deliveryareas = DeliveryArea::select('id', 'area_name','hr_area_name')->orderBy('id', 'ASC')->get();
        $contacttypes = ContactTypes::select('id', 'type_name', 'hr_type_name')->where('type', env('CONTACT_TYPE_DEFAULT'))->orderBy('id', 'ASC')->get();
        return view('auth.deliveryboy', compact('typeid', 'countries', 'deliveryareas', 'contacttypes'));
    }

    /**
     * Store a Delivery Boy Registration Data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function storeDeliveryboy(Request $request)
    {
        $this->clientvalidator($request->all())->validate();
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

            if ($request->filled('address')) {
                foreach ($request->input('address') as $address) {
                    $address['user_type_id'] = $request->type_id;
                    $address['user_id'] = $userData->id;
                    $address['address_type'] = 'Main Office';
                    $address['created_at'] = now();
                    $address['updated_at'] = now();
                    $arr_address[] = $address;
                }
                UserAddress::insert($arr_address);
            }

            if ($request->filled('other_email')) {
                foreach ($request->input('other_email') as $value) {
                    if(empty($value)){
                        continue;
                    }
                    $arr_email[] = [
                        'value' => $value,
                        'user_type_id' => $request->type_id,
                        'user_id' => $userData->id,
                        'email_type'=> 'Main Office',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                if(!empty($arr_email)){
                    UserEmail::insert($arr_email);
                }
            }

            if ($request->filled('other_phone')) {
                foreach ($request->input('other_phone') as $value) {
                    if(empty($value)){
                        continue;
                    }
                    $arr_phones[] = [
                        'value' => $value,
                        'user_type_id' => $request->type_id,
                        'type' => 'phone',
                        'user_id' => $userData->id,
                        'phone_type'=> 'Main Office',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                if(!empty($arr_phones)){
                    UserPhone::insert($arr_phones);
                }   
            }

            if ($request->filled('other_mobile')) {
                foreach ($request->input('other_mobile') as $value) {
                    if(empty($value)){
                        continue;
                    }
                    $arr_mobile[] = [
                        'value' => $value,
                        'user_type_id' => $request->type_id,
                        'type' => 'mobile',
                        'user_id' => $userData->id,
                        'phone_type'=> 'Main Office',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                if(!empty($arr_mobile)){
                    UserPhone::insert($arr_mobile);
                }
            }

            if ($request->filled('other_fax')) {
                foreach ($request->input('other_fax') as $value) {
                    if(empty($value)){
                        continue;
                    }
                    $arr_fax[] = [
                        'value' => $value,
                        'user_type_id' => $request->type_id,
                        'type' => 'fax',
                        'user_id' => $userData->id,
                        'phone_type'=> 'Main Office',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                if(!empty($arr_fax)){
                    UserPhone::insert($arr_fax);
                }
            }

            if ($request->filled('name_of_bank')) {
                DeliveryboyBankDetail::create([
                    'deliveryboy_id' => $deliveryData->id,
                    'name' => $request->name_of_bank,
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
            $mailVerify = Mail::to($userData->email)->queue(new EmailVerify($userData));
            return redirect('/login')->with('status', trans('form.registration.thank_you_for_registering'));
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
            return redirect('/register/deliveryboy')->with('warning', trans('form.registration.something_went_wrong'));
        }
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
     * Handle a email verification for the application.
     * @param mixed $token
     */
    public function userVerify($token)
    {
        $user_verify = User::getUserveryfyByToken($token);
        if (isset($user_verify)) {
            if (empty($user_verify->email_verified_at)) {
                User::where('id', $user_verify->id)->update(['email_verified_at' => date('Y-m-d H:i:s'), 'status' => 'pending']);
                $status = trans('form.registration.email_id_verify_successfully');
            } else {
                $status = trans('form.registration.email_already_verified');
            }
            return redirect('/login')->with('status', $status);
        } else {
            return redirect('/login')->with('status', trans('form.registration.sorry_email_not_verified'));
        }
    }
}
