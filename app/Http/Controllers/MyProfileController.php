<?php

namespace App\Http\Controllers;

use App\Client;
use App\ClientTypes;
use App\ContactTypes;
use App\Country;
use App\DeliveryArea;
use App\DeliveryboyAreas;
use App\InvestigatorSpecilization;
use App\Specialization;
use App\User;
use App\UserAddress;
use App\UserContact;
use App\UserEmail;
use App\UserPhone;
use App\UserTypes;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class MyProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show Profile Page for particaular user.
     *
     * @return \Illuminate\Http\Response
     */
    public function showMyProfile()
    {
        $userType = UserTypes::where('id', auth()->user()->type_id)->pluck('type_name');
        $userType = $userType[0];
        $user = User::where('id', '=', auth()->user()->id)
            ->where('type_id', '=', auth()->user()->type_id)->first();
        $countries = Country::select('id', 'en_name', 'code', 'hr_name')->get();
        $contacttypes = ContactTypes::select('type_name', 'id', 'hr_type_name')->where('type', env('CONTACT_TYPE_DEFAULT'))->orderBy('id', 'ASC')->get();
        if (!$user) {
            return back()->with('warning', trans('general.no_record_found'));
        }
        if ($userType == env('USER_TYPE_CLIENT')) {
            $clienttypes = ClientTypes::select('type_name', 'id', 'hr_type_name')->orderBy('id', 'ASC')->get();
            $contactContactTypes = ContactTypes::select('type_name', 'id', 'hr_type_name')->where('type', env('CONTACT_TYPE_CONTACT'))->orderBy('id', 'ASC')->get();
            return view('myprofile.clientprofile', compact('clienttypes', 'user', 'countries', 'contacttypes', 'contactContactTypes'));
        } else  if ($userType == env('USER_TYPE_INVESTIGATOR')) {
            $specializations = Specialization::select('name', 'id', 'hr_name')->orderBy('id', 'ASC')->get();
            $specs = array_column($user->specializations->toArray(), 'id');
            return view('myprofile.investigatorprofile', compact('specs', 'specializations', 'user', 'countries', 'contacttypes'));
        } else if ($userType == env('USER_TYPE_DELIVERY_BOY')) {
            $deliveryareas = DeliveryArea::select('area_name', 'id', 'hr_area_name')->orderBy('id', 'ASC')->get();
            $areas = array_column($user->delivery_areas->toArray(), 'id');
            return view('myprofile.deliveryboyprofile', compact('areas', 'deliveryareas', 'user', 'countries', 'contacttypes'));
        } else if ($userType == env('USER_TYPE_ADMIN')) {
            return view('myprofile.adminprofile', compact('user'));
        } else {
            return view('myprofile.adminprofile', compact('user'));
        }
    }

    function checkValidation(Request $request)
    {
    }
    /**
     * Update the Profile data as per get specific request from ajax.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function updatePersonalDetails(Request $request)
    {
        try {
            $userType = UserTypes::where('id', auth()->user()->type_id)->pluck('type_name');
            $userType = $userType[0];
            $user = User::find(auth()->user()->id);
            $type = $request->type;
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => ['required', 'string', 'email', 'max:255'],
            ]);
            if ($validator->fails()) {
                return response()->json(
                    array(
                        'status'        => 2,
                        'msg'          => implode(", ", $validator->errors()->all()),
                    )
                );
                exit;
            }

            $userData = $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
            if ($userType == env('USER_TYPE_CLIENT')) {
                $validator = Validator::make($request->all(), [
                    'printname' => 'required',
                    'legal_entity_no' => 'required',

                ]);
                if ($validator->fails()) {
                    return response()->json(
                        array(
                            'status'        => 2,
                            'msg'          => implode(", ", $validator->errors()->all()),
                        )
                    );
                    exit;
                }
                $client = $user->client;
                $client->update($request->only(['printname', 'legal_entity_no', 'website', 'client_type_id']));
            } else  if ($userType == env('USER_TYPE_INVESTIGATOR')) {
                $investigator = $user->investigator;
                $investigator->update([
                    'family' => $request->family,
                    'idnumber' => $request->idnumber,
                    'website' => $request->website,
                    'dob' => date("Y-m-d", strtotime($request->dob))
                ]);
                if ($request->filled('specializations')) {
                    $user->specializations()->detach();
                    foreach ($request->input('specializations') as $value) {
                        $arr_specs[] = [
                            'user_id' => auth()->user()->id,
                            'specialization_id' => $value,
                        ];
                    }
                    InvestigatorSpecilization::insert($arr_specs);
                }
            } else if ($userType == env('USER_TYPE_DELIVERY_BOY')) {
                $user->deliveryboy->update([
                    'family' => $request->family,
                    'idnumber' => $request->idnumber,
                    'website' => $request->website,
                    'dob' => date("Y-m-d", strtotime($request->dob))
                ]);
                if ($request->filled('deliveryarea_id')) {

                    $user->delivery_areas()->detach();

                    foreach ($request->input('deliveryarea_id') as $value) {
                        $arr_delarear[] = [
                            'user_id' => auth()->user()->id,
                            'delivery_area_id' => $value,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                    DeliveryboyAreas::insert($arr_delarear);
                }
            }
            if (!$userData) {
                return response()->json(
                    array(
                        'status'        => 2,
                        'msg'          => trans('form.registration.something_went_wrong'),
                    )
                );
            } else {
                $user = User::where('id', '=', auth()->user()->id)
                    ->where('type_id', '=', auth()->user()->type_id)->first();
                if (!$user) {
                    return response()->json(
                        array(
                            'status'        => 2,
                            'msg'          => trans('general.no_record_found'),
                        )
                    );
                }
                $returnHTML = view('myprofile.clientajax.personaldetail', compact('user', 'type'))->render();
                return response()->json(
                    array(
                        'status'        => 1,
                        'html'          => $returnHTML,
                        'msg'          => trans("form.my_profile.your_profile_updated"),
                    )
                );
            }
        } catch (Exception $exception) {
            return response()->json(
                array(
                    'status'        => 2,
                    'msg'          => trans('form.registration.something_went_wrong'),
                )
            );
        }
    }

    /**
     * Update the Bank details data as per get specific request from ajax.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function updateBankDetails(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name_of_bank' => 'required',
                'company' => ['required', 'string'],
                'bank_number' => ['required', 'string'],
                'branch_name' => ['required', 'string'],
                'branch_no' => ['required', 'string'],
                'account_no' => ['required', 'string'],
            ]);
            if ($validator->fails()) {
                return response()->json(
                    array(
                        'status'        => 2,
                        'msg'          => implode(", ", $validator->errors()->all()),
                    )
                );
                exit;
            }
            $user = User::find(auth()->user()->id);
            $type = $request->type;
            $userType = UserTypes::where('id', auth()->user()->type_id)->pluck('type_name');
            $userType = $userType[0];
            if ($userType == env('USER_TYPE_INVESTIGATOR')) {
                $investigator = $user->investigator;
                $updatbankdata = $investigator->investigatorBankDetail->update([
                    'name' => $request->name_of_bank,
                    'company' => $request->company,
                    'number' => $request->bank_number,
                    'branch_name' => $request->branch_name,
                    'branch_number' => $request->branch_no,
                    'account_no' => $request->account_no,
                ]);
            } else if ($userType == env('USER_TYPE_DELIVERY_BOY')) {
                $updatbankdata = $user->deliveryboy->deliveryboyBankDetail->update([
                    'name' => $request->name_of_bank,
                    'company' => $request->company,
                    'number' => $request->bank_number,
                    'branch_name' => $request->branch_name,
                    'branch_number' => $request->branch_no,
                    'account_no' => $request->account_no,
                ]);
            }
            if (!$updatbankdata) {
                return response()->json(
                    array(
                        'status'        => 2,
                        'msg'          => trans('form.registration.something_went_wrong'),
                    )
                );
            } else {
                $user = User::where('id', '=', auth()->user()->id)
                    ->where('type_id', '=', auth()->user()->type_id)->first();
                if (!$user) {
                    return response()->json(
                        array(
                            'status'        => 2,
                            'msg'          => trans('general.no_record_found'),
                        )
                    );
                }
                $returnHTML = view('myprofile.clientajax.bankdetails', compact('user', 'type'))->render();
                return response()->json(
                    array(
                        'status'        => 1,
                        'html'          => $returnHTML,
                        'msg'          => trans("form.my_profile.your_bank_updated"),
                    )
                );
            }
        } catch (Exception $exception) {
            return response()->json(
                array(
                    'status'        => 2,
                    'msg'          => trans('form.registration.something_went_wrong'),
                )
            );
        }
    }

    /**
     * Check Alrady Email or not from ajax Request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function checkemail(Request $request)
    {
        if ($request->email != auth()->user()->email) {
            $user = User::where('email', '=', $request->email)->first();
            if (!$user) {
                return Response::json(array('msg' => 'false'));
            }
            return Response::json(array('msg' => 'true'));
        } else {
            return Response::json(array('msg' => 'false'));
        }
    }

    /**
     * this function is common for add add/edit views as per request .
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function contactsDataAppends(Request $request)
    {

        $user = User::find(auth()->user()->id);
        $type = $request->type;
        $contacttypes = ContactTypes::select('id', 'type_name', 'hr_type_name')->where('type', env('CONTACT_TYPE_DEFAULT'))->orderBy('id', 'ASC')->get()->toarray();
        
        if ($request->modeltype == 'add') {
            if ($type == 'address') {
                $countries = Country::select('id', 'en_name', 'code', 'hr_name')->get();
                $returnHTML = view('myprofile.clientajax.addajaxmodel', compact('contacttypes', 'type', 'countries'))->render();
            } else if ($type == 'contact') {
                $countries = Country::select('id', 'en_name', 'code', 'hr_name')->get();
                $contacttypes = ContactTypes::select('id', 'type_name', 'hr_type_name')->where('type', env('CONTACT_TYPE_CONTACT'))->orderBy('id', 'ASC')->get()->toarray();
                $returnHTML = view('myprofile.clientajax.addajaxmodel', compact('contacttypes', 'type', 'countries'))->render();
            } else {
                $returnHTML = view('myprofile.clientajax.addajaxmodel', compact('contacttypes', 'type'))->render();
            }
        } else {

            if ($request->type == 'phones') {

                $phone = UserPhone::where('type', '=', 'phone')
                    ->where('id', '=', $request->id)->first();
                $returnHTML = view('myprofile.clientajax.editajaxmodel', compact('contacttypes', 'type', 'phone'))->render();
            }
            if ($request->type == 'mobiles') {

                $mobile = UserPhone::where('type', '=', 'mobile')
                    ->where('id', '=', $request->id)->first();
                $returnHTML = view('myprofile.clientajax.editajaxmodel', compact('contacttypes', 'type', 'mobile'))->render();
            }
            if ($request->type == 'emails') {

                $email = UserEmail::where('id', '=', $request->id)->first();

                $returnHTML = view('myprofile.clientajax.editajaxmodel', compact('contacttypes', 'type', 'email'))->render();
            }
            if ($request->type == 'faxes') {
                $fax = UserPhone::where('type', '=', 'fax')
                    ->where('id', '=', $request->id)->first();


                $returnHTML = view('myprofile.clientajax.editajaxmodel', compact('contacttypes', 'type', 'fax'))->render();
            }
            if ($request->type == 'address') {
                $address = UserAddress::where('id', '=', $request->id)->first();
                $countries = Country::select('id', 'en_name', 'code', 'hr_name')->get();
                $returnHTML = view('myprofile.clientajax.editajaxmodel', compact('contacttypes', 'type', 'address', 'countries'))->render();
            }
            if ($request->type == 'contact') {
                $contacttypes = ContactTypes::select('id', 'type_name', 'hr_type_name')->where('type', env('CONTACT_TYPE_CONTACT'))->orderBy('id', 'ASC')->get()->toarray();
                $contact = UserContact::where('id', '=', $request->id)->first();
                $returnHTML = view('myprofile.clientajax.editajaxmodel', compact('contacttypes', 'type', 'contact'))->render();
            }
        }

        return response()->json(
            array(
                'status'        => 1,
                'html'          => $returnHTML
            )
        );
    }

    /**
     * this function is common for Update/Add/Delete the  data as per requested .
     * ADD/UPDATE/DELETE Phones,Fax,Mobile,Email,Address,Contacts
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function contactsDataUpdate(Request $request)
    {

        $user = User::find(auth()->user()->id);
        $type = $request->type;
        $contacttypes = ContactTypes::select('id', 'type_name', 'hr_type_name')->where('type', env('CONTACT_TYPE_DEFAULT'))->orderBy('id', 'ASC')->get()->toarray();
        if ($request->optype == 'delete') {
            if ($type == 'phones' || $type == 'mobiles' || $type == 'faxes') {
                $delphones = UserPhone::where('id', $request->id)->first();
                $delphones->delete();
                $returnHTML = view('myprofile.clientajax.viewajaxmodel', compact('contacttypes', 'type', 'user'))->render();
                return response()->json(
                    array(
                        'status'        => 1,
                        'html'          => $returnHTML,
                        'msg'          => trans("form.my_profile.deleted_successfully"),
                    )
                );
            }
            if ($type == 'emails') {
                $delemails = UserEmail::where('id', $request->id)->first();
                $delemails->delete();
                $returnHTML = view('myprofile.clientajax.viewajaxmodel', compact('contacttypes', 'type', 'user'))->render();
                return response()->json(
                    array(
                        'status'        => 1,
                        'html'          => $returnHTML,
                        'msg'          => trans("form.my_profile.deleted_successfully"),
                    )
                );
            }
            if ($type == 'address') {
                $deladdress = UserAddress::where('id', $request->id)->first();
                $deladdress->delete();
                $returnHTML = view('myprofile.clientajax.viewajaxmodel', compact('contacttypes', 'type', 'user'))->render();

                return response()->json(
                    array(
                        'status'        => 1,
                        'html'          => $returnHTML,
                        'msg'          => trans("form.my_profile.deleted_successfully"),
                    )
                );
            }
            if ($type == 'contact') {
                $contacttypes = ContactTypes::select('id', 'type_name', 'hr_type_name')->where('type', env('CONTACT_TYPE_CONTACT'))->orderBy('id', 'ASC')->get()->toarray();
                $delcontact = UserContact::where('id', $request->id)->first();
                $delcontact->delete();
                $returnHTML = view('myprofile.clientajax.viewajaxmodel', compact('contacttypes', 'type', 'user'))->render();
                return response()->json(
                    array(
                        'status'        => 1,
                        'html'          => $returnHTML,
                        'msg'          => trans("form.my_profile.deleted_successfully"),
                    )
                );
            }
        } else {

            if ($request->type == 'phones') {
                $validator = Validator::make($request->all(), [
                    'phone' => 'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(
                        array(
                            'status'        => 2,
                            'msg'          => implode(", ", $validator->errors()->all()),
                        )
                    );
                    exit;
                }
                $phonarr = [];
                $phonarr['phone_type'] = (($request->phone_type == 'Other' || $request->phone_type == 'Contact') && $request->other_text != '') ? $request->other_text : $request->phone_type;
                $phonarr['value'] = $request->phone;
                if ($request->optype == 'add') {
                    $phonarr['user_type_id'] = auth()->user()->type_id;
                    $phonarr['user_id'] = auth()->user()->id;
                    $phonarr['type'] = 'phone';
                    $phonupdate = UserPhone::insert($phonarr);
                } else {
                    $phonupdate = UserPhone::where('type', '=', 'phone')
                        ->where('id', '=', $request->id)
                        ->update($phonarr);
                }
                $returnHTML = view('myprofile.clientajax.viewajaxmodel', compact('contacttypes', 'type', 'user'))->render();
                if (!$phonupdate) {
                    return response()->json(
                        array(
                            'status'        => 2,
                            'msg'          => trans('form.registration.something_went_wrong'),
                        )
                    );
                }
            }
            if ($request->type == 'mobiles') {
                $validator = Validator::make($request->all(), [
                    'mobile' => 'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(
                        array(
                            'status'        => 2,
                            'msg'          => implode(", ", $validator->errors()->all()),
                        )
                    );
                    exit;
                }
                $mobilearr = [];
                $mobilearr['phone_type'] = (($request->mobile_type == 'Other'  || $request->mobile_type == 'Contact') && $request->other_text != '') ? $request->other_text : $request->mobile_type;
                $mobilearr['value'] = $request->mobile;
                if ($request->optype == 'add') {
                    $mobilearr['user_type_id'] = auth()->user()->type_id;
                    $mobilearr['user_id'] = auth()->user()->id;
                    $mobilearr['type'] = 'mobile';
                    $mobileupdate = UserPhone::insert($mobilearr);
                } else {
                    $mobileupdate = UserPhone::where('type', '=', 'mobile')
                        ->where('id', '=', $request->id)
                        ->update($mobilearr);
                }
                $returnHTML = view('myprofile.clientajax.viewajaxmodel', compact('contacttypes', 'type', 'user'))->render();
                if (!$mobileupdate) {
                    return response()->json(
                        array(
                            'status'        => 2,
                            'msg'          => trans('form.registration.something_went_wrong'),
                        )
                    );
                }
            }
            if ($request->type == 'emails') {
                $validator = Validator::make($request->all(), [
                    'email' => 'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(
                        array(
                            'status'        => 2,
                            'msg'          => implode(", ", $validator->errors()->all()),
                        )
                    );
                    exit;
                }
                $emailarr = [];
                $emailarr['email_type'] = (($request->email_type == 'Other' || $request->email_type == 'Contact') && $request->other_text != '') ? $request->other_text : $request->email_type;
                $emailarr['value'] = $request->email;
                if ($request->optype == 'add') {
                    $emailarr['user_type_id'] = auth()->user()->type_id;
                    $emailarr['user_id'] = auth()->user()->id;
                    $emailupdate = UserEmail::insert($emailarr);
                } else {
                    $emailupdate = UserEmail::where('id', '=', $request->id)
                        ->update($emailarr);
                }
                $returnHTML = view('myprofile.clientajax.viewajaxmodel', compact('contacttypes', 'type', 'user'))->render();
                if (!$emailupdate) {
                    return response()->json(
                        array(
                            'status'        => 2,
                            'msg'          => trans('form.registration.something_went_wrong'),
                        )
                    );
                }
            }
            if ($request->type == 'faxes') {
                $validator = Validator::make($request->all(), [
                    'fax' => 'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(
                        array(
                            'status'        => 2,
                            'msg'          => implode(", ", $validator->errors()->all()),
                        )
                    );
                    exit;
                }
                $faxarr = [];
                $faxarr['phone_type'] = (($request->fax_type == 'Other' || $request->fax_type == 'Contact') && $request->other_text != '') ? $request->other_text : $request->fax_type;
                $faxarr['value'] = $request->fax;
                if ($request->optype == 'add') {
                    $faxarr['user_type_id'] = auth()->user()->type_id;
                    $faxarr['user_id'] = auth()->user()->id;
                    $faxarr['type'] = 'fax';
                    $faxupdate = UserPhone::insert($faxarr);
                } else {
                    $faxupdate = UserPhone::where('type', '=', 'fax')
                        ->where('id', '=', $request->id)
                        ->update($faxarr);
                }
                $returnHTML = view('myprofile.clientajax.viewajaxmodel', compact('contacttypes', 'type', 'user'))->render();
                if (!$faxupdate) {
                    return response()->json(
                        array(
                            'status'        => 2,
                            'msg'          => trans('form.registration.something_went_wrong'),
                        )
                    );
                }
            }
            if ($request->type == 'address') {
                $validator = Validator::make($request->all(), [
                    'address2' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(
                        array(
                            'status'        => 2,
                            'msg'          => implode(", ", $validator->errors()->all()),
                        )
                    );
                    exit;
                }
                $addressarr = [];
                $addressarr['address1'] = $request->address1;
                $addressarr['address2'] = $request->address2;
                $addressarr['country_id'] = $request->country_id;
                $addressarr['city'] = $request->city;
                $addressarr['state'] = $request->state;
                $addressarr['zipcode'] = $request->zipcode;
                $addressarr['address_type'] = (($request->address_type == 'Other' || $request->address_type == 'Contact') && $request->other_text != '') ? $request->other_text : $request->address_type;
                if ($request->optype == 'add') {
                    $addressarr['user_type_id'] = auth()->user()->type_id;
                    $addressarr['user_id'] = auth()->user()->id;
                    $addressupdate = UserAddress::insert($addressarr);
                } else {
                    $addressupdate = UserAddress::where('id', '=', $request->id)
                        ->update($addressarr);
                }
                if (!$addressupdate) {
                    return response()->json(
                        array(
                            'status'        => 2,
                            'msg'          => trans('form.registration.something_went_wrong'),
                        )
                    );
                }
                $returnHTML = view('myprofile.clientajax.viewajaxmodel', compact('contacttypes', 'type', 'user'))->render();
            }
            if ($request->type == 'contact') {
                $validator = Validator::make($request->all(), [
                    'fax' => 'required',
                    'phone' => 'required',
                    'mobile' => 'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(
                        array(
                            'status'        => 2,
                            'msg'          => implode(", ", $validator->errors()->all()),
                        )
                    );
                    exit;
                }
                $contactarr = [];
                $contactarr['first_name'] = $user->name;
                $contactarr['fax'] = $request->fax;
                $contactarr['phone'] = $request->phone;
                $contactarr['mobile'] = $request->mobile;
                $contypeid = ContactTypes::where('type_name', $request->contact_type)->pluck('id');
                $contactarr['contact_type_id'] = $contypeid[0];
                $contactarr['contact_type'] = (($request->contact_type == 'Other' || $request->contact_type == 'Contact') && $request->other_text != '') ? $request->other_text : $request->contact_type;
                if ($request->optype == 'add') {
                    $contactarr['user_type_id'] = auth()->user()->type_id;
                    $contactarr['user_id'] = auth()->user()->id;
                    $contactupdate = UserContact::insert($contactarr);
                } else {
                    $contactupdate = UserContact::where('id', '=', $request->id)
                        ->update($contactarr);
                }
                if (!$contactupdate) {
                    return response()->json(
                        array(
                            'status'        => 2,
                            'msg'          => trans('form.registration.something_went_wrong'),
                        )
                    );
                }
                $returnHTML = view('myprofile.clientajax.viewajaxmodel', compact('contacttypes', 'type', 'user'))->render();
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
