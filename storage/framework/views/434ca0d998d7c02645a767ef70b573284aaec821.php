<div class="tab-pane p-3" id="documents" role="tabpanel">

    <div class="row">

        <div class="col-xs-12 col-sm-12 <?php echo e($isadminsm ? 'col-md-9 col-lg-9 col-xl-9' : 'col-md-8 col-lg-8 col-xl-8'); ?> resp-order">
            <div class="table-rep-plugin">
                <div class="table-responsive mb-0" data-pattern="priority-columns">
                    <table id="inve_documents" class="table table-bordered">
                        <thead>
                        <th><?php echo e(trans('general.sr_no')); ?></th>
                        <th><?php echo e(trans('form.registration.investigation.documents')); ?></th>
                        <th><?php echo e(trans('form.registration.investigation.document_type')); ?></th>
                        <?php if($isadminsm): ?>
                        <th><?php echo e(trans('form.registration.investigation.uploaded_by')); ?></th>
                        <th><?php echo e(trans('form.registration.investigation.share_document_with')); ?></th>
                        <?php endif; ?>
                        <th><?php echo e(trans('general.action')); ?></th>
                        </thead>
                        <tbody>
                        <?php if(!$invn->documents->isEmpty()): ?>
                            <?php $indx = 1; $count = 0; ?>
                            <?php $__currentLoopData = $invn->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $isPaid = ((isClient()) && ($document->document_typeid == 1) && (!empty($invoice)) && ($document->uploadedby->user_type->type_name == env('USER_TYPE_ADMIN') || $document->uploadedby->user_type->type_name == env('USER_TYPE_STATION_MANAGER')));
                                $viewable = isAdmin() || (isSM()) || (Auth::id() == $document->uploaded_by)
                                    || (isClient() && $document->share_to_client == 1)
                                    || (isInvestigator() && $document->share_to_investigator == 1)
                                    || (isDeliveryboy() && $document->share_to_delivery_boy == 1);
                                ?>
                                <?php if($viewable || $isPaid): ?>
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
                                    <td><a href="javascript:void(0);"><?php echo e($document->doc_name); ?></a></td>
                                    <?php if(App::isLocale('hr')): ?>
                                        <td><a href="javascript:void(0);"><?php echo e(($document->document_typeid!=0)?$document->documenttype->hr_name :"-"); ?></a></td>
                                    <?php else: ?>
                                        <td><a href="javascript:void(0);"><?php echo e(($document->document_typeid!=0)?$document->documenttype->name :"-"); ?></a></td>
                                    <?php endif; ?>
                                    <?php if($isadminsm): ?>
                                    <td><?php echo e($document->uploadedBy->name); ?> (<?php echo e(App::isLocale('hr')?$document->uploadedBy->user_type->hr_type_name:$document->uploadedBy->user_type->type_name); ?>)</td>
                                    <td>
                                        <?php if(Auth::id() == $document->uploaded_by || (isAdmin() || isSM())): ?>
                                            <div class="form-inline form-group justify-content-between">
                                                <label for="share_with_client_<?php echo e($document->id); ?>"
                                                       class="form-check-label"> <?php echo e(trans('form.client_reg')); ?></label>
                                                <input name="share_client[]" id="share_with_client_<?php echo e($document->id); ?>" type="checkbox"
                                                       switch="bool" <?php echo e($document->share_to_client == 1 ? 'checked' : ''); ?>

                                                       onclick="changeShareSetting('<?php echo e($document->id); ?>', 'share_to_client', this)">
                                                <label class="ml-2" for="share_with_client_<?php echo e($document->id); ?>" data-on-label="<?php echo e(trans('general.yes')); ?>"
                                                       data-off-label="<?php echo e(trans('general.no')); ?>"> </label>

                                            </div>
                                            <div class="form-inline form-group justify-content-between">
                                                <label for="share_with_investigator_<?php echo e($document->id); ?>"
                                                       class="form-check-label"> <?php echo e(trans('form.investigator_reg')); ?></label>
                                                <input name="share_investigator[]" id="share_with_investigator_<?php echo e($document->id); ?>"
                                                       type="checkbox" switch="bool" <?php echo e($document->share_to_investigator == 1 ? 'checked' : ''); ?>

                                                       onclick="changeShareSetting('<?php echo e($document->id); ?>', 'share_to_investigator', this)"/>
                                                <label class="ml-2" for="share_with_investigator_<?php echo e($document->id); ?>" data-on-label="<?php echo e(trans('general.yes')); ?>"
                                                       data-off-label="<?php echo e(trans('general.no')); ?>"> </label>
                                            </div>
                                            <div class="form-inline form-group justify-content-between">
                                                <label for="share_with_delivery_boy_<?php echo e($document->id); ?>"
                                                       class="form-check-label"> <?php echo e(trans('form.delivery_boy_reg')); ?></label>
                                                <input name="share_delivery_boy[]" id="share_with_delivery_boy_<?php echo e($document->id); ?>"
                                                       type="checkbox" switch="bool" <?php echo e($document->share_to_delivery_boy == 1 ? 'checked' : ''); ?>

                                                       onclick="changeShareSetting('<?php echo e($document->id); ?>', 'share_to_delivery_boy', this)"/>
                                                <label class="ml-2" for="share_with_delivery_boy_<?php echo e($document->id); ?>" data-on-label="<?php echo e(trans('general.yes')); ?>"
                                                       data-off-label="<?php echo e(trans('general.no')); ?>"> </label>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <?php endif; ?>
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
                                            
                                            <?php if($document->uploaded_by == Auth::id()): ?>
                                            <a class="delete" href="javascript:void(0);" onclick="deleteDocument('<?php echo e($document->id); ?>', this)">
                                                <i class="fas fa-trash"></i></a>
                                            <?php endif; ?>
                                        </div>

                                    </td>
                                </tr>
                                <?php $indx++; ?>
                                <?php endif; ?>
                                
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        </tbody>
                        <tfoot>
                        <tr class="tr-nodoc" style="<?php echo e(!$invn->documents->isEmpty() && $count > 0 ? 'display:none;' : ''); ?>">
                            <td colspan="<?php echo e($isadminsm ? 6 : 4); ?>" class="text-center"><?php echo e(trans('form.registration.investigation.document_notfound')); ?></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 <?php echo e($isadminsm ? 'col-md-3 col-lg-3 col-xl-3' : 'col-md-4 col-lg-4 col-xl-4'); ?>">
            <form method="POST" class="dropzone" id="fileupload"
                  action="<?php echo e(route('investigation.upload-document')); ?>"
                  enctype="multipart/form-data">

                <?php echo csrf_field(); ?>
                <input type="hidden" value="<?php echo e(Auth::id()); ?>" name="uploaded_by" />
                <input type="hidden" value="<?php echo e($invn->id); ?>" name="investigation_id" />

                <div class="fallback">
                    <input name="file" type="file" multiple="multiple" class="form-control" />
                </div>
                <div class="dz-message">
                    <h4><?php echo e(trans('form.registration.investigation.document_dropfile')); ?></h4>
                </div>

            </form>
            <div id="doc_model" class="modal fade bs-example-modal-center" tabindex="-1"
                         role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0"><?php echo e(trans('form.registration.investigation.upload_documents')); ?></h5>
                                    <button  id="closmod" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                   
                                    <form id="docmodelform">
                                    <table id="inve_documents2" class="table table-bordered">
                                        <thead>
                                            <th><?php echo e(trans('form.registration.investigation.document_type')); ?></th>
                                            <th><?php echo e(trans('form.registration.investigation.file_title')); ?></th>
                                            <th><?php echo e(trans('form.registration.investigation.file_name')); ?></th>
                                            <th><?php echo e(trans('general.action')); ?></th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    <div class="form-group text-center">
                                        <button type="button" id="uploadfile" class="uploadfile btn btn-primary w-md waves-effect waves-light addbtn"><?php echo e(trans('general.uploadfiles')); ?></button>
                                        <button type="button" id="closmod2" data-dismiss="modal" aria-hidden="true" class="closmod btn btn-secondary w-md waves-effect waves-light" ><?php echo e(trans('general.cancel')); ?></button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                    </div>
        </div>
    </div>
</div><?php /**PATH /var/www/html/uvda/resources/views/investigation/partials/document.blade.php ENDPATH**/ ?>