<?php $__env->startSection('title'); ?> <?php echo e(trans('form.login')); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
 <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-6">
                    <div class="card overflow-hidden">
                        <div class="card-body pt-0">
                            <h3 class="text-center mt-4">
                                
                                <span style="color: blue;font-size:20px;">AlpaMusic</span>
                            
                            </h3>
                            <div class="p-3">
                                <h4 class="text-muted font-size-18 mb-1 text-center"><?php echo app('translator')->get('form.welcome_back'); ?> !</h4>
                                <p class="text-muted text-center"><?php echo app('translator')->get('form.sign_in_to_continue_to'); ?> <?php echo app('translator')->get('form.app_name'); ?></p>
                                <?php if(Session::has('status')): ?>
                                <div class="alert alert-card alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <?php echo e(Session::get('status')); ?>

                                </div>
                                <?php endif; ?>
                                
                                <form method="POST" id="login-form" class="form-horizontal mt-4" action="<?php echo e(route('login')); ?>">
                                       <?php echo csrf_field(); ?>
                                    <div class="form-group">
                                        <label for="username"><?php echo app('translator')->get('form.email_address'); ?><span class="text-danger">*</span></label>
                                         <input id="email" type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus placeholder="<?php echo app('translator')->get('form.email'); ?>">
                                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                    </div>
                                    <div class="form-group">
                                        <label for="userpassword"><?php echo app('translator')->get('form.password'); ?><span class="text-danger">*</span></label>
                                        <input id="password" type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" required autocomplete="current-password" placeholder="<?php echo app('translator')->get('form.password'); ?>">
                                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="form-group cptch-container">
                                        <div class="g-recaptcha m-b-10"
                                             data-sitekey="<?php echo e(env('GOOGLE_RECAPTCHA_SITE_KEY')); ?>">
                                        </div>
                                        <span class="invalid-feedback gr-error" role="alert" style="display: none;">
                                            <strong><?php echo e(trans('form.captcha_is_required')); ?></strong>
                                        </span>
                                        <?php $__errorArgs = ['g-recaptcha-response'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-6">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="remember" id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                                        <label class="custom-control-label" for="remember"><?php echo app('translator')->get('form.remember_me'); ?></label>
                                            </div>
                                        </div>
                                        <div class="col-6 text-right">
                                            <button class="btn btn-primary w-md waves-effect waves-light" type="submit"><?php echo app('translator')->get('form.login'); ?></button>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0 row">
                                        <div class="col-12">
                                            <a href="<?php echo e(route('password.request')); ?>" class="text-muted"><i class="mdi mdi-lock"></i> <?php echo app('translator')->get('form.forgot_your_password'); ?></a>
                                        </div>
                                    </div>
                                    <div class="mt-5 text-center">
                                        <p class="mb-0"><?php echo app('translator')->get('form.dont_have_an_account'); ?> <a href="<?php echo e(route('clientregister')); ?>" class="text-primary"> <?php echo e(trans('form.clients')); ?></a> | <a href="<?php echo e(route('investigatorregister')); ?>" class="text-primary"><?php echo e(trans('form.investigators')); ?></a> | <a href="<?php echo e(route('deliveryboyregister')); ?>" class="text-primary"><?php echo e(trans('form.deliveryboys')); ?></a> </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        
                        <p><?php echo e(trans('general.developedby')); ?> <a target="_blank" href="https://soft-l.com/"><?php echo e(trans('general.company')); ?> </a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-js'); ?>
<script src="<?php echo e(URL::asset('/libs/jquery-validate/1.19.0/jquery.validate.js')); ?>"></script> 
<?php if(App::isLocale('hr')): ?>
<script src="<?php echo e(URL::asset('/libs/jquery-validate/1.19.0/localization/messages_he.js')); ?>"></script>
<?php endif; ?>
    <script type="text/javascript">
    // This function for Set Responsive Google ReCaptcha 
    function rescaleCaptcha() {
        var width = $('.g-recaptcha').parent().width();
        var scale;
        if (width < 302) {
            scale = width / 302;
        } else {
            scale = 1.0;
        }
        $('.g-recaptcha').css('transform', 'scale(' + scale + ')');
        $('.g-recaptcha').css('-webkit-transform', 'scale(' + scale + ')');
        $('.g-recaptcha').css('transform-origin', '0 0');
        $('.g-recaptcha').css('-webkit-transform-origin', '0 0');
    }
    $(document).ready(function($) {
        rescaleCaptcha();
        $(window).resize(function() {
            rescaleCaptcha();
        });
        
        $("#login-form").validate({
            rules: { 
                email: {
                        required: true ,
                        email: true
                    },
                    password:{
                        required: true,
                    }
                },
            });
          
    });

    $("#login-form").submit(function (event) {
     if (grecaptcha.getResponse() == "") {
            $('.gr-error').show();
            return false;
        } else {
            $('.gr-error').hide();
            return true;
        }
         
 
  
    });

</script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.auth-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/uvda/resources/views/auth/login.blade.php ENDPATH**/ ?>