<div class="tab-pane p-3" id="timeline" role="tabpanel">
    <?php if(isAdmin() || isSM()): ?>
        <?php echo $__env->make('investigation.timeline', ['type'=>'admin','invn' => $invn], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php elseif(isClient()): ?>
        <?php echo $__env->make('investigation.timeline', ['type'=>'client','invn' => $invn], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
     <?php elseif(isInvestigator() || isDeliveryboy()): ?>
        <?php echo $__env->make('investigation.timeline', ['type'=>'other','invn' => $invn], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>   
    <?php endif; ?>
</div><?php /**PATH /var/www/html/uvda/resources/views/investigation/partials/timeline.blade.php ENDPATH**/ ?>