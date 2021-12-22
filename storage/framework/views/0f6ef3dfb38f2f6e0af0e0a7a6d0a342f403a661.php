<?php $__env->startSection('title'); ?> <?php echo e(trans('form.registration.investigation.create_investigation_title')); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('headerCss'); ?>
<!-- headerCss -->
<link href="<?php echo e(URL::asset('/libs/select2/select2.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(URL::asset('/libs/jquery-smartwizard/smart_wizard.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(URL::asset('/libs/jquery-smartwizard/smart_wizard_arrows.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(URL::asset('/libs/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(URL::asset('/css/custom.css')); ?>" rel="stylesheet" type="text/css" />
<style>
    #smartwizard .tab-content{
        height: auto !important;
    }

    .sw-theme-arrows > .nav .nav-link.active{
        color: #ffffff !important;
        border-color: #105C8D !important;
        background: #105C8D !important;
    }

    .sw-theme-arrows > .nav .nav-link.active::after {
        border-left-color: #105C8D !important;
    }

    .sw-theme-arrows > .nav .nav-link.done{
        color: #ffffff !important;
        border-color: #105C8D !important;
        background: #105C8D !important;
    }

    .sw-theme-arrows > .nav .nav-link.done::after {
        border-left-color: #105C8D !important;
    }

    .card-title-desc {
        margin-bottom: 0 !important;
    }

    .select2-container .select2-selection--single{
        height: 33px !important;
    }

    .select2-container .select2-selection--single .select2-selection__rendered{
        line-height: 33px !important;
    }

    .company-del-checks{
        padding-left: 35px !important;
        padding-right: 35px !important;
    }
    
    #approval_text-error{
        position: absolute;
        top: 35px;
    }

    .datepicker{
        border: 1px solid #ced4da !important;
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
    #approval_text-error{
        position: absolute;
        top: 35px;
    }
    </style>
    <?php endif; ?>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="col-sm-6">
    <div class="page-title-box">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?php echo e(route('investigations')); ?>"><?php echo e(trans('form.investigations')); ?></a></li>
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
                        <h4 class="card-title"><?php echo e(trans('form.registration.investigation.create_investigation_title')); ?> <span id="client_credit"></span></h4>
                        <p class="card-title-desc"><?php echo e(trans('form.registration.investigation.create_investigation_desc')); ?></p>
                    </div>

                    <div class="col-12">

                        <form name="investigation-form" id="investigation-form" class="form-horizontal mt-4" method="POST" action="<?php echo e(route('investigation.store')); ?>">

                            <?php echo csrf_field(); ?>

                            <input type="hidden" name="user_id" value="<?php echo e(Auth::id()); ?>" id="user_id">

                            <div id="smartwizard">

                                <ul class="nav">
                                    <li class="nav-item">
                                        <a class="nav-link" href="#step-1">
                                            <strong><?php echo e(trans('form.step_1')); ?></strong> <br><?php echo e(trans('form.registration.investigation.basic_details')); ?>

                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#step-2">
                                            <strong><?php echo e(trans('form.step_2')); ?></strong> <br><?php echo e(trans('form.registration.investigation.subject_details')); ?>

                                        </a>
                                    </li>
                                  
                                </ul>

                                <div class="tab-content">
                                    <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">
                                        <div class="row">
                                            <div class="col-12">
                                                <div id="next_step1">
                                                    <div class="form-row">

                                                        <?php if(isAdmin() || isSM()): ?>
                                                            <div class="form-group col-md-6">
                                                                <label for="userinquiry"><?php echo e(trans('form.registration.investigation.user_inquiry')); ?> <span class="text-danger">*</span></label>
                                                                <select name="user_inquiry" id="userinquiry" class="form-control userinquiry_dd" required>
                                                                    <option disabled selected><?php echo e(trans('form.registration.investigation.select_client_name')); ?></option>
                                                                    <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option value="<?php echo e($key.'-'.$value); ?>"><?php echo e($value); ?></option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="form-group col-md-6">
                                                                <label for="userinquiry"><?php echo e(trans('form.registration.investigation.user_inquiry')); ?> <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control " name="user_inquiry" value="<?php echo e(Auth::user()->name); ?>"
                                                                    required id="userinquiry" placeholder="<?php echo e(trans('form.registration.investigation.user_inquiry')); ?>">
                                                            </div>
                                                        <?php endif; ?>

                                                        <div class="form-group col-md-6">
                                                            <label for="paying_customer"><?php echo e(trans('form.registration.investigation.paying_customer')); ?> <span class="text-danger">*</span></label>
                                                            <select name="paying_customer" id="paying_customer" class="form-control paying_customer_dd" required>
                                                             
                                                                <?php if(isClient()): ?>
                                                               
                                                                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($customer->id); ?>"
                                                                    ><?php echo e($customer->name); ?></option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php endif; ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="fileNum"><?php echo e(trans('form.registration.investigation.file_claim_number')); ?></label>
                                                            <input type="text" name="ex_file_claim_no" value=""
                                                                class="form-control " autofocus id="fileNum"
                                                                placeholder="<?php echo e(trans('form.registration.investigation.file_claim_number')); ?>">
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="claimNum"><?php echo e(trans('form.registration.investigation.claim_number')); ?></label>
                                                            <input type="text" name="claim_number" class="form-control"
                                                                value="" id="claimNum" placeholder="<?php echo e(trans('form.registration.investigation.claim_number')); ?>">
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="orderNum"><?php echo e(trans('form.registration.investigation.work_order_number')); ?></label>
                                                            <input type="text" name="work_order_number" class="form-control"
                                                                   value="<?php echo e($won); ?>" id="orderNum" readonly>
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="paying_customer"><?php echo e(trans('form.registration.investigation.req_type_inquiry')); ?> <span class="text-danger">*</span></label>
                                                            <select name="type_of_inquiry" id="type_of_inquiry" class="form-control " required>
                                                            <?php if(isClient()): ?>
                                                            <?php if(!empty($products)): ?>
                                                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($product->product->id); ?>"
                                                                    data-isdelivery="<?php echo e($product->product->is_delivery == '1' ? 'yes' : 'no'); ?>"
                                                                    data-delcost="<?php echo e($product->product->delivery_cost); ?>"
                                                                    data-price="<?php echo e($product->price); ?>"
                                                                    data-spousecost="<?php echo e($product->product->spouse_cost); ?>"
                                                                    ><?php echo e($product->product->name); ?></option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                            </select>
                                                            <?php if(isClient()): ?>
                                                            <input type="hidden" value="<?php echo e((!empty($products))?$products[0]->price:''); ?>" name="inv_cost" id="inv_cost">
                                                            <?php else: ?>
                                                            <input type="hidden" value="" name="inv_cost" id="inv_cost">
                                                            <?php endif; ?>
                                                            <input type="hidden" name="product_isdel" id="product_isdel"
                                                                   value="<?php echo e(isClient() ? ( !empty($products) ? ($products[0]->product->is_delivery == 1 ? 'yes' : 'no') : 'no' ) : ''); ?>">
                                                            <input type="hidden" name="product_delcost" id="product_delcost"
                                                                   value="<?php echo e(isClient() ? ( !empty($products) ? ($products[0]->product->delivery_cost != null ? $products[0]->product->delivery_cost : 0) : 0 ) : ''); ?>">
                                                            <input type="hidden" name="product_spousecost" id="product_spousecost"
                                                                   value="<?php echo e(isClient() ? ( !empty($products) ? ($products[0]->product->spouse_cost != null ? $products[0]->product->spouse_cost : 0) : 0 ) : ''); ?>">
                                                        </div>

                                                        <div class="form-group del-checks" style="display: none">
                                                            <div class="form-group col-md-12">
                                                                <label for="consent"><?php echo e(trans('form.registration.investigation.type_chk')); ?></label>
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="personal_del" name="personal_del" value="1">
                                                                    <label class="custom-control-label" for="personal_del"><?php echo e(trans('form.registration.investigation.personal_del')); ?></label>
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-md-12">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="company_del" name="company_del" value="1">
                                                                    <label class="custom-control-label" for="company_del"><?php echo e(trans('form.registration.investigation.company_del')); ?></label>
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-md-12 company-del-checks" style="display: none">
                                                            <div class="custom-control custom-checkbox mb-2">
                                                                <input type="checkbox" class="custom-control-input" id="make_paste" name="make_paste" value="1">
                                                                <label class="custom-control-label" for="make_paste"><?php echo e(trans('form.registration.investigation.make_paste')); ?></label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="deliver_by_manager" name="deliver_by_manager" value="1">
                                                                <label class="custom-control-label" for="deliver_by_manager"><?php echo e(trans('form.registration.investigation.delivery_company_manager')); ?></label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row mt-4">
                                                        <div class="col-12 text-right text-xs-center">
                                                            <button onclick="loadSteps('step2', 'step1');"
                                                                class="btn btn-primary w-md waves-effect waves-light"
                                                                type="button"><?php echo e(trans('general.next')); ?></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">
                                        <div class="row">
                                            <div class="col-12">
                                                <div id="next_step2">

                                                    <!-- Block for multiple Subjects-->
                                                    <div class="table-wrapper">
                                                        <div class="table-responsive mb-0 fixed-solution" data-pattern="priority-columns">
                                                            <div id="contacts_tbl">
                                                                <table class="table table-borderless mb-0">
                                                                    <thead>
                                                                    <tr>
                                                                        <th class="btn-primary">
                                                                            <h4 class="font-size-18 mb-1"><?php echo e(trans('form.registration.investigation.subject_details')); ?></h4>
                                                                        </th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody id="contact-accordion">
                                                                    </tbody>
                                                                    <tfoot>
                                                                    <tr>
                                                                        <td id="contacts_footer" class="text-center"> <?php echo e(trans('form.registration.deliveryboy.no_record_added')); ?>

                                                                        </td>
                                                                    </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <?php if(isClient()): ?>
                                                            <div class="col-md-10">
                                                                <div class="pt-3 custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input input_required_s2" id="approval_text" name="approval_text" value="1">
                                                                    <label class="custom-control-label" for="approval_text" style="display: block"><?php echo e(trans('form.investigation.approval_text')); ?></label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2 text-right">
                                                            <?php else: ?>
                                                            <div class="col-md-12 text-right">
                                                            <?php endif; ?>
                                                                <button type="button" onclick="addNewContact()"
                                                                        class="btn btn-link btn-lg waves-effect"
                                                                        style="text-decoration: underline"><?php echo e(trans('form.registration.investigation.add_subject')); ?>

                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Block for multiple Subjects-->


                                                    <!-- Next-Prev Buttons -->
                                                    <div class="form-group row mt-4">
                                                        <div class="col-12 text-right text-xs-center">
                                                            <button onclick="loadSteps('step1', 'step2');"
                                                                class="btn btn-secondary w-md waves-effect waves-light"
                                                                type="button"><?php echo e(trans('general.previous')); ?></button>
                                                            <button 
                                                                class="btn btn-primary w-md waves-effect waves-light create-btn"
                                                                type="submit"><?php echo e(trans('general.create')); ?></button>
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

<div class="card mb-1 shadow-none" id="contact_clone_element" style="display: none">

    <div class="card-header p-3" id="heading_contacts"
         style="padding-bottom: 0 !important;">
        <div class="row"
             style="padding-right: 0; padding-left: 0">
            <div class="col-sm-12 col-md-6 text-left">
                <h6 class="m-0 font-size-14">
                    <a href="#contact_collapse"
                       class="text-dark collapsed contact_header"
                       data-toggle="collapse"
                       aria-expanded="true"
                       aria-controls="contact_collapse">
                        <?php echo e(trans('form.registration.investigation.subject')); ?> : <span class="contact_row_no"></span>

                        
                    </a>
                    <?php if (isAdmin() || isSM()) 
                    {$acflag='';}else{$acflag='d-none';}?>
                           
                    <div class="form-check-inline m-0 <?php echo e($acflag); ?>"> 
                    <label for="contacts_is_default" class="form-check-label"> | <?php echo e(trans('form.registration.investigation.is_address_confirmed')); ?></label>
                    <input name="subjects[]['address_confirmed']" class="arr_contacts_address_confirmed" type="checkbox" switch="bool">
                    <label class="mt-2 ml-1 arr_contacts_address_confirmed_lable" data-on-label="Yes" data-off-label="No"></label>
                    </div>
                </h6>
                
                
            </div>
            <div class="col-sm-12 col-md-6 text-sm-right">
                <button type="button"
                        onclick="deleteContact(this)"
                        class="btn btn-link waves-effect"
                        style="text-decoration: underline">
                    <?php echo e(trans('general.delete')); ?>

                </button>
            </div>
        </div>
    </div>

    <div id="contact_collapse"
         class="collapse contact_block show"
         aria-labelledby="heading_contacts"
         data-parent="#contact-accordion" style="">

        <div class="card-body">

            <!-- Block for multiple subjects-->
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label><?php echo e(trans('form.registration.investigation.family')); ?><span class="text-danger">*</span></label>
                    <input type="text" value=""
                           class="form-control arr_contacts_family input_required_s2"
                           name="subjects[]['family_name']"
                           placeholder="<?php echo e(trans('form.registration.investigation.family')); ?>"
                           data-im-insert="true" required>
                </div>

                <div class="form-group col-md-4">
                    <label><?php echo e(trans('form.registration.investigation.firstname')); ?><span class="text-danger">*</span></label>
                    <input type="text" value=""
                           class="form-control arr_contacts_firstname input_required_s2"
                           name="subjects[]['first_name']"
                           placeholder="<?php echo e(trans('form.registration.investigation.firstname')); ?>"
                           data-im-insert="true" required>
                </div>

                <div class="form-group col-md-4">
                    <label><?php echo e(trans('form.registration.investigation.id')); ?></label>
                    <input onkeypress="settheSubjectId(this)" type="number" maxwidth="9" value=""
                           class="form-control arr_contacts_id"
                           name="subjects[]['id_number']"
                           placeholder="<?php echo e(trans('form.registration.investigation.id')); ?>"
                           data-im-insert="true" data-index="" data-allow="">
                    <label id="" class="id_error error d-none" for="id-error"><?php echo e(trans('form.registration.investigation.id_error')); ?></label>
                </div>
            </div>

            <div class="form-row">
                
                <div class="form-group col-md-4">
                    <label for="accNum"><?php echo e(trans('form.registration.investigation.sub_bank_ac_no')); ?></label>
                    <input type="text" name="subjects[]['bank_account_no']" class="form-control arr_contacts_accNum"
                           value=""
                           placeholder="<?php echo e(trans('form.registration.investigation.sub_bank_ac_no')); ?>">
                </div>

                <div class="form-group col-md-4">
                    <label><?php echo e(trans('form.registration.investigation.workplace')); ?></label>
                    <input type="text" value=""
                           class="form-control arr_contacts_workplace"
                           name="subjects[]['workplace']"
                           placeholder="<?php echo e(trans('form.registration.investigation.workplace')); ?>"
                           data-im-insert="true">
                </div>

                <div class="form-group col-md-4">
                    <label><?php echo e(trans('form.registration.investigation.website')); ?></label>
                    <input type="text" value=""
                           class="form-control arr_contacts_website"
                           name="subjects[]['website']"
                           placeholder="<?php echo e(trans('form.registration.investigation.website')); ?>"
                           data-im-insert="true">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="fatherName"><?php echo e(trans('form.registration.investigation.father_name')); ?></label>
                    <input type="text" name="subjects[]['father_name']" value=""
                           class="form-control arr_contacts_fatherName" autofocus
                           placeholder="<?php echo e(trans('form.registration.investigation.father_name')); ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="motherName"><?php echo e(trans('form.registration.investigation.mothername')); ?></label>
                    <input type="text" name="subjects[]['mother_name']" value=""
                           class="form-control arr_contacts_motherName" autofocus
                           placeholder="<?php echo e(trans('form.registration.investigation.mothername')); ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="spouseName"><?php echo e(trans('form.registration.investigation.spousename')); ?></label>
                    <input type="text" name="subjects[]['spouse_name']" value=""
                           class="form-control arr_contacts_spouseName" autofocus
                           placeholder="<?php echo e(trans('form.registration.investigation.spousename')); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group <?php echo e(($acflag)?'col-md-4':'col-md-4'); ?>">
                    <label for="spouses"><?php echo e(trans('form.registration.investigation.spouse')); ?></label>
                    <input type="number" onkeypress="settheSubjectId(this)" name="subjects[]['spouse']" value=""
                           class="form-control arr_contacts_spouses" autofocus
                           placeholder="<?php echo e(trans('form.registration.investigation.spouse')); ?>">
                    <label id="" class="id_spouses_error error d-none" for="id-spouses-error"><?php echo e(trans('form.registration.investigation.id_spouses_error')); ?></label>
                </div>
                <div class="form-group <?php echo e(($acflag)?'col-md-4':'col-md-4'); ?>">
                    <label for="carNum"><?php echo e(trans('form.registration.investigation.carnumber')); ?></label>
                    <input type="text" name="subjects[]['car_number']" value=""
                           class="form-control arr_contacts_carNum" autofocus
                           placeholder="<?php echo e(trans('form.registration.investigation.carnumber')); ?>">
                </div>
                <div class="form-group <?php echo e(($acflag)?'col-md-4 d-non4':'col-md-4'); ?>">
                    <label for="invCost"><?php echo e(trans('form.registration.investigation.required_cost_invs')); ?>(<?php echo e(trans('general.money_symbol')); ?>) <span
                                class="text-danger">*</span></label>
                    <input type="number" name="subjects[]['req_inv_cost']"
                           class="form-control input_required_s2 arr_contacts_invCost" autofocus required id=""
                           placeholder="<?php echo e(trans('form.registration.investigation.required_cost_invs')); ?>"
                    readonly>
                </div>
                <div class="form-group <?php echo e(($acflag)?'col-md-4':'col-md-4'); ?>">
                    <label for="additionalDetails"><?php echo e(trans('form.registration.investigation.passport')); ?></label>
                    <input type="number" onkeypress="settheSubjectId(this)" name="subjects[]['passport']" value=""
                           class="form-control arr_contacts_passport" autofocus
                           placeholder="<?php echo e(trans('form.registration.investigation.passport')); ?>">
                    <label id="" class="id_passport_error error d-none" for="id-passport-error"><?php echo e(trans('form.registration.investigation.id_passport_error')); ?></label>
                </div>
                <div class="form-group <?php echo e(($acflag)?'col-md-4':'col-md-4'); ?>">
                    <label for="additionalDetails"><?php echo e(trans('form.registration.investigator.date_of_birth')); ?></label>
                    <div class="input-group">
                        <input type="text" name="subjects[]['dob']" value="" 
                                class="form-control arr_contacts_dob datepicker" autofocus
                                placeholder="<?php echo e(trans('form.registration.investigator.date_of_birth')); ?>" readonly="">
                        <span class="input-group-append ml-n1">
                            <div class="input-group-text bg-transparent"><i class="fa fa-calendar-alt"></i></div>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-row">

               
                <div class="form-group col-md-4">
                    <label for="additionalDetails"><?php echo e(trans('form.registration.investigation.add_ass_detail')); ?></label>
                    <input type="text" name="subjects[]['assistive_details']" value=""
                           class="form-control arr_contacts_additionalDetails" autofocus
                           placeholder="<?php echo e(trans('form.registration.investigation.add_ass_detail')); ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="sub_type"><?php echo e(trans('form.registration.investigation.sub_type')); ?></label>
                    <select onchange="changeothertextbox(this);changesubjecttitlebyid(this.id);" name="subjects[][sub_type]" class="form-control arr_contacts_subType multiple_type_required_s2" required data-id="">
                        <?php $__currentLoopData = $subtypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type['name']); ?>" <?php echo e(old('sub_type') == $type['name'] ? 'selected' : ''); ?>><?php echo e(App::isLocale('hr')?$type['hr_name']:$type['name']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group col-md-4 arr_contacts_other_text_div d-none">
                    <label><?php echo e(trans('form.registration.client.type')); ?></label>

                    <input type="text" onchange="changeothersubjecttitle(this);" value=""
                           class="form-control numeric arr_contacts_other_text_type"
                           name="subjects[][other_text]"
                           placeholder="<?php echo e(trans('form.registration.client.type_helper')); ?>">
                </div>

            </div>
            <div class="form-row mt-3">
                <div class="col-md-12">
                    <span class="text-muted font-size-14 mb-1"><?php echo e(trans('form.registration.investigation.subject_contact_details')); ?></span>
                    <hr class="mt-2">
                </div>
            </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label><?php echo e(trans('form.registration.investigation.main_email')); ?></label>
                        <input type="email" value=""
                               class="form-control arr_contacts_mainemails"
                               name="subjects[]['main_email']"
                               placeholder="<?php echo e(trans('general.email_placeholder')); ?>"
                        >
                    </div>
                    <div class="form-group col-md-3">
                        <label><?php echo e(trans('form.registration.investigation.alternate_email')); ?> </label>
                        <input type="email" value=""
                               class="form-control arr_contacts_alternateemails"
                               name="subjects[]['alternate_email']"
                               placeholder="<?php echo e(trans('general.email_placeholder')); ?>"
                        >
                    </div>
                    <div class="form-group col-md-3">
                        <label><?php echo e(trans('form.registration.investigation.main_phone')); ?></label>
                        <input type="tel" value=""
                               class="form-control arr_contacts_mainphones" 
                               name="subjects[]['main_phone']"
                               placeholder="<?php echo e(trans('general.phone_placeholder')); ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label><?php echo e(trans('form.registration.investigation.secondary_phone')); ?></label>
                        <input type="tel" value=""
                               class="form-control arr_contacts_secondaryphones" 
                               name="subjects[]['secondary_phone']"
                               placeholder="<?php echo e(trans('general.phone_placeholder')); ?>"
                              >
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label><?php echo e(trans('form.registration.investigation.main_mobile')); ?></label>
                        <input type="tel" value=""
                               class="form-control arr_contacts_mainmobiles" 
                               name="subjects[]['main_mobile']"
                               placeholder="<?php echo e(trans('general.phone_placeholder')); ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label><?php echo e(trans('form.registration.investigation.secondary_mobile')); ?></label>
                        <input type="tel" value=""
                               class="form-control arr_contacts_secondarymobiles" 
                               name="subjects[]['secondary_mobile']"
                               placeholder="<?php echo e(trans('general.phone_placeholder')); ?>"
                             >
                    </div>
                    <div class="form-group col-md-4">
                        <label><?php echo e(trans('form.registration.investigation.fax')); ?> </label>
                        <input type="tel" value=""
                               class="form-control arr_contacts_fax" 
                               name="subjects[]['fax']"
                               placeholder="<?php echo e(trans('general.fax_placeholder')); ?>">
                    </div>
                </div>
               
            
            <!-- Block for multiple addresses-->
            <div class="form-row mt-3">
                <div class="col-md-12">
                    <span class="text-muted font-size-14 mb-1"><?php echo e(trans('form.registration.investigation.subject_address_details')); ?></span>
                    <hr class="mt-2">
                </div>
            </div>

            <div>
                <div class="table-wrapper">
                    <div class="table-responsive mb-0 fixed-solution"
                         data-pattern="priority-columns">
                        <div id="addresses_tbl">
                            <table class="table table-borderless mb-0">
                                <tbody id="address-accordion" class="addresses_tbl_tbody">
                                </tbody>
                                <tfoot>
                                <tr><td id="address_footer" class="text-center"> <?php echo e(trans('general.no_record_added')); ?> </td></tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 text-right">
                            <button type="button" onclick="addNewAddress(this)"
                                    class="btn btn-link btn-lg waves-effect add-address-btn" id=""
                                    style="text-decoration: underline"><?php echo e(trans('form.add_address')); ?>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Block for multiple addresses-->

        </div>
    </div>
</div>

<div class="card mb-1 shadow-none" id="address_clone_element" style="display: none">
    <div class="card-header p-3" id="address_heading"
         style="padding-bottom: 0 !important;">
        <div class="row"
             style="padding-right: 0; padding-left: 0">
            <div class="col-sm-12 col-md-6 text-left">
                <h6 class="m-0 font-size-14">
                    <a href="#address_collapse"
                       class="text-dark collapsed address_header"
                       data-toggle="collapse"
                       aria-expanded="true"
                       aria-controls="collapse_1">
                        <?php echo e(trans('form.address')); ?> : <span class="address_row_no"></span>
                    </a>
                </h6>
            </div>
            <div class="col-sm-12 col-md-6 text-sm-right">
                <button type="button"
                        onclick="deleteAddress(this)"
                        class="btn btn-link waves-effect delete-address-btn"
                        style="text-decoration: underline" id="">
                    <?php echo e(trans('general.delete')); ?>

                </button>
            </div>
        </div>

    </div>

    <div id="address_collapse"
         class="collapse address_block show"
         aria-labelledby="address_heading"
         data-parent="#address-accordion" style="">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label><?php echo e(trans('form.registration.client.address')); ?>

                        <span class="text-danger">*</span></label>
                    <input type="text" value=""
                           class="form-control pac-target-input multiple_input_required_s2 arr_address_address1"
                           name="subjects[]['address'][]['address1']"
                           placeholder="<?php echo e(trans('form.registration.client.address_helper')); ?>"
                           required
                           autocomplete="off">

                </div>

                <div class="form-group col-md-6">
                    <label><?php echo e(trans('form.registration.client.address2')); ?></label>
                    <input type="text" value=""
                           class="form-control arr_address_address2"
                           name="subjects[]['address'][]['address2']"
                           placeholder="<?php echo e(trans('form.registration.client.address_2_helper')); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-3">
                    <label><?php echo e(trans('form.registration.client.city')); ?>

                        <span class="text-danger">*</span></label>
                    <input type="text" value=""
                           class="form-control multiple_input_required_s2 arr_address_city"
                           name="subjects[]['address'][]['city']"
                           placeholder="<?php echo e(trans('form.registration.client.city')); ?>"
                           required>
                </div>

                <div class="form-group col-md-3">
                    <label><?php echo e(trans('form.registration.client.state')); ?>

                        <span class="text-danger">*</span></label>
                    <input type="text" value=""
                           class="form-control multiple_input_required_s2 arr_address_state"
                           name="subjects[]['address'][]['state']"
                           placeholder="<?php echo e(trans('form.registration.client.state')); ?>"
                           required>
                </div>

                <div class="form-group col-md-3">
                    <label><?php echo e(trans('form.registration.client.country')); ?>

                        <span class="text-danger">*</span></label>
                        <select name="subjects[]['address'][]['country_id']"
                            class="form-control multiple_input_required_s2 arr_address_country"
                            required>
                            <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $country_name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($country_name['id']); ?>" <?php echo e(old('country_id') == $country_name['id'] ? 'selected' : ''); ?>><?php echo e(App::isLocale('hr')?$country_name['hr_name']:$country_name['en_name']); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-group col-md-3">
                    <label><?php echo e(trans('form.registration.client.zip_code')); ?>

                        </label>
                    <input type="text" value=""
                           class="form-control numeric arr_address_zip"
                           name="subjects[]['address'][]['zipcode']"
                           placeholder="<?php echo e(trans('form.registration.client.zip_code')); ?>"
                           >
                </div>

                <div class="form-group col-md-4">
                    <label><?php echo e(trans('form.registration.client.address_type')); ?></label>
                    
                    <input type="text" value="" class="form-control multiple_input_required_s2 arr_address_type multiple_type_required_s2" name="subjects[]['address'][]['address_type']" placeholder="<?php echo e(trans('form.registration.client.address_type')); ?>" required>
                        
                    
                </div>

                <div class="form-group col-md-4 arr_add_other_text_div d-none">
                    <label class="address_other_no"><?php echo e(trans('form.registration.client.type')); ?></label>

                    <input type="text" onchange="changeothersubjecttitle(this);" value=""
                           class="form-control numeric arr_add_other_text_type"
                           name="subjects[]['address'][]['other_text']"
                           placeholder="<?php echo e(trans('form.registration.client.type_helper')); ?>">
                </div>

            </div>
        </div>
    </div>
</div>



<?php $__env->stopSection(); ?>

<?php $__env->startSection('footerScript'); ?>
<!-- footerScript -->
<script src="<?php echo e(URL::asset('/libs/select2/select2.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/libs/jquery-smartwizard/jquery.smartWizard.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/libs/pdfmake/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/libs/inputmask/5.0.5/jquery.inputmask.js')); ?>" async></script>
<script src="<?php echo e(URL::asset('/libs/sweetalert2/sweetalert2.min.js')); ?>" async></script>
<script src="<?php echo e(URL::asset('/libs/jquery-validate/1.19.0/jquery.validate.js')); ?>"></script>  
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(env('GOOGLE_KEY')); ?>&libraries=places&language=<?php echo e(App::isLocale('hr') ? 'iw' : 'en'); ?>" async defer></script>
<?php if(App::isLocale('hr')): ?>
    <script src="<?php echo e(URL::asset('/libs/jquery-validate/1.19.0/localization/messages_he.js')); ?>"></script>
<?php endif; ?>
<script>
    $(document).ready(function () {
        $(window).keydown(function (event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });

        $('#smartwizard').smartWizard({
            selected: 0, // Initial selected step, 0 = first step
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
        
        $("#investigation-form").validate({
            ignore: false,
            invalidHandler: function (e, validator) {
                // loop through the errors:
                for (var i = 0; i < validator.errorList.length; i++) {
                    $(validator.errorList[i].element).closest('.collapse').collapse('show');
                    console.log('error',validator.errorList[i].element);
                    $(this).find(":input.error:first").focus();
                }
            }
        });

        $('.userinquiry_dd').select2();

        $('#type_of_inquiry').on('change', function(e){
            if($('#type_of_inquiry option:selected').text()){
                console.log('inv_cost :>>', $('#type_of_inquiry option:selected').data('price'));
                $('#inv_cost').val($('#type_of_inquiry option:selected').data('price'));
                $('.arr_contacts_invCost').val($('#type_of_inquiry option:selected').data('price'));
                $("#product_spousecost").val($('#type_of_inquiry option:selected').data('spousecost'));
                console.log('inv_cost spousecost :>>', $('#type_of_inquiry option:selected').data('spousecost'));
                if($('#type_of_inquiry option:selected').data('isdelivery') == 'yes'){
                    $(".del-checks").show();
                    $("#product_isdel").val('yes');
                    $("#product_delcost").val($('#type_of_inquiry option:selected').data('delcost'));
                    
                    console.log('product_delcost :>>', $('#type_of_inquiry option:selected').data('delcost'));
                   
                }else{
                    $(".del-checks").hide();
                    $("#product_isdel").val('no');
                    $("#product_delcost").val('');
                  
                }
            }
        });

        $('.userinquiry_dd').on('change', function(e){
            var userId = (e.target.value).split("-")[0];
            $(".del-checks").hide();
            $("#type_of_inquiry").empty();
            $.ajax({
                url: '<?php echo e(route("client.customerdata")); ?>',
                type:"POST",
                data: {
                   "type": "checkData",
                   "id": userId,
                   "_token": "<?php echo e(csrf_token()); ?>",
                },
                success:function (response) {
                    $("#paying_customer").empty();

                    if (response.status == 1) {
                        var firstval = null;
                        $.each(response.data, function(key, value) {
                          
                           var option = "<option value='"+ value.id +"' >" + value.name + " </option>";

                            $("#paying_customer").append(option);
                            firstval = firstval !== null ? firstval : value.id;
                        });
                        $("#paying_customer").val(firstval).trigger('change');
                        if(firstval!=null){
                            if(firstval==userId){
                                Swal.fire({
                            title: "",
                            text: '<?php echo e(trans("form.clientcustomer.you_have_selected_same_person")); ?>',
                            type: 'question',
                            confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
                            }).then((result) => {
                                        
                                    });
                            }
                            
                        }
                       
                    }
                
                }
            });
            //setncheckClientCredit(userId,'client_credit','getprintcredit');
        });

        $('.paying_customer_dd').on('change', function (e) {
            var firstval = $('#paying_customer option:selected').val();
            let userid = document.getElementById('userinquiry').value;
            let isAdmin = "<?php echo e((isAdmin() || isSM()) ? 'true' : 'false'); ?>";
            userid = (isAdmin == 'true') ? userid.split("-")[0] : document.getElementById('user_id').value;

            if (firstval == userid) {
                Swal.fire({
                    title: "",
                    text: '<?php echo e(trans("form.clientcustomer.you_have_selected_same_person")); ?>',
                    type: 'question',
                    confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
                }).then((result) => {

                });
            }

            $.ajax({
                url: "/clients/" + firstval + "/getProducts",
                type: "get",
                success: function (response) {
                    $("#type_of_inquiry").empty();
                    if (response.status == 'success') {
                        var firstval = null;
                        $.each(response.data, function (key, value) {
                            var isdel = value.product.is_delivery == '1' ? 'yes' : 'no';
                            var delcost = value.product.delivery_cost;
                            var spousecost = value.product.spouse_cost;
                            var option = "<option data-price='" + value.price + "' value='" + value.product.id + "' data-spousecost='" + spousecost + "' data-delcost='" + delcost + "' data-isdelivery='" + isdel + "'>" + value.product.name + " </option>";

                            $("#type_of_inquiry").append(option);
                            firstval = firstval !== null ? firstval : value.product.id;
                        });

                        $("#type_of_inquiry").val(firstval).trigger('change');
                    }
                }
            });
        });

        $("#company_del").on('change', function() {
            if(this.checked){
                $(".company-del-checks").show();
            }else{
                $(".company-del-checks").hide();
            }
        });

        // for custom validation before form submit to check all multiple fields has entries
        $('#investigation-form').on('submit', function (e) {
            e.preventDefault(); // prevent the form submit
            $('input[class="arr_contacts_mainphones"]').inputmask('unmaskedvalue');

            //var subNo = obj.id;
            let status = true;
            status = checkSubjectType();
            
            if(!status){
                Swal.fire("<?php echo e(trans('general.error_text')); ?>","<?php echo e(trans('general.cannot_add_new_subject')); ?>", "error");
                return;
            }
            var valid = customFormValidation();
            if(valid == true){
                let isIdNull = false;
                let isSubmit = true;
                $('.arr_contacts_id').each(function (index, object){
                    console.log(index,'index')    
                    if(index!=0){
                        let ele = $('#subjects_id_'+index);
                        if(ele.val()==''){
                            isIdNull=true;
                        } else {
                            let val = $('#subjects_id_'+index).val();
                            let code = calcCode(val);
                            let lastDigit = val.substr(8,9);
                            let errorEle = $('#id-error-'+index);
                            if(lastDigit != code){
                                errorEle.removeClass('d-none');
                                errorEle.css('display', 'block');
                                errorEle.html('<?php echo e(trans('form.registration.investigation.id_error')); ?>');
                                isSubmit = false;    
                            } else {
                                errorEle.addClass('d-none');
                                errorEle.html('');
                            }
                        }
                    }
                });
                
                $('.arr_contacts_spouses').each(function (index, object){
                    if(index!=0){
                        let ele = $('#subjects_spouses_id_'+index);
                        let val = $('#subjects_spouses_id_'+index).val();
                        let code = calcCode(val);
                        let lastDigit = val.substr(8,9);
                        let errorEle = $('#id-spouses-error-'+index);
                        if(lastDigit != code && ele.val()!=''){
                            errorEle.removeClass('d-none');
                            errorEle.css('display', 'block');
                            errorEle.html('<?php echo e(trans('form.registration.investigation.id_spouses_error')); ?>');
                            isSubmit = false;    
                        } else {
                            errorEle.addClass('d-none');
                            errorEle.html('');
                        }
                    }
                });

                $('.arr_contacts_passport').each(function (index, object){
                    if(index!=0){
                        let ele = $('#subjects_passport_id_'+index);
                        let val = $('#subjects_passport_id_'+index).val();
                        let code = calcCode(val);
                        let lastDigit = val.substr(8,9);
                        let errorEle = $('#id-passport-error-'+index);
                        if(lastDigit != code && ele.val()!=''){
                            errorEle.removeClass('d-none');
                            errorEle.css('display', 'block');
                            errorEle.html('<?php echo e(trans('form.registration.investigation.id_passport_error')); ?>');
                            isSubmit = false;    
                        } else {
                            errorEle.addClass('d-none');
                            errorEle.html('');
                        }
                    }
                });
                console.log(isIdNull,'isIdNull')
                if(isSubmit){
                    if(isIdNull){
                        Swal.fire({
                            title: "",
                            text: '<?php echo e(trans("form.clientcustomer.you_have_fill_id")); ?>',
                            type: 'question',
                            confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
                            cancelButtonText: "<?php echo e(trans('general.cancel')); ?>"
                        }).then((result) => {
                            if(result.value){
                                $('.create-btn').text("<?php echo e(trans('general.processing')); ?>" + '...');
                                var url = '<?php echo e(route('investigation.store')); ?>';

                                // create the FormData object from the form context (this),
                                // that will be present, since it is a form event
                                var formData = new FormData(this);

                                $.ajax({
                                    url: url,
                                    type: 'POST',
                                    data: formData,
                                    success: function (response) {
                                        if (response.status == 'success') {
                                            Swal.fire({
                                                title: "<?php echo e(trans('general.created_text')); ?>",
                                                text: (response.message) ? response.message : "<?php echo e(trans('form.registration.investigation.new_investigation_added')); ?>", 
                                                type: "success",
                                                confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
                                            })
                                            .then((result) => {
                                                if(result.value){
                                                    <?php if(isClient()): ?>
                                                        window.location.href = "/investigations/View/"+response.data.id+"#documents";
                                                    <?php else: ?> 
                                                        window.location.href = "<?php echo e(route('investigations')); ?>";
                                                    <?php endif; ?>
                                                    
                                                }
                                            });
                                        } else {
                                            Swal.fire("<?php echo e(trans('general.error_text')); ?>",(response.message) ? response.message : "<?php echo e(trans('general.something_wrong')); ?>", "error");
                                            console.log(response);
                                            $('.create-btn').text("<?php echo e(trans('general.create')); ?>");
                                        }
                                    },
                                    error: function (response) {
                                        Swal.fire("<?php echo e(trans('general.error_text')); ?>",(response.message) ? response.message : "<?php echo e(trans('general.something_wrong')); ?>", "error");
                                        $('.create-btn').text("<?php echo e(trans('general.create')); ?>");
                                    },
                                    contentType: false,
                                    processData: false,
                                });            
                            }                
                        });
                    } else {
                        $('.create-btn').text("<?php echo e(trans('general.processing')); ?>" + '...');
                        var url = '<?php echo e(route('investigation.store')); ?>';

                        // create the FormData object from the form context (this),
                        // that will be present, since it is a form event
                        var formData = new FormData(this);

                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: formData,
                            success: function (response) {
                                if (response.status == 'success') {
                                    Swal.fire({
                                        title: "<?php echo e(trans('general.created_text')); ?>",
                                        text: (response.message) ? response.message : "<?php echo e(trans('form.registration.investigation.new_investigation_added')); ?>", 
                                        type: "success",
                                        confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
                                    })
                                    .then((result) => {
                                        if(result.value){
                                            window.location.href = "<?php echo e(route('investigations')); ?>";
                                        }
                                    });
                                } else {
                                    Swal.fire("<?php echo e(trans('general.error_text')); ?>",(response.message) ? response.message : "<?php echo e(trans('general.something_wrong')); ?>", "error");
                                    console.log(response);
                                    $('.create-btn').text("<?php echo e(trans('general.create')); ?>");
                                }
                            },
                            error: function (response) {
                                Swal.fire("<?php echo e(trans('general.error_text')); ?>",(response.message) ? response.message : "<?php echo e(trans('general.something_wrong')); ?>", "error");
                                $('.create-btn').text("<?php echo e(trans('general.create')); ?>");
                            },
                            contentType: false,
                            processData: false,
                        });
                    }
                }
            }
        });

        let userid = document.getElementById('userinquiry').value;
        let isAdmin = "<?php echo e((isAdmin() || isSM()) ? 'true' : 'false'); ?>";
        userid = (isAdmin == 'true') ? userid.split("-")[0] : document.getElementById('user_id').value;

        if(isAdmin !== 'true'){
           // setncheckClientCredit(userid, 'client_credit', 'getprintcredit');
        }

        $(document).on('change', '.arr_contacts_subType', function(){
            if($(this).val() == 'Spouse'){
                $("#invcost_" +  $(this).data("id")).val(parseFloat(((parseFloat($("#product_spousecost").val()) * parseFloat($("#inv_cost").val())) / 100)));
            }else{
                $("#invcost_" +  $(this).data("id")).val(parseFloat($("#inv_cost").val()));
            }
        });

        loadSteps('step1', 'step1');

    });

    function checkCurrentcredit(){
        var x = document.getElementsByClassName("arr_contacts_invCost");
            var i;
            var totcurinv=0;
            for (i = 0; i < x.length; i++) {
                if(isNaN(x[i].value)){
                continue;
                 }
            totcurinv += Number(x[i].value);
            }
            var userid=document.getElementById('userinquiry').value;
            var isAdmin = "<?php echo e((isAdmin() || isSM()) ? 'true' : 'false'); ?>";
            userid= isAdmin == 'true' ? userid.split("-")[0] : document.getElementById('user_id').value;
            
          setncheckClientCredit(userid,'credit_error','checkcurrentcredit',totcurinv);
    }

    // function for set and check the client available credit
    function setncheckClientCredit(uid, outid, type, curcredit) {
        var ret = false;

        $.ajax({
            url: "/clients/" + uid + "/getCredit",
            type: "get",
            success: function (response) {
                $("#" + outid).empty();
                $(':input[type="submit"]').prop('disabled', false);
                if (response.status == 'success') {
                    if (type == 'getprintcredit') {
                        $("#" + outid).append("<span class='badge dt-badge badge-success'><?php echo e(trans('form.registration.investigation.available_credit')); ?></span> <span class='badge dt-badge badge-primary'><?php echo e(trans('general.money_symbol')); ?>" + response.credit + "</span>");
                    }
                    if (type == 'checkcurrentcredit') {
                        var totalcredit = parseInt(response.credit) - parseInt(curcredit);
                        if (totalcredit < 0 || totalcredit == 0) {
                            $(':input[type="submit"]').prop('disabled', true);
                            $("#" + outid).append(" <h5><span class='badge dt-badge badge-danger'><?php echo e(trans('general.credit_limit_message')); ?></span></h5>");
                        }
                    }
                }
            }
        });

        return ret;
    }

    // function step 1 and step 2 show hide
    function loadSteps(step, backstep) {
        // console.log('step ', step);
        // console.log('backstep ', backstep);

        if (step == 'step1') {
            // $('#next_step1').show();
            // $('#next_step2').hide();
            // $('#next_step3').hide();

            if (backstep == 'step2') {
                $('.multiple_input_required_s2').each(function () {
                    // console.log('removing rule of :>>', $(this));
                    $(this).rules('remove', 'required');
                    $(this).removeAttr('required');
                });
                $('.multiple_type_required_s2').each(function () {
                    // console.log('removing rule of :>>', $(this));
                    var id=$(this).attr('id');
                     if(id!='' && id!='undefined' && ($(this).val()=='Other' || $(this).val()=='Contact'))
                     {
                         $("#"+id+"_otext").rules('remove', 'required');
                         $("#"+id+"_otext").removeAttr('required');
                     
                     }
                });

                $('.input_required_s2').each(function () {
                    // console.log('removing rule of :>>', $(this));
                    $(this).rules('remove', 'required');
                    $(this).removeAttr('required');
                });
            }

            $('#userinquiry').rules('add', {
                required: true,
            });
            $('#paying_customer').rules('add', {
                required: true,
            });
            $('#type_of_inquiry').rules('add', {
                required: true,
            });

            // $('#smartwizard').smartWizard("goToStep", 0);
            $('#smartwizard').smartWizard("reset");

        } else if (step == 'step2') {

            if ((backstep == 'step1' && $("#investigation-form").valid() === true)) {
                // $('#next_step2').show();
                // $('#next_step1').hide();
                // $('#next_step3').hide();

               /*  if (backstep == 'step3') {
                    $('.multiple_input_required_s3').each(function () {
                        // console.log('removing rule of :>>', $(this));
                        $(this).rules('remove', 'required');
                        $(this).removeAttr('required');
                    });
                } */

                $('.multiple_input_required_s2').each(function (e) {
                    // console.log('adding rule of :>>', $(this));
                    $(this).rules('add', {
                        required: true
                    });
                    $(this).attr("required", true);
                });
                $('.multiple_type_required_s2').each(function () {
                    // console.log('removing rule of :>>', $(this));
                    var id=$(this).attr('id');
                    if(id!='' && id!='undefined' && ($(this).val()=='Other' || $(this).val()=='Contact'))
                     {
                            $("#"+id+"_otext").rules('add', {
                            required: true
                        });
                        $("#"+id+"_otext").attr("required", true);
                        
                     
                     }
                });

                $('.input_required_s2').each(function (e) {
                    // console.log('adding rule of :>>', $(this));
                    $(this).rules('add', {
                        required: true
                    });
                    $(this).attr("required", true);
                });

                $('#smartwizard').smartWizard("goToStep", 1);
            }
            

        } else if (step == 'step3') {
           if ($("#investigation-form").valid() === true) {

                if ($(".contact-base-row").length < 1) {
                    $("#contacts_footer").html('<span class="error"><?php echo e(trans('general.minimum_entry')); ?></span>');

                    return false;
                } else {
                    $("#contacts_footer").hide('');
                }

                // if ($(".address-base-row").length < 1) {
                //     $("#address_footer").html('<span class="error">Please add minimum one Entry!</span>');
                //
                //     return false;
                // } else {
                //     $("#address_footer").hide('');
                // }

                $('.multiple_input_required_s3').each(function (e) {
                    // console.log('adding rule of :>>', $(this));
                    $(this).rules('add', {
                        required: true
                    });
                    $(this).attr("required", true);
                });

                // $('#next_step3').show();
                // $('#next_step2').hide();
                // checkCurrentcredit();
                $('#smartwizard').smartWizard("goToStep", 2);
            }
        }
    }

    // Functions for multiple addresses/contacts fields
    function customFormValidation()
    {
        var isValid = false;
        let statusId = true;
        var cnt = 0;
        var basicValidation = $("#investigation-form").valid();

        if ($(".contact-base-row").length < 1) {
                    $("#contacts_footer").html('<span class="error"><?php echo e(trans('general.minimum_entry')); ?></span>');

                    return false;
                } 
                else {
                    $("#contacts_footer").hide('');
                }

        if (basicValidation) {
            cnt++;
        }
        
        $('.arr_contacts_id').each(function (index, object){
            let ele = $('#subjects_id_'+index);
            
            let allow = $(object).attr('data-allow');
            
            if(allow){
                if(allow == 'no'){
                    let errorEle = $('#id-error-'+(index+1));
                    console.log(errorEle,'here');
                    errorEle.css('display', 'block');
                    errorEle.html('<?php echo e(trans('form.registration.investigation.id_error')); ?>')
                    //statusId = false;
                }
            }
            
        });
        
        $("form tbody:not('.addresses_tbl_tbody')").each(function (index) {
            if ($(this).find("tr").length > 0) {
                cnt++;
                console.log("cnt=",cnt);
                if (cnt == 2) {
                    isValid = true;
                    return true;
                }
            } else {
                console.log("isValid2",isValid);
                var footerEle = $(this).parent().find("tfoot tr td");
                footerEle.html('<span class="error"><?php echo e(trans('general.minimum_entry')); ?></span>');
                footerEle.show();
            }
        });
        
        // if(!statusId){
        //     isValid=false;
        //     return false;
        // }

        return isValid;
    }

    function addNewAddress(obj)
    {
        $("#address_footer").hide();
        var lastNo, originalId;
        var arrRowNo = [];

        var subNo = obj.id;

        var baseTbl = $('#address-accordion-' + subNo);
        originalId = baseTbl.find('.address_block:last').attr('id');
        var baseRow = 'address-base-row-' + subNo;

        if (originalId){
            arrRowNo = originalId.split("-");
        }

        if (arrRowNo.length > 1) {
            lastNo = arrRowNo[2];
        } else {
            lastNo = 0;
        }

        let subType = $("select[name='subjects[" + parseInt(subNo) + "][sub_type]']").val();
        console.log("subjectstype ", subType);
        console.log("lastNo ", lastNo);
        // if(subType == 'Main' && parseInt(lastNo) > 0){
        //     Swal.fire("<?php echo e(trans('general.error_text')); ?>","<?php echo e(trans('general.cannot_add_new_address')); ?>", "error");
        //     return;
        // }

        var cloned = $("#address_clone_element").clone().appendTo('#address-accordion-' + subNo).wrap('<tr class="'+baseRow+'" style="display: block;"><td style="width: 100%;display: inline-table;"></td></tr>');

        var newId = 'address_collapse-' + subNo + '-' + (parseInt(lastNo) + 1);

        cloned.show();
        cloned.attr('id', 'address_clone_element-' + subNo + '-' + (parseInt(lastNo) + 1));

        cloned.find(".address_block").attr('id', newId);
        cloned.find(".address_header").attr('href', '#' + newId);
        cloned.find(".address_row_no").html($('.'+baseRow).length);
        cloned.find(".delete-address-btn").attr('id', parseInt(subNo));

        cloned.find(".arr_address_address1").attr('name', "subjects[" + parseInt(subNo) + "][address][" + (parseInt(lastNo) + 1) + "][address1]");
        cloned.find(".arr_address_address1").attr('id', "address_complete_" + parseInt(subNo) + '-' + (parseInt(lastNo) + 1));
        cloned.find(".arr_address_address2").attr('name', "subjects[" + parseInt(subNo) + "][address][" + (parseInt(lastNo) + 1) + "][address2]");
        cloned.find(".arr_address_address2").attr('id', "address2_" + parseInt(subNo) + '-' + (parseInt(lastNo) + 1));
        cloned.find(".arr_address_city").attr('name', "subjects[" + parseInt(subNo) + "][address][" + (parseInt(lastNo) + 1) + "][city]");
        cloned.find(".arr_address_city").attr('id', "city_" + parseInt(subNo) + '-' + (parseInt(lastNo) + 1));
        cloned.find(".arr_address_state").attr('name', "subjects[" + parseInt(subNo) + "][address][" + (parseInt(lastNo) + 1) + "][state]");
        cloned.find(".arr_address_state").attr('id', "state_" + parseInt(subNo) + '-' + (parseInt(lastNo) + 1));
        cloned.find(".arr_address_country").attr('name', "subjects[" + parseInt(subNo) + "][address][" + (parseInt(lastNo) + 1) + "][country_id]");
        cloned.find(".arr_address_country").attr('id', "country_" + parseInt(subNo) + '-' + (parseInt(lastNo) + 1));
        cloned.find(".arr_address_zip").attr('name', "subjects[" + parseInt(subNo) + "][address][" + (parseInt(lastNo) + 1) + "][zipcode]");
        cloned.find(".arr_address_zip").attr('id', "zipcode_" + parseInt(subNo) + '-' + (parseInt(lastNo) + 1));

        cloned.find(".arr_address_type").attr('name', "subjects[" + parseInt(subNo) + "][address][" + (parseInt(lastNo) + 1) + "][address_type]");
        cloned.find(".arr_address_type").attr('id', "arr_address_type_" + parseInt(subNo) + '-' + (parseInt(lastNo) + 1));
        cloned.find(".arr_add_other_text_type").attr('name', "subjects[" + parseInt(subNo) + "][address][" + (parseInt(lastNo) + 1) + "][other_text]");
        cloned.find(".arr_add_other_text_type").attr('id', "arr_address_type_" + parseInt(subNo) + '-' + (parseInt(lastNo) + 1) +"_otext");
        cloned.find(".arr_add_other_text_type").attr('data-id',  "arr_address_type_" + parseInt(subNo) + '-' + (parseInt(lastNo) + 1));
        cloned.find(".arr_add_other_text_div").attr('id', "arr_address_type_" + parseInt(subNo) + '-' + (parseInt(lastNo) + 1) +"_div");
        
        cloned.find(".address_row_no").attr('id', "arr_address_type_"  + parseInt(subNo) + '-' + (parseInt(lastNo) + 1) + "_title");

        cloned.find(".address_other_no").attr('id', "arr_address_type_"  +(parseInt(lastNo) + 1) + "_olabel");

        cloned.find(".arr_address_address1").focus();
        setAddressnew(parseInt(subNo), (parseInt(lastNo) + 1));
        changesubjecttitlebyid("arr_address_type_" + parseInt(subNo) + '-' + (parseInt(lastNo) + 1));
        cloned.find('input[type=text]').val('');
        cloned.find('.collapse').collapse('show');
    }

    function deleteAddress(ele)
    {
        var subNo = ele.id;
        $(ele).closest(".address-base-row-"+subNo).remove();

        $(".address-base-row-"+subNo).each(function (index) {
            //$(this).find(".address_row_no").html(index + 1);
        });

        if ($(".address-base-row-"+subNo).length < 1) {
            $("#address_footer").html('<span>No record added</span>');
            $("#address_footer").show();
        }
    }

    function addNewContact() {
        $("#contacts_footer").hide();
        var lastNo, originalId;
        var arrRowNo = [];
        var baseTbl = $("#contact-accordion");
        originalId = baseTbl.find('.contact_block:last').attr('id');

        var cloned = $("#contact_clone_element").clone().appendTo('#contact-accordion').wrap('<tr class="contact-base-row" style="display: block;"><td style="width: 100%;display: inline-table;"></td></tr>');

        if (originalId)
            arrRowNo = originalId.split("-");

        if (arrRowNo.length > 1) {
            lastNo = arrRowNo[1];
        } else {
            lastNo = 0;
        }

        var newId = 'contact_collapse-' + (parseInt(lastNo) + 1);

        cloned.css('display', 'block');
        cloned.attr('id', 'contact_clone_element-' + (parseInt(lastNo) + 1));

        cloned.find(".contact_block").attr('id', newId);
        cloned.find(".addresses_tbl_tbody").attr('id', 'address-accordion-' + (parseInt(lastNo) + 1));
        cloned.find(".add-address-btn").attr('id', (parseInt(lastNo) + 1));

        cloned.find(".contact_header").attr('href', '#' + newId);
        cloned.find(".contact_row_no").html($(".contact-base-row").length);

        cloned.find(".arr_contacts_address_confirmed").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][address_confirmed]");
        cloned.find(".arr_contacts_address_confirmed").attr('id', "address_confirmed_" + (parseInt(lastNo) + 1));
        cloned.find(".arr_contacts_address_confirmed_lable").attr('for', "address_confirmed_" + (parseInt(lastNo) + 1));
        cloned.find(".arr_contacts_family").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][family_name]");
        cloned.find(".arr_contacts_firstname").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][first_name]");
        cloned.find(".arr_contacts_id").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][id_number]");
        cloned.find(".arr_contacts_id").attr('id', "subjects_id_" + (parseInt(lastNo) + 1));
        cloned.find(".arr_contacts_account_no").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][account_no]");
        cloned.find(".arr_contacts_workplace").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][workplace]");
        cloned.find(".arr_contacts_website").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][website]");
        cloned.find(".arr_contacts_fatherName").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][father_name]");
        cloned.find(".arr_contacts_motherName").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][mother_name]");
        cloned.find(".arr_contacts_spouseName").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][spouse_name]");

        cloned.find(".arr_contacts_spouses").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][spouse]");
        cloned.find(".arr_contacts_spouses").attr('data-index', (parseInt(lastNo) + 1 ));
        cloned.find(".arr_contacts_spouses").attr('id', "subjects_spouses_id_" + (parseInt(lastNo) + 1));
        cloned.find(".id_spouses_error").attr('id', "id-spouses-error-" + (parseInt(lastNo) + 1 ));

        cloned.find(".arr_contacts_carNum").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][car_number]");
        cloned.find(".arr_contacts_invCost").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][req_inv_cost]");
        cloned.find(".arr_contacts_invCost").attr('id', "invcost_" + (parseInt(lastNo) + 1));
        cloned.find(".arr_contacts_subType").attr('data-id', (parseInt(lastNo) + 1));
        cloned.find(".arr_contacts_accNum").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][bank_account_no]");

        cloned.find(".arr_contacts_passport").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][passport]");
        cloned.find(".arr_contacts_passport").attr('data-index', (parseInt(lastNo) + 1 ));
        cloned.find(".arr_contacts_passport").attr('id', "subjects_passport_id_" + (parseInt(lastNo) + 1));
        cloned.find(".id_passport_error").attr('id', "id-passport-error-" + (parseInt(lastNo) + 1 ));

        cloned.find(".arr_contacts_dob").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][dob]");
        cloned.find(".arr_contacts_additionalDetails").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][assistive_details]");
        cloned.find(".arr_contacts_subType").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][sub_type]");

        cloned.find(".arr_contacts_subType").attr('id', "arr_contacts_type_" + (parseInt(lastNo) + 1 ));

        cloned.find(".id_error").attr('id', "id-error-" + (parseInt(lastNo) + 1 ));
        cloned.find(".arr_contacts_id").attr('data-index', (parseInt(lastNo) + 1 ));

        cloned.find(".arr_contacts_other_text_type").attr('name', "subjects[" + (parseInt(lastNo) + 1)+ "][other_text]");
        cloned.find(".arr_contacts_other_text_type").attr('id', "arr_contacts_type_" + (parseInt(lastNo) + 1)+ "_otext");
        cloned.find(".arr_contacts_other_text_type").attr('data-id',  "arr_contacts_type_" + (parseInt(lastNo) + 1 ));
        cloned.find(".arr_contacts_other_text_div").attr('id', "arr_contacts_type_" + (parseInt(lastNo) + 1)+ "_div");
        
        cloned.find(".arr_contacts_mainemails").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][main_email]");
        cloned.find(".arr_contacts_alternateemails").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][alternate_email]");
        cloned.find(".arr_contacts_mainphones").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][main_phone]");
        cloned.find(".arr_contacts_secondaryphones").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][secondary_phone]");
        cloned.find(".arr_contacts_mainmobiles").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][main_mobile]");
        cloned.find(".arr_contacts_secondarymobiles").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][secondary_mobile]");
        cloned.find(".arr_contacts_fax").attr('name', "subjects[" + (parseInt(lastNo) + 1) + "][fax]");

        
        cloned.find(".contact_row_no").attr('id', "arr_contacts_type_" + (parseInt(lastNo) + 1)+ "_title");

        cloned.find('input[type=text]').val('');
        cloned.find('.collapse').collapse('show');

        cloned.find("input[name='subjects[" + (parseInt(lastNo) + 1) + "][req_inv_cost]']").val($('#type_of_inquiry option:selected').data('price'));
        cloned.find(".arr_contacts_family").focus();
        addinputmask('contact');
        changesubjecttitlebyid("arr_contacts_type_"+(parseInt(lastNo) + 1 ));
        datePicker();
    }

    function deleteContact(ele) {
        $(ele).closest(".contact-base-row").remove();

        $(".contact-base-row").each(function (index) {
            $(this).find(".contact_row_no").html(index + 1);
        });

        if ($(".contact-base-row").length < 1) {
            $("#contacts_footer").html('<span>No record added</span>');
            $("#contacts_footer").show();
        }
    }

    function deleteRows(ele) {
        $(ele).closest('tr').remove();

        if ($(ele).parent('tbody').find("tr").length < 1) {
            var footerEle = $(ele).parent().find("tfoot tr td");
            $(footerEle).html('<span>No record added</span>');
            $(footerEle).show();
        }
    }

    // This Function for Address Type Dropdown Othertext to open textbox
    function changeothertextbox(t){
        var id = t.id;
        let status = true;
        status = checkSubjectType();
        console.log(status,'status')
        if(!status){
            Swal.fire("<?php echo e(trans('general.error_text')); ?>","<?php echo e(trans('general.cannot_add_new_subject')); ?>", "error");
            return;
        }
        if(t.value=='Other' || t.value=='Contact'){
            $("#"+id+"_div").removeClass('d-none');
           // $("#"+id+"_otext").prop('required',true);
           var contactType = <?php echo $contacttypes; ?>;
                
            contactType.map((contact, idx) => {
                if(contact.type_name == t.value){
                    <?php if(config('app.locale') == 'hr'): ?>
                        $("#"+id+"_olabel").html(contact.hr_type_name+' <?php echo e(trans("form.registration.client.type")); ?>');
                        $("#"+id+"_otext").attr("placeholder", '<?php echo e(trans("general.enter")); ?> '+contact.hr_type_name+' <?php echo e(trans("form.registration.client.type")); ?>');
                    <?php else: ?>
                    $("#"+id+"_olabel").html(contact.type_name+' <?php echo e(trans("form.registration.client.type")); ?>');
                    $("#"+id+"_otext").attr("placeholder", '<?php echo e(trans("general.enter")); ?> '+contact.type_name+' <?php echo e(trans("form.registration.client.type")); ?>');
                    <?php endif; ?>
                }
            });
           $("#"+id+"_otext").rules('add', {required: true });
           $("#"+id+"_otext").attr("required", true);
        }else{
            $("#"+id+"_div").addClass('d-none');
            //$("#"+id+"_otext").prop('required',false);
            $("#"+id+"_otext").rules('remove', 'required');
            $("#"+id+"_otext").removeAttr('required');
        }
    }
    function checkSubjectType(){
        var baseTbl = $("#contact-accordion");
        var arrRowNo = [];
        originalId = baseTbl.find('.contact_block:last').attr('id');
        if (originalId){
            arrRowNo = originalId.split("-");
        }
        if (arrRowNo.length > 1) {
            lastNo = arrRowNo[1];
        } else {
            lastNo = 0;
        }
        let subType = $("select[name='subjects[" + parseInt((lastNo)) + "][sub_type]']").val();
        
        if(subType == 'Main' && parseInt(lastNo) > 1){
            return false;
        } else {
            return true;
        }
    }
    // set the subject title on Subject Type
    function changeothersubjecttitle(t){
       var id=$(t).data("id");
       var value = t.value;
       $("#"+id+"_title").html(value);  
    }
    function changesubjecttitlebyid(id){
        var e = document.getElementById(id);
			var contry = <?php echo $contacttypes; ?>;
			contry.map(c=>{
				if(e.value == c.type_name) {
					<?php if(config('app.locale') == 'hr'): ?>
						$("#"+id+"_title").html(c.hr_type_name);
					<?php else: ?>
						$("#"+id+"_title").html(c.type_name);
					<?php endif; ?>
				}
            });
    }

    // set the subject ID
    function settheSubjectId(t){
        var id = t.id;
        //alert(id);
        var value = t.value;
        var x = document.getElementById(id);
        x.addEventListener('keypress', calccode, false);
    }
    function calcCode(val){
       var sum = 0;
        for (var i = 0; i < 8; i++) {
            var digit = parseInt(val[i]);
            if (isNaN(digit))
                return;
            var result = digit * (i % 2 == 0 ? 1 : 2);
            while (result > 0) {
                sum += result % 10;
                result = parseInt(result / 10);
            }
        }
        return  (10 - (sum % 10)) % 10; 
    }
    function calccode(e) {
        // filter out all key codes except numbers and specials
        var keynum = null;
        var code = e.keyCode || e.charCode;
        if (code > 30 && !e.ctrlKey) {
            keynum = code - 48;
            if (keynum < 0 || keynum > 9) {
                // not allowed
                e.preventDefault();
                return;
            }
        }
        if (keynum === null)
            return; // skip ctrl keys
            
        var val = this.value 
        //+ '' + keynum; // don't forget to add the key that was pressed
        if ((val.length) < 9)
            return;
        if (val.length == 9) {
            e.preventDefault(); // don't let the user type more chars
        }
        
        let index = $(this).data('index');
        let allDigit = val;
        let lastDigit = val.substr(8,9);
        val = val.substr(0,8); // verify we look only at the first 8 digits
        var code = calcCode(val);
        
        if(lastDigit == code){
            if($(this).hasClass('arr_contacts_spouses')){
                $('#id-spouses-error-'+index).addClass('d-none');
                $(this).attr('data-allow', 'yes');
            } else if($(this).hasClass('arr_contacts_passport')){
                $('#id-passport-error-'+index).addClass('d-none');
                $(this).attr('data-allow', 'yes');
            } else {
                $('#id-error-'+index).addClass('d-none');
                $(this).attr('data-allow', 'yes');
            }
        } else {
            if($(this).hasClass('arr_contacts_spouses')){
                $(this).attr('data-allow', 'no');
                $('#id-spouses-error-'+index).removeClass('d-none');
            } else if($(this).hasClass('arr_contacts_passport')){
                $(this).attr('data-allow', 'no');
                $('#id-passport-error-'+index).removeClass('d-none');
            } else {
                $(this).attr('data-allow', 'no');
                $('#id-error-'+index).removeClass('d-none');
            }
        }
        
        // this.value = val;
        e.preventDefault(); // don't let the browser add the pressed key, because we alread did
    }

    //this for add input mask to phone,mobile,fax
    function addinputmask(type,id){
        if(type=='contact'){
            $('.arr_contacts_mainphones').inputmask('999-999-9999', {
                    autoUnmask: true,
                    removeMaskOnSubmit:true
                });
                $('.arr_contacts_secondaryphones').inputmask('999-999-9999', {
                    autoUnmask: true,
                    removeMaskOnSubmit:true
                });
                $('.arr_contacts_mainmobiles').inputmask('999-999-9999', {
                    autoUnmask: true,
                    removeMaskOnSubmit:true
                });
                $('.arr_contacts_secondarymobiles').inputmask('999-999-9999', {
                    autoUnmask: true,
                    removeMaskOnSubmit:true
                });
            $('.arr_contacts_fax').inputmask('999-999-9999', {
                    autoUnmask: true,
                    removeMaskOnSubmit:true
                });
                
        }
        if(type=='phone'){
            $('.arr_other_phone').inputmask('999-999-9999', {
                autoUnmask: true,
                removeMaskOnSubmit:true
            });
        }
        if(type=='mobile'){
            $('.arr_other_mobile').inputmask('999-999-9999', {
                autoUnmask: true,
                removeMaskOnSubmit:true
            });
        }
        if(type=='fax'){
            $('.arr_other_fax').inputmask('999-999-9999', {
                autoUnmask: true,
                removeMaskOnSubmit:true
            });
        }
    }

</script>

<script type="text/javascript">

    // this code for set  google api autocomplete address dynamically add
    function setAddressnew(subNo, count)
    {
        var autocompletes = [];
        var options = {
            types: ['geocode'],
            language: '<?php echo e(App::isLocale('hr') ? 'iw' : 'en'); ?>',
        };

        var input = document.getElementById('address_complete_' + subNo + '-' +count);
        var autocomplete = new google.maps.places.Autocomplete(input, options);
        autocomplete.inputId = input.id;
        autocomplete.subNo = subNo;
        autocomplete.count = count;
        autocomplete.addListener('place_changed', fillIn);
        autocompletes.push(autocomplete);
    }

    // find the country id fron given json
    function getcountryid(cntr, value)
    {
        for (var i = 0; i < cntr.length; i++) {
            if (cntr[i].code == value) {
                return cntr[i].id;
            }
        }
        return 0;
    }

    function fillIn()
    {
        const componentForm = {
            street_number: "long_name",
            route: "long_name",
            locality: "long_name",
            administrative_area_level_1: "long_name",
            country: "short_name",
            postal_code: "short_name",
        };

        var cntr =<?php echo json_encode($countries, 15, 512) ?>;
        var place = this.getPlace();
        //  console.log('place',place);
        document.getElementById('address2_' + this.subNo + '-' + this.count).value = "";
        document.getElementById('state_' + this.subNo + '-' +this.count).value = "";
        document.getElementById('city_' + this.subNo + '-' +this.count).value = "";
        document.getElementById('zipcode_' + this.subNo + '-' +this.count).value = "";

        for (const component of place.address_components) {
            const addressType = component.types[0];
            if (componentForm[addressType]) {
                const val = component[componentForm[addressType]];
                  if (addressType === "country") {
                    var countryid = getcountryid(cntr, val);
                    
                    if (countryid != 0) {
                        document.getElementById('country_' + this.subNo + '-' + this.count).value = countryid;
                    }
                }
                if (addressType === "street_number") {
                    if (val) {
                        document.getElementById('address2_' + this.subNo + '-' + this.count).value += val + ' ';
                    }
                }
                if (addressType === "route") {
                    if (val)
                        document.getElementById('address2_' + this.subNo + '-' + this.count).value += val;
                }
                if (addressType === "administrative_area_level_1") {
                    document.getElementById('state_' + this.subNo + '-' + this.count).value = val;
                }
                if (addressType === "locality") {
                    document.getElementById('city_' + this.subNo + '-' + this.count).value = val;
                }
                if (addressType === "postal_code") {
                    document.getElementById('zipcode_' + this.subNo + '-' + this.count).value = val;
                }
            }
        }

    }

    /**
     * Hebrew translation for bootstrap-datepicker
     * Sagie Maoz <sagie@maoz.info>
     */
    ;(function($){
      $.fn.datepicker.dates['he'] = {
          days: ["ראשון", "שני", "שלישי", "רביעי", "חמישי", "שישי", "שבת", "ראשון"],
          daysShort: ["א", "ב", "ג", "ד", "ה", "ו", "ש", "א"],
          daysMin: ["א", "ב", "ג", "ד", "ה", "ו", "ש", "א"],
          months: ["ינואר", "פברואר", "מרץ", "אפריל", "מאי", "יוני", "יולי", "אוגוסט", "ספטמבר", "אוקטובר", "נובמבר", "דצמבר"],
          monthsShort: ["ינו", "פבר", "מרץ", "אפר", "מאי", "יונ", "יול", "אוג", "ספט", "אוק", "נוב", "דצמ"],
          today: "היום",
          rtl: true
      };
    }(jQuery));

    datePicker();
    <?php if(App::isLocale('hr')): ?>
        var get_lang = 'he';
    <?php else: ?>
        var get_lang = 'en';
    <?php endif; ?>

    function datePicker()
    {
        $(".datepicker").datepicker({
            autoclose:true,
            format : 'dd-mm-yyyy',
            language:get_lang
        });
    }

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/uvda/resources/views/investigation/create.blade.php ENDPATH**/ ?>