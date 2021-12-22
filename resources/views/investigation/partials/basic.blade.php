<div class="tab-pane active py-3" id="basic_details" role="tabpanel">
    <h4 class="section-title mb-3 pb-2">{{ trans('form.registration.investigation.basic_details') }}</h4>
    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="form-group row">
                <label for="inv_name"
                       class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">{{ trans('form.registration.investigation.user_inquiry') }} :</label>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                    {{ $invn->user_inquiry ?? '-' }}
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="form-group row">
                <label for="inv_name"
                       class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">{{ trans('form.registration.investigation.paying_customer') }} :</label>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                    {{ $invn->client_customer->name ?? '-' }}
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="form-group row">
                <label for="inv_name"
                       class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">{{ trans('form.registration.investigation.file_claim_number') }} :</label>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                    {{ $invn->ex_file_claim_no ?? '-' }}
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="form-group row">
                <label for="inv_name"
                       class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">{{ trans('form.registration.investigation.claim_number') }} :</label>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                    {{ $invn->claim_number ?? '-' }}
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="form-group row">
                <label for="inv_name"
                       class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">{{ trans('form.registration.investigation.work_order_number') }} :</label>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                    {{ $invn->work_order_number ?? '-' }}
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="form-group row">
                <label for="inv_name"
                       class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">{{ trans('form.registration.investigation.req_type_inquiry') }} :</label>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                    {{ $typeofInq->product->name   ?? '-' }}
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="form-group row">
                <label for="inv_name" class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">{{ trans('form.registration.investigation.type_chk') }}</label>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">

                    <ul class="type-ul">
                        @if($invn->personal_del == '1') <li>{{trans('form.registration.investigation.personal_del')}}</li> @endif
                        @if($invn->company_del == '1') <li>{{trans('form.registration.investigation.company_del')}}</li> @endif
                        @if($invn->company_del == '1')
                            <ul>
                                @if($invn->make_paste == '1') <li>{{trans('form.registration.investigation.make_paste')}}</li> @endif
                                @if($invn->deliver_by_manager == '1') <li>{{trans('form.registration.investigation.delivery_company_manager')}}</li> @endif
                            </ul>
                        @endif
                    </ul>

                </div>
            </div>
        </div>
        

        @if($isadminsm)
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="form-group row">
                <label for="inv_name" class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">{{ trans('form.registration.investigation.inv_cost') }} :</label>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                    {{ trans('general.money_symbol')}}{{ $invn->inv_cost ?? '-' }}<br/>
                </div>
            </div>
        </div>
        @endif

        @if(isAdmin() || isSM())
        
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="form-group row">
                <label for="inv_name" class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">{{ trans('general.is_urgent') }}</label>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">

                    <input name="is_urgent" id="is_urgent" type="checkbox" switch="bool" {{ $invn->is_urgent == 1 ? 'checked' : '' }} onclick="changeToUrgent('{{ $invn->id }}', this)"/>
                        <label class="mt-2" id="urgent_label" for="is_urgent" data-on-label="{{ trans('general.yes') }}" data-off-label="{{ trans('general.no') }}"> </label>
                </div>
            </div>
        </div>
        @endif

    </div>

    @if(!isClient())
    <hr />
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <h4 class="section-title mb-3 pb-2">{{ trans('form.investigators') }}</h4>
            @if($assigned->isNotEmpty())
                <div class="row">
                    @foreach($assigned as $assign)
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="card inv-card investigator-card" data-id="{{$assign->id}}">
                            <h5 class="card-header font-16 mt-0">
                                {{ucwords($assign->investigator->user->name)}}

                                @if(isSM() || isAdmin())
                                @if(($assign->status == 'Report Accepted' || $assign->status == 'Completed With Findings' || ($assign->status != 'Report Accepted' && $assign->status != 'Cancel')) && $assign->status != 'Assigned' && $invn->status != 'Closed' && $assign->status != 'Final Report Submitted')
                                <div class="dropdown dropdown-topbar float-right">
                                    <a class="dt-btn-action btn inv-action-btn" href="#" role="button" id="invDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ trans('general.action') }} <i class="mdi mdi-chevron-down"></i>
                                    </a>

                                    <div class="dropdown-menu action-dd" aria-labelledby="invDropdownMenuLink">

                                        @if($assign->status == 'Completed With Findings' && $invn->status != 'Closed')

                                            <a onclick="adminActionOnReport('Report Accepted', 'admin', '{{ $assign->id }}', '{{trans('form.timeline_status.Report Accepted')}}')" class="dropdown-item" href="javascript:void(0)" >{{ trans('form.investigator_investigation_status.accept_report') }}</a>

                                            <a onclick="adminActionOnDecline('Report Declined', 'admin', '{{ $assign->investigator_id }}', '{{trans('form.timeline_status.Report Declined')}}', '{{$assign->id}}')" class="dropdown-item" href="javascript:void(0)" >{{ trans('form.investigator_investigation_status.decline_report') }}</a>
                                        
                                            <a onclick="adminActionOnDecline('Returned To Investigator', 'admin', '{{ $assign->investigator_id }}', '{{trans('form.timeline_status.Returned To Investigator')}}', '{{$assign->id}}')" class="dropdown-item" href="javascript:void(0)" >{{ trans('form.investigation_status.return_to_investigator') }}</a>

                                        @endif
                                        @if($assign->status == 'Report Accepted' || $assign->status != 'Report Accepted' && $assign->status != 'Assigned' && $assign->status != 'Cancel' && ($assign->status != 'Completed With Findings' && $invn->status != 'Closed'))
                                            <a onclick="adminActionOnReport('Cancel', 'admin', '{{ $assign->id }}', '{{trans('form.timeline_status.Cancel')}}')" class="dropdown-item" href="javascript:void(0)" >{{ trans('form.timeline_status.Cancel') }}</a>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                @if($assign->status == 'Assigned' && $assign->status != 'Cancel' && $assign->status != 'Completed With Findings' && $invn->status != 'Closed')
                                <div class="dropdown dropdown-topbar float-right">
                                    <a class="dt-btn-action btn inv-action-btn" href="#" role="button" id="invDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ trans('general.action') }} <i class="mdi mdi-chevron-down"></i>
                                    </a>
                                    <div class="dropdown-menu action-dd" aria-labelledby="invDropdownMenuLink">
                                        @if($assign->status == 'Assigned' && $assign->status != 'Cancel' && $assign->status != 'Completed With Findings' && $invn->status != 'Closed')

                                            <a onclick="adminActionOnReport('Cancel', 'admin', '{{ $assign->id }}', '{{trans('form.timeline_status.Cancel')}}')" class="dropdown-item" href="javascript:void(0)" >{{ trans('form.timeline_status.Cancel') }}</a>
                                        @endif

                                    </div>
                                </div>
                                @endif
                                @endif
                            </h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        @php
                                        $assignStatus = "";
                                        if($assign->status == 'Assigned'){
                                            $assignStatus = trans('form.investigator_investigation_status.AssignedInvestigator');
                                        }else if($assign->status == 'Investigation Started'){
                                            $assignStatus = trans('form.investigator_investigation_status.InvestigatorInProgress');
                                        }else if($assign->status == 'Completed With Findings'){
                                            $assignStatus = trans('form.investigator_investigation_status.InvestigatorFinishedReport');
                                        }else{
                                            $assignStatus = trans('form.investigator_investigation_status.'.str_replace(" ", "", $assign->status));
                                        }
                                        @endphp
                                        {{ trans('form.status') }} : <span class="status-text">{{ $assignStatus }}</span>
                                    </div>
                                    <div class="col-6">
                                        {{ trans('form.registration.investigator.company') }} : {{ !empty($assign->investigator->company) ? ucwords($assign->investigator->company) : '-' }}</p>
                                    </div>
                                </div>

                                <form id="inv-approval-form-{{ $assign->id }}" class="inv-form" action="{{ route('investigation.action-on-report') }}" method="post">

                                    @csrf

                                    <input type="hidden" name="assignId" value="{{ $assign->id }}">
                                    @if($assign->status == "Completed With Findings"
                                    || $assign->status == "Completed Without Findings"
                                    || $assign->status == "Report Accepted" || $assign->status == "Report Declined"
                                    || $assign->status == "Returned To Investigator" || $assign->status == "Final Report Submitted" || $assign->status == "Cancel" )
                                        <hr class="hr-inv">
                                        <div class="row">
                                            <!-- Show subject wise summary given by investigator-->
                                            @php
                                                if($assign->status == "Report Accepted"){
                                                    $comSummary = !empty($assign->admin_report_subject_summary) ? json_decode($assign->admin_report_subject_summary, true) : [];
                                                }else{
                                                    $comSummary = !empty($assign->completion_subject_summary) ? json_decode($assign->completion_subject_summary, true) : [];
                                                }
                                            @endphp
                                            @if(!empty($comSummary))
                                                @foreach($invn->subjects as $subject)
                                                    <div class="form-group col-md-12">
                                                        <label style="color:white !important;">{{ trans('form.investigation.write_summary_of') . ' ' . trans('form.registration.investigation.subject') . ' ' . $subject->first_name }} : </label>
                                                        <textarea class="form-control note" name="admin_summary[{{$subject->id}}]" {{ isSM() ? 'readonly' : '' }}>{{ isset($comSummary[$subject->id]) ? $comSummary[$subject->id] : ''}}</textarea>
                                                    </div>
                                                @endforeach
                                            @endif

                                            <div class="form-group col-md-12">
                                                <label style="color:white !important;">{{ trans('form.investigation.final_summary') }} : </label>
                                                <textarea class="form-control note" name="admin_final_summary" {{ isSM() ? 'readonly' : '' }}>{{ $assign->status == "Report Accepted" ? $assign->admin_report_final_summary : $assign->completion_summary }}</textarea>
                                            </div>
                                            

                                            @if($assign->status == "Final Report Submitted")
                                            <!-- <div class="form-group col-md-12">
                                                <label style="color:white !important;">{{ trans('form.investigation.sm_final_summary') }} : </label>
                                                <textarea class="form-control note" name="sm_final_summary" readonly>{{ $assign->sm_final_summary }}</textarea>
                                            </div> -->
                                            @endif
                                        </div>

                                        @if($invn->documents->isNotEmpty())
                                            <hr class="hr-inv">

                                            @php
                                                $caseReports = [];
                                                $smCaseReports = [];
                                                $otherDocs = [];
                                                $docCost = 0;
                                            @endphp

                                            @foreach($invn->documents as $document)
                                                @php
                                                if(($document->uploaded_by == Auth::id() || $document->uploaded_by == \App\Helpers\AppHelper::getUserIdFromInvestigatorId($assign->investigator_id)) && $document->uploaded_by != Auth::user()->id){
                                                    if($document->documenttype->name == 'Case Report'){
                                                        $caseReports[] = $document;
                                                    }else{
                                                        $otherDocs[] =  $document;
                                                    }
                                                }

                                                if($document->uploaded_by == \App\Helpers\AppHelper::checkIfUserIsSM($document->uploaded_by)){
                                                    if($document->documenttype->name == 'Case Report'){
                                                        $smCaseReports[] = $document;
                                                    }
                                                }
                                                @endphp
                                            @endforeach

                                            <table class="table table-bordered doc-table">
                                                @if(!empty($caseReports))
                                                    <tr>
                                                        <td colspan="3">
                                                            {{ trans('form.documenttypes.case_reports') }}
                                                        </td>
                                                    </tr>

                                                    @foreach($caseReports as $cdocument)
                                                        <tr class="detail-tr">
                                                            <td>
                                                                @php
                                                                    $docurl = '/investigation-documents/'.$cdocument->file_name;
                                                                @endphp
                                                                <a class="doc-link" style="color:white" href="{{ URL::asset($docurl) }}" target="_blank">{{ $cdocument->doc_name }}</a>
                                                            </td>
                                                            <td class="text-center">
                                                                <a class="doc-link" style="color:white" href="{{ URL::asset($docurl) }}" target="_blank"><i class="fa fa-download"></i></a>
                                                            </td>
                                                            @if(isSM() || isAdmin())
                                                            @if($assign->status == 'Completed With Findings' && $invn->status != 'Closed')
                                                            <td class="text-center">

                                                                <input type="checkbox" class="otherdoc otherdoc-{{$assign->id}}" data-price="{{$cdocument->price}}" name="casereport[{{$cdocument->id}}]" value="{{ $cdocument->id }}" checked >

                                                            </td>
                                                            @else
                                                            <input type="checkbox" class="otherdoc otherdoc-{{$assign->id}} d-none" data-price="{{$cdocument->price}}" name="casereport[{{$cdocument->id}}]" value="{{ $cdocument->id }}" checked  class="d-none">
                                                            @endif
                                                            @endif
                                                        </tr>
                                                        @php
                                                            $docCost = $docCost + $cdocument->price;
                                                        @endphp
                                                    @endforeach
                                                @endif

                                                @if(!empty($smCaseReports))
                                                    <tr>
                                                        <td colspan="3">
                                                            {{ trans('form.documenttypes.sm_case_reports') }}
                                                        </td>
                                                    </tr>
                                                    @foreach($smCaseReports as $cdocument)
                                                        <tr class="detail-tr">
                                                            <td>
                                                                @php
                                                                    $docurl = '/investigation-documents/'.$cdocument->file_name;
                                                                @endphp
                                                                <a class="doc-link" style="color:white" href="{{ URL::asset($docurl) }}" target="_blank">{{ $cdocument->doc_name }}</a>
                                                            </td>
                                                            @if(isSM() || isAdmin())
                                                            @if($assign->status == 'Completed With Findings' && $invn->status != 'Closed')
                                                            <td class="text-center">

                                                                <input type="checkbox" class="otherdoc otherdoc-{{$assign->id}}" name="casereport[{{$cdocument->id}}]" value="{{ $cdocument->id }}" data-price="{{$cdocument->price}}" checked >

                                                            </td>
                                                            @else
                                                            <input type="checkbox" class="otherdoc otherdoc-{{$assign->id}} d-none" name="casereport[{{$cdocument->id}}]" value="{{ $cdocument->id }}" checked data-price="{{$cdocument->price}}"  class="d-none">
                                                            @endif
                                                            @endif
                                                        </tr>
                                                        @php
                                                            $docCost = $docCost + $cdocument->price;
                                                        @endphp
                                                    @endforeach
                                                @endif

                                                @if(!empty($otherDocs))
                                                    <tr>
                                                        <td colspan="3">
                                                            {{ trans('form.documenttypes.other_documents') }}
                                                        </td>
                                                    </tr>
                                                    @foreach($otherDocs as $odocument)
                                                        <tr class="detail-tr">
                                                            <td>
                                                                @php
                                                                    $docurl = '/investigation-documents/'.$odocument->file_name;
                                                                @endphp
                                                                <a class="doc-link" style="color:white" href="{{ URL::asset($docurl) }}" target="_blank">{{ $odocument->doc_name }}</a>
                                                            </td>
                                                            <td class="text-center">
                                                                <a class="doc-link" style="color:white" href="{{ URL::asset($docurl) }}" target="_blank"><i class="fa fa-download"></i></a>
                                                            </td>
                                                            @if(isSM() || isAdmin())
                                                            @if($assign->status == 'Completed With Findings' && $invn->status != 'Closed')
                                                            <td class="text-center">
                                                                <input type="checkbox" class="otherdoc otherdoc-{{$assign->id}}" name="otherdoc[{{$odocument->id}}]" value="{{ $odocument->id }}" data-price="{{ $odocument->price }}" checked >
                                                            </td>
                                                            @else
                                                            <input type="checkbox" class="otherdoc otherdoc-{{$assign->id}} d-none" name="otherdoc[{{$odocument->id}}]" value="{{ $odocument->id }}" data-price="{{ $odocument->price }}" checked >
                                                            @endif
                                                            @endif
                                                        </tr>
                                                        @php
                                                            $docCost = $docCost + $odocument->price;
                                                        @endphp
                                                    @endforeach
                                                @endif
                                            </table>

                                        @endif

                                        <hr class="hr-inv">

                                        {{--Investigation cost and document costing and price--}}
                                        @php
                                            $invCost = \App\Helpers\AppHelper::calculateInvestigationCostForInvestigator($assign->investigation_id, $assign->investigator_id);

                                            $assignInvCost = \App\InvestigatorInvestigations::where('investigation_id', $assign->investigation_id)->where('investigator_id', $assign->investigator_id)->first();
                                        @endphp
                                        <div class="row">
                                            <div class="col-12">
                                                {{ trans('form.registration.investigation.inv_cost') }} : {{ trans('general.money_symbol')}}

                                                @php
                                                    $invst_invoice = \App\Helpers\AppHelper::getInvoiceInvestigatorId($assign->investigation_id,$assign->investigator_id);

                                                    $invst_invoice_id = $invst_invoice['id'];
                                                    $invst_invoice_status = $invst_invoice['status'];
                                                @endphp

                                                @if($assignInvCost->inv_cost == $invCost)
                                                    <input type="hidden" name="inv_cost" value="{{ $invCost }}">
                                                    @if($invst_invoice_id > 0 && $invst_invoice_status != 'paid')
                                                        <a href="#" class="invst_amount" style="color: #fff;" data-type="text" data-pk="{{$invst_invoice_id}}" data-title="הכנס סכום">{{ $invCost }}</a>
                                                        <span class="inv-cost-{{$assign->id}}" style= "display:none;" >{{ $invCost }}</span>
                                                    @else
                                                        <a href="#" class="invst_amount" style="color: #fff;" data-type="text" data-pk="{{ json_encode(['investigation_id' => $assign->investigation_id,'investigator_id' => $assign->investigator_id]) }}" data-title="הכנס סכום">{{ $invCost }}</a>
                                                        <span class="inv-cost-{{$assign->id}}" style= "display:none;" >{{ $invCost }}</span>

                                                        <!-- <span class="invst_amount inv-cost-{{$assign->id}}" >{{ $invCost }}</span> -->
                                                    @endif
                                                @else 
                                                    <input type="hidden" name="inv_cost" value="{{ $assignInvCost->inv_cost }}">
                                                    @if($invst_invoice_id > 0 && $invst_invoice_status != 'paid')
                                                        <a href="#" class="invst_amount" style="color: #fff;" data-type="text" data-pk="{{$invst_invoice_id}}" data-title="הכנס סכום">{{ $assignInvCost->inv_cost }}</a>

                                                        <span class="inv-cost-{{$assign->id}}" style= "display:none;">{{ $assignInvCost->inv_cost }}</span>
                                                    @else

                                                        <a href="#" class="invst_amount" style="color: #fff;" data-type="text" data-pk="{{ json_encode(['investigation_id' => $assign->investigation_id,'investigator_id' => $assign->investigator_id]) }}" data-title="הכנס סכום">{{ $assignInvCost->inv_cost }}</a>

                                                        <span class="inv-cost-{{$assign->id}}" style= "display:none;">{{ $assignInvCost->inv_cost }}</span>

                                                        <!-- <span class="inv-cost-{{$assign->id}}">{{ $assignInvCost->inv_cost }}</span> -->
                                                    @endif
                                                @endif

                                            </div>

                                            <div class="col-12">
                                                {{ trans('form.registration.investigation.doc_cost') }} : {{ trans('general.money_symbol')}}<span class="doc-cost-{{$assign->id}}"></span>
                                                <input type="hidden" name="doc_cost" id="doc_cost-${{$assign->id}}" value="">
                                            </div>
                                            <div class="col-12">
                                                <strong>{{ trans('form.registration.investigation.total_cost') }} : {{ trans('general.money_symbol')}}<span class="total-cost-{{$assign->id}}"></span> </strong>
                                            </div>
                                        </div>
                                    @endif
                                </form>

                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <center>
                            <p>{{trans('form.investigation.not_found')}}</p>
                        </center>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <h4 class="section-title mb-3 pb-2">{{ trans('form.delivery_boys') }}</h4>
            
            @if($assignedDel->isNotEmpty())
                <div class="row">
                    @foreach($assignedDel as $assign)
                        <div class="col-xs-12 col-sm-12 col-md-4">
                            <div class="card inv-card deliveryboy-card" data-id="{{$assign->id}}">
                                <h5 class="card-header font-16 mt-0">
                                    {{ucwords($assign->deliveryboy->user->name)}}

                                    @if(isSM() || isAdmin())
                                    @if($invn->status != 'Closed' && $assigned->isNotEmpty() && $assigned->contains('status', 'Report Accepted') && ($assign->status == 'Return To Center' || $assign->status != 'Cancel') && $assign->status != 'Assigned' && $invn->status != 'Closed' && $assign->status != 'Done And Delivered')
                                    <div class="dropdown dropdown-topbar float-right">
                                        <a class="dt-btn-action btn inv-action-btn" href="#" role="button" id="delDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ trans('general.action') }} <i class="mdi mdi-chevron-down"></i>
                                        </a>

                                        <div class="dropdown-menu action-dd" aria-labelledby="delDropdownMenuLink">

                                            @if($invn->status != 'Closed' && $assigned->isNotEmpty() && $assigned->contains('status', 'Report Accepted') && ($assign->status == 'Return To Center' || $assign->status != 'Cancel'))

                                                @if($assign->status != 'Done And Delivered' && $assign->status == 'Return To Center')
                                                    <a onclick="adminActionOnReportDel('Done And Delivered', 'admin', '{{ $assign->deliveryboy_id }}', '{{trans('form.timeline_status.Done And Delivered')}}')" class="dropdown-item" href="javascript:void(0)" >{{ trans('form.investigation_status.Delivered') }}</a>
                                                @endif

                                                @if($assign->status != 'Done And Not Delivered' && $assign->status == 'Return To Center')
                                                    <a onclick="adminActionOnReportDel('Done And Not Delivered', 'admin', '{{ $assign->deliveryboy_id }}', '{{trans('form.timeline_status.Done And Not Delivered')}}')" class="dropdown-item" href="javascript:void(0)" >{{ trans('form.investigation_status.NotDelivered') }}</a>
                                                @endif

                                                @if($assign->status != 'Done And Delivered' && $assign->status != 'Return To Center' && $assign->status != 'Cancel')
                                                <a onclick="adminActionOnReportDel('Cancel', 'admin', '{{ $assign->deliveryboy_id }}', '{{trans('form.timeline_status.Cancel')}}')" class="dropdown-item" href="javascript:void(0)" >{{ trans('form.timeline_status.Cancel') }}</a>
                                                @endif
                                            @endif

                                        </div>
                                    </div>
                                    @endif
                                    @if($assign->status == 'Assigned' && $assign->status != 'Cancel' && $assign->status != 'Done And Delivered' && $invn->status != 'Closed')
                                    <div class="dropdown dropdown-topbar float-right">
                                        <a class="dt-btn-action btn inv-action-btn" href="#" role="button" id="invDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ trans('general.action') }} <i class="mdi mdi-chevron-down"></i>
                                        </a>
                                        <div class="dropdown-menu action-dd" aria-labelledby="invDropdownMenuLink">
                                            @if($assign->status == 'Assigned' && $assign->status != 'Cancel' && $assign->status != 'Done And Delivered' && $invn->status != 'Closed')
                                                <a onclick="adminActionOnReportDel('Cancel', 'admin', '{{ $assign->deliveryboy_id }}', '{{trans('form.timeline_status.Cancel')}}')" class="dropdown-item" href="javascript:void(0)" >{{ trans('form.timeline_status.Cancel') }}</a>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                    @endif
                                </h5>
                                <div class="card-body">
                                    <div class="row">
                                        @php
                                            $assignDelStatus = "";
                                            if($assign->status == 'Assigned'){
                                                $assignDelStatus = trans('form.deliveryboy_investigation_status.AssignedDeliveryboy');
                                            }else if($assign->status == 'Accepted'){
                                                $assignDelStatus = trans('form.deliveryboy_investigation_status.DeliveryInProcess');
                                            }else if($assign->status == 'Return To Center'){
                                                $assignDelStatus = trans('form.deliveryboy_investigation_status.DeliveryFinishedReport');
                                            }else{
                                                $assignDelStatus = trans('form.deliveryboy_investigation_status.'.str_replace(" ","",$assign->status));
                                            }
                                        @endphp
                                        <div class="col-6">
                                            {{ trans('form.status') }} : <span class="status-text">{{ $assignDelStatus }}</span>
                                        </div>
                                        <div class="col-6">
                                            {{ trans('form.registration.investigator.company') }} : {{ $assign->deliveryboy->company ?? '-' }}</p>
                                        </div>
                                    </div>
                                    
                                    <form id="inv-approval-form-del-{{ $assign->deliveryboy_id }}" action="{{ route('investigation.action-on-report-del') }}" method="post">

                                    @csrf

                                    <input type="hidden" name="assignId" value="{{ $assign->id }}">
                                    @if($assign->status == "Return To Center" || $assign->status == "Done And Delivered" || $assign->status == "Done And Not Delivered")
                                        <hr class="hr-inv">
                                        <div class="row">
                                            <!-- Show subject wise summary given by investigator-->
                                            @php
                                                if($assign->status == "Report Accepted"){
                                                    $comSummary = !empty($assign->admin_report_subject_summary) ? json_decode($assign->admin_report_subject_summary, true) : [];
                                                }else{
                                                    $comSummary = !empty($assign->completion_subject_summary) ? json_decode($assign->completion_subject_summary, true) : [];
                                                }
                                            @endphp
                                            @if(!empty($comSummary))
                                                @foreach($invn->subjects as $subject)
                                                    <div class="form-group col-md-12">
                                                        <label style="color:white !important;">{{ trans('form.investigation.write_summary_of') . ' ' . trans('form.registration.investigation.subject') . ' ' . $subject->first_name }} : </label>
                                                        <textarea class="form-control note" name="admin_summary[{{$subject->id}}]" {{ isSM() ? 'readonly' : '' }}>{{ isset($comSummary[$subject->id]) ? $comSummary[$subject->id] : ''}}</textarea>
                                                    </div>
                                                @endforeach
                                            @endif

                                            <div class="form-group col-md-12">
                                                <label style="color:white !important;">{{ trans('form.investigation.final_summary') }} : </label>
                                                <textarea class="form-control note" name="admin_final_summary" {{ isSM() ? 'readonly' : '' }}>{{ $assign->completion_summary }}</textarea>
                                            </div>
                                        </div>
                                        
                                        @if($invn->documents->isNotEmpty())
                                            <hr class="hr-inv">

                                            @php
                                                $caseReports = [];
                                                $otherDocs = [];
                                                $docCost = 0;
                                            @endphp

                                            @foreach($invn->documents as $document)
                                                @php
                                                if($document->uploaded_by == Auth::id() || $document->uploaded_by == \App\Helpers\AppHelper::getUserIdFromDeliveryboyId($assign->deliveryboy_id)){
                                                    if($document->documenttype->name == 'Case Report'){
                                                        $caseReports[] = $document;
                                                    }else{
                                                        $otherDocs[] =  $document;
                                                    }
                                                }
                                                @endphp
                                            @endforeach

                                            <table class="table table-bordered doc-table">
                                                @if(!empty($caseReports))
                                                    <tr>
                                                        <td colspan="3">
                                                            {{ trans('form.documenttypes.case_reports') }}
                                                        </td>
                                                    </tr>
                                                    @foreach($caseReports as $cdocument)
                                                        <tr class="detail-tr">
                                                            <td>
                                                                @php
                                                                    $docurl = '/investigation-documents/'.$cdocument->file_name;
                                                                @endphp
                                                                <a class="doc-link" style="color:white" href="{{ URL::asset($docurl) }}" target="_blank">{{ $cdocument->doc_name }}</a>
                                                            </td>
                                                            <td class="text-center">
                                                                <a class="doc-link" style="color:white" href="{{ URL::asset($docurl) }}" target="_blank"><i class="fa fa-download"></i></a>
                                                            </td>
                                                            @if(isSM() || isAdmin())
                                                            @if($invn->status != 'Closed' && $assigned->isNotEmpty() && $assigned->contains('status', 'Report Accepted') && $assign->status == 'Return To Center')
                                                            <td class="text-center">

                                                                <input type="checkbox" class="otherdoc otherdoc-del otherdoc-del-{{$assign->id}}" data-price="{{$cdocument->price}}" id="otherdoc-del-{{$assign->id}}" name="casereport[{{$cdocument->id}}]" value="{{ $cdocument->id }}" checked >

                                                            </td>
                                                            @else
                                                                <input type="checkbox" class="otherdoc otherdoc-del d-none otherdoc-del-{{$assign->id}}" data-price="{{$cdocument->price}}" id="otherdoc-del-{{$assign->id}}" name="casereport[{{$cdocument->id}}]" value="{{ $cdocument->id }}" checked  class="d-none">
                                                            @endif
                                                            @endif
                                                        </tr>
                                                        @php
                                                            $docCost = $docCost + $cdocument->price;
                                                        @endphp
                                                    @endforeach
                                                @endif

                                                @if(!empty($otherDocs))
                                                    <tr>
                                                        <td colspan="3">
                                                            {{ trans('form.documenttypes.other_documents') }}
                                                        </td>
                                                    </tr>
                                                    @foreach($otherDocs as $odocument)
                                                        <tr class="detail-tr">
                                                            <td>
                                                                @php
                                                                    $docurl = '/investigation-documents/'.$odocument->file_name;
                                                                @endphp
                                                                <a class="doc-link" style="color:white" href="{{ URL::asset($docurl) }}" target="_blank">{{ $odocument->doc_name }}</a>
                                                            </td>
                                                            <td class="text-center">
                                                                <a class="doc-link" style="color:white" href="{{ URL::asset($docurl) }}" target="_blank"><i class="fa fa-download"></i></a>
                                                            </td>
                                                            @if(isSM() || isAdmin())
                                                            @if($invn->status != 'Closed' && $assigned->isNotEmpty() && $assigned->contains('status', 'Report Accepted') && $assign->status == 'Return To Center')
                                                            <td class="text-center">
                                                                <input type="checkbox" class="otherdoc otherdoc-del otherdoc-del-{{$assign->id}}" name="otherdoc[{{$odocument->id}}]" value="{{ $odocument->id }}" id="otherdoc-del-{{$assign->id}}" data-price="{{ $odocument->price }}" checked >
                                                            </td>
                                                            @else
                                                            <input type="checkbox" class="otherdoc otherdoc-del d-none otherdoc-del-{{$assign->id}}" name="otherdoc[{{$odocument->id}}]" value="{{ $odocument->id }}" id="otherdoc-del-{{$assign->id}}" data-price="{{ $odocument->price }}" checked >
                                                            @endif
                                                            @endif
                                                        </tr>
                                                        @php
                                                            $docCost = $docCost + $odocument->price;
                                                        @endphp
                                                    @endforeach
                                                @endif
                                            </table>

                                        @endif

                                        <hr class="hr-inv">

                                        {{--Investigation cost and document costing and price--}}
                                        @php
                                            $invCost = \App\Helpers\AppHelper::calculateInvestigationCostForDeliveryboy($assign->investigation_id, $assign->deliveryboy_id);

                                            $assignInvCost = \App\DeliveryboyInvestigations::where('investigation_id', $assign->investigation_id)->where('deliveryboy_id', $assign->deliveryboy_id)->first();

                                        @endphp
                                        <div class="row">
                                            <div class="col-12">
                                                {{ trans('form.products_form.del_cost') }} : {{ trans('general.money_symbol')}}

                                                @php
                                                    $delboy_invoice = \App\Helpers\AppHelper::getInvoiceIDelboyId($assign->investigation_id,$assign->deliveryboy_id);

                                                    $delboy_invoice_id = $delboy_invoice['id'];
                                                    $delboy_invoice_status = $delboy_invoice['status'];
                                                @endphp

                                                @if($invCost == $assignInvCost->inv_cost)
                                                    <input type="hidden" name="inv_cost" value="{{ $invCost }}">


                                                    @if($delboy_invoice_id > 0 && $delboy_invoice_status != 'paid')
                                                        <a href="#" class="delboy_amount" style="color: #fff;" data-type="text" data-pk="{{$delboy_invoice_id}}" data-title="הכנס סכום">{{ $invCost }}</a>
                                                        
                                                        <span class="inv-cost-del-{{$assign->id}}" style= "display:none;">{{ $invCost }}</span>
                                                    @else
                                                        <a href="#" class="delboy_amount" style="color: #fff;" data-type="text" data-pk="{{ json_encode(['investigation_id' => $assign->investigation_id,'deliveryboy_id' => $assign->deliveryboy_id]) }}" data-title="הכנס סכום">{{ $invCost }}</a>
                                                        
                                                        <span class="inv-cost-del-{{$assign->id}}" style= "display:none;">{{ $invCost }}</span>
                                                        <!-- <span class="inv-cost-del-{{$assign->id}}">{{ $invCost }}</span> -->
                                                    @endif

                                                @else 
                                                    <input type="hidden" name="inv_cost" value="{{ $assignInvCost->inv_cost }}">

                                                    @if($delboy_invoice_id > 0 && $delboy_invoice_status != 'paid')
                                                        <a href="#" class="delboy_amount" style="color: #fff;" data-type="text" data-pk="{{$delboy_invoice_id}}" data-title="הכנס סכום">{{ $assignInvCost->inv_cost }}</a>

                                                        <span class="inv-cost-del-{{$assign->id}}" style= "display:none;">{{ $assignInvCost->inv_cost }}</span>
                                                    @else
                                                        <a href="#" class="delboy_amount" style="color: #fff;" data-type="text" data-pk="{{ json_encode(['investigation_id' => $assign->investigation_id,'deliveryboy_id' => $assign->deliveryboy_id]) }}" data-title="הכנס סכום">{{ $assignInvCost->inv_cost }}</a>

                                                        <span class="inv-cost-del-{{$assign->id}}" style= "display:none;">{{ $assignInvCost->inv_cost }}</span>

                                                        <!-- <span class="inv-cost-del-{{$assign->id}}">{{ $assignInvCost->inv_cost }}</span> -->
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="col-12">
                                                {{ trans('form.registration.investigation.doc_cost') }} : {{ trans('general.money_symbol')}}<span class="doc-cost-del-{{$assign->id}}"></span>
                                                <input type="hidden" name="doc_cost" id="doc_cost_del-{{$assign->id}}" value="">
                                            </div>
                                            <div class="col-12">
                                                <strong>{{ trans('form.registration.investigation.total_cost') }} : {{ trans('general.money_symbol')}}<span class="total-cost-del-{{$assign->id}}"></span> </strong>
                                            </div>
                                        </div>
                                    @endif
                                </form>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <center>
                            <p>{{trans('form.investigation.not_found_del')}}</p>
                        </center>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @endif
    

</div>