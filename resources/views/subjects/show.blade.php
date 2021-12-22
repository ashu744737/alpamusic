@extends('layouts.master')

@section('title') {{ trans('form.registration.investigation.subject_details') }} @endsection

@section('headerCss')
@endsection

@section('content')

    <div class="col-sm-6">
        <div class="page-title-box">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{route('subjects')}}">{{ trans('form.registration.investigation.subjects') }}</a></li>
                <li class="breadcrumb-item">
                    <a href="javascript: void(0);">
                    @if(in_array($subjects->sub_type, array('Main', 'Spouse', 'Company')))
                        @if(config('app.locale') == 'hr')
                        {{ !is_null(\App\SubjectTypes::where('name', $subjects->sub_type)->first())?\App\SubjectTypes::where('name', $subjects->sub_type)->first()->hr_name:(!is_null($subjects->sub_type)?$subjects->sub_type:'') }}
                        @else
                        {{ !is_null(\App\SubjectTypes::where('name', $subjects->sub_type)->first())?\App\SubjectTypes::where('name', $subjects->sub_type)->first()->name:(!is_null($subjects->sub_type)?$subjects->sub_type:'') }}
                        @endif
                    @else
                        {{ $subjects->sub_type }}
                    @endif
                    </a>
                </li>
            </ol>
        </div>
    </div>
    
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
                            </ul>
    
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <!-- BASIC DETAILS -->
                                <div class="tab-pane active py-3" id="basic_details" role="tabpanel">

                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.family') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $subjects->family_name ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.firstname') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $subjects->first_name ?? '-' }}
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.investigation_req') }}</label>
                                                <div class="col-form-label col-8">
                                                @if(!is_null($subjects->investigation))
                                                    {{ $subjects->investigation->first()->work_order_number ?? '-' }}({{$subjects->investigation->first()->user_inquiry}})
                                                @else
                                                    -
                                                @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.sub_type') }}</label>
                                                <div class="col-form-label col-8">
                                                    @if(in_array($subjects->sub_type, array('Main', 'Spouse', 'Company')))
                                                        @if(config('app.locale') == 'hr')
                                                        {{ !is_null(\App\SubjectTypes::where('name', $subjects->sub_type)->first())?\App\SubjectTypes::where('name', $subjects->sub_type)->first()->hr_name:(!is_null($subjects->sub_type)?$subjects->sub_type:'') }}
                                                        @else
                                                        {{ !is_null(\App\SubjectTypes::where('name', $subjects->sub_type)->first())?\App\SubjectTypes::where('name', $subjects->sub_type)->first()->name:(!is_null($subjects->sub_type)?$subjects->sub_type:'') }}
                                                        @endif
                                                    @else
                                                        {{ $subjects->sub_type }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.id') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $subjects->id_number ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.account_no') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $subjects->account_no ?? '-' }}
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.bank_ac_no') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $subjects->bank_account_no ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.workplace') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $subjects->workplace ?? '-' }}
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.website') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $subjects->website ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.father_name') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $subjects->father_name ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.mothername') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $subjects->mother_name ?? '-' }}
                                                </div>
                                            </div>
                                        </div>  
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.spousename') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $subjects->spouse_name ?? '-' }}
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.required_cost_invs') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ trans('general.money_symbol')}}{{ $subjects->req_inv_cost ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.spouse') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $subjects->spouse ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.carnumber') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $subjects->car_number ?? '-' }}
                                                </div>
                                            </div>
                                        </div>  
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.passport') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $subjects->passport ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigator.date_of_birth') }}</label>
                                                <div class="col-form-label col-8">
                                                @if(!is_null($subjects->dob))
                                                    {{ \Carbon\Carbon::parse($subjects->dob)->format('d/m/y') ?? '-' }}
                                                @else
                                                    -
                                                @endif
                                                </div>
                                            </div>
                                        </div>
                                         
                                    </div>
                                    <hr />
                                    <h4 class="section-title mb-3 pb-2">{{ trans('form.registration.investigation.contact_details') }}</h4>
                                 
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.main_email') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $subjects->main_email ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.alternate_email') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $subjects->alternate_email ?? '-' }}
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.main_phone') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $subjects->main_phone ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.secondary_phone') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $subjects->secondary_phone ?? '-' }}
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.main_mobile') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $subjects->main_mobile ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.secondary_mobile') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $subjects->secondary_mobile ?? '-' }}
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.fax') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $subjects->fax ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                       
                                    <hr />
                                    <h4 class="section-title mb-3 pb-2">{{ trans('form.registration.investigation.address_details') }}</h4>
                                    <div class="row">
                                        @if(count($subjects->subject_addresses)>0)
                                        @php $i = 1; @endphp
                                        @foreach($subjects->subject_addresses as $useraddress)
                                        <div class="col-lg-3">
                                            <div class="card">
                                                <div class="card-header">
                                                    <i class="fas fa-map"></i>   {{ trans('form.registration.client.address') }} {{$i}}      
                                                    {{-- <button type="button" class="float-right btn btn-primary waves-effect waves-light" data-toggle="modal" onclick="showModal('address_edit1')"><i class="fas fa-edit"></i> Edit </button> --}}
                                                </div>
                                                <div class="card-body">
                                                    <blockquote class="card-blockquote mb-0">
                                                              <p>{{ $useraddress->address2 ?? '-' }}<br>{{ $useraddress->city.',' ?? '-' }}{{ $useraddress->state.',' ?? '-' }}{{ App::isLocale('hr')?$useraddress->country->hr_name ?? '-':$useraddress->country->en_name ?? '-' }} {{ '-'.$useraddress->zipcode ?? '-' }}
                                                            
                                                            </p> 
                                                            @if ($useraddress->address_type!='')<p><span class="badge badge-primary text-right">
                                                            @if(config('app.locale') == 'hr')
                                                                {{ !is_null(\App\ContactTypes::where('type_name', $useraddress->address_type)->first())?\App\ContactTypes::where('type_name', $useraddress->address_type)->first()->hr_type_name:(!is_null($useraddress->address_type)?$useraddress->address_type:'') }}
                                                                @else
                                                                {{ !is_null(\App\ContactTypes::where('type_name', $useraddress->address_type)->first())?\App\ContactTypes::where('type_name', $useraddress->address_type)->first()->type_name:(!is_null($useraddress->address_type)?$useraddress->address_type:'') }}
                                                                @endif</span></p>@endif
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 
                                                    </blockquote>
                                                </div>
                                                <div class="card-footer text-muted">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        @php $i++; @endphp
                                        @endforeach
                                        @else
                                        <div class="col-lg-3">
                                            <div class="card">
                                                <div class="card-header">
                                               {{ trans('form.registration.deliveryboy.no_record_added') }}
                                                </div>
                                            </div>
                                        </div>
                                        @endif                                       
                                        
                                        
                                    </div>
                                  
                                    <hr />
                                    
                                    <h4 class="section-title mb-3 pb-2">{{ trans('form.registration.investigation.documents') }}</h4>
                                    <div class="row">
                                    <div class="col-lg-12 resp-order">
                                    @if(count($subjects->documents)>0)
                                        @php $i = 1; @endphp
                                        <div class="table-rep-plugin">
                                            <div class="table-responsive mb-0" data-pattern="priority-columns">
                                                <table id="inve_documents" class="table table-bordered">
                                                    <thead>
                                                    <th>{{ trans('general.sr_no') }}</th>
                                                    <th>{{ trans('form.registration.investigation.documents') }}</th>
                                                    <th>{{ trans('form.registration.investigation.comment') }}</th>
                                                    <th>{{ trans('form.registration.investigation.date') }}</th>
                                                    <th>{{ trans('form.registration.investigation.is_delivered') }}</th>
                                                    <th>{{ trans('general.action') }}</th>
                                                    </thead>
                                                    <tbody>
                                                    @if(!$subjects->documents->isEmpty())
                                                        @php $indx = 1; $count = 0; @endphp
                                                        @foreach($subjects->documents as $document)
                                                            <tr>
                                                                @php $isimage=0;
                                                                $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp'];
                                                                if(in_array($document->file_extension, $imageExtensions)){
                                                                    $isimage=1;
                                                                }
                                                                $imgurl='/investigation-documents/'.$document->file_name;
                                                                $count++;
                                                                @endphp
                                                                <td>{{$indx}}</td>
                                                                <td><a href="javascript:void(0);">{{ $document->file_name }}</a></td>
                                                                <td>{{ $document->comment }}</td>
                                                                <td>@if(!is_null($document->attach_date))
                                                                    {{ \Carbon\Carbon::parse($document->attach_date)->format('d/m/y') ?? '-' }}
                                                                @else
                                                                    -
                                                                @endif</td>
                                                                <td><span class="badge dt-badge badge-{{$document->is_delivered?'success':'danger'}}">{{$document->is_delivered?trans('general.yes'):trans('general.no')}}</span></td>
                                                                <td>
                                                                    <div class="action_btns">
                                                                        @if($isimage==1)
                                                                            <a class="view image-popup-no-margins" href="{{URL::asset($imgurl)}}" target="_blank">
                                                                                <i class="fas fa-eye"></i>
                                                                                <img class="img-fluid d-none" alt="" src="{{URL::asset($imgurl)}}" width="75">
                                                                            </a>
                                                                        @else
                                                                            <a class="view" href="{{URL::asset($imgurl)}}" target="_blank"><i class="fas fa-eye"></i>
                                                                            </a>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @php $indx++; @endphp
                                                        @endforeach
                                                    @endif
                                                    </tbody>
                                                    <tfoot>
                                                    <tr class="tr-nodoc" style="{{ !$subjects->documents->isEmpty() && $count > 0 ? 'display:none;' : '' }}">
                                                        <td colspan="6" class="text-center">{{ trans('form.registration.investigation.document_notfound') }}</td>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    @else
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                            {{ trans('form.registration.investigation.document_notfound') }}
                                            </div>
                                        </div>
                                    </div>
                                    @endif                                       
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