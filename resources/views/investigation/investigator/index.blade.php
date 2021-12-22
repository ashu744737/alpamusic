@extends('layouts.master')

@section('title') {{ trans('form.investigations') }} @endsection

@section('headerCss')
<!-- headerCss -->
<!-- DataTables -->
<link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('/libs/datatables/colReorder.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('/libs/datatables/dataTables.checkboxes.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('/css/custom.css') }}" rel="stylesheet" type="text/css" />
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

                <div class="row align-items-center">
                    <div class="col-12 col-sm-4 col-xs-12">
                        <h4 class="card-title">{{ trans('form.investigations') }}</h4>
                    </div>
                </div>
                <!-- <hr /> -->
                @include('layouts.partials.session-message')

                <div class="flash-message"></div>

                <div class="row">
                    <div class="col-12">
                        <button class="btn collapse-toggle hidden-lg hidden-md btn-block mobile_drop" type="button"
                            data-toggle="collapse" data-target="#investigation_status" aria-haspopup="true"
                            aria-expanded="false">
                            <span class="text-uppercase">
                                <div class="selected_nav">
                                    <span class="tab_title">All</span>
                                    <span class="badge badge-light count-all">{{ $invCounts['All'] }}</span>
                                </div>
                                <span class="caret"></span>
                            </span>
                        </button>
                        <div class="list-group collapse" id="investigation_status">
                            <!-- Nav tabs -->
                            <ul class="nav nav-pills nav-justified investigation_status mt-lg-2 py-lg-3 mt-0 py-0"
                                role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#inv_all" role="tab" onclick="changeDTTab('datatable-investigation-all', '')">
                                        <span class="tab_title">{{ trans('form.investigator_investigation_status.All') }}</span>
                                        <span class="badge badge-light count-all">{{ $invCounts['All'] ?? '0' }}</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#inv_assigned" role="tab" onclick="changeDTTab('datatable-investigation-assigned', 'Assigned')">
                                        <span class="tab_title">{{ trans('form.investigator_investigation_status.Assigned') }}</span>
                                        <span class="badge badge-light count-assigned">{{ ($invCounts['Assigned']) ?? '0' }}</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#inv_inprogress" role="tab" onclick="changeDTTab('datatable-investigation-inprogress', 'In Progress')">
                                        <span class="tab_title">{{ trans('form.investigator_investigation_status.InProgress') }}</span>
                                        <span class="badge badge-light count-inprogress">{{ ($invCounts['In Progress']) ?? '0' }}</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#inv_rsubmitted" role="tab" onclick="changeDTTab('datatable-investigation-rsubmitted', 'Report Submitted')">
                                        <span class="tab_title">{{ trans('form.investigator_investigation_status.ReportSubmitted') }}</span>
                                        <span class="badge badge-light count-rsubmitted">{{ $invCounts['Report Submitted'] ?? '0' }}</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#inv_raccepted" role="tab" onclick="changeDTTab('datatable-investigation-raccepted', 'Report Accepted')">
                                        <span class="tab_title">{{ trans('form.investigator_investigation_status.ReportAccepted') }}</span>
                                        <span class="badge badge-light count-raccepted">{{ $invCounts['Report Accepted'] ?? '0' }}</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#inv_declined" role="tab" onclick="changeDTTab('datatable-investigation-declined', 'Investigation Declined')">
                                        <span class="tab_title">{{ trans('form.investigator_investigation_status.Declined') }}</span>
                                        <span class="badge badge-light count-declined">{{ $invCounts['Investigation Declined'] ?? '0' }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Tab panes -->
                        <div class="tab-content pt-3 pt-md-0 pt-lg-0">
                            <div class="tab-pane active p-3" id="inv_all" role="tabpanel">
                                <table id="datatable-investigation-all"
                                    class="table table-striped table-bordered dt-responsive"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <th class="noVis"></th>
                                        <th>{{ trans('form.registration.investigation.work_order_number') }}</th>
                                        <th>{{ trans('form.registration.investigation.req_type_inquiry') }}</th>
                                        <th style="text-align:center">{{ trans('general.status') }}</th>
                                        <th>{{ trans('general.created_at') }}</th>
                                        <th class="noVis">{{ trans('general.status') }}</th>
                                        <th class="noVis">{{ trans('general.action') }}</th>
                                        <th class="noVis">Product Name</th>
                                        <th class="noVis">Family Name</th>
                                    </thead>
                                </table>
                            </div>

                            <div class="tab-pane p-0 p-md-3 p-lg-3" id="inv_assigned" role="tabpanel">
                                <table id="datatable-investigation-assigned"
                                       class="table table-striped table-bordered dt-responsive"
                                       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                    <th class="noVis"></th>
                                    <th>{{ trans('form.registration.investigation.work_order_number') }}</th>
                                    <th>{{ trans('form.registration.investigation.req_type_inquiry') }}</th>
                                    <th style="text-align:center">{{ trans('general.status') }}</th>
                                    <th>{{ trans('general.created_at') }}</th>
                                    <th class="noVis">{{ trans('general.status') }}</th>
                                    <th class="noVis">{{ trans('general.action') }}</th>
                                    <th class="noVis">Product Name</th>
                                    <th class="noVis">Family Name</th>
                                    </thead>
                                </table>
                            </div>

                            <div class="tab-pane p-0 p-md-3 p-lg-3" id="inv_inprogress" role="tabpanel">
                                <table id="datatable-investigation-inprogress"
                                       class="table table-striped table-bordered dt-responsive"
                                       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <th class="noVis"></th>
                                        <th>{{ trans('form.registration.investigation.work_order_number') }}</th>
                                        <th>{{ trans('form.registration.investigation.req_type_inquiry') }}</th>
                                        <th style="text-align:center">{{ trans('general.status') }}</th>
                                        <th>{{ trans('general.created_at') }}</th>
                                        <th class="noVis">{{ trans('general.status') }}</th>
                                        <th class="noVis">{{ trans('general.action') }}</th>
                                        <th class="noVis">Product Name</th>
                                        <th class="noVis">Family Name</th>
                                    </thead>
                                </table>
                            </div>

                            <div class="tab-pane p-3" id="inv_rsubmitted" role="tabpanel">
                                <table id="datatable-investigation-rsubmitted"
                                       class="table table-striped table-bordered dt-responsive"
                                       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <th class="noVis"></th>
                                        <th>{{ trans('form.registration.investigation.work_order_number') }}</th>
                                        <th>{{ trans('form.registration.investigation.req_type_inquiry') }}</th>
                                        <th style="text-align:center">{{ trans('general.status') }}</th>
                                        <th>{{ trans('general.created_at') }}</th>
                                        <th class="noVis">{{ trans('general.status') }}</th>
                                        <th class="noVis">{{ trans('general.action') }}</th>
                                        <th class="noVis">Product Name</th>
                                        <th class="noVis">Family Name</th>
                                    </thead>
                                </table>
                            </div>

                            <div class="tab-pane p-3" id="inv_raccepted" role="tabpanel">
                                <table id="datatable-investigation-raccepted"
                                       class="table table-striped table-bordered dt-responsive"
                                       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <th class="noVis"></th>
                                        <th>{{ trans('form.registration.investigation.work_order_number') }}</th>
                                        <th>{{ trans('form.registration.investigation.req_type_inquiry') }}</th>
                                        <th style="text-align:center">{{ trans('general.status') }}</th>
                                        <th>{{ trans('general.created_at') }}</th>
                                        <th class="noVis">{{ trans('general.status') }}</th>
                                        <th class="noVis">{{ trans('general.action') }}</th>
                                        <th class="noVis">Product Name</th>
                                        <th class="noVis">Family Name</th>
                                    </thead>
                                </table>
                            </div>

                            <div class="tab-pane p-3" id="inv_declined" role="tabpanel">
                                <table id="datatable-investigation-declined"
                                       class="table table-striped table-bordered dt-responsive"
                                       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <th class="noVis"></th>
                                        <th>{{ trans('form.registration.investigation.work_order_number') }}</th>
                                        <th>{{ trans('form.registration.investigation.req_type_inquiry') }}</th>
                                        <th style="text-align:center">{{ trans('general.status') }}</th>
                                        <th>{{ trans('general.created_at') }}</th>
                                        <th class="noVis">{{ trans('general.status') }}</th>
                                        <th class="noVis">{{ trans('general.action') }}</th>
                                        <th class="noVis">Product Name</th>
                                        <th class="noVis">Family Name</th>
                                    </thead>
                                </table>
                            </div>
                        </div>
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

<script>
    var DTable = null;
    var dtSelectedId = "datatable-investigation-all";
    var investigatorId = null;

    $(document).ready(function () {
        // All Datatable
        var iaDTable = $('#datatable-investigation-all').DataTable({
            // dom: 'lBfrtip',
            'dom' : "<'row'<'col-sm-12 col-md-9'lB><'col-sm-12 col-md-3'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
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
            ajax: "{{ route('investigator-investigation-list') }}",
            columns: [
                {data: 'id', name: 'check', orderable: false, searchable: false},
                {data: 'work_order_number', name: 'investigation.work_order_number'},
                {data: 'inquiry', name: 'type_of_inquiry'},
                {data: 'status', name: 'status'},
                {data: 'created_at', name: 'created_at', searchable: false},
                {data: 'status_hr', name: 'status_hr', visible: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
                {data: 'product_name', name: 'investigation.product.name', orderable: false, visible: false,},
                {data: 'family_name', name: 'investigation.subjects.family_name', orderable: false, visible: false,},
            ],
            buttons: [
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ":not(.noVis)"
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: ":not(.noVis)"
                    }
                },
                { extend: 'colvis', columns: ':not(.noVis)'},
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
            order: [[4,'desc']],
        });
        
        iaDTable.buttons().container().appendTo('#datatable-investigation-all_wrapper .col-md-6:eq(0)');

        // All Datatable End

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust()
                .responsive.recalc();
        });

        // List Group
        $(".list-group a").on('click', function (e) {
            $(this).closest('.list-group').removeClass('show');
            //e.stopPropagation();
        });


        //Check for url parameters & default select tab when redirected from Investigators listing

        var loc = window.location.href;
        var index = loc.indexOf("?");
        var params = loc.substr(index+1).split('&');

        if ($.isArray(params)) {
            var status = null;

            for (var i = 0; i < params.length; i++) {
                var nameArr = params[i].split('=');
                if (nameArr[0] == 'status') {
                    status = nameArr[1];
                }
                if (nameArr[0] == 'investigator_id') {
                    investigatorId = nameArr[1];
                }
                if (i == params.length - 1) {
                    if (status == 'Assigned') {
                        $("a[href='#inv_open']").click();
                    }
                    if (status == 'Returned To Center') {
                        $("a[href='#inv_inprogress']").click();
                    }
                }
            }
        }

        //Check for url parameters & default select tab when redirected from Investigators listing
    });

    function changeDTTab(selectedId, status){
        console.log('in change', selectedId, status);
        dtSelectedId = selectedId;

        if ( ! $.fn.DataTable.isDataTable( '#'+selectedId ) ) {
            var url = '{{ route("investigator-investigation-list", ":status") }}';
            url = url.replace(':status', status);

            // Datatable Init
            var DTable = $('#'+selectedId).DataTable({
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
                ajax: {
                    "url": url,
                    "type": "GET",
                    data: {
                        'investigator_id': investigatorId
                    },
                },
                columns: [
                    {data: 'id', name: 'check', orderable: false, searchable: false},
                    {data: 'work_order_number', name: 'investigation.work_order_number'},
                    {data: 'inquiry', name: 'type_of_inquiry'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at', searchable: false},
                    {data: 'status_hr', name: 'status_hr', visible: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                    {data: 'product_name', name: 'investigation.product.name', orderable: false, visible: false,},
                    {data: 'first_name', name: 'investigation.subjects.first_name', orderable: false, visible: false,},
                    {data: 'family_name', name: 'investigation.subjects.family_name', orderable: false, visible: false,},
                ],
                buttons: [
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ":not(.noVis)"
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ":not(.noVis)"
                        }
                    },
                    { extend: 'colvis', columns: ':not(.noVis)'},
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
                order: [[4,'desc']],
            });
            DTable.buttons().container().appendTo('#'+selectedId+'_wrapper .col-md-6:eq(0)');
        }else{
            $('#'+selectedId).DataTable().ajax.reload();
        }

    }

</script>
@endsection