@extends('layouts.master')

@section('title') {{ trans('form.registration.investigation.subjects') }} @endsection

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
                            <h4 class="card-title">{{ trans('form.registration.investigation.subjects') }}</h4>
                        </div>
                       
                    </div>

                    @include('session-message')
                    @if(check_perm('subject_show'))
                    <div class="row">
                        <div class="col-12">

                            <table id="datatable_clients" class="table table-bordered dt-responsive">
                                <thead>
                                <tr role="row">
                                    <th>{{ trans('form.registration.investigation.family') }}</th>
                                    <th>{{ trans('form.registration.investigation.firstname') }}</th>
                                    <th>{{ trans('form.registration.investigation.id') }}</th>
                                    <th>{{ trans('form.registration.investigation.account_no') }}</th>
                                    <th>{{ trans('form.registration.investigation.workplace') }}</th>
                                    <th>{{ trans('form.registration.investigation.website') }}</th>
                                    <th>{{ trans('form.registration.investigation.father_name') }}</th>
                                    <th>{{ trans('form.registration.investigation.mothername') }}</th>
                                    <th>{{ trans('form.registration.investigation.spousename') }}</th>
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
                ajax: "{{ route('get-subject-list') }}",
                columns: [
                    {data: 'family_name', name: 'family_name'},
                    {data: 'first_name', name: 'first_name'},
                    {data: 'id_number', name: 'id_number'},
                    {data: 'account_no', name: 'account_no'},
                    {data: 'workplace', name: 'workplace'},
                    {data: 'website', name: 'website'},
                    {data: 'father_name', name: 'father_name'},
                    {data: 'mother_name', name: 'mother_name'},
                    {data: 'spouse_name', name: 'spouse_name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                buttons: ['excel', 'pdf',
            
                    { extend: 'colvis', columns: ':not(.noVis)'},
                    ],
                colReorder: {
                    realtime: false,
                    fixedColumnsRight: 2
                },
                order: [],
                
            });
           
            cDTable.buttons().container().appendTo('#datatable_clients_wrapper .col-md-6:eq(0)');

           
            

           
        });
    </script>
@endsection