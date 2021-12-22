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
                        <h4 class="card-title">{{ trans('form.titles') }}</h4>
                    </div>
                    @if(!isAccountant() && !isSecretary())
                    <div class="col-12 col-sm-8 col-xs-12 text-left text-sm-right mb-3">
                        <a href="{{ route('investigation.create') }}" class="btn btn-primary w-md add-new-btn">{{ trans('form.registration.investigation.add_investigation') }}</a>
                    </div>
                    @endif
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

                        {{-- <div class="list-group collapse" id="investigation_status">
                            <!-- Nav tabs -->
                            <ul class="nav nav-pills nav-justified investigation_status mt-lg-2 py-lg-3 mt-0 py-0"
                                role="tablist">

                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#inv_all" role="tab" onclick="changeDTTab('datatable-investigation-all', '')">
                                        <span class="tab_title">{{ trans('form.investigation_status.All') }}</span>
                                        <span class="badge badge-light count-all">{{ $invCounts['All'] ?? '0' }}</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#inv_pendingapproval" role="tab" onclick="changeDTTab('datatable-investigation-pendingapproval', 'Pending Approval')">
                                        <span class="tab_title">{{ trans('form.investigation_status.PendingApproval') }}</span>
                                        <span class="badge badge-light count-pendingapproval">{{ $invCounts['Pending Approval'] ?? '0' }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#inv_open" role="tab" onclick="changeDTTab('datatable-investigation-open', 'Open')">
                                        <span class="tab_title">{{ trans('form.investigation_status.Open') }}</span>
                                        <span class="badge badge-light count-open">{{ $invCounts['Open'] ?? '0' }}</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#inv_started" role="tab" onclick="changeDTTab('datatable-investigation-istarted', 'In Progress')">
                                        <span class="tab_title">{{ trans('form.investigator_investigation_status.InProgress') }}</span>
                                        <span class="badge badge-light count-istarted">{{ $invCounts['In Progress'] ?? '0' }}</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#inv_report" role="tab" onclick="changeDTTab('datatable-investigation-report', 'In Report')">
                                        <span class="tab_title">{{ trans('form.investigation_status.InReport') }}</span>
                                        <span class="badge badge-light count-report">{{ $invCounts['In Report'] ?? '0' }}</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#inv_printing" role="tab" onclick="changeDTTab('datatable-investigation-printing', 'Printing')">
                                        <span class="tab_title">{{ trans('form.investigation_status.Printing') }}</span>
                                        <span class="badge badge-light count-printing">{{ $invCounts['Printing'] ?? '0' }}</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#inv_sendclient" role="tab" onclick="changeDTTab('datatable-investigation-sendclient', 'Send To Client')">
                                        <span class="tab_title">{{ trans('form.investigation_status.SendToClient') }}</span>
                                        <span class="badge badge-light count-sendclient">{{ $invCounts['Send To Client'] ?? '0' }}</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#inv_closed" role="tab" onclick="changeDTTab('datatable-investigation-closed', 'Closed')">
                                        <span class="tab_title">{{ trans('form.investigation_status.Closed') }}</span>
                                        <span class="badge badge-light count-closed">{{ $invCounts['Closed'] ?? '0' }}</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#inv_late" role="tab" onclick="changeDTTab('datatable-investigation-late', 'Late')">
                                        <span class="tab_title">{{ trans('form.investigation_status.Late') }}</span>
                                        <span class="badge badge-light count-late">{{ $invCounts['Late'] ?? '0' }}</span>
                                    </a>
                                </li>

                            </ul>
                        </div> --}}

                        <!-- Tab panes -->
                        <div class="tab-content pt-3 pt-md-0 pt-lg-0">

                            <div class="tab-pane active p-3" id="inv_all" role="tabpanel">
                                <table id="datatable-investigation-all"
                                    class="table table-striped table-bordered dt-responsive"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
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
                                    </thead>
                                </table>
                            </div>

                            <div class="tab-pane p-0 p-md-3 p-lg-3" id="inv_open" role="tabpanel">
                                <table id="datatable-investigation-open"
                                       class="table table-striped table-bordered dt-responsive"
                                       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
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
                                    </thead>
                                </table>
                            </div>

                            <div class="tab-pane p-0 p-md-3 p-lg-3" id="inv_pendingapproval" role="tabpanel">
                                <table id="datatable-investigation-pendingapproval"
                                       class="table table-striped table-bordered dt-responsive"
                                       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
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
                                    </thead>
                                </table>
                            </div>

                            <div class="tab-pane p-3" id="inv_started" role="tabpanel">
                                <table id="datatable-investigation-istarted"
                                       class="table table-striped table-bordered dt-responsive"
                                       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
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
                                    </thead>
                                </table>
                            </div>

                            <div class="tab-pane p-3" id="inv_report" role="tabpanel">
                                <table id="datatable-investigation-report"
                                       class="table table-striped table-bordered dt-responsive"
                                       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
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
                                    </thead>
                                </table>
                            </div>

                            <div class="tab-pane p-3" id="inv_printing" role="tabpanel">
                                <table id="datatable-investigation-printing"
                                       class="table table-striped table-bordered dt-responsive"
                                       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
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
                                    </thead>
                                </table>
                            </div>

                            <div class="tab-pane p-3" id="inv_sendclient" role="tabpanel">
                                <table id="datatable-investigation-sendclient"
                                       class="table table-striped table-bordered dt-responsive"
                                       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
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
                                    </thead>
                                </table>
                            </div>

                            <div class="tab-pane p-3" id="inv_closed" role="tabpanel">
                                <table id="datatable-investigation-closed"
                                       class="table table-striped table-bordered dt-responsive"
                                       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
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
                                    </thead>
                                </table>
                            </div>

                            <div class="tab-pane p-3" id="inv_late" role="tabpanel">
                                <table id="datatable-investigation-late"
                                       class="table table-striped table-bordered dt-responsive"
                                       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
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
<script src="{{ URL::asset('/libs/pdfmake/vfs_fonts.js') }}"></script>
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
            ajax: "{{ route('investigation-list') }}",
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
                {
                    text: '{{ trans("general.delete") }}',
                    action: function ( e, dt, node, config ) {
                        deleteSelectedRecords(dt);
                    }
                },
                { extend: 'colvis', columns: ':not(.noVis)'},
                {
                    extend: 'collection',
                    text: '{{ trans("general.change_status") }}',
                    className: 'change-status',
                    autoClose: true,
                    buttons: [
                        { 
                            text: "{{trans('form.investigation_status.Approve')}}",
                            action: function (e, dt, node, config) {
                                changeInvestigationsStatus(dt, 'Approved')
                            } 
                        },
                        { 
                            text: '{{ trans("form.investigation_status.ModificationRequired") }}',
                            action: function (e, dt, node, config) {
                                changeInvestigationsStatus(dt, 'Modification Required')
                            } 
                        },
                        { 
                            text: '{{ trans("form.investigation_status.Closed") }}',
                            action: function (e, dt, node, config) {
                                changeInvestigationsStatus(dt, 'Closed')
                            } 
                        },
                        { 
                            text: '{{ trans("form.investigation_status.Cancelled") }}',
                            action: function (e, dt, node, config) {
                                changeInvestigationsStatus(dt, 'Cancelled')
                            } 
                        }
                    ],
                    fade: true,
                    dropup: true
                },
                {{--{--}}
                {{--    text: '{{ trans("form.investigation.assign_investigator") }}',--}}
                {{--    className: 'assign-investigator',--}}
                {{--    action: function ( e, dt, node, config ) {--}}
                {{--        assignInvestigator(dt);--}}
                {{--    }--}}
                {{--}--}}
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
            order: [[9,'desc']],
        });
        let user = "{{ config('constants.user_type') }}";
        let permition = "{{ env('USER_TYPE_ADMIN')  }}"
        let smPermition = "{{ env('USER_TYPE_STATION_MANAGER')  }}"
        
        // if(user != permition || user != smPermition){
        @if((!isAdmin()) && (!isSM()))
            $('.dt-buttons.btn-group').css('display', '-webkit-inline-box');
            iaDTable.buttons('.assign-investigator').nodes().css("display", "none");
            iaDTable.buttons('.change-status').nodes().css("visibility", "hidden");
        @endif
        
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

        //Single record delete
        $("body").on("click", "#deleteInvestigation", function(){
            var id = $(this).data("id");
            console.log('id ', id);
            Swal.fire({
                title: "{{trans('general.are_you_sure')}}",
                text: "{{trans('form.registration.investigation.confirm_delete')}}",
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
                            url: "/investigations/" + id,
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: id,
                            },
                            success: function (response){
                                if (response.status == 'success') {
                                    Swal.fire({
                                        title: "{{trans('general.deleted_text')}}",
                                        text: (result.message) ? result.message : "{{trans('form.registration.investigation.investigation_deleted')}}", 
                                        type: "success",
                                        confirmButtonText: "{{ trans('general.ok') }}",
                                    });
                                    console.log(dtSelectedId);
                                    $('#'+dtSelectedId).DataTable().draw();
                                    changeCounts();
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

        //Redirect to create duplicate investigation
        $("body").on("click", "#duplicateInvestigation", function(){
            var id = $(this).data("id");
            console.log('id ', id);
            Swal.fire({
                title: "{{trans('general.are_you_sure')}}",
                text: "{{trans('form.registration.investigation.confirm_duplicate')}}",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#34c38f",
                cancelButtonColor: "#f46a6a",
                confirmButtonText: "{{trans('general.yes_duplicate')}}",
                cancelButtonText: "{{trans('general.cancel')}}"
            }).then(function (result) {
                if (result.value){
                    window.location.href = "/investigations/duplicate/" + id;
                   
                }else{
                    Swal.close();
                }
            });

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
                    if (status == 'Open') {
                        $("a[href='#inv_open']").click();
                    }
                    if (status == 'Assigned') {
                        $("a[href='#inv_open']").click();
                    }
                    if (status == 'Delivered') {
                        $("a[href='#inv_delivered']").click();
                    }
                }
            }
        }

        //Check for url parameters & default select tab when redirected from Investigators listing
    });

    function deleteSelectedRecords(dt){

        var ids = [];
        var rows_selected = dt.column(0).checkboxes.selected();
        console.log('rows_selected ', rows_selected);

        if(rows_selected.length > 0){
            Swal.fire({
                title: "{{ trans('general.are_you_sure') }}",
                text: "{{trans('form.registration.investigation.confirm_delete')}}",
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

                    console.log("ids to pass ", ids);

                    $.ajax(
                        {
                            url: "{{ route('multidelete-investigation') }}",
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                "ids": ids,
                                "_token": "{{ csrf_token() }}",
                            },
                            success: function (response){
                                if (response.status == 'success') {
                                    Swal.fire({
                                        title: '{{ trans("general.deleted") }}',
                                        text: (result.message) ? result.message : "{{trans('form.registration.investigation.investigation_deleted')}}", 
                                        type: "success",
                                        confirmButtonText: "{{ trans('general.ok') }}",
                                    });
                                    $('#'+dtSelectedId).DataTable().draw();
                                    changeCounts();
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
            Swal.fire({
                title: "{{trans('general.error_text')}}", 
                text: "{{ trans('general.no_row_selected') }}", 
                type: 'error',
                confirmButtonText: "{{trans('general.ok')}}"
            });
        }
    }

    function changeInvestigationsStatus(dt, status) {
        var ids = [];
        
        $('div.dt-button-collection div.dropdown-menu').css('display', 'none!important');
        
        var rows_selected = dt.column(0).checkboxes.selected();
        if(rows_selected.length > 0){
            Swal.fire({
                title: "{{ trans('general.are_you_sure') }}",
                text: "{{ trans('form.registration.investigation.confirm_changestatus') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#34c38f",
                cancelButtonColor: "#f46a6a",
                confirmButtonText: "{{trans('general.yes_change')}}",
                cancelButtonText: "{{trans('general.cancel')}}"
            }).then(function (result) {
                if (result.value){
                    $.each(rows_selected, function(index, rowId){
                        ids.push(rowId);
                    });
                    console.log("ids to pass ", ids);
                    $.ajax({
                        url: "{{ route('investigation.bulk-change-status') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            "ids": ids,
                            "status": status,
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function (response){
                            if (response.status == 'success') {
                                Swal.fire({
                                    title: "",
                                    text: response.message,
                                    confirmButtonText: "{{ trans('general.ok') }}",
                                });
                                $('#'+dtSelectedId).DataTable().draw();
                                changeCounts();
                            } else {
                                Swal.fire({
                                    title: "{{trans('general.error_text')}}",
                                    text: (response.message) ? response.message : "{{trans('general.something_wrong')}}",
                                    type: "error",
                                    confirmButtonText: "{{ trans('general.ok') }}",
                                });
                            }
                        },
                        error: function (error) {

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

    function assignInvestigator(dt) {
        var ids = [];
        var rows_selected = dt.column(0).checkboxes.selected();
        if(rows_selected.length > 0){
            $.each(rows_selected, function(index, rowId){
                ids.push(rowId);
            });
            if(ids.length > 0){
                let cookieValue = JSON.stringify(ids);
                document.cookie = "invIds="+cookieValue;
                window.location.href = "{{ route('assign-bulk-investigation')}}";
            }

        }else{
            Swal.fire("{{trans('general.error_text')}}", "{{ trans('general.no_row_selected') }}", 'error');
        }
    }

    function changeStatus(action, id){

        var url = "{{ route('investigation.change-status') }}";

        Swal.fire({
            title: "{{ trans('general.are_you_sure') }}",
            text: "{{trans('form.registration.investigation.confirm_changestatus')}}",
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
                                    title: '{{ trans("general.changed") }}',
                                    text: (result.message) ? result.message : "{{trans('form.registration.investigation.confirm_statuschanged')}}", 
                                    type: "success",
                                    confirmButtonText: "{{ trans('general.ok') }}",
                                });
                                dbDTable.draw();
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

    function changeDTTab(selectedId, status){
        console.log('in change', selectedId, status);
        dtSelectedId = selectedId;

        if ( ! $.fn.DataTable.isDataTable( '#'+selectedId ) ) {
            var url = '{{ route("investigation-list", ":status") }}';
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
                    {data: 'user_inquiry', name: 'user_inquiry'},
                    {data: 'ex_file_claim_no', name: 'ex_file_claim_no'},
                    {data: 'claim_number', name: 'claim_number'},
                    {data: 'paying_customer_name', name: 'paying_customer_name', orderable: false, searchable: false},
                    {data: 'work_order_number', name: 'work_order_number'},
                    {data: 'inquiry', name: 'type_of_inquiry'},
                    {data: 'subject_name', name: 'subject_name'},
                    {data: 'status', name: 'status', orderable: false, searchable: false},
                    {data: 'created_at', name: 'created_at', searchable: false},
                    {data: 'status_hr', name: 'status_hr', visible: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                    {data: 'product_name', name: 'product.name', orderable: false, visible: false,},
                    {data: 'first_name', name: 'subjects.first_name', orderable: false, visible: false,},
                    {data: 'family_name', name: 'subjects.family_name', orderable: false, visible: false,},
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
                    {
                        text: '{{ trans("general.delete") }}',
                        action: function ( e, dt, node, config ) {
                            deleteSelectedRecords(dt);
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
                order: [[9,'desc']],
            });
            DTable.buttons().container().appendTo('#'+selectedId+'_wrapper .col-md-6:eq(0)');
        }else{
            $('#'+selectedId).DataTable().ajax.reload();
        }

    }

    function changeCounts(){
        $.ajax(
        {
            url: "{{ route('investigation.statuswise-counts') }}",
            type: 'get',
            success: function (response){
                if (response.status == 'success') {
                    console.log('counts ', response.data);
                    $('.count-all').html(response.data.All || 0);
                    $('.count-open').html(response.data.Open || 0);
                    $('.count-pendingapproval').html(response.data['Pending Approval'] || 0);
                    $('.count-istarted').html(response.data['Investigation Started'] || 0);
                    $('.count-rwriting').html(response.data['Report Writing'] || 0);
                    $('.count-rsubmitted').html(response.data['Report Submitted'] || 0);
                    $('.count-mrequired').html(response.data['Modification Required'] || 0);
                    $('.count-delivered').html(response.data.Delivered || 0);
                    $('.count-late').html(response.data.Late || 0);
                }
            }
        });
    }

</script>
@endsection