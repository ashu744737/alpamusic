<?php if($message = Session::get('success')): ?>
    <div class="alert alert-card alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <span>
            <?php echo e($message); ?>

        </span>
    </div>
<?php endif; ?>

<?php if($message = Session::get('error')): ?>
    <div class="alert alert-card alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <span><?php echo e($message); ?></span>
    </div>
<?php endif; ?>
<?php /**PATH /var/www/html/uvda/resources/views/layouts/partials/session-message.blade.php ENDPATH**/ ?>