<?php $__env->startSection('title'); ?> <?php echo e(trans('form.internal_user.add_user')); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('headerCss'); ?>
    <!-- headerCss -->
    <!-- DataTables -->
    <link href="<?php echo e(URL::asset('/libs/datatables/datatables.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::asset('/libs/datatables/colReorder.dataTables.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::asset('/libs/datatables/select.dataTables.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::asset('/libs/select2/select2.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::asset('/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(URL::asset('/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 col-sm-4 col-xs-12">
                            <h4 class="card-title"><?php echo e(trans('form.internal_user.add_user')); ?></h4>
                        </div>
                    </div>
                    <?php echo $__env->make('session-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <form action="<?php echo e(route('staff.store')); ?>" onSubmit="return dosubmit();" method="POST" id="create_user" class="form needs-validation">
                                <?php echo csrf_field(); ?>
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="ContactType" class="ul-form__label"><?php echo e(trans('form.internal_user.type')); ?> <span class="text-danger">*</span></label>
                                        <select onchange="changeusertype(this)" class="form-control" name="user_type_id" id="user_type_id">
                                            <option value=""><?php echo e(trans('form.internal_user.field.select_type')); ?></option>
                                            <?php $__currentLoopData = $userTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($type->id); ?>" <?php echo e(old('user_type_id') == $type->id ? 'selected' : ''); ?>><?php echo e(App::isLocale('hr')?$type->hr_type_name:$type->type_name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ['user_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="form-row  categorydiv <?php echo e(old('category') ? 'd-block' : 'd-none'); ?>">
                                    <div class="form-group col-12">
                                        <label  for="category"><?php echo e(trans('form.products_form.category')); ?> <span class="text-danger">*</span></label>
                                             
                                            <select name="category[]" id="category" class="select2 form-control select2-multiple" multiple="multiple">
                                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id=>$category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($category['id']); ?>" <?php echo e(old('category') == $category['id'] ? 'selected' : ''); ?>>
                                                <?php echo e(App::isLocale('hr')?$category['hr_name']:$category['name']); ?>

                                                </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php if($errors->has('category')): ?>
                                        <em class="invalid-feedback">
                                            <?php echo e($errors->first('category')); ?>

                                        </em>
                                    <?php endif; ?>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="Name" class="ul-form__label"><?php echo e(trans('form.internal_user.name')); ?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="<?php echo e(trans('form.internal_user.name')); ?>" value="<?php echo e(old('name')); ?>"  />
                                        <?php $__errorArgs = ['first_name'];
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
                                    <div class="form-group col-md-6">
                                        <label for="Email" class="ul-form__label"><?php echo e(trans('form.contact.field.email')); ?> <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="<?php echo e(trans('form.contact.field.email')); ?>" value="<?php echo e(old('email')); ?>" />
                                        <?php $__errorArgs = ['email'];
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
                                </div>
                                <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="userpassword"><?php echo e(trans('form.password')); ?> <span class="text-danger">*</span></label>
                                            <input type="password" minlength="6" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" required autocomplete="new-password" id="userpassword" placeholder="<?php echo e(trans('form.enter_password')); ?>">
                                            <?php $__errorArgs = ['password'];
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

                                    <div class="form-group col-md-6">
                                        <label for="userpassword"><?php echo e(trans('form.registration.confirm_password')); ?></label>
                                        <input type="password" minlength="6" name="password_confirmation" class="form-control" id="userconfirmpassword" required placeholder="<?php echo e(trans('form.registration.confirm_password')); ?>">
                                    </div>
                                </div>
                                <div class="form-row salarydiv <?php echo e(old('salary') ? 'd-block' : 'd-none'); ?>">
                                    <div class="form-group col-12">
                                        <label  for="salary"><?php echo e(trans('form.products_form.salary')); ?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="salary" name="salary" placeholder="<?php echo e(trans('form.products_form.salary')); ?>" value="<?php echo e(old('salary')); ?>"  />
                                        <?php if($errors->has('salary')): ?>
                                        <em class="invalid-feedback">
                                            <?php echo e($errors->first('salary')); ?>

                                        </em>
                                    <?php endif; ?>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12 mb-4">
                                        <button type="submit" class="btn btn-primary" id="create-customer-btn"><?php echo e(trans('form.create')); ?></button>
                                        <a href="<?php echo e(route('staff.index')); ?>" class="btn btn-outline-secondary m-1"><?php echo e(trans('form.cancel')); ?></a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footerScript'); ?>
<script src="<?php echo e(URL::asset('/libs/jquery-validate/1.19.0/jquery.validate.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/libs/inputmask/5.0.5/jquery.inputmask.js')); ?>" async></script>
<script src="<?php echo e(URL::asset('/libs/select2/select2.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/js/pages/form-advanced.init.js')); ?>"></script>

<?php if(App::isLocale('hr')): ?>
    <script src="<?php echo e(URL::asset('/libs/jquery-validate/1.19.0/localization/messages_he.js')); ?>"></script>
    <?php endif; ?>
    <!-- footerScript -->
    <script type="text/javascript"> 
        
    $(function() {
       
        $("#create_user").validate({           
            rules: {
                user_type_id: {
                    required: true
                },
                name: {
                    required: true 
                },
                email:{
                    required: true,
                    email: true
                }
            },
            messages: 
            {
                
            }
        });
      });

        function dosubmit() {
            let errorMsg = "<?php echo e(trans('general.password_validation')); ?>";
            $.validator.addMethod('checkParams', function (value) { 
                return /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/i.test(value); 
            }, errorMsg);
            if($('.salarydiv').hasClass('d-block')){
                $('#salary').rules('add', {required: true, number: true});
            }
            $('#userpassword').rules('add', {required: true, minlength: 6, checkParams: true});
            $('#userconfirmpassword').rules('add', {required: true,minlength: 6,equalTo: '[name="password"]'});
            if($("#create_user").valid() === true){
                return true;
            }else{
                return false;
            }
        }
        //Change User Type for SM
        function changeusertype(t) {
            var id = t.id;
            var val=$('#'+id+'').val()
            console.log(val,'val')
            $.ajax({
                type: "GET",
                url:"/usertypes/get-usertype/"+val,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if(data == 1){
                        $(".categorydiv").removeClass('d-none');
                        $(".categorydiv").addClass('d-block');
                        $(".select2-container").css({'width': '100%'});
                        $("#category").prop('required',true);

                        $(".salarydiv").removeClass('d-none');
                        $(".salarydiv").addClass('d-block');
                        // $(".select2-container").css({'width': '100%'});
                        $("#salary").prop('required',true);
                    }else{
                        if(data == "Accountant"){
                            $(".salarydiv").removeClass('d-none');
                            $(".salarydiv").addClass('d-block');
                            // $(".select2-container").css({'width': '100%'});
                            $("#salary").prop('required',true);

                            $(".categorydiv").removeClass('d-block');
                            $(".categorydiv").addClass('d-none');
                            $("#category").prop('required',false);
                        } else {
                            $(".categorydiv").removeClass('d-block');
                            $(".categorydiv").addClass('d-none');
                            $("#category").prop('required',false);

                            $(".salarydiv").removeClass('d-block');
                            $(".salarydiv").addClass('d-none');
                            $("#salary").prop('required',false);
                        }
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log("XHR",xhr);
                    console.log("status",textStatus);
                    console.log("Error in",errorThrown);
                }
            });
        }
    </script>
    <!--  datatable js -->
   
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/uvda/resources/views/internal-user/create.blade.php ENDPATH**/ ?>