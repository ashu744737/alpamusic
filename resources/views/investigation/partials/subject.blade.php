<div class="tab-pane {{$indeltabactive}} p-3" id="subjects" role="tabpanel">
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-rep-plugin">
                <div class="table-responsive mb-0" data-pattern="priority-columns">
                    <table id="inve_subjects" class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 8%">{{ trans('form.registration.investigation.family') }}</th>
                            <th style="width: 8%">{{ trans('form.registration.investigation.firstname') }}</th>
                            <th style="width: 8%">{{ trans('form.registration.investigation.sub_type') }}</th>
                            <th style="width: 8%">{{ trans('form.registration.investigation.id') }}</th>
                            <th style="width: 8%">{{ trans('form.registration.investigation.bank_ac_no') }}</th>
                            <th style="width: 8%">{{ trans('form.registration.investigation.account_no') }}</th>
                            <th style="width: 8%">{{ trans('form.registration.investigation.workplace') }}</th>
                            <th style="width: 8%">{{ trans('form.registration.investigation.website') }}</th>
                            <th style="width: 8%">{{ trans('form.registration.investigation.father_name') }}</th>
                            <th style="width: 8%">{{ trans('form.registration.investigation.mothername') }}</th>
                            <th style="width: 8%">{{ trans('form.registration.investigation.spousename') }}</th>
                            <th style="width: 8%">{{ trans('form.registration.investigation.spouse') }}</th>
                            <th style="width: 8%">{{ trans('form.registration.investigation.carnumber') }}</th>
                            <th style="width: 8%">{{ trans('form.registration.investigation.passport') }}</th>
                            <th style="width: 8%">{{ trans('form.registration.investigator.date_of_birth') }}</th>
                            @php if (isAdmin() || isSM()) 
                    {$acflag='';}else{$acflag=1;}@endphp
                           
                    
                            @if($indeltabactive=='' && $isadminsm==1)
                            <th style="width: 8%">{{ trans('form.registration.investigation.required_cost_invs') }}</th>
                            @endif
                            @if($isadminsm==1)
                            <th style="width: 8%">{{ trans('form.registration.investigation.address_confirmed') }}</th>
                            @endif
                            <th style="width: 20%">{{ trans('general.action') }}</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($invn->subjects as $subject)
                            <tr>
                                <td>{{$subject->family_name}}</td>
                                <td>{{$subject->first_name}}</td>
                                @if(in_array($subject->sub_type, array('Main', 'Spouse', 'Company')))
                                    @if(config('app.locale') == 'hr')
                                    <td>{{ \App\SubjectTypes::where('name', $subject->sub_type)->first()->hr_name }}</td>
                                    @else
                                    <td>{{ \App\SubjectTypes::where('name', $subject->sub_type)->first()->name }}</td>
                                    @endif
                                @else
                                    <td>{{ $subject->sub_type }}</td>
                                @endif
                                <td>{{$subject->id_number}}</td>
                                <td>{{$subject->bank_account_no}}</td>
                                <td>{{$subject->account_no}}</td>
                                <td>{{$subject->workplace}}</td>
                                <td>{{$subject->website}}</td>
                                <td>{{$subject->father_name}}</td>
                                <td>{{$subject->mother_name}}</td>
                                <td>{{$subject->spouse_name}}</td>
                                <td>{{$subject->spouse}}</td>
                                <td>{{$subject->car_number}}</td>
                                <td>{{$subject->passport}}</td>
                                @if(!is_null($subject->dob))
                                <td>{{\Carbon\Carbon::parse($subject->dob)->format('d/m/y')}}</td>
                                @else
                                <td></td>
                                @endif
                                @if($indeltabactive==''  && $isadminsm==1)
                                <td>{{ trans('general.money_symbol')}}{{$subject->req_inv_cost}}</td>
                                @endif
                                @if($isadminsm==1)
                                <td>
                                    <div class="form-check-inline m-0 "> 
                            
                                        <input name="address_confirmed_{{$subject->id}}" id="address_confirmed_{{$subject->id}}" onclick="changesubjectaddressconfirm(this,{{$subject->id}});"  type="checkbox" switch="bool"{{ $subject->address_confirmed == 1 ? 'checked' : '' }}>
                                        <label class="mt-2 ml-1 arr_contacts_address_confirmed_lable" data-on-label="{{ trans('general.yes') }}" data-off-label="{{ trans('general.no') }}" for="address_confirmed_{{$subject->id}}"></label>
                                        </div>
                                </td>
                                @endif
      
                                <td>
                                    <a href="javascript:void(0);" onclick="showModal('contact_model', {{$subject->id}})" class="dt-btn-action" title="{{ trans('form.registration.investigation.show_contacts') }}">
                                        <i class="fas fa-fax"></i>
                                    </a>
                                    <div id="contact_model-data-{{$subject->id}}" style="display: none;">
                                   
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.main_email') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $subject->main_email ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.alternate_email') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $subject->alternate_email ?? '-' }}
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
                                                    {{ $subject->main_phone ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.secondary_phone') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $subject->secondary_phone ?? '-' }}
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
                                                    {{ $subject->main_mobile ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.registration.investigation.secondary_mobile') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $subject->secondary_mobile ?? '-' }}
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
                                                    {{ $subject->fax ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                    <a href="javascript:void(0);" onclick="showModal('address_model', {{$subject->id}})" class="dt-btn-action" title="{{ trans('form.registration.investigation.show_addresses') }}">
                                        <i class="fas fa-map"></i>
                                    </a>

                                    <div id="address_model-data-{{$subject->id}}" style="display: none;">
                                        @if(!$subject->subject_addresses->isEmpty())
                                            @php $subindex = 1; @endphp
                                            @foreach($subject->subject_addresses as $address)

                                                <div class="form-row mt-3">
                                                    <div class="col-md-12">
                                                        <span class="text-muted font-size-14 mb-1">{{ trans('form.address') . ' ' . $subindex }}</span>
                                                        <hr class="mt-2">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                        <div class="form-group row">
                                                            <label for="inv_name" class="col-form-label col-4">{{ trans('form.registration.client.address') }} :</label>
                                                            <div class="col-8">
                                                                {{ $address->address1 ?? '-' }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                        <div class="form-group row">
                                                            <label for="inv_name" class="col-form-label col-4">{{ trans('form.registration.client.address2') }} :</label>
                                                            <div class="col-8">
                                                                {{ $address->address2 ?? '-' }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                        <div class="form-group row">
                                                            <label for="inv_name" class="col-form-label col-4">{{ trans('form.registration.client.city') }} :</label>
                                                            <div class="col-8">
                                                                {{ $address->city ?? '-' }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                        <div class="form-group row">
                                                            <label for="inv_name"
                                                                   class="col-form-label col-4">{{ trans('form.registration.client.state') }} :</label>
                                                            <div class="col-8">
                                                                {{ $address->state ?? '-' }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                        <div class="form-group row">
                                                            <label for="inv_name" class="col-form-label col-4">{{ trans('form.registration.client.country') }} :</label>
                                                            <div class="col-8">
                                                      
                                                               {{ App::isLocale('hr')?$address->country->hr_name ?? '-':$address->country->en_name ?? '-' }} 
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                        <div class="form-group row">
                                                            <label for="inv_name" class="col-form-label col-4">{{ trans('form.registration.client.zip_code') }} :</label>
                                                            <div class="col-8">
                                                                {{ $address->zipcode ?? '-' }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                @php $subindex++; @endphp
                                            @endforeach
                                        @else
                                            <h6 class="text-center">{{ trans('form.registration.investigation.no_addresses') }}</h6>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        </tbody>
                        @endforeach
                    </table>

                    <div id="address_model" class="modal fade bs-example-modal-center" tabindex="-1"
                         role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0">{{trans('form.registration.investigation.show_subject_addresses')}}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">

                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                    </div>

                    <div id="contact_model" class="modal fade bs-example-modal-center" tabindex="-1"
                    role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                   <div class="modal-dialog modal-dialog-centered modal-lg">
                       <div class="modal-content">
                           <div class="modal-header">
                               <h5 class="modal-title mt-0">{{ trans('form.registration.investigation.contact_details') }}</h5>
                               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                           </div>
                           <div class="modal-body">

                           </div>
                       </div>
                       <!-- /.modal-content -->
                   </div>
               </div>

                </div>
            </div>
        </div>

    </div>
</div>