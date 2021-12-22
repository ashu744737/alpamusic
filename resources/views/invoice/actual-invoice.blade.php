@extends('layouts.master')

@section('title') {{ trans('form.paidperformainvoices') }} @endsection

@section('headerCss')
    <!-- headerCss -->
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/colReorder.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/dataTables.checkboxes.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 col-sm-4 col-xs-12">
                            <h4 class="card-title">{{ trans('form.paidperformainvoices') }}</h4>
                        </div>
                    </div>

                    @include('layouts.partials.session-message')

                    <div class="flash-message"></div>
                    
                    <div class="row">
                        <div class="col-12">

                            <table id="datatable_invoice" class="table table-bordered dt-responsive">
                                <thead>
                                <tr role="row">
                                    <th class="noVis"></th>
                                    <th>{{ trans('form.invoice.clientname') }}</th>
                                    <th>{{ trans('form.invoice.investigation') }}</th>
                                    <th>{{ trans('form.invoice.invoice_no') }}</th>
                                    <th>{{ trans('form.registration.investigation.paying_customer') }}</th>
                                    <th>{{ trans('form.registration.investigation.file_claim_number') }}</th>
                                    <th>{{ trans('form.registration.investigation.claim_number') }}</th>
                                    <th>{{ trans('form.invoice.amount') }}</th>
                                    <th class="noVis"></th>
                                    <th>{{ trans('form.invoice.status') }}</th>
                                    <th class="noVis">{{ trans('general.action') }}</th>
                                </tr>
                                </thead>
                            </table>

                        </div>
                    </div>

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
            
            cDTable = $('#datatable_invoice').DataTable({
                // dom: 'lBfrtip',
                'dom' : "<'row'<'col-sm-12 col-md-6'Bl><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                search:true,
                lengthChange: true,
                autoWidth: false,
                stateSave: true,
                processing: true,
                // serverSide: true,
                language:{
                    @if(config('app.locale') == 'hr')
                    url: '{{ URL::asset('/libs/datatables/json/Hebrew.json') }}'
                    @endif
                },
                ajax: "{{ route('invoice-list-ad') }}",
                columns: [
                    {data: 'id', name: 'check', orderable: false, searchable: false},
                    {data: 'client_name', name: 'client.user.name'},
                    {data: 'work_order_number', name: 'investigation.work_order_number'},
                    {data: 'invoice_no', name: 'invoice_no'},
                    {data: 'paying_customer', name: 'investigation.client_type.name'},
                    {data: 'external_file_claim', name: 'investigation.ex_file_claim_no'},
                    {data: 'claim_number', name: 'investigation.claim_number'},
                    {data: 'amount', name: 'amount'},
                    {data: 'created_at', name: 'created_at', searchable: false, visible: false},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                buttons: ['excel', 'pdf', { extend: 'colvis', columns: ':not(.noVis)'},
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
                order: [[8,'desc']],
            });

            cDTable.buttons().container().appendTo('#datatable_invoice_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection