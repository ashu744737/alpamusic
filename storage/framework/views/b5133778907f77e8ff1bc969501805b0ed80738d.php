<?php if($type=='admin'): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <section id="cd-timeline" class="cd-container" dir="ltr">
                        <?php if(count($transitions)>0): ?>
                        <?php $indxtrans = 1; ?>
                        <?php $__currentLoopData = $transitions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(($indxtrans%2)!=0): ?>
                        <div class="cd-timeline-block <?php echo e(count($transitions) != $indxtrans ? 'timeline-right' : ''); ?>">
                            <div class="cd-timeline-img bg-success <?php echo e(count($transitions) == $indxtrans ? 'd-xl-none' : ''); ?>">
                                <i class="mdi mdi-adjust"></i>
                            </div>
                            <!-- cd-timeline-img -->
                            

                            <div class="cd-timeline-content">
                                <h3><?php echo $transition['event_title'];?>  
                                    <?php if(!empty($transition['investigation_status'])): ?>
                                    <?php $statusbadge='';
                                    if($transition['investigation_status']== trans('form.timeline_status.Pending Approval') || $transition['investigation_status']== trans('form.timeline_status.Open') || $transition['investigation_status']== trans('form.timeline_status.Modification Required'))
                                    $statusbadge='warning';
                                    else if($transition['investigation_status']==trans('form.timeline_status.Assigned'))
                                    $statusbadge='dark';
                                    else if($transition['investigation_status']== trans('form.timeline_status.Approved'))
                                    $statusbadge='success';
                                    else if($transition['investigation_status']== trans('form.timeline_status.Declined') || $transition['investigation_status']== trans('form.timeline_status.Closed') || $transition['investigation_status']== trans('form.timeline_status.Cancelled'))
                                    $statusbadge='danger';
                                    else
                                    $statusbadge = 'primary';
                                    ?>
                                    <span class="badge dt-badge badge-<?php echo e($statusbadge); ?>"> <?php echo e($transition['investigation_status']); ?></span>
                                    <?php endif; ?>
                                   

                                </h3>
                                <p class="mb-0 text-muted"><?php echo $transition['event_desc'];?><br><br></p>
                                <?php if(!empty($transition['decline_reason'])): ?> <p class="mb-0 text-muted"><?php echo $transition['decline_reason'];?></p><?php endif; ?>
                                <?php if(!empty($transition['reason'])): ?> <p class="mb-0 text-muted"><?php echo e(trans('form.timeline.investigation_decline_reason')); ?> <?php echo $transition['reason'];?></p><?php endif; ?>
                                <?php if(!empty($transition['document_filename'])): ?>
                                <?php $isimage=0;
                                $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp'];
                                    if(in_array($transition['document_type'], $imageExtensions))
                                        {$isimage=1;
                                        }
                                        $imgurl='/investigation-documents/'.$transition['document_filename'];
                                ?>
                                <?php if($isimage==1): ?>
                                <p><?php echo e($transition['document_name']); ?></p>
                                <p><a href="<?php echo e(URL::asset($imgurl)); ?>" target="_blank"><img src="<?php echo e(url($imgurl)); ?>" alt="" class="rounded" width="90"/></a></p>
                              
                                <?php elseif($isimage==0): ?>
                                <p><a href="<?php echo e(URL::asset($imgurl)); ?>" class="card-link" target="_blank"><?php echo e($transition['document_name']); ?></a></p>
                                <?php endif; ?>
                                <?php endif; ?>
                                <?php if(!empty($transition['investigator_assign_status'])): ?>
                                    <?php $invstatusbadge='';
                                    if($transition['investigator_assign_status']== trans('form.timeline_status.in progress') || $transition['investigator_assign_status']==trans('form.timeline_status.Assigned') || $transition['investigator_assign_status']==trans('form.timeline_status.Report Writing'))
                                    $invstatusbadge='warning';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.Completed'))
                                    $invstatusbadge='success';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.Report Submitted'))
                                    $invstatusbadge='primary';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.Return To Center'))
                                    $invstatusbadge='info';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.inactive') || $transition['investigator_assign_status']==trans('form.timeline_status.Not Completed') || $transition['investigator_assign_status']==trans('form.timeline_status.Declined'))
                                    $invstatusbadge='danger';
                                    else
                                    $invstatusbadge = 'primary';
                                    ?>
                                <span class="badge dt-badge badge-<?php echo e($invstatusbadge); ?>"> <?php echo e($transition['investigator_assign_status']); ?></span>
                                    <?php endif; ?>

                                    <?php if(!empty($transition['deliveryboy_assign_status'])): ?>
                                    <?php $invstatusbadge='';
                                    if($transition['deliveryboy_assign_status']==trans('form.timeline_status.in progress') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Assigned') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Report Writing'))
                                    $invstatusbadge='warning';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Done And Delivered'))
                                    $invstatusbadge='success';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Report Submitted'))
                                    $invstatusbadge='primary';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Return To Center'))
                                    $invstatusbadge='info';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.inactive') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Done And Not Delivered') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Not Delivered'))
                                    $invstatusbadge='danger';
                                    else
                                    $invstatusbadge = 'primary';
                                    ?>
                                <span class="badge dt-badge badge-<?php echo e($invstatusbadge); ?>"> <?php echo e($transition['deliveryboy_assign_status']); ?></span>
                                    <?php endif; ?>
                                    <?php if($transition['event']=='investigation_generateinvoice'): ?>
                                    <a target="_blank" href="<?php echo e(route('investigation.showinvoice', [Crypt::encrypt($invn->id)])); ?>" class="btn btn-primary btn-rounded waves-effect waves-light m-t-5" title=" <?php echo e(trans('form.investigationinvoice.viewandgenerateinvoice')); ?>">
                                        <?php echo e(trans('form.investigationinvoice.view_invoice')); ?>

                                    </a>
                                    <?php endif; ?>
                                <span class="cd-date"><?php echo $transition['event_date'];?></span>
                                
                            </div>
                            <!-- cd-timeline-content -->
                        </div>
                        <!-- cd-timeline-block -->
                        <?php else: ?>  
                        <div class="cd-timeline-block <?php echo e(count($transitions) != $indxtrans ? 'timeline-left' : ''); ?> ">
                            <div class="cd-timeline-img bg-danger <?php echo e(count($transitions) == $indxtrans ? 'd-xl-none' : ''); ?>">
                                <i class="mdi mdi-adjust"></i>
                            </div>
                            <!-- cd-timeline-img -->
                           
                            <div class="cd-timeline-content">
                                <h3><?php echo $transition['event_title'];?>  
                                    <?php if(!empty($transition['investigation_status'])): ?>
                                    <?php $statusbadge='';
                                    if($transition['investigation_status']==trans('form.timeline_status.Pending Approval') || $transition['investigation_status']==trans('form.timeline_status.Open') || $transition['investigation_status']==trans('form.timeline_status.Modification Required'))
                                    $statusbadge='warning';
                                    else if($transition['investigation_status']==trans('form.timeline_status.Assigned'))
                                    $statusbadge='dark';
                                    else if($transition['investigation_status']==trans('form.timeline_status.Approved'))
                                    $statusbadge='success';
                                    else if($transition['investigation_status']==trans('form.timeline_status.Declined') || $transition['investigation_status']==trans('form.timeline_status.Closed') || $transition['investigation_status']==trans('form.timeline_status.Cancelled'))
                                    $statusbadge='danger';
                                    else
                                    $statusbadge = 'primary';
                                    ?>
                                    <span class="badge dt-badge badge-<?php echo e($statusbadge); ?>"> <?php echo e($transition['investigation_status']); ?></span>
                                    <?php endif; ?>
                                </h3>
                                <p class="mb-4 text-muted"><?php echo $transition['event_desc'];?></p>
                                <?php if(!empty($transition['decline_reason'])): ?> <p class="mb-0 text-muted"><?php echo $transition['decline_reason'];?></p><?php endif; ?>
                                <?php if(!empty($transition['reason'])): ?> <p class="mb-0 text-muted"><?php echo $transition['reason'];?></p><?php endif; ?>
                                <?php if(!empty($transition['document_filename'])): ?>
                                <?php $isimage=0;
                                $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp'];
                                    if(in_array($transition['document_type'], $imageExtensions))
                                        {$isimage=1;
                                        }
                                        $imgurl='/investigation-documents/'.$transition['document_filename'];
                                ?>
                                <?php if($isimage==1): ?>
                                <p><?php echo e($transition['document_name']); ?></p>
                                <p><a href="<?php echo e(URL::asset($imgurl)); ?>" target="_blank"><img src="<?php echo e(url($imgurl)); ?>" alt="" class="rounded" width="90"/></a></p>
                              
                                <?php elseif($isimage==0): ?>
                                <p><a href="<?php echo e(URL::asset($imgurl)); ?>" class="card-link" target="_blank"><?php echo e($transition['document_name']); ?></a></p>
                                <?php endif; ?>
                                <?php endif; ?>
                              
                                <?php if(!empty($transition['investigator_assign_status'])): ?>
                                    <?php $invstatusbadge='';
                                    if($transition['investigator_assign_status']==trans('form.timeline_status.in progress') || $transition['investigator_assign_status']==trans('form.timeline_status.Assigned') || $transition['investigator_assign_status']==trans('form.timeline_status.Report Writing'))
                                    $invstatusbadge='warning';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.Completed'))
                                    $invstatusbadge='success';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.Report Submitted'))
                                    $invstatusbadge='primary';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.Return To Center'))
                                    $invstatusbadge='info';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.inactive') || $transition['investigator_assign_status']==trans('form.timeline_status.Not Completed') || $transition['investigator_assign_status']==trans('form.timeline_status.Declined'))
                                    $invstatusbadge='danger';
                                    else
                                    $invstatusbadge = 'primary';
                                    ?>
                                <span class="badge dt-badge badge-<?php echo e($invstatusbadge); ?>"> <?php echo e($transition['investigator_assign_status']); ?></span>
                                    <?php endif; ?>

                                    <?php if(!empty($transition['deliveryboy_assign_status'])): ?>
                                    <?php $invstatusbadge='';
                                    if($transition['deliveryboy_assign_status']==trans('form.timeline_status.in progress') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Assigned') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Report Writing'))
                                    $invstatusbadge='warning';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Done And Delivered'))
                                    $invstatusbadge='success';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Report Submitted'))
                                    $invstatusbadge='primary';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Return To Center'))
                                    $invstatusbadge='info';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.inactive') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Done And Not Delivered') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Not Delivered'))
                                    $invstatusbadge='danger';
                                    else
                                    $invstatusbadge = 'primary';
                                    ?>
                                <span class="badge dt-badge badge-<?php echo e($invstatusbadge); ?>"> <?php echo e($transition['deliveryboy_assign_status']); ?></span>
                                    <?php endif; ?>
                               
                                
                                    <?php if($transition['event']=='investigation_generateinvoice'): ?>
                                    <a target="_blank" href="<?php echo e(route('investigation.showinvoice', [Crypt::encrypt($invn->id)])); ?>" class="btn btn-primary btn-rounded waves-effect waves-light m-t-5" title=" <?php echo e(trans('form.investigationinvoice.viewandgenerateinvoice')); ?>">
                                        <?php echo e(trans('form.investigationinvoice.view_invoice')); ?>

                                    </a>
                                    <?php endif; ?>
                                <span class="cd-date"><?php echo $transition['event_date'];?></span>
                            </div>
                            <!-- cd-timeline-content -->
                        </div>
                        <!-- cd-timeline-block -->
                        <?php endif; ?>
                        
                         <?php $indxtrans++; ?>
                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                         <?php endif; ?>
                        
                    </section>
                    <!-- cd-timeline -->

                </div>
            </div>
        </div>
    </div>
    <!-- end col -->
<?php endif; ?>
<?php if($type=='client'): ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <section id="cd-timeline" class="cd-container" dir="ltr">
                    <?php if(count($transitions)>0): ?>
                    <?php $indxtrans = 1; ?>
                    <?php $__currentLoopData = $transitions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                   
                   
                    <?php if(($indxtrans%2)!=0): ?>
                    <div class="cd-timeline-block <?php echo e(count($transitions) != $indxtrans ? 'timeline-right' : ''); ?>">
                        <div class="cd-timeline-img bg-success <?php echo e(count($transitions) == $indxtrans ? 'd-xl-none' : ''); ?>">
                            <i class="mdi mdi-adjust"></i>
                        </div>
                        <!-- cd-timeline-img -->
                       

                        <div class="cd-timeline-content">
                            <h3><?php echo $transition['event_title'];?>  
                                <?php if(!empty($transition['investigation_status'])): ?>
                                <?php $statusbadge='';
                                if($transition['investigation_status']==trans('form.timeline_status.Pending Approval') || $transition['investigation_status']==trans('form.timeline_status.Open') || $transition['investigation_status']==trans('form.timeline_status.Modification Required'))
                                $statusbadge='warning';
                                else if($transition['investigation_status']==trans('form.timeline_status.Assigned'))
                                $statusbadge='dark';
                                else if($transition['investigation_status']==trans('form.timeline_status.Approved'))
                                $statusbadge='success';
                                else if($transition['investigation_status']==trans('form.timeline_status.Declined') || $transition['investigation_status']==trans('form.timeline_status.Closed') || $transition['investigation_status']==trans('form.timeline_status.Cancelled'))
                                $statusbadge='danger';
                                else
                                $statusbadge = 'primary';
                                ?>
                                <span class="badge dt-badge badge-<?php echo e($statusbadge); ?>"> <?php echo e($transition['investigation_status']); ?></span>
                                <?php endif; ?>
                               

                            </h3>
                            <p class="mb-0 text-muted"><?php echo $transition['event_desc'];?><br><br></p>
                            <?php if(!empty($transition['document_filename']) && ((!empty($invn->invoice) && $invn->invoice->status == 'paid') || ($transition['event_by']==auth()->user()->id))): ?>
                            <?php $isimage=0;
                            $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp'];
                                if(in_array($transition['document_type'], $imageExtensions))
                                    {$isimage=1;
                                    }
                                    $imgurl='/investigation-documents/'.$transition['document_filename'];
                            ?>
                            <?php if($isimage==1): ?>
                            <p><?php echo e($transition['document_name']); ?></p>
                            <p><a href="<?php echo e(URL::asset($imgurl)); ?>" target="_blank"><img src="<?php echo e(url($imgurl)); ?>" alt="" class="rounded" width="90"/></a></p>
                          
                            <?php elseif($isimage==0): ?>
                            <p><a href="<?php echo e(URL::asset($imgurl)); ?>" class="card-link" target="_blank"><?php echo e($transition['document_name']); ?></a></p>
                            <?php endif; ?>
                            <?php else: ?>
                            <?php if(!empty($transition['document_name'])): ?><p><?php echo e($transition['document_name']); ?></p><?php endif; ?>
                            <?php endif; ?>
                            <?php if(!empty($transition['investigator_assign_status'])): ?>
                                    <?php $invstatusbadge='';
                                    if($transition['investigator_assign_status']==trans('form.timeline_status.in progress') || $transition['investigator_assign_status']==trans('form.timeline_status.Assigned') || $transition['investigator_assign_status']==trans('form.timeline_status.Report Writing'))
                                    $invstatusbadge='warning';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.Completed'))
                                    $invstatusbadge='success';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.Report Submitted'))
                                    $invstatusbadge='primary';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.Return To Center'))
                                    $invstatusbadge='info';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.inactive') || $transition['investigator_assign_status']==trans('form.timeline_status.Not Completed') || $transition['investigator_assign_status']==trans('form.timeline_status.Declined'))
                                    $invstatusbadge='danger';
                                    else
                                    $invstatusbadge = 'primary';
                                    ?>
                                <span class="badge dt-badge badge-<?php echo e($invstatusbadge); ?>"> <?php echo e($transition['investigator_assign_status']); ?></span>
                                    <?php endif; ?>

                                    <?php if(!empty($transition['deliveryboy_assign_status'])): ?>
                                    <?php $invstatusbadge='';
                                    if($transition['deliveryboy_assign_status']==trans('form.timeline_status.in progress') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Assigned') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Report Writing'))
                                    $invstatusbadge='warning';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Done And Delivered'))
                                    $invstatusbadge='success';
                                    else if(trans('form.timeline_status.Report Submitted'))
                                    $invstatusbadge='primary';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Return To Center'))
                                    $invstatusbadge='info';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.inactive') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Done And Not Delivered') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Not Delivered'))
                                    $invstatusbadge='danger';
                                    else
                                    $invstatusbadge = 'primary';
                                    ?>
                                <span class="badge dt-badge badge-<?php echo e($invstatusbadge); ?>"> <?php echo e($transition['deliveryboy_assign_status']); ?></span>
                                    <?php endif; ?>
                                    <?php if($transition['event']=='investigation_generateinvoice'): ?>
                                    <a target="_blank" href="<?php echo e(route('investigation.showinvoice', [Crypt::encrypt($invn->id)])); ?>" class="btn btn-primary btn-rounded waves-effect waves-light m-t-5" title=" <?php echo e(trans('form.investigationinvoice.viewandgenerateinvoice')); ?>">
                                        <?php echo e(trans('form.investigationinvoice.view_invoice')); ?>

                                    </a>
                                    <?php endif; ?>
                            <span class="cd-date"><?php echo $transition['event_date'];?></span>
                        </div>
                        <!-- cd-timeline-content -->
                    </div>
                    <!-- cd-timeline-block -->
                    <?php else: ?>  
                    <div class="cd-timeline-block <?php echo e(count($transitions) != $indxtrans ? 'timeline-left' : ''); ?> ">
                        <div class="cd-timeline-img bg-danger <?php echo e(count($transitions) == $indxtrans ? 'd-xl-none' : ''); ?>">
                            <i class="mdi mdi-adjust"></i>
                        </div>
                        <!-- cd-timeline-img -->
                       
                        <div class="cd-timeline-content">
                            <h3><?php echo $transition['event_title'];?>  
                                <?php if(!empty($transition['investigation_status'])): ?>
                                <?php $statusbadge='';
                                if($transition['investigation_status']==trans('form.timeline_status.Pending Approval') || $transition['investigation_status']==trans('form.timeline_status.Open') || $transition['investigation_status']==trans('form.timeline_status.Modification Required'))
                                $statusbadge='warning';
                                else if($transition['investigation_status']==trans('form.timeline_status.Assigned'))
                                $statusbadge='dark';
                                else if($transition['investigation_status']==trans('form.timeline_status.Approved'))
                                $statusbadge='success';
                                else if($transition['investigation_status']==trans('form.timeline_status.Declined') || $transition['investigation_status']==trans('form.timeline_status.Closed') || $transition['investigation_status']==trans('form.timeline_status.Cancelled'))
                                $statusbadge='danger';
                                else
                                $statusbadge='primary';
                                ?>
                                <span class="badge dt-badge badge-<?php echo e($statusbadge); ?>"> <?php echo e($transition['investigation_status']); ?></span>
                                <?php endif; ?>
                            </h3>
                            <p class="mb-4 text-muted"><?php echo $transition['event_desc'];?></p>
                            
                            <?php if(!empty($transition['document_filename']) && ((!empty($invn->invoice) && $invn->invoice->status == 'paid') || ($transition['event_by']==auth()->user()->id))): ?>
                            <?php $isimage=0;
                            $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp'];
                                if(in_array($transition['document_type'], $imageExtensions))
                                    {$isimage=1;
                                    }
                                    $imgurl='/investigation-documents/'.$transition['document_filename'];
                            ?>
                            <?php if($isimage==1): ?>
                            <p><?php echo e($transition['document_name']); ?></p>
                            <p><a href="<?php echo e(URL::asset($imgurl)); ?>" target="_blank"><img src="<?php echo e(url($imgurl)); ?>" alt="" class="rounded" width="90"/></a></p>
                          
                            <?php elseif($isimage==0): ?>
                            <p><a href="<?php echo e(URL::asset($imgurl)); ?>" class="card-link" target="_blank"><?php echo e($transition['document_name']); ?></a></p>
                            <?php endif; ?>
                            <?php else: ?>
                            <?php if(!empty($transition['document_name'])): ?><p><?php echo e($transition['document_name']); ?></p><?php endif; ?>
                            <?php endif; ?>
                          
                            <?php if(!empty($transition['investigator_assign_status'])): ?>
                            <?php $invstatusbadge='';
                            if($transition['investigator_assign_status']==trans('form.timeline_status.in progress') || $transition['investigator_assign_status']==trans('form.timeline_status.Assigned') || $transition['investigator_assign_status']==trans('form.timeline_status.Report Writing'))
                            $invstatusbadge='warning';
                            else if($transition['investigator_assign_status']==trans('form.timeline_status.Completed'))
                            $invstatusbadge='success';
                            else if($transition['investigator_assign_status']==trans('form.timeline_status.Report Submitted'))
                            $invstatusbadge='primary';
                            else if($transition['investigator_assign_status']==trans('form.timeline_status.Return To Center'))
                            $invstatusbadge='info';
                            else if($transition['investigator_assign_status']==trans('form.timeline_status.inactive') || $transition['investigator_assign_status']==trans('form.timeline_status.Not Completed') || $transition['investigator_assign_status']==trans('form.timeline_status.Declined'))
                            $invstatusbadge='danger';
                            else
                            $invstatusbadge = 'primary';
                            ?>
                        <span class="badge dt-badge badge-<?php echo e($invstatusbadge); ?>"> <?php echo e($transition['investigator_assign_status']); ?></span>
                            <?php endif; ?>

                            <?php if(!empty($transition['deliveryboy_assign_status'])): ?>
                                    <?php $invstatusbadge='';
                                    if($transition['deliveryboy_assign_status']==trans('form.timeline_status.in progress') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Assigned') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Report Writing'))
                                    $invstatusbadge='warning';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Done And Delivered'))
                                    $invstatusbadge='success';
                                    else if(trans('form.timeline_status.Report Submitted'))
                                    $invstatusbadge='primary';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Return To Center'))
                                    $invstatusbadge='info';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.inactive') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Done And Not Delivered') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Not Delivered'))
                                    $invstatusbadge='danger';
                                    else
                                    $invstatusbadge = 'primary';
                                    ?>
                                <span class="badge dt-badge badge-<?php echo e($invstatusbadge); ?>"> <?php echo e($transition['deliveryboy_assign_status']); ?></span>
                                    <?php endif; ?>
                           
                            
                            <?php if($transition['event']=='investigation_generateinvoice'): ?>
                            <a target="_blank" href="<?php echo e(route('investigation.showinvoice', [Crypt::encrypt($invn->id)])); ?>" class="btn btn-primary btn-rounded waves-effect waves-light m-t-5" title=" <?php echo e(trans('form.investigationinvoice.viewandgenerateinvoice')); ?>">
                           <?php echo e(trans('form.investigationinvoice.view_invoice')); ?>

                            </a>
                            <?php endif; ?>
                            <span class="cd-date"><?php echo $transition['event_date'];?></span>
                        </div>
                        <!-- cd-timeline-content -->
                    </div>
                    <!-- cd-timeline-block -->
                    <?php endif; ?>
                    
                     <?php $indxtrans++; ?>
                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                     <?php endif; ?>
                    
                </section>
                <!-- cd-timeline -->

            </div>
        </div>
    </div>
    <!-- end col -->
</div>
<?php endif; ?>
<?php if($type=='other'): ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <section id="cd-timeline" class="cd-container" dir="ltr">
                    <?php if(count($transitions)>0): ?>
                    <?php $indxtrans = 1; ?>
                    <?php $__currentLoopData = $transitions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $active=0;$inarray=array('investigation_generateinvoice','investigator_changeassignee','investigator_changestatus','investigator_assigneed'); // add investigator events on this
                   $delarray=array('investigation_generateinvoice','deliveryboy_assigneed','deliveryboy_changestatus','deliveryboy_changeassignee');//add delivery boy events on this
                   $ownarray=array('investigation_generateinvoice','mail_send','investigation_documentdelete','investigation_documentupload'); // add commen event if need get user data to show
                   $checkarray=array();
                   ?>
                   <?php if(isInvestigator()): ?>
                   <?php  $checkarray=$delarray;?>
                   <?php elseif(isDeliveryboy()): ?>
                   <?php  $checkarray=$inarray;?>
                   <?php endif; ?>
                   <?php if(!in_array($transition['event'], $checkarray) || (in_array($transition['event'], $ownarray) && $transition['event_by']==auth()->user()->id)): ?>
                   <?php $active=1;?>
                   <?php endif; ?>
                   <?php if($active==1): ?>
                    <?php if(($indxtrans%2)!=0): ?>
                    <div class="cd-timeline-block <?php echo e(((count($transitions) != $indxtrans) && ($active==1)) ? 'timeline-right' : ''); ?>">
                        <div class="cd-timeline-img bg-success <?php echo e(((count($transitions) != $indxtrans) && ($active==1)) ? '' : 'd-xl-none'); ?>">
                            <i class="mdi mdi-adjust"></i>
                        </div>
                        <!-- cd-timeline-img -->
                       

                        <div class="cd-timeline-content">
                            <h3><?php echo $transition['event_title'];?>  
                                <?php if(!empty($transition['investigation_status'])): ?>
                                    <?php $statusbadge='';
                                    if($transition['investigation_status']==trans('form.timeline_status.Pending Approval') || $transition['investigation_status']==trans('form.timeline_status.Open') || $transition['investigation_status']==trans('form.timeline_status.Modification Required'))
                                    $statusbadge='warning';
                                    else if($transition['investigation_status']==trans('form.timeline_status.Assigned'))
                                    $statusbadge='dark';
                                    else if($transition['investigation_status']==trans('form.timeline_status.Approved'))
                                    $statusbadge='success';
                                    else if($transition['investigation_status']==trans('form.timeline_status.Declined') || $transition['investigation_status']==trans('form.timeline_status.Closed') || $transition['investigation_status']==trans('form.timeline_status.Cancelled'))
                                    $statusbadge='danger';
                                    else
                                    $statusbadge='primary';
                                    ?>
                                    <span class="badge dt-badge badge-<?php echo e($statusbadge); ?>"> <?php echo e($transition['investigation_status']); ?></span>
                                    <?php endif; ?>
                               

                            </h3>
                            <p class="mb-0 text-muted"><?php echo $transition['event_desc'];?><br><br></p>
                            <?php if(!empty($transition['document_filename'])): ?>
                            <?php $isimage=0;
                            $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp'];
                                if(in_array($transition['document_type'], $imageExtensions))
                                    {$isimage=1;
                                    }
                                    $imgurl='/investigation-documents/'.$transition['document_filename'];
                            ?>
                            <?php if($isimage==1): ?>
                            <p><?php echo e($transition['document_name']); ?></p>
                            <p><a href="<?php echo e(URL::asset($imgurl)); ?>" target="_blank"><img src="<?php echo e(url($imgurl)); ?>" alt="" class="rounded" width="90"/></a></p>
                          
                            <?php elseif($isimage==0): ?>
                            <p><a href="<?php echo e(URL::asset($imgurl)); ?>" class="card-link" target="_blank"><?php echo e($transition['document_name']); ?></a></p>
                            <?php endif; ?>
                            <?php endif; ?>
                            <?php if(!empty($transition['investigator_assign_status'])): ?>
                                    <?php $invstatusbadge='';
                                    if($transition['investigator_assign_status']==trans('form.timeline_status.in progress') || $transition['investigator_assign_status']==trans('form.timeline_status.Assigned') || $transition['investigator_assign_status']==trans('form.timeline_status.Report Writing'))
                                    $invstatusbadge='warning';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.Completed'))
                                    $invstatusbadge='success';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.Report Submitted'))
                                    $invstatusbadge='primary';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.Return To Center'))
                                    $invstatusbadge='info';
                                    else if($transition['investigator_assign_status']==trans('form.timeline_status.inactive') || $transition['investigator_assign_status']==trans('form.timeline_status.Not Completed') || $transition['investigator_assign_status']==trans('form.timeline_status.Declined'))
                                    $invstatusbadge='danger';
                                    else
                                    $invstatusbadge='primary';
                                    ?>
                                <span class="badge dt-badge badge-<?php echo e($invstatusbadge); ?>"> <?php echo e($transition['investigator_assign_status']); ?></span>
                                    <?php endif; ?>

                                    <?php if(!empty($transition['deliveryboy_assign_status'])): ?>
                                    <?php $invstatusbadge='';
                                    if($transition['deliveryboy_assign_status']==trans('form.timeline_status.in progress') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Assigned') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Report Writing'))
                                    $invstatusbadge='warning';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Done And Delivered'))
                                    $invstatusbadge='success';
                                    else if(trans('form.timeline_status.Report Submitted'))
                                    $invstatusbadge='primary';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Return To Center'))
                                    $invstatusbadge='info';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.inactive') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Done And Not Delivered') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Not Delivered'))
                                    $invstatusbadge='danger';
                                    else
                                    $invstatusbadge='primary';
                                    ?>
                                <span class="badge dt-badge badge-<?php echo e($invstatusbadge); ?>"> <?php echo e($transition['deliveryboy_assign_status']); ?></span>
                                    <?php endif; ?>
                            <span class="cd-date"><?php echo $transition['event_date'];?></span>
                        </div>
                        <!-- cd-timeline-content -->
                    </div>
                    <!-- cd-timeline-block -->
                    <?php else: ?>  
                    <div class="cd-timeline-block <?php echo e(((count($transitions) != $indxtrans) && ($active==1)) ? 'timeline-left' : ''); ?> ">
                        <div class="cd-timeline-img bg-danger <?php echo e(((count($transitions) != $indxtrans) && ($active==1)) ? '' : 'd-xl-none'); ?>">
                            <i class="mdi mdi-adjust"></i>
                        </div>
                        <!-- cd-timeline-img -->
                       
                        <div class="cd-timeline-content">
                            <h3><?php echo $transition['event_title'];?>  
                                <?php if(!empty($transition['investigation_status'])): ?>
                                    <?php $statusbadge='';
                                    if($transition['investigation_status']==trans('form.timeline_status.Pending Approval') || $transition['investigation_status']==trans('form.timeline_status.Open') || $transition['investigation_status']==trans('form.timeline_status.Modification Required'))
                                    $statusbadge='warning';
                                    else if($transition['investigation_status']==trans('form.timeline_status.Assigned'))
                                    $statusbadge='dark';
                                    else if($transition['investigation_status']==trans('form.timeline_status.Approved'))
                                    $statusbadge='success';
                                    else if($transition['investigation_status']==trans('form.timeline_status.Declined') || $transition['investigation_status']==trans('form.timeline_status.Closed') || $transition['investigation_status']==trans('form.timeline_status.Cancelled'))
                                    $statusbadge='danger';
                                    else
                                    $statusbadge='primary';
                                    ?>
                                    <span class="badge dt-badge badge-<?php echo e($statusbadge); ?>"> <?php echo e($transition['investigation_status']); ?></span>
                                    <?php endif; ?>
                            </h3>
                            <p class="mb-4 text-muted"><?php echo $transition['event_desc'];?></p>
                            <?php if(!empty($transition['document_filename'])): ?>
                            <?php $isimage=0;
                            $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp'];
                                if(in_array($transition['document_type'], $imageExtensions))
                                    {$isimage=1;
                                    }
                                    $imgurl='/investigation-documents/'.$transition['document_filename'];
                            ?>
                            <?php if($isimage==1): ?>
                            <p><?php echo e($transition['document_name']); ?></p>
                            <p><a href="<?php echo e(URL::asset($imgurl)); ?>" target="_blank"><img src="<?php echo e(url($imgurl)); ?>" alt="" class="rounded" width="90"/></a></p>
                          
                            <?php elseif($isimage==0): ?>
                            <p><a href="<?php echo e(URL::asset($imgurl)); ?>" class="card-link" target="_blank"><?php echo e($transition['document_name']); ?></a></p>
                            <?php endif; ?>
                            <?php endif; ?>
                          
                            <?php if(!empty($transition['investigator_assign_status'])): ?>
                            <?php $invstatusbadge='';
                            if($transition['investigator_assign_status']==trans('form.timeline_status.in progress') || $transition['investigator_assign_status']==trans('form.timeline_status.Assigned') || $transition['investigator_assign_status']==trans('form.timeline_status.Report Writing'))
                            $invstatusbadge='warning';
                            else if($transition['investigator_assign_status']==trans('form.timeline_status.Completed'))
                            $invstatusbadge='success';
                            else if($transition['investigator_assign_status']==trans('form.timeline_status.Report Submitted'))
                            $invstatusbadge='primary';
                            else if($transition['investigator_assign_status']==trans('form.timeline_status.Return To Center'))
                            $invstatusbadge='info';
                            else if($transition['investigator_assign_status']==trans('form.timeline_status.inactive') || $transition['investigator_assign_status']==trans('form.timeline_status.Not Completed') || $transition['investigator_assign_status']==trans('form.timeline_status.Declined'))
                            $invstatusbadge='danger';
                            else
                            $invstatusbadge='primary';
                            ?>
                        <span class="badge dt-badge badge-<?php echo e($invstatusbadge); ?>"> <?php echo e($transition['investigator_assign_status']); ?></span>
                            <?php endif; ?>

                            <?php if(!empty($transition['deliveryboy_assign_status'])): ?>
                                    <?php $invstatusbadge='';
                                    if($transition['deliveryboy_assign_status']==trans('form.timeline_status.in progress') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Assigned') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Report Writing'))
                                    $invstatusbadge='warning';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Done And Delivered'))
                                    $invstatusbadge='success';
                                    else if(trans('form.timeline_status.Report Submitted'))
                                    $invstatusbadge='primary';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.Return To Center'))
                                    $invstatusbadge='info';
                                    else if($transition['deliveryboy_assign_status']==trans('form.timeline_status.inactive') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Done And Not Delivered') || $transition['deliveryboy_assign_status']==trans('form.timeline_status.Not Delivered'))
                                    $invstatusbadge='danger';
                                    else
                                    $invstatusbadge='primary';
                                    ?>
                                <span class="badge dt-badge badge-<?php echo e($invstatusbadge); ?>"> <?php echo e($transition['deliveryboy_assign_status']); ?></span>
                                    <?php endif; ?>
                           
                            
                            <span class="cd-date"><?php echo $transition['event_date'];?></span>
                        </div>
                        <!-- cd-timeline-content -->
                    </div>
                    <!-- cd-timeline-block -->
                    <?php endif; ?>
                    
                     <?php $indxtrans++; ?>
                     <?php endif; ?>
                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                     <?php endif; ?>
                    
                </section>
                <!-- cd-timeline -->

            </div>
        </div>
    </div>
    <!-- end col -->
</div>
<?php endif; ?>
<?php /**PATH /var/www/html/uvda/resources/views/investigation/timeline.blade.php ENDPATH**/ ?>