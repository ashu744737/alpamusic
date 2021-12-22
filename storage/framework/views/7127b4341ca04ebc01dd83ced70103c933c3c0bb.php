<?php $__env->startSection('title'); ?> <?php echo e(trans('form.tickets')); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('headerCss'); ?>
    <!-- headerCss -->
    <!-- DataTables -->
    <link href="<?php echo e(URL::asset('/libs/datatables/datatables.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::asset('/libs/datatables/colReorder.dataTables.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::asset('/libs/datatables/dataTables.checkboxes.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::asset('/libs/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet" type="text/css" />
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
                            <h4 class="card-title"><?php echo e(trans('form.tickets')); ?></h4>
                        </div>
                        <?php if(check_perm('tickets_create') && isClient() ): ?>
                        <div class="col-12 col-sm-8 col-xs-12 text-left text-sm-right mb-2">
                            <a href="<?php echo e(route('tickets.create')); ?>" class="btn btn-primary w-md waves-effect waves-light add-new-btn"><?php echo e(trans('form.ticket.create')); ?></a>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php echo $__env->make('layouts.partials.session-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <div class="flash-message"></div>
                    <?php if(check_perm('tickets_show')): ?>
                    <div class="row">
                        <div class="col-12">

                            <table id="datatable_tickets" class="table table-bordered dt-responsive">
                                <thead>
                                <tr role="row">
                                    <?php if(isAdmin()): ?> 
                                    <th><?php echo e(trans('form.ticket.name')); ?></th>
                                    <?php endif; ?>
                                    <th><?php echo e(trans('form.ticket.subject')); ?></th>
                                    <th><?php echo e(trans('form.ticket.type')); ?></th>
                                    <th><?php echo e(trans('form.ticket.investigation')); ?></th>
                                    <th><?php echo e(trans('form.ticket.ticket_message')); ?></th>
                                    <th class="noVis"><?php echo e(trans('general.created_at')); ?></th>
                                    <th><?php echo e(trans('form.ticket.status')); ?></th>
                                    <th class="noVis"><?php echo e(trans('general.action')); ?></th>
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
        var cDTable = null;

        $(document).ready(function() {
            
            cDTable = $('#datatable_tickets').DataTable({
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
                ajax: "<?php echo e(route('ticket-list')); ?>",
                columns: [
                    <?php if(isAdmin()): ?> 
                    {data: 'user.name', name: 'user.name'},
                    <?php endif; ?>
                    {data: 'subject', name: 'subject'},
                    {data: 'type', name: 'type'},
                    {data: 'investigations', name: 'investigations'},
                    {data: 'message', name: 'message'},
                    {data: 'created_at', name: 'created_at', searchable: false, visible: false},
                    {data: 'status', name: 'status', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                buttons: [{ extend: 'colvis', columns: ':not(.noVis)'},
                    ],
                colReorder: {
                    realtime: false,
                    fixedColumnsRight: 2
                },
                'columnDefs': [
                    {
                        'targets': 0,
                    }
                ],
                order: [[5,'desc']],
            });

            cDTable.buttons().container().appendTo('#datatable_tickets_wrapper .col-md-6:eq(0)');
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/uvda/resources/views/tickets/index.blade.php ENDPATH**/ ?>