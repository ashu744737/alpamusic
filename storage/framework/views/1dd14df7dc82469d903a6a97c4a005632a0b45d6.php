<?php $__env->startSection('title'); ?> <?php echo e(trans('form.registration.investigation.subject_details')); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('headerCss'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="col-sm-6">
        <div class="page-title-box">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="<?php echo e(route('subjects')); ?>"><?php echo e(trans('form.registration.investigation.subjects')); ?></a></li>
                <li class="breadcrumb-item">
                    <a href="javascript: void(0);">
                    <?php if(in_array($subjects->sub_type, array('Main', 'Spouse', 'Company'))): ?>
                        <?php if(config('app.locale') == 'hr'): ?>
                        <?php echo e(!is_null(\App\SubjectTypes::where('name', $subjects->sub_type)->first())?\App\SubjectTypes::where('name', $subjects->sub_type)->first()->hr_name:(!is_null($subjects->sub_type)?$subjects->sub_type:'')); ?>

                        <?php else: ?>
                        <?php echo e(!is_null(\App\SubjectTypes::where('name', $subjects->sub_type)->first())?\App\SubjectTypes::where('name', $subjects->sub_type)->first()->name:(!is_null($subjects->sub_type)?$subjects->sub_type:'')); ?>

                        <?php endif; ?>
                    <?php else: ?>
                        <?php echo e($subjects->sub_type); ?>

                    <?php endif; ?>
                    </a>
                </li>
            </ol>
        </div>
    </div>
    
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
                            </ul>
    
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <!-- BASIC DETAILS -->
                                <div class="tab-pane active py-3" id="basic_details" role="tabpanel">

                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.family')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e($subjects->family_name ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.firstname')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e($subjects->first_name ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.investigation_req')); ?></label>
                                                <div class="col-form-label col-8">
                                                <?php if(!is_null($subjects->investigation)): ?>
                                                    <?php echo e($subjects->investigation->first()->work_order_number ?? '-'); ?>(<?php echo e($subjects->investigation->first()->user_inquiry); ?>)
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.sub_type')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php if(in_array($subjects->sub_type, array('Main', 'Spouse', 'Company'))): ?>
                                                        <?php if(config('app.locale') == 'hr'): ?>
                                                        <?php echo e(!is_null(\App\SubjectTypes::where('name', $subjects->sub_type)->first())?\App\SubjectTypes::where('name', $subjects->sub_type)->first()->hr_name:(!is_null($subjects->sub_type)?$subjects->sub_type:'')); ?>

                                                        <?php else: ?>
                                                        <?php echo e(!is_null(\App\SubjectTypes::where('name', $subjects->sub_type)->first())?\App\SubjectTypes::where('name', $subjects->sub_type)->first()->name:(!is_null($subjects->sub_type)?$subjects->sub_type:'')); ?>

                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <?php echo e($subjects->sub_type); ?>

                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.id')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e($subjects->id_number ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.account_no')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e($subjects->account_no ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.bank_ac_no')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e($subjects->bank_account_no ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.workplace')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e($subjects->workplace ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.website')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e($subjects->website ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.father_name')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e($subjects->father_name ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.mothername')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e($subjects->mother_name ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>  
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.spousename')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e($subjects->spouse_name ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.required_cost_invs')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e(trans('general.money_symbol')); ?><?php echo e($subjects->req_inv_cost ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.spouse')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e($subjects->spouse ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.carnumber')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e($subjects->car_number ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>  
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.passport')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e($subjects->passport ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigator.date_of_birth')); ?></label>
                                                <div class="col-form-label col-8">
                                                <?php if(!is_null($subjects->dob)): ?>
                                                    <?php echo e(\Carbon\Carbon::parse($subjects->dob)->format('d/m/y') ?? '-'); ?>

                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                         
                                    </div>
                                    <hr />
                                    <h4 class="section-title mb-3 pb-2"><?php echo e(trans('form.registration.investigation.contact_details')); ?></h4>
                                 
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.main_email')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e($subjects->main_email ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.alternate_email')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e($subjects->alternate_email ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.main_phone')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e($subjects->main_phone ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.secondary_phone')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e($subjects->secondary_phone ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.main_mobile')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e($subjects->main_mobile ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.secondary_mobile')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e($subjects->secondary_mobile ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.fax')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e($subjects->fax ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                       
                                    <hr />
                                    <h4 class="section-title mb-3 pb-2"><?php echo e(trans('form.registration.investigation.address_details')); ?></h4>
                                    <div class="row">
                                        <?php if(count($subjects->subject_addresses)>0): ?>
                                        <?php $i = 1; ?>
                                        <?php $__currentLoopData = $subjects->subject_addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $useraddress): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-lg-3">
                                            <div class="card">
                                                <div class="card-header">
                                                    <i class="fas fa-map"></i>   <?php echo e(trans('form.registration.client.address')); ?> <?php echo e($i); ?>      
                                                    
                                                </div>
                                                <div class="card-body">
                                                    <blockquote class="card-blockquote mb-0">
                                                              <p><?php echo e($useraddress->address2 ?? '-'); ?><br><?php echo e($useraddress->city.',' ?? '-'); ?><?php echo e($useraddress->state.',' ?? '-'); ?><?php echo e(App::isLocale('hr')?$useraddress->country->hr_name ?? '-':$useraddress->country->en_name ?? '-'); ?> <?php echo e('-'.$useraddress->zipcode ?? '-'); ?>

                                                            
                                                            </p> 
                                                            <?php if($useraddress->address_type!=''): ?><p><span class="badge badge-primary text-right">
                                                            <?php if(config('app.locale') == 'hr'): ?>
                                                                <?php echo e(!is_null(\App\ContactTypes::where('type_name', $useraddress->address_type)->first())?\App\ContactTypes::where('type_name', $useraddress->address_type)->first()->hr_type_name:(!is_null($useraddress->address_type)?$useraddress->address_type:'')); ?>

                                                                <?php else: ?>
                                                                <?php echo e(!is_null(\App\ContactTypes::where('type_name', $useraddress->address_type)->first())?\App\ContactTypes::where('type_name', $useraddress->address_type)->first()->type_name:(!is_null($useraddress->address_type)?$useraddress->address_type:'')); ?>

                                                                <?php endif; ?></span></p><?php endif; ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 
                                                    </blockquote>
                                                </div>
                                                <div class="card-footer text-muted">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <?php $i++; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                        <div class="col-lg-3">
                                            <div class="card">
                                                <div class="card-header">
                                               <?php echo e(trans('form.registration.deliveryboy.no_record_added')); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>                                       
                                        
                                        
                                    </div>
                                  
                                    <hr />
                                    
                                    <h4 class="section-title mb-3 pb-2"><?php echo e(trans('form.registration.investigation.documents')); ?></h4>
                                    <div class="row">
                                    <div class="col-lg-12 resp-order">
                                    <?php if(count($subjects->documents)>0): ?>
                                        <?php $i = 1; ?>
                                        <div class="table-rep-plugin">
                                            <div class="table-responsive mb-0" data-pattern="priority-columns">
                                                <table id="inve_documents" class="table table-bordered">
                                                    <thead>
                                                    <th><?php echo e(trans('general.sr_no')); ?></th>
                                                    <th><?php echo e(trans('form.registration.investigation.documents')); ?></th>
                                                    <th><?php echo e(trans('form.registration.investigation.comment')); ?></th>
                                                    <th><?php echo e(trans('form.registration.investigation.date')); ?></th>
                                                    <th><?php echo e(trans('form.registration.investigation.is_delivered')); ?></th>
                                                    <th><?php echo e(trans('general.action')); ?></th>
                                                    </thead>
                                                    <tbody>
                                                    <?php if(!$subjects->documents->isEmpty()): ?>
                                                        <?php $indx = 1; $count = 0; ?>
                                                        <?php $__currentLoopData = $subjects->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <?php $isimage=0;
                                                                $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp'];
                                                                if(in_array($document->file_extension, $imageExtensions)){
                                                                    $isimage=1;
                                                                }
                                                                $imgurl='/investigation-documents/'.$document->file_name;
                                                                $count++;
                                                                ?>
                                                                <td><?php echo e($indx); ?></td>
                                                                <td><a href="javascript:void(0);"><?php echo e($document->file_name); ?></a></td>
                                                                <td><?php echo e($document->comment); ?></td>
                                                                <td><?php if(!is_null($document->attach_date)): ?>
                                                                    <?php echo e(\Carbon\Carbon::parse($document->attach_date)->format('d/m/y') ?? '-'); ?>

                                                                <?php else: ?>
                                                                    -
                                                                <?php endif; ?></td>
                                                                <td><span class="badge dt-badge badge-<?php echo e($document->is_delivered?'success':'danger'); ?>"><?php echo e($document->is_delivered?trans('general.yes'):trans('general.no')); ?></span></td>
                                                                <td>
                                                                    <div class="action_btns">
                                                                        <?php if($isimage==1): ?>
                                                                            <a class="view image-popup-no-margins" href="<?php echo e(URL::asset($imgurl)); ?>" target="_blank">
                                                                                <i class="fas fa-eye"></i>
                                                                                <img class="img-fluid d-none" alt="" src="<?php echo e(URL::asset($imgurl)); ?>" width="75">
                                                                            </a>
                                                                        <?php else: ?>
                                                                            <a class="view" href="<?php echo e(URL::asset($imgurl)); ?>" target="_blank"><i class="fas fa-eye"></i>
                                                                            </a>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php $indx++; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                    </tbody>
                                                    <tfoot>
                                                    <tr class="tr-nodoc" style="<?php echo e(!$subjects->documents->isEmpty() && $count > 0 ? 'display:none;' : ''); ?>">
                                                        <td colspan="6" class="text-center"><?php echo e(trans('form.registration.investigation.document_notfound')); ?></td>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                            <?php echo e(trans('form.registration.investigation.document_notfound')); ?>

                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>                                       
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
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/uvda/resources/views/subjects/show.blade.php ENDPATH**/ ?>