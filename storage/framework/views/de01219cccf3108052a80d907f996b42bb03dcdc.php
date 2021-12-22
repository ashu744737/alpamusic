<?php $__env->startSection('title'); ?> 
Create New Title
<?php $__env->stopSection(); ?>


<?php $__env->startSection('headerCss'); ?>
    <!-- headerCss -->
    <link href="<?php echo e(URL::asset('/libs/select2/select2.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(URL::asset('/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(URL::asset('/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(URL::asset('/libs/jquery-smartwizard/smart_wizard.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(URL::asset('/libs/jquery-smartwizard/smart_wizard_arrows.css')); ?>" rel="stylesheet">

    <style>

        .card-title-desc{
            margin-bottom: 0 !important;
        }
        .sw-theme-arrows > .nav .nav-link.active{
        color: #ffffff !important;
        border-color: #105C8D !important;
        background: #105C8D !important;
        }
        .sw-theme-arrows > .nav .nav-link.active::after {
        border-left-color: #105C8D !important;
        }
        hr{
            margin-top: 3px !important;
        }

        #smartwizard .tab-content{
            height: auto !important;
        }

        .select2.select2-container{
            width: 100% !important;
        }

        #confirm_submit-error{
            display: inherit !important;
        }
    </style>
    <?php if(App::isLocale('hr')): ?>
    <style>
    .sw-theme-arrows > .nav .nav-link.active {
        
        margin-right: 0;
    }

    .sw-theme-arrows > .nav .nav-link::before {
        content: " ";
        position: absolute;
        display: block;
        width: 0;
        height: 0;
        top: 50%;
        right: 100%;
        margin-top: -50px;
        margin-left: 1px;
        border-top: 50px solid transparent;
        border-bottom: 50px solid transparent;
        border-left: 30px solid #eeeeee;
        z-index: 1;
        transform: rotate(180deg);
        -webkit-transform: rotate(180deg);
    }

    .sw-theme-arrows > .nav .nav-link::after {
        content: "";
        position: absolute;
        display: block;
        width: 0;
        height: 0;
        top: 50%;
        right: 100%;
        margin-top: -50px;
        border-top: 50px solid transparent;
        border-bottom: 50px solid transparent;
        border-left: 30px solid #f8f8f8;
        z-index: 2;
        transform: rotate(135deg);
        -webkit-transform: rotate(180deg);
        margin-right: ;
    }
    .sw-theme-arrows > .nav .nav-link {
        
        margin-left: 0px !important; 
    
    }
    .sw-theme-arrows > .nav .nav-link.inactive {
    
    margin-right: 0 !important;
    }
    </style>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="col-sm-6">
        <div class="page-title-box">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="<?php echo e(route('titles')); ?>"><?php echo e(trans('form.titles')); ?></a></li>
                <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo e(trans('general.create')); ?></a></li>
            </ol>
        </div>
    </div>

    <!-- content -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">

                        <div class="col-12">
                            
                            <h4 class="card-title">Create New Title</h4>
                           
                            
                            <p class="card-title-desc">You can create new Title by completing of filling up details on all steps here.</p>
                        
                        </div>

                        <div class="col-12">

                            <form name="title-form" id="title-form" class="form-horizontal mt-4" method="POST" action="<?php echo e(route('titles.store')); ?>" onSubmit="return dosubmit();" enctype="multipart/form-data" >
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="user_id" value="<?php echo e($typeid); ?>">

                                <div id="smartwizard">

                                    <ul class="nav">
                                        <li class="nav-item">
                                            <a class="nav-link" href="#step-1">
                                                
                                                <strong><?php echo e(trans('form.step_1')); ?></strong> <br>Title Details
                                            
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#step-2">
                                                
                                                <strong><?php echo e(trans('form.step_2')); ?></strong> <br>Contributor Details
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#step-3">
                                                
                                                <strong><?php echo e(trans('form.step_3')); ?></strong> <br>Create Title
                                            
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">
                                            <div class="row">
                                                <div class="col-12 p-4">

                                                    <div id="next_step1">

                                                        <div class="form-row">

                                                            <div class="form-group col-md-6">
                                                                <label for="name">Name <span class="text-danger">*</span></label>
                                                                <input type="text" name="name" value="<?php echo e(old('name')); ?>" autocomplete="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" autofocus id="name" placeholder="Enter the Name">
                                                                <?php $__errorArgs = ['name'];
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

                                                            <div class="form-group col-md-6">
                                                                <label>Categories <span class="text-danger">*</span></label>
                                                                <select multiple="multiple" class="select2 form-control select2-multiple input_required_s2 <?php $__errorArgs = ['categories'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="specializations" name="categories[]" data-placeholder="Select Category">
                                                                   
                                                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $categories): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option value="<?php echo e($categories['id']); ?>" <?php echo e(old('categories') == $categories['id'] ? 'selected' : ''); ?>>
                                                                            <?php echo e(App::isLocale('hr')?$categories['hr_name']:$categories['name']); ?>

                                                                        </option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                                <?php $__errorArgs = ['categories'];
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

                                                        </div>

                                                        <div class="form-row">
                                                            <div class="col-12 mb-2">
                                                                <label for="file_path">Select the files <span class="text-danger">*</span></label>
                                                                
                                                            </div>
                        
                                                            <div class="form-group col-md-6">
                                                                <input type="file" class="<?php $__errorArgs = ['file_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="file_path[]" id="file_path" placeholder="Select the files" multiple>
                                                                <?php $__errorArgs = ['file_path'];
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
                                                        </div>
                                                        <div class="form-group row mt-4">
                                                            <div class="col-12 text-right">
                                                                <button onclick="loadSteps('step2', 'step1');" class="btn btn-primary w-md waves-effect waves-light" type="button"><?php echo e(trans('general.next')); ?></button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">
                                            <div class="row">
                                                <div class="col-12 p-4">

                                                    <div id="next_step2">
                                                        

                                                        

                                                        <!-- Block for multiple owners-->
                                                        <div class="table-wrapper">
                                                            <div class="table-responsive mb-0 fixed-solution"
                                                                 data-pattern="priority-columns">
                                                                <div id="owner_tbl">
                                                                    <table class="table table-borderless mb-0">
                                                                        <thead>
                                                                        <tr>
                                                                            <th class="btn-primary"><h4
                                                                                class="font-size-18 mb-1">Owner Details</h4>
                                                                            </th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody id="owner-form">
                                                                        </tbody>
                                                                        <tfoot>
                                                                        <tr><td id="owner_footer" class="text-center"> No Records Added </td></tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="col-md-12 text-right">
                                                                    <button type="button" onclick="addNewowner()"
                                                                            class="btn btn-link btn-lg waves-effect"
                                                                            style="text-decoration: underline">Add Owner
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <!-- Block for multiple contributores-->
                                                        <div class="table-wrapper">
                                                            <div class="table-responsive mb-0 fixed-solution"
                                                                 data-pattern="priority-columns">
                                                                <div id="contributores_tbl">
                                                                    <table class="table table-borderless mb-0">
                                                                        <thead>
                                                                        <tr>
                                                                            
                                                                            <th class="btn-primary"><h4
                                                                                class="font-size-18 mb-1">Contributor Details</h4>
                                                                            </th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody id="contributor-form">
                                                                        </tbody>
                                                                        <tfoot>
                                                                        <tr><td id="contributor_footer" class="text-center"> No Records Added </td></tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="col-md-12 text-right">
                                                                    <button type="button" onclick="addNewcontributor()"
                                                                            class="btn btn-link btn-lg waves-effect"
                                                                            style="text-decoration: underline">Add Contributor
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row mt-4">
                                                            <div class="col-12 text-right">
                                                                <button onclick="loadSteps('step1', 'step2');" class="btn btn-secondary w-md waves-effect waves-light" type="button"><?php echo e(trans('general.previous')); ?></button>
                                                                <button onclick="loadSteps('step3', 'step2');"
                                                                        class="btn btn-primary w-md waves-effect waves-light"
                                                                        type="button"><?php echo e(trans('general.next')); ?></button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">
                                            <div class="row">
                                                <div class="col-12 p-4">

                                                    <div id="next_step3">

                                                        <div class="form-row">
                                                            <div class="col-md-12">
                                                                
                                                                <h4 class="text-muted font-size-18 mb-1">Create Title</h4>
                                                                
                                                                <hr>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row mt-4">
                                                            <div class="col-12 text-right">
                                                                <button onclick="loadSteps('step2', 'step3');" class="btn btn-secondary w-md waves-effect waves-light" type="button"><?php echo e(trans('general.previous')); ?></button>
                                                                <button class="btn btn-primary w-md waves-effect waves-light" type="submit"><?php echo e(trans('general.create')); ?></button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

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

    <div class="card mb-1 shadow-none" id="contributor_clone_element" style="display: none">
        <div class="card-header p-3" id="contributor_heading" style="padding-bottom: 0 !important;">
            <div class="row" style="padding-right: 0; padding-left: 0">
                <div class="col-sm-12 col-md-6 text-left">
                    <h6 class="m-0 font-size-14">
                        <a href="#contributor_collapse"
                           class="text-dark collapsed contributor_header"
                           data-toggle="collapse"
                           aria-expanded="false"
                           aria-controls="collapse_1">
                           
                           Contributor Details :
                        </a>
                    </h6>
                </div>
                <div class="col-sm-12 col-md-6 text-sm-right">
                    <button type="button"
                            onclick="deletecontributor(this)"
                            class="btn btn-link waves-effect"
                            style="text-decoration: underline">
                            <?php echo e(trans('general.delete')); ?>

                    </button>
                </div>
            </div>

        </div>

        <div id="contributor_collapse"
             class="collapse contributor_block"
             aria-labelledby="contributor_heading"
             data-parent="#contributor-form" style="">
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Email
                            <span
                                class="text-danger">*</span></label>
                                <input type="text" value=""
                                class="form-control pac-target-input multiple_input_required_s2 email1"
                                name="contributor[]['email1']"
                                placeholder="Enter Contributor Email"
                                required
                                autocomplete="off">

                    </div>

                    <div class="form-group col-md-6">
                        <label>Type
                            <span
                                    class="text-danger">*</span></label>

                        <select id="type_id"
                                name="contributor[]['type_id'][]"
                                class="form-control multiple_input_required_s2 type1"
                                required>
                            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($type['id']); ?>" <?php echo e(old('type_id') == $type['id'] ? 'selected' : ''); ?>><?php echo e($type['type']); ?></option>
                          
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                   
                    <div class="form-group col-md-6">
                        <label>First Name
                            <span
                                    class="text-danger">*</span></label>
                        <input type="text" value=""
                               class="form-control pac-target-input multiple_input_required_s2 first_name1"
                               name="contributor[]['first_name1']"
                               placeholder="Enter First Name"
                               required
                               autocomplete="off">

                    </div>

                    <div class="form-group col-md-6">
                        <label>Last Name
                            <span
                                    class="text-danger">*</span></label>
                        <input type="text" value=""
                               class="form-control pac-target-input multiple_input_required_s2 last_name1"
                               name="contributor[]['last_name1']"
                               placeholder="Enter Last Name"
                               required
                               autocomplete="off">

                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="card mb-1 shadow-none" id="owner_clone_element" style="display: none">
        <div class="card-header p-3" id="owner_heading" style="padding-bottom: 0 !important;">
            <div class="row" style="padding-right: 0; padding-left: 0">
                <div class="col-sm-12 col-md-6 text-left">
                    <h6 class="m-0 font-size-14">
                        <a href="#owner_collapse"
                           class="text-dark collapsed owner_header"
                           data-toggle="collapse"
                           aria-expanded="false"
                           aria-controls="collapse_1">
                           Owner Details :
                        </a>
                    </h6>
                </div>
                <div class="col-sm-12 col-md-6 text-sm-right">
                    <button type="button"
                            onclick="deleteowner(this)"
                            class="btn btn-link waves-effect"
                            style="text-decoration: underline">
                            <?php echo e(trans('general.delete')); ?>

                    </button>
                </div>
            </div>

        </div>

        <div id="owner_collapse"
             class="collapse owner_block"
             aria-labelledby="owner_heading"
             data-parent="#owner-form" style="">
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Email
                            <span
                                class="text-danger">*</span></label>
                                <input type="text" value=""
                                class="form-control pac-target-input multiple_input_required_s2 owner_email1"
                                name="owner[]['email1']"
                                placeholder="Enter Owner Email"
                                required
                                autocomplete="off">

                    </div>

                    <div class="form-group col-md-6">
                        <label>Type
                            <span
                            class="text-danger">*</span></label>

                            <select id="owner_type_id"
                                    name="owner[]['owner_type_id'][]"
                                    class="form-control multiple_input_required_s2 owner_type1"
                                    required>
                                <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                
                                <option value="<?php echo e($type['id']); ?>" <?php echo e(old('type_id') == $type['id'] ? 'selected' : ''); ?>><?php echo e($type['type']); ?></option>
                            
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                    </div>
                </div>

                <div class="form-row">
                   
                    <div class="form-group col-md-6">
                        <label>First Name
                            <span
                                    class="text-danger">*</span></label>
                            <input type="text" value=""
                               class="form-control pac-target-input multiple_input_required_s2 owner_first_name1"
                               name="owner[]['owner_first_name1']"
                               placeholder="Enter First Name"
                               required
                               autocomplete="off">

                    </div>

                    <div class="form-group col-md-6">
                        <label>Last Name
                            <span
                                    class="text-danger">*</span></label>
                        <input type="text" value=""
                               class="form-control pac-target-input multiple_input_required_s2 owner_last_name1"
                               name="owner[]['owner_last_name1']"
                               placeholder="Enter Last Name"
                               required
                               autocomplete="off">

                    </div>
                </div>
            </div>
        </div>
    </div>

    



<?php $__env->stopSection(); ?>

<?php $__env->startSection('footerScript'); ?>
    <!-- footerScript -->
    <script src="<?php echo e(URL::asset('/libs/jquery-smartwizard/jquery.smartWizard.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/select2/select2.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/js/pages/form-advanced.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/jquery-validate/1.19.0/jquery.validate.js')); ?>"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(env('GOOGLE_KEY')); ?>&libraries=places&language=<?php echo e(App::isLocale('hr') ? 'iw' : 'en'); ?>" async defer></script>
    <script src="<?php echo e(URL::asset('/libs/inputmask/5.0.5/jquery.inputmask.js')); ?>" async></script>
    <?php if(App::isLocale('hr')): ?>
    <script src="<?php echo e(URL::asset('/libs/jquery-validate/1.19.0/localization/messages_he.js')); ?>"></script>
    <?php endif; ?>
    <script>
        
        $(document).ready(function() {
        
            // alert("456");
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });

            

            $('#smartwizard').smartWizard({
                selected: 0,  // Initial selected step, 0 = first step
                theme: 'arrows', // default, arrows, dots, progress
                enableURLhash: false, // Enable selection of the step based on url hash
                // autoAdjustHeight: true, // Automatically adjust content height
                transition: {
                    animation: 'fade', // Effect on navigation, none/fade/slide-horizontal/slide-vertical/slide-swing
                },
                toolbarSettings: {
                    toolbarPosition: 'none', // none, top, bottom, both
                    showNextButton: false, // show/hide a Next button
                    showPreviousButton: false, // show/hide a Previous button
                },
                anchorSettings: {
                    anchorClickable: false, // Enable/Disable anchor navigation
                    markDoneStep: true, // Add done state on navigation
                    markAllPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
                    removeDoneStepOnNavigateBack: true, // While navigate back done step after active step will be cleared
                    enableAnchorOnDoneStep: false // Enable/Disable the done steps navigation
                },
                keyboardSettings: {
                    keyNavigation: false, // Enable/Disable keyboard navigation(left and right keys are used if enabled)
                },
            });

            $("#title-form").validate({
                ignore: false,
                invalidHandler: function (e, validator) {
                    // loop through the errors:
                    for (var i = 0; i < validator.errorList.length; i++) {
                        $(validator.errorList[i].element).closest('.collapse').collapse('show');
                        $(this).find(":input.error:first").focus();
                    }
                }
            });

            $('#dob').datepicker({
                autoclose: true,
                todayHighlight: true
            });

            loadSteps('step1', 'step1');
         
        });

         //this for add input mask to phone,mobile,fax
        //  function addinputmask(type){
        //     if(type=='phone'){ 
        //         $('.arr_other_phone').inputmask('999-999-9999', {
        //         autoUnmask: true,
        //         removeMaskOnSubmit:true
        //          });
        //     }
        //     if(type=='mobile'){
        //         $('.arr_other_mobile').inputmask('999-999-9999', {
        //         autoUnmask: true,
        //         removeMaskOnSubmit:true
        //          });
        //     }
        //     if(type=='fax'){
        //         $('.arr_other_fax').inputmask('999-999-9999', {
        //         autoUnmask: true,
        //         removeMaskOnSubmit:true
        //     });
        //     }
        // }

         // This Function for contributor Type Dropdown Othertext to open textbox
        // function changeothertextbox(t){
        //     var id = t.id;
        //     if(t.value=='Other' || t.value=='Contact'){
        //         $("#"+id+"_div").removeClass('d-none');
        //         $("#"+id+"_otext").val('');
        //         var contactType = <?php echo $contacttypes; ?>;
                
        //         contactType.map((contact, idx) => {
        //             if(contact.type_name == t.value){
        //                 <?php if(config('app.locale') == 'hr'): ?>
        //                     $("#"+id+"_olabel").html(contact.hr_type_name+' <?php echo e(trans("form.registration.client.type")); ?>');
        //                     $("#"+id+"_otext").attr("placeholder", '<?php echo e(trans("general.enter")); ?> '+contact.hr_type_name+' <?php echo e(trans("form.registration.client.type")); ?>');
        //                 <?php else: ?>
        //                 $("#"+id+"_olabel").html(contact.type_name+' <?php echo e(trans("form.registration.client.type")); ?>');
        //                 $("#"+id+"_otext").attr("placeholder", '<?php echo e(trans("general.enter")); ?> '+contact.type_name+' <?php echo e(trans("form.registration.client.type")); ?>');
        //                 <?php endif; ?>
        //             }
        //         })
        //         //$("#"+id+"_otext").prop('required',true);
        //         $("#"+id+"_otext").rules('add', {required: true });
        //         $("#"+id+"_otext").attr("required", true);
        //     }else{
        //         $("#"+id+"_div").addClass('d-none');
        //         // $("#"+id+"_otext").prop('required',false);
        //         $("#"+id+"_otext").rules('remove', 'required');
        //         $("#"+id+"_otext").removeAttr('required');
        //     }
        // }
        
        // set the subject title on Subject Type
        function changeothersubjecttitle(t){
        var id=$(t).data("id");
        var value = t.value;
        $("#"+id+"_title").html(value);  
        }
        function changesubjecttitlebyid(id){
            var e = document.getElementById(id);
            $("#"+id+"_title").html(e.value);
        }

        //for custom validation before form submit to check all multiple fields has entries
        function dosubmit() {
            return customFormValidation();
            // return true;
        }

        // function step 1 and step 2 show hide
        function loadSteps(step, backstep)
        {
            console.log('step ', step);
            // console.log('backstep ', backstep);

            if (step == 'step1') {
                // $('#next_step1').show();
                // $('#next_step2').hide();
                // $('#next_step3').hide();

                if(backstep == "step2") {
                    $('.multiple_input_required_s2').each(function () {
                        // console.log('removing rule of :>>', $(this));
                        // $(this).rules('remove', 'required');
                        // $(this).removeAttr('required');
                        $(this).rules('add', {
                            required: true
                        });
                        $(this).attr("required", true);
                    });
                    $('.multiple_type_required_s2').each(function () {
                    // console.log('removing rule of :>>', $(this));
                    var id=$(this).attr('id');
                    //  if(id!='' && id!='undefined' && ($(this).val()=='Other' || $(this).val()=='Contact'))
                    //  {
                    //      $("#"+id+"_otext").rules('remove', 'required');
                    //      $("#"+id+"_otext").removeAttr('required');
                     
                    //  }
                });

                    $('.input_required_s2').each(function () {
                        // console.log('removing rule of :>>', $(this));
                        $(this).rules('remove', 'required');
                        $(this).removeAttr('required');
                    });
                }

                $('#name').rules('add', { required: true,});
                $('#specializations').rules('add', { required: true,});
                
                $('#file_path').rules('add', { required: true,});

             
                // let errorMsg = "<?php echo e(trans('general.password_validation')); ?>";
                // $.validator.addMethod('checkParams', function (value) { 
                //     return /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/i.test(value); 
                // }, errorMsg);

                // $('#userpassword').rules('add', {required: true, minlength: 6, checkParams: true});
                // $('#userconfirmpassword').rules('add', {required: true,minlength: 6,equalTo: '[name="password"]'});

                // $('#smartwizard').smartWizard("goToStep", 0);
                $('#smartwizard').smartWizard("reset");

            } else if (step == 'step2') {             

                if((backstep == "step1" && $("#title-form").valid() === true) || backstep == "step3"){
                    // $('#next_step2').show();
                    // $('#next_step1').hide();
                    // $('#next_step3').hide();
               

                    if(backstep == "step3"){
                        $('.multiple_input_required_s2').each(function () {
                        // console.log('removing rule of :>>', $(this));
                        // $(this).rules('remove', 'required');
                        // $(this).removeAttr('required');
                        $(this).rules('add', {
                            required: true
                        });
                        $(this).attr("required", true);
                    });
                        // $('.multiple_input_required_s3').each(function() {
                        //     // console.log('removing rule of :>>', $(this));
                        //     $(this).rules('remove', 'required');
                        //     $(this).removeAttr('required');
                        // });
                        // $('.multiple_type_required_s3').each(function () {
                        //     console.log('removing rule of :>>', $(this));
                        //     var id=$(this).attr('id');
                        //     if(id!='' && id!='undefined' && ($(this).val()=='Other' || $(this).val()=='Contact'))
                        //     {
                        //         $("#"+id+"_otext").rules('remove', 'required');
                        //         $("#"+id+"_otext").removeAttr('required');
                            
                        //     }
                        // });
                        

                        // $('.input_required_s3').each(function() {
                        //     // console.log('removing rule of :>>', $(this));
                        //     $(this).rules('remove', 'required');
                        //     $(this).removeAttr('required');
                        // });

                       
                    }

                    $('.multiple_input_required_s2').each(function(e) {
                        // console.log('adding rule of :>>', $(this));
                        $(this).rules('add', {
                            required: true
                        });
                        $(this).attr("required", true);
                    });
                    
                    // $('.multiple_type_required_s2').each(function () {
                    // // console.log('removing rule of :>>', $(this));
                    // var id=$(this).attr('id');
                    // if(id!='' && id!='undefined' && ($(this).val()=='Other' || $(this).val()=='Contact'))
                    //  {
                    //         $("#"+id+"_otext").rules('add', {
                    //         required: true
                    //     });
                    //     $("#"+id+"_otext").attr("required", true);
                        
                     
                    //  }
                    // });

                    $('.input_required_s2').each(function(e) {
                        // console.log('adding rule of :>>', $(this));
                        $(this).rules('add', {
                            required: true
                        });
                        $(this).attr("required", true);
                    });

                    $('#smartwizard').smartWizard("goToStep", 1);
                }

            } else if (step == 'step3') {
            
                if ($("#title-form").valid() === true) {
                    alert(456);
                    if ($(".owner-base-row").length < 1) {
                        $("#owner_footer").html('<span class="error"><?php echo e(trans('general.minimum_entry')); ?></span>');

                        return false;
                    } else {
                        $("#owner_footer").hide('');
                    }
                    
                    if ($(".contributor-base-row").length < 1) {
                        $("#contributor_footer").html('<span class="error"><?php echo e(trans('general.minimum_entry')); ?></span>');

                        return false;
                    } else {
                        $("#contributor_footer").hide('');
                
                    }

                   

                    // $('.input_required_s3').each(function(e) {
                    //     // console.log('adding rule of :>>', $(this));
                    //     $(this).rules('add', {
                    //         required: true
                    //     });
                    //     $(this).attr("required", true);
                    // });

                    // $('.multiple_input_required_s3').each(function(e) {
                    //     // console.log('adding rule of :>>', $(this));
                    //     $(this).rules('add', {
                    //         required: true
                    //     });
                    //     $(this).attr("required", true);
                    // });

                //     $('.multiple_type_required_s3').each(function () {
                //     // console.log('removing rule of :>>', $(this));
                //     var id=$(this).attr('id');
                //     if(id!='' && id!='undefined' && ($(this).val()=='Other' || $(this).val()=='Contact'))
                //      {
                //             $("#"+id+"_otext").rules('add', {
                //             required: true
                //         });
                //         $("#"+id+"_otext").attr("required", true);
                        
                     
                //      }
                // });
                    // $('#next_step3').show();
                    // $('#next_step2').hide();

                    $('#smartwizard').smartWizard("goToStep", 2);
                }
            }
        }

        // Functions for multiple contributores/contacts fields
        function customFormValidation()
        {
            var isValid = false;
            var cnt = 0;
            var basicValidation = $("#title-form").valid();

            if (basicValidation) {
                cnt++;
            }
// alert(1);
            $("form tbody").each(function (index) {
                
                if ($(this).find("tr").length > 0) {
                    cnt++;
                    if (cnt == 1) {
                        isValid = true;
                        return true;
                    }
                } else {
                    // if ($(this).attr('id') == 'contributor-form') {
                  
					// 	cnt++;
					// 	if (cnt == 1) {
					// 		isValid = true;
					// 		return true
					// 	}
					// } else {
                    //     var footerEle = $(this).parent().find("tfoot tr td");
                    //     footerEle.html('<span class="error"><?php echo e(trans('general.minimum_entry')); ?></span>');
                    //     footerEle.show();
                    // }

                   
                    isValid = true;
                    return true; 
               
                }
            });
            
            // return isValid;
            return true;
        }

        function addNewcontributor()
        {
            $("#contributor_footer").hide();
            var lastNo, originalId;
            var arrRowNo = [];
            var baseTbl = $("#contributor-form");
            originalId = baseTbl.find('.contributor_block:last').attr('id');
            var cloned = $("#contributor_clone_element").clone().appendTo('#contributor-form').wrap('<tr class="contributor-base-row" style="display: block;"><td style="width: 100%;display: inline-table;"></td></tr>');
            //var cloned = baseTbl.append("<tr class='contributor-base-row' style='display: block;'><td style='width: 100%;display: inline-table;'>"+$("#contributor_clone_element").clone().html()+"</td></tr>");
            if (originalId)
                arrRowNo = originalId.split("-");

            if (arrRowNo.length > 1) {
                lastNo = arrRowNo[1];
            } else {
                lastNo = 0;
            }

            var newId = 'contributor_collapse-' + (parseInt(lastNo) + 1);
            cloned.show();
            cloned.find(".contributor_block").attr('id', newId);
            cloned.find(".contributor_header").attr('href', '#' + newId);
            cloned.find(".contributor_row_no").html($(".contributor-base-row").length);

            cloned.find(".email1").attr('name', "contributor[" + (parseInt(lastNo) + 1) + "][email1]");
            cloned.find(".email1").attr('id', "contributor_complete_" + (parseInt(lastNo) + 1));

            cloned.find(".first_name1").attr('name', "contributor[" + (parseInt(lastNo) + 1) + "][first_name1]");
            cloned.find(".first_name1").attr('id', "contributor" + (parseInt(lastNo) + 1));

            cloned.find(".last_name1").attr('name', "contributor[" + (parseInt(lastNo) + 1) + "][last_name1]");
            cloned.find(".last_name1").attr('id', "contributor" + (parseInt(lastNo) + 1));

            cloned.find(".type1").attr('name', "contributor[" + (parseInt(lastNo) + 1) + "][type1][]");
            cloned.find(".type1").attr('id', "contributor" + (parseInt(lastNo) + 1));

            cloned.find('input[type=text]').val('');
            // setcontributornew((parseInt(lastNo) + 1));
            // changesubjecttitlebyid("arr_contributor_type_" + (parseInt(lastNo) + 1));
            cloned.find('.collapse').collapse('show');
        }

        function addNewowner()
        {
            $("#owner_footer").hide();
            var lastNo, originalId;
            var arrRowNo = [];
            var baseTbl = $("#owner-form");
            originalId = baseTbl.find('.owner_block:last').attr('id');
            var cloned = $("#owner_clone_element").clone().appendTo('#owner-form').wrap('<tr class="owner-base-row" style="display: block;"><td style="width: 100%;display: inline-table;"></td></tr>');
            //var cloned = baseTbl.append("<tr class='owner-base-row' style='display: block;'><td style='width: 100%;display: inline-table;'>"+$("#owner_clone_element").clone().html()+"</td></tr>");
            if (originalId)
                arrRowNo = originalId.split("-");

            if (arrRowNo.length > 1) {
                lastNo = arrRowNo[1];
            } else {
                lastNo = 0;
            }

            var newId = 'owner_collapse-' + (parseInt(lastNo) + 1);
            cloned.show();
            cloned.find(".owner_block").attr('id', newId);
            cloned.find(".owner_header").attr('href', '#' + newId);
            cloned.find(".owner_row_no").html($(".owner-base-row").length);

            cloned.find(".owner_email1").attr('name', "owner[" + (parseInt(lastNo) + 1) + "][owner_email1]");
            cloned.find(".owner_email1").attr('id', "owner_complete_" + (parseInt(lastNo) + 1));

            cloned.find(".owner_first_name1").attr('name', "owner[" + (parseInt(lastNo) + 1) + "][owner_first_name1]");
            cloned.find(".owner_first_name1").attr('id', "owner" + (parseInt(lastNo) + 1));

            cloned.find(".owner_last_name1").attr('name', "owner[" + (parseInt(lastNo) + 1) + "][owner_last_name1]");
            cloned.find(".owner_last_name1").attr('id', "owner" + (parseInt(lastNo) + 1));

            cloned.find(".owner_type1").attr('name', "owner[" + (parseInt(lastNo) + 1) + "][owner_type1][]");
            cloned.find(".owner_type1").attr('id', "owner" + (parseInt(lastNo) + 1));

            cloned.find('input[type=text]').val('');
            // setownernew((parseInt(lastNo) + 1));
            // changesubjecttitlebyid("arr_owner_type_" + (parseInt(lastNo) + 1));
            cloned.find('.collapse').collapse('show');
        }

        function firstowner()
        {
            $("#owner_footer").hide();
            var lastNo, originalId;
            var arrRowNo = [];
            var baseTbl = $("#owner-form");
            originalId = baseTbl.find('.owner_block:last').attr('id');
            var cloned = $("#owner_clone_element").clone().appendTo('#owner-form').wrap('<tr class="owner-base-row" style="display: block;"><td style="width: 100%;display: inline-table;"></td></tr>');
            //var cloned = baseTbl.append("<tr class='owner-base-row' style='display: block;'><td style='width: 100%;display: inline-table;'>"+$("#owner_clone_element").clone().html()+"</td></tr>");
            if (originalId)
                arrRowNo = originalId.split("-");

            if (arrRowNo.length > 1) {
                lastNo = arrRowNo[1];
            } else {
                lastNo = 0;
            }

            var newId = 'owner_collapse-' + (parseInt(lastNo) + 1);
            cloned.show();
            cloned.find(".owner_block").attr('id', newId);
            cloned.find(".owner_header").attr('href', '#' + newId);
            cloned.find(".owner_row_no").html($(".owner-base-row").length);

            cloned.find(".owner_email1").attr('name', "owner[" + (parseInt(lastNo) + 1) + "][owner_email1]");
            cloned.find(".owner_email1").attr('id', "owner_complete_" + (parseInt(lastNo) + 1));

            cloned.find(".owner_first_name1").attr('name', "owner[" + (parseInt(lastNo) + 1) + "][owner_first_name1]");
            cloned.find(".owner_first_name1").attr('id', "owner" + (parseInt(lastNo) + 1));

            cloned.find(".owner_last_name1").attr('name', "owner[" + (parseInt(lastNo) + 1) + "][owner_last_name1]");
            cloned.find(".owner_last_name1").attr('id', "owner" + (parseInt(lastNo) + 1));

            cloned.find(".owner_type1").attr('name', "owner[" + (parseInt(lastNo) + 1) + "][owner_type1][]");
            cloned.find(".owner_type1").attr('id', "owner" + (parseInt(lastNo) + 1));

            cloned.find('input[type=text]').val('');
            // setownernew((parseInt(lastNo) + 1));
            // changesubjecttitlebyid("arr_owner_type_" + (parseInt(lastNo) + 1));
            cloned.find('.collapse').collapse('show');
        }

        function deletecontributor(ele)
        {
            $(ele).closest(".contributor-base-row").remove();

            $(".contributor-base-row").each(function (index) {
                //$(this).find(".contributor_row_no").html(index + 1);
            });

            if ($(".contributor-base-row").length < 1) {
                $("#contributor_footer").html('<span>No Records Added</span>');
                $("#contributor_footer").show();
            }
        }

        function deleteowner(ele)
        {
            $(ele).closest(".owner-base-row").remove();

            $(".owner-base-row").each(function (index) {
                //$(this).find(".owner_row_no").html(index + 1);
            });

            if ($(".owner-base-row").length < 1) {
                $("#owner_footer").html('<span>No Records Added</span>');
                $("#owner_footer").show();
            }
        }


    </script>
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/uvda/resources/views/titles/create.blade.php ENDPATH**/ ?>