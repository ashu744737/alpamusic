 <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu">

            <div data-simplebar class="h-100">

                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <!-- Left Menu Start -->
                    <ul class="metismenu list-unstyled" id="side-menu">

                        <?php if(is_menu_enable('dashboard')): ?>
                        <li class="<?php echo e(request()->is('dashboard/index') ? 'mm-active' : ''); ?>">
                            <a href="<?php echo e(route('home')); ?>" class="waves-effect <?php echo e(request()->is('dashboard/index')  ? 'active' : ''); ?>">
                                <i class="mdi mdi-view-dashboard"></i>
                                <span><?php echo app('translator')->get('form.dashboard'); ?></span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <li>
                            <a href="<?php echo e(route('titles')); ?>" class=" waves-effect <?php echo e(request()->is('/titles') ? 'active' : ''); ?>">
                                <i class="mdi mdi-cash-usd"></i>
                                <span><?php echo app('translator')->get('form.titles'); ?></span>
                            </a>
                        </li>
                        

                        
                        <?php if(is_menu_enable('investigator')): ?>
                        <li>
                            <a href="<?php echo e(route('investigators')); ?>" class=" waves-effect <?php echo e(request()->is('/investigators') ? 'active' : ''); ?>">
                                <i class="mdi mdi-account-multiple"></i>
                                <span><?php echo app('translator')->get('form.contributors'); ?></span>
                            </a>
                        </li>
                        <?php endif; ?>

                       
                        
                        
                        
                                
                                

                    </ul>
                </div>
                <!-- Sidebar -->
            </div>
        </div>
        <!-- Left Sidebar End --><?php /**PATH /var/www/html/uvda/resources/views/layouts/partials/sidebar.blade.php ENDPATH**/ ?>