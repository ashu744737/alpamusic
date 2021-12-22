@extends('layouts.master')

@section('title') {{ trans('form.registration.client.client_details') }} @endsection

@section('headerCss')
@endsection

@section('content')

    <div class="col-sm-6">
        <div class="page-title-box">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{route('clients')}}">{{ trans('form.clients') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript: void(0);"> {{ $user->name ?? '' }} @if ($user->status)
                    @if ($user->status == 'approved')
                        <span class="badge dt-badge badge-success">{{trans('form.timeline_status.'.ucwords($user->status))}}</span>   
                    @elseif ($user->status == 'pending')
                        <span class="badge dt-badge badge-warning">{{trans('form.timeline_status.'.ucwords($user->status))}}</span> 
                    @elseif ($user->status == 'disabled') 
                            <span class="badge dt-badge badge-dark">{{trans('form.timeline_status.'.ucwords($user->status))}}</span> 
                    @endif   
                @endif</a></li>
            </ol>
        </div>
    </div>
    <!-- content -->
    <div class="row">
        <div class="col-12">
            <div class="card investogators_Details">
                <div class="card-body">

                    <div class="row">
                        <div class="col-12">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-2" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#basic_details" role="tab">
                                        <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                        <span class="d-none d-sm-block">{{ trans('form.registration.client.basic_details') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#addresses" role="tab">
                                        <span class="d-block d-sm-none"><i class="far fa-address-book"></i></span>
                                        <span class="d-none d-sm-block">{{ trans('form.registration.client.address') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#contacts" role="tab">
                                        <span class="d-block d-sm-none"><i class="fas fa-phone"></i></span>
                                        <span class="d-none d-sm-block">{{ trans('form.contacts') }}</span>
                                    </a>
                                </li>
                                
                            </ul>
    
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <!-- BASIC DETAILS -->
                                <div class="tab-pane active py-3" id="basic_details" role="tabpanel">
                                    <h4 class="section-title mb-3 pb-2">{{ trans('form.registration.client.personal_details') }}</h4>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4">{{ trans('form.registration.client.viewform.name') }}</label>
                                                <div class="col-8">
                                                    {{ $user->name ?? '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4">{{ trans('form.registration.client.viewform.email') }}</label>
                                                <div class="col-8">
                                                    {{ $user->email ?? '' }}
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4">{{ trans('form.registration.client.viewform.customer_type') }}</label>
                                                <div class="col-8">
                                                @if(config('app.locale') == 'hr')
                                                    @if($user->client->client_type->type_name)
                                                        {{ !is_null(\App\ClientTypes::where('type_name', $user->client->client_type->type_name)->first())?(\App\ClientTypes::where('type_name', $user->client->client_type->type_name)->first()->hr_type_name):'' }}
                                                    @endif
                                                @else
                                                    @if($user->client->client_type->type_name)
                                                        {{ !is_null(\App\ClientTypes::where('type_name', $user->client->client_type->type_name)->first())?(\App\ClientTypes::where('type_name', $user->client->client_type->type_name)->first()->type_name):'' }}
                                                    @endif
                                                @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4">{{ trans('form.registration.client.viewform.printable_name') }}</label>
                                                <div class="col-8">
                                                    {{ $user->client->printname ?? '' }}
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4">{{ trans('form.registration.client.viewform.legal_entity_id') }}</label>
                                                <div class="col-8">
                                                    {{ $user->client->legal_entity_no ?? '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4">{{ trans('form.registration.client.viewform.website') }}</label>
                                                <div class="col-8">
                                                    {{ $user->client->website ?? '' }}
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    {{--<hr />
                                    <h4 class="section-title mb-3 pb-2">{{ trans('form.registration.client.additional_details') }}</h4>
                                    <div class="row">
                                        
                
                                        <div class="col-lg-3">
                                            <div class="card">
                                                <div class="card-header">
                                                    <i class="fas fa-blender-phone"></i>   {{ trans('form.registration.client.phone') }}
                                                </div>
                                                <div class="card-body">
                                                    <blockquote class="card-blockquote mb-0">
                                                        @foreach($user->userPhones as $phone)
                                                        @if ($phone->type=='phone')
                                                        <p> {{ $phone->value ?? '' }} @if ($phone->phone_type!='')<span class="badge badge-primary">
                                                        @if(config('app.locale') == 'hr')
                                                        {{ !is_null(\App\ContactTypes::where('type_name', $phone->phone_type)->first())?(\App\ContactTypes::where('type_name', $phone->phone_type)->first()->hr_type_name):'' }}
                                                        @else
                                                        {{ !is_null(\App\ContactTypes::where('type_name', $phone->phone_type)->first())?(\App\ContactTypes::where('type_name', $phone->phone_type)->first()->type_name):'' }}
                                                        @endif</span>@endif
                                                        </p>   
                                                        @endif
                                                        @endforeach 
                                                    </blockquote>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="card">
                                                <div class="card-header">
                                                    <i class="fas fa-phone-volume"></i>  {{ trans('form.registration.client.mobile') }}
                                                </div>
                                                <div class="card-body">
                                                    <blockquote class="card-blockquote mb-0">
                                                        @foreach($user->userPhones as $mobile)
                                                        @if ($mobile->type=='mobile')
                                                        <p> {{ $mobile->value ?? '' }} @if ($mobile->phone_type!='')<span class="badge badge-primary">@if(config('app.locale') == 'hr')
                                                            {{ !is_null(\App\ContactTypes::where('type_name', $mobile->phone_type)->first())?(\App\ContactTypes::where('type_name', $mobile->phone_type)->first()->hr_type_name): '' }}
                                                            @else
                                                            {{ !is_null(\App\ContactTypes::where('type_name', $mobile->phone_type)->first())?(\App\ContactTypes::where('type_name', $mobile->phone_type)->first()->type_name): '' }}
                                                            @endif</span>@endif
                                                        </p>   
                                                        @endif
                                                        @endforeach  
                                                    </blockquote>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="card">
                                                <div class="card-header">
                                                    <i class="fas fa-mail-bulk"></i>  {{ trans('form.registration.client.email') }}
                                                </div>
                                                <div class="card-body">
                                                    <blockquote class="card-blockquote mb-0">
                                                        @foreach($user->userEmails as $email)
                                                        <p> {{ $email->value ?? '' }} @if ($email->email_type!='')<span class="badge badge-primary">@if(config('app.locale') == 'hr')
                                                            {{ !is_null(\App\ContactTypes::where('type_name', $email->email_type)->first())?(\App\ContactTypes::where('type_name', $email->email_type)->first()->hr_type_name):'' }}
                                                            @else
                                                            {{ !is_null(\App\ContactTypes::where('type_name', $email->email_type)->first())?(\App\ContactTypes::where('type_name', $email->email_type)->first()->type_name):'' }}
                                                            @endif</span>@endif
                                                        </p>  
                                                        @endforeach  
                                                    </blockquote>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="card">
                                                <div class="card-header">
                                                    <i class="fas fa-fax "></i> {{ trans('form.registration.client.fax') }}
                                                </div>
                                                <div class="card-body">
                                                    <blockquote class="card-blockquote mb-0">
                                                        @foreach($user->userPhones as $fax)
                                                        @if ($fax->type=='fax')
                                                        <p> {{ $fax->value ?? '' }} @if ($fax->phone_type!='')<span class="badge badge-primary">@if(config('app.locale') == 'hr')
                                                            {{ !is_null(\App\ContactTypes::where('type_name', $fax->phone_type)->first())?(\App\ContactTypes::where('type_name', $fax->phone_type)->first()->hr_type_name):'' }}
                                                            @else
                                                            {{ !is_null(\App\ContactTypes::where('type_name', $fax->phone_type)->first())?(\App\ContactTypes::where('type_name', $fax->phone_type)->first()->type_name):'' }}
                                                            @endif</span>@endif
                                                        </p>   
                                                        @endif
                                                        @endforeach    
                                                    </blockquote>
                                                </div>
                                            </div>
                                        </div>
                
                                        
                                    </div>--}}
                                    
                                </div>
    
                                <!-- DOCUMENTS -->
                                <div class="tab-pane p-3" id="addresses" role="tabpanel">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 resp-order">
                                            <div class="table-rep-plugin">
                                                <div class="table-responsive mb-0" data-pattern="priority-columns">
                                                    <table id="inve_documents" class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ trans('general.sr_no') }}</th>
                                                                <th>{{ trans('form.registration.client.address_helper') }}</th>
                                                                <th>{{ trans('form.registration.client.city') }}</th>
                                                                <th>{{ trans('form.registration.client.state') }}</th>
                                                                <th>{{ trans('form.registration.client.country') }}</th>
                                                                <th>{{ trans('form.registration.client.zip_code') }}</th>
                                                                <th>{{ trans('form.registration.client.type') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if(count($user->userAddresses)>0)
                                                            @php $i = 1; @endphp
                                                            @foreach($user->userAddresses as $useraddress)
                                                            <tr>
                                                                <td>{{$i}}</td>
                                                                <td>{{ $useraddress->address2 ?? '-' }}</td>
                                                                <td>{{ $useraddress->city ?? '-' }}</td>
                                                                <td>{{ $useraddress->state ?? '-' }}</td>
                                                                <td>{{ App::isLocale('hr')?$useraddress->country->hr_name ?? '-':$useraddress->country->en_name ?? '-' }} </td>
                                                                <td>{{ $useraddress->zipcode ?? '-' }}</td>
                                                                <td>@if(config('app.locale') == 'hr')
                                                                {{ !is_null(\App\ContactTypes::where('type_name', $useraddress->address_type)->first())?(\App\ContactTypes::where('type_name', $useraddress->address_type)->first()->hr_type_name):(!is_null($useraddress->address_type)?$useraddress->address_type:'') }}
                                                                @else
                                                                {{ !is_null(\App\ContactTypes::where('type_name', $useraddress->address_type)->first())?(\App\ContactTypes::where('type_name', $useraddress->address_type)->first()->type_name):(!is_null($useraddress->address_type)?$useraddress->address_type:'') }}
                                                                @endif</td>
                                                            </tr>
                                                            @php $i++; @endphp
                                                            @endforeach
                                                            @else
                                                            <tr>
                                                                <td class="text-center" colspan="7">
                                                                   {{ trans('form.registration.deliveryboy.no_record_added') }}
                                                                </td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>
    
                                <!-- CONTACTS -->
                                <div class="tab-pane p-3" id="contacts" role="tabpanel">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 resp-order">
                                            <div class="table-rep-plugin">
                                                <div class="table-responsive mb-0" data-pattern="priority-columns">
                                                    <table id="inve_documents" class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ trans('general.sr_no') }}</th>
                                                                <th>{{ trans('form.contact.name') }}</th>
                                                                <th>{{ trans('form.contact.contact_type') }}</th>
                                                                <th>{{ trans('form.contact.workplace') }}</th>
                                                                <th>{{ trans('form.contact.phone') }}</th>
                                                                <th>{{ trans('form.contact.mobile') }}</th>
                                                                <th>{{ trans('form.contact.fax') }}</th>
                                                                <th>{{ trans('form.contact.email') }}</th>
                                                                <th>{{ trans('form.registration.client.Primary Email') }}</th>
                                                                <th>{{ trans('form.registration.client.Secondary Email') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if(count($user->userContacts)>0)
                                                            @php $i = 1; @endphp
                                                            @foreach($user->userContacts as $usercontact)
                                                            <tr>
                                                                <td>{{$i}}</td>
                                                                <td>{{ !empty($usercontact->first_name) ? $usercontact->first_name.' '.$usercontact->last_name : '-' }}</td>
                                                                <td>@if(config('app.locale') == 'hr')
                                                                {{ !is_null(\App\ContactTypes::where('type_name', $usercontact->contact_type)->first())?(\App\ContactTypes::where('type_name', $usercontact->contact_type)->first()->hr_type_name):(!is_null($usercontact->contact_type)?$usercontact->contact_type:'') }}
                                                                @else
                                                                {{ !is_null(\App\ContactTypes::where('type_name', $usercontact->contact_type)->first())?(\App\ContactTypes::where('type_name', $usercontact->contact_type)->first()->type_name):(!is_null($usercontact->contact_type)?$usercontact->contact_type:'') }}
                                                                @endif</td>
                                                                <td>{{ $usercontact->workplace ?? '-' }}</td>
                                                                <td>{{ $usercontact->phone ?? '-' }}</td>
                                                                <td>{{ $usercontact->mobile ?? '-' }}</td>
                                                                <td>{{ $usercontact->fax ?? '-' }}</td>
                                                                <td>{{ $usercontact->email ?? '-' }}</td>
                                                                <td>{{ $usercontact->primary_email ?? '-' }}</td>
                                                                <td>{{ $usercontact->secondary_email ?? '-' }}</td>
                                                            </tr>
                                                            @php $i++; @endphp
                                                            @endforeach 
                                                            @else
                                                            <tr>
                                                                <td class="text-center" colspan="8">
                                                                   {{ trans('form.registration.deliveryboy.no_record_added') }}
                                                                </td>
                                                            </tr>
                                                            @endif 
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>
                            </div>
    
    
                        </div>
                    </div>
    
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->                            

@endsection

@section('footerScript')
   <script src="{{ URL::asset('/libs/select2/select2.min.js')}}"></script>
   <script src="{{ URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>  
@endsection