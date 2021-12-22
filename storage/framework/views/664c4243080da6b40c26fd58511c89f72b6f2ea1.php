<?php $__env->startSection('title'); ?> Title Details <?php $__env->stopSection(); ?>

<?php $__env->startSection('headerCss'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="col-sm-6">
        <div class="page-title-box">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="<?php echo e(route('titles')); ?>">Titles</a></li>
                <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo e($title->name ?? '-'); ?>

                    <?php if($title->isadminconfirmed == 1): ?>
                        <span class="badge dt-badge badge-success"><?php echo e(ucwords('Approved')); ?></span>   
                    <?php else: ?>
                        <span class="badge dt-badge badge-warning"><?php echo e(ucwords('Pending')); ?></span> 
                    <?php endif; ?>   
               </a></li>
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
                                        <span class="d-none d-sm-block">Title Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#contributors" role="tab">
                                        <span class="d-block d-sm-none"><i class="far fa-address-book"></i></span>
                                        <span class="d-none d-sm-block">Contributors</span>
                                    </a>
                                </li>
                                
                            </ul>
    
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <!-- BASIC DETAILS -->
                                <div class="tab-pane active py-3" id="basic_details" role="tabpanel">
                                    <h4 class="section-title mb-3 pb-2">Title Details</h4>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4"><?php echo e(trans('form.registration.client.viewform.name')); ?></label>
                                                <div class="col-8">
                                                    <?php echo e($title->name ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-4">Created By</label>
                                                <div class="col-8">
                                                    <?php echo e($title->user->name); ?>

                                                </div>
                                            </div>
                                        </div>
                                       
                                    </div>
                                                                        
                                    <hr />
                                    <h4 class="section-title mb-3 pb-2">Categories</h4>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                              <div>
                                                
                                                    <?php $__currentLoopData = $title->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                                    <?php if($category->name || $category->hr_name): ?>   
                                                    <span class="badge dt-badge badge-primary">
                                                    <?php if(config('app.locale') == 'hr'): ?>
                                                        <?php echo e($category->hr_name ?? ''); ?>

                                                    <?php else: ?>
                                                        <?php echo e($category->name ?? ''); ?>

                                                    <?php endif; ?>
                                                    </span>
                                                    <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>   
                                                </div>      
                                        </div>
                                    </div>   

                                    <hr>
                                    
                                    <h4 class="section-title mb-3 pb-2">Music Files</h4>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 resp-order">
                                            <div class="table-rep-plugin">
                                                <div class="table-responsive mb-0" data-pattern="priority-columns">
                                                    <table id="inve_documents" class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                
                                                                <th>Download Files</th>                                                         
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if(count($title->files)>0): ?>
                                                            <?php $i = 1; ?>
                                                            <?php $__currentLoopData = $title->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                            <tr>
                                                                
                                                                <td><a href="<?php echo e(asset($file->file_path)); ?>" target="_blank">
                                                                    <button class="btn"><i class="fa fa-download"></i> Download File</button>
                                                                </a></td>
                                                            </tr>
                                                            <?php $i++; ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php else: ?>
                                                            <tr>
                                                                <td class="text-center" colspan="7">
                                                                   No Records Added
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
    
                              
                            
                                <!-- owners -->
                                <div class="tab-pane p-3" id="contributors" role="tabpanel">
                                    <h4 class="section-title mb-3 pb-2">Owner Details</h4>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 resp-order">
                                            <div class="table-rep-plugin">
                                                <div class="table-responsive mb-0" data-pattern="priority-columns">
                                                    <table id="inve_documents" class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th><?php echo e(trans('general.sr_no')); ?></th>
                                                                <th>First Name</th>
                                                                <th>Last Name</th>
                                                                <th>Email</th>
                                                                <th>Types</th>
                                                              
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if(count($title->owner)>0): ?>
                                                            <?php $i = 1; ?>
                                                            <?php $__currentLoopData = $title->owner; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contributor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <td><?php echo e($i); ?></td>
                                                                <td><?php echo e($contributor->first_name ?? '-'); ?></td>
                                                                <td><?php echo e($contributor->last_name ?? '-'); ?></td>
                                                                <td><?php echo e($contributor->email ?? '-'); ?></td>
                                                                
                                                                <td>

                                                                    <?php $__currentLoopData = $contributor->types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                                                    <?php if($type->type): ?>   
                                                                    <span class="badge dt-badge badge-primary">
                                                                   
                                                                        <?php echo e($type->type ?? ''); ?>

                                                                   
                                                                    </span>
                                                                    <?php endif; ?>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  
                                                                </td>
                                                            </tr>
                                                            <?php $i++; ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php else: ?>
                                                            <tr>
                                                                <td class="text-center" colspan="7">
                                                                   No Records Added
                                                                </td>
                                                            </tr>
                                                            <?php endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                       
                                    </div>
                                    <hr>
                                    <h4 class="section-title mb-3 pb-2">Contributor Details</h4>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 resp-order">
                                            <div class="table-rep-plugin">
                                                <div class="table-responsive mb-0" data-pattern="priority-columns">
                                                    <table id="inve_documents" class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th><?php echo e(trans('general.sr_no')); ?></th>
                                                                <th>First Name</th>
                                                                <th>Last Name</th>
                                                                <th>Email</th>
                                                                <th>Types</th>
                                                              
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if(count($title->contributors)>0): ?>
                                                            <?php $i = 1; ?>
                                                            <?php $__currentLoopData = $title->contributors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contributor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <td><?php echo e($i); ?></td>
                                                                <td><?php echo e($contributor->first_name ?? '-'); ?></td>
                                                                <td><?php echo e($contributor->last_name ?? '-'); ?></td>
                                                                <td><?php echo e($contributor->email ?? '-'); ?></td>
                                                                
                                                                <td>

                                                                    <?php $__currentLoopData = $contributor->types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                                                    <?php if($type->type): ?>   
                                                                    <span class="badge dt-badge badge-primary">
                                                                   
                                                                        <?php echo e($type->type ?? ''); ?>

                                                                   
                                                                    </span>
                                                                    <?php endif; ?>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  
                                                                </td>
                                                            </tr>
                                                            <?php $i++; ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php else: ?>
                                                            <tr>
                                                                <td class="text-center" colspan="7">
                                                                   No Records Added
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
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/uvda/resources/views/titles/show.blade.php ENDPATH**/ ?>