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
                    <div class="col-12 col-sm-8 col-xs-12 text-left text-sm-right mb-3">
                        <a href="{{ route('investigation.create') }}" class="btn btn-primary w-md add-new-btn">{{ trans('form.registration.investigation.add_investigation') }}</a>
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
                                    <a class="nav-link" data-toggle="tab" href="#inv_waiting" role="tab" onclick="changeDTTab('datatable-investigation-waiting', 'Waiting')">
                                        <span class="tab_title">{{ trans('form.investigation_status.Waiting') }}</span>
                                        <span class="badge badge-light count-waiting">{{ $invCounts['Waiting'] ?? '0' }}</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#inv_inprogress" role="tab" onclick="changeDTTab('datatable-investigation-inprogress', 'In Progress')">
                                        <span class="tab_title">{{ trans('form.investigator_investigation_status.InProgress') }}</span>
                                        <span class="badge badge-light count-inprogress">{{ ($invCounts['InProgress']) ?? '0' }}</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#inv_closed" role="tab" onclick="changeDTTab('datatable-investigation-closed', 'Closed')">
                                        <span class="tab_title">{{ trans('form.investigation_status.Closed') }}</span>
                                        <span class="badge badge-light count-closed">{{ $invCounts['Closed'] ?? '0' }}</span>
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

                            <div class="tab-pane p-3" id="inv_waiting" role="tabpanel">
                                <table id="datatable-investigation-waiting"
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

                            <div class="tab-pane p-3" id="inv_inprogress" role="tabpanel">
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

                            <div class="tab-pane p-3" id="inv_closed" role="tabpanel">
                                <table id="datatable-investigation-closed"
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
            ajax: "{{ route('client-investigation-list') }}",
            columns: [
                {data: 'id', name: 'check', orderable: false, searchable: false},
                {data: 'work_order_number', name: 'work_order_number'},
                {data: 'inquiry', name: 'type_of_inquiry'},
                {data: 'status', name: 'status'},
                {data: 'created_at', name: 'created_at', searchable: false},
                {data: 'status_hr', name: 'status_hr', visible: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
                {data: 'product_name', name: 'product.name', orderable: false, visible: false,},
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
                                    Swal.fire("{{trans('general.deleted_text')}}",(result.message) ? result.message : "{{trans('form.registration.investigation.investigation_deleted')}}", "success");
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



    function changeDTTab(selectedId, status){
        console.log('in change', selectedId, status);
        dtSelectedId = selectedId;

        if ( ! $.fn.DataTable.isDataTable( '#'+selectedId ) ) {
            var url = '{{ route("client-investigation-list", ":status") }}';
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
                    data: {},
                },
                columns: [
                    {data: 'id', name: 'check', orderable: false, searchable: false},
                    {data: 'work_order_number', name: 'work_order_number'},
                    {data: 'inquiry', name: 'type_of_inquiry'},
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