<?php $__env->startSection('title'); ?> Titles <?php $__env->stopSection(); ?>

<?php $__env->startSection('headerCss'); ?>
	<!-- headerCss -->
	<!-- DataTables -->
	<link href="<?php echo e(URL::asset('/libs/datatables/datatables.min.css')); ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo e(URL::asset('/libs/datatables/colReorder.dataTables.min.css')); ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo e(URL::asset('/libs/datatables/dataTables.checkboxes.css')); ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo e(URL::asset('/libs/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet" type="text/css"/>
	<style type="text/css">
		.table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child:before {
			margin-top: 1.9rem;
		}

		.top-inv-wrapper .card .card-head {
			padding: 10px;
			background: #f7f6f6;
			font-weight: bold;
		}

		.top-inv-wrapper .card .card-head p {
			margin: 0;
			text-align: center;
		}

		.top-inv-wrapper .card .card-body .table tr:first-child td {
			border-top: none;
		}

		#datatable_titles tbody tr .personal-details p {
			margin-bottom: 0;
		}

		#datatable_titles tbody tr .investigator-status p {
			margin-bottom: 0;
		}

		.investigations-ul {
			list-style-type: none;
			padding: 0;
		}

		.speclist-ul {
			padding-left: 1.1em;
		}
		/* Portrait and Landscape */
		@media  only screen 
		and (min-device-width: 320px) 
		and (max-device-width: 410px)
		and (-webkit-min-device-pixel-ratio: 2) {
			.table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child:before {
					/* margin-top: 0; */
				}
			.table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:nth-child(2) {
				display: inline-block !important;
				}
				.table.dataTable.dtr-inline.collapsed > thead > tr[role="row"] > th:nth-child(2) {
				display: inline-block !important;
				}
				.table.dataTable.dtr-inline.collapsed > tbody > tr.child ul.dtr-details >li:first-child{
					display: none !important;
				}
			
		}
		@media  only screen 
		and (min-device-width: 410px) 
		and (max-device-width: 480px)
		and (-webkit-min-device-pixel-ratio: 2) {
			.table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:nth-child(2) {
				display: inline-block !important;
				}
				.table.dataTable.dtr-inline.collapsed > thead > tr[role="row"] > th:nth-child(2) {
				display: inline-block !important;
				}
		}
	</style>
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
		<div class="col-12 col-sm-12">
			<div class="card">
				<div class="card-body">

					<div class="row">
						<div class="col-12 col-sm-4 col-xs-12">
							<h4 class="card-title"><?php echo e(trans('form.titles')); ?></h4>
						</div>
						
							<div class="col-12 col-sm-8 col-xs-12 text-left text-sm-right mb-2">
								
								   <a href="<?php echo e(route('titles.create')); ?>"
								   class="btn btn-primary w-md waves-effect waves-light add-new-btn">Add Title</a>
							</div>
						
					</div>

					<?php echo $__env->make('layouts.partials.session-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

					<div class="flash-message"></div>
					
						<div class="row">
							<div class="col-12">
								<table id="datatable_titles"
									   class="table table-centered table-vertical dt-responsive">
									<thead>
									<tr role="row">
										<th class="noVis"></th>
										<th>Name</th>
										
										<th>Categories</th>
										<th>Status</th>
										<th class="noVis"><?php echo e(trans('general.action')); ?></th>
									</tr>
									</thead>
								</table>
							</div>
						</div>
					
				</div>
			</div>
		</div>


	</div>

	<div id="view_investigations_model" class="modal fade bs-example-modal-center  bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">

	</div>

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
		var iDTable = null;

		$(document).ready(function () {
			iDTable = $('#datatable_titles').DataTable({
				'dom': "<'row'<'col-sm-12 col-md-8'Bl><'col-sm-12 col-md-4'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
				search: true,
				lengthChange: true,
				autoWidth: false,
				stateSave: true,
				processing: true,
				serverSide: true,
				language: {
					<?php if(config('app.locale') == 'hr'): ?>
						url: '<?php echo e(URL::asset('/libs/datatables/json/Hebrew.json')); ?>'
					<?php endif; ?>
				},
				ajax: "<?php echo e(route('title-list')); ?>",
				columns: [
					{data: 'id', name: 'check', orderable: false, searchable: false},
					{
						data: 'name', name: 'name', sortable: false, "render": function (data, type, row, meta) {
						return `<div class="p-1">
							<h6 class="mb-1 font-size-16 mt-2" id="titlename">${data}</h6>
							</div>`;
					}
					},
					
					{data: 'categories', name: 'categories', sortable: false},
					{data: 'confirmation_status', name: 'confirmation_status', sortable: false},
					{data: 'action', name: "action", sortable: false},
				],
				buttons: [
					// 'excel', 'pdf',
					{
						"text": '<?php echo e(trans("general.delete")); ?>',
						action: function (e, dt, node, config) {
							deleteSelectedRecords();
						},
					}, 
					{
						extend: 'colvis', columns: ':not(.noVis)'
					},
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

			iDTable.buttons().container().appendTo('#datatable_titles_wrapper .col-md-8:eq(0)');
		});

		// function changeStatus(action, id) {

		// 	var url = "<?php echo e(route('investigators.change-status')); ?>";

		// 	Swal.fire({
		// 		title: "<?php echo e(trans('general.are_you_sure')); ?>",
		// 		text: "<?php echo e(trans('form.registration.investigator.confirm_changestatus')); ?>",
		// 		type: "warning",
		// 		showCancelButton: true,
		// 		confirmButtonColor: "#34c38f",
		// 		cancelButtonColor: "#f46a6a",
		// 		confirmButtonText: "<?php echo e(trans('general.yes_change')); ?>",
		// 		cancelButtonText: "<?php echo e(trans('general.cancel')); ?>",
		// 	}).then(function (result) {
		// 		if (result.value) {

		// 			$.ajax(
		// 				{
		// 					url: url,
		// 					type: 'post',
		// 					dataType: 'json',
		// 					data: {
		// 						action: action,
		// 						id: id,
		// 						"_token": "<?php echo e(csrf_token()); ?>",
		// 					},
		// 					success: function (response) {
		// 						if (response.status == 'success') {
		// 							Swal.fire("<?php echo e(trans('general.changed_text')); ?>", (result.message) ? result.message : "<?php echo e(trans('form.registration.investigator.confirm_statuschanged')); ?>", "success");
		// 							iDTable.draw();
		// 						} else {
		// 							Swal.fire("<?php echo e(trans('general.error_text')); ?>", (result.message) ? result.message : "<?php echo e(trans('general.something_wrong')); ?>", "error");
		// 						}
		// 					}
		// 				});
		// 		} else {
		// 			Swal.close();
		// 		}
		// 	});
		// }

		$("body").on("click", "#deletetitle", function () {
			var id = $(this).data("id");

			Swal.fire({
				title: "<?php echo e(trans('general.are_you_sure')); ?>",
				text: "Do you want to delete this Title",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#34c38f",
				cancelButtonColor: "#f46a6a",
				confirmButtonText: "<?php echo e(trans('general.yes_delete')); ?>",
				cancelButtonText: "<?php echo e(trans('general.cancel')); ?>",
			}).then(function (result) {
				if (result.value) {
					$.ajax(
						{
							url: "/title/" + id,
							type: 'DELETE',
							data: {
								_token: "<?php echo e(csrf_token()); ?>",
								id: id,
							},
							success: function (response) {
								if (response.status == 'success') {
									Swal.fire("<?php echo e(trans('general.deleted_text')); ?>", (result.message) ? result.message : "You have successfully deleted Title", "success");
									iDTable.draw();
								} else {
									Swal.fire("<?php echo e(trans('general.error_text')); ?>", (result.message) ? result.message : "<?php echo e(trans('general.something_wrong')); ?>", "error");
								}
							}
						}
					);
				} else {
					Swal.close();
				}
			});
		});

		

		function deleteSelectedRecords() {

			var rows_selected = iDTable.column(0).checkboxes.selected();
			var ids = [];

			if (rows_selected.length > 0) {
				Swal.fire({
					title: "<?php echo e(trans('general.are_you_sure')); ?>",
					text: "Do you want to delete these Titles",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#34c38f",
					cancelButtonColor: "#f46a6a",
					confirmButtonText: "<?php echo e(trans('general.yes_delete')); ?>",
					cancelButtonText: "<?php echo e(trans('general.cancel')); ?>"
				}).then(function (result) {
					if (result.value) {
						// Iterate over all selected checkboxes
						$.each(rows_selected, function (index, rowId) {
							ids.push(rowId);
						});

						$.ajax(
							{
								url: "<?php echo e(route('multidelete-titles')); ?>",
								type: 'POST',
								dataType: 'json',
								data: {
									"ids": ids,
									"_token": "<?php echo e(csrf_token()); ?>",
								},
								success: function (response) {
									if (response.status == 'success') {
										Swal.fire("<?php echo e(trans('general.deleted_text')); ?>", (result.message) ? result.message : "Titles has been deleted", "success");
										iDTable.draw();
									} else {
										Swal.fire("<?php echo e(trans('general.error_text')); ?>", (result.message) ? result.message : "<?php echo e(trans('general.something_wrong')); ?>", "error");
									}
								}
							});
					} else {
						Swal.close();
					}
				});
			} else {
				Swal.fire("<?php echo e(trans('general.error_text')); ?>", "<?php echo e(trans('general.no_row_selected')); ?>", 'error');
			}
		}

		$("body").on("click", "#change_title_status", function () {
			var id = $(this).data("id");

			Swal.fire({
				title: "<?php echo e(trans('general.are_you_sure')); ?>",
				text: "Do you want to change the Status",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#34c38f",
				cancelButtonColor: "#f46a6a",
				confirmButtonText: "Yes",
				cancelButtonText: "Cancel",
			}).then(function (result) {
				if (result.value) {
					$.ajax(
						{
							url: "/title/change_status/" + id,
							type: 'get',
							data: {
								_token: "<?php echo e(csrf_token()); ?>",
								id: id,
							},
							success: function (response) {
								if (response.status == 'success') {
									Swal.fire("Status Changed", (result.message) ? result.message : "You have successfully Changed the Status", "success");
									iDTable.draw();
								} else {
									Swal.fire("<?php echo e(trans('general.error_text')); ?>", (result.message) ? result.message : "<?php echo e(trans('general.something_wrong')); ?>", "error");
								}
							}
						}
					);
				} else {
					Swal.close();
				}
			});
		});

		function viewInvestigationList(invId, status) {
			let investigatorId = invId;
			$.ajax({
				url: "/investigators/" + investigatorId + "/view-investigations",
				type: "GET",
				data: {
					_token: "<?php echo e(csrf_token()); ?>",
					status: status
				},
				success: function( response ) {
					if(response.status == true){
						$('#view_investigations_model').html(response.html);
						$('#view_investigations_model').modal('show');
					} else{
						$('#view_investigations_model').modal('hide');
					}
				}

			});
		}

	</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/uvda/resources/views/titles/index.blade.php ENDPATH**/ ?>