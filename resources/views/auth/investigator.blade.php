@extends('layouts.auth-master')

@section('title') {{trans('form.register')}} @endsection

@section('headerCss')
    <link href="{{ URL::asset('/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
 <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-8">
                    <div class="card overflow-hidden">
                        <div class="card-body pt-0">
                            <h3 class="text-center mt-4">
                                <a href="/" class="logo logo-admin"><img src="{{ URL::asset('/images/logo-dark.png')}}"  height="50" alt="logo"></a>
                            </h3>
                            <div class="p-3">
                                <h4 class="text-muted font-size-18 mb-1 text-center">{{ trans('form.investigator_reg'). ' ' .trans('form.registration.register') }}</h4>
                                <p class="text-muted text-center">{{ trans('form.registration.get_your_free') }} {{ trans('form.investigator_reg') }} {{ trans('form.registration.account_now') }}</p>
                                @error('g-recaptcha-response')
                                <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                   {{ trans('form.captcha_is_required') }}
                                </div>   
                                @enderror

                                <form name="investigator-form" id="investigator-form" class="form-horizontal mt-4" method="POST" action="{{ route('register.storeInvestigator') }}">

                                    @csrf

                                    <input type="hidden" name="type_id" value="{{$typeid}}">

                                    <div id="next_step1">

                                        <div class="form-group">
                                            <label for="username">{{ trans('form.name') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="name" value="{{ old('name') }}" autocomplete="name" class="form-control @error('name') is-invalid @enderror" autofocus id="name" placeholder="{{ trans('form.enter_name') }}">
                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="useremail">{{ trans('form.email_address') }} <span class="text-danger">*</span></label>
                                              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" id="useremail" placeholder="{{ trans('form.enter_email') }}" autocomplete="email" >
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                            <label for="userpassword">{{ trans('form.password') }} <span class="text-danger">*</span></label>
                                            <input type="password" minlength="6" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password" id="userpassword" placeholder="{{ trans('form.enter_password') }}">
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="userpassword">{{ trans('form.registration.confirm_password') }}</label>
                                                <input type="password" minlength="6" name="password_confirmation" class="form-control" id="userconfirmpassword" placeholder="{{ trans('form.registration.confirm_password') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mt-4">
                                            <div class="col-12 text-right">
                                                <button onclick="loadSteps('step2', 'step1');" class="btn btn-primary w-md waves-effect waves-light" type="button">{{ trans('general.next') }}</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="next_step2">
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <h4 class="text-muted font-size-18 mb-1">{{ trans('form.registration.client.personal_details') }}</h4>
                                                <hr>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label>{{ trans('form.registration.investigator.family') }} </label>
                                                <input type="text" value="" class="form-control" id="family" name="family" placeholder="{{ trans('form.registration.investigator.family') }}">
                                                @error('family')
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label>{{ trans('form.registration.investigator.id_number') }} </label>
                                                <input type="text" value="" class="form-control" id="idnumber" name="idnumber" placeholder="{{ trans('form.registration.investigator.id_number') }}">
                                                @error('idnumber')
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>{{ trans('form.registration.client.website') }}</label>
                                                <input type="text" value="" class="form-control" id="website" name="website" placeholder="{{ trans('form.registration.client.website') }}">
                                                @error('website')
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                   <label>{{ trans('form.registration.investigator.fields_of_specialization') }} <span class="text-danger">*</span></label>
                                                   <select multiple="multiple" class="select2 form-control select2-multiple input_required_s2" id="specializations" name="specializations[]" data-placeholder="{{ trans('form.registration.investigator.fos_helper') }}">
                                                       @foreach($specializations as $key => $specialization)
                                                            <option value="{{ $specialization['id'] }}" {{ old('specializations') == $specialization['id'] ? 'selected' : '' }}>{{ App::isLocale('hr')?$specialization['hr_name']:$specialization['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>{{ trans('form.registration.investigator.date_of_birth') }}</label>
                                                     <div class="input-group">
                                                        <input type="text" class="form-control" placeholder="{{trans('general.date_format')}}" id="dob" name="dob">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i
                                                                    class="mdi mdi-calendar"></i></span>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>

                                        <!-- Block for multiple addresses-->
                                        <div class="table-wrapper">
                                            <div class="table-responsive mb-0 fixed-solution"
                                                 data-pattern="priority-columns">
                                                <div id="addresses_tbl">
                                                    <table class="table table-borderless mb-0">
                                                        <thead>
                                                        <tr>
                                                            <th class="btn-primary"><h4
                                                                    class="font-size-18 mb-1">{{ trans('form.registration.client.address_details') }}</h4>
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="address-accordion">
                                                        </tbody>
                                                        <tfoot>
                                                        <tr><td id="address_footer" class="text-center"> {{ trans("general.no_record_added") }} </td></tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-12 text-right">
                                                    <button type="button" onclick="addNewAddress()"
                                                            class="btn btn-link btn-lg waves-effect"
                                                            style="text-decoration: underline">{{ trans('form.add_address') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row mt-4">
                                             <div class="col-12 text-right">
                                                 <button onclick="loadSteps('step1', 'step2');" class="btn btn-secondary w-md waves-effect waves-light" type="button">{{ trans('general.previous') }}</button>
                                                 <button onclick="loadSteps('step3', 'step2');"
                                                         class="btn btn-primary w-md waves-effect waves-light"
                                                         type="button">{{ trans('general.next') }}</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="next_step3">


                                        <!-- Block for multiple email-->
										<div class="table-wrapper">
											<div class="table-responsive mb-0 fixed-solution"
												 data-pattern="priority-columns">
												<div id="emails_tbl">
													<table class="table table-borderless mb-0">
														<thead>
														<tr>
															<th class="btn-primary"><h4
																		class="font-size-18 mb-1">{{ trans('form.registration.client.email') }}</h4>
															</th>
														</tr>
														</thead>
														<tbody id="email-accordion">
														</tbody>
														<tfoot>
														<tr>
															<td id="email_footer" class="text-center"> {{ trans('form.registration.deliveryboy.no_record_added') }}
															</td>
														</tr>
														</tfoot>
													</table>
												</div>
											</div>

											<div class="form-row">
												<div class="col-md-12 text-right">
													<button type="button" onclick="addNewEmail()"
															class="btn btn-link btn-lg waves-effect"
															style="text-decoration: underline">{{ trans('form.registration.deliveryboy.add_email') }}
													</button>
												</div>
											</div>
										</div>
										<!-- end Block for multiple email-->

										<!-- Block for multiple phone-->
										<div class="table-wrapper">
											<div class="table-responsive mb-0 fixed-solution"
												 data-pattern="priority-columns">
												<div id="emails_tbl">
													<table class="table table-borderless mb-0">
														<thead>
														<tr>
															<th class="btn-primary"><h4
																		class="font-size-18 mb-1">{{ trans('form.registration.client.phone') }}</h4>
															</th>
														</tr>
														</thead>
														<tbody id="phone-accordion">
														</tbody>
														<tfoot>
														<tr>
															<td id="phone_footer" class="text-center"> {{ trans('form.registration.deliveryboy.no_record_added') }}
															</td>
														</tr>
														</tfoot>
													</table>
												</div>
											</div>

											<div class="form-row">
												<div class="col-md-12 text-right">
													<button type="button" onclick="addNewPhone()"
															class="btn btn-link btn-lg waves-effect"
															style="text-decoration: underline">{{ trans('form.registration.deliveryboy.add_phone') }}
													</button>
												</div>
											</div>
										</div>
										<!-- end Block for multiple phone-->

										<!-- Block for multiple mobile-->
										<div class="table-wrapper">
											<div class="table-responsive mb-0 fixed-solution"
												 data-pattern="priority-columns">
												<div id="emails_tbl">
													<table class="table table-borderless mb-0">
														<thead>
														<tr>
															<th class="btn-primary"><h4
																		class="font-size-18 mb-1">{{ trans('form.registration.client.mobile') }}</h4>
															</th>
														</tr>
														</thead>
														<tbody id="mobile-accordion">
														</tbody>
														<tfoot>
														<tr>
															<td id="mobile_footer" class="text-center"> {{ trans('form.registration.deliveryboy.no_record_added') }}
															</td>
														</tr>
														</tfoot>
													</table>
												</div>
											</div>

											<div class="form-row">
												<div class="col-md-12 text-right">
													<button type="button" onclick="addNewMobile()"
															class="btn btn-link btn-lg waves-effect"
															style="text-decoration: underline">{{ trans('form.registration.deliveryboy.add_mobile') }}
													</button>
												</div>
											</div>
										</div>
										<!-- end Block for multiple mobile-->

										<!-- Block for multiple fax-->
										<div class="table-wrapper">
											<div class="table-responsive mb-0 fixed-solution"
												 data-pattern="priority-columns">
												<div id="emails_tbl">
													<table class="table table-borderless mb-0">
														<thead>
														<tr>
															<th class="btn-primary"><h4
																		class="font-size-18 mb-1">{{ trans('form.registration.client.fax') }}</h4>
															</th>
														</tr>
														</thead>
														<tbody id="fax-accordion">
														</tbody>
														<tfoot>
														<tr>
															<td id="fax_footer" class="text-center"> {{ trans('form.registration.deliveryboy.no_record_added') }}
															</td>
														</tr>
														</tfoot>
													</table>
												</div>
											</div>

											<div class="form-row">
												<div class="col-md-12 text-right">
													<button type="button" onclick="addNewFax()"
															class="btn btn-link btn-lg waves-effect"
															style="text-decoration: underline">{{ trans('form.registration.deliveryboy.add_fax') }}
													</button>
												</div>
											</div>
										</div>
										<!-- end Block for multiple fax-->



                                        

                                        <!-- Bank details -->
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <h4 class="text-muted font-size-18 mb-1">{{ trans('form.registration.investigator.bank_details') }}</h4>
                                                <hr>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="company">{{ trans('form.registration.investigator.company') }} </label>
                                                <input type="text" class="form-control @error('company') is-invalid @enderror" name="company" id="company" placeholder="{{ trans('form.registration.investigator.company') }}">
                                                @error('company')
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label
                                                        for="name_of_bank">{{ trans('form.registration.investigator.name_of_bank') }} 
                                                </label>
                                                <input type="text" class="form-control @error('name_of_bank') is-invalid @enderror" name="name_of_bank" id="name_of_bank" placeholder="{{ trans('form.registration.investigator.name_of_bank') }}">

                                                @error('name_of_bank')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label
                                                        for="bank_number">{{ trans('form.registration.investigator.bank_number') }} </label>
                                                <input type="text"
                                                       class="form-control @error('name_of_bank') is-invalid @enderror"
                                                       name="bank_number"  id="bank_number"
                                                       placeholder="{{ trans('form.registration.investigator.bank_number') }}">
                                                @error('bank_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="branch_name">{{ trans('form.registration.investigator.branch_name') }} </label>
                                                <input type="text" class="form-control @error('branch_name') is-invalid @enderror" name="branch_name" id="branch_name" placeholder="{{ trans('form.registration.investigator.branch_name') }}">
                                                @error('branch_name')
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="branch_no">{{ trans('form.registration.investigator.branch_no') }} </label>
                                                <input type="text" class="form-control @error('branch_no') is-invalid @enderror" name="branch_no" id="branch_no" placeholder="{{ trans('form.registration.investigator.branch_no') }}">
                                                @error('branch_no')
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="account_no">{{ trans('form.registration.investigator.account_no') }}</label>
                                                <input type="text" class="form-control @error('account_no') is-invalid @enderror" name="account_no" id="account_no" placeholder="{{ trans('form.registration.investigator.account_no') }}">
                                                @error('account_no')
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="consent">{{ trans('form.registration.investigator.consent') }} <span class="text-danger">*</span></label>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input input_required_s3" name="confirm_submit" id="confirm_submit">
                                                    <label class="custom-control-label" for="confirm_submit"><a data-toggle = "collapse" 
                                                        href = "#collapsewithlink" role = "button" aria-expanded = "false" 
                                                        aria-controls = "collapsewithlink">@lang('form.registration.investigator.agree_privacy_policy')</a></label>
                                                        <div class = "collapse" id = "collapsewithlink">
                                                            <div class = "card card-body">
                                                               {{ trans('general.privacy_policy_data') }}
                                                            </div>
                                                         </div>
                                                    </div>
                                            </div>
                                        </div>
                                        <!-- End Bank detials -->

                                        <div class="form-group cptch-container">
                                            <div class="g-recaptcha m-b-10" data-sitekey="{{ env('GOOGLE_RECAPTCHA_SITE_KEY') }}"></div>
                                            <span class="invalid-feedback gr-error" role="alert" style="display: none;">
                                                <strong>{{ trans('form.captcha_is_required') }}</strong>
                                            </span>
                                            @error('g-recaptcha-response')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group row mt-4">
                                            <div class="col-12 text-right">
                                                <button onclick="loadSteps('step2', 'step3');" class="btn btn-secondary w-md waves-effect waves-light" type="button">{{ trans('general.previous') }}</button>
                                                <button class="btn btn-primary w-md waves-effect waves-light" type="submit">{{ trans('form.register') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                 
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 text-center">
                        <p>{{ trans('form.already_have_an_account') }} <a href="/login" class="text-primary"> {{ trans('form.login') }} </a> </p>
                        <p>{{ trans('general.developedby') }} <a target="_blank" href="https://soft-l.com/">{{ trans('general.company') }} </a></p>
                    </div>

                </div>
            </div>
        </div>
    </div>
{{-- start address div --}}
 <div class="card mb-1 shadow-none" id="address_clone_element" style="display: none">
     <div class="card-header p-3" id="address_heading" style="padding-bottom: 0 !important;">
         <div class="row" style="padding-right: 0; padding-left: 0">
             <div class="col-sm-12 col-md-6 text-left">
                 <h6 class="m-0 font-size-14">
                     <a href="#address_collapse"
                        class="text-dark collapsed address_header"
                        data-toggle="collapse"
                        aria-expanded="false"
                        aria-controls="collapse_1">
                        {{ trans('form.registration.client.address') }} : <span class="address_row_no"></span>
                     </a>
                 </h6>
             </div>
             <div class="col-sm-12 col-md-6 text-sm-right">
                 <button type="button"
                         onclick="deleteAddress(this)"
                         class="btn btn-link waves-effect"
                         style="text-decoration: underline">
                         {{ trans('general.delete') }}
                 </button>
             </div>
         </div>

     </div>

     <div id="address_collapse"
          class="collapse address_block"
          aria-labelledby="address_heading"
          data-parent="#address-accordion" style="">
         <div class="card-body">
             <div class="form-row">
                 <div class="form-group col-md-6">
                     <label>{{ trans('form.registration.client.address') }}
                         <span
                             class="text-danger">*</span></label>
                     <input type="text" value=""
                            class="form-control pac-target-input multiple_input_required_s2 arr_address_address1"
                            name="address[]['address1']"
                            placeholder="{{ trans('form.search_address') }}"
                            required
                            autocomplete="off">

                 </div>

                 <div class="form-group col-md-6">
                     <label>{{ trans('form.registration.client.address_helper') }}</label>
                     <input type="text" value=""
                            class="form-control arr_address_address2"
                            name="address[]['address2']"
                            placeholder="{{ trans('form.registration.client.address_2_helper') }}">
                 </div>
             </div>

             <div class="form-row">
                 <div class="form-group col-md-4">
                     <label>{{ trans('form.registration.client.city') }}
                         <span
                             class="text-danger">*</span></label>
                     <input type="text" value=""
                            class="form-control multiple_input_required_s2 arr_address_city"
                            name="address[]['city']"
                            placeholder="{{ trans('form.registration.client.city') }}"
                            required>
                 </div>

                 <div class="form-group col-md-4">
                     <label>{{ trans('form.registration.client.state') }}
                         <span
                             class="text-danger">*</span></label>
                     <input type="text" value=""
                            class="form-control multiple_input_required_s2 arr_address_state"
                            name="address[]['state']"
                            placeholder="{{ trans('form.registration.client.state') }}"
                            required>
                 </div>

                 <div class="form-group col-md-4">
                     <label>{{ trans('form.registration.client.country') }}
                         <span
                             class="text-danger">*</span></label>
                     <select id="country_id"
                             name="address[]['country_id']"
                             class="form-control multiple_input_required_s2 arr_address_country"
                             required>
                             @foreach($countries as  $country_name)
                             <option value="{{ $country_name['id'] }}" {{ old('country_id') == $country_name['id'] ? 'selected' : '' }}>{{ App::isLocale('hr')?$country_name['hr_name']:$country_name['en_name'] }}</option>
                           @endforeach
                     </select>
                 </div>

             </div>

             <div class="form-row">
                <div class="form-group col-md-4">
                    <label>{{ trans('form.registration.client.zip_code') }}
                        </label>
                    <input type="text" value=""
                        class="form-control numeric arr_address_zip"
                        name="address[]['zipcode']"
                        placeholder="{{ trans('form.registration.client.zip_code') }}"
                        >
                </div>
                <div class="form-group col-md-4">
                    <label>{{ trans('form.registration.client.address_type') }}</label>
                    <select onchange="changeothertextbox(this);changesubjecttitlebyid(this.id);" name="address[]['address_type']"
                            class="form-control arr_address_type multiple_type_required_s2" required>
                        @foreach($contacttypes as $id => $contact_name)
                            <option value="{{ $contact_name['type_name'] }}" {{ old('address_type') == $contact_name['id'] ? 'selected' : '' }}>{{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4 arr_add_other_text_div d-none">
                    <label>{{ trans('form.registration.client.type') }}</label>
                        
                    <input type="text" onchange="changeothersubjecttitle(this);"  value=""
                        class="form-control numeric arr_add_other_text_type"
                        name="address[]['other_text']"
                        placeholder="{{ trans('form.registration.client.type_helper') }}"
                        >
                </div>
            </div>
         </div>
     </div>
 </div>
 {{-- end address div --}}
  {{-- start email div --}}
  <div class="card mb-1 shadow-none" id="email_clone_element" style="display: none">
    <div class="card-header p-3" id="heading_email"
         style="padding-bottom: 0 !important;">
        <div class="row"
             style="padding-right: 0; padding-left: 0">
            <div class="col-sm-12 col-md-6 text-left">
                <h6 class="m-0 font-size-14">
                    <a href="#email_collapse"
                       class="text-dark collapsed email_header"
                       data-toggle="collapse"
                       aria-expanded="false"
                       aria-controls="email_collapse">
                       {{ trans('form.registration.client.email') }} <span class="email_row_no"></span>
                    </a>
                </h6>
            </div>
            <div class="col-sm-12 col-md-6 text-sm-right">
                <button type="button"
                        onclick="deleteEmail(this)"
                        class="btn btn-link waves-effect"
                        style="text-decoration: underline">
                        {{ trans('general.delete') }}
                </button>
            </div>
        </div>
    </div>
    <div id="email_collapse"
            class="collapse email_block"
            aria-labelledby="heading_email"
            data-parent="#email-accordion" style="">
            <div class="card-body">
                
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>{{ trans('form.registration.client.email') }}
							<span class="text-danger">*</span>
						</label>
                        <input type="email" value=""
                            class="form-control multiple_input_required_s3 arr_other_email" 
                            name="otheremail[]['email']"
                            placeholder="{{trans('general.email_placeholder')}}"
                            >
                    </div>
                    <div class="form-group col-md-4">
                        <label>{{ trans('form.registration.client.email_type') }}</label>
                        <select onchange="changeothertextbox(this)" name="otheremail[]['email_type']"
                                class="form-control arr_otheremail_type multiple_input_required_s3 multiple_type_required_s3" required>
                            @foreach($contacttypes as $id => $contact_name)
                                <option value="{{ $contact_name['type_name'] }}" {{ old('contact_type_id') == $contact_name['id'] ? 'selected' : '' }}>{{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4 arr_otheremail_other_text_div d-none">
                        <label>{{ trans('form.registration.client.type') }}</label>
                            
                        <input type="text" value=""
                            class="form-control numeric arr_otheremail_other_text_type"
                            name="otheremail[]['other_text']"
                            placeholder="{{ trans('form.registration.client.type_helper') }}"
                            >
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- end email div --}}

{{-- start phone div --}}
<div class="card mb-1 shadow-none" id="phone_clone_element" style="display: none">
    <div class="card-header p-3" id="heading_phone"
         style="padding-bottom: 0 !important;">
        <div class="row"
             style="padding-right: 0; padding-left: 0">
            <div class="col-sm-12 col-md-6 text-left">
                <h6 class="m-0 font-size-14">
                    <a href="#phone_collapse"
                       class="text-dark collapsed phone_header"
                       data-toggle="collapse"
                       aria-expanded="false"
                       aria-controls="phone_collapse">
                       {{ trans('form.registration.client.phone') }} <span class="phone_row_no"></span>
                    </a>
                </h6>
            </div>
            <div class="col-sm-12 col-md-6 text-sm-right">
                <button type="button"
                        onclick="deletePhone(this)"
                        class="btn btn-link waves-effect"
                        style="text-decoration: underline">
                        {{ trans('general.delete') }}
                </button>
            </div>
        </div>
    </div>
    <div id="phone_collapse"
            class="collapse phone_block"
            aria-labelledby="heading_phone"
            data-parent="#phone-accordion" style="">
            <div class="card-body">
                
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>{{ trans('form.registration.client.phone') }}
							<span class="text-danger">*</span>
						</label>
                        <input type="tel" placeholder="{{trans('general.phone_placeholder')}}" value=""
                            class="form-control multiple_input_required_s3 arr_other_phone" 
                            name="otherphone[]['phone']"
                            >
                    </div>
                    <div class="form-group col-md-4">
                        <label>{{ trans('form.registration.client.phone_type') }}</label>
                        <select onchange="changeothertextbox(this)" name="otherphone[]['phone_type']"
                                class="form-control arr_otherphone_type multiple_input_required_s3 multiple_type_required_s3" required>
                            @foreach($contacttypes as $id => $contact_name)
                                <option value="{{ $contact_name['type_name'] }}" {{ old('contact_type_id') == $contact_name['id'] ? 'selected' : '' }}>{{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4 arr_otherphone_other_text_div d-none">
                        <label>{{ trans('form.registration.client.type') }}</label>
                            
                        <input type="text" value=""
                            class="form-control numeric arr_otherphone_other_text_type"
                            name="otherphone[]['other_text']"
                            placeholder="{{ trans('form.registration.client.type_helper') }}"
                            >
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- end phone div --}}

{{-- start mobile div --}}
<div class="card mb-1 shadow-none" id="mobile_clone_element" style="display: none">
    <div class="card-header p-3" id="heading_mobile"
         style="padding-bottom: 0 !important;">
        <div class="row"
             style="padding-right: 0; padding-left: 0">
            <div class="col-sm-12 col-md-6 text-left">
                <h6 class="m-0 font-size-14">
                    <a href="#mobile_collapse"
                       class="text-dark collapsed mobile_header"
                       data-toggle="collapse"
                       aria-expanded="false"
                       aria-controls="mobile_collapse">
                       {{ trans('form.registration.client.mobile') }} <span class="mobile_row_no"></span>
                    </a>
                </h6>
            </div>
            <div class="col-sm-12 col-md-6 text-sm-right">
                <button type="button"
                        onclick="deleteMobile(this)"
                        class="btn btn-link waves-effect"
                        style="text-decoration: underline">
                        {{ trans('general.delete') }}
                </button>
            </div>
        </div>
    </div>
    <div id="mobile_collapse"
            class="collapse mobile_block"
            aria-labelledby="heading_mobile"
            data-parent="#mobile-accordion" style="">
            <div class="card-body">
                
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>{{ trans('form.registration.client.mobile') }}
							<span class="text-danger">*</span>
						</label>
                        <input type="tel" placeholder="{{trans('general.phone_placeholder')}}" value=""
                            class="form-control multiple_input_required_s3 arr_other_mobile" 
                            name="othermobile[]['mobile']"
                            >
                    </div>
                    <div class="form-group col-md-4">
                        <label>{{ trans('form.registration.client.mobile_type') }}</label>
                        <select onchange="changeothertextbox(this)" name="othermobile[]['mobile_type']"
                                class="form-control arr_othermobile_type multiple_input_required_s3 multiple_type_required_s3" required>
                            @foreach($contacttypes as $id => $contact_name)
                                <option value="{{ $contact_name['type_name'] }}" {{ old('contact_type_id') == $contact_name['id'] ? 'selected' : '' }}>{{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4 arr_othermobile_other_text_div d-none">
                        <label>{{ trans('form.registration.client.type') }}</label>
                            
                        <input type="text" value=""
                            class="form-control numeric arr_othermobile_other_text_type"
                            name="othermobile[]['other_text']"
                            placeholder="{{ trans('form.registration.client.type_helper') }}"
                            >
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- end mobile div --}}

{{-- start fax div --}}
<div class="card mb-1 shadow-none" id="fax_clone_element" style="display: none">
    <div class="card-header p-3" id="heading_fax"
         style="padding-bottom: 0 !important;">
        <div class="row"
             style="padding-right: 0; padding-left: 0">
            <div class="col-sm-12 col-md-6 text-left">
                <h6 class="m-0 font-size-14">
                    <a href="#fax_collapse"
                       class="text-dark collapsed fax_header"
                       data-toggle="collapse"
                       aria-expanded="false"
                       aria-controls="fax_collapse">
                       {{ trans('form.registration.client.fax') }} <span class="fax_row_no"></span>
                    </a>
                </h6>
            </div>
            <div class="col-sm-12 col-md-6 text-sm-right">
                <button type="button"
                        onclick="deleteFax(this)"
                        class="btn btn-link waves-effect"
                        style="text-decoration: underline">
                        {{ trans('general.delete') }}
                </button>
            </div>
        </div>
    </div>
    <div id="fax_collapse"
            class="collapse fax_block"
            aria-labelledby="heading_fax"
            data-parent="#fax-accordion" style="">
            <div class="card-body">
                
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>{{ trans('form.registration.client.fax') }}
							<span class="text-danger">*</span>
						</label>
                        <input type="tel" placeholder="{{trans('general.fax_placeholder')}}" value=""
                            class="form-control multiple_input_required_s3 arr_other_fax" 
                            name="otherfax[]['fax']"
                            >
                    </div>
                    <div class="form-group col-md-4">
                        <label>{{ trans('form.registration.client.fax_type') }}</label>
                        <select onchange="changeothertextbox(this)" name="otherfax[]['fax_type']"
                                class="form-control arr_otherfax_type multiple_input_required_s3 multiple_type_required_s3" required>
                            @foreach($contacttypes as $id => $contact_name)
                                <option value="{{ $contact_name['type_name'] }}" {{ old('contact_type_id') == $contact_name['id'] ? 'selected' : '' }}>{{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4 arr_otherfax_other_text_div d-none">
                        <label>{{ trans('form.registration.client.type') }}</label>
                            
                        <input type="text" value=""
                            class="form-control numeric arr_otherfax_other_text_type"
                            name="otherfax[]['other_text']"
                            placeholder="{{ trans('form.registration.client.type_helper') }}"
                            >
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- end fax div --}}

 

@stop

@section('page-js')

    <script src="{{ URL::asset('/libs/select2/select2.min.js')}}"></script>
    <script src="{{ URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{ URL::asset('/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
    <script src="{{ URL::asset('/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')}}"></script>
    <script src="{{ URL::asset('/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{ URL::asset('/js/pages/form-advanced.init.js')}}"></script>
    <script src="{{ URL::asset('/libs/jquery-validate/1.19.0/jquery.validate.js')}}"></script>  
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_KEY')}}&libraries=places&language={{ App::isLocale('hr') ? 'iw' : 'en' }}" async defer></script>
    <script src="{{ URL::asset('/libs/inputmask/5.0.5/jquery.inputmask.js') }}" async></script>
    @if (App::isLocale('hr'))
    <script src="{{ URL::asset('/libs/jquery-validate/1.19.0/localization/messages_he.js')}}"></script>
    @endif
  
    <script type="text/javascript">

        $(document).ready(function ($) {

            $("#investigator-form").validate({
                ignore: false,
                invalidHandler: function (e, validator) {
                    // loop through the errors:
                    for (var i = 0; i < validator.errorList.length; i++) {
                        $(validator.errorList[i].element).closest('.collapse').collapse('show');
                        $(this).find(":input.error:first").focus();
                    }
                }
            });

            $(window).resize(function () {
                rescaleCaptcha();
            });

            $('#dob').datepicker({
                autoclose: true,
                todayHighlight: true
            });

            //for custom validation before form submit to check all multiple fields has entries
            $('#investigator-form').submit(function (event) {
                var res = false;

                var checkMultipleFields = customFormValidation();
                if (checkMultipleFields) {
                    res = true;
                }

                if (grecaptcha.getResponse() == ""){
                    $('.gr-error').show();
                    return false;
                }else{
                    $('.gr-error').hide();
                    return res;
                }

                return false;

            });

            rescaleCaptcha();

            loadSteps('step1', 'step1');

        });

        //this for add input mask to phone,mobile,fax
        function addinputmask(type){
            if(type=='phone'){ 
                $('.arr_other_phone').inputmask('999-999-9999', {
                autoUnmask: true,
                removeMaskOnSubmit:true
                 });
            }
            if(type=='mobile'){
                $('.arr_other_mobile').inputmask('999-999-9999', {
                autoUnmask: true,
                removeMaskOnSubmit:true
                 });
            }
            if(type=='fax'){
                $('.arr_other_fax').inputmask('999-999-9999', {
                autoUnmask: true,
                removeMaskOnSubmit:true
            });
            }
        }

      // This Function for Address Type Dropdown Othertext to open textbox
      function changeothertextbox(t){
            var id = t.id;
            if(t.value=='Other' || t.value=='Contact'){
                $("#"+id+"_div").removeClass('d-none');
                $("#"+id+"_otext").val('');
                //$("#"+id+"_otext").prop('required',true);
                $("#"+id+"_otext").rules('add', {required: true });
                $("#"+id+"_otext").attr("required", true);
            }else{
                $("#"+id+"_div").addClass('d-none');
               // $("#"+id+"_otext").prop('required',false);
               $("#"+id+"_otext").rules('remove', 'required');
                $("#"+id+"_otext").removeAttr('required');
            }
        }
        
        // set the subject title on Subject Type
        function changeothersubjecttitle(t){
        var id=$(t).data("id");
        var value = t.value;
        $("#"+id+"_title").html(value);  
        }
        function changesubjecttitlebyid(id){
            var e = document.getElementById(id);
			var contry = <?php echo $contacttypes; ?>;
			contry.map(c=>{
				if(e.value == c.type_name) {
					@if(config('app.locale') == 'hr')
						$("#"+id+"_title").html(c.hr_type_name);
					@else
						$("#"+id+"_title").html(c.type_name);
					@endif	
				}		
            });
        }

        // This function for Set Responsive Google ReCaptcha
        function rescaleCaptcha()
        {
            var width = $('.g-recaptcha').parent().width();
            var scale;
            if (width < 302) {
                scale = width / 302;
            } else {
                scale = 1.0;
            }
            $('.g-recaptcha').css('transform', 'scale(' + scale + ')');
            $('.g-recaptcha').css('-webkit-transform', 'scale(' + scale + ')');
            $('.g-recaptcha').css('transform-origin', '0 0');
            $('.g-recaptcha').css('-webkit-transform-origin', '0 0');
        }

        // function step 1 and step 2 show hide
        function loadSteps(step, backstep)
        {
            if (step == 'step1') {
                $('#next_step1').show();
                $('#next_step2').hide();
                $('#next_step3').hide();

                if(backstep == "step2") {
                    $('.multiple_input_required_s2').each(function () {
                        // console.log('removing rule of :>>', $(this));
                        $(this).rules('remove', 'required');
                        $(this).removeAttr('required');
                    });
                    $('.multiple_type_required_s2').each(function () {
                    // console.log('removing rule of :>>', $(this));
                    var id=$(this).attr('id');
                     if(id!='' && id!='undefined' && ($(this).val()=='Other' || $(this).val()=='Contact'))
                     {
                         $("#"+id+"_otext").rules('remove', 'required');
                         $("#"+id+"_otext").removeAttr('required');
                     
                     }
                });

                    $('.input_required_s2').each(function () {
                        // console.log('removing rule of :>>', $(this));
                        $(this).rules('remove', 'required');
                        $(this).removeAttr('required');
                    });
                }

                $('#name').rules('add', { required: true,});
                $('#useremail').rules('add', { required: true,});
                let errorMsg = "{{trans('general.password_validation')}}";
                $.validator.addMethod('checkParams', function (value) { 
                    return /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/i.test(value); 
                }, errorMsg);
                $('#userpassword').rules('add', {required: true, minlength: 6, checkParams: true});
                $('#userconfirmpassword').rules('add', {required: true,equalTo: '[name="password"]'});


            } else if (step == 'step2') {

                if((backstep == "step1" && $("#investigator-form").valid() === true) || backstep == "step3"){
                    $('#next_step2').show();
                    $('#next_step1').hide();
                    $('#next_step3').hide();

                    if(backstep == "step3"){
                        $('.multiple_input_required_s3').each(function() {
                            // console.log('removing rule of :>>', $(this));
                            $(this).rules('remove', 'required');
                            $(this).removeAttr('required');
                        });
                        $('.multiple_type_required_s3').each(function () {
                    // console.log('removing rule of :>>', $(this));
                    var id=$(this).attr('id');
                     if(id!='' && id!='undefined' && ($(this).val()=='Other' || $(this).val()=='Contact'))
                     {
                         $("#"+id+"_otext").rules('remove', 'required');
                         $("#"+id+"_otext").removeAttr('required');
                     
                     }
                });

                        $('.input_required_s3').each(function() {
                            // console.log('removing rule of :>>', $(this));
                            $(this).rules('remove', 'required');
                            $(this).removeAttr('required');
                        });
                    }

                    $('.multiple_input_required_s2').each(function(e) {
                        // console.log('adding rule of :>>', $(this));
                        $(this).rules('add', {
                            required: true
                        });
                        $(this).attr("required", true);
                    });
                    $('.multiple_type_required_s2').each(function () {
                    // console.log('removing rule of :>>', $(this));
                    var id=$(this).attr('id');
                    if(id!='' && id!='undefined' && ($(this).val()=='Other' || $(this).val()=='Contact'))
                     {
                            $("#"+id+"_otext").rules('add', {
                            required: true
                        });
                        $("#"+id+"_otext").attr("required", true);
                        
                     
                     }
                });

                    $('.input_required_s2').each(function(e) {
                        // console.log('adding rule of :>>', $(this));
                        $(this).rules('add', {
                            required: true
                        });
                        $(this).attr("required", true);
                    });
                }

            } else if (step == 'step3') {

                if ($("#investigator-form").valid() === true) {
                    if ($(".address-base-row").length < 1) {
                        $("#address_footer").html('<span class="error">{{ trans('general.minimum_entry') }}</span>');

                        return false;
                    } else {
                        $("#address_footer").hide('');
                    }

                    $('.input_required_s3').each(function(e) {
                        // console.log('adding rule of :>>', $(this));
                        $(this).rules('add', {
                            required: true
                        });
                        $(this).attr("required", true);
                    });

                    $('.multiple_input_required_s3').each(function(e) {
                        // console.log('adding rule of :>>', $(this));
                        $(this).rules('add', {
                            required: true
                        });
                        $(this).attr("required", true);
                    });
                    $('.multiple_type_required_s3').each(function () {
                    // console.log('removing rule of :>>', $(this));
                    var id=$(this).attr('id');
                    if(id!='' && id!='undefined' && ($(this).val()=='Other' || $(this).val()=='Contact'))
                     {
                            $("#"+id+"_otext").rules('add', {
                            required: true
                        });
                        $("#"+id+"_otext").attr("required", true);
                        
                     
                     }
                });

                    $('#next_step3').show();
                    $('#next_step2').hide();
                }
            }
        }

        function customFormValidation()
        {
            var isValid = false;
            var cnt = 0;
            var basicValidation = $("#investigator-form").valid();

            if (basicValidation) {
                cnt++;
            }

            $("form tbody").each(function (index) {
                if ($(this).find("tr").length > 0) {
                    cnt++;
                    if (cnt == 6) {
                        isValid = true;
                        return true;
                    }
                } else {
					if ($(this).attr('id') == 'fax-accordion' || $(this).attr('id') == 'email-accordion' || $(this).attr('id') == 'phone-accordion' || $(this).attr('id') == 'mobile-accordion' || $(this).attr('id') == 'contact-accordion') {
					// if ($(this).attr('id') == 'fax-accordion') {
						// fax row is not mandatory - so skip and increment validation cnt
						cnt++;
						if (cnt == 6) {
							isValid = true;
							return true
						}
					} else {
						var footerEle = $(this).parent().find("tfoot tr td");
						footerEle.html('<span class="error">{{ trans('general.minimum_entry') }}</span>');
						footerEle.show();
					}
                }
            });

            return isValid;
        }

        function addNewAddress()
        {
            $("#address_footer").hide();
            var lastNo, originalId;
            var arrRowNo = [];
            var baseTbl = $("#address-accordion");
            originalId = baseTbl.find('.address_block:last').attr('id');
            var cloned = $("#address_clone_element").clone().appendTo('#address-accordion').wrap('<tr class="address-base-row" style="display: block;"><td style="width: 100%;display: inline-table;"></td></tr>');
            //var cloned = baseTbl.append("<tr class='address-base-row' style='display: block;'><td style='width: 100%;display: inline-table;'>"+$("#address_clone_element").clone().html()+"</td></tr>");
            if (originalId)
                arrRowNo = originalId.split("-");

            if (arrRowNo.length > 1) {
                lastNo = arrRowNo[1];
            } else {
                lastNo = 0;
            }

            var newId = 'address_collapse-' + (parseInt(lastNo) + 1);
            cloned.show();
            cloned.find(".address_block").attr('id', newId);
            cloned.find(".address_header").attr('href', '#' + newId);
            cloned.find(".address_row_no").html($(".address-base-row").length);

            cloned.find(".arr_address_address1").attr('name', "address[" + (parseInt(lastNo) + 1) + "][address1]");
            cloned.find(".arr_address_address1").attr('id', "address_complete_" + (parseInt(lastNo) + 1));
            cloned.find(".arr_address_address2").attr('name', "address[" + (parseInt(lastNo) + 1) + "][address2]");
            cloned.find(".arr_address_address2").attr('id', "address2_" + (parseInt(lastNo) + 1));
            cloned.find(".arr_address_state").attr('name', "address[" + (parseInt(lastNo) + 1) + "][city]");
            cloned.find(".arr_address_state").attr('id', "state_" + (parseInt(lastNo) + 1));
            cloned.find(".arr_address_city").attr('name', "address[" + (parseInt(lastNo) + 1) + "][state]");
            cloned.find(".arr_address_city").attr('id',"city_" + (parseInt(lastNo) + 1));
            cloned.find(".arr_address_country").attr('name', "address[" + (parseInt(lastNo) + 1) + "][country_id]");
            cloned.find(".arr_address_country").attr('id', "country_" + (parseInt(lastNo) + 1));
            cloned.find(".arr_address_zip").attr('name', "address[" + (parseInt(lastNo) + 1) + "][zipcode]");
            cloned.find(".arr_address_zip").attr('id', "zipcode_" + (parseInt(lastNo) + 1));

            cloned.find(".arr_address_type").attr('name', "address[" + (parseInt(lastNo) + 1) + "][address_type]");
            cloned.find(".arr_add_other_text_type").attr('name', "address[" + (parseInt(lastNo) + 1) + "][other_text]");
            cloned.find(".arr_add_other_text_type").attr('id', "arr_address_type_" + (parseInt(lastNo) + 1) +"_otext");
            cloned.find(".arr_add_other_text_type").attr('data-id',  "arr_address_type_" + (parseInt(lastNo) + 1));
            cloned.find(".arr_address_type").attr('id', "arr_address_type_" + (parseInt(lastNo) + 1));
            cloned.find(".arr_add_other_text_div").attr('id', "arr_address_type_" + (parseInt(lastNo) + 1)+"_div");

            cloned.find(".address_row_no").attr('id', "arr_address_type_"  +(parseInt(lastNo) + 1) + "_title");

            cloned.find(".arr_add_other_text_div").addClass('d-none');
            cloned.find('input[type=text]').val('');
            setAddressnew((parseInt(lastNo) + 1));
            changesubjecttitlebyid("arr_address_type_" + (parseInt(lastNo) + 1));
            cloned.find('.collapse').collapse('show');
        }

        function deleteAddress(ele)
        {
            $(ele).closest(".address-base-row").remove();

            $(".address-base-row").each(function (index) {
                //$(this).find(".address_row_no").html(index + 1);
            });

            if ($(".address-base-row").length < 1) {
                $("#address_footer").html('<span>{{ trans("general.no_record_added") }}</span>');
                $("#address_footer").show();
            }
        }

        function addNewEmail() {
			$("#email_footer").hide();
			var lastNo, originalId;
			var arrRowNo = [];
			var baseTbl = $("#email-accordion");
			originalId = baseTbl.find('.email_block:last').attr('id');
			var cloned = $("#email_clone_element").clone().appendTo('#email-accordion').wrap('<tr class="email-base-row" style="display: block;"><td style="width: 100%;display: inline-table;"></td></tr>');
			if (originalId)
				arrRowNo = originalId.split("-");

			if (arrRowNo.length > 1) {
				lastNo = arrRowNo[1];
			} else {
				lastNo = 0;
			}
			var newId = 'email_collapse-' + (parseInt(lastNo) + 1);
			cloned.css('display', 'block');
			cloned.find(".email_block").attr('id', newId);
			cloned.find(".email_header").attr('href', '#' + newId);
			cloned.find(".email_row_no").html($(".email-base-row").length);

			cloned.find(".arr_other_email").attr('name', "otheremail[" + (parseInt(lastNo) + 1) + "][email]");
			cloned.find(".arr_otheremail_type").attr('name', "otheremail[" + (parseInt(lastNo) + 1) + "][email_type]");
			cloned.find(".arr_otheremail_other_text_type").attr('name', "otheremail[" + (parseInt(lastNo) + 1) + "][other_text]");
            cloned.find(".arr_otheremail_other_text_type").attr('id', "arr_otheremail_type_" + (parseInt(lastNo) + 1) +"_otext");
            cloned.find(".arr_otheremail_type").attr('id', "arr_otheremail_type_" + (parseInt(lastNo) + 1));
			cloned.find(".arr_otheremail_other_text_div").attr('id', "arr_otheremail_type_" + (parseInt(lastNo) + 1)+"_div");

            cloned.find(".arr_otheremail_other_text_div").addClass('d-none');
			cloned.find('input[type=text]').val('');
			cloned.find('.collapse').collapse('show');

		}

		function deleteEmail(ele) {
			$(ele).closest(".email-base-row").remove();

			$(".email-base-row").each(function (index) {
				$(this).find(".email_row_no").html(index + 1);
			});

			if ($(".email-base-row").length < 1) {
				$("#email_footer").html('<span>{{ trans("general.no_record_added") }}</span>');
				$("#email_footer").show();
			}
		}

        function addNewPhone() {
			$("#phone_footer").hide();
			var lastNo, originalId;
			var arrRowNo = [];
			var baseTbl = $("#phone-accordion");
			originalId = baseTbl.find('.phone_block:last').attr('id');
			var cloned = $("#phone_clone_element").clone().appendTo('#phone-accordion').wrap('<tr class="phone-base-row" style="display: block;"><td style="width: 100%;display: inline-table;"></td></tr>');
			if (originalId)
				arrRowNo = originalId.split("-");

			if (arrRowNo.length > 1) {
				lastNo = arrRowNo[1];
			} else {
				lastNo = 0;
			}
			var newId = 'phone_collapse-' + (parseInt(lastNo) + 1);
			cloned.css('display', 'block');
			cloned.find(".phone_block").attr('id', newId);
			cloned.find(".phone_header").attr('href', '#' + newId);
			cloned.find(".phone_row_no").html($(".phone-base-row").length);

			cloned.find(".arr_other_phone").attr('name', "otherphone[" + (parseInt(lastNo) + 1) + "][phone]");
			cloned.find(".arr_otherphone_type").attr('name', "otherphone[" + (parseInt(lastNo) + 1) + "][phone_type]");
			cloned.find(".arr_otherphone_other_text_type").attr('name', "otherphone[" + (parseInt(lastNo) + 1) + "][other_text]");
			cloned.find(".arr_otherphone_other_text_type").attr('id', "arr_otherphone_type_" + (parseInt(lastNo) + 1) +"_otext");
			cloned.find(".arr_otherphone_type").attr('id', "arr_otherphone_type_" + (parseInt(lastNo) + 1));
			cloned.find(".arr_otherphone_other_text_div").attr('id', "arr_otherphone_type_" + (parseInt(lastNo) + 1)+"_div");

            cloned.find(".arr_otherphone_other_text_div").addClass('d-none');
			cloned.find('input[type=text]').val('');
            addinputmask('phone');
			cloned.find('.collapse').collapse('show');

		}

		function deletePhone(ele) {
			$(ele).closest(".phone-base-row").remove();

			$(".phone-base-row").each(function (index) {
				$(this).find(".phone_row_no").html(index + 1);
			});

			if ($(".phone-base-row").length < 1) {
				$("#phone_footer").html('<span>{{ trans("general.no_record_added") }}</span>');
				$("#phone_footer").show();
			}
		}

		function addNewMobile() {
			$("#mobile_footer").hide();
			var lastNo, originalId;
			var arrRowNo = [];
			var baseTbl = $("#mobile-accordion");
			originalId = baseTbl.find('.mobile_block:last').attr('id');
			var cloned = $("#mobile_clone_element").clone().appendTo('#mobile-accordion').wrap('<tr class="mobile-base-row" style="display: block;"><td style="width: 100%;display: inline-table;"></td></tr>');
			if (originalId)
				arrRowNo = originalId.split("-");

			if (arrRowNo.length > 1) {
				lastNo = arrRowNo[1];
			} else {
				lastNo = 0;
			}
			var newId = 'mobile_collapse-' + (parseInt(lastNo) + 1);
			cloned.css('display', 'block');
			cloned.find(".mobile_block").attr('id', newId);
			cloned.find(".mobile_header").attr('href', '#' + newId);
			cloned.find(".mobile_row_no").html($(".mobile-base-row").length);

			cloned.find(".arr_other_mobile").attr('name', "othermobile[" + (parseInt(lastNo) + 1) + "][mobile]");
			cloned.find(".arr_othermobile_type").attr('name', "othermobile[" + (parseInt(lastNo) + 1) + "][mobile_type]");
			cloned.find(".arr_othermobile_other_text_type").attr('name', "othermobile[" + (parseInt(lastNo) + 1) + "][other_text]");
            cloned.find(".arr_othermobile_other_text_type").attr('id', "arr_othermobile_type_" + (parseInt(lastNo) + 1) +"_otext");
            cloned.find(".arr_othermobile_type").attr('id', "arr_othermobile_type_" + (parseInt(lastNo) + 1));
			cloned.find(".arr_othermobile_other_text_div").attr('id', "arr_othermobile_type_" + (parseInt(lastNo) + 1)+"_div");

            cloned.find(".arr_othermobile_other_text_div").addClass('d-none');
			cloned.find('input[type=text]').val('');
            addinputmask('mobile');
			cloned.find('.collapse').collapse('show');

		}

		function deleteMobile(ele) {
			$(ele).closest(".mobile-base-row").remove();

			$(".mobile-base-row").each(function (index) {
				$(this).find(".mobile_row_no").html(index + 1);
			});

			if ($(".mobile-base-row").length < 1) {
				$("#mobile_footer").html('<span>{{ trans("general.no_record_added") }}</span>');
				$("#mobile_footer").show();
			}
		}

		function addNewFax() {
			$("#fax_footer").hide();
			var lastNo, originalId;
			var arrRowNo = [];
			var baseTbl = $("#fax-accordion");
			originalId = baseTbl.find('.fax_block:last').attr('id');
			var cloned = $("#fax_clone_element").clone().appendTo('#fax-accordion').wrap('<tr class="fax-base-row" style="display: block;"><td style="width: 100%;display: inline-table;"></td></tr>');
			if (originalId)
				arrRowNo = originalId.split("-");

			if (arrRowNo.length > 1) {
				lastNo = arrRowNo[1];
			} else {
				lastNo = 0;
			}
			var newId = 'fax_collapse-' + (parseInt(lastNo) + 1);
			cloned.css('display', 'block');
			cloned.find(".fax_block").attr('id', newId);
			cloned.find(".fax_header").attr('href', '#' + newId);
			cloned.find(".fax_row_no").html($(".fax-base-row").length);

			cloned.find(".arr_other_fax").attr('name', "otherfax[" + (parseInt(lastNo) + 1) + "][fax]");
			cloned.find(".arr_otherfax_type").attr('name', "otherfax[" + (parseInt(lastNo) + 1) + "][fax_type]");
			cloned.find(".arr_otherfax_other_text_type").attr('name', "otherfax[" + (parseInt(lastNo) + 1) + "][other_text]");
            cloned.find(".arr_otherfax_other_text_type").attr('id', "arr_otherfax_type_" + (parseInt(lastNo) + 1) +"_otext");
            cloned.find(".arr_otherfax_type").attr('id', "arr_otherfax_type_" + (parseInt(lastNo) + 1));
			cloned.find(".arr_otherfax_other_text_div").attr('id', "arr_otherfax_type_" + (parseInt(lastNo) + 1)+"_div");

            cloned.find(".arr_otherfax_other_text_div").addClass('d-none');
			cloned.find('input[type=text]').val('');
            addinputmask('fax');
			cloned.find('.collapse').collapse('show');

		}

		function deleteFax(ele) {
			$(ele).closest(".fax-base-row").remove();

			$(".fax-base-row").each(function (index) {
				$(this).find(".fax_row_no").html(index + 1);
			});

			if ($(".fax-base-row").length < 1) {
				$("#fax_footer").html('<span>{{ trans("general.no_record_added") }}</span>');
				$("#fax_footer").show();
			}
		}

        

    </script>
<script type="text/javascript">
      
    // this code for set  google api autocomplete address dynamically add 
    function setAddressnew(count){   
        var autocompletes  = [];
        var options = {
            types: ['geocode'],
            language: '{{ App::isLocale('hr') ? 'iw' : 'en' }}',
        //componentRestrictions: { country: ["fr","IL","us","UK"] } 
        };
        var input = document.getElementById('address_complete_' + count);
        var autocomplete = new google.maps.places.Autocomplete(input, options);
        autocomplete.inputId = input.id;
        autocomplete.count = count;
        autocomplete.addListener('place_changed', fillIn);
        autocompletes.push(autocomplete);
    }
     // find the country id fron given json
     function getcountryid(cntr, value) {
        for (var i = 0; i < cntr.length; i++){
            if (cntr[i].code == value){
                return cntr[i].id;
                }
            }
            return 0;
        }
    function fillIn() {
        const componentForm = {
          street_number: "long_name",
          route: "long_name",
          locality: "long_name",
          administrative_area_level_1: "long_name",
          country: "short_name",
          postal_code: "short_name",
        };
        var cntr=@json($countries);
        var place = this.getPlace();
      
        document.getElementById('address2_'+this.count).value="";
        document.getElementById('state_'+this.count).value="";
        document.getElementById('city_'+this.count).value="";
        document.getElementById('zipcode_'+this.count).value="";      
        for (const component of place.address_components) {
                const addressType = component.types[0];
                if (componentForm[addressType]) {
                const val = component[componentForm[addressType]];
                    if(addressType==="country"){
                        var countryid=getcountryid(cntr, val);
                        if(countryid!=0){
                            document.getElementById('country_'+this.count).value=countryid;
                        }  
                    }
                    if(addressType==="street_number"){
                        if(val)
                        {document.getElementById('address2_'+this.count).value+=val+' ';}
                    }
                    if(addressType==="route"){
                        if(val)
                        document.getElementById('address2_'+this.count).value+=val;
                    }
                    if(addressType==="administrative_area_level_1"){
                        document.getElementById('state_'+this.count).value=val;
                    }
                    if(addressType==="locality"){
                        document.getElementById('city_'+this.count).value=val;
                    }
                    if(addressType==="postal_code"){
                        document.getElementById('zipcode_'+this.count).value=val;
                    }
                }
            }
      
    }
 
</script>
@endsection