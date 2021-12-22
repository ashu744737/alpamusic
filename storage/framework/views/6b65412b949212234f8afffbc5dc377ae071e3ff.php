<?php $__env->startSection('title'); ?> <?php echo e(trans('form.registration.investigation.investigation_details')); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('headerCss'); ?>
<!-- headerCss -->
<!-- DataTables -->
<link href="<?php echo e(URL::asset('/libs/datatables/datatables.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(URL::asset('/libs/bootstrap-editable/bootstrap-editable.min.css')); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo e(URL::asset('/libs/dropzone/dropzone.min.css')); ?>" rel="stylesheet" type="text/css" /></link>
<link href="<?php echo e(URL::asset('/libs/rwd-table/rwd-table.min.css')); ?>" rel="stylesheet" type="text/css" /></link>
<link href="<?php echo e(URL::asset('/libs/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(URL::asset('/css/custom.css')); ?>" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo e(URL::asset('/libs/sweetalert2/sweetalert2.min.css')); ?>">
<link href="<?php echo e(URL::asset('/libs/magnific-popup/magnific-popup.min.css')); ?>" rel="stylesheet" type="text/css" /> 
<style>
    .btn-toolbar{
        display: none !important;
    }

    .add-label{
        white-space: initial !important;
    }
    #investigator_list{
        overflow-x: auto;
    }
    .investigator-wrapper{
        border-bottom: 1px solid #eee;
        padding: 10px;
    }
    .investigator-wrapper:last-child{
        border-bottom: none;
    }
    .investigator-wrapper:hover{
        background: #eee;
        cursor: pointer;
    }
    .investigator-wrapper p{
        margin-bottom: 0;
    }
    .investigator-wrapper .details-wrapper .name{
        font-weight: bold;
    }
    .investigator-wrapper .special p{
        width: fit-content;
        padding: 5px 15px;
        border-radius: 20px;
        margin-top: 6px;
        color: #fff;
        background-color: #105C8D;
        border-color: #13496D;
    }
    #search{
        border-left: none;
    }
    #search:focus{
        border-color: #ced4da;
    }
    #basic-addon1{
        background: none;
        border-right: none;
    }
    .assign-investigator{
        padding: 20px 10px;
        background: #13496D;
        -webkit-box-shadow: 5px 5px 15px -7px rgba(0,0,0,0.5);
        -moz-box-shadow: 5px 5px 15px -7px rgba(0,0,0,0.5);
        box-shadow: 5px 5px 15px -7px rgba(0,0,0,0.5);
        color: white;
    }
    .assign-investigator .basic-details{
        padding: 0px 10px;
    }
    .assign-investigator .basic-details .change-investigator{
        cursor: pointer;
        position: absolute;
        top: 15px;
        right: 20px;
        padding: 2px 6px;
        border-radius: 50%;
        background: white;
        height: 30px;
        width: 30px;
        color: #13496D;
        font-size: 17px;
    }
    .assign-investigator hr{
        border-color: white;
    }
    .assign-investigator .personal-details{
        padding: 0px 10px 10px 10px;
    }
    .assign-investigator .personal-details span{
        display: inline-flex;
    }
    .assign-investigator .personal-details span i{
        margin: 5px 5px 5px 0px
    }
    .assign-investigator .personal-details span p{
        margin: 0;
    }

    .action-dd{
        right: 0 !important;
        left: auto !important;
    }

    .type-ul{
        padding-left: 12px !important;
    }

    .inv-card{
        color: #fff;
        border: 1px solid #fff;
        border-radius: 0px;
    }

    .inv-card .card-header{
        background-color: #13496D;
        border-bottom: 1px solid #fff;
    }
    .inv-card .card-body{
        background-color: #13496D;
        font-size: 12px;
    }

    .inv-action-btn{
        background: transparent;
        padding: 0;
        border: none;
        color: #fff;
    }

    .inv-action-btn:hover{
        color: #fff;
    }

    .inv-action-btn i{
        color: #fff;
    }

    .check-formraw{
        border-bottom: 1px solid #e9ecef;
    }

    .hs-fields{
        border-bottom: 1px solid #e9ecef;
    }

    .hr-inv{
        margin-top: 0;
        border-color: black;
    }

    .status-text{
        color: #f5b225;
    }

    .doc-table td{
        color: white;
        font-weight: 500;
    }

    .doc-table .detail-tr td{
        font-size: 10px;
        font-weight: normal;
    }

    .doc-link:hover{
        text-decoration: underline !important;
    }
    #is_urgent:checked + label {
        background-color: #ec536c;
    }
    #is_urgent + label {
        background-color: #58db83;
    }
    @media (max-width: 575px) {
        .action-dd {
            left: 0 !important;
            right: auto !important;
        }
    }

    .invst_amount,.invst_amount .editable-click, a.invst_amount, a.invst_amount:hover,
    .delboy_amount,.delboy_amount .editable-click, a.delboy_amount, a.delboy_amount:hover{
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        border-bottom: 1px dashed #fff !important;
        width: 80px;
        display: inline-block;
    }
</style>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="col-sm-6">
    <div class="page-title-box">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?php echo e(route('investigations')); ?>"><?php echo e(trans('form.investigations')); ?></a></li>
            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo e(trans('general.view')); ?></a></li>
        </ol>
    </div>
</div>

<!-- content -->

<?php echo $__env->make('layouts.partials.session-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <!-- end page title -->
<?php $isadminsm=0; //don't chage this if change then check ovwerall $isadminsm used in code
if (isAdmin() || isSM())
{$isadminsm=1;}
?>
<div class="row">
    <div class="col-12">
        <div class="card investogators_Details">
            <div class="card-body">

                <div class="row">
                    <div class="col-12 col-sm-5 col-xs-12">
                        <h4 class="card-title pb-4"><?php echo e(trans('form.registration.investigation.investigation_details')); ?>

                            <?php if(!empty($invn->status)): ?>
                                <?php $statusbadge='';
                                    if($invn->status=='Open')
                                    $statusbadge='warning';
                                    else if($invn->status=='Pending Approval')
                                    $statusbadge='warning';
                                    else if($invn->status=='Assigned')
                                    $statusbadge='dark';
                                    else if($invn->status=='Approved')
                                    $statusbadge='success';
                                    else if($invn->status=='Declined')
                                    $statusbadge='danger';
                                    else
                                        $statusbadge='primary';
                                ?>
                                <?php if(isClient()): ?>
                                    <?php $status = trans('form.timeline_status.In Progress');
                                    if ($invn->status == 'Pending Approval') {
                                        $status = trans('form.timeline_status.Pending Approval');
                                    } else if ($invn->status == 'Declined') {
                                        $status = trans('form.timeline_status.Declined');
                                    } else if ($invn->status == 'Investigation Started') {
                                        $status = trans('form.timeline_status.Investigation Started');
                                    } else if ($invn->status == 'Closed') {
                                        $status = trans('form.timeline_status.Closed');
                                    }
                                    ?>
                                    <span class="badge dt-badge badge-<?php echo e($statusbadge); ?>"> <?php echo e($status); ?> </span>
                                <?php else: ?>
                                <span class="badge dt-badge badge-<?php echo e($statusbadge); ?>"> <?php echo e(trans('form.investigation_status.'.str_replace(" ","",$invn->status))); ?> </span>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if((isClient() && $invn->status == 'Pending Approval') || isAdmin() || isSM()): ?>
                            <a href="<?php echo e(route('investigation.edit', [Crypt::encrypt($invn->id)])); ?>"  title="<?php echo e(trans('general.edit')); ?>">
                            <span class="badge dt-badge badge-primary"> <i class="fas fa-edit"></i> </span>  </a>
                            <?php endif; ?>
                            <?php if($invn->is_urgent): ?>
                                <span class="badge dt-badge badge-danger"> <?php echo e(trans('general.Urgent')); ?></span>
                            <?php endif; ?>
                        </h4>
                    </div>


                    <!-- Actions for Admin -->
                    <?php if((isAdmin() || isSM()) && $invn->status != 'Closed'): ?>
                        <div class="actiondropdown col-12 col-sm-7 col-xs-12 text-sm-right">
                            <div class="dropdown dropdown-topbar d-inline-block">
                                <a class="btn btn-primary" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo e(trans('general.action')); ?> <i class="mdi mdi-chevron-down"></i>
                                </a>

                                <div class="dropdown-menu action-dd" aria-labelledby="dropdownMenuLink">
                                    <?php if(check_perm('investigation_approve') && $invn->status != 'Closed' && ($invn->status == 'Pending Approval' || $invn->status == 'Approved' || $invn->status == 'Declined')): ?>
                                        <?php if($invn->status != 'Approved' && $assigned->isEmpty() && $assignedDel->isEmpty()): ?>
                                            <a onclick="approveconfirm(<?php echo e($invn->id); ?>)" class="dropdown-item" href="javascript:void(0);"><?php echo e(trans('general.approve')); ?></a>
                                        <?php endif; ?>
                                        <?php if($invn->status != 'Declined' && $assigned->isEmpty() && $assignedDel->isEmpty()): ?>
                                            <a onclick="opendeclineModal('decline_model','decline_data',<?php echo e($invn->id); ?>)" class="dropdown-item action-decline" data-toggle="modal"  href="javascript:void(0);"><?php echo e(trans('general.decline')); ?></a>
                                        <?php endif; ?>
                                        <?php if($invn->status != 'Approved' || $invn->status != 'Declined'): ?>
                                            <div class="dropdown-divider"></div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if(check_perm('investigation_assign') && $invn->status != 'Closed'): ?>
                                        <?php if(( $invn->status != 'Pending Approval' && $invn->status != 'Declined' ) && ( $assigned->isEmpty() || ( $assigned->isNotEmpty() && !$assigned->contains('status', 'Report Accepted') && !$assigned->contains('status', 'Final Report Submitted') ) )): ?>
                                            <a href="<?php echo e(route('investigation.show-search-investigators', [Crypt::encrypt($invn->id)])); ?>" class="dropdown-item"><?php echo e(trans('form.investigation.assign_investigator')); ?></a>
                                        <?php endif; ?>
                                        <?php if($typeofInq->product->is_delivery == '1' && $assigned->isNotEmpty() && $assigned->contains('status', 'Report Accepted') && ( $assignedDel->isEmpty() || ($assignedDel->isNotEmpty() && !$assignedDel->contains('status', 'Done And Delivered')) )): ?>
                                            <a href="<?php echo e(route('investigation.show-search-deliveryboys', [Crypt::encrypt($invn->id)])); ?>" class="dropdown-item"><?php echo e(trans('form.investigation.assign_deliveryboy')); ?></a>
                                            <div class="dropdown-divider"></div>
                                            <a href="<?php echo e(route('investigation.show-search-investigators', [Crypt::encrypt($invn->id)])); ?>" class="dropdown-item"><?php echo e(trans('form.investigation.assign_investigator')); ?></a>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if($invn->status != 'Closed' && $invn->status != 'Send To Client' && $assigned->isNotEmpty() && $assigned->contains('status', 'Report Accepted')): ?>
                                        <?php if( ($typeofInq->product->is_delivery == '1' && $assignedDel->isNotEmpty() && $assignedDel->contains('status', 'Done And Delivered')) || ($typeofInq->product->is_delivery == '0' || empty($typeofInq->product->is_delivery))): ?>
                                            <a onclick="smReportEvent()" class="dropdown-item" href="javascript:void(0)" ><?php echo e(trans('form.investigation_status.SubmitFinalReport')); ?></a>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if($invn->status == 'Writing Report' && $invn->status != 'Closed' && $invn->status != 'Finalizing Report' && $invn->status != 'Printing' && $invn->status != 'Waiting For Final Approval' && $invn->status != 'Send To Client' && $assigned->isNotEmpty() && $assigned->contains('status', 'Final Report Submitted')): ?>
                                        <?php if( ($typeofInq->product->is_delivery == '1' && $assignedDel->isNotEmpty() && $assignedDel->contains('status', 'Done And Delivered')) || ($typeofInq->product->is_delivery == '0' || empty($typeofInq->product->is_delivery))): ?>
                                            <?php $transStatus = trans('form.timeline_status.Finalizing Report') ?>
                                            <a onclick="changeAssignmentStatus('Finalizing Report', 'admin', '<?php echo e($transStatus); ?>')" class="dropdown-item" href="javascript:void(0)" ><?php echo e(trans('form.investigation_status.FinalizingReport')); ?></a>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if($invn->status == 'Finalizing Report' && $assigned->isNotEmpty() && $assigned->contains('status', 'Final Report Submitted')): ?>
                                        <?php if( ($typeofInq->product->is_delivery == '1' && $assignedDel->isNotEmpty() && $assignedDel->contains('status', 'Done And Delivered')) || ($typeofInq->product->is_delivery == '0' || empty($typeofInq->product->is_delivery))): ?>
                                            <?php $transStatus = trans('form.timeline_status.Printing') ?>
                                            <a onclick="changeAssignmentStatus('Printing', 'admin', '<?php echo e($transStatus); ?>')" class="dropdown-item" href="javascript:void(0)" ><?php echo e(trans('form.investigation_status.Printing')); ?></a>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if($invn->status == 'Printing' && $assigned->isNotEmpty() && $assigned->contains('status', 'Final Report Submitted')): ?>
                                        <?php if( ($typeofInq->product->is_delivery == '1' && $assignedDel->isNotEmpty() && $assignedDel->contains('status', 'Done And Delivered')) || ($typeofInq->product->is_delivery == '0' || empty($typeofInq->product->is_delivery))): ?>
                                            <?php $transStatus = trans('form.timeline_status.Waiting For Final Approval') ?>
                                            <a onclick="changeAssignmentStatus('Waiting For Final Approval', 'admin', '<?php echo e($transStatus); ?>')" class="dropdown-item" href="javascript:void(0)" ><?php echo e(trans('form.investigation_status.WaitingForFinalApproval')); ?></a>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if($invn->status == 'Waiting For Final Approval' && $assigned->isNotEmpty() && $assigned->contains('status', 'Final Report Submitted')): ?>
                                        <?php if( ($typeofInq->product->is_delivery == '1' && $assignedDel->isNotEmpty() && $assignedDel->contains('status', 'Done And Delivered')) || ($typeofInq->product->is_delivery == '0' || empty($typeofInq->product->is_delivery))): ?>
                                            <?php $transStatus = trans('form.timeline_status.Send To Client') ?>
                                            <a onclick="changeAssignmentStatus('Send To Client', 'admin', '<?php echo e($transStatus); ?>')" class="dropdown-item" href="javascript:void(0)" ><?php echo e(trans('form.investigation_status.SendToClient')); ?></a>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if($invn->status == 'Send To Client' && $assigned->isNotEmpty() && $assigned->contains('status', 'Final Report Submitted')): ?>
                                        <?php if( ($typeofInq->product->is_delivery == '1' && $assignedDel->isNotEmpty() && $assignedDel->contains('status', 'Done And Delivered')) || ($typeofInq->product->is_delivery == '0' || empty($typeofInq->product->is_delivery))): ?>
                                            <?php $transStatus = trans('form.timeline_status.Closed') ?>
                                            <a onclick="changeAssignmentStatus('Closed', 'admin', '<?php echo e($transStatus); ?>')" class="dropdown-item" href="javascript:void(0)" ><?php echo e(trans('form.investigation_status.Closed')); ?></a>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if($assigned->isNotEmpty() && !$assigned->contains('status', 'Report Accepted')): ?>
                                        <div class="dropdown-divider"></div>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="loading col-12 col-sm-7 col-xs-12 text-sm-right d-none"><h4><span class="badge badge-primary"><?php echo e(trans('general.updating')); ?></span></h4></div>

                    <div id="decline_model" class="modal fade bs-example-modal-center  bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"></div>
                    
                    <!-- Investigator Report Decline Model -->
                    <div id="decline-modal" class="modal fade bs-example-modal-center  bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">                        
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0"><?php echo e(trans('form.timeline.decline_reason')); ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                    <form class="reportdeclineform" id="reportdeclineform">
                                        <?php echo csrf_field(); ?>  
                                        <div class="row">
                                        <div class="col-md-12 col-lg-12 col-xl-12">
                                            <div class="form-group">
                                            <textarea rows="7" id="decline_reason" name="decline_reason" type="textarea" placeholder="<?php echo e(trans('form.timeline.decline_reason')); ?>" class="form-control" ></textarea>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                    <div class="col-md-12 col-lg-12 col-xl-12 ">
                                        <div class="form-group text-center text-xs-center">
                                        <button id="reportDecline" type="button" onclick="" class="btn btn-danger waves-effect" data-status="" data-performer="" data-entityId="" data-textstatus="" data-assignid=""><?php echo e(trans('general.decline')); ?></button>
                                        <button type="button" data-dismiss="modal"  class="btn btn-secondary waves-effect"><?php echo e(trans('general.cancel')); ?></button>
                                        </div>     
                                            
                                    </div>
                                    </div>

                                    </form>      
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                    </div>

                    <?php if(isInvestigator()): ?>
                        <?php if($assigned->isNotEmpty()): ?>
                            <?php if($assigned->first()->status == 'Report Declined' || $assigned->first()->status == 'Returned To Investigator'): ?>
                                <?php if(!is_null($assigned->first()->decline_by)): ?>
                    <div id="decline-reason-modal" class="modal fade bs-example-modal-center  bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">                        
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0"><?php echo e(trans('form.timeline.decline_reason')); ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                        <div class="row">
                                        <div class="col-md-12 col-lg-12 col-xl-12">
                                            <div class="form-group">
                                            <textarea rows="7" id="decline_reason" name="decline_reason" type="textarea" placeholder="<?php echo e(trans('form.timeline.decline_reason')); ?>" class="form-control" readonly><?php echo e($assigned->first()->decline_reason ?? trans('general.no_reason')); ?></textarea>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                    <div class="col-md-12 col-lg-12 col-xl-12 ">
                                        <div class="form-group text-center text-xs-center">
                                        <button type="button" data-dismiss="modal"  class="btn btn-secondary waves-effect"><?php echo e(trans('general.cancel')); ?></button>
                                        </div>     
                                    </div>
                                    </div>     
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                    <?php endif; ?>
                    <?php endif; ?>
                    <!-- Actions for Admin-->

                    <!-- Actions for SM -->
                    <!-- <?php if(isSM()): ?>
                        <div class="actiondropdown col-12 col-sm-8 col-xs-12 text-sm-right">
                            <div class="dropdown dropdown-topbar d-inline-block">
                                <a class="btn btn-primary" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo e(trans('general.action')); ?> <i class="mdi mdi-chevron-down"></i>
                                </a>

                                <div class="dropdown-menu action-dd" aria-labelledby="dropdownMenuLink">

                                    <?php if($invn->status != 'Closed' && $assigned->isNotEmpty() && $assigned->contains('status', 'Report Accepted')): ?>
                                        <?php if( ($typeofInq->product->is_delivery == '1' && $assignedDel->isNotEmpty() && $assignedDel->contains('status', 'Done And Delivered')) || ($typeofInq->product->is_delivery == '0' || empty($typeofInq->product->is_delivery))): ?>
                                            <a onclick="smReportEvent()" class="dropdown-item" href="javascript:void(0)" ><?php echo e(trans('form.investigation_status.SubmitFinalReport')); ?></a>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    <?php endif; ?> -->
                    <!-- Actions for SM -->

                    <!-- Actions for Investigators -->
                    <?php if(isInvestigator() && $assigned->isNotEmpty()): ?>
                        <?php if(($assigned->first()->status != 'Final Report Submitted' && $assigned->first()->status != 'Completed With Findings' && $assigned->first()->status != 'Report Accepted')): ?>
                        <div class="actiondropdown col-12 col-sm-7 col-xs-12 text-sm-right">
                            <?php if($assigned->isNotEmpty()): ?>
                                <?php if($assigned->first()->status == 'Report Declined' || $assigned->first()->status == 'Returned To Investigator'): ?>
                                    <?php if(!is_null($assigned->first()->decline_by)): ?>
                                        <button type="button" class="btn btn-warning w-md waves-effect waves-light" id="showDeclineReason">
                                            <?php echo e(trans('form.timeline.decline_reason')); ?>

                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                            <div class="dropdown dropdown-topbar d-inline-block">
                                <a class="btn btn-primary" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo e(trans('general.action')); ?> <i class="mdi mdi-chevron-down"></i>
                                </a>

                                <div class="dropdown-menu action-dd" aria-labelledby="dropdownMenuLink">
                                    <?php
                                        $invAssigned = $assigned->isNotEmpty() ? $assigned->firstWhere('investigator.user_id', Auth::id()) : null;
                                    ?>

                                    <?php if( (!$assigned->contains('status', 'Report Accepted') && !$assigned->contains('status', 'Final Report Submitted') ) && $invAssigned && $invAssigned->status != 'Investigation Declined' && $invAssigned->status != 'Completed Without Findings' && $invAssigned->status != 'Completed With Findings' && $invn->status != 'Closed' && $invAssigned->status != 'Cancel'): ?>

                                        <?php if($invAssigned->status == 'Assigned'): ?>
                                        <a onclick="showAcceptDeclineModal()" class="dropdown-item" href="javascript:void(0)" ><?php echo e(trans('form.investigation_status.InvestigationAcceptDecline')); ?></a>
                                        <?php endif; ?>

                                        <?php if($invAssigned->status != 'Assigned'): ?>
                                        <a onclick="finishAssignmentEvent()" class="dropdown-item" href="javascript:void(0)" ><?php echo e(trans('form.investigator_investigation_status.complete')); ?></a>
                                        <?php endif; ?>

                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <!-- Actions for Investigators -->

                    <!-- Actions for Delivery Boy -->
                    <?php
                        $delAssigned = $assignedDel->isNotEmpty() ? $assignedDel->firstWhere('deliveryboy.user_id', Auth::id()) : null;
                    ?>
                    <?php if(isDeliveryboy() && $assignedDel->isNotEmpty()): ?>
                        <?php if($delAssigned->status != 'Return To Center' && $delAssigned->status != 'Done And Not Delivered' && $invn->status != 'Closed' && $delAssigned->status != 'Cancel'): ?>
                        <div class="actiondropdown col-12 col-sm-7 col-xs-12 text-sm-right">
                            <div class="dropdown dropdown-topbar d-inline-block">
                                <a class="btn btn-primary" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo e(trans('general.action')); ?> <i class="mdi mdi-chevron-down"></i>
                                </a>

                                <div class="dropdown-menu action-dd" aria-labelledby="dropdownMenuLink">
                                    <?php if(!is_null($delAssigned) && $delAssigned->status != 'Done And Not Delivered' && $invn->status != 'Closed' && $delAssigned->status != 'Cancel'): ?>

                                        <?php if($delAssigned && $delAssigned->status == 'Assigned'): ?>
                                            <a onclick="showDelAcceptDeclineModal()" class="dropdown-item" href="javascript:void(0)" ><?php echo e(trans('form.investigation_status.InvestigationAcceptDecline')); ?></a>
                                        <?php endif; ?>

                                        <?php if($delAssigned && $delAssigned->status == 'Accepted'): ?>
                                            <a onclick="finishAssignmentEventDel()" class="dropdown-item" href="javascript:void(0)" ><?php echo e(trans('form.investigator_investigation_status.complete')); ?></a>
                                        <?php endif; ?>

                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <!-- Actions for Delivery Boy -->

                </div>

                <div class="row">
                    <div class="col-12">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-2" role="tablist">
                            <?php if(!isInvestigator() && !isDeliveryboy()): ?>
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#basic_details" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                    <span class="d-none d-sm-block"><?php echo e(trans('form.registration.investigation.basic_details')); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php $indeltabactive='';
                            if(isInvestigator() || isDeliveryboy())
                            {$indeltabactive='active';}?>
                            <li class="nav-item">
                                <a class="nav-link  <?php echo e($indeltabactive); ?>" data-toggle="tab" href="#subjects" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-file-alt"></i></span>
                                    <span class="d-none d-sm-block"><?php echo e(trans('form.registration.investigation.subjects')); ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#documents" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-file-alt"></i></span>
                                    <span class="d-none d-sm-block"><?php echo e(trans('form.registration.investigation.documents')); ?></span>
                                </a>
                            </li>
                            <?php if(!isClient()): ?>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#timeline" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-chart-line"></i></span>
                                    <span class="d-none d-sm-block"><?php echo e(trans('form.registration.investigation.time_line')); ?></span>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <?php if(!isInvestigator() && !isDeliveryboy()): ?>
                            <!-- BASIC DETAILS -->
                            <?php echo $__env->make('investigation.partials.basic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php endif; ?>

                            <!-- Subjects -->
                            <?php echo $__env->make('investigation.partials.subject', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                            <!-- DOCUMENTS -->
                            <?php echo $__env->make('investigation.partials.document', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                            <!-- TIMELINE -->
                            <?php echo $__env->make('investigation.partials.timeline', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                            <!-- HISTORY -->
                           

                        </div>


                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- end col -->
</div>
<!-- end row -->

<!-- Complete Investigation Modal for Investigator -->
<div class="modal fade bd-example-modal-lg" id="finishAssignmentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo e(trans('form.investigator_investigation_status.complete_inv')); ?> </h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form class="add_mobile" id="completion_form" novalidate="novalidate" method="post" action="<?php echo e(route('investigation.complete-investigation')); ?>">

                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="invnid" id="invnid" value="<?php echo e($invn->id); ?>"/>
                    <input type="hidden" name="invrid" id="invrid" value="<?php echo e(Auth::id()); ?>"/>

                    <div class="form-row check-formraw mb-2">












                        <div class="form-group col-md-6">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="complete_without_findings" name="complete_type">
                                <label class="custom-control-label" for="complete_without_findings"><?php echo e(trans('form.investigator_investigation_status.complete_without_findings')); ?></label>
                            </div>
                        </div>

                    </div>

                    <div class="form-row with-finding-fields hs-fields py-2">
                        <?php $__currentLoopData = $invn->subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-group col-md-12">
                                <label><?php echo e(trans('form.investigation.write_summary_of') . ' ' . trans('form.registration.investigation.subject') . ' ' . $subject->first_name); ?> : </label>
                                <textarea class="form-control note" name="summary[<?php echo e($subject->id); ?>]" placeholder="<?php echo e(trans('form.investigation.write_note_sub')); ?>" ></textarea>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="form-row with-finding-fields">
                        <div class="form-group col-md-12 mt-2">
                            <label><?php echo e(trans('form.investigation.final_summary')); ?> : </label>
                            <textarea class="form-control note" name="final_summary" placeholder="<?php echo e(trans('form.investigation.write_final_summary')); ?>" ></textarea>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary completeButton"><?php echo e(trans('form.investigator_investigation_status.complete')); ?></button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(trans('general.cancel')); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- Complete Investigation Modal for Investigator -->

<!-- Complete Investigation Modal for Delivery boy  -->
<div class="modal fade bd-example-modal-lg" id="finishAssignmentModalDel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo e(trans('form.investigator_investigation_status.complete_inv')); ?> </h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form class="add_mobile" id="completion_form_del" novalidate="novalidate" method="post" action="<?php echo e(route('investigation.complete-investigation-del')); ?>">

                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="invnid" id="invnid" value="<?php echo e($invn->id); ?>"/>
                    <input type="hidden" name="invrid" id="invrid" value="<?php echo e(Auth::id()); ?>"/>

                    <div class="form-row with-finding-fields hs-fields py-2">
                        <?php $__currentLoopData = $invn->subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-group col-md-12">
                                <label><?php echo e(trans('form.investigation.write_summary_of') . ' ' . trans('form.registration.investigation.subject') . ' ' . $subject->first_name); ?> : </label>
                                <textarea class="form-control note" name="summary[<?php echo e($subject->id); ?>]" placeholder="<?php echo e(trans('form.investigation.write_note_sub')); ?>" ></textarea>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="form-row with-finding-fields">
                        <div class="form-group col-md-12 mt-2">
                            <label><?php echo e(trans('form.investigation.final_summary')); ?> : </label>
                            <textarea class="form-control note" name="final_summary" placeholder="<?php echo e(trans('form.investigation.write_final_summary')); ?>" ></textarea>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary completeButtonDel"><?php echo e(trans('form.investigator_investigation_status.complete')); ?></button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(trans('general.cancel')); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- Complete Investigation Modal for Delivery boy -->

<!-- Accept/decline Investigation Modal for Investigator  -->
<div class="modal fade bd-example-modal-lg" id="acceptDeclineModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo e(trans('form.investigator_investigation_status.accept_inv')); ?> </h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form class="assign-form">

                    <form>

                        <input type="hidden" name="invnid" id="invnid" value="<?php echo e($invn->id); ?>"/>
                        <input type="hidden" name="invrid" id="invrid" value="<?php echo e(Auth::id()); ?>"/>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="invn_cost" class="mb-0">
                                <?php
                                $investigator = \App\InvestigatorInvestigations::where('investigator_id', \App\Helpers\AppHelper::getInvestigatorIdFromUserId(Auth::id()))->where('investigation_id', $invn->id)->first();
                                ?>
                                    <?php echo e(trans('form.registration.investigation.inv_cost_paid')); ?> : <?php echo e(trans('general.money_symbol')); ?><span id="invn_invr_cost"> <?php if(!is_null($investigator)): ?><?php echo e($investigator->inv_cost); ?><?php endif; ?></span>
                                    
                                    
                                    
                                </label>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label><?php echo e(trans('form.investigator_investigation_status.note_by_sm')); ?> : </label>
                                <?php
                                    $note = "";
                                    if(isInvestigator()){
                                        $noteInvn = $invn->investigators->where('investigator_id', \App\Helpers\AppHelper::getInvestigatorIdFromUserId(Auth::id()))
                                        ->whereNotIn('status', ['Completed Without Findings', 'Investigation Declined'])
                                        ->first();

                                        if($noteInvn){
                                            $note = $noteInvn->note;
                                        }
                                    }

                                ?>
                                <textarea class="form-control note" name="note" readonly><?php echo e($note); ?></textarea>
                            </div>
                        </div>

                    </form>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success acceptButton"><?php echo e(trans('general.accept')); ?></button>
                <button type="button" class="btn btn-danger rejectButton"><?php echo e(trans('general.reject')); ?></button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(trans('general.cancel')); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- Accept/decline Investigation Modal for Investigator -->

<!-- Accept/decline Investigation Modal for Delivery boy  -->
<div class="modal fade bd-example-modal-lg" id="acceptDeclineDelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo e(trans('form.investigator_investigation_status.accept_inv')); ?> </h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form class="assign-form">

                    <form>

                        <input type="hidden" name="invnid" id="invnid" value="<?php echo e($invn->id); ?>"/>
                        <input type="hidden" name="invrid" id="invrid" value="<?php echo e(Auth::id()); ?>"/>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="invn_cost" class="mb-0">

                                    
                                    <?php
                                    $delivery = \App\DeliveryboyInvestigations::where('deliveryboy_id', \App\Helpers\AppHelper::getDeliveryboyIdFromUserId(Auth::id()))->where('investigation_id', $invn->id)->first();
                                    ?>
                                    <?php echo e(trans('form.registration.investigation.inv_cost_paid')); ?> : <?php echo e(trans('general.money_symbol')); ?><span id="invn_invr_cost"><?php if(!is_null($delivery)): ?><?php echo e($delivery->inv_cost); ?><?php endif; ?></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label><?php echo e(trans('form.investigator_investigation_status.note_by_sm')); ?> : </label>
                                <?php
                                    $note = "";
                                    if(isDeliveryboy()){
                                        $noteInvn = $invn->deliveryboys->where('deliveryboy_id', \App\Helpers\AppHelper::getDeliveryboyIdFromUserId(Auth::id()))
                                        ->whereNotIn('status', ['Rejected', 'Done And Not Delivered'])
                                        ->first();

                                        if($noteInvn){
                                            $note = $noteInvn->note;
                                        }
                                    }
                                ?>
                                <textarea class="form-control note" name="note" readonly><?php echo e($note); ?></textarea>
                            </div>
                        </div>

                    </form>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success acceptDelButton"><?php echo e(trans('general.accept')); ?></button>
                <button type="button" class="btn btn-danger rejectDelButton"><?php echo e(trans('general.reject')); ?></button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(trans('general.cancel')); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- Accept/decline Investigation Modal for Delivery boy -->

<!-- Reject reason Modal -->
<div class="modal fade bd-example-modal-lg" id="rejectReasonModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo e(trans('form.investigator_investigation_status.RejectInvestigation')); ?> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="reject-form">
                    <input type="hidden" name="invnid" id="invnid" value="<?php echo e($invn->id); ?>"/>
                    <input type="hidden" name="invrid" id="invrid" value="<?php echo e(Auth::id()); ?>"/>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label><?php echo e(trans('form.reject_reason')); ?> : </label>
                            <textarea class="form-control reject_reason" id="reject_reason" name="reject_reason"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger submitRejectButton"><?php echo e(trans('general.reject')); ?></button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(trans('general.cancel')); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- Reject reason Modal -->

<!-- SM Final Report Submittion Modal for SM -->
<div class="modal fade bd-example-modal-lg" id="smReportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo e(trans('form.investigation_status.SubmitFinalReport')); ?> </h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form class="add_mobile" id="completion_form_sm" novalidate="novalidate" method="post" action="<?php echo e(route('investigation.submit-sm-report')); ?>">

                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="invnid" id="invnid" value="<?php echo e($invn->id); ?>"/>

                    <div class="form-row with-finding-fields">
                        <div class="form-group col-md-12 mt-2">
                            <label><?php echo e(trans('form.investigation.final_summary')); ?> : </label>
                            <textarea class="form-control note" name="final_summary" placeholder="<?php echo e(trans('form.investigation.write_final_summary')); ?>" ></textarea>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary completeButtonSM"><?php echo e(trans('general.submit')); ?></button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(trans('general.cancel')); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- SM Final Report Submittion Modal for SM -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footerScript'); ?>

<!-- footerScript -->
<!-- Required datatable js -->
<script src="<?php echo e(URL::asset('/libs/datatables/datatables.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/libs/jszip/jszip.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/libs/pdfmake/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/libs/bootstrap-editable/bootstrap-editable.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/js/pages/form-xeditable.init.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/libs/dropzone/dropzone.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/libs/rwd-table/rwd-table.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/js/pages/table-responsive.init.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/libs/sweetalert2/sweetalert2.min.js')); ?>" async></script>
<script src="<?php echo e(URL::asset('/libs/magnific-popup/magnific-popup.min.js')); ?>"></script>
<!-- lightbox init js-->
<script src="<?php echo e(URL::asset('/js/pages/lightbox.init.js')); ?>"></script>

<script>

    Dropzone.autoDiscover = false;
    $(function () {
        var jj = 0;
        var kk = 0;

        var url = window.location.href;
        var activeTab = url.substring(url.indexOf("#") + 1);
        // $(".tab-pane").removeClass("active in");
        // $("#" + activeTab).addClass("active in");

        $('a[href="#'+ activeTab +'"]').tab('show')

        var myDropzone = new Dropzone("#fileupload", {
            //maxFilesize: 8, // MB
            parallelUploads: 10,
            autoProcessQueue: false,
            //acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.mp4,.mp3,.m4v,.mov,.mkv,.avi,.3gp,.wav,.aac",
            addRemoveLinks: true,
            init: function () {
                this.on("addedfile", function (file) {
                    $(".dz-preview:last .dz-remove").attr('id', 'deletefile_' + file.size);
                });
                this.on("removedfile", function (file) {
                    var trln = $('#inve_documents2 >tbody >tr').length;
                    if (trln == 0) {
                        hideModel('doc_model');
                    }
                });
                this.on("addedfiles", function (files) {
                    var validFileTypes = ['jpeg','jpg','png','gif','pdf','doc','docx','xls','xlsx','mp4','mp3','m4v','mov','mkv','avi','3gp','wav','aac'];

                    $('#inve_documents2 >tbody').empty();
                    jj = 0;
                    kk = 0;
                    var validCount = 0;

                    for (var i = 0; i < files.length; i++) {
                        var idrand = Math.random();
                        var f = files[i];
                        var ext = (files[i].name).split('.').pop().toLowerCase();

                        //check file extension against supported types
                        if (jQuery.inArray(ext, validFileTypes) !== -1 && files[i].size <= (1024 * 1024 * 8)) {
                            validCount++;
                            //with old other type
                            var doctypes2 = <?php echo json_encode($doctypes, 15, 512) ?>;
                            var selstr = ' <select onchange="changeother(' + (f.size) + ', this)" required name="doctype[]" id="doctype_' + (f.size) + '" class="form-control doctype" >';

                            $.each(doctypes2, function (key, value) {
                                <?php if(config('app.locale') == 'hr'): ?>
                                selstr += "<option value='" + value.id + "'>" + value.hr_name + " </option>";
                                <?php else: ?>
                                selstr += "<option value='" + value.id + "'>" + value.name + " </option>";
                                <?php endif; ?>
                            });

                            selstr += "</select>";
                            var nmstr = ' <input type="text" class="form-control " name="docname[]" value=" ' + f.name + '" id="docname_' + (f.size) + '" />';
                            nmstr += ' <input type="hidden" class="form-control " name="ids[' + f.name + ']" value=" ' + idrand + '" id="ids" />';

                            var trHTML =
                                '<tr class="doctr_' + (f.size) + '"><td>' + selstr +
                                '</td><td>' + nmstr +
                                '</td><td>' + f.name +
                                '</td><td>' +
                                '<div class="action_btns">' +
                                '<a href="javascript:void(0);" class="deletefile" id="deletefile" data-id="' + (f.size) + '"><i class="fas fa-trash"></i></a>' +
                                '</div>' +
                                '</td></tr>';

                            $('#inve_documents2 > tbody').append(trHTML);
                        }else{
                            myDropzone.removeFile(files[i]);
                        }

                        if (i == files.length - 1 && validCount > 0) {
                            // Load file modal after all files are looped and type checked.
                            showModal2('doc_model');
                        }
                    }
                });

                /*  this.on("complete", function(file) {
                     console.log('Finally done');
                     myDropzone.removeFile(file);
                     Swal.fire('Uploaded!', "<?php echo e(trans('form.registration.investigation.document_uploaded')); ?>", "success");
                });  */
                this.on("success", function (file, response) {
                    console.log("in suceess...")

                    if(response.data.fileId){
                        jj = (parseInt(jj) + 1);

                        var doctypeval = $('#doctype_' + file.size).val();
                        var doctypetext = $('#doctype_' + file.size + ' option:selected').text();

                        var doctname = $('#docname_' + file.size).val();
                        if (doctypetext == 'Other') {
                            doctypeval = $('#doctypeother_' + file.size).val();
                        }

                        $.ajax({
                            url: '<?php echo e(route("investigation.update-documenttype")); ?>',
                            type: "POST",
                            data: {
                                "_token": "<?php echo e(csrf_token()); ?>",
                                "doctype": doctypeval,
                                "docname": doctname,
                                "id": response.data.fileId
                            },
                            success: function (response) {
                                if (response.status == 1) {
                                } else {
                                }
                            }

                        });

                        var isadminsm='<?php echo e($isadminsm); ?>';
                        var srNo = $('#inve_documents >tbody >tr').length;
                        var uploadedUser = response.data.uploadedby;
                        var trHTML =
                            '<tr><td>' + (parseInt(srNo) + 1) +
                            '</td><td>' + doctname +
                            '</td><td>' + doctypetext +
                            '</td>';

                        if(isadminsm==1) {
                            trHTML+= '<td>'+uploadedUser.name+' ( '+ uploadedUser.user_type.type_name +')</td>';
                            trHTML+= '<td>';
                            trHTML+='<div class="form-inline form-group justify-content-between">' +
                                '<label for="share_with_client_' + response.data.fileId + '" class="form-check-label"><?php echo e(trans('form.client_reg')); ?></label>' +
                                '<input name="share_client[]" id="share_with_client_' + response.data.fileId + '" type="checkbox" switch="bool" onclick="changeShareSetting(' + response.data.fileId + ', `share_to_client`, this)">'+
                                '<label class="ml-2" for="share_with_client_' + response.data.fileId + '" data-on-label="<?php echo e(trans('general.yes')); ?>" data-off-label="<?php echo e(trans('general.no')); ?>"> </label>' +
                                '</div>';
                            trHTML+='<div class="form-inline form-group justify-content-between">' +
                                '<label for="share_with_investigator_' + response.data.fileId + '" class="form-check-label"><?php echo e(trans('form.investigator_reg')); ?></label>' +
                                '<input name="share_investigator[]" id="share_with_investigator_' + response.data.fileId + '" type="checkbox" switch="bool" onclick="changeShareSetting(' + response.data.fileId + ', `share_to_investigator`, this)">'+
                                '<label class="ml-2" for="share_with_investigator_' + response.data.fileId + '" data-on-label="<?php echo e(trans('general.yes')); ?>" data-off-label="<?php echo e(trans('general.no')); ?>"> </label>' +
                                '</div>';
                            trHTML+='<div class="form-inline form-group justify-content-between">' +
                                '<label for="share_with_delivery_boy_' + response.data.fileId + '" class="form-check-label"><?php echo e(trans('form.delivery_boy_reg')); ?></label>' +
                                '<input name="share_delivery_boy[]" id="share_with_delivery_boy_' + response.data.fileId + '" type="checkbox" switch="bool" onclick="changeShareSetting(' + response.data.fileId + ', `share_to_delivery_boy`, this)">'+
                                '<label class="ml-2" for="share_with_delivery_boy_' + response.data.fileId + '" data-on-label="<?php echo e(trans('general.yes')); ?>" data-off-label="<?php echo e(trans('general.no')); ?>"> </label>' +
                                '</div>';
                            trHTML+= '</td>'
                        }

                        var fileExt = response.data.file_extension;
                        var fileView = '';
                        var fileName = response.data.file_url;

                        if (jQuery.inArray(fileExt, ['jpg', 'jpeg', 'gif', 'png', 'bmp'])) {
                            fileView = '<a class="view image-popup-no-margins" href="'+fileName+'" target="_blank">' +
                                '<i class="fas fa-eye"></i>' +
                                '<img class="img-fluid d-none" alt="" src="'+fileName+'" width="75"> </a>';
                        } else {
                            fileView = '<a class="view" href="'+fileName+'" target="_blank">' +
                                '<i class="fas fa-eye"></i></a>'
                        }

                        trHTML+='<td>' +
                            '<div class="action_btns">' +
                            fileView +
                            '<a class="dz-remove delete" href="javascript:void(0);" onclick="deleteDocument(' + response.data.fileId + ', this)"><i class="fas fa-trash"></i></a>' +
                            '</div>' +
                            '</td></tr>';

                        $('.tr-nodoc').hide();
                        $('#inve_documents >tbody').append(trHTML);
                        myDropzone.removeFile(file);

                        
                    }

                });

                this.on("error", function (file, errorMessage) {
                    if (!file.accepted) {
                        this.removeFile(file);
                    }
                    Swal.fire("<?php echo e(trans('general.error_text')); ?>", "<?php echo e(trans('form.registration.investigation.document_not_allowed')); ?>", "error");
                });
                /*    this.on("complete", function(file) {
                      // console.log('Finally done');
                      // myDropzone.removeFile(file);
                       //Swal.fire('Uploaded!', "<?php echo e(trans('form.registration.investigation.document_uploaded')); ?>", "success");
                });  */

                this.on("queuecomplete", function () {
                    //myDropzone.removeAllFiles(true); // disable temporary to allow uploading valid files when invalid is too selected with wrong one
                    Swal.fire({
                        title: "<?php echo e(trans('general.uploaded_text')); ?>", 
                        text: "<?php echo e(trans('form.registration.investigation.document_uploaded')); ?>", 
                        type: "success",
                        confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
                    });
                });
            }
        });

        $('#showDeclineReason').on('click', function(){
            $('#decline-reason-modal').modal('show');
        })

        $('#closmod').click(function () {
            Dropzone.forElement("#fileupload").removeAllFiles(true);
            // myDropzone.removeAllFiles(true);

        });

        $('#closmod2').click(function () {
            Dropzone.forElement("#fileupload").removeAllFiles(true);
            // myDropzone.removeAllFiles(true);

        });

        $('#uploadfile').click(function () {
            hideModel('doc_model');
            Swal.showLoading();
            Swal.fire({
              title: 'Wait ...',
              onBeforeOpen () {
                Swal.showLoading ()
              },
              onAfterClose () {
                Swal.hideLoading()
              },
              allowOutsideClick: false,
              allowEscapeKey: false,
              allowEnterKey: false
            })
            myDropzone.processQueue();
        });

        $("body").on("click", "#deletefile", function () {
            var id = $(this).data("id");
            $(".doctr_" + id).remove();
            clickhref(id);
        });

        $("input[name='complete_type']").on('change', function() {
            if($(this).is(":checked")){
                $(".hs-fields").hide();
            }else{
                $(".hs-fields").show();
            }
        });

        $(".completeButton").on('click', function(e){
            e.preventDefault(); // avoid to execute the actual submit of the form.
            let status = $("input[name='complete_type']").is(":checked") ? "<?php echo e(trans('form.timeline_status.Completed Without Findings')); ?>" : "<?php echo e(trans('form.timeline_status.Completed With Findings')); ?>";

            Swal.fire({
                title: "<?php echo e(trans('general.are_you_sure')); ?>",
                text: "<?php echo e(trans('form.investigation.change_status_msg')); ?>" + status + '?',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#34c38f",
                cancelButtonColor: "#f46a6a",
                confirmButtonText: "<?php echo e(trans('general.yes_change')); ?>",
                cancelButtonText: "<?php echo e(trans('general.cancel')); ?>"
            }).then(function (result) {
                if (result.value) {
                    var form = $('#completion_form');
                    var url = form.attr('action');
                    console.log(url);
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: form.serialize(), // serializes the form's elements.
                        success: function(response)
                        {
                            console.log(response,'response')
                            if (response.status == 'success') {
                                Swal.fire({
                                    text: "<?php echo e(trans('general.success_text')); ?>", 
                                    text: (result.message) ? result.message : "<?php echo e(trans('form.registration.investigation.confirm_statuschanged')); ?>",
                                    type: "success",
                                    confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
                                })
                                    .then((result) => {
                                        if (result.value) {
                                            if(response.inv_status){
                                                if(response.inv_status == "Completed With Findings"){
                                                    location.href = "/investigations";
                                                } else if(response.inv_status == "Completed Without Findings") {
                                                    location.href = "/investigations";
                                                } else {
                                                    location.reload(true);    
                                                }
                                            } else {
                                                location.reload(true);
                                            }
                                        }
                                    });
                            } else {
                                Swal.fire("<?php echo e(trans('general.error_text')); ?>", (result.message) ? result.message : "<?php echo e(trans('general.something_wrong')); ?>", "error");
                            }
                        }
                    });
                } else {
                    Swal.close();
                }
            });

        });

        $(".acceptButton").on('click', function(){
            let status = "<?php echo e(trans('form.timeline_status.Investigation Started')); ?>";
            changeAssignmentStatus('Investigation Started', 'investigator', status);
        });

        $(".rejectButton").on('click', function(){
            $('#acceptDeclineModal').modal('hide');
            $('#rejectReasonModal').modal('show');
        });

        $('.submitRejectButton').on('click', function(){
            $.ajax({
                type: "POST",
                url: "<?php echo e(route('investigation.reject')); ?>",
                data: {
                    "_token": "<?php echo e(csrf_token()); ?>",
                    "investigation_id": $('#invnid').val(),
                    "investigator_id": $('#invrid').val(),
                    "reject_reason": $('#reject_reason').val(),
                    "type": "<?php echo e(config('constants.user_type')); ?>",
                },
                success: function(data){
                    if(data.result){
                        <?php if(isInvestigator()): ?>
                            let status = "<?php echo e(trans('form.timeline_status.Investigation Declined')); ?>";
                            changeAssignmentStatus('Investigation Declined', 'investigator', status);
                        <?php else: ?>
                            let status = "<?php echo e(trans('form.timeline_status.Rejected')); ?>";
                            changeAssignmentStatus('Rejected', 'deliveryboy', status);
                        <?php endif; ?>
                    }
                }, error: function(data){
                    Swal.fire("<?php echo e(trans('general.error_text')); ?>", "<?php echo e(trans('general.something_wrong')); ?>", "error");
                }
            });
        });

        $(".acceptDelButton").on('click', function(){
            let status = "<?php echo e(trans('form.timeline_status.Accepted')); ?>";
            changeAssignmentStatus('Accepted', 'deliveryboy', status);
        });

        $(".rejectDelButton").on('click', function(){
            $('#acceptDeclineDelModal').modal('hide');
            $('#rejectReasonModal').modal('show');
            // let status = "<?php echo e(trans('form.timeline_status.Rejected')); ?>";
            // changeAssignmentStatus('Rejected', 'deliveryboy', status);
        });

        $(".otherdoc").on('change', function(){
            calculateDocCost();
        });

        $(".completeButtonDel").on('click', function(e){
            e.preventDefault(); // avoid to execute the actual submit of the form.
            let status = 'Return To Center';
            let txtStatus = "<?php echo e(trans('form.timeline_status.Return To Center')); ?>";

            Swal.fire({
                title: "<?php echo e(trans('general.are_you_sure')); ?>",
                text: "<?php echo e(trans('form.investigation.change_status_msg')); ?>" + txtStatus + '?',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#34c38f",
                cancelButtonColor: "#f46a6a",
                confirmButtonText: "<?php echo e(trans('general.yes_change')); ?>",
                cancelButtonText: "<?php echo e(trans('general.cancel')); ?>"
            }).then(function (result) {
                if (result.value) {
                    var form = $('#completion_form_del');
                    var url = form.attr('action');

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: form.serialize() + "&status=" + status, // serializes the form's elements.
                        success: function(response)
                        {
                            if (response.status == 'success') {
                                Swal.fire({
                                    title: "<?php echo e(trans('general.success_text')); ?>", 
                                    text: (result.message) ? result.message : "<?php echo e(trans('form.registration.investigation.confirm_statuschanged')); ?>",
                                    type: "success",
                                    confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
                                })
                                    .then((result) => {
                                        if (result.value) {
                                            if(status == "Return To Center") {
                                                location.href = "/investigations";
                                            } else {
                                                location.reload(true);
                                            }
                                        }
                                    });
                            } else {
                                Swal.fire("<?php echo e(trans('general.error_text')); ?>", (result.message) ? result.message : "<?php echo e(trans('general.something_wrong')); ?>", "error");
                            }
                        }
                    });
                } else {
                    Swal.close();
                }
            });

        });

        $(".completeButtonSM").on('click', function(e){
            e.preventDefault(); // avoid to execute the actual submit of the form.
            let status = 'Final Report Submitted';
            let txtStatus = "<?php echo e(trans('form.timeline_status.Final Report Submitted')); ?>";

            Swal.fire({
                title: "<?php echo e(trans('general.are_you_sure')); ?>",
                text: "<?php echo e(trans('form.investigation.change_status_msg')); ?>" + txtStatus + '?',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#34c38f",
                cancelButtonColor: "#f46a6a",
                confirmButtonText: "<?php echo e(trans('general.yes_change')); ?>",
                cancelButtonText: "<?php echo e(trans('general.cancel')); ?>"
            }).then(function (result) {
                if (result.value) {
                    var form = $('#completion_form_sm');
                    var url = form.attr('action');
                    console.log(url);
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: form.serialize(), // serializes the form's elements.
                        success: function(response)
                        {
                            if (response.status == 'success') {
                                Swal.fire({
                                    title: "<?php echo e(trans('general.success_text')); ?>",
                                    text: (result.message) ? result.message : "<?php echo e(trans('form.registration.investigation.confirm_statuschanged')); ?>", 
                                    type: "success",
                                    confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
                            })
                                    .then((result) => {
                                        if (result.value) {
                                            location.reload(true);
                                        }
                                    });
                            } else {
                                Swal.fire("<?php echo e(trans('general.error_text')); ?>", (result.message) ? result.message : "<?php echo e(trans('general.something_wrong')); ?>", "error");
                            }
                        }
                    });
                } else {
                    Swal.close();
                }
            });

        });

        calculateDocCost();
    });

    function clickhref(id) {
        document.getElementById('deletefile_' + id).click();
    }

    function changeother(id, t) {
        var e = document.getElementById('doctype_' + id);
        if (e.value == 'Other') {
            $("#doctypeother_" + id).removeClass('d-none');

        } else {
            $("#doctypeother_" + id).addClass('d-none');
        }
    }

    function deleteDocument(id, obj) {
        Swal.fire({
            title: "<?php echo e(trans('general.are_you_sure')); ?>",
            text: "<?php echo e(trans('form.registration.investigation.document_confirm_delete')); ?>",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#34c38f",
            cancelButtonColor: "#f46a6a",
            confirmButtonText: "<?php echo e(trans('general.yes_delete')); ?>",
            cancelButtonText: "<?php echo e(trans('general.cancel')); ?>"
        }).then(function (result) {
            if (result.value) {
                $.ajax(
                    {
                        url: "/investigations/deleteDocument",
                        type: 'POST',
                        data: {
                            _token: "<?php echo e(csrf_token()); ?>",
                            id: id,
                        },
                        success: function (response) {
                            if (response.status == 'success') {
                                Swal.fire({
                                    title: "<?php echo e(trans('general.deleted_text')); ?>", 
                                    text: (result.message) ? result.message : "<?php echo e(trans('form.registration.investigation.document_deleted')); ?>", 
                                    type: "success",
                                    confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
                                });
                                $(obj).parents("tr:first").remove();

                                if ($('#inve_documents >tbody >tr').length < 1) {
                                    var trHTML = '<tr> <td colspan="3" class="text-center"> <?php echo e(trans('form.registration.investigation.document_notfound')); ?> </td> </tr>';
                                    $('.tr-nodoc').show();
                                }
                            } else {
                                Swal.fire("<?php echo e(trans('general.error_text')); ?>", (result.message) ? result.message : "<?php echo e(trans('general.something_wrong')); ?>", "error");
                            }
                        }
                    }
                );
            } else {
                Swal.close();
            }
        });
    }

    function changeShareSetting(id, type, obj) {
        var enabled = document.getElementById(obj.id).checked;
        var sFlag = 0;
        if (enabled) {
            sFlag = 1;
        }
        $.ajax(
            {
                url: "/investigations/shareDocument",
                type: 'POST',
                data: {
                    _token: "<?php echo e(csrf_token()); ?>",
                    docId: id,
                    type,
                    value: sFlag
                },
                success: function (response) {
                    if (response.status == 'success') {
                        Swal.fire({
                            title: "<?php echo e(trans('general.success_text')); ?>",
                            text: (response.message) ? response.message : "<?php echo e(trans('form.registration.investigation.confirm_statuschanged')); ?>", 
                            type: "success",
                            confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
                        });
                    } else {
                        Swal.fire({
                            title: "<?php echo e(trans('general.error_text')); ?>", 
                            text: (response.message) ? response.message : "<?php echo e(trans('general.something_wrong')); ?>", 
                            type: "error",
                            confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
                        });
                    }
                }
            }
        );
    }

    function notPaidAlert() {
        Swal.fire({
            title: "<?php echo e(trans('general.payment_not_done')); ?>",
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#34c38f",
            confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
            cancelButtonText: "<?php echo e(trans('general.cancel')); ?>"
        }).then(function (result) {
            if (result.value) {
                Swal.close();
            }
        });
    }

    function opendeclineModal(modelid, type, id) {
        $.ajax({
            url: '<?php echo e(route("investigation.actiondata")); ?>',
            type: "POST",
            data: {
                "_token": "<?php echo e(csrf_token()); ?>",
                "type": type,
                "id": id
            },
            success: function (response) {
                if (response.status == 1) {
                    $('#' + modelid).html(response.html);
                    showModal(modelid);
                } else {
                    $('#' + modelid).modal('hide');
                }
            }

        });
    }

    function approveconfirm(id) {
        var inid = id;
        Swal.fire({
            title: "<?php echo e(trans('general.are_you_sure')); ?>",
            text: "<?php echo e(trans('form.registration.investigation.approve_confirm')); ?>",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#34c38f",
            cancelButtonColor: "#f46a6a",
            confirmButtonText: "<?php echo e(trans('general.yes_approve')); ?>",
            cancelButtonText: "<?php echo e(trans('general.cancel')); ?>"
        }).then(function (result) {
            if (result.value) {
                $('.actiondropdown').addClass('d-none');
                $('.loading').removeClass('d-none');
                $.ajax({
                    url: '<?php echo e(route("investigation.actiondata")); ?>',
                    type: "POST",
                    data: {
                        "_token": "<?php echo e(csrf_token()); ?>",
                        "type": "approve_update",
                        "id": inid
                    },
                    success: function (response) {
                        if (response.status == 1) {
                            Swal.fire({
                                title: "<?php echo e(trans('general.approve')); ?>", 
                                text: (result.msg) ? result.msg : "<?php echo e(trans('form.registration.investigation.approve_confirmed')); ?>", 
                                type: "success",
                                confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
                            })
                                .then((result) => {
                                    if (result.value) {
                                        location.reload(true);
                                    }
                                });

                        } else {
                            $('.actiondropdown').removeClass('d-none');
                            $('.loading').addClass('d-none');
                            Swal.fire("<?php echo e(trans('general.error_text')); ?>", (result.msg) ? result.msg : "<?php echo e(trans('general.something_wrong')); ?>", "error")
                        }
                    }

                });

            } else {
                $('.actiondropdown').removeClass('d-none');
                $('.loading').addClass('d-none');
                Swal.close();
            }
        });
    }

    function showModal(modelid, subid) {
        $('#' + modelid).modal({
            backdrop: 'static',
            keyboard: false
        });

        $('#' + modelid + ' .modal-body').html($('#' + modelid + '-data-' + subid).html());
        $('#' + modelid).modal('show');
    }

    function showModal2(modelid) {
        $('#' + modelid).modal({
            backdrop: 'static',
            keyboard: false
        })

        $('#' + modelid).modal('show');
    }

    function hideModel(id) {
        $('#' + id).modal('hide');
    }

    function changeAssignmentStatus(status, performer, translatedStatus, entityId = null) {
        console.log(translatedStatus,'translatedStatus')
        Swal.fire({
            title: "<?php echo e(trans('general.are_you_sure')); ?>",
            text: "<?php echo e(trans('form.investigation.change_status_msg')); ?>" + translatedStatus + '?',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#34c38f",
            cancelButtonColor: "#f46a6a",
            confirmButtonText: "<?php echo e(trans('general.yes_change')); ?>",
            cancelButtonText: "<?php echo e(trans('general.cancel')); ?>"
        }).then(function (result) {
            if (result.value) {
                $.ajax(
                    {
                        url: "/investigations/change-assignment-status",
                        type: 'POST',
                        data: {
                            _token: "<?php echo e(csrf_token()); ?>",
                            investigationId: <?php echo e($invn->id); ?>,
                            userId: <?php echo e(Auth::id()); ?>,
                            status: status,
                            performer: performer,
                            entityId: entityId,
                        },
                        success: function (response) {
                            if (response.status == 'success') {
                                console.log(response,'responseresponse')
                                Swal.fire({
                                    title: "<?php echo e(trans('general.success_text')); ?>", 
                                    text: (result.message) ? result.message : "<?php echo e(trans('form.registration.investigation.confirm_statuschanged')); ?>", 
                                    type: "success",
                                    confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
                                })
                                    .then((result) => {
                                        if (result.value) {
                                            if(status == "Send To Client"){
                                                location.href = "<?php echo e(route('investigation.showinvoice', \Illuminate\Support\Facades\Crypt::encrypt($invn->id))); ?>";
                                            } else if(status == "Investigation Declined"){                                                
                                                location.href = "/investigations";
                                            } else if(status == "Rejected"){                                                
                                                location.href = "/investigations";
                                            } else{
                                                location.reload(true);
                                            }
                                        }
                                    });
                            } else {
                                Swal.fire("<?php echo e(trans('general.error_text')); ?>", (result.message) ? result.message : "<?php echo e(trans('general.something_wrong')); ?>", "error");
                            }
                        }
                    }
                );
            } else {
                Swal.close();
            }
        });
    }

    // Update Address Confirmed in the Subjects List
    function changesubjectaddressconfirm(e, subid) {
        var id = e.id;
        var lfckv = document.getElementById(id).checked;
        var acflag = 0;
        if (lfckv)
            acflag = 1;
        $.ajax({
            url: '<?php echo e(route("subject.updatedata")); ?>',
            type: "POST",
            data: {
                _token: "<?php echo e(csrf_token()); ?>",
                subjectId: subid,
                acflag: acflag,
            },

            success: function (response) {
                if (response.status == 'success') {

                    Swal.fire({
                        title: "<?php echo e(trans('general.success_text')); ?>", 
                        text: (response.message) ? response.message : "<?php echo e(trans('form.registration.investigation.confirm_statuschanged')); ?>", 
                        type: "success",
                        confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
                    })
                        .then((result) => {

                        });
                } else {
                    Swal.fire("<?php echo e(trans('general.error_text')); ?>", (response.message) ? response.message : "<?php echo e(trans('general.something_wrong')); ?>", "error");
                }

            }

        });
    }

    // Update is Payment Document in the Document List
    function changeispaymentdocument(e,subid){
        var id=e.id;
        var lfckv = document.getElementById(id).checked;
        var acflag=0;
        if(lfckv)
        acflag=1;
            $.ajax({
                        url: '<?php echo e(route("investigation.updateispaymentdoc")); ?>' ,
                        type: "POST",
                        data: {
                            _token: "<?php echo e(csrf_token()); ?>",
                            docId: subid,
                            acflag: acflag,
                        },
                        
                        success: function( response ) {
                            if (response.status == 'success') {
                                    
                                Swal.fire({
                                    title: "<?php echo e(trans('general.success_text')); ?>",
                                    text: (response.message) ? response.message : "<?php echo e(trans('form.registration.investigation.confirm_statuschanged')); ?>", 
                                    type: "success",
                                    confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
                                })
                                .then((result) => {
                                    
                                });
                            } else {
                                Swal.fire("<?php echo e(trans('general.error_text')); ?>",(response.message) ? response.message : "<?php echo e(trans('general.something_wrong')); ?>", "error");
                            }
                            
                        }

                 });
    }

    function finishAssignmentEvent(){
        $('#finishAssignmentModal').modal('show');
    }

    function finishAssignmentEventDel(){
        $('#finishAssignmentModalDel').modal('show');
    }

    function showAcceptDeclineModal(){
        $('#acceptDeclineModal').modal('show');
    }

    function showDelAcceptDeclineModal(){
        $('#acceptDeclineDelModal').modal('show');
    }

    function calculateDocCost(){

        $('.investigator-card').each(function() {
            let id = $(this).data('id');
            var yourArray = [];
            $(`.otherdoc-${id}:checkbox:checked`).each(function(){
                yourArray.push(parseFloat($(this).data('price')));
            });

            var totalDocCost = yourArray.reduce((a, b) => a + b, 0);
            $(`.doc-cost-${id}`).html(totalDocCost);
            $(`#doc_cost-${id}`).val(totalDocCost);

            var invCost = $(`.inv-cost-${id}`).html();
            var totalCost = parseFloat(invCost) + totalDocCost;
            $(`.total-cost-${id}`).html(totalCost);
        })

        $('.deliveryboy-card').each(function(){
            let id = $(this).data('id');
            var yourArratDel = [];
            $(`.otherdoc-del-${id}:checkbox:checked`).each(function(){    
                yourArratDel.push(parseFloat($(this).data('price')));    
            })

            var totalDocCostDel = yourArratDel.reduce((a, b) => a + b, 0);
            $(`.doc-cost-del-${id}`).html(totalDocCostDel);
            $(`#doc_cost_del-${id}`).val(totalDocCostDel);

            var invCostDel = $(`.inv-cost-del-${id}`).html();
            var totalCostDel = parseFloat(invCostDel) + totalDocCostDel;
            $(`.total-cost-del-${id}`).html(totalCostDel);
        });
    }

    function adminActionOnReport(status, performer, entityId = null, txtStatus){
        Swal.fire({
            title: "<?php echo e(trans('general.are_you_sure')); ?>",
            text: "<?php echo e(trans('form.investigation.change_status_msg')); ?>" + txtStatus + '?',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#34c38f",
            cancelButtonColor: "#f46a6a",
            confirmButtonText: "<?php echo e(trans('general.yes_change')); ?>",
            cancelButtonText: "<?php echo e(trans('general.cancel')); ?>"
        }).then(function (result) {
            if (result.value) {
                var form = $('#inv-approval-form-' + entityId);
                var url = form.attr('action');
                var data = form.serialize() + "&status=" + status;
                console.log(data);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data, // serializes the form's elements.
                    success: function(response)
                    {
                        if (response.status == 'success') {
                            Swal.fire({
                                title: "<?php echo e(trans('general.success_text')); ?>", 
                                text: (result.message) ? result.message : "<?php echo e(trans('form.registration.investigation.confirm_statuschanged')); ?>", 
                                type: "success",
                                confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
                            }).then((result) => {
                                    if (result.value) {
                                        location.reload(true);
                                    }
                                });
                        } else {
                            Swal.fire("<?php echo e(trans('general.error_text')); ?>", (result.message) ? result.message : "<?php echo e(trans('general.something_wrong')); ?>", "error");
                        }
                    }
                });
            } else {
                Swal.close();
            }
        });
    }

    function changeToUrgent(id) {
        Swal.fire({
            title: "<?php echo e(trans('general.are_you_sure')); ?>",
            text: "<?php echo e(trans('form.registration.investigation.urgent_confirm')); ?>",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#34c38f",
            cancelButtonColor: "#f46a6a",
            confirmButtonText: "<?php echo e(trans('general.yes')); ?>",
            cancelButtonText: "<?php echo e(trans('general.cancel')); ?>"
        }).then(function (result) {
            if (result.value) {
                $('.loading').removeClass('d-none');
                $.ajax({
                    type: "GET",
                    url: '/investigations/urgent/change-status',
                    data: {
                        "_token": "<?php echo e(csrf_token()); ?>",
                        "id": id,
                    },
                    success: function(response)
                    {
                        if (response.status == 'success') {
                            Swal.fire({
                                title: "<?php echo e(trans('general.success_text')); ?>", 
                                text: response.message, 
                                type: "success",
                                confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
                            }).then((result) => {
                                    if (result.value) {
                                        location.reload(true);
                                    }
                                });
                        } else {
                            Swal.fire("<?php echo e(trans('general.error_text')); ?>", (response.message) ? response.message : "<?php echo e(trans('general.something_wrong')); ?>", "error");
                        }
                    }
                });
            } else {
                $('.loading').addClass('d-none');
                Swal.close();
            }
        });
    }

    function adminActionOnDecline(status, performer, entityId = null, txtStatus, assignId) {
        $('#reportDecline').data('status', status);
        $('#reportDecline').data('performer', performer);
        $('#reportDecline').data('entityid', entityId);
        $('#reportDecline').data('textstatus', txtStatus);
        $('#reportDecline').data('assignid', assignId);
        $('#decline-modal').modal('show');
    }

    $('#decline-modal').on('hidden.bs.modal', function () {
        $('#reportDecline').attr('data-status', '');
        $('#reportDecline').attr('data-performer', '');
        $('#reportDecline').attr('data-entityid', '');
        $('#reportDecline').attr('data-textstatus', '');
        $('#reportDecline').data('assignid', '');
        $('#decline_reason').val('');
    });

    $('#reportDecline').on('click', function(){
        let status = $('#reportDecline').data('status');
        let performer = $('#reportDecline').data('performer');
        let entityId = $('#reportDecline').data('entityid');
        let textstatus = $('#reportDecline').data('textstatus');
        let assignid = $('#reportDecline').data('assignid');
        let reason = $('#decline_reason').val();

        Swal.fire({
            title: "<?php echo e(trans('general.are_you_sure')); ?>",
            text: "<?php echo e(trans('form.investigation.change_status_msg')); ?>" + textstatus + '?',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#34c38f",
            cancelButtonColor: "#f46a6a",
            confirmButtonText: "<?php echo e(trans('general.yes_change')); ?>",
            cancelButtonText: "<?php echo e(trans('general.cancel')); ?>"
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo e(route('investigation.action-on-report-decline')); ?>",
                    data: {
                        assignId: assignid,
                        decline_reason: reason,
                        status: status,
                        performer: performer,
                        entityId: entityId,
                        textstatus: textstatus,
                        "_token": "<?php echo e(csrf_token()); ?>",
                    },
                    success: function(response)
                    {
                        if (response.status == 'success') {
                            $('#decline-modal').modal('hide');
                            Swal.fire({
                                title: "<?php echo e(trans('general.success_text')); ?>", 
                                text: (result.message) ? result.message : "<?php echo e(trans('form.registration.investigation.confirm_statuschanged')); ?>", 
                                type: "success",
                                confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
                            }).then((result) => {
                                    if (result.value) {
                                        location.reload(true);
                                    }
                                });
                        } else {
                            $('#decline-modal').modal('hide');
                            Swal.fire("<?php echo e(trans('general.error_text')); ?>", (result.message) ? result.message : "<?php echo e(trans('general.something_wrong')); ?>", "error");
                        }
                    }
                });
            } else {
                $('#decline-modal').modal('hide');
                Swal.close();
            }
        });
    });

    function adminActionOnReportDel(status, performer, entityId = null){
        Swal.fire({
            title: "<?php echo e(trans('general.are_you_sure')); ?>",
            text: "<?php echo e(trans('form.investigation.change_status_msg')); ?>" + status + '?',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#34c38f",
            cancelButtonColor: "#f46a6a",
            confirmButtonText: "<?php echo e(trans('general.yes_change')); ?>",
            cancelButtonText: "<?php echo e(trans('general.cancel')); ?>"
        }).then(function (result) {
            if (result.value) {
                var form = $('#inv-approval-form-del-' + entityId);
                var url = form.attr('action');
                var data = form.serialize() + "&status=" + status;
                    console.log(data);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data, // serializes the form's elements.
                    success: function(response)
                    {
                        if (response.status == 'success') {
                            Swal.fire({
                                title: "<?php echo e(trans('general.success_text')); ?>", 
                                text:(result.message) ? result.message : "<?php echo e(trans('form.registration.investigation.confirm_statuschanged')); ?>", 
                                type: "success",
                                confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
                            })
                                .then((result) => {
                                    if (result.value) {
                                        location.reload(true);
                                    }
                                });
                        } else {
                            Swal.fire("<?php echo e(trans('general.error_text')); ?>", (result.message) ? result.message : "<?php echo e(trans('general.something_wrong')); ?>", "error");
                        }
                    }
                });
            } else {
                Swal.close();
            }
        });

    }

    function smReportEvent(){
        $('#smReportModal').modal('show');
    }

    $.fn.editable.defaults.params = function (params) {
        params._token = $("meta[name=csrf-token]").attr("content");
        return params;
    };

    $('.invst_amount').editable({
        type: 'text',
        url: "<?php echo e(route('investigator-invoice-amount')); ?>",
        name: 'investigator_cost',
        title: 'Enter username',
        mode: 'inline',
        inputclass: 'form-control-sm',
        ajaxOptions: {
            type: 'post'
        },
        success: function(response, newValue) {
            if(response.success){
                location.reload();
            }
        }
      });

    $('.delboy_amount').editable({
        type: 'text',
        url: "<?php echo e(route('delboy-invoice-amount')); ?>",
        name: 'investigator_cost',
        title: 'Enter username',
        mode: 'inline',
        inputclass: 'form-control-sm',
        ajaxOptions: {
            type: 'post'
        },
        success: function(response, newValue) {
            if(response.success){
                location.reload();
            }
        }
      });

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/uvda/resources/views/investigation/show.blade.php ENDPATH**/ ?>