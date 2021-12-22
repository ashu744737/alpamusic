@extends('layouts.master')

@section('title') {{trans('form.registration.client.edit_client_title')}} @endsection

@section('headerCss')
    <!-- headerCss -->
    <link href="{{ URL::asset('/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet">`
    <link href="{{ URL::asset('/libs/jquery-smartwizard/smart_wizard.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('/libs/jquery-smartwizard/smart_wizard_arrows.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/css/custom.css') }}" rel="stylesheet" type="text/css" />
     <style>

        .card-title-desc{
            margin-bottom: 0 !important;
        }
        .sw-theme-arrows > .nav .nav-link.active{
        color: #ffffff !important;
        border-color: #105C8D !important;
        background: #105C8D !important;
        }
        .sw-theme-arrows > .nav .nav-link.active::after {
        border-left-color: #105C8D !important;
        }

        hr{
            margin-top: 3px !important;
        }

        #smartwizard .tab-content{
            height: auto !important;
        } 
    </style>
    @if (App::isLocale('hr'))
    <style>
    .sw-theme-arrows > .nav .nav-link.active {
        
        margin-right: 0;
    }

    .sw-theme-arrows > .nav .nav-link::before {
        content: " ";
        position: absolute;
        display: block;
        width: 0;
        height: 0;
        top: 50%;
        right: 100%;
        margin-top: -50px;
        margin-left: 1px;
        border-top: 50px solid transparent;
        border-bottom: 50px solid transparent;
        border-left: 30px solid #eeeeee;
        z-index: 1;
        transform: rotate(180deg);
        -webkit-transform: rotate(180deg);
    }

    .sw-theme-arrows > .nav .nav-link::after {
        content: "";
        position: absolute;
        display: block;
        width: 0;
        height: 0;
        top: 50%;
        right: 100%;
        margin-top: -50px;
        border-top: 50px solid transparent;
        border-bottom: 50px solid transparent;
        border-left: 30px solid #f8f8f8;
        z-index: 2;
        transform: rotate(135deg);
        -webkit-transform: rotate(180deg);
        margin-right: ;
    }
    .sw-theme-arrows > .nav .nav-link {
        
        margin-left: 0px !important; 
    
    }
    .sw-theme-arrows > .nav .nav-link.inactive {
    
    margin-right: 0 !important;
    }
    </style>
    @endif

@endsection

@section('content')

    <div class="col-sm-6">
        <div class="page-title-box">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{route('clients')}}">{{ trans('form.clients') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript: void(0);">{{ trans('general.edit') }}</a></li>
            </ol>
        </div>
    </div>

    <!-- content -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">

                        <div class="col-12">
                            <h4 class="card-title">{{trans('form.registration.client.edit_client_title')}}</h4>
                            <p class="card-title-desc">{{trans('form.registration.client.edit_client_desc')}}</p>
                        </div>

                        <div class="col-12">

                            <form name="client-form" id="client-form" class="form-horizontal mt-4" method="POST" action="{{ route('client.update', $client->id) }}" onSubmit="return dosubmit();" >

                                @csrf
                                @method('PUT')

                                <input type="hidden" name="type_id" value="{{$typeid}}">

                                <div id="smartwizard">

                                    <ul class="nav">
                                        <li class="nav-item">
                                            <a class="nav-link" href="#step-1">
                                                <strong>{{ trans('form.step_1') }}</strong> <br>{{ trans('form.registration.client.basic_details') }}
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#step-2">
                                                <strong>{{ trans('form.step_2') }}</strong> <br>{{ trans('form.registration.client.personal_details') }}
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#step-3">
                                                <strong>{{ trans('form.step_3') }}</strong> <br>{{ trans('form.registration.client.contact_details') }}
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">
                                            <div class="row">
                                                <div class="col-12 p-4">
                                                    <div id="next_step1">

                                                        <div class="form-row">
                                                            <div class="col-md-12">
                                                                <h4 class="text-muted font-size-18 mb-1">{{ trans('form.registration.client.basic_details') }}</h4>
                                                                <hr>
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <div class="form-group">
                                                                    <label for="username">{{ trans('form.name') }} <span class="text-danger">*</span></label>
                                                                    <input type="text" name="name" value="{{ old('name', $client->name) }}" required autocomplete="name" class="form-control @error('name') is-invalid @enderror" autofocus id="name" placeholder="{{ trans('form.enter_name') }}">
                                                                    @error('name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <div class="form-group">
                                                                    <label for="useremail">{{ trans('form.email_address') }} <span class="text-danger">*</span></label>
                                                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $client->email) }}" id="useremail" placeholder="{{ trans('form.enter_email') }}" autocomplete="email" required>
                                                                    @error('email')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row mt-4">
                                                            <div class="col-12 text-right">
                                                                <button onclick="loadSteps('step2', 'step1');" class="btn btn-primary w-md waves-effect waves-light" type="button">{{ trans('general.next') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">
                                            <div class="row">
                                                <div class="col-12 p-4">
                                                    <div id="next_step2">

                                                        <!-- Block for Personal detail-->
                                                        <div class="form-row">
                                                            <div class="col-md-12">
                                                                <h4 class="text-muted font-size-18 mb-1">{{ trans('form.registration.client.personal_details') }}</h4>
                                                                <hr>
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="client_type_id">{{ trans('form.registration.client.customer_type') }}</label>
                                                                <select name="client_type_id" id="client_type_id" class="form-control @error('type_id') is-invalid @enderror" required>
                                                                    @foreach($clienttypes as $id => $client_type)
                                                                        <option value="{{ $client_type['id'] }}" {{ old('client_type_id', $client->client->client_type_id) == $client_type['id'] ? 'selected' : '' }}>
                                                                            {{ App::isLocale('hr')?$client_type['hr_type_name']:$client_type['type_name'] }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('client_type_id')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group col-md-6">
                                                                <label>{{ trans('form.registration.client.printable_name') }} <span class="text-danger">*</span></label>
                                                                <input type="text" value="{{ old('printname', $client->client->printname) }}" class="form-control input_required_s2" id="printname" name="printname" placeholder="{{ trans('form.registration.client.printable_name') }}">
                                                                @error('printname')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label>{{ trans('form.registration.client.legal_entity_id') }} <span class="text-danger">*</span></label>
                                                                <input type="number" value="{{ old('legal_entity_no', $client->client->legal_entity_no) }}" class="form-control input_required_s2" id="legal_entity_no" name="legal_entity_no" placeholder="{{ trans('form.registration.client.legal_entity_id') }}" onkeypress="settheSubjectId(this)" maxwidth="9">
                                                                <label id="id-error" class="id_error error d-none" for="id-error">{{trans('form.registration.investigation.id_error')}}</label>
                                                                @error('legal_entity_no')
                                                                <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group col-md-6">
                                                                <label>{{ trans('form.registration.client.website') }}</label>
                                                                <input type="text" value="{{ old('website', $client->client->website) }}" class="form-control" id="website" name="website" placeholder="{{ trans('form.registration.client.website') }}">
                                                                @error('website')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <!-- Block for Personal detail-->

                                                        <!-- Block for multiple addresses-->
                                                        <div class="table-wrapper">
                                                            <div class="table-responsive mb-0 fixed-solution"
                                                                 data-pattern="priority-columns">
                                                                <div id="addresses_tbl">
                                                                    <table class="table table-borderless mb-0">
                                                                        <thead>
                                                                        <tr>
                                                                            <th class="btn-primary"><h4 class="font-size-18 mb-1">{{ trans('form.registration.client.address_details') }}</h4>
                                                                            </th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody id="address-accordion">
                                                                        @php $addrIndx = 1; @endphp
                                                                        @foreach($client->userAddresses as $address)
                                                                            <tr class="address-base-row">
                                                                                <td style="width: 100%;display: inline-table;">
                                                                                    <div class="card mb-1 shadow-none" id="address_clone_element-{{$addrIndx}}">
                                                                                        <div class="card-header p-3"
                                                                                             id="address_heading"
                                                                                             style="padding-bottom: 0 !important;">
                                                                                            <div class="row"
                                                                                                 style="padding-right: 0; padding-left: 0">
                                                                                                <div class="col-sm-12 col-md-6 text-left">
                                                                                                    <h6 class="m-0 font-size-14">
                                                                                                        <a href="#address_collapse-{{ $addrIndx }}"
                                                                                                           class="text-dark address_header"
                                                                                                           data-toggle="collapse"
                                                                                                           aria-expanded="true"
                                                                                                           aria-controls="collapse_1">
                                                                                                           {{ trans('form.registration.client.address') }} : <span id="arr_address_type_{{$addrIndx}}_title" class="address_row_no">
                                                                                                           @if(config('app.locale') == 'hr')
                                                                                                            {{ !is_null(\App\ContactTypes::where('type_name', $address->address_type)->first())?\App\ContactTypes::where('type_name', $address->address_type)->first()->hr_type_name:(!is_null($address->address_type)?$address->address_type:'') }}
                                                                                                            @else
                                                                                                            {{ !is_null(\App\ContactTypes::where('type_name', $address->address_type)->first())?\App\ContactTypes::where('type_name', $address->address_type)->first()->type_name:(!is_null($address->address_type)?$address->address_type:'') }}
                                                                                                            @endif
                                                                                                           </span>
                                                                                                        </a>
                                                                                                    </h6>
                                                                                                </div>
                                                                                                <div class="col-sm-12 col-md-6 text-sm-right">
                                                                                                    <button type="button"
                                                                                                            onclick="deleteAddress(this)"
                                                                                                            class="btn btn-link waves-effect"
                                                                                                            style="text-decoration: underline;">
                                                                                                            {{ trans('general.delete') }}
                                                                                                    </button>
                                                                                                </div>
                                                                                            </div>

                                                                                        </div>

                                                                                        <div id="address_collapse-{{ $addrIndx }}"
                                                                                             class="address_block collapse show"
                                                                                             aria-labelledby="address_heading"
                                                                                             data-parent="#address-accordion"
                                                                                             style="">
                                                                                            <div class="card-body">
                                                                                                <div class="form-row">
                                                                                                    <div class="form-group col-md-6">
                                                                                                        <label>{{ trans('form.registration.client.address') }}
                                                                                                            <span class="text-danger">*</span></label>
                                                                                                        <input type="text"
                                                                                                               value="{{ $address->address1 }}"
                                                                                                               class="form-control pac-target-input multiple_input_required_s2 arr_address_address1"
                                                                                                               name="address[{{$addrIndx}}][address1]"
                                                                                                               id="address_complete_{{$addrIndx}}"
                                                                                                               placeholder="{{ trans('form.search_address') }}"
                                                                                                               autocomplete="off"
                                                                                                               required="required">
                                                                                                    </div>

                                                                                                    <div class="form-group col-md-6">
                                                                                                        <label>{{ trans('form.registration.client.address_helper') }}</label>
                                                                                                        <input type="text"
                                                                                                        id="address2_{{$addrIndx}}"
                                                                                                               value="{{ $address->address2 }}"
                                                                                                               class="form-control arr_address_address2"
                                                                                                               name="address[{{$addrIndx}}][address2]"
                                                                                                               placeholder="{{ trans('form.registration.client.address_2_helper') }}">
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div class="form-row">
                                                                                                    <div class="form-group col-md-4">
                                                                                                        <label>{{ trans('form.registration.client.city') }}
                                                                                                            <span class="text-danger">*</span></label>
                                                                                                        <input type="text"
                                                                                                             id="city_{{$addrIndx}}"
                                                                                                               value="{{ $address->city }}"
                                                                                                               class="form-control multiple_input_required_s2 arr_address_city"
                                                                                                               name="address[{{$addrIndx}}][state]"
                                                                                                               placeholder="City"
                                                                                                               required="required">
                                                                                                    </div>

                                                                                                    <div class="form-group col-md-4">
                                                                                                        <label>{{ trans('form.registration.client.state') }}
                                                                                                            <span class="text-danger">*</span></label>
                                                                                                        <input type="text"
                                                                                                               value="{{ $address->state }}"
                                                                                                               id="state_{{$addrIndx}}"
                                                                                                               class="form-control multiple_input_required_s2 arr_address_state"
                                                                                                               name="address[{{$addrIndx}}][city]"
                                                                                                               placeholder="State"
                                                                                                               required="required">
                                                                                                    </div>

                                                                                                    <div class="form-group col-md-4">
                                                                                                        <label>{{ trans('form.registration.client.country') }}
                                                                                                            <span class="text-danger">*</span></label>
                                                                                                        <select  id="country_{{$addrIndx}}" name="address[{{$addrIndx}}][country_id]"
                                                                                                                class="form-control multiple_input_required_s2 arr_address_country"
                                                                                                                required="required">
                                                                                                                @foreach($countries as $id => $country_name)
                                                                                                            <option value="{{$country_name['id']}}" {{ $address->country_id == $country_name['id']? 'selected' : '' }}>
                                                                                                                {{ App::isLocale('hr')?$country_name['hr_name']:$country_name['en_name'] }}
                                                                                                            </option>
                                                                                                            
                                                                                                            @endforeach
                                                                                                           
                                                                                                        </select>
                                                                                                    </div>

                                                                                                </div>
                                                                                                <div class="form-row">
                                                                                                    <div class="form-group col-md-4">
                                                                                                        <label>{{ trans('form.registration.client.zip_code') }}
                                                                                                            </label>
                                                                                                        <input type="text"
                                                                                                              id="zipcode_{{$addrIndx}}"
                                                                                                               value="{{ $address->zipcode }}"
                                                                                                               class="form-control numeric arr_address_zip"
                                                                                                               name="address[{{$addrIndx}}][zipcode]"
                                                                                                               placeholder="Zip Code"
                                                                                                               >
                                                                                                    </div>

                                                                                                    <div class="form-group col-md-4">
                                                                                                       
                                                                                                         @php $seloth=0; @endphp
                                                                                                         @foreach($contacttypes as $id => $contact_name)
                                                                                                             @if($contact_name['type_name']==$address->address_type)
                                                                                                             @php $seloth=1; @endphp
                                                                                                             @endif
                                                                                                         @endforeach
                                                                                                              
                                                                                                        <label>{{ trans('form.registration.client.address_type') }}</label>
                                                                                                        <select id="arr_address_type_{{$addrIndx}}" onchange="changeotheroldtextbox(this);changesubjecttitlebyid(this.id);" name="address[{{$addrIndx}}][address_type]"
                                                                                                                class="form-control arr_address_type multiple_type_required_s2" required>
                                                                                                                
                                                                                                            @foreach($contacttypes as $id => $contact_name)
                                                                                                           <option {{ (($seloth==0) || ($contact_name['type_name'] == $address->address_type)) ? 'selected' : '' }} value="{{ $contact_name['type_name'] }}">
                                                                                                            {{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}
                                                                                                            </option>
                                                                                                             @endforeach
                                                                                                        </select>
                                                                                                    </div>
                                                                                                    <div id="arr_address_type_{{$addrIndx}}_div" class="form-group col-md-4 arr_add_other_text_div {{ ($seloth==0 || ($address->address_type == 'Other' || $address->address_type == 'Contact'))? 'd-block' : 'd-none' }} ">
                                                                                                        <label class="address_other_no">{{ trans('form.registration.client.type') }}</label>
                                                                                                            
                                                                                                        <input type="text" onchange="changeothersubjecttitle(this);"  value="{{ ($seloth==0) ? $address->address_type  : '' }}"
                                                                                                            class="form-control numeric arr_add_other_text_type"
                                                                                                            name="address[{{$addrIndx}}][other_text]"
                                                                                                            placeholder="{{ trans('form.registration.client.type_helper') }}"
                                                                                                            id="arr_address_type_{{$addrIndx}}_otext" data-id="arr_address_type_{{$addrIndx}}"
                                                                                                           {{--  {{ ($seloth==0 || ($address->address_type == 'Other' || $address->address_type == 'Contact'))? 'required' : '' }} --}}
                                                                                                            >
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            @php $addrIndx++; @endphp
                                                                        @endforeach

                                                                        </tbody>
                                                                        <tfoot>
                                                                            <tr>
                                                                                <td id="address_footer" class="text-center" style="{{ !$client->userAddresses->isEmpty() ? 'display:none' : '' }}"> {{ trans('form.registration.deliveryboy.no_record_added') }} </td>
                                                                            </tr>
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
                                                </div>
                                            </div>
                                        </div>
                                        <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">
                                            <div class="row">
                                                <div class="col-12 p-4">
                                                    <div id="next_step3">

                                                        <div class="form-row">
                                                            <div class="col-md-12">
                                                                <h4 class="text-muted font-size-18 mb-1">{{ trans('form.registration.client.contact_details') }}</h4>
                                                                <hr>
                                                            </div>
                                                        </div>

                                                        <!-- Block for multiple contacts-->
                                                        <div class="table-wrapper">
                                                            <div class="table-responsive mb-0 fixed-solution"
                                                                 data-pattern="priority-columns">
                                                                <div id="emails_tbl">
                                                                    <table class="table table-borderless mb-0">
                                                                        <thead>
                                                                        <tr>
                                                                            <th class="btn-primary">
                                                                                <h4 class="font-size-18 mb-1">{{ trans('form.registration.client.contact_details') }}</h4>
                                                                            </th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody id="contact-accordion">

                                                                        @php $cntIndx = 1; @endphp
                                                                        @foreach($client->userContacts as $contact)
                                                                            <tr class="contact-base-row">
                                                                                <td style="width: 100%;display: inline-table;">
                                                                                    <div class="card mb-1 shadow-none" id="contact_clone_element-{{$cntIndx}}">
                                                                                        <div class="card-header p-3"
                                                                                             id="heading_contact"
                                                                                             style="padding-bottom: 0 !important;">
                                                                                            <div class="row"
                                                                                                 style="padding-right: 0; padding-left: 0">
                                                                                                <div class="col-sm-12 col-md-6 text-left">
                                                                                                    <h6 class="m-0 font-size-14">
                                                                                                        <a href="#contact_collapse-{{$cntIndx}}"
                                                                                                           class="text-dark contact_header"
                                                                                                           data-toggle="collapse"
                                                                                                           aria-expanded="true"
                                                                                                           aria-controls="contact_collapse">
                                                                                                           {{ trans('form.contact.contact') }} : <span id="arr_contact_type_{{$cntIndx}}_title" class="contact_row_no">
                                                                                                           @if(config('app.locale') == 'hr')
                                                                                                            {{ !is_null(\App\ContactTypes::where('type_name', $contact->contact_type)->first())?\App\ContactTypes::where('type_name', $contact->contact_type)->first()->hr_type_name:(!is_null($contact->contact_type)?$contact->contact_type:'') }}
                                                                                                            @else
                                                                                                            {{ !is_null(\App\ContactTypes::where('type_name', $contact->contact_type)->first())?\App\ContactTypes::where('type_name', $contact->contact_type)->first()->type_name:(!is_null($contact->contact_type)?$contact->contact_type:'') }}
                                                                                                            @endif
                                                                                                           </span>
                                                                                                        </a>
                                                                                                    </h6>
                                                                                                </div>
                                                                                                <div class="col-sm-12 col-md-6 text-sm-right">
                                                                                                    <button type="button"
                                                                                                            onclick="deleteContact(this)"
                                                                                                            class="btn btn-link waves-effect"
                                                                                                            style="text-decoration: underline">
                                                                                                            {{ trans('general.delete') }}
                                                                                                    </button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div id="contact_collapse-{{$cntIndx}}"
                                                                                             class="contact_block collapse show"
                                                                                             aria-labelledby="heading_contact"
                                                                                             data-parent="#contact-accordion"
                                                                                             style="">
                                                                                            <div class="card-body">
                                                                                                <div class="form-row">

                                                                                                    <div class="form-group col-md-4">
                                                                                                        <label>{{ trans('form.registration.client.phone') }} <span class="text-danger">*</span></label>
                                                                                                        <input type="text"
                                                                                                               value="{{$contact->phone}}"
                                                                                                               class="form-control arr_contacts_phone multiple_input_required_s3"
                                                                                                               name="contacts[{{$cntIndx}}][phone]"
                                                                                                               placeholder="{{trans('general.phone_placeholder')}}"
                                                                                                               data-im-insert="true"
                                                                                                               required="required">
                                                                                                    </div>
                                                                                                    <div class="form-group col-md-4">
                                                                                                        <label>{{ trans('form.registration.client.mobile') }}</label>
                                                                                                        <input type="text"  value="{{$contact->mobile}}"
                                                                                                            class="form-control arr_contacts_mobile" id="mobile"
                                                                                                            name="contacts[{{$cntIndx}}][mobile]"
                                                                                                            placeholder="{{trans('general.phone_placeholder')}}"
                                                                                                            data-im-insert="true">
                                                                                                    </div>
                                                                                                    <div class="form-group col-md-4">
                                                                                                        <label>{{ trans('form.registration.client.fax') }}</label>
                                                                                                        <input type="text"
                                                                                                               value="{{$contact->fax}}"
                                                                                                               class="form-control arr_contacts_fax"
                                                                                                               id="fax"
                                                                                                               name="contacts[{{$cntIndx}}][fax]"
                                                                                                               placeholder="(555)555-5555"
                                                                                                               data-im-insert="true">
                                                                                                    </div>                                                                               
                                                                                                    <div class="form-group col-md-6">
                                                                                                        <label>{{ trans('form.registration.client.Primary Email') }}
                                                                                                        </label>
                                                                                                        <input type="email" value="{{$contact->primary_email}}"
                                                                                                            class="form-control arr_contacts_primary_email"
                                                                                                            name="contacts[{{$cntIndx}}][primary_email]"
                                                                                                            placeholder="{{trans('form.registration.client.Primary Email')}}"
                                                                                                            data-im-insert="true">
                                                                                                    </div>
                                                                                                    <div class="form-group col-md-6">
                                                                                                        <label>{{ trans('form.registration.client.Secondary Email') }}
                                                                                                        </label>
                                                                                                        <input type="email" value="{{$contact->secondary_email}}"
                                                                                                            class="form-control arr_contacts_secondary_email"
                                                                                                            name="contacts[{{$cntIndx}}][secondary_email]"
                                                                                                            placeholder="{{ trans('form.registration.client.Secondary Email') }}"
                                                                                                            data-im-insert="true">
                                                                                                    </div>
                                                                                                </div>  
                                                                                                    <div class="form-row">
                                                                                                       
                                                                                                        <div class="form-group col-md-4">
                                                                                                       
                                                                                                            @php $seloth = 1; @endphp
                                                                                                            <label>{{ trans('form.registration.client.contact_type') }}</label>
                                                                                                            <select id="arr_contact_type_{{$cntIndx}}" onchange="changeotheroldtextbox(this);changesubjecttitlebyid(this.id);" name="contacts[{{$cntIndx}}][contact_type_id]"
                                                                                                                    class="form-control arr_contact_type" required>
                                                                                                                    
                                                                                                                @foreach($contacttypes as $id => $contact_name)
                                                                                                                <option {{ ((($contact_name['type_name'] == 'Other' || $contact_name['type_name'] == 'Contact') && $seloth==0) || ($contact_name['type_name'] == $contact->contact_type)) ? 'selected' : '' }} value="{{ $contact_name['type_name'] }}">
                                                                                                                {{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}
                                                                                                                </option>
                                                                                                                    @endforeach
                                                                                                            </select>
                                                                                                         </div>
                                                                                                        <div id="arr_contact_type_{{$cntIndx}}_div" class="form-group col-md-4 arr_contact_other_text_div {{ ($seloth == 0)? 'd-block' : 'd-none' }} ">
                                                                                                            <label class="address_other_no">{{ trans('form.registration.client.type') }}</label>
                                                                                                                
                                                                                                            <input type="text"  onchange="changeothersubjecttitle(this);" value="{{ ($seloth==0) ? $contact->contact_type  : '' }}"
                                                                                                                class="form-control numeric arr_contact_other_text_type"
                                                                                                                name="contacts[{{$cntIndx}}][other_text]"
                                                                                                                placeholder="{{ trans('form.registration.client.type_helper') }}"
                                                                                                                id="arr_contact_type_{{$cntIndx}}_otext" data-id="arr_contact_type_{{$cntIndx}}"
                                                                                                              {{--   {{ ($seloth==0 || ($contact->contact_type == 'Other' || $contact->contact_type == 'Contact'))? 'required' : '' }} --}}
                                                                                                                >
                                                                                                        </div>
                                                                                                        
                                                                                                        <div class="form-check col-md-3 form-check-inline mt-2 ">   
                                                                                                            <input id="contacts_default_{{$cntIndx}}" {{ ($contact->is_default) == 1 ? 'checked' : '' }} onchange="changradiobox(this)" type="checkbox" class="arr_contact_default form-check-input " name="contacts[{{$cntIndx}}][is_default]" id="contacts_is_default"
                                                                                                               >
                                                                                                              <label for="contacts_is_default" class="form-check-label">{{trans('form.contact.field.is_default')}}</label>
                                                                                                          </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            @php $cntIndx++; @endphp
                                                                        @endforeach

                                                                        </tbody>
                                                                        <tfoot>
                                                                        <tr>
                                                                            <td id="contacts_footer" class="text-center" style="{{ !$client->userContacts->isEmpty() ? 'display:none' : '' }}"> {{ trans('form.registration.deliveryboy.no_record_added') }}
                                                                            </td>
                                                                        </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                            </div>

                                                            <div class="form-row">
                                                                <div class="col-md-12 text-right">
                                                                    <button type="button" onclick="addNewContact()"
                                                                            class="btn btn-link btn-lg waves-effect"
                                                                            style="text-decoration: underline">{{ trans('form.registration.deliveryboy.add_contact') }}
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Block for multiple contacts-->
                                                        {{--
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
                                                            @php $emailIndx = 1; @endphp
                                                               @foreach($client->userEmails as $email)
                                                                <tr class="email-base-row">
                                                                    <td style="width: 100%;display: inline-table;">
                                                                        <div class="card mb-1 shadow-none" id="email_clone_element-{{$emailIndx}}">
                                                                            <div class="card-header p-3"
                                                                                 id="heading_email"
                                                                                 style="padding-bottom: 0 !important;">
                                                                                <div class="row"
                                                                                     style="padding-right: 0; padding-left: 0">
                                                                                    <div class="col-sm-12 col-md-6 text-left">
                                                                                        <h6 class="m-0 font-size-14">
                                                                                            <a href="#email_collapse-{{$emailIndx}}"
                                                                                               class="text-dark email_header"
                                                                                               data-toggle="collapse"
                                                                                               aria-expanded="true"
                                                                                               aria-controls="email_collapse">
                                                                                               {{ trans('form.registration.client.email') }} <span class="email_row_no">{{$emailIndx}}</span>
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

                                                                            <div id="email_collapse-{{$emailIndx}}"
                                                                                 class="email_block collapse show"
                                                                                 aria-labelledby="heading_email"
                                                                                 data-parent="#email-accordion"
                                                                                 style="">
                                                                                <div class="card-body">
                                                                                 
                                                                                        <div class="form-row">
                                                                                            
                                                                                            <div class="form-group col-md-4">
                                                                                                <label>{{ trans('form.registration.client.email') }} <span class="text-danger">*</span></label>
                                                                                                <input type="email" value="{{$email->value}}"
                                                                                                    class="form-control multiple_input_required_s3 arr_other_email" 
                                                                                                    name="otheremail[{{$emailIndx}}][email]"
                                                                                                    placeholder="{{trans('general.email_placeholder')}}"
                                                                                                    >
                                                                                            </div>
                                                                                            <div class="form-group col-md-4">
                                                                                           
                                                                                                @php $seloth=0; @endphp
                                                                                                @foreach($contacttypes as $id => $contact_name)
                                                                                                    @if($contact_name['type_name']==$email->email_type)
                                                                                                    @php $seloth=1; @endphp
                                                                                                    @endif
                                                                                                @endforeach
                                                                                                    
                                                                                                <label>{{ trans('form.registration.client.email_type') }}</label>
                                                                                                <select id="arr_otheremail_type_{{$emailIndx}}" onchange="changeotheroldtextbox(this)" name="otheremail[{{$emailIndx}}][email_type]"
                                                                                                        class="form-control arr_otheremail_type multiple_type_required_s3" required>
                                                                                                        
                                                                                                    @foreach($contacttypes as $id => $contact_name)
                                                                                                    <option {{ (($seloth==0) || ($contact_name['type_name'] == $email->email_type)) ? 'selected' : '' }} value="{{ $contact_name['type_name'] }}">
                                                                                                    {{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}
                                                                                                    </option>
                                                                                                        @endforeach
                                                                                                </select>
                                                                                             </div>
                                                                                            <div id="arr_otheremail_type_{{$emailIndx}}_div" class="form-group col-md-4 arr_otheremail_other_text_div {{ ($seloth==0 || ($email->email_type == 'Other' || $email->email_type == 'Contact')) ? 'd-block' : 'd-none' }} ">
                                                                                                <label>{{ trans('form.registration.client.type') }}</label>
                                                                                                    
                                                                                                <input type="text" value="{{ ($seloth==0) ? $email->email_type  : '' }}"
                                                                                                id="arr_otheremail_type_{{$emailIndx}}_otext" class="form-control numeric arr_otheremail_other_text_type"
                                                                                                    name="otheremail[{{$emailIndx}}][other_text]"
                                                                                                    placeholder="{{ trans('form.registration.client.type_helper') }}"
                                                                                                    >
                                                                                            </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                @php $emailIndx++; @endphp
                                                            @endforeach
														</tbody>
														<tfoot>
														<tr>
                                                            <td id="emails_tbl_footer" class="text-center" style="{{ !$client->userEmails->isEmpty() ? 'display:none' : '' }}"> {{ trans('form.registration.deliveryboy.no_record_added') }}
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
                                                            @php $phoneIndx = 1; @endphp
                                                               @foreach($phones as $phone)
                                                                <tr class="phone-base-row">
                                                                    <td style="width: 100%;display: inline-table;">
                                                                        <div class="card mb-1 shadow-none" id="phone_clone_element-{{$phoneIndx}}">
                                                                            <div class="card-header p-3"
                                                                                 id="heading_phone"
                                                                                 style="padding-bottom: 0 !important;">
                                                                                <div class="row"
                                                                                     style="padding-right: 0; padding-left: 0">
                                                                                    <div class="col-sm-12 col-md-6 text-left">
                                                                                        <h6 class="m-0 font-size-14">
                                                                                            <a href="#phone_collapse-{{$phoneIndx}}"
                                                                                               class="text-dark phone_header"
                                                                                               data-toggle="collapse"
                                                                                               aria-expanded="true"
                                                                                               aria-controls="phone_collapse">
                                                                                               {{ trans('form.registration.client.phone') }} <span class="phone_row_no">{{$phoneIndx}}</span>
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

                                                                            <div id="phone_collapse-{{$phoneIndx}}"
                                                                                 class="phone_block collapse show"
                                                                                 aria-labelledby="heading_phone"
                                                                                 data-parent="#phone-accordion"
                                                                                 style="">
                                                                                <div class="card-body">
                                                                                 
                                                                                        <div class="form-row">
                                                                                            
                                                                                            <div class="form-group col-md-4">
                                                                                                <label>{{ trans('form.registration.client.phone') }} <span class="text-danger">*</span></label>
                                                                                                <input 
                                                                                                type="tel" placeholder="{{trans('general.phone_placeholder')}}"
                                                                                                class="form-control multiple_input_required_s3 arr_other_phone" 
                                                                                                name="otherphone[{{$phoneIndx}}][phone]"
                                                                                                 value="{{$phone->value}}"
                                                                                                    >
                                                                                            </div>
                                                                                            <div class="form-group col-md-4">
                                                                                           
                                                                                                @php $seloth=0; @endphp
                                                                                                @foreach($contacttypes as $id => $contact_name)
                                                                                                    @if($contact_name['type_name']==$phone->phone_type)
                                                                                                    @php $seloth=1; @endphp
                                                                                                    @endif
                                                                                                @endforeach
                                                                                                    
                                                                                                <label>{{ trans('form.registration.client.phone_type') }}</label>
                                                                                                <select id="arr_otherphone_type_{{$phoneIndx}}" onchange="changeotheroldtextbox(this)" name="otherphone[{{$phoneIndx}}][phone_type]"
                                                                                                        class="form-control arr_otherphone_type multiple_type_required_s3" required>
                                                                                                        
                                                                                                    @foreach($contacttypes as $id => $contact_name)
                                                                                                    <option {{ (($seloth==0) || ($contact_name['type_name'] == $phone->phone_type)) ? 'selected' : '' }} value="{{ $contact_name['type_name'] }}">
                                                                                                    {{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}
                                                                                                    </option>
                                                                                                        @endforeach
                                                                                                </select>
                                                                                             </div>
                                                                                            <div id="arr_otherphone_type_{{$phoneIndx}}_div" class="form-group col-md-4 arr_otherphone_other_text_div {{ ($seloth==0 || ($phone->phone_type == 'Other' || $phone->phone_type == 'Contact'))? 'd-block' : 'd-none' }} ">
                                                                                                <label>{{ trans('form.registration.client.type') }}</label>
                                                                                                    
                                                                                                <input type="text" value="{{ ($seloth==0) ? $phone->phone_type  : '' }}"
                                                                                                id="arr_otherphone_type_{{$phoneIndx}}_otext" class="form-control numeric arr_otherphone_other_text_type"
                                                                                                    name="otherphone[{{$phoneIndx}}][other_text]"
                                                                                                    placeholder="{{ trans('form.registration.client.type_helper') }}"
                                                                                                    >
                                                                                            </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                @php $phoneIndx++; @endphp
                                                            @endforeach
														</tbody>
														<tfoot>
														<tr>
                                                            <td id="phone_footer" class="text-center" style="{{ count($phones)>0 ? 'display:none' : '' }}"> {{ trans('form.registration.deliveryboy.no_record_added') }}
                                                
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
                                                            @php $mobileIndx = 1; @endphp
                                                            @foreach($mobiles as $mobile)
                                                             <tr class="mobile-base-row">
                                                                 <td style="width: 100%;display: inline-table;">
                                                                     <div class="card mb-1 shadow-none" id="mobile_clone_element-{{$mobileIndx}}">
                                                                         <div class="card-header p-3"
                                                                              id="heading_mobile"
                                                                              style="padding-bottom: 0 !important;">
                                                                             <div class="row"
                                                                                  style="padding-right: 0; padding-left: 0">
                                                                                 <div class="col-sm-12 col-md-6 text-left">
                                                                                     <h6 class="m-0 font-size-14">
                                                                                         <a href="#mobile_collapse-{{$mobileIndx}}"
                                                                                            class="text-dark mobile_header"
                                                                                            data-toggle="collapse"
                                                                                            aria-expanded="true"
                                                                                            aria-controls="mobile_collapse">
                                                                                            {{ trans('form.registration.client.mobile') }} <span class="mobile_row_no">{{$mobileIndx}}</span>
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

                                                                         <div id="mobile_collapse-{{$mobileIndx}}"
                                                                              class="mobile_block collapse show"
                                                                              aria-labelledby="heading_mobile"
                                                                              data-parent="#mobile-accordion"
                                                                              style="">
                                                                             <div class="card-body">
                                                                              
                                                                                     <div class="form-row">
                                                                                         
                                                                                         <div class="form-group col-md-4">
                                                                                             <label>{{ trans('form.registration.client.mobile') }} <span class="text-danger">*</span></label>
                                                                                             <input 
                                                                                             type="tel" placeholder="{{trans('general.phone_placeholder')}}" 
                                                                                             class="form-control multiple_input_required_s3 arr_other_mobile" 
                                                                                             name="othermobile[{{$mobileIndx}}][mobile]"
                                                                                              value="{{$mobile->value}}"
                                                                                                 >
                                                                                         </div>
                                                                                         <div class="form-group col-md-4">
                                                                                        
                                                                                             @php $seloth=0; @endphp
                                                                                             @foreach($contacttypes as $id => $contact_name)
                                                                                                 @if($contact_name['type_name']==$mobile->phone_type)
                                                                                                 @php $seloth=1; @endphp
                                                                                                 @endif
                                                                                             @endforeach
                                                                                                 
                                                                                             <label>{{ trans('form.registration.client.mobile_type') }}</label>
                                                                                             <select id="arr_othermobile_type_{{$mobileIndx}}" onchange="changeotheroldtextbox(this)" name="othermobile[{{$mobileIndx}}][mobile_type]"
                                                                                                     class="form-control arr_othermobile_type multiple_type_required_s3" required>
                                                                                                     
                                                                                                 @foreach($contacttypes as $id => $contact_name)
                                                                                                 <option {{ (($seloth==0) || ($contact_name['type_name'] == $mobile->phone_type)) ? 'selected' : '' }} value="{{ $contact_name['type_name'] }}">
                                                                                                 {{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}
                                                                                                 </option>
                                                                                                     @endforeach
                                                                                             </select>
                                                                                          </div>
                                                                                         <div id="arr_othermobile_type_{{$mobileIndx}}_div" class="form-group col-md-4 arr_othermobile_other_text_div {{ ($seloth==0 || ($mobile->phone_type == 'Other' || $mobile->phone_type == 'Contact'))? 'd-block' : 'd-none' }} ">
                                                                                             <label>{{ trans('form.registration.client.type') }}</label>
                                                                                                 
                                                                                             <input type="text" value="{{ ($seloth==0) ? $mobile->phone_type  : '' }}"
                                                                                             id="arr_othermobile_type_{{$mobileIndx}}_otext" class="form-control numeric arr_othermobile_other_text_type"
                                                                                                 name="othermobile[{{$mobileIndx}}][other_text]"
                                                                                                 placeholder="{{ trans('form.registration.client.type_helper') }}"
                                                                                                 >
                                                                                         </div>
                                                                                 </div>
                                                                             </div>
                                                                         </div>
                                                                     </div>
                                                                 </td>
                                                             </tr>
                                                             @php $mobileIndx++; @endphp
                                                         @endforeach
                                                     </tbody>
                                                     <tfoot>
                                                     <tr>
                                                         <td id="mobile_footer" class="text-center" style="{{ count($mobiles)>0 ? 'display:none' : '' }}"> {{ trans('form.registration.deliveryboy.no_record_added') }}
                                             
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
                                                            @php $faxIndx = 1; @endphp
                                                            @foreach($faxes as $fax)
                                                             <tr class="fax-base-row">
                                                                 <td style="width: 100%;display: inline-table;">
                                                                     <div class="card mb-1 shadow-none" id="fax_clone_element-{{$faxIndx}}">
                                                                         <div class="card-header p-3"
                                                                              id="heading_fax"
                                                                              style="padding-bottom: 0 !important;">
                                                                             <div class="row"
                                                                                  style="padding-right: 0; padding-left: 0">
                                                                                 <div class="col-sm-12 col-md-6 text-left">
                                                                                     <h6 class="m-0 font-size-14">
                                                                                         <a href="#fax_collapse-{{$faxIndx}}"
                                                                                            class="text-dark fax_header"
                                                                                            data-toggle="collapse"
                                                                                            aria-expanded="true"
                                                                                            aria-controls="fax_collapse">
                                                                                            {{ trans('form.registration.client.fax') }} <span class="fax_row_no">{{$faxIndx}}</span>
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

                                                                         <div id="fax_collapse-{{$faxIndx}}"
                                                                              class="fax_block collapse show"
                                                                              aria-labelledby="heading_fax"
                                                                              data-parent="#fax-accordion"
                                                                              style="">
                                                                             <div class="card-body">
                                                                              
                                                                                     <div class="form-row">
                                                                                         
                                                                                         <div class="form-group col-md-4">
                                                                                             <label>{{ trans('form.registration.client.fax') }} <span class="text-danger">*</span></label>
                                                                                             <input 
                                                                                             type="tel" placeholder="{{trans('general.fax_placeholder')}}" 
                                                                                             class="form-control arr_other_fax" 
                                                                                             name="otherfax[{{$faxIndx}}][fax]"
                                                                                              value="{{$fax->value}}"
                                                                                                 >
                                                                                         </div>
                                                                                         <div class="form-group col-md-4">
                                                                                        
                                                                                             @php $seloth=0; @endphp
                                                                                             @foreach($contacttypes as $id => $contact_name)
                                                                                                 @if($contact_name['type_name']==$fax->phone_type)
                                                                                                 @php $seloth=1; @endphp
                                                                                                 @endif
                                                                                             @endforeach
                                                                                                 
                                                                                             <label>{{ trans('form.registration.client.fax_type') }}</label>
                                                                                             <select id="arr_otherfax_type_{{$faxIndx}}" onchange="changeotheroldtextbox(this)" name="otherfax[{{$faxIndx}}][fax_type]"
                                                                                                     class="form-control arr_otherfax_type">
                                                                                                     
                                                                                                 @foreach($contacttypes as $id => $contact_name)
                                                                                                 <option {{ (($seloth==0) || ($contact_name['type_name'] == $fax->phone_type)) ? 'selected' : '' }} value="{{ $contact_name['type_name'] }}">
                                                                                                 {{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}
                                                                                                 </option>
                                                                                                     @endforeach
                                                                                             </select>
                                                                                          </div>
                                                                                         <div id="arr_otherfax_type_{{$faxIndx}}_div" class="form-group col-md-4 arr_otherfax_other_text_div {{ ($seloth==0 || ($fax->phone_type == 'Other' || $fax->phone_type == 'Contact'))? 'd-block' : 'd-none' }} ">
                                                                                             <label>{{ trans('form.registration.client.type') }}</label>
                                                                                                 
                                                                                             <input type="text" value="{{ ($seloth==0) ? $fax->phone_type  : '' }}"
                                                                                             id="arr_otherfax_type_{{$faxIndx}}_otext" class="form-control numeric arr_otherfax_other_text_type"
                                                                                                 name="otherfax[{{$faxIndx}}][other_text]"
                                                                                                 placeholder="{{ trans('form.registration.client.type_helper') }}"
                                                                                                 >
                                                                                         </div>
                                                                                 </div>
                                                                             </div>
                                                                         </div>
                                                                     </div>
                                                                 </td>
                                                             </tr>
                                                             @php $faxIndx++; @endphp
                                                         @endforeach
                                                     </tbody>
                                                     <tfoot>
                                                     <tr>
                                                         <td id="fax_footer" class="text-center" style="{{ count($faxes)>0 ? 'display:none' : '' }}"> {{ trans('form.registration.deliveryboy.no_record_added') }}
                                             
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
                                        --}}

                                                        
                                                        <div class="form-group row mt-4">
                                                            <div class="col-12 text-right">
                                                                <button onclick="loadSteps('step2', 'step3');" class="btn btn-secondary w-md waves-effect waves-light" type="button">{{ trans('general.previous') }}</button>
                                                                <button class="btn btn-primary w-md waves-effect waves-light" type="submit">{{ trans('general.update') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </form>

                        </div>

                    </div>

                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->

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
             class="collapse address_block show"
             aria-labelledby="address_heading"
             data-parent="#address-accordion" style="">
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>{{ trans('form.registration.client.address') }}
                            <span class="text-danger">*</span></label>
                        <input type="text" value=""
                           class="form-control pac-target-input multiple_input_required_s2 arr_address_address1"
                           name="address[]['address1']"
                           placeholder="{{ trans('form.registration.client.address_helper') }}"
                           required
                           autocomplete="off" >
                    </div>

                    <div class="form-group col-md-6">
                        <label>{{ trans('form.registration.client.address2') }}</label>
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
                        <select name="address[]['country_id']"
                                class="form-control multiple_input_required_s2 arr_address_country"
                                required>
                            @foreach($countries as $id => $country_name)
                                <option value="{{$country_name['id']}}">
                                    {{ App::isLocale('hr')?$country_name['hr_name']:$country_name['en_name'] }}
                                </option>
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
								<option value="{{ $contact_name['type_name'] }}" {{ old('address_type') == $id ? 'selected' : '' }}>
                                {{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}
                                </option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-md-4 arr_add_other_text_div d-none">
						<label class="address_other_no">{{ trans('form.registration.client.type') }}</label>
							
						<input type="text" onchange="changeothersubjecttitle(this);" value=""
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

    {{-- start contact div --}}
    <div class="card mb-1 shadow-none" id="contact_clone_element" style="display: none">
        <div class="card-header p-3" id="heading_contact"
             style="padding-bottom: 0 !important;">
            <div class="row"
                 style="padding-right: 0; padding-left: 0">
                <div class="col-sm-12 col-md-6 text-left">
                    <h6 class="m-0 font-size-14">
                        <a href="#contact_collapse"
                           class="text-dark collapsed contact_header"
                           data-toggle="collapse"
                           aria-expanded="false"
                           aria-controls="contact_collapse">
                           {{ trans('form.contacts') }} : <span class="contact_row_no"></span>
                        </a>
                    </h6>
                </div>
                <div class="col-sm-12 col-md-6 text-sm-right">
                    <button type="button"
                            onclick="deleteContact(this)"
                            class="btn btn-link waves-effect"
                            style="text-decoration: underline">
                            {{ trans('general.delete') }}
                    </button>
                </div>
            </div>
        </div>

        <div id="contact_collapse"
             class="collapse contact_block show"
             aria-labelledby="heading_contact"
             data-parent="#contact-accordion" style="">
            <div class="card-body">
                <div class="form-row">

                    <div class="form-group col-md-4">
                        <label>{{ trans('form.registration.client.phone') }} <span class="text-danger">*</span></label>
                        <input type="text" value=""
                               class="form-control arr_contacts_phone multiple_input_required_s3"
                               name="contacts[]['phone']"
                               placeholder="{{trans('general.phone_placeholder')}}"
                               data-im-insert="true" required>
                    </div>
                    <div class="form-group col-md-4">
						<label>{{ trans('form.registration.client.mobile') }}</label>
						<input type="text" value=""
							class="form-control arr_contacts_mobile" id="mobile"
							name="contacts[]['mobile']"
							placeholder="{{trans('general.phone_placeholder')}}"
							data-im-insert="true">
					</div>
                    <div class="form-group col-md-4">
                        <label>{{ trans('form.registration.client.fax') }}</label>
                        <input type="text" value=""
                               class="form-control arr_contacts_fax" id="fax"
                               name="contacts[]['fax']"
                               placeholder="{{trans('general.fax_placeholder')}}"
                               data-im-insert="true">
                    </div>
                    <div class="form-group col-md-6">
						<label>{{ trans('form.registration.client.Primary Email') }}
						</label>
						<input type="text" value=""
							   class="form-control arr_contacts_primary_email"
							   name="contacts[]['primary_email']"
							   placeholder="{{trans('form.registration.client.Primary Email')}}"
							   data-im-insert="true">
					</div>
					<div class="form-group col-md-6">
						<label>{{ trans('form.registration.client.Secondary Email') }}
						</label>
						<input type="email" value=""
							   class="form-control arr_contacts_secondary_email"
							   name="contacts[]['secondary_email']"
							   placeholder="{{ trans('form.registration.client.Secondary Email') }}"
							   data-im-insert="true">
					</div>
                </div>
                <div class="form-row">
					
					<div class="form-group col-md-4">
						<label>{{ trans('form.registration.client.contact_type') }}</label>
						<select onchange="changeothertextbox(this);changesubjecttitlebyid(this.id);" name="contacts[]['contact_type_id']"
								class="form-control arr_contact_type multiple_input_required_s3" required>
							@foreach($contacttypes as $id => $contact_name)
								<option value="{{ $contact_name['type_name'] }}" {{ old('contact_type_id') == $id ? 'selected' : '' }}>
                                {{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}
                                </option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-md-4 arr_contact_other_text_div d-none">
						<label class="address_other_no">{{ trans('form.registration.client.type') }}</label>
							
						<input type="text" onchange="changeothersubjecttitle(this);" value=""
							class="form-control numeric arr_contact_other_text_type"
							name="contacts[]['other_text']"
							placeholder="{{ trans('form.registration.client.type_helper') }}"
							>
                    </div>
                    <div class="form-check col-md-3 form-check-inline mt-2 ">   
                        <input onchange="changradiobox(this)" type="checkbox" class="arr_contact_default form-check-input " name="contacts[][is_default]" id="contacts_is_default"
                           >
                          <label for="contacts_is_default" class="form-check-label">{{trans('form.contact.field.is_default')}}</label>
                      </div>
				</div>
            </div>
        </div>
    </div>
    {{-- end contact div --}}

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
				class="collapse email_block show"
				aria-labelledby="heading_email"
				data-parent="#email-accordion" style="">
				<div class="card-body">
					
					<div class="form-row">
						<div class="form-group col-md-4">
							<label>{{ trans('form.registration.client.email') }} <span class="text-danger">*</span></label>
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
									<option value="{{ $contact_name['type_name'] }}" {{ old('contact_type_id') == $id ? 'selected' : '' }}>
                                    {{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}
                                    </option>
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
				class="collapse phone_block show"
				aria-labelledby="heading_phone"
				data-parent="#phone-accordion" style="">
				<div class="card-body">
					
					<div class="form-row">
						<div class="form-group col-md-4">
							<label>{{ trans('form.registration.client.phone') }} <span class="text-danger">*</span></label>
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
									<option value="{{ $contact_name['type_name'] }}" {{ old('contact_type_id') == $id ? 'selected' : '' }}>
                                    {{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}
                                    </option>
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
				class="collapse mobile_block show"
				aria-labelledby="heading_mobile"
				data-parent="#mobile-accordion" style="">
				<div class="card-body">
					
					<div class="form-row">
						<div class="form-group col-md-4">
							<label>{{ trans('form.registration.client.mobile') }} <span class="text-danger">*</span></label>
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
									<option value="{{ $contact_name['type_name'] }}" {{ old('contact_type_id') == $id ? 'selected' : '' }}>
                                    {{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}
                                    </option>
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
				class="collapse fax_block show"
				aria-labelledby="heading_fax"
				data-parent="#fax-accordion" style="">
				<div class="card-body">
					
					<div class="form-row">
						<div class="form-group col-md-4">
							<label>{{ trans('form.registration.client.fax') }} </label>
							<input type="tel" placeholder="{{trans('general.fax_placeholder')}}" value=""
								class="form-control arr_other_fax" 
								name="otherfax[]['fax']"
								>
						</div>
						<div class="form-group col-md-4">
							<label>{{ trans('form.registration.client.fax_type') }}</label>
							<select onchange="changeothertextbox(this)" name="otherfax[]['fax_type']"
									class="form-control arr_otherfax_type">
								@foreach($contacttypes as $id => $contact_name)
									<option value="{{ $contact_name['type_name'] }}" {{ old('contact_type_id') == $id ? 'selected' : '' }}>
                                    {{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}
                                    </option>
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
	{{-- end fax div --}}

@endsection

@section('footerScript')
    <!-- footerScript -->
{{--    <script src='https://www.google.com/recaptcha/api.js' async defer></script>--}}
    <script src="{{ URL::asset('/libs/select2/select2.min.js')}}"></script>
    <script src="{{ URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{ URL::asset('/libs/jquery-smartwizard/jquery.smartWizard.js')}}"></script>
    <script src="{{ URL::asset('/libs/jquery-validate/1.19.0/jquery.validate.js')}}"></script>  
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_KEY')}}&libraries=places&language={{ App::isLocale('hr') ? 'iw' : 'en' }}" async defer></script>
    <script src="{{ URL::asset('/libs/inputmask/5.0.5/jquery.inputmask.js') }}" async></script>
    @if (App::isLocale('hr'))
    <script src="{{ URL::asset('/libs/jquery-validate/1.19.0/localization/messages_he.js')}}"></script>
    @endif
    <script src="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.js') }}" async></script>
    <script>
        $(document).ready(function() {
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });

            $('#smartwizard').smartWizard({
                selected: 0,  // Initial selected step, 0 = first step
                theme: 'arrows', // default, arrows, dots, progress
                enableURLhash: false, // Enable selection of the step based on url hash
                // autoAdjustHeight: true, // Automatically adjust content height
                transition: {
                    animation: 'fade', // Effect on navigation, none/fade/slide-horizontal/slide-vertical/slide-swing
                },
                toolbarSettings: {
                    toolbarPosition: 'none', // none, top, bottom, both
                    showNextButton: false, // show/hide a Next button
                    showPreviousButton: false, // show/hide a Previous button
                },
                anchorSettings: {
                    anchorClickable: false, // Enable/Disable anchor navigation
                    markDoneStep: true, // Add done state on navigation
                    markAllPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
                    removeDoneStepOnNavigateBack: true, // While navigate back done step after active step will be cleared
                    enableAnchorOnDoneStep: false // Enable/Disable the done steps navigation
                },
                keyboardSettings: {
                    keyNavigation: false, // Enable/Disable keyboard navigation(left and right keys are used if enabled)
                },
            });

            $("#client-form").validate({
                ignore: false,
                invalidHandler: function (e, validator) {
                    // loop through the errors:
                    for (var i = 0; i < validator.errorList.length; i++) {
                        $(validator.errorList[i].element).closest('.collapse').collapse('show');
                        $(this).find(":input.error:first").focus();
                        console.log('error on ',validator.errorList[i].element);
                    }
                }
            });

            $('#dob').datepicker({
                autoclose: true,
                todayHighlight: true
            });

            loadSteps('step1', 'step1');
        });

        //this for add input mask to phone,mobile,fax
        function addinputmask(type){
            if(type=='contact'){
             
             $('.arr_contacts_fax').inputmask('999-999-9999', {
                autoUnmask: true,
                removeMaskOnSubmit:true
            });
             $('.arr_contacts_phone').inputmask('999-999-9999', {
                autoUnmask: true,
                removeMaskOnSubmit:true
            });
            $('.arr_contacts_mobile').inputmask('999-999-9999', {
                autoUnmask: true,
                removeMaskOnSubmit:true
            });
            }
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
		function changeotheroldtextbox(t){
			var id = t.id;
			if(t.value=='Other'|| t.value=='Contact'){
				$("#"+id+"_div").addClass('d-block');
                $("#"+id+"_div").removeClass('d-none');
                 //$("#"+id+"_otext").prop('required',true);
                 var contactType = <?php echo $contacttypes; ?>;
                
                contactType.map((contact, idx) => {
                    if(contact.type_name == t.value){
                        console.log(id,'old')
                        @if(config('app.locale') == 'hr')
                            $("#"+id+"_olabel").html(contact.hr_type_name+' {{trans("form.registration.client.type")}}');
                            $("#"+id+"_otext").attr("placeholder", '{{trans("general.enter")}} '+contact.hr_type_name+' {{trans("form.registration.client.type")}}');
                        @else
                        $("#"+id+"_olabel").html(contact.type_name+' {{trans("form.registration.client.type")}}');
                        $("#"+id+"_otext").attr("placeholder", '{{trans("general.enter")}} '+contact.type_name+' {{trans("form.registration.client.type")}}');
                        @endif
                    }
                })
                $("#"+id+"_otext").rules('add', {required: true });
                $("#"+id+"_otext").attr("required", true);
			}else{
				$("#"+id+"_div").addClass('d-none');
                $("#"+id+"_div").removeClass('d-block');
               //$("#"+id+"_otext").prop('required',false);
                $("#"+id+"_otext").rules('remove', 'required');
                $("#"+id+"_otext").removeAttr('required');
			}
    	}
        function changradiobox(t){
			var id = t.id;
			$("input[type=checkbox]").prop('checked', false);
			$("#"+id).prop('checked', true);	
    	}

        // This Function for Address Type Dropdown Othertext to open textbox
        function changeothertextbox(t){
            var id = t.id;
            console.log(t.value,'t.value')
            if(t.value=='Other' || t.value=='Contact'){
                $("#"+id+"_div").removeClass('d-none');
                $("#"+id+"_otext").val('');
                
                var contactType = <?php echo $contacttypes; ?>;

                contactType.map((contact, idx) => {
                    if(contact.type_name == t.value){
                        @if(config('app.locale') == 'hr')
                            $("#"+id+"_olabel").html(contact.hr_type_name+' {{trans("form.registration.client.type")}}');
                            $("#"+id+"_otext").attr("placeholder", '{{trans("general.enter")}} '+contact.hr_type_name+' {{trans("form.registration.client.type")}}');
                        @else
                            $("#"+id+"_olabel").html(contact.type_name+' {{trans("form.registration.client.type")}}');
                            $("#"+id+"_otext").attr("placeholder", '{{trans("general.enter")}} '+contact.type_name+' {{trans("form.registration.client.type")}}');
                        @endif
                    }
                })

                //$("#"+id+"_otext").prop('required',true);
                $("#"+id+"_otext").rules('add', {required: true });
                $("#"+id+"_otext").attr("required", true);
            }else{
                $("#"+id+"_div").addClass('d-none');
                //$("#"+id+"_otext").prop('required',false);
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

        //for custom validation before form submit to check all multiple fields has entries
        function dosubmit() {
            return customFormValidation();
        }

        // function step 1 and step 2 show hide
        function loadSteps(step, backstep) {
            // console.log('step ', step);
            // console.log('backstep ', backstep);

            if (step == 'step1') {
                // $('#next_step1').show();
                // $('#next_step2').hide();
                // $('#next_step3').hide();

                if(backstep == 'step2') {
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

                // $('#smartwizard').smartWizard("goToStep", 0);
                $('#smartwizard').smartWizard("reset");

            } else if (step == 'step2') {
                let printName = $('#name').val();
                $('#printname').val(printName);
                if((backstep == 'step1' && $("#client-form").valid() === true) || backstep == 'step3'){
                    // $('#next_step2').show();
                    // $('#next_step1').hide();
                    // $('#next_step3').hide();

                    if(backstep == 'step3'){
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

                    $('#smartwizard').smartWizard("goToStep", 1);
                }

            } else if (step == 'step3') {

                if ($("#client-form").valid() === true) {
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

                    // $('#next_step3').show();
                    // $('#next_step2').hide();

                    $('#smartwizard').smartWizard("goToStep", 2);
                }
            }
        }

        // Functions for multiple addresses/contacts fields
        function customFormValidation() {
            var isValid = false;
            var cnt = 0;
            var basicValidation = $("#client-form").valid();

            if (basicValidation) {
                cnt++;
            }
            $("form tbody").each(function (index) {
                if ($(this).find("tr").length > 0) {
                    cnt++;
                    if (cnt > 1) {
                        isValid = true;
                        return true
                    }
                } else {
                    //if ($(this).attr('id') == 'fax-accordion' || $(this).attr('id') == 'email-accordion' || $(this).attr('id') == 'phone-accordion' || $(this).attr('id') == 'mobile-accordion' || $(this).attr('id') == 'contact-accordion') {
					
					if ($(this).attr('id') == 'contact-accordion') {
						cnt++;
						if (cnt > 1) {
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

            let LegalId = $('#legal_entity_no').val();
			if(LegalId!=''){
				let val = LegalId;
				let code = calcCode(LegalId);
				let lastDigit = val.substr(8,9);
				let errorEle = $('#id-error');
				if(lastDigit != code){
					$('#id-error').removeClass('d-none');
					$('#id-error').css('display', 'block');
					$('#id-error').html("{{trans('form.registration.investigation.id_error')}}");
					isValid = false;
					Swal.fire({
						title: "",
						text: "{{trans('form.registration.investigation.id_error')}}", 
						type: "error",
						confirmButtonText: "{{ trans('general.ok') }}",
					})
					.then((result) => {
						if(result.value){
							loadSteps('step2', 'step3');
						}
					});
				} else {
					$('#id-error').addClass('d-none');
					$('#id-error').css('display', 'none');
					$('#id-error').html('');
					isValid = true;
				}
			}

            return isValid;
        }

        function addNewAddress() {
            $("#address_footer").hide();
            var lastNo, originalId;
            var arrRowNo = [];
            var baseTbl = $("#address-accordion");
            originalId = baseTbl.find('.address_block:last').attr('id');

            var cloned = $("#address_clone_element").clone().appendTo('#address-accordion').wrap('<tr class="address-base-row" style="display: block;"><td style="width: 100%;display: inline-table;"></td></tr>');

            if (originalId)
                arrRowNo = originalId.split("-");

            if (arrRowNo.length > 1) {
                lastNo = arrRowNo[1];
            } else {
                lastNo = 0;
            }

            var newId = 'address_collapse-' + (parseInt(lastNo) + 1);

            cloned.show();
            cloned.attr('id', 'address_clone_element-' + (parseInt(lastNo) + 1));

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

            cloned.find(".address_other_no").attr('id', "arr_address_type_"  +(parseInt(lastNo) + 1) + "_olabel");

            cloned.find(".arr_add_other_text_div").addClass('d-none');
            cloned.find('input[type=text]').val('');
            setAddressnew((parseInt(lastNo) + 1));
            changesubjecttitlebyid("arr_address_type_" + (parseInt(lastNo) + 1));
            changeothersubjecttitle("arr_other_type_" + (parseInt(lastNo) + 1));
            cloned.find('.collapse').collapse('show');
        }

        function deleteAddress(ele) {
            $(ele).closest(".address-base-row").remove();

            $(".address-base-row").each(function (index) {
                //$(this).find(".address_row_no").html(index + 1);
            });

            if ($(".address-base-row").length < 1) {
                $("#address_footer").html('<span>No record added</span>');
                $("#address_footer").show();
            }
        }

        function addNewContact() {
            $("#contacts_footer").hide();
            var lastNo, originalId;
            var arrRowNo = [];
            var baseTbl = $("#contact-accordion");
            originalId = baseTbl.find('.contact_block:last').attr('id');

            var cloned = $("#contact_clone_element").clone().appendTo('#contact-accordion').wrap('<tr class="contact-base-row" style="display: block;"><td style="width: 100%;display: inline-table;"></td></tr>');

            if (originalId)
                arrRowNo = originalId.split("-");

            if (arrRowNo.length > 1) {
                lastNo = arrRowNo[1];
            } else {
                lastNo = 0;
            }

            var newId = 'contact_collapse-' + (parseInt(lastNo) + 1);

            cloned.css('display', 'block');
            cloned.attr('id', 'contact_clone_element-' + (parseInt(lastNo) + 1));

            cloned.find(".contact_block").attr('id', newId);
            cloned.find(".contact_header").attr('href', '#' + newId);
            cloned.find(".contact_row_no").html($(".contact-base-row").length);

            cloned.find(".arr_contacts_fax").attr('name', "contacts[" + (parseInt(lastNo) + 1) + "][fax]");
            cloned.find(".arr_contacts_phone").attr('name', "contacts[" + (parseInt(lastNo) + 1) + "][phone]");
            cloned.find(".arr_contacts_mobile").attr('name', "contacts[" + (parseInt(lastNo) + 1) + "][mobile]");
            cloned.find(".arr_contact_type").attr('name', "contacts[" + (parseInt(lastNo) + 1) + "][contact_type_id]");

            cloned.find(".arr_contacts_primary_email").attr('name', "contacts[" + (parseInt(lastNo) + 1) + "][primary_email]");
			cloned.find(".arr_contacts_secondary_email").attr('name', "contacts[" + (parseInt(lastNo) + 1) + "][secondary_email]");

            cloned.find(".arr_contact_other_text_type").attr('name', "contacts[" + (parseInt(lastNo) + 1) + "][other_text]");
            cloned.find(".arr_contact_other_text_type").attr('id', "arr_contact_type_" + (parseInt(lastNo) + 1) +"_otext");
            cloned.find(".arr_contact_other_text_type").attr('data-id',  "arr_contact_type_" + (parseInt(lastNo) + 1));
		
            cloned.find(".arr_contact_type").attr('id', "arr_contact_type_" + (parseInt(lastNo) + 1));
			cloned.find(".arr_contact_other_text_div").attr('id', "arr_contact_type_" + (parseInt(lastNo) + 1)+"_div");

            cloned.find(".arr_contact_default").attr('name', "contacts[" + (parseInt(lastNo) + 1) + "][is_default]");
			cloned.find(".arr_contact_default").attr('id', "contacts_default_" + (parseInt(lastNo) + 1));

            cloned.find(".contact_row_no").attr('id', "arr_contact_type_"  +(parseInt(lastNo) + 1) + "_title");

            cloned.find(".address_other_no").attr('id', "arr_contact_type_"  +(parseInt(lastNo) + 1) + "_olabel");

            cloned.find(".arr_contact_other_text_div").addClass('d-none');
            cloned.find('input[type=text]').val('');
            addinputmask('contact');
            changesubjecttitlebyid("arr_contact_type_" + (parseInt(lastNo) + 1));
            cloned.find('.collapse').collapse('show');

        }

        function deleteContact(ele) {
            $(ele).closest(".contact-base-row").remove();

            $(".contact-base-row").each(function (index) {
               // $(this).find(".contact_row_no").html(index + 1);
            });

            if ($(".contact-base-row").length < 1) {
                $("#contacts_footer").html('<span>No record added</span>');
                $("#contacts_footer").show();
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
            cloned.attr('id', 'email_clone_element-' + (parseInt(lastNo) + 1));

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
				$("#email_footer").html('<span>No record added</span>');
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
            cloned.attr('id', 'phone_clone_element-' + (parseInt(lastNo) + 1));

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
				$("#phone_footer").html('<span>No record added</span>');
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
            cloned.attr('id', 'mobile_clone_element-' + (parseInt(lastNo) + 1));

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
				$("#mobile_footer").html('<span>No record added</span>');
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
            cloned.attr('id', 'fax_clone_element-' + (parseInt(lastNo) + 1));

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
				$("#fax_footer").html('<span>No record added</span>');
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
          function settheSubjectId(t){
			var id = t.id;
			//alert(id);
			var value = t.value;
			var x = document.getElementById(id);
			x.addEventListener('keypress', calccode, false);
		}
		function calcCode(val){
			var sum = 0;
			for (var i = 0; i < 8; i++) {
				var digit = parseInt(val[i]);
				if (isNaN(digit))
					return;
				var result = digit * (i % 2 == 0 ? 1 : 2);
				while (result > 0) {
					sum += result % 10;
					result = parseInt(result / 10);
				}
			}
			return  (10 - (sum % 10)) % 10; 
		}
		function calccode(e) {
			// filter out all key codes except numbers and specials
			var keynum = null;
			var code = e.keyCode || e.charCode;
			if (code > 30 && !e.ctrlKey) {
				keynum = code - 48;
				if (keynum < 0 || keynum > 9) {
					// not allowed
					e.preventDefault();
					return;
				}
			}
			if (keynum === null)
				return; // skip ctrl keys
				
			var val = this.value 
			//+ '' + keynum; // don't forget to add the key that was pressed
			if ((val.length) < 9)
				return;
			if (val.length == 9) {
				e.preventDefault(); // don't let the user type more chars
			}
			// let index = $(this).data('index');
			let allDigit = val;
			let lastDigit = val.substr(8,9);
			val = val.substr(0,8); // verify we look only at the first 8 digits
			var code = calcCode(val);
			if(lastDigit == code){
				$('#id-error').addClass('d-none');
				$(this).attr('data-allow', 'yes');
				$('#id-error').css('display', 'none');
				$('#id-error').html("");
			} else {
				$(this).attr('data-allow', 'no');
				$('#id-error').removeClass('d-none');
				$('#id-error').css('display', 'block');
				$('#id-error').html("{{trans('form.registration.investigation.id_error')}}");
			}
			
			// this.value = val;
			e.preventDefault(); // don't let the browser add the pressed key, because we alread did
		}
      </script>
      <script type="text/javascript">
          window.onload = function() {
            addinputmask('contact');
            addinputmask('phone');
            addinputmask('mobile');
            addinputmask('fax');
             var i;
             for (i = 1; i <= {{count($client->userAddresses)}}; i++) {
                setAddressnew(i)
             }
          };
         </script>
@endsection