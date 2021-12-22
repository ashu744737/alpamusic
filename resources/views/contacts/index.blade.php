@extends('layouts.master')

@section('title') {{ trans('form.contacts') }} @endsection

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
                            <h4 class="card-title">{{ trans('form.contacts') }}</h4>
                        </div>
                        @if(check_perm('contact_create'))
                        <div class="col-12 col-sm-8 col-xs-12 text-left text-sm-right mb-2">
                            <a href="{{ route('contactsCreate') }}" class="btn btn-primary w-md waves-effect waves-light add-new-btn">{{ trans('form.contact.add_contact') }}</a>
                        </div>
                        @endif
                    </div>

                    @include('session-message')
                    @if(check_perm('contact_show'))
                    <div class="row">
                        <div class="col-12">

                            <table id="datatable_clients" class="table table-bordered dt-responsive">
                                <thead>
                                <tr role="row">
                                    <th class="noVis"></th>
                                    <th>{{ trans('form.contact.name') }}</th>
                                    <th>{{ trans('form.contact.user_type') }}</th>
                                    <th>{{ trans('form.contact.contact_type') }}</th>
                                    <th>{{ trans('form.contact.workplace') }}</th>
                                    <th>{{ trans('form.contact.phone') }}</th>
                                    <th>{{ trans('form.contact.mobile') }}</th>
                                    <th>{{ trans('form.contact.fax') }}</th>
                                    <th>{{ trans('form.contact.email') }}</th>
                                    <th>{{ trans('form.contact.status') }}</th>
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
                ajax: "{{ route('get-contacts-list') }}",
                columns: [
                    {data: 'id', name: 'check', orderable: false, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'user_type', name: 'user_type'},
                    {data: 'contact_type', name: 'contact_type'},
                    {data: 'workplace', name: 'workplace'},
                    {data: 'phone', name: 'phone'},
                    {data: 'mobile', name: 'mobile'},
                    {data: 'fax', name: 'fax'},
                    {data: 'email', name: 'email'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                buttons: ['excel', 'pdf',
                @if(check_perm('contact_delete')){
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
                    url: "contacts/remove/" + record_id,
                    method: 'delete',
                    success: function(result) {
                        if (result.status == 'success') {
                            //$('#datatable_contacts').DataTable().destroy();
                            cDTable.draw();
                            Swal.fire({
                                title: "{{trans('general.deleted_text')}}",
                                text: (result.message) ? result.message : "{{trans('general.successfully_delete')}}",
                                type: "{{ trans('general.success_text') }}",
                                confirmButtonText: "{{ trans('general.ok') }}",
                            })

                        } else {
                            Swal.fire(
                                "{{trans('general.error_text')}}",
                                (result.message) ? result.message : "{{trans('general.something_wrong')}}",
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
                            url: "{{ route('contacts.multidelete') }}",
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
        });
    </script>
@endsection