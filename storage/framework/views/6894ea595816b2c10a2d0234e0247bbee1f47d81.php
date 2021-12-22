<?php $__env->startSection('title'); ?> <?php echo e(trans('form.products_form.add_product')); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('headerCss'); ?>
    <!-- headerCss -->
    <!-- DataTables -->
    <link href="<?php echo e(URL::asset('/libs/datatables/datatables.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::asset('/libs/datatables/colReorder.dataTables.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::asset('/libs/datatables/select.dataTables.min.css')); ?>" rel="stylesheet" type="text/css" />

    <style>
        #product_doc-error{
            display: block;
        }

        #product_doc_del-error{
            display: block;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 col-sm-4 col-xs-12">
                            <h4 class="card-title"><?php echo e(trans('form.products_form.add_product')); ?></h4>
                        </div>
                    </div>
                    <?php echo $__env->make('session-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <form action="<?php echo e(route('product.store')); ?>" method="POST" id="create_product" class="form needs-validation" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                               
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="name" class="ul-form__label"><?php echo e(trans('form.products_form.product_name')); ?> <span class="text-danger">*</span> </label>
                                        <input  type="text" class="form-control" id="name" name="name" placeholder="<?php echo e(trans('form.products_form.product_name')); ?>" value="<?php echo e(old('name')); ?>"  />
                                        <?php $__errorArgs = ['name'];
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
                                    <div class="form-group col-md-12">
                                        <label for="spouse_cost" class="ul-form__label"><?php echo e(trans('form.products_form.spouse_cost')); ?> </label>
                                <select name="spouse_cost" id="spouse_cost" class="form-control " required>
                                  <option value="0">0%</option>
                                  <option value="50">50%</option>
                                  <option value="100">100%</option>
                                        
                                    </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="price" class="ul-form__label"><?php echo e(trans('form.products_form.price')); ?>(<?php echo e(trans('general.money_symbol')); ?>) <span class="text-danger">*</span></label>
                                        <input  type="number" class="form-control" id="price" name="price" placeholder="<?php echo e(trans('form.products_form.price')); ?>" value="<?php echo e(old('price')); ?>" step="0.01" required/>
                                        <?php $__errorArgs = ['price'];
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
                                    <div class="form-group col-md-12">
                                        <label for="price" class="ul-form__label"><?php echo e(trans('form.products_form.category')); ?> </label>
                                        <select name="category_id" id="category_id" class="form-control <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($category['id']); ?>" <?php echo e(old('category_id') == $category['id'] ? 'selected' : ''); ?>><?php echo e(App::isLocale('hr')?$category['hr_name']:$category['name']); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ['category_id'];
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
                                    <div class="form-group col-md-12">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="is_delivery" name="is_delivery" value="1">
                                            <label class="custom-control-label" for="is_delivery"><?php echo e(trans('form.products_form.is_del')); ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row dc-div" style="display: none">
                                    <div class="form-group col-md-12">
                                        <label for="name" class="ul-form__label"><?php echo e(trans('form.products_form.del_cost')); ?>(<?php echo e(trans('general.money_symbol')); ?>) </label>
                                        <input  type="number" class="form-control" id="delivery_cost" name="delivery_cost" placeholder="<?php echo e(trans('form.products_form.del_cost')); ?>" value="<?php echo e(old('delivery_cost')); ?>"  step="0.01" required/>
                                        <?php $__errorArgs = ['delivery_cost'];
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

                                <hr>

                                <div class="form-row">
                                    <div class="col-12 mb-2">
                                        <h4 class="card-title"><?php echo e(trans('form.products_form.upload_document') . ' ' . trans('form.products_form.for_inv')); ?></h4>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <input type="file" class="" id="product_doc" name="product_doc">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-12 mb-2">
                                        <h4 class="card-title"><?php echo e(trans('form.products_form.upload_document') . ' ' . trans('form.products_form.for_del')); ?></h4>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <input type="file" class="" id="product_doc_del" name="product_doc_del">
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="col-md-12 mb-4">
                                        <button type="submit" class="btn btn-primary" id="create-customer-btn"><?php echo e(trans('form.create')); ?></button>
                                        <a href="<?php echo e(route('product.index')); ?>" class="btn btn-outline-secondary m-1"><?php echo e(trans('form.cancel')); ?></a>
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

        $.validator.addMethod('filesize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        }, '<?php echo e(trans('form.products_form.filesize_val')); ?>');
        
        $(function() {
            $("#create_product").validate({           
                rules: { 
                    name: {
                        required: true 
                    },
                    price:{
                        required: true,
                    },
                    product_doc: {
                        filesize: 1024*1024*8,
                    },
                    product_doc_del: {
                        filesize: 1024*1024*8,
                    },
                },
                messages: 
                {
                   
                }
            });

            $('#is_delivery').on('change', function(){
                if(this.checked){
                    $('.dc-div').show();
                }else{
                    $('.dc-div').hide();
                }
            });

          });

        </script>
    <!--  datatable js -->
    <script src="<?php echo e(URL::asset('/libs/datatables/datatables.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/jszip/jszip.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/pdfmake/pdfmake.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/datatables/dataTables.colReorder.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/datatables/dataTables.select.min.js')); ?>"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/uvda/resources/views/products/create.blade.php ENDPATH**/ ?>