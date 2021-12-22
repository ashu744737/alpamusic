<!DOCTYPE html>
<?php if(App::isLocale('hr')): ?>
<html lang="he">
<?php else: ?>
<html lang="en">
<?php endif; ?>

<head>
    <meta charset="utf-8" />
    <title> <?php echo $__env->yieldContent('title'); ?>  | <?php echo e(config('constants.app_title')); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?php echo e(trans('general.site_description')); ?>" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo e(URL::asset('/images/favicon.ico')); ?>"> 
    
    <!-- headerCss -->
    <?php echo $__env->yieldContent('headerCss'); ?>

    <!-- Bootstrap Css -->
    <link href="<?php echo e(URL::asset('/css/bootstrap.min.css')); ?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?php echo e(URL::asset('/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?php echo e(URL::asset('/css/app.min.css')); ?>" id="app-style" rel="stylesheet" type="text/css" />

    <link href="<?php echo e(URL::asset('/css/custom.css')); ?>" id="app-style" rel="stylesheet" type="text/css" />

</head>

<body>

    <?php echo $__env->yieldContent('content'); ?>
    
  
    <!-- JAVASCRIPT -->
    <script src="<?php echo e(URL::asset('/libs/jquery/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/bootstrap/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/metismenu/metismenu.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/simplebar/simplebar.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/node-waves/node-waves.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/jquery-sparkline/jquery-sparkline.min.js')); ?>"></script>
    <!-- App js -->
    <?php if(App::isLocale('hr')): ?>
    <script src="<?php echo e(URL::asset('/js/apphr.min.js')); ?>"></script>
    <script src='https://www.google.com/recaptcha/api.js?hl=iw' async defer></script>
    <?php else: ?>
    <script src="<?php echo e(URL::asset('/js/app.min.js')); ?>"></script>
    <script src='https://www.google.com/recaptcha/api.js' async defer></script>
    <?php endif; ?>
   
     <?php echo $__env->yieldContent('page-js'); ?>
</body>

</html><?php /**PATH /var/www/html/uvda/resources/views/layouts/auth-master.blade.php ENDPATH**/ ?>