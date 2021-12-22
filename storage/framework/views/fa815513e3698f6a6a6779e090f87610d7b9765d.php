<div class="tab-pane <?php echo e($indeltabactive); ?> p-3" id="subjects" role="tabpanel">
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="table-rep-plugin">
                <div class="table-responsive mb-0" data-pattern="priority-columns">
                    <table id="inve_subjects" class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 8%"><?php echo e(trans('form.registration.investigation.family')); ?></th>
                            <th style="width: 8%"><?php echo e(trans('form.registration.investigation.firstname')); ?></th>
                            <th style="width: 8%"><?php echo e(trans('form.registration.investigation.sub_type')); ?></th>
                            <th style="width: 8%"><?php echo e(trans('form.registration.investigation.id')); ?></th>
                            <th style="width: 8%"><?php echo e(trans('form.registration.investigation.bank_ac_no')); ?></th>
                            <th style="width: 8%"><?php echo e(trans('form.registration.investigation.account_no')); ?></th>
                            <th style="width: 8%"><?php echo e(trans('form.registration.investigation.workplace')); ?></th>
                            <th style="width: 8%"><?php echo e(trans('form.registration.investigation.website')); ?></th>
                            <th style="width: 8%"><?php echo e(trans('form.registration.investigation.father_name')); ?></th>
                            <th style="width: 8%"><?php echo e(trans('form.registration.investigation.mothername')); ?></th>
                            <th style="width: 8%"><?php echo e(trans('form.registration.investigation.spousename')); ?></th>
                            <th style="width: 8%"><?php echo e(trans('form.registration.investigation.spouse')); ?></th>
                            <th style="width: 8%"><?php echo e(trans('form.registration.investigation.carnumber')); ?></th>
                            <th style="width: 8%"><?php echo e(trans('form.registration.investigation.passport')); ?></th>
                            <th style="width: 8%"><?php echo e(trans('form.registration.investigator.date_of_birth')); ?></th>
                            <?php if (isAdmin() || isSM()) 
                    {$acflag='';}else{$acflag=1;}?>
                           
                    
                            <?php if($indeltabactive=='' && $isadminsm==1): ?>
                            <th style="width: 8%"><?php echo e(trans('form.registration.investigation.required_cost_invs')); ?></th>
                            <?php endif; ?>
                            <?php if($isadminsm==1): ?>
                            <th style="width: 8%"><?php echo e(trans('form.registration.investigation.address_confirmed')); ?></th>
                            <?php endif; ?>
                            <th style="width: 20%"><?php echo e(trans('general.action')); ?></th>
                            
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $invn->subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($subject->family_name); ?></td>
                                <td><?php echo e($subject->first_name); ?></td>
                                <?php if(in_array($subject->sub_type, array('Main', 'Spouse', 'Company'))): ?>
                                    <?php if(config('app.locale') == 'hr'): ?>
                                    <td><?php echo e(\App\SubjectTypes::where('name', $subject->sub_type)->first()->hr_name); ?></td>
                                    <?php else: ?>
                                    <td><?php echo e(\App\SubjectTypes::where('name', $subject->sub_type)->first()->name); ?></td>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <td><?php echo e($subject->sub_type); ?></td>
                                <?php endif; ?>
                                <td><?php echo e($subject->id_number); ?></td>
                                <td><?php echo e($subject->bank_account_no); ?></td>
                                <td><?php echo e($subject->account_no); ?></td>
                                <td><?php echo e($subject->workplace); ?></td>
                                <td><?php echo e($subject->website); ?></td>
                                <td><?php echo e($subject->father_name); ?></td>
                                <td><?php echo e($subject->mother_name); ?></td>
                                <td><?php echo e($subject->spouse_name); ?></td>
                                <td><?php echo e($subject->spouse); ?></td>
                                <td><?php echo e($subject->car_number); ?></td>
                                <td><?php echo e($subject->passport); ?></td>
                                <?php if(!is_null($subject->dob)): ?>
                                <td><?php echo e(\Carbon\Carbon::parse($subject->dob)->format('d/m/y')); ?></td>
                                <?php else: ?>
                                <td></td>
                                <?php endif; ?>
                                <?php if($indeltabactive==''  && $isadminsm==1): ?>
                                <td><?php echo e(trans('general.money_symbol')); ?><?php echo e($subject->req_inv_cost); ?></td>
                                <?php endif; ?>
                                <?php if($isadminsm==1): ?>
                                <td>
                                    <div class="form-check-inline m-0 "> 
                            
                                        <input name="address_confirmed_<?php echo e($subject->id); ?>" id="address_confirmed_<?php echo e($subject->id); ?>" onclick="changesubjectaddressconfirm(this,<?php echo e($subject->id); ?>);"  type="checkbox" switch="bool"<?php echo e($subject->address_confirmed == 1 ? 'checked' : ''); ?>>
                                        <label class="mt-2 ml-1 arr_contacts_address_confirmed_lable" data-on-label="<?php echo e(trans('general.yes')); ?>" data-off-label="<?php echo e(trans('general.no')); ?>" for="address_confirmed_<?php echo e($subject->id); ?>"></label>
                                        </div>
                                </td>
                                <?php endif; ?>
      
                                <td>
                                    <a href="javascript:void(0);" onclick="showModal('contact_model', <?php echo e($subject->id); ?>)" class="dt-btn-action" title="<?php echo e(trans('form.registration.investigation.show_contacts')); ?>">
                                        <i class="fas fa-fax"></i>
                                    </a>
                                    <div id="contact_model-data-<?php echo e($subject->id); ?>" style="display: none;">
                                   
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.main_email')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e($subject->main_email ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.alternate_email')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e($subject->alternate_email ?? '-'); ?>

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
                                                    <?php echo e($subject->main_phone ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.secondary_phone')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e($subject->secondary_phone ?? '-'); ?>

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
                                                    <?php echo e($subject->main_mobile ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4"><?php echo e(trans('form.registration.investigation.secondary_mobile')); ?></label>
                                                <div class="col-form-label col-8">
                                                    <?php echo e($subject->secondary_mobile ?? '-'); ?>

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
                                                    <?php echo e($subject->fax ?? '-'); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                    <a href="javascript:void(0);" onclick="showModal('address_model', <?php echo e($subject->id); ?>)" class="dt-btn-action" title="<?php echo e(trans('form.registration.investigation.show_addresses')); ?>">
                                        <i class="fas fa-map"></i>
                                    </a>

                                    <div id="address_model-data-<?php echo e($subject->id); ?>" style="display: none;">
                                        <?php if(!$subject->subject_addresses->isEmpty()): ?>
                                            <?php $subindex = 1; ?>
                                            <?php $__currentLoopData = $subject->subject_addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                <div class="form-row mt-3">
                                                    <div class="col-md-12">
                                                        <span class="text-muted font-size-14 mb-1"><?php echo e(trans('form.address') . ' ' . $subindex); ?></span>
                                                        <hr class="mt-2">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                        <div class="form-group row">
                                                            <label for="inv_name" class="col-form-label col-4"><?php echo e(trans('form.registration.client.address')); ?> :</label>
                                                            <div class="col-8">
                                                                <?php echo e($address->address1 ?? '-'); ?>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                        <div class="form-group row">
                                                            <label for="inv_name" class="col-form-label col-4"><?php echo e(trans('form.registration.client.address2')); ?> :</label>
                                                            <div class="col-8">
                                                                <?php echo e($address->address2 ?? '-'); ?>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                        <div class="form-group row">
                                                            <label for="inv_name" class="col-form-label col-4"><?php echo e(trans('form.registration.client.city')); ?> :</label>
                                                            <div class="col-8">
                                                                <?php echo e($address->city ?? '-'); ?>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                        <div class="form-group row">
                                                            <label for="inv_name"
                                                                   class="col-form-label col-4"><?php echo e(trans('form.registration.client.state')); ?> :</label>
                                                            <div class="col-8">
                                                                <?php echo e($address->state ?? '-'); ?>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                        <div class="form-group row">
                                                            <label for="inv_name" class="col-form-label col-4"><?php echo e(trans('form.registration.client.country')); ?> :</label>
                                                            <div class="col-8">
                                                      
                                                               <?php echo e(App::isLocale('hr')?$address->country->hr_name ?? '-':$address->country->en_name ?? '-'); ?> 
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                        <div class="form-group row">
                                                            <label for="inv_name" class="col-form-label col-4"><?php echo e(trans('form.registration.client.zip_code')); ?> :</label>
                                                            <div class="col-8">
                                                                <?php echo e($address->zipcode ?? '-'); ?>

                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <?php $subindex++; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <h6 class="text-center"><?php echo e(trans('form.registration.investigation.no_addresses')); ?></h6>
                                        <?php endif; ?>
                                    </div>
                                </td>

                            </tr>
                        </tbody>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>

                    <div id="address_model" class="modal fade bs-example-modal-center" tabindex="-1"
                         role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0"><?php echo e(trans('form.registration.investigation.show_subject_addresses')); ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">

                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                    </div>

                    <div id="contact_model" class="modal fade bs-example-modal-center" tabindex="-1"
                    role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                   <div class="modal-dialog modal-dialog-centered modal-lg">
                       <div class="modal-content">
                           <div class="modal-header">
                               <h5 class="modal-title mt-0"><?php echo e(trans('form.registration.investigation.contact_details')); ?></h5>
                               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                           </div>
                           <div class="modal-body">

                           </div>
                       </div>
                       <!-- /.modal-content -->
                   </div>
               </div>

                </div>
            </div>
        </div>

    </div>
</div><?php /**PATH /var/www/html/uvda/resources/views/investigation/partials/subject.blade.php ENDPATH**/ ?>