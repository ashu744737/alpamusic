
<button type="button" class="btn header-item noti-icon waves-effect" onclick='openDropdown();' id="page-header-notifications-dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="ti-bell"></i>
   <?php if(count($notificationdata)>0): ?> <span class="noticount badge badge-danger badge-pill" id="notificationCount"><?php echo e(count($notificationdata)); ?></span><?php endif; ?>
</button>
<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0" aria-labelledby="page-header-notifications-dropdown" id="notification-messages">
    <?php if(count($notificationdata)>0): ?>
    <div class="p-3">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="m-0" id="notification_label_count"> <?php echo e(trans('content.notificationdata.notifications')); ?> (<?php echo e(count($notificationdata)); ?>) </h5>
            </div>
        </div>
    </div>
    <div data-simplebar style="max-height: 230px;">
        <?php $__currentLoopData = $notificationdata; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php 
            $item = $notification->notification;
            $eventjsondata = json_decode($item->data, true)['data'];
            $brdlbl='success';$icnlbl='message';
            if($item->event=='investigation_documentupload')
            {$brdlbl='success';$icnlbl='image';}
            if($item->event=='investigation_documentdelete')
            {$brdlbl='danger';$icnlbl='image';}
            if($item->event=='investigator_assigneed' || $item->event=='investigator_changeassignee')
            {$brdlbl='success';$icnlbl='account';}
            if($item->event=='investigation_action' && $eventjsondata['type']=='declined')
            {$brdlbl='danger';$icnlbl='cash-usd';}
            if($item->event=='investigation_action' && $eventjsondata['type']=='approved')
            {$brdlbl='success';$icnlbl='cash-usd';}
            if($item->event=='notify-unpaid-invoice')
            {$brdlbl='danger';$icnlbl='alert-circle';}
            if($item->event=='invoice-not-send-to-client')
            {$brdlbl='danger';$icnlbl='alert-circle';}
            $link='';
            if($item->with_link==1) { $link=route('investigation.show', Crypt::encrypt($item->investigation_id)); }
            if(isset($item->redirect_link) && !empty($item->redirect_link)){ $link = $item->redirect_link; }
            
            if($item->event == 'user_approve' || $item->event == 'investigator_approve' || $item->event == 'deliveryboy_approve'){
                if(isClient() || isInvestigator() || isDeliveryboy()){
                    $link = '';
                }
            }
        ?>
        <div class="text-reset notification-item" id="notification_<?php echo e($notification->id); ?>">
            <div class="media" style="cursor: pointer;">
                <div class="avatar-xs mr-3" onclick='readNotifications("<?php echo e($notification->id); ?>", "<?php echo e($link); ?>");'>
                    <span class="avatar-title border-<?php echo e($brdlbl); ?> rounded-circle ">
                        <i class="mdi mdi-<?php echo e($icnlbl); ?>"></i>
                    </span>
                </div>
                <div class="media-body" onclick='readNotifications("<?php echo e($notification->id); ?>", "<?php echo e($link); ?>");'>
                    <?php if(config('app.locale') == 'hr'): ?>
                        <?php $ex=explode('by',$item->hr_message);?>
                    <?php else: ?>
                    <?php $ex=explode('by',$item->message);?>
                    <?php endif; ?>
                   
                    <?php if(!empty($ex[0])): ?><h6 class="mt-0 mb-1"><?php echo e($ex[0]); ?></h6><?php endif; ?>

                    <?php if(!empty($item->performby)): ?>
                        <?php if(($item->performby->user_type->type_name!=env('USER_TYPE_INVESTIGATOR') && $item->performby->user_type->type_name!=env('USER_TYPE_DELIVERY_BOY')) || (isAdmin() || isSM() )): ?>
                            <div class="text-muted">
                                <p class="mb-1"><?php echo e(trans('general.by')); ?> <?php echo e($item->performby->name); ?></p>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <div class="text-muted">
                        <p class="mb-1"><?php echo gmdate('d M h:i:s A', strtotime($item->created_at));?></p>
                    </div>
                </div>
                <div style="padding: 0 7px;" onclick='readNotifications("<?php echo e($notification->id); ?>","read");'>
                    <i class="fa fa-trash" style="margin-top: 40px;color:red;"></i>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
       
    </div>
    
    <?php else: ?>
    <div class="p-3">
        <div class="row align-items-center text-center">
            <div class="col">
                <h5 class="m-0"> <?php echo e(trans('content.notificationdata.you_have_no_notification')); ?> </h5>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>
<?php /**PATH /var/www/html/uvda/resources/views/layouts/partials/notification-data.blade.php ENDPATH**/ ?>