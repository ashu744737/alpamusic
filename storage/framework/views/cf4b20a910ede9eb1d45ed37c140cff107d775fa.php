<?php $__env->startSection('title'); ?> <?php echo e(trans('form.deliveryboys')); ?> <?php $__env->stopSection(); ?>

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
                            <h4 class="card-title"><?php echo e(trans('form.deliveryboys')); ?></h4>
                        </div>
                        <?php if(check_perm('deliveryboy_create')): ?>
                        <div class="col-12 col-sm-8 col-xs-12 text-left text-sm-right mb-2">
                            <a href="<?php echo e(route('deliveryboy.create')); ?>" class="btn btn-primary w-md waves-effect waves-light add-new-btn"><?php echo e(trans('form.registration.deliveryboy.add_deliveryboy')); ?></a>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php echo $__env->make('layouts.partials.session-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <div class="flash-message"></div>
                    <?php if(check_perm('deliveryboy_show')): ?>
                    <div class="row">
                        <div class="col-12">

                            <form id="frm-example" action="#" method="POST">

                                <table id="datatable_deliveryboys" class="table table-bordered dt-responsive">
                                <thead>
                                <tr role="row">
                                    <th class="noVis"></th>
                                    <th><?php echo e(trans('form.registration.investigator.name')); ?></th>
                                    <th><?php echo e(trans('form.registration.investigator.email')); ?></th>
                                    <th><?php echo e(trans('form.registration.investigator.family')); ?></th>
                                    <th><?php echo e(trans('form.registration.investigator.id_number')); ?></th>
                                    <th><?php echo e(trans('form.registration.investigator.website')); ?></th>
                                    <th><?php echo e(trans('form.registration.deliveryboy.delivery_areas')); ?></th>
                                    <th><?php echo e(trans('form.registration.investigator.phone')); ?></th>
                                    <th><?php echo e(trans('form.registration.investigator.address')); ?></th>
                                    <th><?php echo e(trans('general.status')); ?></th>
                                    <th class="noVis"><?php echo e(trans('general.action')); ?></th>
                                </tr>
                                </thead>
                            </table>

                            </form>
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
        var dbDTable = null;

        $(document).ready(function() {
            dbDTable = $('#datatable_deliveryboys').DataTable({
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
                ajax: "<?php echo e(route('deliveryboy-list')); ?>",
                columns: [
                    {data: 'id', name: 'check', orderable: false, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'family', name: 'deliveryboy.family'},
                    {data: 'idnumber', name: 'deliveryboy.idnumber'},
                    {data: 'website', name: 'deliveryboy.website'},
                    {data: 'deliveryareas', name: 'delivery_areas.area_name'},
                    {data: 'phone', name: 'userPhones.value'},
                    {data: 'address', name: 'userAddresses.address1'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                    {data: 'created_at', name: 'created_at', searchable: false, visible: false},
                ],
                buttons: ['excel', 'pdf',
                <?php if(check_perm('deliveryboy_delete')): ?>{
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
                order: [[11,'desc']],
            });

            dbDTable.buttons().container().appendTo('#datatable_deliveryboys_wrapper .col-md-6:eq(0)');

            $("body").on("click", "#deleteDeliveryboy", function(){
                var id = $(this).data("id");

                Swal.fire({
                    title: "<?php echo e(trans('general.are_you_sure')); ?>",
                    text: "<?php echo e(trans('form.registration.deliveryboy.confirm_delete')); ?>",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#34c38f",
                    cancelButtonColor: "#f46a6a",
                    confirmButtonText: "<?php echo e(trans('general.yes_delete')); ?>",
                    cancelButtonText: "<?php echo e(trans('general.cancel')); ?>"
                }).then(function (result) {
                    if (result.value){
                        $.ajax(
                            {
                                url: "/deliveryboys/" + id,
                                type: 'DELETE',
                                data: {
                                    _token: "<?php echo e(csrf_token()); ?>",
                                    id: id,
                                },
                                success: function (response){
                                    if (response.status == 'success') {
                                        Swal.fire({
                                            title: "<?php echo e(trans('general.deleted_text')); ?>",
                                            text: (result.message) ? result.message : "<?php echo e(trans('form.registration.deliveryboy.deliveryboy_deleted')); ?>", 
                                            type: "success",
                                            confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
                                        });
                                        dbDTable.draw();
                                    } else {
                                        Swal.fire("<?php echo e(trans('general.error_text')); ?>",(result.message) ? result.message : "<?php echo e(trans('general.something_wrong')); ?>", "error");
                                    }
                                }
                            }
                        );
                    }else{
                        Swal.close();
                    }
                });

            });

        });

        function deleteSelectedRecords(){

            var rows_selected = dbDTable.column(0).checkboxes.selected();
            // var rows_selected = dbDTable.columns().checkboxes.selected()[0];
            var ids = [];

            if(rows_selected.length > 0){
                Swal.fire({
                    title: "<?php echo e(trans('general.are_you_sure')); ?>",
                    text: "<?php echo e(trans('form.registration.deliveryboy.confirm_delete')); ?>",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#34c38f",
                    cancelButtonColor: "#f46a6a",
                    confirmButtonText: "<?php echo e(trans('general.yes_delete')); ?>",
                    cancelButtonText: "<?php echo e(trans('general.cancel')); ?>"
                }).then(function (result) {
                    if (result.value){
                        // Iterate over all selected checkboxes
                        $.each(rows_selected, function(index, rowId){
                            ids.push(rowId);
                        });

                        console.log("ids", ids);

                        $.ajax(
                            {
                                url: "<?php echo e(route('multidelete-deliveryboy')); ?>",
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    "ids": ids,
                                    "_token": "<?php echo e(csrf_token()); ?>",
                                },
                                success: function (response){
                                    if (response.status == 'success') {
                                        Swal.fire({
                                            title: "<?php echo e(trans('general.deleted_text')); ?>",
                                            text: (result.message) ? result.message : "<?php echo e(trans('form.registration.deliveryboy.deliveryboy_deleted')); ?>", 
                                            type: "success",
                                            confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
                                        });
                                        dbDTable.draw();
                                    } else {
                                        Swal.fire("<?php echo e(trans('general.error_text')); ?>",(result.message) ? result.message : "<?php echo e(trans('general.something_wrong')); ?>", "error");
                                    }
                                }
                            });
                    }else{
                        Swal.close();
                    }
                });
            }else{
                Swal.fire("<?php echo e(trans('general.error_text')); ?>", "<?php echo e(trans('general.no_row_selected')); ?>", 'error');
            }
        }

        function changeStatus(action, id){

            var url = "<?php echo e(route('deliveryboy.change-status')); ?>";

            Swal.fire({
                title: "<?php echo e(trans('general.are_you_sure')); ?>",
                text: "<?php echo e(trans('form.registration.deliveryboy.confirm_changestatus')); ?>",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#34c38f",
                cancelButtonColor: "#f46a6a",
                confirmButtonText: "<?php echo e(trans('general.yes_change')); ?>",
                cancelButtonText: "<?php echo e(trans('general.cancel')); ?>"
            }).then(function (result) {
                if (result.value){

                    $.ajax(
                        {
                            url: url,
                            type: 'post',
                            dataType: 'json',
                            data: {
                                action: action,
                                id: id,
                                "_token": "<?php echo e(csrf_token()); ?>",
                            },
                            success: function (response){
                                if (response.status == 'success') {
                                    Swal.fire({
                                        title: "<?php echo e(trans('general.changed_text')); ?>",
                                        text: (result.message) ? result.message : "<?php echo e(trans('form.registration.deliveryboy.confirm_statuschanged')); ?>", 
                                        type: "success",
                                        confirmButtonText: "<?php echo e(trans('general.ok')); ?>",
                                    });
                                    dbDTable.draw();
                                } else {
                                    Swal.fire("<?php echo e(trans('general.error_text')); ?>",(result.message) ? result.message : "<?php echo e(trans('general.something_wrong')); ?>", "error");
                                }
                            }
                        });
                }else{
                    Swal.close();
                }
            });
        }

    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/uvda/resources/views/deliveryboys/index.blade.php ENDPATH**/ ?>