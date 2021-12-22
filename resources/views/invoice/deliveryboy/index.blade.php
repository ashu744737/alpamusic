@extends('layouts.master')

@section('title') {{ trans('form.paidperformainvoices') }} @endsection

@section('headerCss')
    <!-- headerCss -->
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/colReorder.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/dataTables.checkboxes.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/css/custom.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/bootstrap-editable/bootstrap-editable.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('/libs/rwd-table/rwd-table.min.css') }}" rel="stylesheet" type="text/css" /></link>
    <link href="{{ URL::asset('/libs/magnific-popup/magnific-popup.min.css')}}" rel="stylesheet" type="text/css" /> 
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
                            <h4 class="card-title">{{ trans('form.deliveryboys') }} {{ trans('general.payment') }}</h4>
                        </div>
                    </div>

                    @include('layouts.partials.session-message')

                    <div class="flash-message"></div>
                    
                    <div class="row">
                        <div class="col-12">

                            <table id="datatable_deliveryboy_invoice" class="table table-bordered dt-responsive">
                                <thead>
                                <tr role="row">
                                    <th class="noVis"></th>
                                    <th class="noVis"></th>
                                    @if(isAdmin())
                                    <th>{{ trans('form.client_approval.client_name_title_del') }}</th>
                                    <th>{{ trans('form.invoice.clientname') }}</th>
                                    @endif
                                    <th>{{ trans('form.invoice.investigation') }}</th>
                                    <th>{{ trans('form.invoice.amount') }}</th>
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

    <div id="pay_model" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">{{ trans('form.invoice.payment.payment_form') }}</h5>
                    <button id="closmod" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <label>{{ trans('form.invoice.payment.upload_doc_message') }}
                            <span class="text-danger">*</span>
                        </label>
                        
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <form method="POST" class="dropzone dropzone-area" id="investigatorFileupload"
                        action="{{ route('deliveryboy-bulk-invoice-pay') }}"
                        enctype="multipart/form-data"> 
                        @csrf
                        
                            <input type="hidden" value="{{ Auth::id() }}" name="uploaded_by" />
                            <input type="hidden" name="invoice_id" id="invoice_id" />
                            <div class="fallback">
                                <input name="file" type="files" multiple="multiple" class="form-control" />
                            </div>
                            <div class="dz-message">
                                <h4>{{ trans('form.registration.investigation.document_dropfile') }}</h4>
                            </div>
                        
                    </div><br/>
                    <div class="row">
                            <div class="form-group col-md-6">
                                <label for="received_date">{{trans('form.performa_invoice.received_date')}} <span class="text-danger">*</span></label>
                                <input type="date" class="form-control " name="received_date" required id="received_date" placeholder="{{ trans('form.performa_invoice.received_date') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="paid_date">{{trans('form.performa_invoice.paid_date')}} <span class="text-danger">*</span></label>
                                <input type="date" class="form-control " name="paid_date" required id="paid_date" placeholder="{{ trans('form.performa_invoice.paid_date') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="paymentForm">{{ trans('form.client_approval.payment_mode') }}</label>
                                <select class="form-control" name="payment_mode_id" id="payment_mode_id">
                                    @php 
                                    $payment_modes = \App\PaymentMode::orderBy('mode_name')->get();
                                    @endphp
                                    @foreach($payment_modes as $key => $mode)
                                        <option
                                            value="{{ $mode->id }}">{{ App::isLocale('hr')?$mode->hr_mode_name:$mode->mode_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="bank_details">{{trans('form.performa_invoice.bank_details_cheque_number')}} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control " name="bank_details" required id="bank_details" placeholder="{{ trans('form.performa_invoice.bank_details_cheque_number') }}">
                            </div>
                            <div class="form-group col-md-12">
                                <label>{{ trans('form.invoice.payment.payment_note') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea 
                                type="tex" placeholder="{{ trans('form.invoice.payment.enter_payment_note') }}"
                                class="form-control" 
                                name="notes"
                                value=""
                                    required
                                ></textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="userinquiry">{{ trans('form.investigator_investigation_status.note_by_sm') }} <span class="text-danger">*</span></label>
                                <textarea 
                                type="tex" placeholder="{{ trans('form.investigator_investigation_status.note_by_sm') }}"
                                class="form-control" 
                                name="admin_notes"
                                value=""
                                required
                                ></textarea>
                            </div>
                        </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="form-group text-center">
                            <button type="button" id="uploadInvestigatorFile" class="uploadInvestigatorFile btn btn-primary w-md waves-effect waves-light addbtn">{{ trans('form.invoice.payment.payment_send') }}</button>
                            <button type="button" id="closmod2" data-dismiss="modal" aria-hidden="true" class="closmod btn btn-secondary w-md waves-effect waves-light">{{ trans('general.cancel') }}</button>
                        </div>
                    </div>
                    
                    
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>

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
    <script src="{{ URL::asset('/libs/bootstrap-editable/bootstrap-editable.min.js') }}"></script>
    <script src="{{ URL::asset('/js/pages/form-xeditable.init.js') }}"></script>
    <script src="{{ URL::asset('/libs/rwd-table/rwd-table.min.js') }}"></script>
    <script src="{{ URL::asset('/js/pages/table-responsive.init.js') }}"></script>
    <script src="{{ URL::asset('/libs/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/magnific-popup/magnific-popup.min.js')}}"></script>
    <script src="{{ URL::asset('/js/pages/lightbox.init.js')}}"></script>
    <script src="{{ URL::asset('/libs/jquery-validate/1.19.0/jquery.validate.js')}}"></script>  
    <script>
        var cDTable = null;
        Dropzone.autoDiscover = false;
        $(document).ready(function() {
            
            cDTable = $('#datatable_deliveryboy_invoice').DataTable({
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
                ajax: "{{ route('deliveryboy.invoices.invoice-list') }}",
                columns: [
                    {data: 'id', name: 'check', orderable: false, searchable: false},
                    {data: 'created_at', name: 'created_at', searchable: false, visible: false},
                    @if(isAdmin())
                    {data: 'deliveryboy_name', name: 'deliveryboy.user.name'},
                    {data: 'client_name', name: 'investigation.user.name'},
                    @endif
                    {data: 'work_order_number', name: 'work_order_number'},
                    {data: 'amount', name: 'amount'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                buttons: ['excel', 'pdf',
                @if(check_perm('invoice_list_deliveryboy') && isAdmin()){
                        "text": '{{ trans("form.invoice.mark_as_paid") }}',
                        action: function ( e, dt, node, config ) {
                            markAsPaidSelectedRecords();
                        },
                },@endif, { extend: 'colvis', columns: ':not(.noVis)'},
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
                order: [[1,'desc']],
            });

            cDTable.buttons().container().appendTo('#datatable_invoice_wrapper .col-md-6:eq(0)');

            function markAsPaidSelectedRecords(){
                var rows_selected = cDTable.column(0).checkboxes.selected();
                var ids = [];

                if(rows_selected.length > 0){
                    
                    $.each(rows_selected, function(index, rowId){
                        ids.push(rowId);
                    });
                    $('#invoice_id').val(ids);
                    $('#pay_model').modal('show');
                }else{
                    Swal.fire({
                      icon: 'error',
                      text: "{{ trans('general.no_row_selected') }}",
                      confirmButtonText: "{{trans('general.ok')}}"
                    })
                }
            }


            var jj = 0;
            var kk = 0;
            var myInvestigatorDropzone = new Dropzone("#investigatorFileupload", {
                maxFilesize: 20000, // MB
                parallelUploads: 20,
                autoProcessQueue: false,
                uploadMultiple:true,
                acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx",
                addRemoveLinks: true,
                init: function () {
                    this.on("addedfile", function (file) {
                        $(".dz-preview:last .dz-remove").attr('id', 'deletefile_' + file.size);
                    });

                    this.on("removedfile", function (file) {
                        hideModel('pay_model');
                    });

                    this.on("sending", function (file, xhr, formData) {
                        //formData.append("fileno", jj);
                    });

                    this.on("addedfiles", function (files) {
                        //showModal2('doc_model');
                        /*  for (var i = 0; i < files.length; i++) {
                            var idrand = Math.random();
                            var f = files[i];
                            console.log('f.name',f.name);
                        } */
                    });
                    this.on("complete", function(file) {
                        if (this.getQueuedFiles().length == 0 && this.getUploadingFiles()
                            .length == 0) {
                            var _this = this;
                            console.log(_this,'-------------files')
                            // _this.removeAllFiles();
                        }
                    }); 

                    this.on("success", function (file, response) {
                        // myInvestigatorDropzone.removeFile(file);
                        console.log('success',response.status);
                    });

                    this.on("error", function (file, errorMessage) {
                        console.log('File upload error');
                        //Swal.fire("{{trans('general.error_text')}}", "{{trans('form.registration.investigation.document_not_allowed')}}", "error");
                    });

                    /*    this.on("complete", function(file) {

                        // console.log('Finally done');

                        // myInvestigatorDropzone.removeFile(file);

                        //Swal.fire('Uploaded!', "{{trans('form.registration.investigation.document_uploaded')}}", "success");

                    });  */

                    this.on('sending', function(file, xhr, formData) {
                        // Append all form inputs to the formData Dropzone will POST
                        var data = $('#investigatorFileupload').serializeArray();
                        $.each(data, function(key, el) {
                            formData.append(el.name, el.value);
                        });
                    });

                    this.on("queuecomplete", function (file) {
                        myInvestigatorDropzone.removeAllFiles(true);
                        Swal.fire({
                            title: "{{trans('general.updated_text')}}", 
                            text: "{{trans('form.registration.investigation.document_uploaded')}}", 
                            type: "{{trans('general.success_text')}}",
                            confirmButtonText: "{{ trans('general.ok') }}",
                        }).then(function (result) {
                            if(result.value){
                                location.reload();
                            }
                        });
                        $('#client_paid').remove();
                    });
                }
            });

            $('#closmod').click(function () {
                Dropzone.forElement("#investigatorFileupload").removeAllFiles(true);
            });

            $('#closmod2').click(function (){
                Dropzone.forElement("#investigatorFileupload").removeAllFiles(true);
            });

            $('#uploadInvestigatorFile').click(function () {
                if(myInvestigatorDropzone.files.length==0){
                    Swal.fire({
                        position: 'center',
                        type: 'error',
                        title: "{{ trans('general.upload_file_error') }}",
                        showConfirmButton: true,
                        confirmButtonText: "{{ trans('general.ok') }}",
                    }); 
                    return false;
                }else{
                    var data=$("#investigatorFileupload").serialize();
                    console.log(data,'data')
                    hideModel('pay_model');
                    myInvestigatorDropzone.processQueue();
                    // location.reload();
                }
            });

            function showModal(modelid) {
            $('#' + modelid).modal({
                backdrop: 'static',
                keyboard: false
            })

            $('#' + modelid).modal('show');
        }

        function hideModel(id) {
            $('#' + id).modal('hide');
        }

        });
    </script>
@endsection