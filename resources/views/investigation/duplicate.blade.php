@extends('layouts.master')

@section('title') {{ trans('form.registration.investigation.create_investigation_title') }} @endsection

@section('headerCss')
<!-- headerCss -->
<link href="{{ URL::asset('/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('/libs/jquery-smartwizard/smart_wizard.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('/libs/jquery-smartwizard/smart_wizard_arrows.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('/css/custom.css') }}" rel="stylesheet" type="text/css" />

<style>
    #smartwizard .tab-content{
        height: auto !important;
    }

    .sw-theme-arrows > .nav .nav-link.active{
        color: #ffffff !important;
        border-color: #105C8D !important;
        background: #105C8D !important;
    }

    .sw-theme-arrows > .nav .nav-link.active::after {
        border-left-color: #105C8D !important;
    }

    .sw-theme-arrows > .nav .nav-link.done{
        color: #ffffff !important;
        border-color: #105C8D !important;
        background: #105C8D !important;
    }

    .sw-theme-arrows > .nav .nav-link.done::after {
        border-left-color: #105C8D !important;
    }

    .card-title-desc {
        margin-bottom: 0 !important;
    }

    .select2-container .select2-selection--single{
        height: 33px !important;
    }

    .select2-container .select2-selection--single .select2-selection__rendered{
        line-height: 33px !important;
    }

    .company-del-checks{
        padding-left: 25px !important;
        padding-right: 25px !important;
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
            <li class="breadcrumb-item"><a href="{{route('investigations')}}">{{ trans('form.investigations') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript: void(0);">{{ trans('general.create') }}</a></li>
        </ol>
    </div>
</div>

<!-- content -->

@if (count($errors) > 0)
    <?php
    $oldArr = old();
    $oldArr['user_inquiry'] = isset($oldArr['user_inquiry']) ? $oldArr['user_inquiry'] : NULL;
    $oldArr['type_of_inquiry'] = isset($oldArr['type_of_inquiry']) ? $oldArr['type_of_inquiry'] : NULL;
    $oldArr['paying_customer'] = isset($oldArr['paying_customer']) ? $oldArr['paying_customer'] : NULL;
    $oldArr['error'] = true;
    ?>
@else
    <?php
    $oldArr['user_inquiry'] = $invn->user_id.'-'.$invn->user_inquiry;
    $oldArr['type_of_inquiry'] = $invn->type_of_inquiry;
    $oldArr['paying_customer'] = $invn->paying_customerid;
    $oldArr['error'] = false;
    ?>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
              
                <div class="row">

                    <div class="col-12">
                        <h4 class="card-title">{{ trans('form.registration.investigation.create_investigation_title') }}<span id="client_credit"></span></h4>
                        <p class="card-title-desc">{{ trans('form.registration.investigation.create_investigation_desc') }}</p>
                    </div>

                    <div class="col-12">
                        
                        <form name="investigation-form" id="investigation-form" class="form-horizontal mt-4" method="POST" action="{{ route('investigation.store', [$invn->id]) }}">

                            @csrf

                            <input type="hidden" name="user_id" value="{{ Auth::id() }}" id="user_id">

                            <div id="smartwizard">

                                <ul class="nav">
                                    <li class="nav-item">
                                        <a class="nav-link" href="#step-1">
                                            <strong>{{ trans('form.step_1') }}</strong> <br>{{ trans('form.registration.investigation.basic_details') }}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#step-2">
                                            <strong>{{ trans('form.step_2') }}</strong> <br>{{ trans('form.registration.investigation.subject_details') }}
                                        </a>
                                    </li>
                                   {{--  <li class="nav-item">
                                        <a class="nav-link" href="#step-3">
                                            <strong>{{ trans('form.step_3') }}</strong> <br>{{ trans('form.registration.investigation.contact_details') }}
                                        </a>
                                    </li> --}}
                                </ul>

                                <div class="tab-content">
                                    <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">
                                        <div class="row">
                                            <div class="col-12">
                                                <div id="next_step1">
                                                    <div class="form-row">

                                                        @if(isAdmin() || isSM())
                                                            <div class="form-group col-md-6">
                                                                <label for="userinquiry">{{ trans('form.registration.investigation.user_inquiry') }} <span class="text-danger">*</span></label>
                                                                <select name="user_inquiry" id="userinquiry" class="form-control userinquiry_dd" required>
                                                                    <option disabled selected>{{ trans('form.registration.investigation.select_client_name') }}</option>
                                                                    @foreach($clients as $key => $value)
                                                                        <option value="{{$key.'-'.$value}}" {{ $key.'-'.$value == old('user_inquiry', $invn->user_id.'-'.$invn->user_inquiry) ? 'selected' : ''  }}>{{$value}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @else
                                                            <div class="form-group col-md-6">
                                                                <label for="userinquiry">{{ trans('form.registration.investigation.user_inquiry') }} <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control " name="user_inquiry" value="{{ old('user_inquiry', $invn->user_inquiry) }}"
                                                                    required id="userinquiry" placeholder="{{ trans('form.registration.investigation.user_inquiry') }}">
                                                            </div>
                                                        @endif

                                                        <div class="form-group col-md-6">
                                                            <label for="paying_customer">{{ trans('form.registration.investigation.paying_customer') }} <span
                                                                    class="text-danger">*</span></label>
                                                            <select name="paying_customer" id="paying_customer" class="form-control paying_customer_dd" required>
                                                               
                                                                @if(isClient())
                                                               
                                                                @foreach($customers as $customer)
                                                                    <option value="{{$customer->id}}" {{ $customer->id == old('paying_customer', $invn->paying_customerid) ? 'selected' : '' }}
                                                                    
                                                                    >{{ $customer->name }}</option>
                                                                @endforeach
                                                            @endif
                                                            </select>
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="fileNum">{{ trans('form.registration.investigation.file_claim_number') }}</label>
                                                            <input type="text" name="ex_file_claim_no" value="{{ old('ex_file_claim_no', $invn->ex_file_claim_no) }}"
                                                                class="form-control " autofocus id="fileNum"
                                                                placeholder="{{ trans('form.registration.investigation.file_claim_number') }}">
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="claimNum">{{ trans('form.registration.investigation.claim_number') }}</label>
                                                            <input type="text" name="claim_number" class="form-control"
                                                                value="{{ old('claim_number', $invn->claim_number) }}" id="claimNum" placeholder="{{ trans('form.registration.investigation.claim_number') }}">
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="orderNum">{{ trans('form.registration.investigation.work_order_number') }}</label>
                                                            <input type="text" name="work_order_number" class="form-control"
                                                                   value="{{$won}}" id="orderNum" readonly>
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="paying_customer">{{ trans('form.registration.investigation.req_type_inquiry') }} <span class="text-danger">*</span></label>
                                                            <select name="type_of_inquiry" id="type_of_inquiry" class="form-control " required>
                                                            @if(isClient())
                                                                @foreach($products as $product)
                                                                    <option value="{{$product->product->id}}" {{ $product->product->id == old('type_of_inquiry', $invn->type_of_inquiry) ? 'selected' : '' }}
                                                                    data-isdelivery="{{ $product->product->is_delivery == '1' ? 'yes' : 'no' }}"
                                                                    data-delcost="{{ $product->product->delivery_cost }}"
                                                                    data-price="{{ $product->price }}"
                                                                    data-spousecost="{{ $product->product->spouse_cost }}"
                                                                    >{{ $product->product->name}}</option>
                                                                @endforeach
                                                            @endif
                                                            </select>
                                                            <input type="hidden" value="" name="inv_cost" id="inv_cost">
                                                            <input type="hidden" value="" name="product_isdel" id="product_isdel">
                                                            <input type="hidden" value="" name="product_delcost" id="product_delcost">
                                                            <input type="hidden" value="" name="product_spousecost" id="product_spousecost">
                                                        </div>

                                                        <div class="form-group del-checks" style="display: none">
                                                            <div class="form-group col-md-12">
                                                                <label for="consent">{{ trans('form.registration.investigation.type_chk') }}</label>
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="personal_del" name="personal_del" value="1" {{ $invn->personal_del == '1' ? 'checked' : '' }}>
                                                                    <label class="custom-control-label" for="personal_del">{{ trans('form.registration.investigation.personal_del') }}</label>
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-md-12">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="company_del" name="company_del" value="1" {{ $invn->company_del == '1' ? 'checked' : '' }}>
                                                                    <label class="custom-control-label" for="company_del">{{ trans('form.registration.investigation.company_del') }}</label>
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-md-12 company-del-checks" style="{{ $invn->company_del != '1' ? 'display: none' : '' }}">
                                                                <div class="custom-control custom-checkbox mb-2">
                                                                    <input type="checkbox" class="custom-control-input" id="make_paste" name="make_paste" value="1" {{ $invn->make_paste == '1' ? 'checked' : '' }}>
                                                                    <label class="custom-control-label" for="make_paste">{{ trans('form.registration.investigation.make_paste') }}</label>
                                                                </div>
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="deliver_by_manager" name="deliver_by_manager" value="1" {{ $invn->deliver_by_manager == '1' ? 'checked' : '' }}>
                                                                    <label class="custom-control-label" for="deliver_by_manager">{{ trans('form.registration.investigation.delivery_company_manager') }}</label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="form-group row mt-4">
                                                        <div class="col-12 text-right text-xs-center">
                                                            <button onclick="loadSteps('step2', 'step1');"
                                                                class="btn btn-primary w-md waves-effect waves-light"
                                                                type="button">{{ trans('general.next') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">
                                        <div class="row">
                                            <div class="col-12">
                                                <div id="next_step2">

                                                    <!-- Block for multiple Subjects-->
                                                    <div class="table-wrapper">
                                                        <div class="table-responsive mb-0 fixed-solution" data-pattern="priority-columns">
                                                            <div id="contacts_tbl">
                                                                <table class="table table-borderless mb-0">
                                                                    <thead>
                                                                    <tr>
                                                                        <th class="btn-primary">
                                                                            <h4 class="font-size-18 mb-1">{{ trans('form.registration.investigation.subject_details') }}</h4>
                                                                        </th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody id="contact-accordion">
                                                                    <!-- Block for multiple subjects loop-->

                                                                    @php $subIndx = 1; @endphp
                                                                    @foreach($invn->subjects as $subject)
                                                                        <tr class="contact-base-row" style="display: block;">
                                                                            <td style="width: 100%;display: inline-table;">
                                                                                <div class="card mb-1 shadow-none" id="contact_clone_element-{{$subIndx}}">

                                                                                    <div class="card-header p-3" id="heading_contacts"
                                                                                         style="padding-bottom: 0 !important;">
                                                                                        <div class="row"
                                                                                             style="padding-right: 0; padding-left: 0">
                                                                                            <div class="col-sm-12 col-md-6 text-left">
                                                                                                <h6 class="m-0 font-size-14">
                                                                                                    <a href="#contact_collapse-{{$subIndx}}"
                                                                                                       class="text-dark collapsed contact_header"
                                                                                                       data-toggle="collapse"
                                                                                                       aria-expanded="true"
                                                                                                       aria-controls="contact_collapse">
                                                                                                {{ trans('form.registration.investigation.subject') }} : <span id="arr_contacts_type_{{$subIndx}}_title" class="contact_row_no">{{$subject->sub_type}}</span>
                                                                                                    </a>
                                                                                                    @php if (isAdmin() || isSM()) 
                                                                                                    {$acflag='';}else{$acflag='d-none';}@endphp
                                                                                                           
                                                                                                    <div class="form-check-inline m-0 {{$acflag}}"> 
                                                                                                    <label for="contacts_is_default" class="form-check-label"> | {{ trans('form.registration.investigation.is_address_confirmed') }}</label>
                                                                                                    <input id="address_confirmed_{{$subIndx}}" name="subjects[{{$subIndx}}][address_confirmed]" class="arr_contacts_address_confirmed" type="checkbox" switch="bool" {{ $subject->address_confirmed == 1 ? 'checked' : '' }}>
                                                                                                    <label class="mt-2 ml-1 arr_contacts_address_confirmed_lable" for="address_confirmed_{{$subIndx}}" data-on-label="Yes" data-off-label="No"></label>
                                                                                                    </div>
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

                                                                                    <div id="contact_collapse-{{$subIndx}}"
                                                                                         class="collapse contact_block show"
                                                                                         aria-labelledby="heading_contacts"
                                                                                         data-parent="#contact-accordion" style="">

                                                                                        <div class="card-body">

                                                                                            <div class="form-row">
                                                                                                <div class="form-group col-md-4">
                                                                                                    <label>{{ trans('form.registration.investigation.family') }}<span class="text-danger">*</span></label>

                                                                                                    <input type="text" value="{{ $subject->family_name }}"
                                                                                                           class="form-control arr_contacts_family input_required_s2"
                                                                                                           name="subjects[{{$subIndx}}][family_name]"
                                                                                                           placeholder="{{ trans('form.registration.investigation.family') }}"
                                                                                                           data-im-insert="true" required>
                                                                                                </div>

                                                                                                <div class="form-group col-md-4">
                                                                                                    <label>{{ trans('form.registration.investigation.firstname') }}<span class="text-danger">*</span></label>
                                                                                                    <input type="text" value="{{ $subject->first_name }}"
                                                                                                           class="form-control arr_contacts_firstname input_required_s2"
                                                                                                           name="subjects[{{$subIndx}}][first_name]"
                                                                                                           placeholder="{{ trans('form.registration.investigation.firstname') }}"
                                                                                                           data-im-insert="true" required>
                                                                                                </div>

                                                                                                <div class="form-group col-md-4">
                                                                                                    <label>{{ trans('form.registration.investigation.id') }}</label>
                                                                                                    <input onkeypress="settheSubjectId(this)" id="subjects_id_{{$subIndx}}" type="number" maxwidth="9" value="{{ $subject->id_number }}"
                                                                                                           class="form-control arr_contacts_id"
                                                                                                           name="subjects[{{$subIndx}}][id_number]"
                                                                                                           placeholder="{{ trans('form.registration.investigation.id') }}"
                                                                                                           data-im-insert="true" data-index="{{$subIndx}}" data-allow="">
                                                                                                    <label id="id-error-{{$subIndx}}" class="id_error error d-none" for="id-error">{{trans('form.registration.investigation.id_error')}}</label>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="form-row">
                                                                                                
                                                                                                <div class="form-group col-md-4">
                                                                                                    <label for="accNum">{{ trans('form.registration.investigation.sub_bank_ac_no') }}</label>
                                                                                                    <input type="text" name="subjects[{{$subIndx}}][bank_account_no]" class="form-control arr_contacts_accNum"
                                                                                                           value="{{ $subject->bank_account_no }}"
                                                                                                           placeholder="{{ trans('form.registration.investigation.sub_bank_ac_no') }}">
                                                                                                </div>

                                                                                                <div class="form-group col-md-4">
                                                                                                    <label>{{ trans('form.registration.investigation.workplace') }}</label>
                                                                                                    <input type="text" value="{{ $subject->workplace }}"
                                                                                                           class="form-control arr_contacts_workplace"
                                                                                                           name="subjects[{{$subIndx}}][workplace]"
                                                                                                           placeholder="{{ trans('form.registration.investigation.workplace') }}"
                                                                                                           data-im-insert="true">
                                                                                                </div>

                                                                                                <div class="form-group col-md-4">
                                                                                                    <label>{{ trans('form.registration.investigation.website') }}</label>
                                                                                                    <input type="text" value="{{ $subject->website }}"
                                                                                                           class="form-control arr_contacts_website"
                                                                                                           name="subjects[{{$subIndx}}][website]"
                                                                                                           placeholder="{{ trans('form.registration.investigation.website') }}"
                                                                                                           data-im-insert="true">
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="form-row">
                                                                                                <div class="form-group col-md-4">
                                                                                                    <label for="fatherName">{{ trans('form.registration.investigation.father_name') }}</label>
                                                                                                    <input type="text" name="subjects[{{$subIndx}}][father_name]" value="{{ $subject->father_name }}"
                                                                                                           class="form-control arr_contacts_fatherName" autofocus
                                                                                                           placeholder="{{ trans('form.registration.investigation.father_name') }}">
                                                                                                </div>
                                                                                                <div class="form-group col-md-4">
                                                                                                    <label for="motherName">{{ trans('form.registration.investigation.mothername') }}</label>
                                                                                                    <input type="text" name="subjects[{{$subIndx}}][mother_name]" value="{{ $subject->mother_name }}"
                                                                                                           class="form-control arr_contacts_motherName" autofocus
                                                                                                           placeholder="{{ trans('form.registration.investigation.mothername') }}">
                                                                                                </div>
                                                                                                <div class="form-group col-md-4">
                                                                                                    <label for="spouseName">{{ trans('form.registration.investigation.spousename') }}</label>
                                                                                                    <input type="text" name="subjects[{{$subIndx}}][spouse_name]" value="{{ $subject->spouse_name }}"
                                                                                                           class="form-control arr_contacts_spouseName" autofocus
                                                                                                           placeholder="{{ trans('form.registration.investigation.spousename') }}">
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="form-row">
                                                                                                <div class="form-group {{($acflag)?'col-md-4':'col-md-4'}}">
                                                                                                    <label for="spouses">{{ trans('form.registration.investigation.spouse') }}</label>
                                                                                                    <input type="number" onkeypress="settheSubjectId(this)" 
                                                                                                            id="subjects_spouses_id_{{$subIndx}}"
                                                                                                            name="subjects[{{$subIndx}}][spouse]" 
                                                                                                            value="{{ $subject->spouse }}"
                                                                                                            class="form-control arr_contacts_spouses" autofocus
                                                                                                            placeholder="{{ trans('form.registration.investigation.spouse') }}"
                                                                                                            data-im-insert="true" data-index="{{$subIndx}}" data-allow="">
                                                                                                    <label id="id-spouses-error-{{$subIndx}}" class="id_spouses_error error d-none" for="id-spouses-error">{{trans('form.registration.investigation.id_spouses_error')}}</label>
                                                                                                </div>
                                                                                                <div class="form-group {{($acflag)?'col-md-4':'col-md-4'}}">
                                                                                                    <label for="carNum">{{ trans('form.registration.investigation.carnumber') }}</label>
                                                                                                    <input type="text" name="subjects[{{$subIndx}}][car_number]" value="{{ $subject->car_number }}"
                                                                                                           class="form-control arr_contacts_carNum" autofocus
                                                                                                           placeholder="{{ trans('form.registration.investigation.carnumber') }}">
                                                                                                </div>
                                                                                                <div class="form-group {{($acflag)?'col-md-4 d-none':'col-md-4'}}">
                                                                                                    <label for="invCost">{{ trans('form.registration.investigation.required_cost_invs') }}({{ trans('general.money_symbol')}}) <span
                                                                                                                class="text-danger">*</span></label>
                                                                                                    <input type="number" name="subjects[{{$subIndx}}][req_inv_cost]" value="{{ $subject->req_inv_cost }}"
                                                                                                           class="form-control input_required_s2 arr_contacts_invCost" autofocus required
                                                                                                           placeholder="{{ trans('form.registration.investigation.required_cost_invs') }}"
                                                                                                    readonly>
                                                                                                </div>
                                                                                                <div class="form-group {{($acflag)?'col-md-4':'col-md-4'}}">
                                                                                                    <label for="passport">{{ trans('form.registration.investigation.passport') }}</label>
                                                                                                    <input type="number" onkeypress="settheSubjectId(this)" name="subjects[{{$subIndx}}][passport]" 
                                                                                                            value="{{ $subject->passport }}"
                                                                                                            class="form-control arr_contacts_passport" autofocus
                                                                                                            placeholder="{{ trans('form.registration.investigation.passport') }}"
                                                                                                            id="subjects_passport_id_{{$subIndx}}"
                                                                                                            data-index="{{$subIndx}}" 
                                                                                                            data-allow="">
                                                                                                    <label id="id-passport-error-{{$subIndx}}" class="id_passport_error error d-none" for="id-passport-error">{{trans('form.registration.investigation.id_passport_error')}}</label>
                                                                                                </div>
                                                                                                <div class="form-group {{($acflag)?'col-md-4':'col-md-4'}}">
                                                                                                    <label for="dob">{{ trans('form.registration.investigator.date_of_birth') }}</label>
                                                                                                    <input type="date" name="subjects[{{$subIndx}}][dob]" value="{{ $subject->dob }}"
                                                                                                           class="form-control arr_contacts_dob" autofocus
                                                                                                           placeholder="{{ trans('form.registration.investigator.date_of_birth') }}">
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="form-row">

                                                                                               
                                                                                                <div class="form-group col-md-4">
                                                                                                    <label for="additionalDetails">{{ trans('form.registration.investigation.add_ass_detail') }}</label>
                                                                                                    <input type="text" name="subjects[{{$subIndx}}][assistive_details]" value="{{ $subject->assistive_details }}"
                                                                                                           class="form-control arr_contacts_additionalDetails" autofocus
                                                                                                           placeholder="{{ trans('form.registration.investigation.add_ass_detail') }}">
                                                                                                </div>
                                                                                                @php $seloth = 0; @endphp
                                                                                                @foreach($subtypes as $id => $type_name)
                                                                                                    @if($type_name['name'] == $subject->sub_type)
                                                                                                        @php $seloth = 1; @endphp
                                                                                                    @endif
                                                                                                @endforeach
                                                                                                <div class="form-group col-md-4">
                                                                                                    <label for="sub_type">{{ trans('form.registration.investigation.sub_type') }}</label>
                                                                                                    <select onchange="changeotheroldtextbox(this);changesubjecttitlebyid(this.id);" name="subjects[{{$subIndx}}][sub_type]" id="arr_contacts_type_{{$subIndx}}" class="form-control arr_contacts_subType multiple_type_required_s2" >
                                                                                                        @foreach($subtypes as $type)
                                                                                                            <option {{ (((($type['name'] == 'Other' || $type['name'] == 'Contact')) && $seloth==0) || ($type['name'] == $subject->sub_type)) ? 'selected' : '' }} value="{{ $type['name'] }}">
                                                                                                            {{ App::isLocale('hr')?$type['hr_name']:$type['name'] }}
                                                                                                            </option>
                                                                                                            @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                                <div class="form-group col-md-4 arr_contacts_other_text_div {{ ($seloth==0 || ($subject->sub_type == 'Other' || $subject->sub_type == 'Contact'))? 'd-block' : 'd-none' }}" id="arr_contacts_type_{{$subIndx}}_div">
                                                                                                    <label>{{ trans('form.registration.client.type') }}</label>
                                                                                
                                                                                                    <input type="text" id="arr_contacts_type_{{$subIndx}}_otext" data-id="arr_contacts_type_{{$subIndx}}" onchange="changeothersubjecttitle(this);" value="{{ ($seloth==0) ? $subject->sub_type  : '' }}"
                                                                                                           class="form-control numeric arr_contacts_other_text_type"
                                                                                                           name="subjects[{{$subIndx}}][other_text]"
                                                                                                           placeholder="{{ trans('form.registration.client.type_helper') }}"
                                                                                                           
                                                                                                           {{ ($seloth==0 || ($subject->sub_type == 'Other' || $subject->sub_type == 'Contact'))? 'required' : '' }}
                                                                                                           >
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="form-row mt-3">
                                                                                                <div class="col-md-12">
                                                                                                    <span class="text-muted font-size-14 mb-1">{{ trans('form.registration.investigation.subject_contact_details') }}</span>
                                                                                                    <hr class="mt-2">
                                                                                                </div>
                                                                                            </div>
                                                                                                <div class="form-row">
                                                                                                    <div class="form-group col-md-3">
                                                                                                        <label>{{ trans('form.registration.investigation.main_email') }} </label>
                                                                                                        <input type="email" value="{{ $subject->main_email }}" 
                                                                                                               class="form-control arr_contacts_mainemails"
                                                                                                               name="subjects[{{$subIndx}}][main_email]"
                                                                                                               placeholder="{{trans('general.email_placeholder')}}" 
                                                                                                        >
                                                                                                    </div>
                                                                                                    <div class="form-group col-md-3">
                                                                                                        <label>{{ trans('form.registration.investigation.alternate_email') }} </label>
                                                                                                        <input type="email" value="{{ $subject->alternate_email }}"
                                                                                                               class="form-control arr_contacts_alternateemails"
                                                                                                               name="subjects[{{$subIndx}}][alternate_email]"
                                                                                                               placeholder="{{trans('general.email_placeholder')}}"
                                                                                                        >
                                                                                                    </div>
                                                                                                    <div class="form-group col-md-3">
                                                                                                        <label>{{ trans('form.registration.investigation.main_phone') }} </label>
                                                                                                        <input type="tel" value="{{ $subject->main_phone }}"
                                                                                                               class="form-control arr_contacts_mainphones" 
                                                                                                               name="subjects[{{$subIndx}}][main_phone]"
                                                                                                               placeholder="{{trans('general.phone_placeholder')}}"
                                                                                                                >
                                                                                                    </div>
                                                                                                    <div class="form-group col-md-3">
                                                                                                        <label>{{ trans('form.registration.investigation.secondary_phone') }}</label>
                                                                                                        <input type="tel" value="{{ $subject->secondary_phone }}"
                                                                                                               class="form-control arr_contacts_secondaryphones" 
                                                                                                               name="subjects[{{$subIndx}}][secondary_phone]"
                                                                                                               placeholder="{{trans('general.phone_placeholder')}}"
                                                                                                              >
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="form-row">
                                                                                                    <div class="form-group col-md-4">
                                                                                                        <label>{{ trans('form.registration.investigation.main_mobile') }} </label>
                                                                                                        <input type="tel" value="{{ $subject->main_mobile }}"
                                                                                                               class="form-control arr_contacts_mainmobiles" 
                                                                                                               name="subjects[{{$subIndx}}][main_mobile]"
                                                                                                               placeholder="{{trans('general.phone_placeholder')}}"
                                                                                                                >
                                                                                                    </div>
                                                                                                    <div class="form-group col-md-4">
                                                                                                        <label>{{ trans('form.registration.investigation.secondary_mobile') }}</label>
                                                                                                        <input type="tel" value="{{ $subject->secondary_mobile }}"
                                                                                                               class="form-control arr_contacts_secondarymobiles" 
                                                                                                               name="subjects[{{$subIndx}}][secondary_mobile]"
                                                                                                               placeholder="{{trans('general.phone_placeholder')}}"
                                                                                                             >
                                                                                                    </div>
                                                                                                    <div class="form-group col-md-4">
                                                                                                        <label>{{ trans('form.registration.investigation.fax') }} </label>
                                                                                                        <input type="tel" value="{{ $subject->fax }}"
                                                                                                               class="form-control arr_contacts_fax" 
                                                                                                               name="subjects[{{$subIndx}}][fax]"
                                                                                                               placeholder="{{trans('general.fax_placeholder')}}"
                                                                                                               >
                                                                                                    </div>
                                                                                                </div>

                                                                                            <!-- Block for multiple addresses-->
                                                                                            <div class="form-row mt-3">
                                                                                                <div class="col-md-12">
                                                                                                    <span class="text-muted font-size-14 mb-1">{{ trans('form.registration.investigation.subject_address_details') }}</span>
                                                                                                    <hr class="mt-2">
                                                                                                </div>
                                                                                            </div>

                                                                                            <div>
                                                                                                <div class="table-wrapper">
                                                                                                    <div class="table-responsive mb-0 fixed-solution"
                                                                                                         data-pattern="priority-columns">
                                                                                                        <div id="addresses_tbl">
                                                                                                            <table class="table table-borderless mb-0">
                                                                                                                <tbody id="address-accordion-{{$subIndx}}" class="addresses_tbl_tbody">

                                                                                                                <!-- Block for multiple addresses loop-->
                                                                                                                @php $addrIndx = 1; @endphp
                                                                                                                @foreach($subject->subject_addresses as $address)

                                                                                                                    <tr class="address-base-row-{{$subIndx}}">
                                                                                                                        <td style="width: 100%;display: inline-table;">
                                                                                                                            <div class="card mb-1 shadow-none" id="address_clone_element-{{$subIndx.'-'.$addrIndx}}">
                                                                                                                                <div class="card-header p-3" id="address_heading"
                                                                                                                                     style="padding-bottom: 0 !important;">
                                                                                                                                    <div class="row"
                                                                                                                                         style="padding-right: 0; padding-left: 0">
                                                                                                                                        <div class="col-sm-12 col-md-6 text-left">
                                                                                                                                            <h6 class="m-0 font-size-14">
                                                                                                                                                <a href="#address_collapse-{{$subIndx.'-'.$addrIndx}}"
                                                                                                                                                   class="text-dark collapsed address_header"
                                                                                                                                                   data-toggle="collapse"
                                                                                                                                                   aria-expanded="true"
                                                                                                                                                   aria-controls="collapse_1">
                                                                                                                                                    {{ trans('form.address') }} : <span id="arr_address_type_{{$subIndx.'-'.$addrIndx}}_title" class="address_row_no">{{$address->address_type}}</span>
                                                                                                                                                </a>
                                                                                                                                            </h6>
                                                                                                                                        </div>
                                                                                                                                        <div class="col-sm-12 col-md-6 text-sm-right">
                                                                                                                                            <button type="button"
                                                                                                                                                    onclick="deleteAddress(this)"
                                                                                                                                                    class="btn btn-link waves-effect delete-address-btn"
                                                                                                                                                    style="text-decoration: underline" id="{{$subIndx}}">
                                                                                                                                                {{ trans('general.delete') }}
                                                                                                                                            </button>
                                                                                                                                        </div>
                                                                                                                                    </div>

                                                                                                                                </div>

                                                                                                                                <div id="address_collapse-{{$subIndx.'-'.$addrIndx}}"
                                                                                                                                     class="collapse address_block show"
                                                                                                                                     aria-labelledby="address_heading"
                                                                                                                                     data-parent="#address-accordion" style="">
                                                                                                                                    <div class="card-body">
                                                                                                                                        <div class="form-row">
                                                                                                                                            <div class="form-group col-md-6">
                                                                                                                                                <label>{{ trans('form.registration.client.address') }}
                                                                                                                                                    <span class="text-danger">*</span></label>
                                                                                                                                                <input type="text" value="{{ $address->address1 }}"
                                                                                                                                                       class="form-control pac-target-input multiple_input_required_s2 arr_address_address1"
                                                                                                                                                       name="subjects[{{$subIndx}}][address][{{$addrIndx}}][address1]"
                                                                                                                                                       id="address_complete_{{$subIndx.'-'.$addrIndx}}"
                                                                                                                                                       placeholder="{{ trans('form.registration.client.address_helper') }}"
                                                                                                                                                       required
                                                                                                                                                       autocomplete="off">

                                                                                                                                            </div>

                                                                                                                                            <div class="form-group col-md-6">
                                                                                                                                                <label>{{ trans('form.registration.client.address2') }}</label>
                                                                                                                                                <input type="text" value="{{ $address->address2 }}"
                                                                                                                                                       class="form-control arr_address_address2"
                                                                                                                                                       name="subjects[{{$subIndx}}][address][{{$addrIndx}}][address2]"
                                                                                                                                                       id="address2_{{$subIndx.'-'.$addrIndx}}"
                                                                                                                                                       placeholder="{{ trans('form.registration.client.address_2_helper') }}">
                                                                                                                                            </div>
                                                                                                                                        </div>

                                                                                                                                        <div class="form-row">
                                                                                                                                            <div class="form-group col-md-3">
                                                                                                                                                <label>{{ trans('form.registration.client.city') }}
                                                                                                                                                    <span class="text-danger">*</span></label>
                                                                                                                                                <input type="text" value="{{ $address->city }}"
                                                                                                                                                       class="form-control multiple_input_required_s2 arr_address_city"
                                                                                                                                                       name="subjects[{{$subIndx}}][address][{{$addrIndx}}][city]"
                                                                                                                                                       id="city_{{$subIndx.'-'.$addrIndx}}"
                                                                                                                                                       placeholder="{{ trans('form.registration.client.city') }}"
                                                                                                                                                       required>
                                                                                                                                            </div>

                                                                                                                                            <div class="form-group col-md-3">
                                                                                                                                                <label>{{ trans('form.registration.client.state') }}
                                                                                                                                                    <span class="text-danger">*</span></label>
                                                                                                                                                <input type="text" value="{{ $address->state }}"
                                                                                                                                                       class="form-control multiple_input_required_s2 arr_address_state"
                                                                                                                                                       name="subjects[{{$subIndx}}][address][{{$addrIndx}}][state]"
                                                                                                                                                       id="state_{{$subIndx.'-'.$addrIndx}}"
                                                                                                                                                       placeholder="{{ trans('form.registration.client.state') }}"
                                                                                                                                                       required>
                                                                                                                                            </div>

                                                                                                                                            <div class="form-group col-md-3">
                                                                                                                                                <label>{{ trans('form.registration.client.country') }}
                                                                                                                                                    <span class="text-danger">*</span></label>
                                                                                                                                                <select name="subjects[{{$subIndx}}][address][{{$addrIndx}}][country_id]"
                                                                                                                                                        id="country_{{$subIndx.'-'.$addrIndx}}"
                                                                                                                                                        class="form-control multiple_input_required_s2 arr_address_country"
                                                                                                                                                        required>
                                                                                                                                                    @foreach($countries as $id => $country_name)
                                                                                                                                                        <option value="{{ $country_name['id'] }}" {{ $address->country_id == $country_name['id'] ? 'selected' : '' }}>{{ App::isLocale('hr')?$country_name['hr_name']:$country_name['en_name'] }}</option>
                                                                                                                                                    @endforeach
                                                                                                                                                </select>
                                                                                                                                            </div>

                                                                                                                                            <div class="form-group col-md-3">
                                                                                                                                                <label>{{ trans('form.registration.client.zip_code') }}
                                                                                                                                                    </label>
                                                                                                                                                <input type="text" value="{{ $address->zipcode }}"
                                                                                                                                                       class="form-control numeric  arr_address_zip"
                                                                                                                                                       name="subjects[{{$subIndx}}][address][{{$addrIndx}}][zipcode]"
                                                                                                                                                       id="zipcode_{{$subIndx.'-'.$addrIndx}}"
                                                                                                                                                       placeholder="{{ trans('form.registration.client.zip_code') }}"
                                                                                                                                                       >
                                                                                                                                            </div>

                                                                                                                                            <div class="form-group col-md-4">

                                                                                                                                                @php $seloth = 0; @endphp
                                                                                                                                                @foreach($contacttypes as $id => $contact_name)
                                                                                                                                                    @if($contact_name['type_name'] == $address->address_type)
                                                                                                                                                        @php $seloth = 1; @endphp
                                                                                                                                                    @endif
                                                                                                                                                @endforeach

                                                                                                                                                <label>{{ trans('form.registration.client.address_type') }}</label>
                                                                                                                                                <input type="text" id="arr_address_type_{{$subIndx.'-'.$addrIndx}}" name="subjects[{{$subIndx}}][address][{{$addrIndx}}][address_type]" value="{{$address->address_type??''}}" class="form-control multiple_input_required_s2 arr_address_type multiple_type_required_s2" name="subjects[{{$subIndx}}][address][{{$addrIndx}}][address_type]" placeholder="{{ trans('form.registration.client.address_type') }}" required>
                                                                                                                                                {{--<select id="arr_address_type_{{$subIndx.'-'.$addrIndx}}" onchange="changeotheroldtextbox(this);changesubjecttitlebyid(this.id);"
                                                                                                                                                        name="subjects[{{$subIndx}}][address][{{$addrIndx}}][address_type]"
                                                                                                                                                        class="form-control multiple_input_required_s2 arr_address_type multiple_type_required_s2" required>
                                                                                                                                                    @foreach($contacttypes as $id => $contact_name)
                                                                                                                                                        <option value="{{ $contact_name['type_name'] }}" {{ (($seloth == 0) || $contact_name['type_name'] == $address->address_type ? 'selected' : '' }}>
                                                                                                                                                        {{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}
                                                                                                                                                        </option>
                                                                                                                                                    @endforeach
                                                                                                                                                </select>--}}
                                                                                                                                            </div>

                                                                                                                                            <div id="arr_address_type_{{$subIndx.'-'.$addrIndx}}_div" class="form-group col-md-4 arr_add_other_text_div {{ ($seloth == 0 ) ? 'd-block' : 'd-none' }}">
                                                                                                                                            <label class="address_other_no">{{ trans('form.registration.client.type') }}</label>

                                                                                                                                                <input onchange="changeothersubjecttitle(this);" type="text" id="arr_address_type_{{$subIndx.'-'.$addrIndx}}_otext" data-id="arr_address_type_{{$subIndx.'-'.$addrIndx}}"  value="{{ ($seloth == 0) ? $address->address_type  : '' }}"
                                                                                                                                                       class="form-control numeric arr_add_other_text_type"
                                                                                                                                                       name="subjects[{{$subIndx}}][address][{{$addrIndx}}][other_text]"
                                                                                                                                                       placeholder="{{ trans('form.registration.client.type_helper') }}"
                                                                                                                                                       {{ ($seloth==0 || ($address->address_type == 'Other' || $address->address_type == 'Contact'))? 'required' : '' }}
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
                                                                                                                <!-- Block for multiple addresses loop-->

                                                                                                                </tbody>
                                                                                                                <tfoot>
                                                                                                                <tr><td id="address_footer" class="text-center" style="{{ !$subject->subject_addresses->isEmpty() ? 'display:none' : '' }}"> {{ trans('form.registration.deliveryboy.no_record_added') }} </td></tr>
                                                                                                                </tfoot>
                                                                                                            </table>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="form-row">
                                                                                                        <div class="col-md-12 text-right">
                                                                                                            <button type="button" onclick="addNewAddress(this)"
                                                                                                                    class="btn btn-link btn-lg waves-effect add-address-btn" id="{{$subIndx}}"
                                                                                                                    style="text-decoration: underline">{{ trans('form.add_address') }}
                                                                                                            </button>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <!-- Block for multiple addresses-->

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        @php $subIndx++; @endphp
                                                                    @endforeach

                                                                    <!-- Block for multiple subjects loop-->
                                                                    </tbody>
                                                                    <tfoot>
                                                                    <tr>
                                                                        <td id="contacts_footer" class="text-center" style="{{ !$invn->subjects->isEmpty() ? 'display:none' : '' }}"> {{ trans('form.registration.deliveryboy.no_record_added') }}
                                                                        </td>
                                                                    </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            @if(isClient())
                                                                <div class="col-md-10">
                                                                    <div class="pt-3 custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input input_required_s2" id="approval_text" name="approval_text" value="1">
                                                                        <label class="custom-control-label" for="approval_text">{{ trans('form.investigation.approval_text') }}</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 text-right">
                                                                @else
                                                                <div class="col-md-12 text-right">
                                                                @endif
                                                                <button type="button" onclick="addNewContact()"
                                                                        class="btn btn-link btn-lg waves-effect"
                                                                        style="text-decoration: underline">{{ trans('form.registration.investigation.add_subject') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Block for multiple Subjects-->


                                                    <!-- Next-Prev Buttons -->
                                                    <div class="form-group row mt-4">
                                                        <div class="col-12 text-right text-xs-center">
                                                            <button onclick="loadSteps('step1', 'step2');"
                                                                class="btn btn-secondary w-md waves-effect waves-light"
                                                                type="button">{{ trans('general.previous') }}</button>
                                                            <button 
                                                                class="btn btn-primary w-md waves-effect waves-light create-btn"
                                                                type="submit">{{ trans('general.create') }}</button>
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


{{-- start contact div --}}
<div class="card mb-1 shadow-none" id="contact_clone_element" style="display: none">

    <div class="card-header p-3" id="heading_contacts"
         style="padding-bottom: 0 !important;">
        <div class="row"
             style="padding-right: 0; padding-left: 0">
            <div class="col-sm-12 col-md-6 text-left">
                <h6 class="m-0 font-size-14">
                    <a href="#contact_collapse"
                       class="text-dark collapsed contact_header"
                       data-toggle="collapse"
                       aria-expanded="true"
                       aria-controls="contact_collapse">
                        {{ trans('form.registration.investigation.subject') }} : <span class="contact_row_no"></span>
                    </a>
                    @php if (isAdmin() || isSM()) 
                    {$acflag='';}else{$acflag='d-none';}@endphp
                           
                    <div class="form-check-inline m-0 {{$acflag}}"> 
                    <label for="contacts_is_default" class="form-check-label"> | {{ trans('form.registration.investigation.is_address_confirmed') }}</label>
                    <input name="subjects[][address_confirmed]" class="arr_contacts_address_confirmed" type="checkbox" switch="bool">
                    <label class="mt-2 ml-1 arr_contacts_address_confirmed_lable" data-on-label="Yes" data-off-label="No"></label>
                    </div>
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
         aria-labelledby="heading_contacts"
         data-parent="#contact-accordion" style="">

        <div class="card-body">

            <!-- Block for multiple subjects-->
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>{{ trans('form.registration.investigation.family') }}<span class="text-danger">*</span></label>
                    <input type="text" value=""
                           class="form-control arr_contacts_family input_required_s2"
                           name="subjects[]['family_name']"
                           placeholder="{{ trans('form.registration.investigation.family') }}"
                           data-im-insert="true" required>
                </div>

                <div class="form-group col-md-4">
                    <label>{{ trans('form.registration.investigation.firstname') }}<span class="text-danger">*</span></label>
                    <input type="text" value=""
                           class="form-control arr_contacts_firstname input_required_s2"
                           name="subjects[]['first_name']"
                           placeholder="{{ trans('form.registration.investigation.firstname') }}"
                           data-im-insert="true" required>
                </div>

                <div class="form-group col-md-4">
                    <label>{{ trans('form.registration.investigation.id') }}</label>
                    <input onkeypress="settheSubjectId(this)" type="number" maxwidth="9" value=""
                           class="form-control arr_contacts_id"
                           name="subjects[]['id_number']"
                           placeholder="{{ trans('form.registration.investigation.id') }}"
                           data-im-insert="true">
                    <label id="" class="id_error error d-none" for="id-error">{{trans('form.registration.investigation.id_error')}}</label>
                </div>
            </div>

            <div class="form-row">
                
                <div class="form-group col-md-4">
                    <label for="accNum">{{ trans('form.registration.investigation.sub_bank_ac_no') }}</label>
                    <input type="text" name="subjects[]['bank_account_no']" class="form-control arr_contacts_accNum"
                           value=""
                           placeholder="{{ trans('form.registration.investigation.sub_bank_ac_no') }}">
                </div>
                <div class="form-group col-md-4">
                    <label>{{ trans('form.registration.investigation.workplace') }}</label>
                    <input type="text" value=""
                           class="form-control arr_contacts_workplace"
                           name="subjects[]['workplace']"
                           placeholder="{{ trans('form.registration.investigation.workplace') }}"
                           data-im-insert="true">
                </div>

                <div class="form-group col-md-4">
                    <label>{{ trans('form.registration.investigation.website') }}</label>
                    <input type="text" value=""
                           class="form-control arr_contacts_website"
                           name="subjects[]['website']"
                           placeholder="{{ trans('form.registration.investigation.website') }}"
                           data-im-insert="true">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="fatherName">{{ trans('form.registration.investigation.father_name') }}</label>
                    <input type="text" name="subjects[]['father_name']" value=""
                           class="form-control arr_contacts_fatherName" autofocus
                           placeholder="{{ trans('form.registration.investigation.father_name') }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="motherName">{{ trans('form.registration.investigation.mothername') }}</label>
                    <input type="text" name="subjects[]['mother_name']" value=""
                           class="form-control arr_contacts_motherName" autofocus
                           placeholder="{{ trans('form.registration.investigation.mothername') }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="spouseName">{{ trans('form.registration.investigation.spousename') }}</label>
                    <input type="text" name="subjects[]['spouse_name']" value=""
                           class="form-control arr_contacts_spouseName" autofocus
                           placeholder="{{ trans('form.registration.investigation.spousename') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group {{($acflag)?'col-md-4':'col-md-4'}}">
                    <label for="spouses">{{ trans('form.registration.investigation.spouse') }}</label>
                    <input type="number" onkeypress="settheSubjectId(this)" name="subjects[]['spouse']" value=""
                           class="form-control arr_contacts_spouses" autofocus
                           placeholder="{{ trans('form.registration.investigation.spouse') }}">
                    <label id="" class="id_spouses_error error d-none" for="id-spouses-error">{{trans('form.registration.investigation.id_spouses_error')}}</label>
                </div>
                <div class="form-group {{($acflag)?'col-md-4':'col-md-4'}}">
                    <label for="carNum">{{ trans('form.registration.investigation.carnumber') }}</label>
                    <input type="text" name="subjects[]['car_number']" value=""
                           class="form-control arr_contacts_carNum" autofocus
                           placeholder="{{ trans('form.registration.investigation.carnumber') }}">
                </div>
                <div class="form-group {{($acflag)?'col-md-4 d-none':'col-md-4'}}">
                    <label for="invCost">{{ trans('form.registration.investigation.required_cost_invs') }}({{ trans('general.money_symbol')}}) <span class="text-danger">*</span></label>
                    <input type="number" name="subjects[]['req_inv_cost']" value=""
                           class="form-control input_required_s2 arr_contacts_invCost" autofocus required
                           placeholder="{{ trans('form.registration.investigation.required_cost_invs') }}"
                    readonly>
                </div>
                <div class="form-group {{($acflag)?'col-md-4':'col-md-4'}}">
                    <label for="passport">{{ trans('form.registration.investigation.passport') }}</label>
                    <input type="number" onkeypress="settheSubjectId(this)" name="subjects[]['passport']" value=""
                           class="form-control arr_contacts_passport" autofocus
                           placeholder="{{ trans('form.registration.investigation.passport') }}">
                    <label id="" class="id_passport_error error d-none" for="id-passport-error">{{trans('form.registration.investigation.id_passport_error')}}</label>
                </div>
                <div class="form-group {{($acflag)?'col-md-4':'col-md-4'}}">
                    <label for="dob">{{ trans('form.registration.investigator.date_of_birth') }}</label>
                    <input type="date" name="subjects[]['dob']" value=""
                           class="form-control arr_contacts_dob" autofocus
                           placeholder="{{ trans('form.registration.investigator.date_of_birth') }}">
                </div>
            </div>

            <div class="form-row">

               
                <div class="form-group col-md-4">
                    <label for="additionalDetails">{{ trans('form.registration.investigation.add_ass_detail') }}</label>
                    <input type="text" name="subjects[]['assistive_details']" value=""
                           class="form-control arr_contacts_additionalDetails" autofocus
                           placeholder="{{ trans('form.registration.investigation.add_ass_detail') }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="sub_type">{{ trans('form.registration.investigation.sub_type') }}</label>
                    <select onchange="changeothertextbox(this);changesubjecttitlebyid(this.id);" name="subjects[][sub_type]" class="form-control arr_contacts_subType multiple_type_required_s2">
                        @foreach($subtypes as $type)
                            <option value="{{ $type['name'] }}" {{ old('sub_type', $subject->sub_type) == $type['name'] ? 'selected' : '' }}>
                            {{ App::isLocale('hr')?$type['hr_name']:$type['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4 arr_contacts_other_text_div d-none">
                    <label>{{ trans('form.registration.client.type') }}</label>

                    <input type="text" onchange="changeothersubjecttitle(this);" value=""
                           class="form-control numeric arr_contacts_other_text_type"
                           name="subjects[][other_text]"
                           placeholder="{{ trans('form.registration.client.type_helper') }}">
                </div>
            </div>

            <div class="form-row mt-3">
                <div class="col-md-12">
                    <span class="text-muted font-size-14 mb-1">{{ trans('form.registration.investigation.subject_contact_details') }}</span>
                    <hr class="mt-2">
                </div>
            </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>{{ trans('form.registration.investigation.main_email') }} </label>
                        <input type="email" value=""
                               class="form-control arr_contacts_mainemails"
                               name="subjects[]['main_email']"
                               placeholder="{{trans('general.email_placeholder')}}"
                        >
                    </div>
                    <div class="form-group col-md-3">
                        <label>{{ trans('form.registration.investigation.alternate_email') }} </label>
                        <input type="email" value=""
                               class="form-control arr_contacts_alternateemails"
                               name="subjects[]['alternate_email']"
                               placeholder="{{trans('general.email_placeholder')}}"
                        >
                    </div>
                    <div class="form-group col-md-3">
                        <label>{{ trans('form.registration.investigation.main_phone') }} </label>
                        <input type="tel" value=""
                               class="form-control arr_contacts_mainphones" 
                               name="subjects[]['main_phone']"
                               placeholder="{{trans('general.phone_placeholder')}}"
                            >
                    </div>
                    <div class="form-group col-md-3">
                        <label>{{ trans('form.registration.investigation.secondary_phone') }}</label>
                        <input type="tel" value=""
                               class="form-control arr_contacts_secondaryphones" 
                               name="subjects[]['secondary_phone']"
                               placeholder="{{trans('general.phone_placeholder')}}"
                              >
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>{{ trans('form.registration.investigation.main_mobile') }} </label>
                        <input type="tel" value=""
                               class="form-control arr_contacts_mainmobiles" 
                               name="subjects[]['main_mobile']"
                               placeholder="{{trans('general.phone_placeholder')}}"
                                >
                    </div>
                    <div class="form-group col-md-4">
                        <label>{{ trans('form.registration.investigation.secondary_mobile') }}</label>
                        <input type="tel" value=""
                               class="form-control arr_contacts_secondarymobiles" 
                               name="subjects[]['secondary_mobile']"
                               placeholder="{{trans('general.phone_placeholder')}}"
                             >
                    </div>
                    <div class="form-group col-md-4">
                        <label>{{ trans('form.registration.investigation.fax') }} </label>
                        <input type="tel" value=""
                               class="form-control arr_contacts_fax" 
                               name="subjects[]['fax']"
                               placeholder="{{trans('general.fax_placeholder')}}"
                               >
                    </div>
                </div>

            <!-- Block for multiple addresses-->
            <div class="form-row mt-3">
                <div class="col-md-12">
                    <span class="text-muted font-size-14 mb-1">{{ trans('form.registration.investigation.subject_address_details') }}</span>
                    <hr class="mt-2">
                </div>
            </div>

            <div>
                <div class="table-wrapper">
                    <div class="table-responsive mb-0 fixed-solution"
                         data-pattern="priority-columns">
                        <div id="addresses_tbl">
                            <table class="table table-borderless mb-0">
                                <tbody id="address-accordion" class="addresses_tbl_tbody">
                                </tbody>
                                <tfoot>
                                <tr><td id="address_footer" class="text-center"> {{ trans('general.no_record_added') }} </td></tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 text-right">
                            <button type="button" onclick="addNewAddress(this)"
                                    class="btn btn-link btn-lg waves-effect add-address-btn" id=""
                                    style="text-decoration: underline">{{ trans('form.add_address') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Block for multiple addresses-->

        </div>
    </div>
</div>
{{-- end contact div --}}

{{-- start address div --}}
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
                       aria-expanded="true"
                       aria-controls="collapse_1">
                        {{ trans('form.address') }} : <span class="address_row_no"></span>
                    </a>
                </h6>
            </div>
            <div class="col-sm-12 col-md-6 text-sm-right">
                <button type="button"
                        onclick="deleteAddress(this)"
                        class="btn btn-link waves-effect delete-address-btn"
                        style="text-decoration: underline" id="">
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
                           name="subjects[]['address'][]['address1']"
                           placeholder="{{ trans('form.registration.client.address_helper') }}"
                           required
                           autocomplete="off">

                </div>

                <div class="form-group col-md-6">
                    <label>{{ trans('form.registration.client.address2') }}</label>
                    <input type="text" value=""
                           class="form-control arr_address_address2"
                           name="subjects[]['address'][]['address2']"
                           placeholder="{{ trans('form.registration.client.address_2_helper') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>{{ trans('form.registration.client.city') }}
                        <span class="text-danger">*</span></label>
                    <input type="text" value=""
                           class="form-control multiple_input_required_s2 arr_address_city"
                           name="subjects[]['address'][]['city']"
                           placeholder="{{ trans('form.registration.client.city') }}"
                           required>
                </div>

                <div class="form-group col-md-3">
                    <label>{{ trans('form.registration.client.state') }}
                        <span class="text-danger">*</span></label>
                    <input type="text" value=""
                           class="form-control multiple_input_required_s2 arr_address_state"
                           name="subjects[]['address'][]['state']"
                           placeholder="{{ trans('form.registration.client.state') }}"
                           required>
                </div>

                <div class="form-group col-md-3">
                    <label>{{ trans('form.registration.client.country') }}
                        <span class="text-danger">*</span></label>
                    <select name="subjects[]['address'][]['country_id']"
                            class="form-control multiple_input_required_s2 arr_address_country"
                            required>
                        @foreach($countries as $id => $country_name)
                            <option value="{{ $country_name['id'] }}" {{ old('country_id') == $country_name['id'] ? 'selected' : '' }}>{{ App::isLocale('hr')?$country_name['hr_name']:$country_name['en_name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-3">
                    <label>{{ trans('form.registration.client.zip_code') }}
                        </label>
                    <input type="text" value=""
                           class="form-control numeric arr_address_zip"
                           name="subjects[]['address'][]['zipcode']"
                           placeholder="{{ trans('form.registration.client.zip_code') }}"
                           >
                </div>

                <div class="form-group col-md-4">
                    <label>{{ trans('form.registration.client.address_type') }}</label>
                    <input type="text" name="subjects[]['address'][]['address_type']" value="" class="form-control multiple_input_required_s2 arr_address_type multiple_type_required_s2" name="subjects[{{$subIndx}}][address][{{$addrIndx}}][address_type]" placeholder="{{ trans('form.registration.client.address_type') }}" required>
                    {{--<select onchange="changeothertextbox(this);changesubjecttitlebyid(this.id);" name="subjects[]['address'][]['address_type']"
                            class="form-control multiple_input_required_s2 arr_address_type multiple_type_required_s2" required>
                        @foreach($contacttypes as $id => $contact_name)
                            <option value="{{ $contact_name['type_name'] }}" {{ old('address_type') == $contact_name['id'] ? 'selected' : '' }}>
                            {{ App::isLocale('hr')?$contact_name['hr_type_name']:$contact_name['type_name'] }}
                            </option>
                        @endforeach
                    </select>--}}
                </div>

                <div class="form-group col-md-4 arr_add_other_text_div d-none">
                    <label class="address_other_no">{{ trans('form.registration.client.type') }}</label>

                    <input type="text" onchange="changeothersubjecttitle(this);" value=""
                           class="form-control numeric arr_add_other_text_type"
                           name="subjects[]['address'][]['other_text']"
                           placeholder="{{ trans('form.registration.client.type_helper') }}">
                </div>

            </div>
        </div>
    </div>
</div>
{{-- start address div --}}





@endsection

@section('footerScript')
<!-- footerScript -->
<script src="{{ URL::asset('/libs/select2/select2.min.js') }}"></script>
<script src="{{ URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ URL::asset('/libs/jquery-smartwizard/jquery.smartWizard.js') }}"></script>
<script src="{{ URL::asset('/libs/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('/libs/inputmask/5.0.5/jquery.inputmask.js') }}" async></script>
<script src="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.js') }}" async></script>
<script src="{{ URL::asset('/libs/jquery-validate/1.19.0/jquery.validate.js')}}"></script> 
<script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_KEY')}}&libraries=places&language={{ App::isLocale('hr') ? 'iw' : 'en' }}" async defer></script>
@if (App::isLocale('hr'))
    <script src="{{ URL::asset('/libs/jquery-validate/1.19.0/localization/messages_he.js')}}"></script>
    @endif
<script>
    $(document).ready(function () {
        $(window).keydown(function (event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });

        $('#smartwizard').smartWizard({
            selected: 0, // Initial selected step, 0 = first step
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

        $("#investigation-form").validate({
            ignore: false,
            invalidHandler: function (e, validator) {
                // loop through the errors:
                for (var i = 0; i < validator.errorList.length; i++) {
                    $(validator.errorList[i].element).closest('.collapse').collapse('show');
                    console.log('dfdf',validator.errorList[i].element);
                    $(this).find(":input.error:first").focus();
                }
            }
        });

        var oldArr = {!! json_encode($oldArr) !!};

        $('.userinquiry_dd').select2();

        {{--$(".userinquiry_dd").select2({--}}
        {{--    placeholder: "{{ trans('form.registration.investigation.select_client_name') }}",--}}
        {{--    ajax: {--}}
        {{--        url: '{{ route('client.autocomplete') }}',--}}
        {{--        dataType: 'json',--}}
        {{--        delay: 250,--}}
        {{--        processResults: function (data) {--}}
        {{--            return {--}}
        {{--                results:  $.map(data, function (item) {--}}
        {{--                    return {--}}
        {{--                        text: item.name,--}}
        {{--                        id: item.id +'-'+item.name--}}
        {{--                    }--}}
        {{--                })--}}
        {{--            };--}}
        {{--        },--}}
        {{--        cache: true,--}}
        {{--    }--}}
        {{--});--}}

        $('#type_of_inquiry').on('change', function(e){
            if($('#type_of_inquiry option:selected').text()){
                console.log('inv_cost :>>', $('#type_of_inquiry option:selected').data('price'));
                $('#inv_cost').val($('#type_of_inquiry option:selected').data('price'));
                $('.arr_contacts_invCost').val($('#type_of_inquiry option:selected').data('price'));
                $("#product_spousecost").val($('#type_of_inquiry option:selected').data('spousecost'));
                console.log('inv_cost spousecost :>>', $('#type_of_inquiry option:selected').data('spousecost'));
                if($('#type_of_inquiry option:selected').data('isdelivery') == 'yes'){
                    $(".del-checks").show();
                    $("#product_isdel").val('yes');
                    $("#product_delcost").val($('#type_of_inquiry option:selected').data('delcost'));
                    console.log('product_delcost :>>', $('#type_of_inquiry option:selected').data('delcost'));
                }else{
                    $(".del-checks").hide();
                    $("#product_isdel").val('no');
                    $("#product_delcost").val('');
                }
            }
        });

        $(".userinquiry_dd").on('change', function(e){
            //console.log()
            $("#type_of_inquiry").empty();
            $(".del-checks").hide();
            var userId = (e.target.value).split("-")[0];
            $.ajax({
                url: '{{ route("client.customerdata") }}',
                type:"POST",
                data: {
                   "type": "checkData",
                   "id": userId,
                   "_token": "{{ csrf_token() }}",
                },
                success:function (response) {
                    $("#paying_customer").empty();
                    
                    if (response.status == 1) {
                        var firstval = null;
                        $.each(response.data, function(key, value) {
                           
                            var option = "<option value='"+ value.id +"' >" + value.name + " </option>";

                            $("#paying_customer").append(option);
                        });
                        if (oldArr['paying_customer'] != null) {
                            $("#paying_customer").val(oldArr['paying_customer']).trigger("change");
                        }
                    }
                }
            });
            var status='{{ $invn->status }}';
            /* if(status=='Open'){
                setncheckClientCredit(userId,'client_credit','getprintcredit');
            } */

        });
        $('.paying_customer_dd').on('change', function(e){
            var firstval =  $('#paying_customer option:selected').val();
            let userid = document.getElementById('userinquiry').value;
            let isAdmin = "{{ (isAdmin() || isSM()) ? 'true' : 'false' }}";
            userid = (isAdmin == 'true') ? userid.split("-")[0] : document.getElementById('user_id').value;
            if(firstval==userid){
                                Swal.fire({
                            title: "",
                            text: '{{trans("form.clientcustomer.you_have_selected_same_person")}}',
                            type: 'question',
                            }).then((result) => {
                                        
                                    });
              }
            $.ajax({
                            url: "/clients/" + firstval + "/getProducts",
                            type:"get",
                            success:function (response) {
                                $("#type_of_inquiry").empty();
                                if (response.status == 'success') {
                                    var firstval2 = null;
                                    $(".del-checks").hide();
                                    $.each(response.data, function(key, value) {
                                        var isdel = value.product.is_delivery == '1' ? 'yes' : 'no';
                                        var delcost = value.product.delivery_cost;
                                        var spousecost = value.product.spouse_cost;
                                        var intypeinq='{{$invn->type_of_inquiry}}';
                                        var paying_customerid='{{$invn->paying_customerid}}';
                                        var paying_customerid='{{$invn->paying_customerid}}';
                                        //console.log('paying_customerid',paying_customerid);
                                        //console.log('firstval',firstval);
                                        var optsel='';
                                        if(value.product.id==intypeinq && paying_customerid==firstval)
                                        
                                        {optsel='selected';
                                            $('#inv_cost').val(value.price);
                                            $('.arr_contacts_invCost').val(value.price);
                                            $("#product_spousecost").val(spousecost);
                                            
                                            if(isdel == 'yes'){
                                                $(".del-checks").show();
                                                $("#product_isdel").val('yes');
                                                $("#product_delcost").val(delcost);
                                                
                                            }else{
                                                $(".del-checks").hide();
                                                $("#product_isdel").val('no');
                                                $("#product_delcost").val('');
                                            }
                                        }
                                        var option = "<option "+optsel+" data-price='"+ value.price +"' value='"+ value.product.id +"' data-spousecost='" + spousecost + "' data-delcost='" + delcost + "' data-isdelivery='" + isdel + "'>" + value.product.name + " </option>";

                                        $("#type_of_inquiry").append(option);
                                        firstval2 = firstval2 !== null ? firstval2 : value.product.id;
                                    });
                                    if($('#type_of_inquiry option:selected').data('isdelivery') == 'yes'){
                                    $(".del-checks").show();
                                    $("#product_isdel").val('yes');
                                    $("#product_delcost").val($('#type_of_inquiry option:selected').data('delcost'));
                                    
                                    }else{
                                        $(".del-checks").hide();
                                        $("#product_isdel").val('no');
                                        $("#product_delcost").val('');
                                    }

                                    //$("#type_of_inquiry").val(firstval).trigger('change');
                                }
                            }
                             });
        });

        if (oldArr['user_inquiry'] != null) {
            $(".userinquiry_dd").val(oldArr['user_inquiry']).trigger("change");
        }

        if (oldArr['type_of_inquiry'] != null) {
            $("#type_of_inquiry").val(oldArr['type_of_inquiry']).trigger("change");
        }
        if (oldArr['paying_customer'] != null) {
            $("#paying_customer").val(oldArr['paying_customer']).trigger("change");
        }


        $("#company_del").on('change', function() {
            if(this.checked){
                $(".company-del-checks").show();
            }else{
                $(".company-del-checks").hide();
            }
        });

        // for custom validation before form submit to check all multiple fields has entries
        $('#investigation-form').on('submit', function (e) {
            e.preventDefault(); // prevent the form submit

            var valid = customFormValidation();

            if(valid == true){
                let isIdNull = false;
                let isSubmit = true;
                $('.arr_contacts_id').each(function (index, object){
                    if(index!=0){
                        let ele = $('#subjects_id_'+index);
                        if(ele.val()==''){
                            isIdNull=true;
                        } else {
                            let val = $('#subjects_id_'+index).val();
                            let code = calcCode(val);
                            let lastDigit = val.substr(8,9);
                            let errorEle = $('#id-error-'+index);
                            if(lastDigit != code){
                                errorEle.removeClass('d-none');
                                errorEle.css('display', 'block');
                                errorEle.html('{{trans('form.registration.investigation.id_error')}}');
                                isSubmit = false;    
                            } else {
                                errorEle.addClass('d-none');
                                errorEle.html('');
                            }
                        }
                    }
                });
                $('.arr_contacts_spouses').each(function (index, object){
                    if(index!=0){
                        let ele = $('#subjects_spouses_id_'+index);
                        // if(ele.val()==''){
                        //     isIdNull=true;
                        // } else {
                            let val = $('#subjects_spouses_id_'+index).val();
                            let code = calcCode(val);
                            let lastDigit = val.substr(8,9);
                            let errorEle = $('#id-spouses-error-'+index);
                            if(lastDigit != code && ele.val()!=''){
                                errorEle.removeClass('d-none');
                                errorEle.css('display', 'block');
                                errorEle.html('{{trans('form.registration.investigation.id_spouses_error')}}');
                                isSubmit = false;    
                            } else {
                                errorEle.addClass('d-none');
                                errorEle.html('');
                            }
                        //}
                    }
                });
                $('.arr_contacts_passport').each(function (index, object){
                    if(index!=0){
                        let ele = $('#subjects_passport_id_'+index);
                        // if(ele.val()==''){
                        //     isIdNull=true;
                        // } else {
                            let val = $('#subjects_passport_id_'+index).val();
                            let code = calcCode(val);
                            let lastDigit = val.substr(8,9);
                            let errorEle = $('#id-passport-error-'+index);
                            if(lastDigit != code && ele.val()!=''){
                                errorEle.removeClass('d-none');
                                errorEle.css('display', 'block');
                                errorEle.html('{{trans('form.registration.investigation.id_passport_error')}}');
                                isSubmit = false;    
                            } else {
                                errorEle.addClass('d-none');
                                errorEle.html('');
                            }
                        //}
                    }
                });

                if(isSubmit){
                    if(isIdNull){
                        Swal.fire({
                            title: "",
                            text: '{{trans("form.clientcustomer.you_have_fill_id")}}',
                            type: 'question',
                            confirmButtonText: "{{ trans('general.ok') }}",
                            cancelButtonText: "{{trans('general.cancel')}}"
                        }).then((result) => {
                            if(result.value){
                                $('.create-btn').text("{{trans('general.processing')}}" + '...');
                                var url = '{{ route('investigation.store') }}';

                                // create the FormData object from the form context (this),
                                // that will be present, since it is a form event
                                var formData = new FormData(this);

                                $.ajax({
                                    url: url,
                                    type: 'POST',
                                    data: formData,
                                    success: function (response) {
                                        if (response.status == 'success') {
                                            Swal.fire({
                                                title: "{{trans('general.created_text')}}",
                                                text: (response.message) ? response.message : "{{trans('form.registration.investigation.new_investigation_added')}}", 
                                                type: "success",
                                                confirmButtonText: "{{ trans('general.ok') }}",
                                            })
                                            .then((result) => {
                                                if(result.value){
                                                    @if(isClient())
                                                        window.location.href = "/investigations/"+response.data.id+"#documents";
                                                    @else 
                                                        window.location.href = "{{ route('investigations')}}";
                                                    @endif
                                                    //window.location.href = "{{ route('investigations')}}";
                                                }
                                            });
                                        } else {
                                            Swal.fire("{{trans('general.error_text')}}",(response.message) ? response.message : "{{trans('general.something_wrong')}}", "error");
                                            console.log(response);
                                            $('.create-btn').text("{{trans('general.create')}}");
                                        }
                                    },
                                    error: function (response) {
                                        Swal.fire("{{trans('general.error_text')}}",(response.message) ? response.message : "{{trans('general.something_wrong')}}", "error");
                                        $('.create-btn').text("{{trans('general.create')}}");
                                    },
                                    contentType: false,
                                    processData: false,
                                });
                            }
                        });
                    } else {
                        $('.create-btn').text("{{trans('general.processing')}}" + '...');
                        var url = '{{ route('investigation.store') }}';

                        // create the FormData object from the form context (this),
                        // that will be present, since it is a form event
                        var formData = new FormData(this);

                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: formData,
                            success: function (response) {
                                if (response.status == 'success') {
                                    Swal.fire({
                                        title: "{{trans('general.created_text')}}",
                                        text: (response.message) ? response.message : "{{trans('form.registration.investigation.new_investigation_added')}}", 
                                        type: "success",
                                        confirmButtonText: "{{ trans('general.ok') }}",
                                    })
                                    .then((result) => {
                                        if(result.value){
                                            window.location.href = "{{ route('investigations')}}";
                                        }
                                    });
                                } else {
                                    Swal.fire("{{trans('general.error_text')}}",(response.message) ? response.message : "{{trans('general.something_wrong')}}", "error");
                                    console.log(response);
                                    $('.create-btn').text("{{trans('general.create')}}");
                                }
                            },
                            error: function (response) {
                                Swal.fire("{{trans('general.error_text')}}",(response.message) ? response.message : "{{trans('general.something_wrong')}}", "error");
                                $('.create-btn').text("{{trans('general.create')}}");
                            },
                            contentType: false,
                            processData: false,
                        });
                    }
                }
            }
        });

        loadSteps('step1', 'step1');

    });

    function checkCurrentcredit(){
        var x = document.getElementsByClassName("arr_contacts_invCost");
            var i;
            var totcurinv=0;
            for (i = 0; i < x.length; i++) {
                if(isNaN(x[i].value)){
                continue;
                 }
            totcurinv += Number(x[i].value);
            }
            var userid=document.getElementById('userinquiry').value;
            var isAdmin = "{{ (isAdmin() || isSM()) ? 'true' : 'false' }}";
            userid= isAdmin == 'true' ? userid.split("-")[0] : document.getElementById('user_id').value;
            
          setncheckClientCredit(userid,'credit_error','checkcurrentcredit',totcurinv);
    }

    // function for set and check the client available credit
    function setncheckClientCredit(uid, outid, type, curcredit) {
        var ret = false;

        $.ajax({
            url: "/clients/" + uid + "/getCredit",
            type: "get",
            data: { "invid": '{{ $invn->id }}' },
            success: function (response) {
                $("#" + outid).empty();
                $(':input[type="submit"]').prop('disabled', false);
                if (response.status == 'success') {
                    if (type == 'getprintcredit') {
                        $("#" + outid).append("<span class='badge dt-badge badge-success'>{{ trans('form.registration.investigation.available_credit') }}</span> <span class='badge dt-badge badge-primary'>{{ trans('general.money_symbol') }}" + response.credit + "</span>");
                    }
                    if (type == 'checkcurrentcredit') {
                        var totalcredit = parseInt(response.credit) - parseInt(curcredit);
                        if (totalcredit < 0 || totalcredit == 0) {
                            $(':input[type="submit"]').prop('disabled', true);
                            $("#" + outid).append(" <h5><span class='badge dt-badge badge-danger'>{{ trans('general.credit_limit_message') }}</span></h5>");
                        }
                    }
                }
            }
        });

        return ret;
    }

    // function step 1 and step 2 show hide
    function loadSteps(step, backstep) {
        // console.log('step ', step);
        // console.log('backstep ', backstep);

        if (step == 'step1') {
            // $('#next_step1').show();
            // $('#next_step2').hide();
            // $('#next_step3').hide();

            if (backstep == 'step2') {
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

            $('#userinquiry').rules('add', {
                required: true,
            });
            $('#paying_customer').rules('add', {
                required: true,
            });
            $('#type_of_inquiry').rules('add', {
                required: true,
            });

            // $('#smartwizard').smartWizard("goToStep", 0);
            $('#smartwizard').smartWizard("reset");

        } else if (step == 'step2') {
            if ((backstep == 'step1' && $("#investigation-form").valid() === true)) {
                // $('#next_step2').show();
                // $('#next_step1').hide();
                // $('#next_step3').hide();

               /*  if (backstep == 'step3') {
                    $('.multiple_input_required_s3').each(function () {
                        // console.log('removing rule of :>>', $(this));
                        $(this).rules('remove', 'required');
                        $(this).removeAttr('required');
                    });
                } */

                $('.multiple_input_required_s2').each(function (e) {
                     //console.log('adding rule of :>>', $(this));
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

                $('.input_required_s2').each(function (e) {
                     //console.log('adding rule of :>>', $(this));
                    $(this).rules('add', {
                        required: true
                    });
                    $(this).attr("required", true);
                });

                $('#smartwizard').smartWizard("goToStep", 1);
            }

        } else if (step == 'step3') {
            console.log($("#investigation-form").valid());

            if ($("#investigation-form").valid() === true) {

                if ($(".contact-base-row").length < 1) {
                    $("#contacts_footer").html('<span class="error">{{ trans('general.minimum_entry') }}</span>');

                    return false;
                } else {
                    $("#contacts_footer").hide('');
                }

                // if ($(".address-base-row").length < 1) {
                //     $("#address_footer").html('<span class="error">Please add minimum one Entry!</span>');
                //
                //     return false;
                // } else {
                //     $("#address_footer").hide('');
                // }

                $('.multiple_input_required_s3').each(function (e) {
                    // console.log('adding rule of :>>', $(this));
                    $(this).rules('add', {
                        required: true
                    });
                    $(this).attr("required", true);
                });

                // $('#next_step3').show();
                // $('#next_step2').hide();
                {{--var status='{{ $invn->status }}';--}}
                {{--if(status=='Open'){--}}
                {{--    checkCurrentcredit();--}}
                {{--}--}}
                
                $('#smartwizard').smartWizard("goToStep", 2);
            }
        }
    }

    // Functions for multiple addresses/contacts fields
    function customFormValidation()
    {
        var isValid = false;
        let statusId = true;
        var cnt = 0;
        var basicValidation = $("#investigation-form").valid();
        if ($(".contact-base-row").length < 1) {
                    $("#contacts_footer").html('<span class="error">{{ trans('general.minimum_entry') }}</span>');

                    return false;
                } 
                else {
                    $("#contacts_footer").hide('');
                }

        if (basicValidation) {
            cnt++;
        }

        $('.arr_contacts_id').each(function (index, object){
            let ele = $('#subjects_id_'+index);
            
            let allow = $(object).attr('data-allow');
            
            if(allow){
                if(allow == 'no'){
                    let errorEle = $('#id-error-'+(index+1));
                    console.log(errorEle,'here');
                    errorEle.css('display', 'block');
                    errorEle.html('{{trans('form.registration.investigation.id_error')}}')
                    // statusId = false;
                }
            }
            
        });

        $('.arr_contacts_spouses').each(function (index, object){
            let ele = $('#subjects_spouses_id_'+index);
            
            let allow = $(object).attr('data-allow');
            
            if(allow){
                if(allow == 'no'){
                    let errorEle = $('#id-spouses-error-'+(index+1));
                    console.log(errorEle,'here');
                    errorEle.css('display', 'block');
                    errorEle.html('{{trans('form.registration.investigation.id_spouses_error')}}')
                    // statusId = false;
                }
            }
            
        });
        $('.arr_contacts_spouses').each(function (index, object){
            let ele = $('#subjects_spouses_id_'+index);
            
            let allow = $(object).attr('data-allow');
            
            if(allow){
                if(allow == 'no'){
                    let errorEle = $('#id-spouses-error-'+(index+1));
                    console.log(errorEle,'here');
                    errorEle.css('display', 'block');
                    errorEle.html('{{trans('form.registration.investigation.id_passport_error')}}')
                    // statusId = false;
                }
            }
            
        });

        $("form tbody:not('.addresses_tbl_tbody')").each(function (index) {
            if ($(this).find("tr").length > 0) {
                cnt++;
                if (cnt == 2) {
                    isValid = true;
                    return true;
                }
            } else {
                var footerEle = $(this).parent().find("tfoot tr td");
                footerEle.html('<span class="error">{{ trans('general.minimum_entry') }}</span>');
                footerEle.show();
            }
        });

        // if(!statusId){
        //     isValid=false;
        //     return false;
        // }

        return isValid;
    }

    function addNewAddress(obj)
    {
        $("#address_footer").hide();
        var lastNo, originalId;
        var arrRowNo = [];

        var subNo = obj.id;

        var baseTbl = $('#address-accordion-' + subNo);
        originalId = baseTbl.find('.address_block:last').attr('id');
        var baseRow = 'address-base-row-' + subNo;

        if (originalId){
            arrRowNo = originalId.split("-");
        }

        if (arrRowNo.length > 1) {
            lastNo = arrRowNo[2];
        } else {
            lastNo = 0;
        }

        let subType = $("select[name='subjects[" + parseInt(subNo) + "][sub_type]']").val();
        console.log("subjectstype ", subType);
        console.log("lastNo ", lastNo);
        if(subType == 'Main' && parseInt(lastNo) > 0){
            Swal.fire("{{trans('general.error_text')}}","{{trans('general.cannot_add_new_address')}}", "error");
            return;
        }

        var cloned = $("#address_clone_element").clone().appendTo('#address-accordion-' + subNo).wrap('<tr class="'+baseRow+'" style="display: block;"><td style="width: 100%;display: inline-table;"></td></tr>');

        var newId = 'address_collapse-' + subNo + '-' + (parseInt(lastNo) + 1);

        cloned.show();
        cloned.attr('id', 'address_clone_element-' + subNo + '-' + (parseInt(lastNo) + 1));

        cloned.find(".address_block").attr('id', newId);
        cloned.find(".address_header").attr('href', '#' + newId);
        cloned.find(".address_row_no").html($('.'+baseRow).length);
        cloned.find(".delete-address-btn").attr('id', parseInt(subNo));

        cloned.find(".arr_address_address1").attr('name', "subjects[" + parseInt(subNo) + "][address][" + (parseInt(lastNo) + 1) + "][address1]");
        cloned.find(".arr_address_address1").attr('id', "address_complete_" + parseInt(subNo) + '-' + (parseInt(lastNo) + 1));
        cloned.find(".arr_address_address2").attr('name', "subjects[" + parseInt(subNo) + "][address][" + (parseInt(lastNo) + 1) + "][address2]");
        cloned.find(".arr_address_address2").attr('id', "address2_" + parseInt(subNo) + '-' + (parseInt(lastNo) + 1));
        cloned.find(".arr_address_city").attr('name', "subjects[" + parseInt(subNo) + "][address][" + (parseInt(lastNo) + 1) + "][city]");
        cloned.find(".arr_address_city").attr('id', "city_" + parseInt(subNo) + '-' + (parseInt(lastNo) + 1));
        cloned.find(".arr_address_state").attr('name', "subjects[" + parseInt(subNo) + "][address][" + (parseInt(lastNo) + 1) + "][state]");
        cloned.find(".arr_address_state").attr('id', "state_" + parseInt(subNo) + '-' + (parseInt(lastNo) + 1));
        cloned.find(".arr_address_country").attr('name', "subjects[" + parseInt(subNo) + "][address][" + (parseInt(lastNo) + 1) + "][country_id]");
        cloned.find(".arr_address_country").attr('id', "country_" + parseInt(subNo) + '-' + (parseInt(lastNo) + 1));
        cloned.find(".arr_address_zip").attr('name', "subjects[" + parseInt(subNo) + "][address][" + (parseInt(lastNo) + 1) + "][zipcode]");
        cloned.find(".arr_address_zip").attr('id', "zipcode_" + parseInt(subNo) + '-' + (parseInt(lastNo) + 1));

        cloned.find(".arr_address_type").attr('name', "subjects[" + parseInt(subNo) + "][address][" + (parseInt(lastNo) + 1) + "][address_type]");
        cloned.find(".arr_address_type").attr('id', "arr_address_type_" + parseInt(subNo) + '-' + (parseInt(lastNo) + 1));
        cloned.find(".arr_add_other_text_type").attr('name', "subjects[" + parseInt(subNo) + "][address][" + (parseInt(lastNo) + 1) + "][other_text]");
        cloned.find(".arr_add_other_text_type").attr('id', "arr_address_type_" + parseInt(subNo) + '-' + (parseInt(lastNo) + 1) +"_otext");
        cloned.find(".arr_add_other_text_type").attr('data-id',  "arr_address_type_" + parseInt(subNo) + '-' + (parseInt(lastNo) + 1));
        cloned.find(".arr_add_other_text_div").attr('id', "arr_address_type_" + parseInt(subNo) + '-' + (parseInt(lastNo) + 1) +"_div");
        cloned.find(".arr_address_address1").focus();
        cloned.find(".address_row_no").attr('id', "arr_address_type_"  + parseInt(subNo) + '-' + (parseInt(lastNo) + 1) + "_title");
        cloned.find(".address_other_no").attr('id', "arr_address_type_"  +(parseInt(lastNo) + 1) + "_olabel");
        setAddressnew(parseInt(subNo), (parseInt(lastNo) + 1));
        changesubjecttitlebyid("arr_address_type_" + parseInt(subNo) + '-' + (parseInt(lastNo) + 1));
        cloned.find('input[type=text]').val('');
        cloned.find('.collapse').collapse('show');
    }

    function deleteAddress(ele)
    {
        var subNo = ele.id;
        $(ele).closest(".address-base-row-"+subNo).remove();

        $(".address-base-row-"+subNo).each(function (index) {
            //$(this).find(".address_row_no").html(index + 1);
        });

        if ($(".address-base-row-"+subNo).length < 1) {
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
        cloned.find(".addresses_tbl_tbody").attr('id', 'address-accordion-' + (parseInt(lastNo) + 1));
        cloned.find(".add-address-btn").attr('id', (parseInt(lastNo) + 1));

        cloned.find(".contact_header").attr('href', '#' + newId);
        cloned.find(".contact_row_no").html($(".contact-base-row").length);

        cloned.find(".arr_contacts_address_confirmed").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][address_confirmed]");
        cloned.find(".arr_contacts_address_confirmed").attr('id', "address_confirmed_" + (parseInt(lastNo) + 1));
        cloned.find(".arr_contacts_address_confirmed_lable").attr('for', "address_confirmed_" + (parseInt(lastNo) + 1));
       
        cloned.find(".arr_contacts_family").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][family_name]");
        cloned.find(".arr_contacts_firstname").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][first_name]");
        
        cloned.find(".arr_contacts_id").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][id_number]");
        cloned.find(".arr_contacts_id").attr('id', "subjects_id_" + (parseInt(lastNo) + 1));

        cloned.find(".arr_contacts_account_no").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][account_no]");
        cloned.find(".arr_contacts_workplace").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][workplace]");
        cloned.find(".arr_contacts_website").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][website]");
        cloned.find(".arr_contacts_fatherName").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][father_name]");
        cloned.find(".arr_contacts_motherName").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][mother_name]");
        cloned.find(".arr_contacts_spouseName").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][spouse_name]");
        
        cloned.find(".arr_contacts_spouses").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][spouse]");
        cloned.find(".arr_contacts_spouses").attr('data-index', (parseInt(lastNo) + 1 ));
        cloned.find(".arr_contacts_spouses").attr('id', "subjects_spouses_id_" + (parseInt(lastNo) + 1));
        cloned.find(".id_spouses_error").attr('id', "id-spouses-error-" + (parseInt(lastNo) + 1 ));
        
        cloned.find(".arr_contacts_carNum").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][car_number]");
        cloned.find(".arr_contacts_invCost").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][req_inv_cost]");
        cloned.find(".arr_contacts_accNum").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][bank_account_no]");
        
        cloned.find(".arr_contacts_passport").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][passport]");
        cloned.find(".arr_contacts_passport").attr('data-index', (parseInt(lastNo) + 1 ));
        cloned.find(".arr_contacts_passport").attr('id', "subjects_passport_id_" + (parseInt(lastNo) + 1));
        cloned.find(".id_passport_error").attr('id', "id-passport-error-" + (parseInt(lastNo) + 1 ));

        cloned.find(".arr_contacts_dob").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][dob]");
        cloned.find(".arr_contacts_additionalDetails").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][assistive_details]");
        cloned.find(".arr_contacts_subType").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][sub_type]");

        cloned.find(".id_error").attr('id', "id-error-" + (parseInt(lastNo) + 1 ));
        cloned.find(".arr_contacts_id").attr('data-index', (parseInt(lastNo) + 1 ));

        cloned.find(".arr_contacts_subType").attr('id', "arr_contacts_type_" + (parseInt(lastNo) + 1 ));
        cloned.find(".arr_contacts_other_text_type").attr('name', "subjects[" + (parseInt(lastNo) + 1)+ "][other_text]");
        cloned.find(".arr_contacts_other_text_type").attr('id', "arr_contacts_type_" + (parseInt(lastNo) + 1)+ "_otext");
        cloned.find(".arr_contacts_other_text_type").attr('data-id',  "arr_contacts_type_" + (parseInt(lastNo) + 1 ));
        cloned.find(".arr_contacts_other_text_div").attr('id', "arr_contacts_type_" + (parseInt(lastNo) + 1)+ "_div");
       
        cloned.find(".arr_contacts_mainemails").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][main_email]");
        cloned.find(".arr_contacts_alternateemails").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][alternate_email]");
        cloned.find(".arr_contacts_mainphones").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][main_phone]");
        cloned.find(".arr_contacts_secondaryphones").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][secondary_phone]");
        cloned.find(".arr_contacts_mainmobiles").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][main_mobile]");
        cloned.find(".arr_contacts_secondarymobiles").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][secondary_mobile]");
        cloned.find(".arr_contacts_fax").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][fax]");

        cloned.find(".contact_row_no").attr('id', "arr_contacts_type_" + (parseInt(lastNo) + 1)+ "_title");

        cloned.find('input[type=text]').val('');
        cloned.find('.collapse').collapse('show');

        cloned.find("input[name='subjects[" + (parseInt(lastNo) + 1) + "][req_inv_cost]']").val($('#type_of_inquiry option:selected').data('price'));
        cloned.find(".arr_contacts_family").focus();
        addinputmask('contact');
        changesubjecttitlebyid("arr_contacts_type_"+(parseInt(lastNo) + 1 ));

    }

    function deleteContact(ele) {
        $(ele).closest(".contact-base-row").remove();

        $(".contact-base-row").each(function (index) {
            //$(this).find(".contact_row_no").html(index + 1);
        });

        if ($(".contact-base-row").length < 1) {
            $("#contacts_footer").html('<span>No record added</span>');
            $("#contacts_footer").show();
        }
    }

    function deleteRows(ele) {
        $(ele).closest('tr').remove();

        if ($(ele).parent('tbody').find("tr").length < 1) {
            var footerEle = $(ele).parent().find("tfoot tr td");
            $(footerEle).html('<span>No record added</span>');
            $(footerEle).show();
        }
    }

    // This Function for Address Type Dropdown Othertext to open textbox
    function changeothertextbox(t)
    {
        var id = t.id;
        if (t.value == 'Other' || t.value=='Contact') {
            $("#" + id + "_div").removeClass('d-none');
            //$("#"+id+"_otext").prop('required',true);
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
            });
            $("#"+id+"_otext").rules('add', {required: true });
           $("#"+id+"_otext").attr("required", true);
        } else {
            $("#" + id + "_div").addClass('d-none');
            //$("#"+id+"_otext").prop('required',false);
            $("#"+id+"_otext").rules('remove', 'required');
            $("#"+id+"_otext").removeAttr('required');
        }
    }

    // This Function for Address Type Dropdown Othertext to open textbox
    function changeotheroldtextbox(t)
    {
        var id = t.id;

        if (t.value == 'Other' || t.value=='Contact') {
            $("#" + id + "_div").addClass('d-block');
            $("#" + id + "_div").removeClass('d-none');
            //$("#"+id+"_otext").prop('required',true);
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
            });
            $("#"+id+"_otext").rules('add', {required: true });
           $("#"+id+"_otext").attr("required", true);
        } else {
            $("#" + id + "_div").addClass('d-none');
            $("#" + id + "_div").removeClass('d-block');
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
        $("#"+id+"_title").html(e.value);
    }

     // set the subject ID
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
        let index = $(this).data('index');
        let allDigit = val;
        let lastDigit = val.substr(8,9);
        val = val.substr(0,8); // verify we look only at the first 8 digits
        var code = calcCode(val);
        
        if(lastDigit == code){
            if($(this).hasClass('arr_contacts_spouses')){
                $('#id-spouses-error-'+index).addClass('d-none');
                $(this).attr('data-allow', 'yes');
            } else if($(this).hasClass('arr_contacts_passport')){
                $('#id-passport-error-'+index).addClass('d-none');
                $(this).attr('data-allow', 'yes');
            } else {
                $('#id-error-'+index).addClass('d-none');
                $(this).attr('data-allow', 'yes');
            }
        } else {
            if($(this).hasClass('arr_contacts_spouses')){
                console.log('#id-spouses-error-'+index, 'idddd');
                $(this).attr('data-allow', 'no');
                $('#id-spouses-error-'+index).removeClass('d-none');
            } else if($(this).hasClass('arr_contacts_passport')){
                $(this).attr('data-allow', 'no');
                $('#id-passport-error-'+index).removeClass('d-none');
            } else {
                $(this).attr('data-allow', 'no');
                $('#id-error-'+index).removeClass('d-none');
            }
        }
        
        // this.value = val;
        e.preventDefault(); // don't let the browser add the pressed key, because we alread did
    }

    //this for add input mask to phone,mobile,fax
    function addinputmask(type,id){
        if(type=='contact'){
            $('.arr_contacts_mainphones').inputmask('999-999-9999', {
                    autoUnmask: true,
                    removeMaskOnSubmit:true
                });
                $('.arr_contacts_secondaryphones').inputmask('999-999-9999', {
                    autoUnmask: true,
                    removeMaskOnSubmit:true
                });
                $('.arr_contacts_mainmobiles').inputmask('999-999-9999', {
                    autoUnmask: true,
                    removeMaskOnSubmit:true
                });
                $('.arr_contacts_secondarymobiles').inputmask('999-999-9999', {
                    autoUnmask: true,
                    removeMaskOnSubmit:true
                });
            $('.arr_contacts_fax').inputmask('999-999-9999', {
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

</script>

<script type="text/javascript">

    // this code for set  google api autocomplete address dynamically add
    function setAddressnew(subNo, count)
    {
        var autocompletes = [];
        var options = {
            types: ['geocode'],
            language: '{{ App::isLocale('hr') ? 'iw' : 'en' }}',
        };

        var input = document.getElementById('address_complete_' + subNo + '-' +count);
        var autocomplete = new google.maps.places.Autocomplete(input, options);
        autocomplete.inputId = input.id;
        autocomplete.subNo = subNo;
        autocomplete.count = count;
        autocomplete.addListener('place_changed', fillIn);
        autocompletes.push(autocomplete);
    }

    // find the country id fron given json
    function getcountryid(cntr, value)
    {
        for (var i = 0; i < cntr.length; i++) {
            if (cntr[i].code == value) {
                return cntr[i].id;
            }
        }
        return 0;
    }

    function fillIn()
    {
        const componentForm = {
            street_number: "long_name",
            route: "long_name",
            locality: "long_name",
            administrative_area_level_1: "long_name",
            country: "short_name",
            postal_code: "short_name",
        };

        var cntr =@json($countries);
        var place = this.getPlace();

        document.getElementById('address2_' + this.subNo + '-' + this.count).value = "";
        document.getElementById('state_' + this.subNo + '-' +this.count).value = "";
        document.getElementById('city_' + this.subNo + '-' +this.count).value = "";
        document.getElementById('zipcode_' + this.subNo + '-' +this.count).value = "";

        for (const component of place.address_components) {
            const addressType = component.types[0];
            if (componentForm[addressType]) {
                const val = component[componentForm[addressType]];
                if (addressType === "country") {
                    var countryid = getcountryid(cntr, val);
                    if (countryid != 0) {
                        document.getElementById('country_' + this.subNo + '-' + this.count).value = countryid;
                    }
                }
                if (addressType === "street_number") {
                    if (val) {
                        document.getElementById('address2_' + this.subNo + '-' + this.count).value += val + ' ';
                    }
                }
                if (addressType === "route") {
                    if (val)
                        document.getElementById('address2_' + this.subNo + '-' + this.count).value += val;
                }
                if (addressType === "administrative_area_level_1") {
                    document.getElementById('state_' + this.subNo + '-' + this.count).value = val;
                }
                if (addressType === "locality") {
                    document.getElementById('city_' + this.subNo + '-' + this.count).value = val;
                }
                if (addressType === "postal_code") {
                    document.getElementById('zipcode_' + this.subNo + '-' + this.count).value = val;
                }
            }
        }

    }

    // This Function for Address Type Dropdown Othertext to open textbox
    function setoldadderess(i, j){
        setAddressnew(i, j);
    }

</script>

<script type="text/javascript">
    window.onload = function() {
        addinputmask('arr_contacts_mainphones');
        addinputmask('arr_contacts_secondaryphones');
        addinputmask('arr_contacts_mainmobiles');
        addinputmask('arr_contacts_secondarymobiles');
        addinputmask('arr_contacts_fax');
       
        <?php
            for ($i = 0; $i < count($invn->subjects); $i++) {
                for ($j = 0; $j < count($invn->subjects[$i]->subject_addresses); $j++) {
        ?>
                    setoldadderess({{$i+1}}, {{$j+1}});
        <?php
                }
            }
        ?>
    }
</script>

@endsection