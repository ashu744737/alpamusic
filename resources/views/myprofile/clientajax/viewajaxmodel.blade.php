@if($type=='phones')

    <div class="card">
        <div class="card-header">
            <i class="fas fa-blender-phone"></i>   {{ trans('form.registration.client.phone') }} 
            <a href="javascript:void(0);" onclick="addeditModal('phone_add_model','phones',1,'add')" class="float-right dt-btn-action" title="{{ trans('form.my_profile.addnew_phone') }}">
                <i class="mdi mdi-table-plus mdi-18px"></i>
            </a>
             
        </div>
        <div class="card-body">
            <blockquote class="card-blockquote mb-0">
                @php $phoneIndx = 1; @endphp
                @foreach($user->userPhones as $phone)
                @if ($phone->type=='phone' && $phone->value!='')
                <p> {{ $phone->value ?? '' }} @if ($phone->phone_type!='')<span class="badge badge-primary">@if(config('app.locale') == 'hr')
                                {{ !is_null(\App\ContactTypes::where('type_name', $phone->phone_type)->first())?\App\ContactTypes::where('type_name', $phone->phone_type)->first()->hr_type_name:(!is_null($phone->phone_type)?$phone->phone_type:'') }}
                                @else
                                {{ !is_null(\App\ContactTypes::where('type_name', $phone->phone_type)->first())?\App\ContactTypes::where('type_name', $phone->phone_type)->first()->type_name:(!is_null($phone->phone_type)?$phone->phone_type:'') }}
                                @endif</span>@endif
                    <a class="float-right dt-btn-action text-danger delete-record" data-outputid="phones_data" data-type="phones" data-id="{{$phone->id}}" title="{{ trans('form.my_profile.delete_phone') }}">
                        <i class="mdi mdi-delete mdi-18px"></i>
                    </a>   
                    <a href="javascript:void(0);" onclick="addeditModal('phone_edit_model','phones','{{$phone->id}}','edit')" class="float-right dt-btn-action" title="{{ trans('form.my_profile.edit_phone') }}">
                        <i class="mdi mdi-table-edit mdi-18px"></i>
                    </a> 
                </p> 
                
                @endif
                @php $phoneIndx++; @endphp
                @endforeach 
            </blockquote>
        </div>
    </div>

@endif
@if($type=='mobiles')

    <div class="card">
        <div class="card-header">
            <i class="fas fa-phone-volume"></i>   {{ trans('form.registration.client.mobile') }} 
            <a href="javascript:void(0);" onclick="addeditModal('phone_add_model','mobiles',1,'add')" class="float-right dt-btn-action" title="{{ trans('form.my_profile.addnew_phone') }}">
                <i class="mdi mdi-table-plus mdi-18px"></i>
            </a>
              
        </div>
        <div class="card-body">
            <blockquote class="card-blockquote mb-0">
                @php $mobileIndx = 1; @endphp
                @foreach($user->userPhones as $mobile)
                @if ($mobile->type=='mobile' && $mobile->value!='')
                <p> {{ $mobile->value ?? '' }} @if ($mobile->phone_type!='')<span class="badge badge-primary">@if(config('app.locale') == 'hr')
                                {{ !is_null(\App\ContactTypes::where('type_name', $mobile->phone_type)->first())?\App\ContactTypes::where('type_name', $mobile->phone_type)->first()->hr_type_name:(!is_null($mobile->phone_type)?$mobile->phone_type:'') }}
                                @else
                                {{ !is_null(\App\ContactTypes::where('type_name', $mobile->phone_type)->first())?\App\ContactTypes::where('type_name', $mobile->phone_type)->first()->type_name:(!is_null($mobile->phone_type)?$mobile->phone_type:'') }}
                                @endif</span>@endif
                    <a class="float-right dt-btn-action text-danger delete-record" data-outputid="mobiles_data" data-type="mobiles" data-id="{{$mobile->id}}" title="{{ trans('form.my_profile.delete_mobile') }}">
                        <i class="mdi mdi-delete mdi-18px"></i>
                    </a>   
                    <a href="javascript:void(0);" onclick="addeditModal('phone_edit_model','mobiles','{{$mobile->id}}','edit')" class="float-right dt-btn-action" title="{{ trans('form.my_profile.edit_mobile') }}">
                        <i class="mdi mdi-table-edit mdi-18px"></i>
                    </a> 
                </p> 
                
                @endif
                @php $mobileIndx++; @endphp
                @endforeach 
            </blockquote>
        </div>
    </div>


@endif
@if($type=='emails')

    <div class="card">
        <div class="card-header">
            <i class="fas fa-mail-bulk"></i>   {{ trans('form.registration.client.email') }} 
            <a href="javascript:void(0);" onclick="addeditModal('phone_add_model','emails',1,'add')" class="float-right dt-btn-action" title="{{ trans('form.my_profile.addnew_phone') }}">
                <i class="mdi mdi-table-plus mdi-18px"></i>
            </a>
            
        </div>
        <div class="card-body">
            <blockquote class="card-blockquote mb-0">
                @php $emailIndx = 1; @endphp
                @foreach($user->userEmails as $email)
                <p> {{ $email->value ?? '' }} @if ($email->email_type!='')<span class="badge badge-primary">@if(config('app.locale') == 'hr')
                                {{ !is_null(\App\ContactTypes::where('type_name', $email->email_type)->first())?\App\ContactTypes::where('type_name', $email->email_type)->first()->hr_type_name:(!is_null($email->email_type)?$email->email_type:'') }}
                                @else
                                {{ !is_null(\App\ContactTypes::where('type_name', $email->email_type)->first())?\App\ContactTypes::where('type_name', $email->email_type)->first()->type_name:(!is_null($email->email_type)?$email->email_type:'') }}
                                @endif</span>@endif
                    <a class="float-right dt-btn-action text-danger delete-record" data-outputid="emails_data" data-type="emails" data-id="{{$email->id}}" title="{{ trans('form.my_profile.delete_email') }}">
                        <i class="mdi mdi-delete mdi-18px"></i>
                    </a>   
                    <a href="javascript:void(0);" onclick="addeditModal('phone_edit_model','emails','{{$email->id}}','edit')" class="float-right dt-btn-action" title="{{ trans('form.my_profile.edit_email') }}">
                        <i class="mdi mdi-table-edit mdi-18px"></i>
                    </a> 
                </p> 
                
            
                @php $emailIndx++; @endphp
                @endforeach 
            </blockquote>
        </div>
    </div>



@endif
@if($type=='faxes')

    <div class="card">
        <div class="card-header">
            <i class="fas fa-fax"></i>   {{ trans('form.registration.client.fax') }} 
            <a href="javascript:void(0);" onclick="addeditModal('phone_add_model','faxes',1,'add')" class="float-right dt-btn-action" title="{{ trans('form.my_profile.addnew_fax') }}">
                <i class="mdi mdi-table-plus mdi-18px"></i>
            </a>
             
        </div>
        <div class="card-body">
            <blockquote class="card-blockquote mb-0">
                @php $faxIndx = 1; @endphp
                @foreach($user->userPhones as $fax)
                @if ($fax->type=='fax')
                <p> {{ $fax->value ?? '' }} @if ($fax->phone_type!='')<span class="badge badge-primary">
                @if(config('app.locale') == 'hr')
                                {{ !is_null(\App\ContactTypes::where('type_name', $fax->phone_type)->first())?\App\ContactTypes::where('type_name', $fax->phone_type)->first()->hr_type_name:(!is_null($fax->phone_type)?$fax->phone_type:'') }}
                                @else
                                {{ !is_null(\App\ContactTypes::where('type_name', $fax->phone_type)->first())?\App\ContactTypes::where('type_name', $fax->phone_type)->first()->type_name:(!is_null($fax->phone_type)?$fax->phone_type:'') }}
                                @endif</span>@endif
                    <a class="float-right dt-btn-action text-danger delete-record" data-outputid="faxes_data" data-type="faxes" data-id="{{$fax->id}}" title="{{ trans('form.my_profile.delete_fax') }}">
                        <i class="mdi mdi-delete mdi-18px"></i>
                    </a>   
                    <a href="javascript:void(0);" onclick="addeditModal('phone_edit_model','faxes','{{$fax->id}}','edit')" class="float-right dt-btn-action" title="{{ trans('form.my_profile.edit_fax') }}">
                        <i class="mdi mdi-table-edit mdi-18px"></i>
                    </a> 
                </p> 
                @endif
                @php $faxIndx++; @endphp
                @endforeach 
            </blockquote>
        </div>
    </div>

@endif
@if($type=='address')
<div class="row">
    @if(count($user->userAddresses)>0)
    @php $i = 1; @endphp
    @foreach($user->userAddresses as $useraddress)
    <div class="col-lg-3">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-map"></i>   {{ trans('form.registration.client.address') }} {{$i}}  <a data-outputid="address_data" data-type="address" data-id="{{$useraddress->id}}" class="float-right dt-btn-action text-danger delete-record"  title="{{trans('general.delete')}}">
                    <i class="mdi mdi-delete mdi-18px"></i>
                </a>   
                <a href="javascript:void(0);" onclick="addeditModal('address_edit_model','address','{{$useraddress->id}}','edit')" class="float-right dt-btn-action" title="{{trans('general.edit')}}">
                    <i class="mdi mdi-table-edit mdi-18px"></i>
                </a>      
                {{-- <button type="button" class="float-right btn btn-primary waves-effect waves-light" data-toggle="modal" onclick="showModal('address_edit1')"><i class="fas fa-edit"></i> {{trans('general.edit')}} </button> --}}
            </div>
            <div class="card-body">
                <blockquote class="card-blockquote mb-0">
                          <p>{{ $useraddress->address2 ?? '-' }}<br>{{ $useraddress->city.',' ?? '-' }}{{ $useraddress->state.',' ?? '-' }}{{ App::isLocale('hr')? $useraddress->country->hr_name:$useraddress->country->en_name }}{{ '-'.$useraddress->zipcode ?? '-' }}
                        
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
@endif
@if($type=='contact')
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
                                                                <th>{{ trans('general.action') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if(count($user->userContacts)>0)
                                                            @php $i = 1; @endphp
                                                            @foreach($user->userContacts as $usercontact)
                                                            <tr>
                                                                <td>{{$i}}</td>
                                                                <td>{{ !empty($usercontact->first_name) ? $usercontact->first_name.' '.$usercontact->last_name : '-' }}</td>
                                                                <td>
                                                                @if(config('app.locale') == 'hr')
                                                                {{ !is_null(\App\ContactTypes::where('type_name', $usercontact->contact_type)->first())?\App\ContactTypes::where('type_name', $usercontact->contact_type)->first()->hr_type_name:(!is_null($usercontact->contact_type)?$usercontact->contact_type:'') }}
                                                                @else
                                                                {{ !is_null(\App\ContactTypes::where('type_name', $usercontact->contact_type)->first())?\App\ContactTypes::where('type_name', $usercontact->contact_type)->first()->type_name:(!is_null($usercontact->contact_type)?$usercontact->contact_type:'') }}
                                                                @endif
                                                                </td>
                                                                <td>{{ $usercontact->workplace ?? '-' }}</td>
                                                                <td>{{ $usercontact->phone ?? '-' }}</td>
                                                                <td>{{ $usercontact->mobile ?? '-' }}</td>
                                                                <td>{{ $usercontact->fax ?? '-' }}</td>
                                                                <td>{{ $usercontact->email ?? '-' }}</td>
                                                                <td><span class="noVis" style="display: inline-flex">
                           
                                                                    <a href="javascript:void(0);" onclick="addeditModal('contact_edit_model','contact','{{$usercontact->id}}','edit')" class="dt-btn-action" title="{{trans('general.edit')}}">
                                                                        <i class="mdi mdi-table-edit mdi-18px"></i>
                                                                    </a>
                                                                    <a class="dt-btn-action text-danger delete-record" data-outputid="contact_data" data-type="contact" data-id="{{$usercontact->id}}" title="{{trans('general.delete')}}">
                                                                        <i class="mdi mdi-delete mdi-18px"></i>
                                                                    </a>
                                                                </span></td>
                                                            </tr>
                                                            @php $i++; @endphp
                                                            @endforeach 
                                                            @else
                                                            <tr>
                                                                <td class="text-center" colspan="9">
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
@endif