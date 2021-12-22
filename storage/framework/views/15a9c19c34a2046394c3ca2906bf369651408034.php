
        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box">
                        <a href="/" class="logo logo-dark">
                            <span class="logo-sm">
                                
                                <span style="color: white;font-size:20px;">AM</span>
                            
                            </span>
                            <span class="logo-lg">
                                
                                <span style="color: white;font-size:20px;">AlpaMusic</span>
                            
                            </span>
                        </a>

                        <a href="/" class="logo logo-light">
                            <span class="logo-sm">
                                
                                <span style="color: white;font-size:20px;">AM</span>
                            </span>
                            <span class="logo-lg">
                                <span style="color: white;font-size:20px;">AlpaMusic</span>
                                
                                
                            </span>
                        </a>
                    </div>

                    <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                        <i class="mdi mdi-menu"></i>
                    </button>
                    <font class="px-1 pt-4" style="font-size: 15px;"><?php echo e(trans('form.registration.verifymail.profile_update_text')); ?><?php echo e(Auth::user()->name); ?> <?php if((isInvestigator())): ?><?php echo e(Auth::user()->investigator->family); ?><?php endif; ?> <?php if(isDeliveryboy()): ?><?php echo e(Auth::user()->deliveryboy->family); ?><?php endif; ?></font>
                    
                </div>

                <div class="d-flex">
                    <?php if(auth()->user()->type_id == 1): ?>
                    <div class="dropdown d-none d-md-block ml-2">
                        
                        <?php $locale = session()->get('locale'); ?>
                        <?php switch($locale):
                                case ('en'): ?>
                                <button type="button" class="btn header-item waves-effect" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="mr-2" src="<?php echo e(URL::asset('/images/flags/us_flag.jpg')); ?>" alt="Header Language" height="16"> English <span class="mdi mdi-chevron-down"></span>
                        </button>
                                <?php break; ?>
                                <?php case ('hr'): ?>
                               <button type="button" class="btn header-item waves-effect" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <img src="<?php echo e(URL::asset('/images/flags/israel_flag.jpg')); ?>" alt="Header Language" height="16" class="mr-2" height="12"> Hebrew <span class="mdi mdi-chevron-down"></span>
                                   </button>
                                 <?php break; ?>
                                 <?php default: ?>
                                  <button type="button" class="btn header-item waves-effect" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="mr-2" src="<?php echo e(URL::asset('/images/flags/us_flag.jpg')); ?>" alt="Header Language" height="16"> English <span class="mdi mdi-chevron-down"></span>
                        </button>
                                <?php endswitch; ?>
                        <div class="dropdown-menu dropdown-menu-right">
                            <!-- item-->
                            <a href="<?php echo e(route('set_lang','en')); ?>" class="<?php echo e(($locale=='en') ? 'd-none' : ''); ?>  dropdown-item notify-item">
                                <img src="<?php echo e(URL::asset('/images/flags/us_flag.jpg')); ?>" alt="user-image" class="mr-1" height="12"> <span class="align-middle"> English </span>
                            </a>
                            <!-- item-->
                            <a href="<?php echo e(route('set_lang','hr')); ?>" class="<?php echo e(($locale=='hr') ? 'd-none' : ''); ?> dropdown-item notify-item">
                                <img src="<?php echo e(URL::asset('/images/flags/israel_flag.jpg')); ?>" alt="user-image" class="mr-1" height="12"> <span class="align-middle"> Hebrew </span>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="dropdown d-none d-lg-inline-block">
                        <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                            <i class="mdi mdi-fullscreen font-size-24"></i>
                        </button>
                    </div>
                   
                    
                    <div class="dropdown d-inline-block ml-1" id="notificationdata">
                        <?php echo $__env->make('layouts.partials.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                     </div>

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle header-profile-user" src="<?php echo e(URL::asset('/images/users/user-default.png')); ?>"
                                alt="Header Avatar">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <!-- item-->
                            <a class="dropdown-item" href="<?php echo e(route('myprofile.index')); ?>"><i class="mdi mdi-account-circle font-size-17 text-muted align-middle mr-1"></i> <?php echo e(trans('general.menu.profile')); ?></a>
                            <a class="dropdown-item d-block" href="#">

                                <i class="mdi mdi-settings font-size-17 text-muted align-middle mr-1"></i> <?php echo e(trans('general.menu.settings')); ?></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="mdi mdi-power font-size-17 text-muted align-middle mr-1 text-danger"></i><?php echo e(trans('general.menu.logout')); ?></a>
                            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                <?php echo csrf_field(); ?>
                            </form>
                        </div>
                    </div>
            
                </div>
            </div>
        </header>
       
        
     <?php /**PATH /var/www/html/uvda/resources/views/layouts/partials/header.blade.php ENDPATH**/ ?>