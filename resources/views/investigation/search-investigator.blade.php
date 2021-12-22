@extends('layouts.master')

@section('title') {{ trans('form.investigation.assign_investigator') }} @endsection

@section('headerCss')
    <!-- headerCss -->
    <!-- DataTables -->

    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/colReorder.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/dataTables.checkboxes.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
    @if (App::isLocale('hr'))
    <style>
        .table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child:before {
            margin-top: 3.2rem;
            margin-left: -7px;
        }
        .table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child{
            display: inline !important;
        }
    </style>
    @else
    <style>
    .table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child:before {
        margin-top: 1.9rem;
    }
    </style>
    @endif
    <style>

        
        .spec-ul{
            list-style-type: none;
            padding: 0;
        }

        .speclist-ul{
            padding-left:15px !important;
        }

        @media (max-width: 575px) {
            .state-information .state-graph {
                float: none !important;
                margin-left: 0 !important;
                text-align: center !important;
                margin-bottom: 10px !important;
            }

            .back-btn{
                width: 100%;
            }
        }
    </style>

@endsection

@section('content')

    <div class="row">
        <div class="col-12 col-sm-6">
            <div class="page-title-box">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{route('investigations')}}">{{ trans('form.investigations') }}</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">{{ trans('form.investigation.search.title') }}</a></li>
                </ol>
            </div>
        </div>
        <div class="col-12 col-sm-6">
            <div class="state-information">
                <div class="state-graph">
                    <a href="{{ url()->previous() }}" type="button" class="btn btn-primary back-btn">{{trans('general.back')}}</a>
                </div>
            </div>
        </div>
    </div>


    <!-- content -->

    <div class="row">

        <div class="col-12 col-sm-8 p-0">
            <div class="col-12 col-sm-12 col-md-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-12 col-sm-8 form-group mb-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">
                                        <i class="mdi mdi-magnify" style="font-size: 17px;"></i>
                                    </span>
                                    </div>
                                    <input type="text" class="form-control" id="search" name="search"
                                           placeholder="{{ trans('form.investigation.search.title') }}" value="{{ old('search') }}" aria-label="search"
                                           aria-describedby="basic-addon1"
                                           style="height: 40px;border-left: none;border-radius: 0px 0.25rem 0.25rem 0px;"/>
                                </div>
                                @error('search')
                                <div class="validation-errors">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-4">

                            </div>
                        </div>


                        <div class="table-responsive">
                            <table id="datatable_investors" class="table table-centered table-vertical dt-responsive">
                                <thead>
                                <tr role="row">
                                    <th></th>
                                    <th>{{ trans('form.registration.investigator.specialization') }}</th>
                                    <th>{{ trans('form.investigations') }}</th>
                                    <th>{{ trans('general.action') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-4 p-0">
            <!-- Top investigators ---->
            <div class="col-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">{{ trans('form.investigation.search.top_investigators') }}</h4>

                        <div class="table-responsive">
                            <table class="table table-centered table-vertical table-nowrap">

                                <tbody>
                                @foreach($topInvestigators ?? [] as $top)
                                    <tr>
                                        <td>
                                            <div class="p-1">
                                                <a href="javascript:void(0)" >
                                                    <h6 class="mb-1 font-size-14 mt-2">{{$top->investigator->user->name}}</h6>
                                                </a>
                                                <p class="text-muted mb-0">{{$top->investigator->user->email}}</p>
                                            </div>
                                        </td>
                                        <td>
                                            <ul class="spec-ul">
                                                <li>{{trans('form.investigation.search.open_cases')}}({{ $top->total_open ?? '0' }})</li>
                                                <li>{{trans('form.investigation_status.Completed')}} ({{ $top->total_completed ?? '0' }})</li>
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Top investigators ---->

            <!-- Active investigators ---->
            <div class="col-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">{{ trans('form.investigation.search.active_investigators') }}</h4>

                        <div class="table-responsive">
                            <table class="table table-centered table-vertical table-nowrap">

                                <tbody>
                                @foreach($activeInvestigators ?? [] as $active)
                                    <tr>
                                        <td>
                                            <div class="p-1">
                                                <a href="javascript:void(0)" >
                                                    <h6 class="mb-1 font-size-14 mt-2">{{$active->name}}</h6>
                                                </a>
                                                <p class="text-muted mb-0">{{$active->email}}</p>
                                            </div>
                                        </td>
                                        <td>
                                            <ul class="spec-ul">
                                                <li>{{trans('form.investigation.search.open_cases')}} ({{ $active->total_open ?? '0' }})</li>
                                                <li>{{trans('form.investigation_status.Completed')}} ({{ $active->total_completed ?? '0' }})</li>
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Active investigators ---->
        </div>

    </div>

    <!-- Add Note and Assign Investigation modal  -->
    <div class="modal fade bs-example-modal-center" id="addNoteModal" tabindex="-1" role="dialog" aria-labelledby="addNote" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ trans('form.investigation.additional_details') }} </h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form class="assign-form">

                        <form class="add_mobile" id="add_mobile" novalidate="novalidate">
                            <div class="form-row">

                                <input type="hidden" name="invnid" id="invnid" value="{{ $invId ?? 'bulk' }}"/>
                                <input type="hidden" name="invrid" id="invrid" value=""/>

{{--                                <div class="form-group col-md-6">--}}
{{--                                    <label for="case_number">{{ trans('form.investigation.search.case_number') }}</label>--}}
{{--                                    <input type="text" class="form-control" name="case_number" value="">--}}
{{--                                </div>--}}

{{--                                <div class="form-group col-md-6">--}}
{{--                                    <label for="investigation_number">{{ trans('form.investigation.search.investigation_number') }}</label>--}}
{{--                                    <input type="text" class="form-control" name="investigation_number" value="">--}}
{{--                                </div>--}}

                                <div class="form-group col-md-6">
                                    <label for="investigator">{{ trans('form.investigation.search.investigator') }}</label>
                                    <input type="text" class="form-control investigator-name" name="investigator_name" value="" readonly>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="paying_customer">{{ trans('form.registration.investigation.req_type_inquiry') }}</label>
                                    <select name="type_of_inquiry" id="type_of_inquiry" class="form-control" required>
                                        @foreach($products ?? [] as $product)
                                            <option value="{{$product->product->id}}">{{ $product->product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="invn_cost" class="mb-0">
                                        {{ trans('form.registration.investigation.inv_cost_paid') }} : {{ trans('general.money_symbol')}}
                                        @if (!isAdmin() || isSM()) 
                                        <span id="invn_invr_cost"></span>
                                        @endif
                                    </label>
                                    @if (isAdmin() || isSM()) 
                                        <input type="hidden" class="form-control investigator-name" name="old_invn_invr_cost" id="old_invn_invr_cost">
                                        <input type="text" class="form-control investigator-name" name="invn_invr_cost" id="invn_invr_cost">
                                    @endif
                                </div>

                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label>{{ trans('form.investigation.note') }} : </label>
                                    <textarea class="form-control note" name="note" placeholder="{{ trans('form.investigation.write_note') }}" ></textarea>
                                </div>
                            </div>

                        </form>

                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary assignButton">{{trans('form.investigation.assign')}}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('general.cancel')}}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- -->

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
    <script src="{{ URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

    <script>
        var iDTable = null;

        $(document).ready(function() {

            $('.start_date').datepicker({
                autoclose: true,
                todayHighlight: true,
                container: '#addNoteModal',
            });

            $('.completion_date').datepicker({
                autoclose: true,
                todayHighlight: true,
                container: '#addNoteModal',
            });

            let invId = '{{$invId ?? ''}}';
            let url = '';
            if(invId){
                url = `/investigations/${invId}/search-investigators`;
            } else {
                url = `/investigations/bulk/search-investigators`;
            }

            iDTable = $('#datatable_investors').DataTable({
                // dom: 'lBfrtip',
                'dom': "<'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                search: true,
                lengthChange: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                language: {
                    @if(config('app.locale') == 'hr')
                    url: '{{ URL::asset('/libs/datatables/json/Hebrew.json') }}'
                    @endif
                },
                ajax: url,
                columns: [
                    {data: 'name-email', name: 'name-email', sortable: false},
                    {data: 'specializations', name: 'specializations.name', sortable: false},
                    {data: 'investigations', name: 'investigations', sortable: false},
                    {data: 'action', name: 'action', sortable: false},
                    {data: 'name', name: 'name', visible: false, sortable: false},
                    {data: 'email', name: 'email', visible: false, sortable: false},
                    {data: 'family', name: 'investigator.family', visible: false, sortable: false},
                    {data: 'idnumber', name: 'investigator.idnumber', visible: false, sortable: false},
                    {data: 'website', name: 'investigator.website', visible: false, sortable: false},
                    {data: 'specializations', name: 'specializations.name', visible: false, sortable: false},
                    {data: 'phone', name: 'userPhones.value', visible: false, sortable: false },
                    {data: 'address', name: 'userAddresses.address1', visible: false, sortable: false},
                ],
            });
            @if(check_perm('investigator_delete'))
            iDTable.button().add(2, {
                "text": '{{ trans("general.delete") }}',
                action: function (e, dt, node, config) {
                    deleteSelectedRecords();
                }
            });
            @endif

            iDTable.buttons().container().appendTo('#datatable_investors_wrapper .col-md-6:eq(0)');

            $('#search').keyup(delay(function(){
                iDTable.search($(this).val()).draw();
            }, 150));

            $("body").on("click", ".assignInvestigation", function (e) {
                $("#addNoteModal .modal-body .investigator-name").val( $(this).data('invrname') );
                $("#addNoteModal .modal-body #invrid").val( $(this).data('invrid') );
                $("#addNoteModal .modal-body #type_of_inquiry").val( $(this).data('typeofinq') );
                
                $.ajax({
                    url: "/investigations/investigatorcost/" + "{{ $invId ?? 'bulk' }}" + "/" + $(this).data('invrid'),
                    method: 'get',
                    success: function(result) {
                        if (result.status == 'success') {
                            $("#addNoteModal .modal-body #invn_invr_cost").html( result.data );
                            $("#addNoteModal .modal-body #invn_invr_cost").val( result.data );
                            $("#addNoteModal .modal-body #old_invn_invr_cost").val( result.data );
                        }
                    },
                });

                var isAssigned = $(this).data('isassigned');
                var invId = $(this).data('invId');

                if(isAssigned !== ""){
                    Swal.fire({
                        title: "{{ trans('general.are_you_sure') }}",
                        text: "{{trans('form.investigation.already_assigned_msg')}}",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#34c38f",
                        cancelButtonColor: "#f46a6a",
                        confirmButtonText: "{{trans('form.investigation.assign')}}",
                        cancelButtonText: "{{trans('general.cancel')}}"
                    }).then((result) => {
                        if (result.value) {
                            $('#addNoteModal').modal('show');
                        }
                    });
                }else{
                    $('#addNoteModal').modal('show');
                }

            });

            $("body").on("click", ".assignButton", function (e) {
                console.log('assignButton clicked');
                var investigator = $('.investigator-name').val();
                Swal.fire({
                    title: "{{ trans('general.are_you_sure') }}",
                    text: "{{trans('form.investigation.confirm_assign_investigation')}}" + investigator + '?',
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#34c38f",
                    cancelButtonColor: "#f46a6a",
                    confirmButtonText: "{{trans('form.investigation.assign')}}",
                    cancelButtonText: "{{trans('general.cancel')}}"
                }).then((result) => {
                    if (result.value) {
                        assignInvestigation();
                    }
                });
            });

        });

        function assignInvestigation() {
            $('.loading').removeClass('d-none');

            var invnid = $('#invnid').val();
            var invrid = $('#invrid').val();
            var investigator = $('.investigator-name').val();
            var type_of_inquiry = $('#type_of_inquiry').val();
            var start_date = $('.start_date').val();
            var start_time = $('.start_time').val();
            var completion_date = $('.completion_date').val();
            var completion_time = $('.completion_time').val();
            var note = $('.note').val();
            var inv_cost = $('#invn_invr_cost').val();
            var old_inv_cost = $("#old_invn_invr_cost").val();
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('investigation.assign')}}",
                method: 'post',
                data: {
                    'investigatorId': invrid,
                    'investigationId': invnid,
                    'investigator': investigator,
                    'type_of_inquiry': type_of_inquiry,
                    'start_date': start_date,
                    'start_time': start_time,
                    'completion_date': completion_date,
                    'completion_time': completion_time,
                    'note': note,
                    'inv_cost': inv_cost,
                    'old_inv_cost': old_inv_cost,
                },
                success: function(result) {
                    if (result.status == 'success') {
                        $('#addNoteModal').modal('hide');
                        Swal.fire({
                            title: "{{trans('general.assigned_text')}}", 
                            text: (result.message) ? result.message : '{{ trans('form.investigation.inv_assign_success') }}', 
                            type: "{{trans('general.success_text')}}",
                            confirmButtonText: "{{ trans('general.ok') }}",
                        })
                            .then((result) => {
                                if(result.value){
                                    window.location.href = "{{ (isset($invId) && $invId) ? url()->previous() : route('investigations') }}";
                                }
                            });

                    } else {
                        Swal.fire(
                            "{{trans('general.error_text')}}",
                            (result.message) ? result.message : '{{ trans('general.something_wrong') }}',
                            "{{trans('general.error_text')}}"
                        )
                    }

                    $('.loading').addClass('d-none');
                },
            });
        }

        function delay(callback, ms) {
            var timer = 0;
            return function() {
                var context = this, args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function () {
                    callback.apply(context, args);
                }, ms || 0);
            };
        }

    </script>
@endsection