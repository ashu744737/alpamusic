<?php $__env->startSection('title'); ?> <?php echo e(trans('form.performainvoices')); ?> <?php $__env->stopSection(); ?>

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
                            <h4 class="card-title"><?php echo e(trans('form.performainvoices')); ?></h4>
                        </div>
                    </div>

                    <?php echo $__env->make('layouts.partials.session-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <div class="flash-message"></div>
                    <?php if(check_perm('invoice_list')): ?>
                    <div class="row">
                        <div class="col-12">

                            <table id="datatable_invoice" class="table table-bordered dt-responsive">
                                <thead>
                                <tr role="row">
                                    <th class="noVis"></th>
                                    <th><?php echo e(trans('form.invoice.clientname')); ?></th>
                                    <th><?php echo e(trans('form.invoice.investigation')); ?></th>
                                    <th><?php echo e(trans('form.invoice.invoice_no')); ?></th>
                                    <th><?php echo e(trans('form.registration.investigation.paying_customer')); ?></th>
                                    <th><?php echo e(trans('form.invoice.amount')); ?></th>
                                    <th><?php echo e(trans('form.registration.investigation.created_by')); ?></th>
                                    <th><?php echo e(trans('general.created_at')); ?></th>
                                    <th><?php echo e(trans('form.invoice.status')); ?></th>
                                    <th><?php echo e(trans('form.invoice.due_date')); ?></th>
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
            
            cDTable = $('#datatable_invoice').DataTable({
                // dom: 'lBfrtip',
                'dom' : "<'row'<'col-sm-12 col-md-9'lB><'col-sm-12 col-md-3'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
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
                ajax: "<?php echo e(route('invoice-list')); ?>",
                columns: [
                    {data: 'id', name: 'check', orderable: false, searchable: false},
                    //{data: 'investigations', name: 'investigations'},
                    {data: 'client_name', name: 'investigation.user.name'},
                    {data: 'work_order_number', name: 'investigation.work_order_number'},
                    {data: 'invoice_no', name: 'invoice_no'},
                    {data: 'paying_customer', name: 'investigation.client_type.name'},
                    {data: 'amount', name: 'amount'},
                    {data: 'investigationCreatedBy', name: 'investigation.investigationCreatedBy.name'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'status', name: 'status'},
                    {data: 'due_date', name: 'due_date'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                    {data: 'late', name: 'late', orderable: false, searchable: false, visible: false},
                ],
                createdRow: function ( row, data, index ) {
                    if ( data['late'] == 1 ) {
                        // $('td', row).eq(7).addClass('success');
                    } else {
                        $('td', row).css('background-color', '#EA1C29');
                        $('td', row).css('color', 'white');
                    }
                },
                buttons: ['excel', 'pdf', <?php if(true): ?>{
                        "text": '<?php echo e(trans("form.invoice.mark_as_paid")); ?>',
                        action: function ( e, dt, node, config ) {
                            markAsPaidRecords();
                        },
                },<?php endif; ?>, { extend: 'colvis', columns: ':not(.noVis)'},
                    ],
                colReorder: {
                    realtime: false,
                    fixedColumnsRight: 2
                },
                'columnDefs': [
                    {
                        'className': 'text-center',
                        'orderable': false,
                        'targets': 0,
                        'checkboxes': {
                            'selectRow': true
                        }
                    }
                ],
                'select': {
                    'style': 'multi'
                },
                order: [[7,'desc']],
            });

            cDTable.buttons().container().appendTo('#datatable_invoice_wrapper .col-md-6:eq(0)');

            function markAsPaidRecords(){
                var rows_selected = cDTable.column(0).checkboxes.selected();
                var ids = [];

                if(rows_selected.length > 0){
                    $.each(rows_selected, function(index, rowId){
                        ids.push(rowId);
                    });
                    document.cookie = "ids=" + ids;
                    window.location = "/invoices/bulk";
                    // $.ajax({
                    //     url: "<?php echo e(route('investigation.showinvoices')); ?>",
                    //     type: 'POST',
                    //     dataType: 'json',
                    //     data: {
                    //         "ids": ids,
                    //         "_token": $('meta[name="csrf-token"]').attr('content'),
                    //     },
                    //     success: function (){
                    //         // cDTable.draw();
                            
                    //     }
                    // });
                } else{
                    Swal.fire({
                      icon: 'error',
                      text: "<?php echo e(trans('general.no_row_selected')); ?>",
                    })
                }
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/uvda/resources/views/invoice/index.blade.php ENDPATH**/ ?>