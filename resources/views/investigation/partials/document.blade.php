<div class="tab-pane p-3" id="documents" role="tabpanel">

    <div class="row">

        <div class="col-xs-12 col-sm-12 {{ $isadminsm ? 'col-md-9 col-lg-9 col-xl-9' : 'col-md-8 col-lg-8 col-xl-8' }} resp-order">
            <div class="table-rep-plugin">
                <div class="table-responsive mb-0" data-pattern="priority-columns">
                    <table id="inve_documents" class="table table-bordered">
                        <thead>
                        <th>{{ trans('general.sr_no') }}</th>
                        <th>{{ trans('form.registration.investigation.documents') }}</th>
                        <th>{{ trans('form.registration.investigation.document_type') }}</th>
                        @if ($isadminsm)
                        <th>{{ trans('form.registration.investigation.uploaded_by') }}</th>
                        <th>{{ trans('form.registration.investigation.share_document_with') }}</th>
                        @endif
                        <th>{{ trans('general.action') }}</th>
                        </thead>
                        <tbody>
                        @if(!$invn->documents->isEmpty())
                            @php $indx = 1; $count = 0; @endphp
                            @foreach($invn->documents as $document)
                                @php
                                $isPaid = ((isClient()) && ($document->document_typeid == 1) && (!empty($invoice)) && ($document->uploadedby->user_type->type_name == env('USER_TYPE_ADMIN') || $document->uploadedby->user_type->type_name == env('USER_TYPE_STATION_MANAGER')));
                                $viewable = isAdmin() || (isSM()) || (Auth::id() == $document->uploaded_by)
                                    || (isClient() && $document->share_to_client == 1)
                                    || (isInvestigator() && $document->share_to_investigator == 1)
                                    || (isDeliveryboy() && $document->share_to_delivery_boy == 1);
                                @endphp
                                @if($viewable || $isPaid)
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
                                    <td><a href="javascript:void(0);">{{ $document->doc_name }}</a></td>
                                    @if(App::isLocale('hr'))
                                        <td><a href="javascript:void(0);">{{ ($document->document_typeid!=0)?$document->documenttype->hr_name :"-" }}</a></td>
                                    @else
                                        <td><a href="javascript:void(0);">{{ ($document->document_typeid!=0)?$document->documenttype->name :"-" }}</a></td>
                                    @endif
                                    @if ($isadminsm)
                                    <td>{{ $document->uploadedBy->name }} ({{ App::isLocale('hr')?$document->uploadedBy->user_type->hr_type_name:$document->uploadedBy->user_type->type_name }})</td>
                                    <td>
                                        @if(Auth::id() == $document->uploaded_by || (isAdmin() || isSM()))
                                            <div class="form-inline form-group justify-content-between">
                                                <label for="share_with_client_{{ $document->id }}"
                                                       class="form-check-label"> {{ trans('form.client_reg') }}</label>
                                                <input name="share_client[]" id="share_with_client_{{ $document->id }}" type="checkbox"
                                                       switch="bool" {{ $document->share_to_client == 1 ? 'checked' : '' }}
                                                       onclick="changeShareSetting('{{ $document->id }}', 'share_to_client', this)">
                                                <label class="ml-2" for="share_with_client_{{ $document->id }}" data-on-label="{{ trans('general.yes') }}"
                                                       data-off-label="{{ trans('general.no') }}"> </label>

                                            </div>
                                            <div class="form-inline form-group justify-content-between">
                                                <label for="share_with_investigator_{{ $document->id }}"
                                                       class="form-check-label"> {{ trans('form.investigator_reg') }}</label>
                                                <input name="share_investigator[]" id="share_with_investigator_{{ $document->id }}"
                                                       type="checkbox" switch="bool" {{ $document->share_to_investigator == 1 ? 'checked' : '' }}
                                                       onclick="changeShareSetting('{{ $document->id }}', 'share_to_investigator', this)"/>
                                                <label class="ml-2" for="share_with_investigator_{{ $document->id }}" data-on-label="{{ trans('general.yes') }}"
                                                       data-off-label="{{ trans('general.no') }}"> </label>
                                            </div>
                                            <div class="form-inline form-group justify-content-between">
                                                <label for="share_with_delivery_boy_{{ $document->id }}"
                                                       class="form-check-label"> {{ trans('form.delivery_boy_reg') }}</label>
                                                <input name="share_delivery_boy[]" id="share_with_delivery_boy_{{ $document->id }}"
                                                       type="checkbox" switch="bool" {{ $document->share_to_delivery_boy == 1 ? 'checked' : '' }}
                                                       onclick="changeShareSetting('{{ $document->id }}', 'share_to_delivery_boy', this)"/>
                                                <label class="ml-2" for="share_with_delivery_boy_{{ $document->id }}" data-on-label="{{ trans('general.yes') }}"
                                                       data-off-label="{{ trans('general.no') }}"> </label>
                                            </div>
                                        @endif
                                    </td>
                                    @endif
                                    <td>
                                        <div class="action_btns">
                                           {{--  @if(isClient())
                                                @if(!empty($invn->invoice) && $invn->invoice->status == 'paid')
                                                    @if($isimage==1)
                                                        <a class="view image-popup-no-margins" href="{{URL::asset($imgurl)}}" target="_blank">
                                                            <i class="fas fa-eye"></i>
                                                            <img class="img-fluid d-none" alt="" src="{{URL::asset($imgurl)}}" width="75">
                                                        </a>
                                                    @else
                                                        <a class="view" href="{{URL::asset($imgurl)}}" target="_blank"><i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif
                                                @else
                                                <a href="javascript:void(0);" onclick="notPaidAlert()" class="view">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @endif
                                            @else --}}
                                                @if($isimage==1)
                                                    <a class="view image-popup-no-margins" href="{{URL::asset($imgurl)}}" target="_blank">
                                                        <i class="fas fa-eye"></i>
                                                        <img class="img-fluid d-none" alt="" src="{{URL::asset($imgurl)}}" width="75">
                                                    </a>
                                                @else
                                                    <a class="view" href="{{URL::asset($imgurl)}}" target="_blank"><i class="fas fa-eye"></i>
                                                    </a>
                                                @endif
                                            {{-- @endif --}}
                                            @if($document->uploaded_by == Auth::id())
                                            <a class="delete" href="javascript:void(0);" onclick="deleteDocument('{{ $document->id }}', this)">
                                                <i class="fas fa-trash"></i></a>
                                            @endif
                                        </div>

                                    </td>
                                </tr>
                                @php $indx++; @endphp
                                @endif
                                
                            @endforeach
                        @endif
                        </tbody>
                        <tfoot>
                        <tr class="tr-nodoc" style="{{ !$invn->documents->isEmpty() && $count > 0 ? 'display:none;' : '' }}">
                            <td colspan="{{ $isadminsm ? 6 : 4 }}" class="text-center">{{ trans('form.registration.investigation.document_notfound') }}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 {{ $isadminsm ? 'col-md-3 col-lg-3 col-xl-3' : 'col-md-4 col-lg-4 col-xl-4' }}">
            <form method="POST" class="dropzone" id="fileupload"
                  action="{{ route('investigation.upload-document') }}"
                  enctype="multipart/form-data">

                @csrf
                <input type="hidden" value="{{ Auth::id() }}" name="uploaded_by" />
                <input type="hidden" value="{{ $invn->id }}" name="investigation_id" />

                <div class="fallback">
                    <input name="file" type="file" multiple="multiple" class="form-control" />
                </div>
                <div class="dz-message">
                    <h4>{{ trans('form.registration.investigation.document_dropfile') }}</h4>
                </div>

            </form>
            <div id="doc_model" class="modal fade bs-example-modal-center" tabindex="-1"
                         role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0">{{ trans('form.registration.investigation.upload_documents') }}</h5>
                                    <button  id="closmod" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                   
                                    <form id="docmodelform">
                                    <table id="inve_documents2" class="table table-bordered">
                                        <thead>
                                            <th>{{ trans('form.registration.investigation.document_type') }}</th>
                                            <th>{{ trans('form.registration.investigation.file_title') }}</th>
                                            <th>{{ trans('form.registration.investigation.file_name') }}</th>
                                            <th>{{ trans('general.action') }}</th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    <div class="form-group text-center">
                                        <button type="button" id="uploadfile" class="uploadfile btn btn-primary w-md waves-effect waves-light addbtn">{{ trans('general.uploadfiles') }}</button>
                                        <button type="button" id="closmod2" data-dismiss="modal" aria-hidden="true" class="closmod btn btn-secondary w-md waves-effect waves-light" >{{ trans('general.cancel') }}</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                    </div>
        </div>
    </div>
</div>