<?php $__env->startSection('title'); ?> <?php echo e(trans('form.registration.investigation.subjects')); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('headerCss'); ?>
    <!-- headerCss -->
    <!-- DataTables -->
    <link href="<?php echo e(URL::asset('/libs/datatables/datatables.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::asset('/libs/datatables/colReorder.dataTables.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::asset('/libs/datatables/dataTables.checkboxes.css')); ?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?php echo e(URL::asset('/libs/sweetalert2/sweetalert2.min.css')); ?>">
    <?php if(config('app.locale') == 'hr'): ?>
    <style>
        .dt-buttons{
            float: right;
        }
    </style>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 col-sm-4 col-xs-12">
                            <h4 class="card-title"><?php echo e(trans('form.registration.investigation.subjects')); ?></h4>
                        </div>
                       
                    </div>

                    <?php echo $__env->make('session-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php if(check_perm('subject_show')): ?>
                    <div class="row">
                        <div class="col-12">

                            <table id="datatable_clients" class="table table-bordered dt-responsive">
                                <thead>
                                <tr role="row">
                                    <th><?php echo e(trans('form.registration.investigation.family')); ?></th>
                                    <th><?php echo e(trans('form.registration.investigation.firstname')); ?></th>
                                    <th><?php echo e(trans('form.registration.investigation.id')); ?></th>
                                    <th><?php echo e(trans('form.registration.investigation.account_no')); ?></th>
                                    <th><?php echo e(trans('form.registration.investigation.workplace')); ?></th>
                                    <th><?php echo e(trans('form.registration.investigation.website')); ?></th>
                                    <th><?php echo e(trans('form.registration.investigation.father_name')); ?></th>
                                    <th><?php echo e(trans('form.registration.investigation.mothername')); ?></th>
                                    <th><?php echo e(trans('form.registration.investigation.spousename')); ?></th>
                                    <th class="noVis"><?php echo e(trans('form.action')); ?></th>
                                </tr>
                                </thead>
                            </table>

                        </div>
                    </div>
                    <?php endif; ?>
                  

                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footerScript'); ?>
    <!-- footerScript -->
    <!-- Required datatable js -->
    <script src="<?php echo e(URL::asset('/libs/datatables/datatables.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/jszip/jszip.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/pdfmake/pdfmake.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/datatables/dataTables.colReorder.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/datatables/dataTables.checkboxes.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/sweetalert2/sweetalert2.min.js')); ?>" async></script>
    <script src="<?php echo e(URL::asset('/libs/pdfmake/vfs_fonts.js')); ?>"></script>
    <script>
        $(document).ready(function() {
            var cDTable = $('#datatable_clients').DataTable({
                // dom: 'lBfrtip',
                'dom' : "<'row'<'col-sm-12 col-md-6'Bl><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                search:true,
                lengthChange: true,
                autoWidth: false,
                stateSave: true,
                processing: true,
                serverSide: true,
                language:{
                    <?php if(config('app.locale') == 'hr'): ?>
                    url: '<?php echo e(URL::asset('/libs/datatables/json/Hebrew.json')); ?>'
                    <?php endif; ?>
                },
                ajax: "<?php echo e(route('get-subject-list')); ?>",
                columns: [
                    {data: 'family_name', name: 'family_name'},
                    {data: 'first_name', name: 'first_name'},
                    {data: 'id_number', name: 'id_number'},
                    {data: 'account_no', name: 'account_no'},
                    {data: 'workplace', name: 'workplace'},
                    {data: 'website', name: 'website'},
                    {data: 'father_name', name: 'father_name'},
                    {data: 'mother_name', name: 'mother_name'},
                    {data: 'spouse_name', name: 'spouse_name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                buttons: ['excel', 'pdf',
            
                    { extend: 'colvis', columns: ':not(.noVis)'},
                    ],
                colReorder: {
                    realtime: false,
                    fixedColumnsRight: 2
                },
                order: [],
                
            });
           
            cDTable.buttons().container().appendTo('#datatable_clients_wrapper .col-md-6:eq(0)');

           
            

           
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/uvda/resources/views/subjects/index.blade.php ENDPATH**/ ?>