@extends('layouts.master')

@section('title') {{trans('form.client_approval.title_inv')}} @endsection

@section('headerCss')
	<!-- headerCss -->
	<!-- DataTables -->
	<link href="{{ URL::asset('/libs/rwd-table/rwd-table.min.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ URL::asset('/css/custom.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ URL::asset('/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
	
@endsection

@section('content')

	<!-- content -->

	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div class="row align-items-center">
						<div class="col-12 col-sm-12 col-xs-12">
							<h4 class="card-title">{{trans('form.client_approval.client_name_title_inv')}} : <span
									class="customer_name"><strong>{{ $user->name ?? '' }}</strong></span></h4>
						</div>
					</div>
					<hr/>
					<form action="{{ route('investigator.approve', $user->id) }}" method="POST" id="client_approve_form"
						  class="form needs-validation">
						@csrf
						<div class="row">
							<div class="col-12">
								<h4 class="section_title mb-3">{{trans('form.client_approval.investigator_price_list')}}</h4>
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 mb-2">
										<div class="enter_amount">
											<input type="number" name="unit_value" id="unit_value" class="form-control"
												   min="0" placeholder="0">
										</div>
									</div>
									{{--<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 mb-2">
										<div class="form-group mb-0">
											<select class="form-control decorated" id="conversion_unit"
													name="conversion_unit">
												<option>{{ trans('general.pleaseSelect') }}</option>
												<option value="percent">{{trans('form.conversion_unit.percent')}}</option>
												<option value="unit">{{trans('form.conversion_unit.unit')}}</option>
											</select>
										</div>
									</div>--}}
									<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 mb-2">
										<button type="button" class="btn btn-primary btn-block" id="apply_btn"
												onclick="calculateProductPrices()">{{ trans('general.apply') }}
										</button>
									</div>
								</div>
								<div class="table-rep-plugin">
									<div class="table-responsive mb-0" data-pattern="priority-columns">
										<table id="products" class="table  table-striped table-bordered">
											<thead>
											<tr>
												<th>{{trans('form.products_form.product')}}</th>
												<th data-priority="1">{{trans('form.client_approval.base_price')}}({{ trans('general.money_symbol')}})</th>
												<th data-priority="1">{{trans('form.client_approval.price')}}({{ trans('general.money_symbol')}})</th>
											</tr>
											</thead>
											<tbody>
											@foreach($products as $key => $value)
												<tr>
													<th>{{$value->name}}</th>
													<td>{{$value->price}}</td>
													<td>
														<input type="number" name="arr_product[{{$value->id}}]"
															   id="product_{{$value->id}}" class="offered_price"
															   value="{{isset($client_products[$value->id]['pivot']) ? sprintf('%0.2f', $client_products[$value->id]['pivot']['price']) : $value->price}}">
														<input type="hidden" name="product_base_price[{{$value->id}}]"
															   id="base_price_{{$value->id}}" value="{{$value->price}}">
														<input type="hidden" name="product_ids[]"
															   id="product_id_{{$value->id}}" value="{{$value->id}}">
													</td>
												</tr>
											@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>


							<div class="col-12">
								<div class="form-group row mt-4">
									<div class="col-12 text-right text-xs-center">
										<a href="{{ route('investigators') }}"
										   class="btn btn-secondary">{{trans('form.cancel')}}</a>
										<button type="submit"
												class="btn btn-primary">{{$user->status !== 'approved' ? trans('general.approve') : trans('form.save')}}
										</button>
									</div>
								</div>
							</div>

						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- end col -->
	</div>
	<!-- end row -->

@endsection

@section('footerScript')
	<!-- footerScript -->
	<!-- Required datatable js -->
	<!-- <script src="{{ URL::asset('/libs/datatables/datatables.min.js') }}"></script>
	<script src="{{ URL::asset('/libs/jszip/jszip.min.js') }}"></script>
	<script src="{{ URL::asset('/libs/pdfmake/pdfmake.min.js') }}"></script> -->
	<!-- Responsive Table js -->
	<script src="{{ URL::asset('/libs/rwd-table/rwd-table.min.js') }}"></script>
	<script src="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.js') }}" async></script>
	<script src="{{ URL::asset('/libs/select2/select2.min.js') }}"></script>
<script src="{{ URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

	<!-- Init js -->
	<script src="{{ URL::asset('/libs/jquery-validate/1.19.0/jquery.validate.js')}}"></script>  
	@if (App::isLocale('hr'))
    <script src="{{ URL::asset('/libs/jquery-validate/1.19.0/localization/messages_he.js')}}"></script>
    @endif
	<script>
		$(document).ready(function () {
			$('.userinquiry_dd').select2();

			$('.table-responsive').responsiveTable({
				addDisplayBtn: false,
				addDisplayAllBtn: false,
				addFocusBtn: false
			});
			$(".dropdown-btn-group").hide();

			$("#client_approve_form").validate({
				rules: {
					payment_mode_id: {
						required: true
					},
					payment_term_id: {
						required: true
					},
					credit_limit: {
						required: true,
					}
				},
				messages: {
					name: {
						required: "This field required"
					},
					price: {
						required: "This field required"
					}
				}
			});

			$("input[type=number]").on("focus", function () {
				$(this).on("keydown", function (event) {
					if (event.keyCode === 38 || event.keyCode === 40) {
						event.preventDefault();
					}
				});
			});
		});
		
		function calculateProductPrices() {
			var conversion_unit = $("#conversion_unit").val();
			var unit_value = $("#unit_value").val();
			if ($.isNumeric(unit_value)) {
				unit_value = parseFloat(unit_value);
				$('input[name$="product_ids[]"]').each(function (key, value) {
					// Calculate price from original base price
					var product_id = $(this).val();
					var basePrice = parseFloat($("#base_price_" + product_id).val());
					var newPrice = 0;
					newPrice = parseFloat(basePrice + unit_value);
					// if (conversion_unit == 'unit') {
					// 	newPrice = parseFloat(basePrice + unit_value);
					// }
					// if (conversion_unit == 'percent') {
					// 	newPrice = parseFloat(basePrice + ((unit_value * basePrice) / 100));
					// }
					$("#product_" + product_id).val(newPrice.toFixed(2))
				})
			}
		}

	
        function addeditModal(modelid,type,id,modeltype) {
            $.ajax({
                url: '{{ route("client.customerdata") }}' ,
                type: "GET",
                data: {
                   "_token": "{{ csrf_token() }}",
                   "type": type,
                   "modeltype": modeltype,
                   "id": id
                },
                success: function( response ) {
                    if(response.status==1){
                            $('#'+modelid).html(response.html);
                            showModal(modelid);  
                    }else{
                        $('#'+modelid).modal('hide');      
                    }
                }

            });
           
            }
            function showModal(id) {
                $('#'+id).modal('show');
            }
            function hideModel(id) {
                $('#'+id).modal('hide');
            }
			function deleteform(id,type,outputid,clientid){
            
            $.ajax({
                url: '{{ route("client.customerdata.update") }}' ,
                type: "POST",
                data: "id="+id+"&clientid="+clientid+"&type="+type+"&optype=delete&_token={{ csrf_token() }}",
                success: function( response ) {
                    if(response.status==1){
                            
                            Swal.fire(
								"{{trans('general.deleted_text')}}",
                                (response.msg) ? response.msg : "{{trans('general.successfully_delete')}}",
                                "{{ trans('general.success_text') }}"
                                )
                                $('#'+outputid).html(response.html);
                    }else{ 
                       Swal.fire(
								"{{trans('general.error_text')}}",
                                (response.msg) ? response.msg : "{{trans('general.error_text')}}",
                                "{{ trans('general.error_text') }}"
                            )    
                    }
                    
                }

            });
        }
        function updateform(id,type,outputid){
            if($("#"+id).valid() === true){
            $('.editbtn').text("{{ trans('general.updating') }}");
            $.ajax({
                url: '{{ route("client.customerdata.update") }}' ,
                type: "POST",
                data: $('#'+id).serialize()+ "&type="+type+"&optype=update",
                success: function( response ) {
                    if(response.status==1){
                            
                                $('.editbtn').text('{{ trans("general.update") }}');
                            Swal.fire(
                                "{{trans('general.updated_text')}}",
                                (response.msg) ? response.msg : "{{trans('general.successfully_update')}}",
                                "{{ trans('general.success_text') }}"
                            )
                            hideModel('customer_edit_model');
                           $('#'+outputid).html(response.html);
                    }else{ 
                       Swal.fire(
								"{{trans('general.error_text')}}",
                                (response.msg) ? response.msg : "{{trans('general.something_wrong')}}",
                                "{{trans('general.updated_text')}}"
                            )  
                            $('.editbtn').text('{{ trans("general.update") }}');  
                    }
                    
                }

            });
            }
        }
      
        function savecontactform(id,type,outputid){
            if($("#"+id).valid() === true){
                    $('.addbtn').text('{{ trans("general.adding") }}');
                    $.ajax({
                        url: '{{ route("client.customerdata.update") }}' ,
                        type: "POST",
                        data: $('#'+id).serialize()+ "&type="+type+"&optype=add",
                        
                        success: function( response ) {
                            if(response.status==1){
                                    
                                   $('.addbtn').text('{{ trans("general.save") }}');
                                    Swal.fire(
                                        "{{trans('general.added')}}",
                                        (response.msg) ? response.msg : "{{trans('general.successfully_added')}}",
                                        "{{ trans('general.success_text') }}"
                                    )
                                    hideModel('customer_add_model');
                                $('#'+outputid).html(response.html);
                            }else{ 
                            Swal.fire(
										"{{trans('general.error_text')}}",
                                        (response.msg) ? response.msg : "{{trans('general.something_wrong')}}",
                                        "{{trans('general.error_text')}}"
                                    )  
                                    $('.addbtn').text("{{ trans('general.add') }}");  
                            }
                            
                        }

                        });
            }    
	 }
	 $("body").on("click", ".delete-record", function(e){
                var id = $(this).data('id');
                var type = $(this).data('type');
                var outputid = $(this).data('outputid');       
				var clientid = $(this).data('clientid');    
                Swal.fire({
                    title: "{{ trans('general.confirm_delete') }}",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0CC27E',
                    cancelButtonColor: '#FF586B',
                    confirmButtonText: "{{ trans('general.yes_delete') }}",
                    cancelButtonText: "{{ trans('general.no_cancel') }}",
                    confirmButtonClass: 'btn btn-lg btn-success mr-5',
                    cancelButtonClass: 'btn btn-lg btn-danger',
                    buttonsStyling: false
                }).then((result) => {
                  if (result.value) {
                    deleteform(id,type,outputid,clientid);
                  } else if (result.dismiss == "cancel") {
                    
                  }
                })
            });
	</script>
@endsection