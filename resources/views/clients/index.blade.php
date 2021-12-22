@extends('layouts.master')

@section('title') {{ trans('form.clients') }} @endsection

@section('headerCss')
    <!-- headerCss -->
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/colReorder.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/dataTables.checkboxes.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
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
                            <h4 class="card-title">{{ trans('form.clients') }}</h4>
                        </div>
                        @if(check_perm('client_create'))
                        <div class="col-12 col-sm-8 col-xs-12 text-left text-sm-right mb-2">
                            <a href="{{ route('client.create') }}" class="btn btn-primary w-md waves-effect waves-light add-new-btn">{{ trans('form.registration.client.add_client') }}</a>
                        </div>
                        @endif
                    </div>

                    @include('layouts.partials.session-message')

                    <div class="flash-message"></div>
                    @if(check_perm('client_show'))
                    <div class="row">
                        <div class="col-12">

                            <table id="datatable_clients" class="table table-bordered dt-responsive">
                                <thead>
                                <tr role="row">
                                    <th class="noVis"></th>
                                    <th>{{ trans('form.registration.client.name') }}</th>
                                    <th>{{ trans('form.registration.client.email') }}</th>
                                    <th>{{ trans('form.registration.client.customer_type') }}</th>
                                    <th>{{ trans('form.registration.client.printable_name') }}</th>
                                    <th>{{ trans('form.registration.client.legal_entity_id') }}</th>
                                    <th>{{ trans('form.registration.client.website') }}</th>
                                    <th>{{ trans('form.registration.client.phone') }}</th>
                                    <th>{{ trans('form.registration.client.address') }}</th>
                                    <th>{{ trans('general.status') }}</th>
                                    <th class="noVis">{{ trans('general.action') }}</th>
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
        var cDTable = null;

        $(document).ready(function() {
            
            cDTable = $('#datatable_clients').DataTable({
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
                ajax: "{{ route('client-list') }}",
                columns: [
                    {data: 'id', name: 'check', orderable: false, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'type_name', name: 'client.client_type.type_name'},
                    {data: 'printname', name: 'client.printname'},
                    {data: 'legal_entity_no', name: 'client.legal_entity_no'},
                    {data: 'website', name: 'client.website'},
                    {data: 'phone', name: 'userPhones.value'},
                    {data: 'address', name: 'userAddresses.address1'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                    {data: 'created_at', name: 'created_at', searchable: false, visible: false},
                ],
                buttons: ['excel', 'pdf',
                @if(check_perm('client_delete')){
                        "text": '{{ trans("general.delete") }}',
                        action: function ( e, dt, node, config ) {
                            deleteSelectedRecords();
                        },
                },@endif
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
            
            cDTable.buttons().container().appendTo('#datatable_clients_wrapper .col-md-6:eq(0)');

            $("body").on("click", "#deleteClient", function(){
                var id = $(this).data("id");

                Swal.fire({
                    title: "{{trans('general.are_you_sure')}}",
                    text: "{{trans('form.registration.client.confirm_delete')}}",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#34c38f",
                    cancelButtonColor: "#f46a6a",
                    confirmButtonText: "{{trans('general.yes_delete')}}",
                    cancelButtonText: "{{trans('general.cancel')}}"
                }).then(function (result) {
                    if (result.value){
                        $.ajax(
                            {
                                url: "/clients/" + id, //or you can use url: "company/"+id,
                                type: 'DELETE',
                                // type: "post",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    id: id,
                                    // _method: 'delete',
                                },
                                success: function (response){
                                    if (response.status == 'success') {
                                        Swal.fire({
                                            title: "{{trans('general.deleted_text')}}",
                                            text: (result.message) ? result.message : "{{trans('form.registration.client.client_deleted')}}", 
                                            type: "success",
                                            confirmButtonText: "{{ trans('general.ok') }}",
                                        });
                                        cDTable.draw();
                                    } else if(response.status == 'warning') {
                                        Swal.fire({
                                            title: "",
                                            text: (result.message) ? result.message : "{{trans('form.registration.client.no_client_deleted')}}", 
                                            type: "warning",
                                            confirmButtonText: "{{ trans('general.ok') }}",
                                        });
                                    } else {
                                        Swal.fire("{{trans('general.error_text')}}",(result.message) ? result.message : "{{trans('general.something_wrong')}}", "error");
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

            var rows_selected = cDTable.column(0).checkboxes.selected();
            // var rows_selected = cDTable.columns().checkboxes.selected()[0];
            var ids = [];

            if(rows_selected.length > 0){
                Swal.fire({
                    title: "{{trans('general.are_you_sure')}}",
                    text: "{{trans('form.registration.client.confirm_delete')}}",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#34c38f",
                    cancelButtonColor: "#f46a6a",
                    confirmButtonText: "{{trans('general.yes_delete')}}",
                    cancelButtonText: "{{trans('general.cancel')}}"
                }).then(function (result) {
                    if (result.value){
                        // Iterate over all selected checkboxes
                        $.each(rows_selected, function(index, rowId){
                            ids.push(rowId);
                        });

                        console.log("ids", ids);

                        $.ajax(
                            {
                                url: "{{ route('multidelete-clients') }}",
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    "ids": ids,
                                    "_token": "{{ csrf_token() }}",
                                },
                                success: function (response){
                                    if (response.status == 'success') {
                                        Swal.fire({
                                            title: "{{trans('general.deleted_text')}}",
                                            text: (result.message) ? result.message : "{{trans('form.registration.client.client_deleted')}}", 
                                            type: "success",
                                            confirmButtonText: "{{ trans('general.ok') }}",
                                        });
                                        cDTable.draw();
                                    } else {
                                        Swal.fire("{{trans('general.error_text')}}",(result.message) ? result.message : "{{trans('general.something_wrong')}}", "error");
                                    }
                                }
                            });
                    }else{
                        Swal.close();
                    }
                });
            }else{
                Swal.fire("{{trans('general.error_text')}}", "{{ trans('general.no_row_selected') }}", 'error');
            }
        }

        function changeStatus(action, id){

            var url = "{{ route('clients.change-status') }}";

            Swal.fire({
                title: "{{trans('general.are_you_sure')}}",
                text: "{{trans('form.registration.client.confirm_changestatus')}}",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#34c38f",
                cancelButtonColor: "#f46a6a",
                confirmButtonText: "{{trans('general.yes_change')}}",
                cancelButtonText: "{{trans('general.cancel')}}"
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
                                "_token": "{{ csrf_token() }}",
                            },
                            success: function (response){
                                if (response.status == 'success') {
                                    Swal.fire({
                                        title: "{{ trans('general.changed_text') }}",
                                        text: (result.message) ? result.message : "{{trans('form.registration.client.confirm_statuschanged')}}", 
                                        type: "success",
                                        confirmButtonText: "{{ trans('general.ok') }}",
                                    });
                                    cDTable.draw();
                                } else {
                                    Swal.fire("{{trans('general.error_text')}}",(result.message) ? result.message : "{{trans('general.something_wrong')}}", "error");
                                }
                            }
                        });
                }else{
                    Swal.close();
                }
            });
        }

    </script>
@endsection