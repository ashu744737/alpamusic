<!DOCTYPE html>
<?php if(App::isLocale('hr')): ?>
<html lang="he">
<?php else: ?>
<html lang="en">
<?php endif; ?>

<head>

    <meta charset="utf-8" />
    
    <title> <?php echo $__env->yieldContent('title'); ?>  | <?php echo e(env('APP_NAME')); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?php echo e(trans('general.site_description')); ?>" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo e(URL::asset('/images/favicon.ico')); ?>">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <!-- Bootstrap Css -->
    <link href="<?php echo e(URL::asset('/css/bootstrap.min.css')); ?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?php echo e(URL::asset('/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
    <?php if(App::isLocale('hr')): ?>
    <!-- App Rtl Css-->
    <link href="<?php echo e(URL::asset('/css/app-rtl.min.css')); ?>" id="app-style" rel="stylesheet" type="text/css" />
    <?php else: ?>
    <!-- App Css-->
    <link href="<?php echo e(URL::asset('/css/app.min.css')); ?>" id="app-style" rel="stylesheet" type="text/css" />
    <?php endif; ?>
    <!-- Custom Css-->
    <link href="<?php echo e(URL::asset('/css/custom.css')); ?>" id="app-style" rel="stylesheet" type="text/css" />

    <!-- headerCss -->
    <?php echo $__env->yieldContent('headerCss'); ?>
    <style>
        @media (max-width: 425px) {
            .navbar-brand-box {
                display: none !important;
            }
        }
    </style>
</head>

<body data-sidebar="dark">

    <!-- Begin page -->
    <div id="layout-wrapper">

         <?php echo $__env->make('layouts/partials/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

         <?php echo $__env->make('layouts/partials/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                  <!-- content -->
                   <?php echo $__env->yieldContent('content'); ?>


                  <?php echo $__env->make('layouts/partials/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                </div>
                <!-- end main content-->
            </div>
            <!-- END layout-wrapper -->

             <?php echo $__env->make('layouts/partials/rightbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
   
            <!-- JAVASCRIPT -->
            <script src="<?php echo e(URL::asset('/libs/jquery/jquery.min.js')); ?>"></script>
            <script src="<?php echo e(URL::asset('/libs/bootstrap/bootstrap.min.js')); ?>"></script>
            <script src="<?php echo e(URL::asset('/libs/metismenu/metismenu.min.js')); ?>"></script>
            <script src="<?php echo e(URL::asset('/libs/simplebar/simplebar.min.js')); ?>"></script>
            <script src="<?php echo e(URL::asset('/libs/node-waves/node-waves.min.js')); ?>"></script>
            <script src="<?php echo e(URL::asset('/libs/jquery-sparkline/jquery-sparkline.min.js')); ?>"></script>
            <script>
                ajax_call();
                function ajax_call(){
                    $.ajax({
                        type: "GET",
                        url:"<?php echo e(route('user.getnotification')); ?>",
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            // console.log(data,'data')
                            if(data.status == 1){
                                $('#notificationHTML').html(data.html);   
                            }
                        },
                        error: function (xhr, textStatus, errorThrown) {
                        console.log("XHR",xhr);
                        console.log("status",textStatus);
                        console.log("Error in",errorThrown);
                        }
                    });
                };
                const interval = setInterval(function() {
                    if(!($('#notification-messages').hasClass('show'))){
                        ajax_call();
                    }
                }, 10000);

                function openDropdown() {
                    if($('#notification-messages').hasClass('show')){
                        //$('#notification-messages').toggleClass('open');
                        $('#notification-messages').removeClass('show');
                    } else {
                        $('#notification-messages').toggleClass('open');
                        $('#notification-messages').addClass('show');
                    }
                }

                function closeDropdown(){
                    if($('#notification-messages').hasClass('show')){
                        //$('#notification-messages').toggleClass('open');
                        $('#notification-messages').removeClass('show');
                    }
                }

                $('.vertical-menu').on('click', function(){
                    closeDropdown();
                });
                $('.main-content').on('click', function(){
                    closeDropdown();
                });

                function readNotifications(id, link) {
                    if(link != 'read'){
                        location.href = link;
                    }
                    if(link=='read'){
                        $('.'+id).addClass('d-none');
                            $.ajax({
                            url: '/read-notification/'+id,
                            type: "GET",
                            data: {
                                "_token": "<?php echo e(csrf_token()); ?>"
                            },
                            success: function( response ) {
                                $(`#notification_${id}`).remove();
                                let count = $('#notificationCount').html();
                                if(count > 0){
                                    count = count - 1;
                                    $('#notificationCount').html(count);
                                    let notificationStr = `<?php echo e(trans('content.notificationdata.notifications')); ?> (${count})`
                                    $('#notification_label_count').html(notificationStr);
                                }
                                // ajax_call();
                            }
                        });
                    }
                }
            </script>
            <!-- footerScript -->
             <?php echo $__env->yieldContent('footerScript'); ?>

            <!-- App js -->
            <?php if(App::isLocale('hr')): ?>
            <script src="<?php echo e(URL::asset('/js/apphr.min.js')); ?>"></script>
            <?php else: ?>
            <script src="<?php echo e(URL::asset('/js/app.min.js')); ?>"></script>
            <?php endif; ?>
</body>

</html><?php /**PATH /var/www/html/uvda/resources/views/layouts/master.blade.php ENDPATH**/ ?>