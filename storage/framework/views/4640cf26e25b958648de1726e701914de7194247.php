<?php $__env->startSection('title'); ?> <?php echo e(trans('form.staff')); ?> <?php $__env->stopSection(); ?>

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
                            <h4 class="card-title"><?php echo e(trans('form.staff')); ?></h4>
                        </div>
                        <?php if(check_perm('staff_create')): ?>
                        <div class="col-12 col-sm-8 col-xs-12 text-left text-sm-right mb-2">
                            <a href="<?php echo e(route('staff.create')); ?>" class="btn btn-primary w-md waves-effect waves-light add-new-btn"><?php echo e(trans('form.internal_user.add_staff')); ?></a>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php echo $__env->make('session-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php if(check_perm('staff_show')): ?>
                    <div class="row">
                        <div class="col-12">

                            <table id="datatable_clients" class="table table-bordered dt-responsive">
                                <thead>
                                <tr role="row">
                                    <th class="noVis text-left"></th>
                                    <th><?php echo e(trans('form.internal_user.name')); ?></th>
                                    <th><?php echo e(trans('form.internal_user.type')); ?></th>
                                    <th><?php echo e(trans('form.internal_user.email')); ?></th>
                                    <th><?php echo e(trans('form.internal_user.status')); ?></th>
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
                ajax: "<?php echo e(route('staff.getlist')); ?>",
                columns: [
                    {data: 'id', name: 'check'},
                    {data: 'name', name: 'name'},
                    {data: 'user_type', name: 'user_type'},
                    {data: 'email', name: 'email'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: true, searchable: true},
                ],
                buttons: ['excel', 'pdf',
                <?php if(check_perm('staff_delete')): ?>{
                        "text": '<?php echo e(trans("general.delete")); ?>',
                        action: function ( e, dt, node, config ) {
                            deleteSelectedRecords();
                        },
                },<?php endif; ?>
                    { extend: 'colvis', columns: ':not(.noVis)'},
                    ],
                colReorder: {
                    realtime: false,
                    fixedColumnsRight: 2
                },
                order: [],
                'columnDefs': [
                    {
                        'targets': 0,
                        'checkboxes': {
                            'selectRow': true
                        }
                    }
                ],
                'select': {
                    'style': 'multi'
                },
            });
    
            cDTable.buttons().container().appendTo('#datatable_clients_wrapper .col-md-6:eq(0)');

            $("body").on("click", ".delete-record", function(e){
                var id = $(this).data('id');
                Swal.fire({
                    title: "<?php echo e(trans('general.confirm_delete')); ?>",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0CC27E',
                    cancelButtonColor: '#FF586B',
                    confirmButtonText: "<?php echo e(trans('general.yes_delete')); ?>",
                    cancelButtonText: "<?php echo e(trans('general.cancel')); ?>",
                    confirmButtonClass: 'btn btn-lg btn-success mr-5',
                    cancelButtonClass: 'btn btn-lg btn-danger',
                    buttonsStyling: false
                }).then((result) => {
                  if (result.value) {
                    ajaxRemoveRecord(id);
                  } else if (result.dismiss == "cancel") {
                    
                  }
                })
            });

            function ajaxRemoveRecord(record_id) {
                $('.loading').removeClass('d-none');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "staff/remove/" + record_id,
                    method: 'delete',
                    success: function(result) {
                        if (result.status == 'success') {
                            cDTable.draw();
                            Swal.fire({
                                title: "<?php echo e(trans('general.deleted_text')); ?>",
                                text: (result.message) ? result.message : "<?php echo e(trans('general.successfully_delete')); ?>",
                                type: "<?php echo e(trans('general.success_text')); ?>",
                                confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
                            })

                        } else {
                            Swal.fire(
                                "<?php echo e(trans('general.error_text')); ?>",
                                (result.message) ? result.message : "<?php echo e(trans('general.error')); ?>",
                                "<?php echo e(trans('general.error_text')); ?>"
                            )
                        }
                        $('.loading').addClass('d-none');
                    },
                    error: function(response) {
                        $('.invalidMessage').removeClass('alert-success');
                        $('.invalidMessage').addClass('alert-danger');
                        $('.invalidMessage').find('span.message-content').html("<?php echo e(trans('general.error')); ?>");
                        $('.invalidMessage').removeClass('d-none');
                        $('.loading').addClass('d-none');
                    }
                });
            }

            function deleteSelectedRecords(){
                var rows_selected = cDTable.column(0).checkboxes.selected();
                var ids = [];

                if(rows_selected.length > 0){
                    Swal.fire({
                        title: "<?php echo e(trans('general.confirm_delete')); ?>",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#0CC27E',
                        cancelButtonColor: '#FF586B',
                        confirmButtonText: "<?php echo e(trans('general.yes_delete')); ?>",
                    cancelButtonText: "<?php echo e(trans('general.cancel')); ?>",
                        confirmButtonClass: 'btn btn-lg btn-success mr-5',
                        cancelButtonClass: 'btn btn-lg btn-danger',
                        buttonsStyling: false
                    }).then((result) => {
                      if (result.value) {
                        $.each(rows_selected, function(index, rowId){
                            ids.push(rowId);
                        });
                        $.ajax({
                            url: "<?php echo e(route('staff.multidelete')); ?>",
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                "ids": ids,
                                "_token": $('meta[name="csrf-token"]').attr('content'),
                            },
                            success: function (){
                                cDTable.draw();
                            }
                        });
                      } else if (result.dismiss == "cancel") {
                        
                      }
                    });
                }else{
                    Swal.fire({
                      icon: 'error',
                      text: "<?php echo e(trans('general.no_row_selected')); ?>",
                    })
                }
            }

            $("body").on("click", "a.status-btn", function(event) {
                event.stopPropagation();
                $('.loading').removeClass('d-none');
                var user_id = $(this).data('id');
                var status = $(this).data('status');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "staff/update-status",
                    method: 'post',
                    data: {
                        user_id: user_id,
                        status: status
                    },
                    success: function(result) {
                        if (result.success == "true") {
                            cDTable.draw();
                            Swal.fire({
                              icon: 'success',
                              text: result.message,
                            })
                        }
                    },
                    error: function(response) {
                        if (response.success == "false") {
                            Swal.fire({
                              icon: 'warning',
                              text: result.message,
                            })
                        }
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/uvda/resources/views/internal-user/index.blade.php ENDPATH**/ ?>