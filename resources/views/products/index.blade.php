@extends('layouts.master')

@section('title') {{ trans('form.products') }} @endsection

@section('headerCss')
    <!-- headerCss -->
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/colReorder.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/dataTables.checkboxes.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.css') }}">
    @if(config('app.locale') == 'hr')
    <style>
        .dt-buttons{
            float: right;
        }
    </style>
    @endif
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 col-sm-4 col-xs-12">
                            <h4 class="card-title">{{ trans('form.products') }}</h4>
                        </div>
                        @if(check_perm('product_create'))
                        <div class="col-12 col-sm-8 col-xs-12 text-left text-sm-right mb-2">
                            <a href="{{ route('product.create') }}" class="btn btn-primary w-md waves-effect waves-light add-new-btn">{{ trans('form.products_form.add_product') }}</a>
                        </div>
                        @endif
                    </div>

                    @include('session-message')
                    @if(check_perm('product_show'))
                    <div class="row">
                        <div class="col-12">

                            <table id="datatable_clients" class="table table-bordered dt-responsive">
                                <thead>
                                <tr role="row">
                                    <th class="noVis text-left"></th>
                                    <th>{{ trans('form.products_form.product_name') }} </th>
                                    <th>{{ trans('form.products_form.price') }}({{ trans('general.money_symbol') }})</th>
                                    <th>{{ trans('form.products_form.spouse_cost') }}(%)</th>
                                    <th>{{ trans('form.products_form.is_del') }}</th>
                                    <th>{{ trans('form.products_form.del_cost') }}({{ trans('general.money_symbol') }})</th>
                                    <th>{{ trans('form.products_form.category') }}</th>
                                    <th class="noVis">{{ trans('form.action') }}</th>
                                </tr>
                                </thead>
                            </table>

                        </div>
                    </div>
                    @endif
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
    <script src="{{ URL::asset('/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/datatables/dataTables.colReorder.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/datatables/dataTables.checkboxes.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.js') }}" async></script>
    <script src="{{ URL::asset('/libs/pdfmake/vfs_fonts.js') }}"></script>
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
                    @if(config('app.locale') == 'hr')
                    url: '{{ URL::asset('/libs/datatables/json/Hebrew.json') }}'
                    @endif
                },
                ajax: "{{ route('product.getlist') }}",
                columns: [
                    {data: 'id', name: 'check'},
                    {data: 'name', name: 'name'},
                    {data: 'price', name: 'price'},
                    {data: 'spouse_cost', name: 'spouse_cost'},
                    {data: 'is_delivery', name: 'is_delivery'},
                    {data: 'delivery_cost', name: 'delivery_cost'},
                    {data: 'category', name: 'category.name'},
                    {data: 'action', name: 'action', orderable: true, searchable: true},
                ],                
                buttons: ['excel','pdf',
                @if(check_perm('product_delete')){
                        "text": '{{ trans("general.delete") }}',
                        action: function ( e, dt, node, config ) {
                            deleteSelectedRecords();
                        },
                },@endif
               {  extend: 'colvis', columns: ':not(.noVis)'},
             
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
                order: [[7,'desc']],
            });
        
            cDTable.buttons().container().appendTo('#datatable_clients_wrapper .col-md-6:eq(0)');

            $("body").on("click", ".delete-record", function(e){
                var id = $(this).data('id');
                var client_count = $(this).data('client-count');
                var title = parseInt(client_count) > 0 ? "{{ trans('form.products_form.confirm_product_delete') }}" : "{{ trans('general.confirm_delete') }}";
                Swal.fire({
                    title: title,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0CC27E',
                    cancelButtonColor: '#FF586B',
                    confirmButtonText: "{{trans('general.yes_delete')}}",
                    cancelButtonText: "{{trans('general.cancel')}}",
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
                    url: "products/remove/" + record_id,
                    method: 'delete',
                    success: function(result) {
                        if (result.status == 'success') {
                            cDTable.draw();
                            Swal.fire(
                                "{{ trans('general.deleted_text') }}",
                                (result.message) ? result.message : "{{ trans('general.successfully_delete') }}",
                                "{{ trans('general.success_text') }}"
                            )

                        } else {
                            Swal.fire(
                                "{{ trans('general.error_text') }}",
                                (result.message) ? result.message : "{{ trans('general.something_wrong') }}",
                                "{{ trans('general.error_text') }}"
                            )
                        }
                        $('.loading').addClass('d-none');
                    },
                    error: function(response) {
                        $('.invalidMessage').removeClass('alert-success');
                        $('.invalidMessage').addClass('alert-danger');
                        $('.invalidMessage').find('span.message-content').html(
                            'Something went wrong! Please try again later');
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
                        title: "{{ trans('general.confirm_delete') }}",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#0CC27E',
                        cancelButtonColor: '#FF586B',
                        confirmButtonText: "{{trans('general.yes_delete')}}",
                    cancelButtonText: "{{trans('general.cancel')}}",
                        confirmButtonClass: 'btn btn-lg btn-success mr-5',
                        cancelButtonClass: 'btn btn-lg btn-danger',
                        buttonsStyling: false
                    }).then((result) => {
                      if (result.value) {
                        $.each(rows_selected, function(index, rowId){
                            ids.push(rowId);
                        });
                        $.ajax({
                            url: "{{ route('product.multidelete') }}",
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
                      text: "{{ trans('general.no_row_selected') }}",
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
                    url: "internal-user/update-status",
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
@endsection