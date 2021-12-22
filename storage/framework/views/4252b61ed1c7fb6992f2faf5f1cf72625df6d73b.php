<?php $__env->startSection('title'); ?> <?php echo e(trans('form.registration.investigator.viewform.investigators_details')); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('headerCss'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="col-sm-6">
        <div class="page-title-box">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="<?php echo e(route('investigators')); ?>"><?php echo e(trans('form.registration.investigator.viewform.investigators')); ?></a></li>
                <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo e($user->name ?? '-'); ?> <?php if($user->status): ?>
                    <?php if($user->status == 'approved'): ?>
                        <span class="badge dt-badge badge-success"><?php echo e(trans('form.timeline_status.'.ucwords($user->status))); ?></span>   
                    <?php elseif($user->status == 'pending'): ?>
                        <span class="badge dt-badge badge-warning"><?php echo e(trans('form.timeline_status.'.ucwords($user->status))); ?></span> 
                    <?php elseif($user->status == 'disabled'): ?> 
                            <span class="badge dt-badge badge-dark"><?php echo e(trans('form.timeline_status.'.ucwords($user->status))); ?></span> 
                    <?php endif; ?>   
                <?php endif; ?></a></li>
            </ol>
        </div>
    </div>
    <!-- content -->
    <div class="row">
        <div class="col-12">
            <div class="card investogators_Details">
                <div class="card-body">
                    
    
                    <div class="row">
                        <div class="col-12">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-2" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#basic_details" role="tab">
                                        <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                        <span class="d-none d-sm-block"><?php echo e(trans('form.registration.client.basic_details')); ?></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#addresses" role="tab">
                                        <span class="d-block d-sm-none"><i class="far fa-address-book"></i></span>
                                        <span class="d-none d-sm-block"><?php echo e(trans('form.registration.client.address')); ?></span>
                                    </a>
                                </li>
                                
                            </ul>
    
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <!-- BASIC DETAILS -->
                                <div class="tab-pane active py-3" id="basic_details" role="tabpanel">
                                    <h4 class="section-title mb-3 pb-2"><?php echo e(trans('form.registration.client.personal_details')); ?></h4>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4"><?php echo e(trans('form.registration.client.viewform.name')); ?></label>
                                                <div class="col-8">
                                                    <?php echo e($user->name ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4"><?php echo e(trans('form.registration.client.viewform.email')); ?></label>
                                                <div class="col-8">
                                                    <?php echo e($user->email ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4"><?php echo e(trans('form.registration.investigator.viewform.family')); ?></label>
                                                <div class="col-8">
                                                    <?php echo e($user->investigator->family ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4"><?php echo e(trans('form.registration.investigator.viewform.id_number')); ?></label>
                                                <div class="col-8">
                                                    <?php echo e($user->investigator->idnumber ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4"><?php echo e(trans('form.registration.investigator.viewform.date_of_birth')); ?></label>
                                                <div class="col-8">
                                                    <?php echo e(date('d M Y', strtotime($user->investigator->dob)) ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4"><?php echo e(trans('form.registration.client.viewform.website')); ?></label>
                                                <div class="col-8">
                                                    <?php echo e($user->investigator->website ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <hr />
                                    <h4 class="section-title mb-3 pb-2"><?php echo e(trans('form.registration.investigator.viewform.area_of_specialization')); ?></h4>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                              <div>
                                                    <?php $__currentLoopData = $user->investigatorspecilizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $specialize): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                                    <?php if($specialize->specializations->name || $specialize->specializations->hr_name): ?>   
                                                    <span class="badge dt-badge badge-primary">
                                                    <?php if(config('app.locale') == 'hr'): ?>
                                                        <?php echo e($specialize->specializations->hr_name ?? ''); ?>

                                                    <?php else: ?>
                                                        <?php echo e($specialize->specializations->name ?? ''); ?>

                                                    <?php endif; ?>
                                                    </span>
                                                    <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>   
                                                </div>      
                                        </div>
                                    </div>   
                                    <hr />
                                    <h4 class="section-title mb-3 pb-2"><?php echo e(trans('form.registration.client.additional_details')); ?></h4>
                                    <div class="row">
                                        
                
                                        <div class="col-lg-3">
                                            <div class="card">
                                                <div class="card-header">
                                                    <i class="fas fa-blender-phone"></i>   <?php echo e(trans('form.registration.client.phone')); ?>

                                                </div>
                                                <div class="card-body">
                                                    <blockquote class="card-blockquote mb-0">
                                                        <?php $__currentLoopData = $user->userPhones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $phone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if($phone->type=='phone'): ?>
                                                            <p> <?php echo e($phone->value ?? ''); ?> <?php if($phone->phone_type!=''): ?><span class="badge badge-primary">
                                                                <?php if(config('app.locale') == 'hr'): ?>
                                                                <?php echo e(!is_null(\App\ContactTypes::where('type_name', $phone->phone_type)->first())?\App\ContactTypes::where('type_name', $phone->phone_type)->first()->hr_type_name:(!is_null($phone->phone_type)?$phone->phone_type:'')); ?>

                                                                <?php else: ?>
                                                                <?php echo e(!is_null(\App\ContactTypes::where('type_name', $phone->phone_type)->first())?\App\ContactTypes::where('type_name', $phone->phone_type)->first()->type_name:(!is_null($phone->phone_type)?$phone->phone_type:'')); ?>

                                                                <?php endif; ?>
                                                            </span>
                                                            <?php endif; ?>
                                                        </p>   
                                                        <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
                                                    </blockquote>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="card">
                                                <div class="card-header">
                                                    <i class="fas fa-phone-volume"></i>  <?php echo e(trans('form.registration.client.mobile')); ?>

                                                </div>
                                                <div class="card-body">
                                                    <blockquote class="card-blockquote mb-0">
                                                        <?php $__currentLoopData = $user->userPhones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mobile): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($mobile->type=='mobile'): ?>
                                                        <p> <?php echo e($mobile->value ?? ''); ?> <?php if($mobile->phone_type!=''): ?><span class="badge badge-primary">
                                                            <?php if(config('app.locale') == 'hr'): ?>
                                                            <?php echo e(!is_null(\App\ContactTypes::where('type_name', $mobile->phone_type)->first())?\App\ContactTypes::where('type_name', $mobile->phone_type)->first()->hr_type_name:(!is_null($mobile->phone_type)?$mobile->phone_type:'')); ?>

                                                            <?php else: ?>
                                                            <?php echo e(!is_null(\App\ContactTypes::where('type_name', $mobile->phone_type)->first())?\App\ContactTypes::where('type_name', $mobile->phone_type)->first()->type_name:(!is_null($mobile->phone_type)?$mobile->phone_type:'')); ?>

                                                            <?php endif; ?></span><?php endif; ?>
                                                        </p>   
                                                        <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  
                                                    </blockquote>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="card">
                                                <div class="card-header">
                                                    <i class="fas fa-mail-bulk"></i>  <?php echo e(trans('form.registration.client.email')); ?>

                                                </div>
                                                <div class="card-body">
                                                    <blockquote class="card-blockquote mb-0">
                                                        <?php $__currentLoopData = $user->userEmails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $email): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <p> <?php echo e($email->value ?? ''); ?> <?php if($email->email_type!=''): ?><span class="badge badge-primary">
                                                            <?php if(config('app.locale') == 'hr'): ?>
                                                            <?php echo e(!is_null(\App\ContactTypes::where('type_name', $email->email_type)->first())?\App\ContactTypes::where('type_name', $email->email_type)->first()->hr_type_name:(!is_null($email->email_type)?$email->email_type:'')); ?>

                                                            <?php else: ?>
                                                            <?php echo e(!is_null(\App\ContactTypes::where('type_name', $email->email_type)->first())?\App\ContactTypes::where('type_name', $email->email_type)->first()->type_name:(!is_null($email->email_type)?$email->email_type:'')); ?>

                                                            <?php endif; ?>
                                                        </span><?php endif; ?>
                                                        </p>  
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  
                                                    </blockquote>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="card">
                                                <div class="card-header">
                                                    <i class="fas fa-fax "></i> <?php echo e(trans('form.registration.client.fax')); ?>

                                                </div>
                                                <div class="card-body">
                                                    <blockquote class="card-blockquote mb-0">
                                                        <?php $__currentLoopData = $user->userPhones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fax): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($fax->type=='fax'): ?>
                                                        <p> <?php echo e($fax->value ?? ''); ?> <?php if($fax->phone_type!=''): ?><span class="badge badge-primary">
                                                            <?php if(config('app.locale') == 'hr'): ?>
                                                            <?php echo e(!is_null(\App\ContactTypes::where('type_name', $fax->phone_type)->first())?\App\ContactTypes::where('type_name', $fax->phone_type)->first()->hr_type_name:(!is_null($fax->phone_type)?$fax->phone_type:'')); ?>

                                                            <?php else: ?>
                                                            <?php echo e(!is_null(\App\ContactTypes::where('type_name', $fax->phone_type)->first())?\App\ContactTypes::where('type_name', $fax->phone_type)->first()->type_name:(!is_null($fax->phone_type)?$fax->phone_type:'')); ?>

                                                            <?php endif; ?>
                                                        </span><?php endif; ?>
                                                        </p>   
                                                        <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>    
                                                    </blockquote>
                                                </div>
                                            </div>
                                        </div>
                
                                        
                                    </div>
                                    <hr/>
                                    <h4 class="section-title mb-3 pb-2"><?php echo e(trans('form.registration.investigator.bank_details')); ?></h4>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4"><?php echo e(trans('form.registration.investigator.viewform.company')); ?></label>
                                                <div class="col-8">
                                                    <?php echo e($user->investigator->bank_details->company ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4"><?php echo e(trans('form.registration.investigator.viewform.name_of_bank')); ?></label>
                                                <div class="col-8">
                                                    <?php echo e($user->investigator->bank_details->name ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                          
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4"><?php echo e(trans('form.registration.investigator.viewform.bank_number')); ?></label>
                                                <div class="col-8">
                                                    <?php echo e($user->investigator->bank_details->number ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4"><?php echo e(trans('form.registration.investigator.viewform.branch_name')); ?></label>
                                                <div class="col-8">
                                                    <?php echo e($user->investigator->bank_details->branch_name ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                         
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4"><?php echo e(trans('form.registration.investigator.viewform.branch_no')); ?></label>
                                                <div class="col-8">
                                                    <?php echo e($user->investigator->bank_details->branch_number ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div> 
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4"><?php echo e(trans('form.registration.investigator.viewform.account_no')); ?></label>
                                                <div class="col-8">
                                                    <?php echo e($user->investigator->bank_details->account_no ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                </div>
    
                                <!-- ADDRESS -->
                                <div class="tab-pane p-3" id="addresses" role="tabpanel">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 resp-order">
                                            <div class="table-rep-plugin">
                                                <div class="table-responsive mb-0" data-pattern="priority-columns">
                                                    <table id="inve_documents" class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th><?php echo e(trans('general.sr_no')); ?></th>
                                                                <th><?php echo e(trans('form.registration.client.address_helper')); ?></th>
                                                                <th><?php echo e(trans('form.registration.client.city')); ?></th>
                                                                <th><?php echo e(trans('form.registration.client.state')); ?></th>
                                                                <th><?php echo e(trans('form.registration.client.country')); ?></th>
                                                                <th><?php echo e(trans('form.registration.client.zip_code')); ?></th>
                                                                <th><?php echo e(trans('form.registration.client.type')); ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if(count($user->userAddresses)>0): ?>
                                                            <?php $i = 1; ?>
                                                            <?php $__currentLoopData = $user->userAddresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $useraddress): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <td><?php echo e($i); ?></td>
                                                                <td><?php echo e($useraddress->address2 ?? '-'); ?></td>
                                                                <td><?php echo e($useraddress->city ?? '-'); ?></td>
                                                                <td><?php echo e($useraddress->state ?? '-'); ?></td>
                                                                <td><?php echo e(App::isLocale('hr')?$useraddress->country->hr_name ?? '-':$useraddress->country->en_name ?? '-'); ?> </td>
                                                                <td><?php echo e($useraddress->zipcode ?? '-'); ?></td>
                                                                <td>
                                                                <?php if(config('app.locale') == 'hr'): ?>
                                                                <?php echo e(!is_null(\App\ContactTypes::where('type_name', $useraddress->address_type)->first())?\App\ContactTypes::where('type_name', $useraddress->address_type)->first()->hr_type_name:(!is_null($useraddress->address_type)?$useraddress->address_type:'')); ?>

                                                                <?php else: ?>
                                                                <?php echo e(!is_null(\App\ContactTypes::where('type_name', $useraddress->address_type)->first())?\App\ContactTypes::where('type_name', $useraddress->address_type)->first()->type_name:(!is_null($useraddress->address_type)?$useraddress->address_type:'')); ?>

                                                                <?php endif; ?></td>
                                                            </tr>
                                                            <?php $i++; ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php else: ?>
                                                            <tr>
                                                                <td class="text-center" colspan="7">
                                                                   <?php echo e(trans('form.registration.deliveryboy.no_record_added')); ?>

                                                                </td>
                                                            </tr>
                                                            <?php endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>
    
                         
                            </div>
    
    
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
   <script src="<?php echo e(URL::asset('/libs/select2/select2.min.js')); ?>"></script>
   <script src="<?php echo e(URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')); ?>"></script>  
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/uvda/resources/views/investigators/show.blade.php ENDPATH**/ ?>