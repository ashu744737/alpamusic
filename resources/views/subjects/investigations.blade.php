@extends('layouts.master')

@section('title') {{ trans('form.investigations') }} @endsection

@section('headerCss')
    <!-- headerCss -->
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/colReorder.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/dataTables.checkboxes.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.css') }}">

@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 col-sm-4 col-xs-12">
                            <h4 class="card-title">{{ trans('form.investigations') }}</h4>
                        </div>
                       
                    </div>
                    @include('session-message')
                    @if(check_perm('investigation_show'))
                    <div class="row">
                        <div class="col-12">

                            <table id="datatable_sub_inv" class="table table-bordered dt-responsive">
                                <thead>
                                <tr role="row">
                                    <th class="noVis"></th>
                                    <th>{{ trans('form.registration.investigation.user_inquiry') }}</th>
                                    <th>{{ trans('form.registration.investigation.file_claim_number') }}</th>
                                    <th>{{ trans('form.registration.investigation.claim_number') }}</th>
                                    <th>{{ trans('form.registration.investigation.paying_customer') }}</th>
                                    <th>{{ trans('form.registration.investigation.work_order_number') }}</th>
                                    <th>{{ trans('form.registration.investigation.req_type_inquiry') }}</th>
                                    <th>{{ trans('form.ticket.subject') }}</th>
                                    <th style="text-align:center">{{ trans('general.status') }}</th>
                                    <th>{{ trans('general.created_at') }}</th>
                                    <th class="noVis">{{ trans('general.status') }}</th>
                                    <th class="noVis">{{ trans('general.action') }}</th>
                                    <th class="noVis">{{ trans('form.products_form.product_name') }}</th>
                                    <th class="noVis">{{ trans('form.registration.investigation.firstname') }}</th>
                                    <th class="noVis">{{ trans('form.registration.investigation.family') }}</th>
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
            var cDTable = $('#datatable_sub_inv').DataTable({
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
                ajax: "{{ route('get-subject-investigation-list', ['subjectId' => $id]) }}",
                columns: [
                    {data: 'id', name: 'check', orderable: false, searchable: false},
                    {data: 'user_inquiry', name: 'user_inquiry'},
                    {data: 'ex_file_claim_no', name: 'ex_file_claim_no'},
                    {data: 'claim_number', name: 'claim_number'},
                    {data: 'paying_customer_name', name: 'paying_customer_name', searchable: false},
                    {data: 'work_order_number', name: 'work_order_number'},
                    {data: 'inquiry', name: 'type_of_inquiry'},
                    {data: 'subject_name', name: 'subject_name'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at', searchable: false},
                    {data: 'status_hr', name: 'status_hr', visible: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                    {data: 'product_name', name: 'product.name', orderable: false, visible: false,},
                    {data: 'first_name', name: 'subjects.first_name', orderable: false, visible: false,},
                    {data: 'family_name', name: 'subjects.family_name', orderable: false, visible: false,},
                ],
                buttons: ['excel', 'pdf', { extend: 'colvis', columns: ':not(.noVis)'},],
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
                order: [[9,'desc']],
            });
           
            cDTable.buttons().container().appendTo('#datatable_sub_inv_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection