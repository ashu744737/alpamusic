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
                            {{-- <a href="/" class="logo logo-admin"><img src="{{ URL::asset('/images/logo-dark.png')}}"  height="50" alt="logo"></a> --}}
                            <span style="color: blue;font-size:20px;">AlpaMusic</span>
                        
                        </h3>
                        <div class="p-3">
                            <h4 class="text-muted font-size-18 mb-1 text-center">{{ trans('form.delivery_boy_reg'). ' ' .trans('form.registration.register') }}</h4>
                            <p class="text-muted text-center">{{ trans('form.registration.get_your_free') }} {{ trans('form.delivery_boy_reg') }} {{ trans('form.registration.account_now') }}</p>
                            @error('g-recaptcha-response')
                            <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                               {{ trans('form.captcha_is_required') }}
                            </div>
                            @enderror

                            @if (session('warning'))
                            <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                {{ session('warning') }}
                            </div>
                            @endif
                            
                            <form name="delivery-form" id="delivery-form" onSubmit="return dosubmit();" class="form-horizontal mt-4" method="POST" action="{{ route('register.storeDeliveryboy') }}">

                                @csrf

                                <input type="hidden" name="type_id" value="{{$typeid}}">

                                <div id="next_step1">

                                    <div class="form-group">
                                        <label for="username">{{ trans('form.name') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="name" value="{{ old('name') }}" required autocomplete="name" class="form-control @error('name') is-invalid @enderror" autofocus id="name" placeholder="{{ trans('form.enter_name') }}">
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="useremail">{{ trans('form.email_address') }} <span class="text-danger">*</span></label>
                                          <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" id="useremail" placeholder="{{ trans('form.enter_email') }}" autocomplete="email" required>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                        <label for="userpassword">{{ trans('form.password') }} <span class="text-danger">*</span></label>
                                        <input type="password" minlength="8" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" id="userpassword" placeholder="{{ trans('form.enter_password') }}">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="userpassword">{{ trans('form.registration.confirm_password') }}</label>
                                            <input type="password" minlength="8" name="password_confirmation" class="form-control" id="userconfirmpassword" required placeholder="{{ trans('form.registration.confirm_password') }}">
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
                                            <label>{{ trans('form.registration.deliveryboy.delivery_areas') }} <span class="text-danger">*</span></label>
                                             <select multiple="multiple" class="select2 form-control select2-multiple input_required_s2" id="deliveryarea_id" name="deliveryarea_id[]" data-placeholder="{{ trans('form.registration.deliveryboy.delivery_helper') }}">
                                                @foreach($deliveryareas as $id => $deliveryarea)
                                                    <option value="{{ $deliveryarea['id'] }}" {{ old('deliveryarea_id') == $deliveryarea['id'] ? 'selected' : '' }}>{{ App::isLocale('hr')?$deliveryarea['hr_area_name']:$deliveryarea['area_name'] }}</option>
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
                                        <tr><td id="address_footer" class="text-center"> {{ trans('form.registration.deliveryboy.no_record_added') }} </td></tr>
                                    </tfoot>
                                    </table>
                                    </div>
                                    </div>
                                    <div class="form-row">
                                    <div class="col-md-12 text-right">
                                    <button type="button" onclick="addNewAddress()"
                                        class="btn btn-link btn-lg waves-effect"
                                        style="text-decoration: underline">{{ trans('form.registration.deliveryboy.add_address') }}
                                    </button>
                                    </div>
                                    </div>
                                    </div>
                                    <!-- Block for multiple addresses-->

                                    <div class="form-group row mt-4">
                                        <div class="col-12 text-right">
                                            <button onclick="loadSteps('step1', 'step2');"
                                                    class="btn btn-secondary w-md waves-effect waves-light"
                                                    type="button">{{ trans('general.previous') }}</button>
                                            <button onclick="loadSteps('step3', 'step2');"
                                                    class="btn btn-primary w-md waves-effect waves-light"
                                                    type="button">{{ trans('general.next') }}</button>
                                        </div>
                                    </div>
                                </div>

                                <div id="next_step3">
                                   

                                    <!-- Multiple Client Emails-->
                                    <div class="table-wrapper">
                                        <div class="table-responsive mb-0 fixed-solution"
                                             data-pattern="priority-columns">
                                            <div id="emails_tbl">
                                                <table class="table mb-0">
                                                    <thead>
                                                    <tr>
                                                        <th colspan="2" class="btn-primary"><h4
                                                                class="font-size-18 mb-1">{{ trans('form.registration.client.email') }}</h4>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="rows_client_email">
                                                    </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <td id="emails_tbl_footer" class="text-center"> {{ trans('form.registration.deliveryboy.no_record_added') }}
                                                        </td>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-12 text-right">
                                                <button type="button" onclick="addNewContactInfoField('email')"
                                                        class="btn btn-link btn-lg waves-effect"
                                                        style="text-decoration: underline">{{ trans('form.registration.deliveryboy.add_email') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Multiple Phone rows -->
                                    <div class="table-wrapper">
                                        <div class="table-responsive mb-0 fixed-solution"
                                             data-pattern="priority-columns">
                                            <div id="tbl_phone">
                                                <table class="table mb-0">
                                                    <thead>
                                                    <tr>
                                                        <th colspan="2" class="btn-primary"><h4
                                                                class="font-size-18 mb-1">{{ trans('form.registration.client.phone') }}</h4>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="rows_client_phone">
                                                    </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <td id="phone_tbl_footer" class="text-center"> {{ trans('form.registration.deliveryboy.no_record_added') }}
                                                        </td>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-12 text-right">
                                                <button type="button" onclick="addNewContactInfoField('phone')"
                                                        class="btn btn-link btn-lg waves-effect"
                                                        style="text-decoration: underline">{{ trans('form.registration.deliveryboy.add_phone') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-wrapper">
                                        <div class="table-responsive mb-0 fixed-solution"
                                             data-pattern="priority-columns">
                                            <div id="tbl_mobile">
                                                <table class="table mb-0">
                                                    <thead>
                                                    <tr>
                                                        <th colspan="2" class="btn-primary"><h4
                                                                class="font-size-18 mb-1">{{ trans('form.registration.client.mobile') }}</h4>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="rows_client_mobile">
                                                    </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <td id="mobile_tbl_footer" class="text-center"> {{ trans('form.registration.deliveryboy.no_record_added') }}
                                                        </td>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-12 text-right">
                                                <button type="button" onclick="addNewContactInfoField('mobile')"
                                                        class="btn btn-link btn-lg waves-effect"
                                                        style="text-decoration: underline">{{ trans('form.registration.deliveryboy.add_mobile') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-wrapper">
                                        <div class="table-responsive mb-0 fixed-solution"
                                             data-pattern="priority-columns">
                                            <div id="tbl_fax">
                                                <table class="table mb-0">
                                                    <thead>
                                                    <tr>
                                                        <th colspan="2" class="btn-primary"><h4
                                                                class="font-size-18 mb-1">{{ trans('form.registration.client.fax') }}</h4>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="rows_client_fax">
                                                    </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <td id="fax_tbl_footer" class="text-center"> {{ trans('form.registration.deliveryboy.no_record_added') }}
                                                        </td>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-12 text-right">
                                                <button type="button" onclick="addNewContactInfoField('fax')"
                                                        class="btn btn-link btn-lg waves-effect"
                                                        style="text-decoration: underline">{{ trans('form.registration.deliveryboy.add_fax') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>

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
                                                   class="form-control @error('bank_number') is-invalid @enderror"
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
                                        <div class="g-recaptcha m-b-10" data-sitekey="{{ env('GOOGLE_RECAPTCHA_SITE_KEY') }}">
                                        </div>
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
                    <p>  {{ trans('general.by') }} <a target="_blank" href="https://soft-l.com/">{{ config('constants.company_name') }}</a></p>
                </div>
        </div>
    </div>
</div>
</div>

<div class="card mb-1 shadow-none" id="address_clone_element" style="display: none">
    <div class="card-header p-3" id="address_heading"
      style="padding-bottom: 0 !important;">
     <div class="row"
          style="padding-right: 0; padding-left: 0">
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
             <div class="form-group col-md-3">
                 <label>{{ trans('form.registration.client.city') }}
                     <span
                         class="text-danger">*</span></label>
                 <input type="text" value=""
                        class="form-control multiple_input_required_s2 arr_address_city"
                        name="address[]['city']"
                        placeholder="{{ trans('form.registration.client.city') }}"
                        required>
             </div>

             <div class="form-group col-md-3">
                 <label>{{ trans('form.registration.client.state') }}
                     <span
                         class="text-danger">*</span></label>
                 <input type="text" value=""
                        class="form-control multiple_input_required_s2 arr_address_state"
                        name="address[]['state']"
                        placeholder="{{ trans('form.registration.client.state') }}"
                        required>
             </div>

             <div class="form-group col-md-3">
                 <label>{{ trans('form.registration.client.country') }}
                     <span
                         class="text-danger">*</span></label>
                 <select name="address[]['country_id']"
                         class="form-control multiple_input_required_s2 arr_address_country"
                         required>
                         @foreach($countries as  $country_name)
                         <option value="{{ $country_name['id'] }}" {{ old('country_id') == $country_name['id'] ? 'selected' : '' }}>{{ App::isLocale('hr')?$country_name['hr_name']:$country_name['en_name'] }}</option>
                       @endforeach
                 </select>
             </div>

             <div class="form-group col-md-3">
                 <label>{{ trans('form.registration.client.zip_code') }}
                     </label>
                 <input type="text" value=""
                        class="form-control numeric arr_address_zip"
                        name="address[]['zipcode']"
                        placeholder="{{ trans('form.registration.client.zip_code') }}"
                        >
             </div>
         </div>
     </div>
    </div>
</div>

<table style="display: none;">
    <tbody>
        <tr id="contact_email_clone_row">
            <td><input class="form-control multiple_input_required_s3" type="email" placeholder="{{trans('general.email_placeholder')}}"
                       name="other_email[]" required></td>
            <td class="text-right">
                <button type="button" onclick="deleteRows(this)" class="btn btn-link waves-effect"
                        style="text-decoration: underline">{{ trans('general.delete') }}
                </button>
            </td>
        </tr>
        <tr id="contact_phone_clone_row">
            <td><input class="arr_other_phone form-control multiple_input_required_s3" type="tel" placeholder="{{trans('general.phone_placeholder')}}"
                       name="other_phone[]" required></td>
            <td class="text-right">
                <button type="button" onclick="deleteRows(this)" class="btn btn-link waves-effect"
                        style="text-decoration: underline">{{ trans('general.delete') }}
                </button>
            </td>
        </tr>
        <tr id="contact_mobile_clone_row">
            <td><input class="arr_other_mobile form-control multiple_input_required_s3" type="tel" placeholder="{{trans('general.phone_placeholder')}}"
                       name="other_mobile[]" required></td>
            <td class="text-right">
                <button type="button" onclick="deleteRows(this)" class="btn btn-link waves-effect"
                        style="text-decoration: underline">{{ trans('general.delete') }}
                </button>
            </td>
        </tr>
        <tr id="contact_fax_clone_row">
            <td><input class="arr_other_fax form-control multiple_input_required_s3" type="tel" placeholder="{{trans('general.fax_placeholder')}}"
                       name="other_fax[]" required></td>
            <td class="text-right">
                <button type="button" onclick="deleteRows(this)" class="btn btn-link waves-effect"
                        style="text-decoration: underline">{{ trans('general.delete') }}
                </button>
            </td>
        </tr>
    </tbody>
</table>

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
  
    {{--<script type="text/javascript" src="{{ asset('js/client-register.js') }}"></script>--}}

    <script type="text/javascript">

        $(document).ready(function($) {

            $("#delivery-form").validate({
                ignore: false,
                invalidHandler: function (e, validator) {
                    // loop through the errors:
                    for (var i = 0; i < validator.errorList.length; i++) {
                        $(validator.errorList[i].element).closest('.collapse').collapse('show');
                        $(this).find(":input.error:first").focus();
                    }
                }
            });

            $(window).resize(function() {
                rescaleCaptcha();
            });

            $('#dob').datepicker({
                autoclose: true,
                todayHighlight: true
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

        // This function for Set Responsive Google ReCaptcha
        function rescaleCaptcha() {
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

        //for custom validation before form submit to check all multiple fields has entries
        function dosubmit() {
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
        }

        // function step 1 and step 2 show hide
        function loadSteps(step, backstep) {
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

                if((backstep == "step1" && $("#delivery-form").valid() === true) || backstep == "step3"){
                    $('#next_step2').show();
                    $('#next_step1').hide();
                    $('#next_step3').hide();
                    $('#family').focus();

                    if(backstep == "step3"){
                        $('.multiple_input_required_s3').each(function() {
                            // console.log('removing rule of :>>', $(this));
                            $(this).rules('remove', 'required');
                            $(this).removeAttr('required');
                        });
                        $('.input_required_s3').each(function() {
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

                    $('.input_required_s2').each(function(e) {
                        // console.log('adding rule of :>>', $(this));
                        $(this).rules('add', {
                            required: true
                        });
                        $(this).attr("required", true);
                    });
                }

            } else if (step == 'step3') {

                if ($("#delivery-form").valid() === true) {
                    if ($(".address-base-row").length < 1) {
                        $("#address_footer").html('<span class="error">{{ trans('general.minimum_entry') }}</span>');

                        return false;
                    } else {
                        $("#address_footer").hide('');
                    }

                    $('.multiple_input_required_s3').each(function(e) {
                        // console.log('adding rule of :>>', $(this));
                        $(this).rules('add', {
                            required: true
                        });
                        $(this).attr("required", true);
                    });
                    $('.input_required_s3').each(function(e) {
                        // console.log('adding rule of :>>', $(this));
                        $(this).rules('add', {
                            required: true
                        });
                        $(this).attr("required", true);
                    });

                    $('#next_step3').show();
                    $('#next_step2').hide();
                }
            }
        }

        // Functions for multiple addresses/contacts fields
        function customFormValidation()
        {
            var isValid = false;
            var cnt = 0;
            var basicValidation = $("#delivery-form").valid();

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
                    if ($(this).attr('id') == 'rows_client_fax' || $(this).attr('id') == 'rows_client_email'  || $(this).attr('id') == 'rows_client_phone' || $(this).attr('id') == 'rows_client_mobile') {
                    //if ($(this).attr('id') == 'rows_client_fax') {
                        //fax row is not mandatory - so skip and increment validation cnt
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

        function addNewAddress() {
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
           
            cloned.find('input[type=text]').val('');
            setAddressnew((parseInt(lastNo) + 1));
            cloned.find('.collapse').collapse('show');
        }

        function deleteAddress(ele) {
            $(ele).closest(".address-base-row").remove();

            $(".address-base-row").each(function (index) {
                $(this).find(".address_row_no").html(index + 1);
            });

            if ($(".address-base-row").length < 1) {
                $("#address_footer").html('<span>{{ trans("general.no_record_added") }}</span>');
                $("#address_footer").show();
            }
        }
      

        function deleteRows(ele) {
            $(ele).closest('tr').remove();

            if ($(ele).parent('tbody').find("tr").length < 1) {
                var footerEle = $(ele).parent().find("tfoot tr td");
                $(footerEle).html('<span>{{ trans("general.no_record_added") }}</span>');
                $(footerEle).show();
            }
        }

        function addNewContactInfoField(type) {
            var cloned;

            switch (type) {
                case 'email' :
                    $("#emails_tbl_footer").hide();
                    cloned = $("#contact_email_clone_row").clone().appendTo("#rows_client_email");
                    break;
                case 'phone' :
                    $("#phone_tbl_footer").hide();
                    cloned = $("#contact_phone_clone_row").clone().appendTo("#rows_client_phone");
                    addinputmask('phone');
                    break;
                case 'mobile' :
                    $("#mobile_tbl_footer").hide();
                    cloned = $("#contact_mobile_clone_row").clone().appendTo("#rows_client_mobile");
                    addinputmask('mobile');
                    break;
                case 'fax' :
                    $("#fax_tbl_footer").hide();
                    cloned = $("#contact_fax_clone_row").clone().appendTo("#rows_client_fax");
                    addinputmask('fax');
                    break;
                default:
                    console.log('no row added');
            }
            cloned.removeAttr('id');
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
                        {document.getElementById('address2_'+this.count).value+=val+',';}
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