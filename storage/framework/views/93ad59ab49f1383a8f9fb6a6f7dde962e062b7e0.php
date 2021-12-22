<?php $__env->startSection('title'); ?> <?php echo e(trans('form.contact.add_contact')); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('headerCss'); ?>
    <!-- headerCss -->
    <!-- DataTables -->
    <link href="<?php echo e(URL::asset('/libs/datatables/datatables.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::asset('/libs/datatables/colReorder.dataTables.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::asset('/libs/datatables/select.dataTables.min.css')); ?>" rel="stylesheet" type="text/css" />

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 col-sm-4 col-xs-12">
                            <h4 class="card-title"><?php echo e(trans('form.contact.add_contact')); ?></h4>
                        </div>
                    </div>
                    <?php echo $__env->make('session-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <form action="<?php echo e(route('contacts.store')); ?>" onSubmit="return dosubmit();" method="POST" id="create_customer" class="form needs-validation">
                                <?php echo csrf_field(); ?>
                                <div class="form-row">
                                    <?php if(auth()->user()->type_id == 1): ?>
                                    <div class="form-group col-md-3">
                                        <label for="SelectUserType" class="ul-form__label"><?php echo e(trans('form.contact.entity_type')); ?> </label>
                                        <select class="form-control" name="user_type" id="user_type">
                                            <option value=""><?php echo e(trans('form.contact.field.select_entity_type')); ?></option>
                                            <?php $__currentLoopData = $userType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($type->id); ?>" <?php echo e(old('user_type') == $type->id ? 'selected' : ''); ?>>
                                            <?php echo e(App::isLocale('hr')?$type->hr_type_name:$type->type_name); ?>

                                            </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ['user_type'];
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
                                    <div class="form-group col-md-3">
                                        <label for="SelectUser" class="ul-form__label"><?php echo e(trans('form.contact.entity_name')); ?> </label>
                                        <select class="form-control" name="user" id="user">
                                            <option value=""><?php echo e(trans('form.contact.field.select_entity_name')); ?></option>
                                        </select>
                                        <?php $__errorArgs = ['user'];
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
                                    <?php endif; ?>
                                    <?php if(auth()->user()->type_id == 1): ?>
                                    <div class="form-group col-md-3">
                                    <?php else: ?>
                                    <div class="form-group col-md-6">
                                    <?php endif; ?>
                                        <label for="ContactType" class="ul-form__label"><?php echo e(trans('form.contact.contact_type')); ?> </label>
                                        <select onchange="changeothertextbox(this)" id="contactypeid" class="form-control" name="contact_type_id" id="contact_type_id">
                                            <option value=""><?php echo e(trans('form.contact.field.select_contact_type')); ?></option>
                                            <?php $__currentLoopData = $contactType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($type->type_name); ?>" <?php echo e(old('contact_type_id') == $type->type_name ? 'selected' : ''); ?>>
                                            <?php echo e(App::isLocale('hr')?$type->hr_type_name:$type->type_name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ['contact_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if(auth()->user()->type_id == 1): ?>
                                    <div class="form-group col-md-3 d-none" id="contactypeid_div">
                                    <?php else: ?>
                                    <div class="form-group col-md-6 d-none">
                                    <?php endif; ?>
                                   
                                        <label><?php echo e(trans('form.registration.client.type')); ?></label>
                                            
                                        <input type="text" value="" id="contactypeid_otext"
                                            class="form-control numeric"
                                            name="other_text"
                                            placeholder="<?php echo e(trans('form.registration.client.type_helper')); ?>"
                                            >
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="FirstName" class="ul-form__label"><?php echo e(trans('form.contact.field.first_name')); ?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="<?php echo e(trans('form.contact.field.first_name')); ?>" value="<?php echo e(old('first_name')); ?>"  />
                                        <div class="invalid-feedback">
                                            <?php echo e(trans('form.contact.field.validation.first_name')); ?>

                                        </div>
                                        <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="validation-errors">
                                            <?php echo e($message); ?>

                                        </div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="LastName" class="ul-form__label"><?php echo e(trans('form.contact.field.last_name')); ?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="<?php echo e(trans('form.contact.field.last_name')); ?>" value="<?php echo e(old('last_name')); ?>"  />
                                        <div class="invalid-feedback">
                                            <?php echo e(trans('form.contact.field.validation.last_name')); ?>

                                        </div>
                                        <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="validation-errors">
                                            <?php echo e($message); ?>

                                        </div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="FamilyName" class="ul-form__label"><?php echo e(trans('form.contact.field.family_name')); ?> </label>
                                        <input type="text" class="form-control" id="family_name" name="family_name" placeholder="<?php echo e(trans('form.contact.field.family_name')); ?>" value="<?php echo e(old('family_name')); ?>" />
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="WorkPlace" class="ul-form__label"><?php echo e(trans('form.contact.field.work_place')); ?> </label>
                                        <input type="text" class="form-control" id="workplace" name="workplace" placeholder="<?php echo e(trans('form.contact.field.work_place')); ?>" value="<?php echo e(old('workplace')); ?>" />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="Phone" class="ul-form__label"><?php echo e(trans('form.contact.field.phone')); ?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="phone" name="phone" placeholder="999-999-9999" value="<?php echo e(old('phone')); ?>"  required/>
                                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="validation-errors">
                                            <?php echo e($message); ?>

                                        </div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="mobile" class="ul-form__label"><?php echo e(trans('form.contact.field.mobile')); ?> </label>
                                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="999-999-9999" value="<?php echo e(old('mobile')); ?>" />
                                        <?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="validation-errors">
                                            <?php echo e($message); ?>

                                        </div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="Fax" class="ul-form__label"><?php echo e(trans('form.contact.field.fax')); ?> </label>
                                        <input type="text" class="form-control" id="fax" name="fax" placeholder="<?php echo e(trans('general.fax_placeholder')); ?>" value="<?php echo e(old('fax')); ?>" />
                                        <?php $__errorArgs = ['fax'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="validation-errors">
                                            <?php echo e($message); ?>

                                        </div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="Email" class="ul-form__label"><?php echo e(trans('form.contact.field.email')); ?> </label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="<?php echo e(trans('form.contact.field.email')); ?>" value="<?php echo e(old('email')); ?>" />
                                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="validation-errors">
                                            <?php echo e($message); ?>

                                        </div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <input type="checkbox" class="ml-2" name="is_default" id="is_default"
                                                <?php echo e(old('is_default') ? 'checked' : ''); ?>>
                                        <span><?php echo e(trans('form.contact.field.is_default')); ?></span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12 mb-4">
                                        <button type="submit" class="btn btn-primary" id="create-customer-btn"><?php echo e(trans('form.create')); ?></button>
                                        <a href="<?php echo e(route('contacts')); ?>" class="btn btn-outline-secondary m-1"><?php echo e(trans('form.cancel')); ?></a>
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
    <?php if(App::isLocale('hr')): ?>
    <script src="<?php echo e(URL::asset('/libs/jquery-validate/1.19.0/localization/messages_he.js')); ?>"></script>
    <?php endif; ?>
    <!-- footerScript -->
    <script type="text/javascript"> 
        
    $(function() {  
        $('#fax').inputmask('999-999-9999', {
                autoUnmask: true,
                removeMaskOnSubmit:true
        });
        $('#mobile').inputmask('999-999-9999', {
                autoUnmask: true,
                removeMaskOnSubmit:true
        });
        $('#phone').inputmask('999-999-9999', {
                autoUnmask: true,
                removeMaskOnSubmit:true
         });
        $("#create_customer").validate({           
            rules: {
                user_type: {
                    required: true
                },
                user: {
                    required: true
                },
                contact_type_id: {
                    required: true
                },
                first_name: {
                    required: true 
                },
                last_name: {
                    required: true,
                },
                phone: {
                    required: true,
                    number:true,
                },
                mobile: {
                    number:true,
                },
                fax: {
                    number:true,
                },
                email:{
                    email: true
                }
            },
            messages: 
            {
                
            }
        });
        let users = <?php echo $users ?>;
        $('#user_type').on('change', function(){
            let userData = "<option value=''><?php echo e(trans('form.contact.field.select_user')); ?></option>";
            let status = false;
            users.map((user, key) => {
                if(user.type_id == $('#user_type').val()) {
                    userData+=`<option value="${user.id}">${user.name} (${user.email})</option>`;
                    status = true;
                }
            });
            $('#user').html(userData)
        })
      });
        function dosubmit() {
            $('#phone').rules('add', { number:true});
            $('#mobile').rules('add', { number:true});
            if($("#create_customer").valid() === true){
                return true;
            }else{
                return false;
            }
        }
        function changeothertextbox(t){
            var id = t.id;
            if(t.value=='Other'){
                $("#"+id+"_div").removeClass('d-none');
                $("#"+id+"_otext").val('');
                $("#"+id+"_otext").prop('required',true);
            }else{
                $("#"+id+"_div").addClass('d-none');
                $("#"+id+"_otext").prop('required',false);
            }
        }
    </script>
    <!--  datatable js -->
    <script src="<?php echo e(URL::asset('/libs/datatables/datatables.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/jszip/jszip.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/pdfmake/pdfmake.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/datatables/dataTables.colReorder.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/datatables/dataTables.select.min.js')); ?>"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/uvda/resources/views/contacts/create.blade.php ENDPATH**/ ?>