@extends('layouts.master')

@section('title') {{ trans('form.investigationinvoice.invoice') }} @endsection

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
<style>
    .focus-btn-group{
        display: none;
    }
    .btn-toolbar{
        display: none;
    }
</style>
@endsection

@section('content')

<!-- content -->
<div class="col-sm-6">
    <div class="page-title-box">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{route('investigations')}}">{{ trans('form.investigations') }}</a></li>
            <li class="breadcrumb-item">
                <a href="{{route('investigation.show', [Crypt::encrypt($invoice->investigation->id)]) }}">
                    <span class="badge dt-badge badge-dark">{{$invoice->investigation->work_order_number}} </span> &nbsp;
                </a>
            </li>
            <li class="breadcrumb-item"><a href="javascript: void(0);">{{ trans('general.payment') }}</a></li>
        </ol>
    </div>
</div>

@include('layouts.partials.session-message')

<div class="row" id="invoicedata">
    @include('invoice.investigator.invoice-pay-inner')
</div>
<!-- end row -->
<div id="editor"></div>
<!-- -->

@endsection

@section('footerScript')
<!-- footerScript -->
<!-- Required datatable js -->
<script src="{{ URL::asset('/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ URL::asset('/libs/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('/libs/bootstrap-editable/bootstrap-editable.min.js') }}"></script>
<script src="{{ URL::asset('/js/pages/form-xeditable.init.js') }}"></script>
<script src="{{ URL::asset('/libs/rwd-table/rwd-table.min.js') }}"></script>
<script src="{{ URL::asset('/js/pages/table-responsive.init.js') }}"></script>
<script src="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.js') }}" async></script>
<script src="{{ URL::asset('/libs/dropzone/dropzone.min.js') }}"></script>
<script src="{{ URL::asset('/libs/magnific-popup/magnific-popup.min.js')}}"></script>
<script src="{{ URL::asset('/js/pages/lightbox.init.js')}}"></script>
<script src="{{ URL::asset('/libs/jquery-validate/1.19.0/jquery.validate.js')}}"></script> 
@if (App::isLocale('hr'))
<script src="{{ URL::asset('/libs/jquery-validate/1.19.0/localization/messages_he.js')}}"></script>
@endif   
<script>
    Dropzone.autoDiscover = false;
    $(function () {
        calculateTotal();
        $(".sub-amt").on('change, keyup', function(){
            calculateTotal();
        });

        var jj = 0;
        var kk = 0;
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        var myInvestigatorDropzone = new Dropzone("#investigatorFileupload", {
            url: '{{ route('investigator-invoice-pay') }}',
            headers: {
                'x-csrf-token': CSRF_TOKEN,
            },
            maxFilesize: 20000, // MB
            parallelUploads: 20,
            autoProcessQueue: false,
            uploadMultiple:true,
            acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx",
            addRemoveLinks: true,
            onBeforeOpen:function () {
                Swal.showLoading();
            },
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
                    Swal.close();
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
                    var data = $('#investigatorFileuploadform').serializeArray();
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
                            location.reload(true);
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

        $("body").on("click", "#deletefile", function () {
            var id = $(this).data("id");
            $(".doctr_" + id).remove(); 
        });
        
        // $("#investigatorFileupload").validate({
        //     rules: {
        //         received_date: {
        //             required: true,
        //         },
        //         paid_date: {
        //             required: true,
        //         },
        //         payment_mode_id: {
        //             required: true,
        //         },
        //         bank_details: {
        //             required: true,
        //         },
        //         admin_notes: {
        //             required: true,
        //         }
        //     },
        //     messages: 
        //     {
                
        //     }
        // });

        $("#investigatorFileuploadform").validate({
            rules: {
                received_date: {
                    required: true,
                },
                paid_date: {
                    required: true,
                },
                payment_mode_id: {
                    required: true,
                },
                bank_details: {
                    required: true,
                },
                admin_notes: {
                    required: true,
                }
            },
            messages: 
            {
                
            },
            submitHandler: function(form) {
                // 
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
                    var isSend = "{{$isSend}}";
                    if(isSend==0){
                        Swal.fire({
                            position: 'center',
                            type: 'warning',
                            title: "{{ trans('content.notificationdata.msg.invoice_didnt_send_to_client') }}",
                            showConfirmButton: true,
                            showCancelButton: true,
                            confirmButtonText: "{{ trans('general.payanyway') }}",
                            cancelButtonText: "{{ trans('general.cancel') }}"
                        }).then((result) => {
                            if (result.value) {
                                var data=$("#investigatorFileupload").serialize();
                                console.log(data,'data')
                                hideModel('pay_model');
                                myInvestigatorDropzone.processQueue();
                                // location.reload();
                            } else if (result.dismiss == "cancel") {
                                Dropzone.forElement("#investigatorFileupload").removeAllFiles(true);
                                $('#investigatorFileupload').trigger("reset");
                            }
                        }); 
                    } else {
                        Swal.showLoading();
                        var data=$("#investigatorFileupload").serialize();
                        console.log(data,'data');
                        hideModel('pay_model');
                        myInvestigatorDropzone.processQueue();
                        // location.reload();
                    }
                }
                // 
            }
        });

    });
    calAmt();
    function calAmt() {
        if($('#amountToPay').length)
        {
            let amt = $('#amount').val();
            let amountTex = "{{trans('form.performa_invoice.Total')}}<br>{{ trans('general.money_symbol') }}"+amt;
            $('#amountToPay').html(amountTex);
            $('#amountSum').html("{{trans('form.performa_invoice.Sum')}}<br>{{ trans('general.money_symbol') }}"+amt);
            $('#investigatorAmt').val(amt);
        }
    }

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

    function calculateTotal(){
        var yourArray = [];
        $(".sub-amt").each(function(){
            var val = $(this).val() ? parseFloat($(this).val()) : 0;
            yourArray.push(val);
        });

        var totalSubCost = yourArray.reduce((a, b) => a + b, 0);
        $('.sub-total').html(totalSubCost.toFixed(2));

        var tax = $('.tax').html();
        var totalCost = ((parseFloat(tax) * totalSubCost) / 100) + (totalSubCost);
        $('.final-total').html(totalCost.toFixed(2));
    }
</script>

@endsection