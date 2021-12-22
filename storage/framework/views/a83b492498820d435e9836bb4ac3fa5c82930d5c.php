<div class="tab-pane active py-3" id="basic_details" role="tabpanel">
    <h4 class="section-title mb-3 pb-2"><?php echo e(trans('form.registration.investigation.basic_details')); ?></h4>
    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="form-group row">
                <label for="inv_name"
                       class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3"><?php echo e(trans('form.registration.investigation.user_inquiry')); ?> :</label>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                    <?php echo e($invn->user_inquiry ?? '-'); ?>

                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="form-group row">
                <label for="inv_name"
                       class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3"><?php echo e(trans('form.registration.investigation.paying_customer')); ?> :</label>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                    <?php echo e($invn->client_customer->name ?? '-'); ?>

                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="form-group row">
                <label for="inv_name"
                       class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3"><?php echo e(trans('form.registration.investigation.file_claim_number')); ?> :</label>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                    <?php echo e($invn->ex_file_claim_no ?? '-'); ?>

                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="form-group row">
                <label for="inv_name"
                       class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3"><?php echo e(trans('form.registration.investigation.claim_number')); ?> :</label>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                    <?php echo e($invn->claim_number ?? '-'); ?>

                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="form-group row">
                <label for="inv_name"
                       class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3"><?php echo e(trans('form.registration.investigation.work_order_number')); ?> :</label>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                    <?php echo e($invn->work_order_number ?? '-'); ?>

                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="form-group row">
                <label for="inv_name"
                       class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3"><?php echo e(trans('form.registration.investigation.req_type_inquiry')); ?> :</label>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                    <?php echo e($typeofInq->product->name   ?? '-'); ?>

                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="form-group row">
                <label for="inv_name" class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3"><?php echo e(trans('form.registration.investigation.type_chk')); ?></label>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">

                    <ul class="type-ul">
                        <?php if($invn->personal_del == '1'): ?> <li><?php echo e(trans('form.registration.investigation.personal_del')); ?></li> <?php endif; ?>
                        <?php if($invn->company_del == '1'): ?> <li><?php echo e(trans('form.registration.investigation.company_del')); ?></li> <?php endif; ?>
                        <?php if($invn->company_del == '1'): ?>
                            <ul>
                                <?php if($invn->make_paste == '1'): ?> <li><?php echo e(trans('form.registration.investigation.make_paste')); ?></li> <?php endif; ?>
                                <?php if($invn->deliver_by_manager == '1'): ?> <li><?php echo e(trans('form.registration.investigation.delivery_company_manager')); ?></li> <?php endif; ?>
                            </ul>
                        <?php endif; ?>
                    </ul>

                </div>
            </div>
        </div>
        

        <?php if($isadminsm): ?>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="form-group row">
                <label for="inv_name" class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3"><?php echo e(trans('form.registration.investigation.inv_cost')); ?> :</label>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                    <?php echo e(trans('general.money_symbol')); ?><?php echo e($invn->inv_cost ?? '-'); ?><br/>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if(isAdmin() || isSM()): ?>
        
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="form-group row">
                <label for="inv_name" class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3"><?php echo e(trans('general.is_urgent')); ?></label>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">

                    <input name="is_urgent" id="is_urgent" type="checkbox" switch="bool" <?php echo e($invn->is_urgent == 1 ? 'checked' : ''); ?> onclick="changeToUrgent('<?php echo e($invn->id); ?>', this)"/>
                        <label class="mt-2" id="urgent_label" for="is_urgent" data-on-label="<?php echo e(trans('general.yes')); ?>" data-off-label="<?php echo e(trans('general.no')); ?>"> </label>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <?php if(!isClient()): ?>
    <hr />
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <h4 class="section-title mb-3 pb-2"><?php echo e(trans('form.investigators')); ?></h4>
            <?php if($assigned->isNotEmpty()): ?>
                <div class="row">
                    <?php $__currentLoopData = $assigned; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="card inv-card investigator-card" data-id="<?php echo e($assign->id); ?>">
                            <h5 class="card-header font-16 mt-0">
                                <?php echo e(ucwords($assign->investigator->user->name)); ?>


                                <?php if(isSM() || isAdmin()): ?>
                                <?php if(($assign->status == 'Report Accepted' || $assign->status == 'Completed With Findings' || ($assign->status != 'Report Accepted' && $assign->status != 'Cancel')) && $assign->status != 'Assigned' && $invn->status != 'Closed' && $assign->status != 'Final Report Submitted'): ?>
                                <div class="dropdown dropdown-topbar float-right">
                                    <a class="dt-btn-action btn inv-action-btn" href="#" role="button" id="invDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <?php echo e(trans('general.action')); ?> <i class="mdi mdi-chevron-down"></i>
                                    </a>

                                    <div class="dropdown-menu action-dd" aria-labelledby="invDropdownMenuLink">

                                        <?php if($assign->status == 'Completed With Findings' && $invn->status != 'Closed'): ?>

                                            <a onclick="adminActionOnReport('Report Accepted', 'admin', '<?php echo e($assign->id); ?>', '<?php echo e(trans('form.timeline_status.Report Accepted')); ?>')" class="dropdown-item" href="javascript:void(0)" ><?php echo e(trans('form.investigator_investigation_status.accept_report')); ?></a>

                                            <a onclick="adminActionOnDecline('Report Declined', 'admin', '<?php echo e($assign->investigator_id); ?>', '<?php echo e(trans('form.timeline_status.Report Declined')); ?>', '<?php echo e($assign->id); ?>')" class="dropdown-item" href="javascript:void(0)" ><?php echo e(trans('form.investigator_investigation_status.decline_report')); ?></a>
                                        
                                            <a onclick="adminActionOnDecline('Returned To Investigator', 'admin', '<?php echo e($assign->investigator_id); ?>', '<?php echo e(trans('form.timeline_status.Returned To Investigator')); ?>', '<?php echo e($assign->id); ?>')" class="dropdown-item" href="javascript:void(0)" ><?php echo e(trans('form.investigation_status.return_to_investigator')); ?></a>

                                        <?php endif; ?>
                                        <?php if($assign->status == 'Report Accepted' || $assign->status != 'Report Accepted' && $assign->status != 'Assigned' && $assign->status != 'Cancel' && ($assign->status != 'Completed With Findings' && $invn->status != 'Closed')): ?>
                                            <a onclick="adminActionOnReport('Cancel', 'admin', '<?php echo e($assign->id); ?>', '<?php echo e(trans('form.timeline_status.Cancel')); ?>')" class="dropdown-item" href="javascript:void(0)" ><?php echo e(trans('form.timeline_status.Cancel')); ?></a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if($assign->status == 'Assigned' && $assign->status != 'Cancel' && $assign->status != 'Completed With Findings' && $invn->status != 'Closed'): ?>
                                <div class="dropdown dropdown-topbar float-right">
                                    <a class="dt-btn-action btn inv-action-btn" href="#" role="button" id="invDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <?php echo e(trans('general.action')); ?> <i class="mdi mdi-chevron-down"></i>
                                    </a>
                                    <div class="dropdown-menu action-dd" aria-labelledby="invDropdownMenuLink">
                                        <?php if($assign->status == 'Assigned' && $assign->status != 'Cancel' && $assign->status != 'Completed With Findings' && $invn->status != 'Closed'): ?>

                                            <a onclick="adminActionOnReport('Cancel', 'admin', '<?php echo e($assign->id); ?>', '<?php echo e(trans('form.timeline_status.Cancel')); ?>')" class="dropdown-item" href="javascript:void(0)" ><?php echo e(trans('form.timeline_status.Cancel')); ?></a>
                                        <?php endif; ?>

                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php endif; ?>
                            </h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <?php
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
                                        ?>
                                        <?php echo e(trans('form.status')); ?> : <span class="status-text"><?php echo e($assignStatus); ?></span>
                                    </div>
                                    <div class="col-6">
                                        <?php echo e(trans('form.registration.investigator.company')); ?> : <?php echo e(!empty($assign->investigator->company) ? ucwords($assign->investigator->company) : '-'); ?></p>
                                    </div>
                                </div>

                                <form id="inv-approval-form-<?php echo e($assign->id); ?>" class="inv-form" action="<?php echo e(route('investigation.action-on-report')); ?>" method="post">

                                    <?php echo csrf_field(); ?>

                                    <input type="hidden" name="assignId" value="<?php echo e($assign->id); ?>">
                                    <?php if($assign->status == "Completed With Findings"
                                    || $assign->status == "Completed Without Findings"
                                    || $assign->status == "Report Accepted" || $assign->status == "Report Declined"
                                    || $assign->status == "Returned To Investigator" || $assign->status == "Final Report Submitted" || $assign->status == "Cancel" ): ?>
                                        <hr class="hr-inv">
                                        <div class="row">
                                            <!-- Show subject wise summary given by investigator-->
                                            <?php
                                                if($assign->status == "Report Accepted"){
                                                    $comSummary = !empty($assign->admin_report_subject_summary) ? json_decode($assign->admin_report_subject_summary, true) : [];
                                                }else{
                                                    $comSummary = !empty($assign->completion_subject_summary) ? json_decode($assign->completion_subject_summary, true) : [];
                                                }
                                            ?>
                                            <?php if(!empty($comSummary)): ?>
                                                <?php $__currentLoopData = $invn->subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="form-group col-md-12">
                                                        <label style="color:white !important;"><?php echo e(trans('form.investigation.write_summary_of') . ' ' . trans('form.registration.investigation.subject') . ' ' . $subject->first_name); ?> : </label>
                                                        <textarea class="form-control note" name="admin_summary[<?php echo e($subject->id); ?>]" <?php echo e(isSM() ? 'readonly' : ''); ?>><?php echo e(isset($comSummary[$subject->id]) ? $comSummary[$subject->id] : ''); ?></textarea>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>

                                            <div class="form-group col-md-12">
                                                <label style="color:white !important;"><?php echo e(trans('form.investigation.final_summary')); ?> : </label>
                                                <textarea class="form-control note" name="admin_final_summary" <?php echo e(isSM() ? 'readonly' : ''); ?>><?php echo e($assign->status == "Report Accepted" ? $assign->admin_report_final_summary : $assign->completion_summary); ?></textarea>
                                            </div>
                                            

                                            <?php if($assign->status == "Final Report Submitted"): ?>
                                            <!-- <div class="form-group col-md-12">
                                                <label style="color:white !important;"><?php echo e(trans('form.investigation.sm_final_summary')); ?> : </label>
                                                <textarea class="form-control note" name="sm_final_summary" readonly><?php echo e($assign->sm_final_summary); ?></textarea>
                                            </div> -->
                                            <?php endif; ?>
                                        </div>

                                        <?php if($invn->documents->isNotEmpty()): ?>
                                            <hr class="hr-inv">

                                            <?php
                                                $caseReports = [];
                                                $smCaseReports = [];
                                                $otherDocs = [];
                                                $docCost = 0;
                                            ?>

                                            <?php $__currentLoopData = $invn->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
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
                                                ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            <table class="table table-bordered doc-table">
                                                <?php if(!empty($caseReports)): ?>
                                                    <tr>
                                                        <td colspan="3">
                                                            <?php echo e(trans('form.documenttypes.case_reports')); ?>

                                                        </td>
                                                    </tr>

                                                    <?php $__currentLoopData = $caseReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cdocument): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr class="detail-tr">
                                                            <td>
                                                                <?php
                                                                    $docurl = '/investigation-documents/'.$cdocument->file_name;
                                                                ?>
                                                                <a class="doc-link" style="color:white" href="<?php echo e(URL::asset($docurl)); ?>" target="_blank"><?php echo e($cdocument->doc_name); ?></a>
                                                            </td>
                                                            <td class="text-center">
                                                                <a class="doc-link" style="color:white" href="<?php echo e(URL::asset($docurl)); ?>" target="_blank"><i class="fa fa-download"></i></a>
                                                            </td>
                                                            <?php if(isSM() || isAdmin()): ?>
                                                            <?php if($assign->status == 'Completed With Findings' && $invn->status != 'Closed'): ?>
                                                            <td class="text-center">

                                                                <input type="checkbox" class="otherdoc otherdoc-<?php echo e($assign->id); ?>" data-price="<?php echo e($cdocument->price); ?>" name="casereport[<?php echo e($cdocument->id); ?>]" value="<?php echo e($cdocument->id); ?>" checked >

                                                            </td>
                                                            <?php else: ?>
                                                            <input type="checkbox" class="otherdoc otherdoc-<?php echo e($assign->id); ?> d-none" data-price="<?php echo e($cdocument->price); ?>" name="casereport[<?php echo e($cdocument->id); ?>]" value="<?php echo e($cdocument->id); ?>" checked  class="d-none">
                                                            <?php endif; ?>
                                                            <?php endif; ?>
                                                        </tr>
                                                        <?php
                                                            $docCost = $docCost + $cdocument->price;
                                                        ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>

                                                <?php if(!empty($smCaseReports)): ?>
                                                    <tr>
                                                        <td colspan="3">
                                                            <?php echo e(trans('form.documenttypes.sm_case_reports')); ?>

                                                        </td>
                                                    </tr>
                                                    <?php $__currentLoopData = $smCaseReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cdocument): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr class="detail-tr">
                                                            <td>
                                                                <?php
                                                                    $docurl = '/investigation-documents/'.$cdocument->file_name;
                                                                ?>
                                                                <a class="doc-link" style="color:white" href="<?php echo e(URL::asset($docurl)); ?>" target="_blank"><?php echo e($cdocument->doc_name); ?></a>
                                                            </td>
                                                            <?php if(isSM() || isAdmin()): ?>
                                                            <?php if($assign->status == 'Completed With Findings' && $invn->status != 'Closed'): ?>
                                                            <td class="text-center">

                                                                <input type="checkbox" class="otherdoc otherdoc-<?php echo e($assign->id); ?>" name="casereport[<?php echo e($cdocument->id); ?>]" value="<?php echo e($cdocument->id); ?>" data-price="<?php echo e($cdocument->price); ?>" checked >

                                                            </td>
                                                            <?php else: ?>
                                                            <input type="checkbox" class="otherdoc otherdoc-<?php echo e($assign->id); ?> d-none" name="casereport[<?php echo e($cdocument->id); ?>]" value="<?php echo e($cdocument->id); ?>" checked data-price="<?php echo e($cdocument->price); ?>"  class="d-none">
                                                            <?php endif; ?>
                                                            <?php endif; ?>
                                                        </tr>
                                                        <?php
                                                            $docCost = $docCost + $cdocument->price;
                                                        ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>

                                                <?php if(!empty($otherDocs)): ?>
                                                    <tr>
                                                        <td colspan="3">
                                                            <?php echo e(trans('form.documenttypes.other_documents')); ?>

                                                        </td>
                                                    </tr>
                                                    <?php $__currentLoopData = $otherDocs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $odocument): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr class="detail-tr">
                                                            <td>
                                                                <?php
                                                                    $docurl = '/investigation-documents/'.$odocument->file_name;
                                                                ?>
                                                                <a class="doc-link" style="color:white" href="<?php echo e(URL::asset($docurl)); ?>" target="_blank"><?php echo e($odocument->doc_name); ?></a>
                                                            </td>
                                                            <td class="text-center">
                                                                <a class="doc-link" style="color:white" href="<?php echo e(URL::asset($docurl)); ?>" target="_blank"><i class="fa fa-download"></i></a>
                                                            </td>
                                                            <?php if(isSM() || isAdmin()): ?>
                                                            <?php if($assign->status == 'Completed With Findings' && $invn->status != 'Closed'): ?>
                                                            <td class="text-center">
                                                                <input type="checkbox" class="otherdoc otherdoc-<?php echo e($assign->id); ?>" name="otherdoc[<?php echo e($odocument->id); ?>]" value="<?php echo e($odocument->id); ?>" data-price="<?php echo e($odocument->price); ?>" checked >
                                                            </td>
                                                            <?php else: ?>
                                                            <input type="checkbox" class="otherdoc otherdoc-<?php echo e($assign->id); ?> d-none" name="otherdoc[<?php echo e($odocument->id); ?>]" value="<?php echo e($odocument->id); ?>" data-price="<?php echo e($odocument->price); ?>" checked >
                                                            <?php endif; ?>
                                                            <?php endif; ?>
                                                        </tr>
                                                        <?php
                                                            $docCost = $docCost + $odocument->price;
                                                        ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </table>

                                        <?php endif; ?>

                                        <hr class="hr-inv">

                                        
                                        <?php
                                            $invCost = \App\Helpers\AppHelper::calculateInvestigationCostForInvestigator($assign->investigation_id, $assign->investigator_id);

                                            $assignInvCost = \App\InvestigatorInvestigations::where('investigation_id', $assign->investigation_id)->where('investigator_id', $assign->investigator_id)->first();
                                        ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <?php echo e(trans('form.registration.investigation.inv_cost')); ?> : <?php echo e(trans('general.money_symbol')); ?>


                                                <?php
                                                    $invst_invoice = \App\Helpers\AppHelper::getInvoiceInvestigatorId($assign->investigation_id,$assign->investigator_id);

                                                    $invst_invoice_id = $invst_invoice['id'];
                                                    $invst_invoice_status = $invst_invoice['status'];
                                                ?>

                                                <?php if($assignInvCost->inv_cost == $invCost): ?>
                                                    <input type="hidden" name="inv_cost" value="<?php echo e($invCost); ?>">
                                                    <?php if($invst_invoice_id > 0 && $invst_invoice_status != 'paid'): ?>
                                                        <a href="#" class="invst_amount" style="color: #fff;" data-type="text" data-pk="<?php echo e($invst_invoice_id); ?>" data-title="הכנס סכום"><?php echo e($invCost); ?></a>
                                                        <span class="inv-cost-<?php echo e($assign->id); ?>" style= "display:none;" ><?php echo e($invCost); ?></span>
                                                    <?php else: ?>
                                                        <a href="#" class="invst_amount" style="color: #fff;" data-type="text" data-pk="<?php echo e(json_encode(['investigation_id' => $assign->investigation_id,'investigator_id' => $assign->investigator_id])); ?>" data-title="הכנס סכום"><?php echo e($invCost); ?></a>
                                                        <span class="inv-cost-<?php echo e($assign->id); ?>" style= "display:none;" ><?php echo e($invCost); ?></span>

                                                        <!-- <span class="invst_amount inv-cost-<?php echo e($assign->id); ?>" ><?php echo e($invCost); ?></span> -->
                                                    <?php endif; ?>
                                                <?php else: ?> 
                                                    <input type="hidden" name="inv_cost" value="<?php echo e($assignInvCost->inv_cost); ?>">
                                                    <?php if($invst_invoice_id > 0 && $invst_invoice_status != 'paid'): ?>
                                                        <a href="#" class="invst_amount" style="color: #fff;" data-type="text" data-pk="<?php echo e($invst_invoice_id); ?>" data-title="הכנס סכום"><?php echo e($assignInvCost->inv_cost); ?></a>

                                                        <span class="inv-cost-<?php echo e($assign->id); ?>" style= "display:none;"><?php echo e($assignInvCost->inv_cost); ?></span>
                                                    <?php else: ?>

                                                        <a href="#" class="invst_amount" style="color: #fff;" data-type="text" data-pk="<?php echo e(json_encode(['investigation_id' => $assign->investigation_id,'investigator_id' => $assign->investigator_id])); ?>" data-title="הכנס סכום"><?php echo e($assignInvCost->inv_cost); ?></a>

                                                        <span class="inv-cost-<?php echo e($assign->id); ?>" style= "display:none;"><?php echo e($assignInvCost->inv_cost); ?></span>

                                                        <!-- <span class="inv-cost-<?php echo e($assign->id); ?>"><?php echo e($assignInvCost->inv_cost); ?></span> -->
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                            </div>

                                            <div class="col-12">
                                                <?php echo e(trans('form.registration.investigation.doc_cost')); ?> : <?php echo e(trans('general.money_symbol')); ?><span class="doc-cost-<?php echo e($assign->id); ?>"></span>
                                                <input type="hidden" name="doc_cost" id="doc_cost-$<?php echo e($assign->id); ?>" value="">
                                            </div>
                                            <div class="col-12">
                                                <strong><?php echo e(trans('form.registration.investigation.total_cost')); ?> : <?php echo e(trans('general.money_symbol')); ?><span class="total-cost-<?php echo e($assign->id); ?>"></span> </strong>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </form>

                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <center>
                            <p><?php echo e(trans('form.investigation.not_found')); ?></p>
                        </center>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <h4 class="section-title mb-3 pb-2"><?php echo e(trans('form.delivery_boys')); ?></h4>
            
            <?php if($assignedDel->isNotEmpty()): ?>
                <div class="row">
                    <?php $__currentLoopData = $assignedDel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-xs-12 col-sm-12 col-md-4">
                            <div class="card inv-card deliveryboy-card" data-id="<?php echo e($assign->id); ?>">
                                <h5 class="card-header font-16 mt-0">
                                    <?php echo e(ucwords($assign->deliveryboy->user->name)); ?>


                                    <?php if(isSM() || isAdmin()): ?>
                                    <?php if($invn->status != 'Closed' && $assigned->isNotEmpty() && $assigned->contains('status', 'Report Accepted') && ($assign->status == 'Return To Center' || $assign->status != 'Cancel') && $assign->status != 'Assigned' && $invn->status != 'Closed' && $assign->status != 'Done And Delivered'): ?>
                                    <div class="dropdown dropdown-topbar float-right">
                                        <a class="dt-btn-action btn inv-action-btn" href="#" role="button" id="delDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?php echo e(trans('general.action')); ?> <i class="mdi mdi-chevron-down"></i>
                                        </a>

                                        <div class="dropdown-menu action-dd" aria-labelledby="delDropdownMenuLink">

                                            <?php if($invn->status != 'Closed' && $assigned->isNotEmpty() && $assigned->contains('status', 'Report Accepted') && ($assign->status == 'Return To Center' || $assign->status != 'Cancel')): ?>

                                                <?php if($assign->status != 'Done And Delivered' && $assign->status == 'Return To Center'): ?>
                                                    <a onclick="adminActionOnReportDel('Done And Delivered', 'admin', '<?php echo e($assign->deliveryboy_id); ?>', '<?php echo e(trans('form.timeline_status.Done And Delivered')); ?>')" class="dropdown-item" href="javascript:void(0)" ><?php echo e(trans('form.investigation_status.Delivered')); ?></a>
                                                <?php endif; ?>

                                                <?php if($assign->status != 'Done And Not Delivered' && $assign->status == 'Return To Center'): ?>
                                                    <a onclick="adminActionOnReportDel('Done And Not Delivered', 'admin', '<?php echo e($assign->deliveryboy_id); ?>', '<?php echo e(trans('form.timeline_status.Done And Not Delivered')); ?>')" class="dropdown-item" href="javascript:void(0)" ><?php echo e(trans('form.investigation_status.NotDelivered')); ?></a>
                                                <?php endif; ?>

                                                <?php if($assign->status != 'Done And Delivered' && $assign->status != 'Return To Center' && $assign->status != 'Cancel'): ?>
                                                <a onclick="adminActionOnReportDel('Cancel', 'admin', '<?php echo e($assign->deliveryboy_id); ?>', '<?php echo e(trans('form.timeline_status.Cancel')); ?>')" class="dropdown-item" href="javascript:void(0)" ><?php echo e(trans('form.timeline_status.Cancel')); ?></a>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <?php if($assign->status == 'Assigned' && $assign->status != 'Cancel' && $assign->status != 'Done And Delivered' && $invn->status != 'Closed'): ?>
                                    <div class="dropdown dropdown-topbar float-right">
                                        <a class="dt-btn-action btn inv-action-btn" href="#" role="button" id="invDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?php echo e(trans('general.action')); ?> <i class="mdi mdi-chevron-down"></i>
                                        </a>
                                        <div class="dropdown-menu action-dd" aria-labelledby="invDropdownMenuLink">
                                            <?php if($assign->status == 'Assigned' && $assign->status != 'Cancel' && $assign->status != 'Done And Delivered' && $invn->status != 'Closed'): ?>
                                                <a onclick="adminActionOnReportDel('Cancel', 'admin', '<?php echo e($assign->deliveryboy_id); ?>', '<?php echo e(trans('form.timeline_status.Cancel')); ?>')" class="dropdown-item" href="javascript:void(0)" ><?php echo e(trans('form.timeline_status.Cancel')); ?></a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </h5>
                                <div class="card-body">
                                    <div class="row">
                                        <?php
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
                                        ?>
                                        <div class="col-6">
                                            <?php echo e(trans('form.status')); ?> : <span class="status-text"><?php echo e($assignDelStatus); ?></span>
                                        </div>
                                        <div class="col-6">
                                            <?php echo e(trans('form.registration.investigator.company')); ?> : <?php echo e($assign->deliveryboy->company ?? '-'); ?></p>
                                        </div>
                                    </div>
                                    
                                    <form id="inv-approval-form-del-<?php echo e($assign->deliveryboy_id); ?>" action="<?php echo e(route('investigation.action-on-report-del')); ?>" method="post">

                                    <?php echo csrf_field(); ?>

                                    <input type="hidden" name="assignId" value="<?php echo e($assign->id); ?>">
                                    <?php if($assign->status == "Return To Center" || $assign->status == "Done And Delivered" || $assign->status == "Done And Not Delivered"): ?>
                                        <hr class="hr-inv">
                                        <div class="row">
                                            <!-- Show subject wise summary given by investigator-->
                                            <?php
                                                if($assign->status == "Report Accepted"){
                                                    $comSummary = !empty($assign->admin_report_subject_summary) ? json_decode($assign->admin_report_subject_summary, true) : [];
                                                }else{
                                                    $comSummary = !empty($assign->completion_subject_summary) ? json_decode($assign->completion_subject_summary, true) : [];
                                                }
                                            ?>
                                            <?php if(!empty($comSummary)): ?>
                                                <?php $__currentLoopData = $invn->subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="form-group col-md-12">
                                                        <label style="color:white !important;"><?php echo e(trans('form.investigation.write_summary_of') . ' ' . trans('form.registration.investigation.subject') . ' ' . $subject->first_name); ?> : </label>
                                                        <textarea class="form-control note" name="admin_summary[<?php echo e($subject->id); ?>]" <?php echo e(isSM() ? 'readonly' : ''); ?>><?php echo e(isset($comSummary[$subject->id]) ? $comSummary[$subject->id] : ''); ?></textarea>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>

                                            <div class="form-group col-md-12">
                                                <label style="color:white !important;"><?php echo e(trans('form.investigation.final_summary')); ?> : </label>
                                                <textarea class="form-control note" name="admin_final_summary" <?php echo e(isSM() ? 'readonly' : ''); ?>><?php echo e($assign->completion_summary); ?></textarea>
                                            </div>
                                        </div>
                                        
                                        <?php if($invn->documents->isNotEmpty()): ?>
                                            <hr class="hr-inv">

                                            <?php
                                                $caseReports = [];
                                                $otherDocs = [];
                                                $docCost = 0;
                                            ?>

                                            <?php $__currentLoopData = $invn->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                if($document->uploaded_by == Auth::id() || $document->uploaded_by == \App\Helpers\AppHelper::getUserIdFromDeliveryboyId($assign->deliveryboy_id)){
                                                    if($document->documenttype->name == 'Case Report'){
                                                        $caseReports[] = $document;
                                                    }else{
                                                        $otherDocs[] =  $document;
                                                    }
                                                }
                                                ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            <table class="table table-bordered doc-table">
                                                <?php if(!empty($caseReports)): ?>
                                                    <tr>
                                                        <td colspan="3">
                                                            <?php echo e(trans('form.documenttypes.case_reports')); ?>

                                                        </td>
                                                    </tr>
                                                    <?php $__currentLoopData = $caseReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cdocument): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr class="detail-tr">
                                                            <td>
                                                                <?php
                                                                    $docurl = '/investigation-documents/'.$cdocument->file_name;
                                                                ?>
                                                                <a class="doc-link" style="color:white" href="<?php echo e(URL::asset($docurl)); ?>" target="_blank"><?php echo e($cdocument->doc_name); ?></a>
                                                            </td>
                                                            <td class="text-center">
                                                                <a class="doc-link" style="color:white" href="<?php echo e(URL::asset($docurl)); ?>" target="_blank"><i class="fa fa-download"></i></a>
                                                            </td>
                                                            <?php if(isSM() || isAdmin()): ?>
                                                            <?php if($invn->status != 'Closed' && $assigned->isNotEmpty() && $assigned->contains('status', 'Report Accepted') && $assign->status == 'Return To Center'): ?>
                                                            <td class="text-center">

                                                                <input type="checkbox" class="otherdoc otherdoc-del otherdoc-del-<?php echo e($assign->id); ?>" data-price="<?php echo e($cdocument->price); ?>" id="otherdoc-del-<?php echo e($assign->id); ?>" name="casereport[<?php echo e($cdocument->id); ?>]" value="<?php echo e($cdocument->id); ?>" checked >

                                                            </td>
                                                            <?php else: ?>
                                                                <input type="checkbox" class="otherdoc otherdoc-del d-none otherdoc-del-<?php echo e($assign->id); ?>" data-price="<?php echo e($cdocument->price); ?>" id="otherdoc-del-<?php echo e($assign->id); ?>" name="casereport[<?php echo e($cdocument->id); ?>]" value="<?php echo e($cdocument->id); ?>" checked  class="d-none">
                                                            <?php endif; ?>
                                                            <?php endif; ?>
                                                        </tr>
                                                        <?php
                                                            $docCost = $docCost + $cdocument->price;
                                                        ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>

                                                <?php if(!empty($otherDocs)): ?>
                                                    <tr>
                                                        <td colspan="3">
                                                            <?php echo e(trans('form.documenttypes.other_documents')); ?>

                                                        </td>
                                                    </tr>
                                                    <?php $__currentLoopData = $otherDocs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $odocument): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr class="detail-tr">
                                                            <td>
                                                                <?php
                                                                    $docurl = '/investigation-documents/'.$odocument->file_name;
                                                                ?>
                                                                <a class="doc-link" style="color:white" href="<?php echo e(URL::asset($docurl)); ?>" target="_blank"><?php echo e($odocument->doc_name); ?></a>
                                                            </td>
                                                            <td class="text-center">
                                                                <a class="doc-link" style="color:white" href="<?php echo e(URL::asset($docurl)); ?>" target="_blank"><i class="fa fa-download"></i></a>
                                                            </td>
                                                            <?php if(isSM() || isAdmin()): ?>
                                                            <?php if($invn->status != 'Closed' && $assigned->isNotEmpty() && $assigned->contains('status', 'Report Accepted') && $assign->status == 'Return To Center'): ?>
                                                            <td class="text-center">
                                                                <input type="checkbox" class="otherdoc otherdoc-del otherdoc-del-<?php echo e($assign->id); ?>" name="otherdoc[<?php echo e($odocument->id); ?>]" value="<?php echo e($odocument->id); ?>" id="otherdoc-del-<?php echo e($assign->id); ?>" data-price="<?php echo e($odocument->price); ?>" checked >
                                                            </td>
                                                            <?php else: ?>
                                                            <input type="checkbox" class="otherdoc otherdoc-del d-none otherdoc-del-<?php echo e($assign->id); ?>" name="otherdoc[<?php echo e($odocument->id); ?>]" value="<?php echo e($odocument->id); ?>" id="otherdoc-del-<?php echo e($assign->id); ?>" data-price="<?php echo e($odocument->price); ?>" checked >
                                                            <?php endif; ?>
                                                            <?php endif; ?>
                                                        </tr>
                                                        <?php
                                                            $docCost = $docCost + $odocument->price;
                                                        ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </table>

                                        <?php endif; ?>

                                        <hr class="hr-inv">

                                        
                                        <?php
                                            $invCost = \App\Helpers\AppHelper::calculateInvestigationCostForDeliveryboy($assign->investigation_id, $assign->deliveryboy_id);

                                            $assignInvCost = \App\DeliveryboyInvestigations::where('investigation_id', $assign->investigation_id)->where('deliveryboy_id', $assign->deliveryboy_id)->first();

                                        ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <?php echo e(trans('form.products_form.del_cost')); ?> : <?php echo e(trans('general.money_symbol')); ?>


                                                <?php
                                                    $delboy_invoice = \App\Helpers\AppHelper::getInvoiceIDelboyId($assign->investigation_id,$assign->deliveryboy_id);

                                                    $delboy_invoice_id = $delboy_invoice['id'];
                                                    $delboy_invoice_status = $delboy_invoice['status'];
                                                ?>

                                                <?php if($invCost == $assignInvCost->inv_cost): ?>
                                                    <input type="hidden" name="inv_cost" value="<?php echo e($invCost); ?>">


                                                    <?php if($delboy_invoice_id > 0 && $delboy_invoice_status != 'paid'): ?>
                                                        <a href="#" class="delboy_amount" style="color: #fff;" data-type="text" data-pk="<?php echo e($delboy_invoice_id); ?>" data-title="הכנס סכום"><?php echo e($invCost); ?></a>
                                                        
                                                        <span class="inv-cost-del-<?php echo e($assign->id); ?>" style= "display:none;"><?php echo e($invCost); ?></span>
                                                    <?php else: ?>
                                                        <a href="#" class="delboy_amount" style="color: #fff;" data-type="text" data-pk="<?php echo e(json_encode(['investigation_id' => $assign->investigation_id,'deliveryboy_id' => $assign->deliveryboy_id])); ?>" data-title="הכנס סכום"><?php echo e($invCost); ?></a>
                                                        
                                                        <span class="inv-cost-del-<?php echo e($assign->id); ?>" style= "display:none;"><?php echo e($invCost); ?></span>
                                                        <!-- <span class="inv-cost-del-<?php echo e($assign->id); ?>"><?php echo e($invCost); ?></span> -->
                                                    <?php endif; ?>

                                                <?php else: ?> 
                                                    <input type="hidden" name="inv_cost" value="<?php echo e($assignInvCost->inv_cost); ?>">

                                                    <?php if($delboy_invoice_id > 0 && $delboy_invoice_status != 'paid'): ?>
                                                        <a href="#" class="delboy_amount" style="color: #fff;" data-type="text" data-pk="<?php echo e($delboy_invoice_id); ?>" data-title="הכנס סכום"><?php echo e($assignInvCost->inv_cost); ?></a>

                                                        <span class="inv-cost-del-<?php echo e($assign->id); ?>" style= "display:none;"><?php echo e($assignInvCost->inv_cost); ?></span>
                                                    <?php else: ?>
                                                        <a href="#" class="delboy_amount" style="color: #fff;" data-type="text" data-pk="<?php echo e(json_encode(['investigation_id' => $assign->investigation_id,'deliveryboy_id' => $assign->deliveryboy_id])); ?>" data-title="הכנס סכום"><?php echo e($assignInvCost->inv_cost); ?></a>

                                                        <span class="inv-cost-del-<?php echo e($assign->id); ?>" style= "display:none;"><?php echo e($assignInvCost->inv_cost); ?></span>

                                                        <!-- <span class="inv-cost-del-<?php echo e($assign->id); ?>"><?php echo e($assignInvCost->inv_cost); ?></span> -->
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-12">
                                                <?php echo e(trans('form.registration.investigation.doc_cost')); ?> : <?php echo e(trans('general.money_symbol')); ?><span class="doc-cost-del-<?php echo e($assign->id); ?>"></span>
                                                <input type="hidden" name="doc_cost" id="doc_cost_del-<?php echo e($assign->id); ?>" value="">
                                            </div>
                                            <div class="col-12">
                                                <strong><?php echo e(trans('form.registration.investigation.total_cost')); ?> : <?php echo e(trans('general.money_symbol')); ?><span class="total-cost-del-<?php echo e($assign->id); ?>"></span> </strong>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </form>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <center>
                            <p><?php echo e(trans('form.investigation.not_found_del')); ?></p>
                        </center>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    

</div><?php /**PATH /var/www/html/uvda/resources/views/investigation/partials/basic.blade.php ENDPATH**/ ?>