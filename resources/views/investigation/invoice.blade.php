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
            <li class="breadcrumb-item"><a href="{{route('investigation.show', [Crypt::encrypt($invn->id)]) }}">{{$invn->work_order_number}}</a></li>
            <li class="breadcrumb-item"><a href="javascript: void(0);">{{ trans('form.investigationinvoice.invoice') }}</a></li>
        </ol>
    </div>
</div>


<div class="row" id="invoicedata">
    <!--performa invoice has been generated-->
    @if(count($invn->clientinvoice) > 0)
        @include('investigation.invoice.clientinvoice')
    @else
        @include('investigation.invoice.createinvoice')
    @endif
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
        var myDropzone = new Dropzone("#fileupload", {
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
                    // myDropzone.removeFile(file);
                    console.log('success',response.status);
                    location.reload();
                });

                this.on("error", function (file, errorMessage) {
                    console.log('File upload error');
                    //Swal.fire("{{trans('general.error_text')}}", "{{trans('form.registration.investigation.document_not_allowed')}}", "error");
                });

                /*    this.on("complete", function(file) {

                    // console.log('Finally done');

                    // myDropzone.removeFile(file);

                    //Swal.fire('Uploaded!', "{{trans('form.registration.investigation.document_uploaded')}}", "success");

                });  */

                this.on('sending', function(file, xhr, formData) {
                    // Append all form inputs to the formData Dropzone will POST
                    var data = $('#fileupload').serializeArray();
                    $.each(data, function(key, el) {
                        formData.append(el.name, el.value);
                    });
                });

                this.on("queuecomplete", function (file) {
                    myDropzone.removeAllFiles(true);
                    Swal.fire({
                        title: "{{trans('general.updated_text')}}", 
                        text: "{{trans('form.registration.investigation.document_uploaded')}}", 
                        type: "{{trans('general.success_text')}}",
                        confirmButtonText: "{{ trans('general.ok') }}",
                    }).then(function (result) {
                        
                    });
                    $('#client_paid').remove();
                });
            }
        });

        $('#closmod').click(function () {
            Dropzone.forElement("#fileupload").removeAllFiles(true);
        });

        $('#closmod2').click(function (){
            Dropzone.forElement("#fileupload").removeAllFiles(true);
        });

        $('#uploadfile').click(function () {
            if(myDropzone.files.length==0){
                Swal.fire({
                    position: 'center',
                    type: 'error',
                    title: "{{ trans('general.upload_file_error') }}",
                    showConfirmButton: true,
                    confirmButtonText: "{{ trans('general.ok') }}",
                }); 
                return false;
            }else{
                var data=$("#fileupload").serialize();
                console.log(data,'data')
                hideModel('pay_model');
                myDropzone.processQueue();
            }
        });

        // admin partial form
        var jj = 0;
        var kk = 0;
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var myAdminDropzone = new Dropzone("#adminPartialFileupload", {
            url: '{{ route('client-invoice-pay') }}',
            headers: {
                'x-csrf-token': CSRF_TOKEN,
            },
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
                    // myDropzone.removeFile(file);
                    console.log('success',response.status);
                    location.reload();
                });

                this.on("error", function (file, errorMessage) {
                    console.log('File upload error');
                    //Swal.fire("{{trans('general.error_text')}}", "{{trans('form.registration.investigation.document_not_allowed')}}", "error");
                });

                /*    this.on("complete", function(file) {

                    // console.log('Finally done');

                    // myDropzone.removeFile(file);

                    //Swal.fire('Uploaded!', "{{trans('form.registration.investigation.document_uploaded')}}", "success");

                });  */

                this.on('sending', function(file, xhr, formData) {
                    // Append all form inputs to the formData Dropzone will POST
                    var data = $('#adminPartialFileuploadForm').serializeArray();
                    $.each(data, function(key, el) {
                        formData.append(el.name, el.value);
                    });
                });

                this.on("queuecomplete", function (file) {
                    myAdminDropzone.removeAllFiles(true);
                    Swal.fire({
                        title: "{{trans('general.updated_text')}}", 
                        text: "{{trans('form.registration.investigation.document_uploaded')}}", 
                        type: "{{trans('general.success_text')}}",
                        confirmButtonText: "{{ trans('general.ok') }}",
                    }).then(function (result) {
                        if(result.value){
                            // location.reload();
                        }
                    });
                    $('#client_paid').remove();
                });
            }
        });

        $('#closmod').click(function () {
            myAdminDropzone.forElement("#adminPartialFileupload").removeAllFiles(true);
        });

        $('#closmod2').click(function (){
            myAdminDropzone.forElement("#adminPartialFileupload").removeAllFiles(true);
        });

        $("#adminPartialFileuploadForm").validate({
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
                if(myAdminDropzone.files.length==0){
                    Swal.fire({
                        position: 'center',
                        type: 'error',
                        title: "{{ trans('general.upload_file_error') }}",
                        showConfirmButton: true,
                        confirmButtonText: "{{ trans('general.ok') }}",
                    }); 
                    return false;
                } else {
                    var data=$("#adminPartialFileupload").serialize();
                    console.log(data,'data')
                    hideModel('admin_partial_payment');
                    myAdminDropzone.processQueue();
                    // location.reload();
                }
            }
        });

        // $('#partial_uploadfile').click(function () {
        //     if(myAdminDropzone.files.length==0){
        //         Swal.fire({
        //             position: 'center',
        //             type: 'error',
        //             title: "{{ trans('general.upload_file_error') }}",
        //             showConfirmButton: true,
        //             confirmButtonText: "{{ trans('general.ok') }}",
        //         }); 
        //         return false;
        //     }else{
        //         var data=$("#adminPartialFileupload").serialize();
        //         console.log(data,'data')
        //         hideModel('admin_partial_payment');
        //         myAdminDropzone.processQueue();
        //         location.reload();
        //     }
        // });
        // end admin partial form

        // admin discount form
        var jj = 0;
        var kk = 0;
        var myDiscountDropzone = new Dropzone("#adminDiscountFileupload", {
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
                    hideModel('admin_discount_payment_model');
                });

                this.on("sending", function (file, xhr, formData) {
                });

                this.on("addedfiles", function (files) {
                });
                this.on("complete", function(file) {
                    if (this.getQueuedFiles().length == 0 && this.getUploadingFiles()
                        .length == 0) {
                        var _this = this;
                    }
                }); 

                this.on("success", function (file, response) {
                    console.log('success',response.status);
                });

                this.on("error", function (file, errorMessage) {
                    //Swal.fire("{{trans('general.error_text')}}", "{{trans('form.registration.investigation.document_not_allowed')}}", "error");
                });
                this.on('sending', function(file, xhr, formData) {
                    // Append all form inputs to the formData Dropzone will POST
                    var data = $('#adminDiscountFileupload').serializeArray();
                    $.each(data, function(key, el) {
                        formData.append(el.name, el.value);
                    });
                });

                this.on("queuecomplete", function (file) {
                    myDiscountDropzone.removeAllFiles(true);
                    Swal.fire({
                        title: "{{trans('general.updated_text')}}", 
                        text: "{{trans('form.registration.investigation.document_uploaded')}}", 
                        type: "{{trans('general.success_text')}}",
                        confirmButtonText: "{{ trans('general.ok') }}",
                    }).then(function (result) {
                        if(result.value){
                            // location.reload();
                        }
                    });
                    $('#client_paid').remove();
                });
            }
        });

        $('#closmod').click(function () {
            myDiscountDropzone.forElement("#adminDiscountFileupload").removeAllFiles(true);
        });

        $('#closmod2').click(function (){
            myDiscountDropzone.forElement("#adminDiscountFileupload").removeAllFiles(true);
        });

        $('#discount_uploadfile').click(function () {
            if(myDiscountDropzone.files.length==0){
                Swal.fire({
                    position: 'center',
                    type: 'error',
                    title: "{{ trans('general.upload_file_error') }}",
                    showConfirmButton: true,
                    confirmButtonText: "{{ trans('general.ok') }}",
                }); 
                return false;
            }else{
                var data=$("#adminDiscountFileupload").serialize();
                console.log(data,'data')
                hideModel('admin_discount_payment_model');
                myDiscountDropzone.processQueue();
                location.reload();
            }
        });
        // end admin discount form

        // admin mark as paid form
        var jj = 0;
        var kk = 0;
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var myFullPaymentAdminDropzone = new Dropzone("#adminFullPaymentfileupload", {
            url: '{{ route('client-invoice-pay') }}',
            headers: {
                'x-csrf-token': CSRF_TOKEN,
            },
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
                    // myDropzone.removeFile(file);
                    console.log('success',response.status);
                });

                this.on("error", function (file, errorMessage) {
                    console.log('File upload error');
                    //Swal.fire("{{trans('general.error_text')}}", "{{trans('form.registration.investigation.document_not_allowed')}}", "error");
                });

                /*    this.on("complete", function(file) {

                    // console.log('Finally done');

                    // myDropzone.removeFile(file);

                    //Swal.fire('Uploaded!', "{{trans('form.registration.investigation.document_uploaded')}}", "success");

                });  */

                this.on('sending', function(file, xhr, formData) {
                    // Append all form inputs to the formData Dropzone will POST
                    var data = $('#adminFullPaymentfileuploadForm').serializeArray();
                    $.each(data, function(key, el) {
                        formData.append(el.name, el.value);
                    });
                });

                this.on("queuecomplete", function (file) {
                    myFullPaymentAdminDropzone.removeAllFiles(true);
                    Swal.fire({
                        title: "{{trans('general.updated_text')}}", 
                        text: "{{trans('form.registration.investigation.document_uploaded')}}", 
                        type: "{{trans('general.success_text')}}",
                        confirmButtonText: "{{ trans('general.ok') }}",
                    }).then(function (result) {
                        
                    });
                    $('#client_paid').remove();
                });
            }
        });

        $('#closmod').click(function () {
            myFullPaymentAdminDropzone.forElement("#adminFullPaymentfileupload").removeAllFiles(true);
        });

        $('#closmod2').click(function (){
            myFullPaymentAdminDropzone.forElement("#adminFullPaymentfileupload").removeAllFiles(true);
        });

        $("#adminFullPaymentfileuploadForm").validate({
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
                if(myFullPaymentAdminDropzone.files.length==0){
                    Swal.fire({
                        position: 'center',
                        type: 'error',
                        title: "{{ trans('general.upload_file_error') }}",
                        showConfirmButton: true,
                        confirmButtonText: "{{ trans('general.ok') }}",
                    }); 
                    return false;
                }else{
                    var data=$("#adminFullPaymentfileuploadForm").serialize();
                    console.log(data,'data')
                    hideModel('admin_full_payment_model');
                    myFullPaymentAdminDropzone.processQueue();
                    location.reload();
                }
                // 
            }
        });

        // $('#fullPaymentUploadfile').click(function () {
        //     if(myFullPaymentAdminDropzone.files.length==0){
        //         Swal.fire({
        //             position: 'center',
        //             type: 'error',
        //             title: "{{ trans('general.upload_file_error') }}",
        //             showConfirmButton: true,
        //             confirmButtonText: "{{ trans('general.ok') }}",
        //         }); 
        //         return false;
        //     }else{
        //         var data=$("#adminFullPaymentfileupload").serialize();
        //         console.log(data,'data')
        //         hideModel('admin_full_payment_model');
        //         myFullPaymentAdminDropzone.processQueue();
        //         location.reload();
        //     }
        // });
        // end admin mark as paid form

        $("body").on("click", "#deletefile", function () {
            var id = $(this).data("id");
            $(".doctr_" + id).remove(); 
        });

        $("body").on("click", "#mark_final_paid", function () {

            return false;
            var invoice_id = $(this).data("id");
            var investigationId = $(this).data("investigation");
            var type = $(this).data("type");
            var performaId = $(this).data("performaId");
            Swal.fire({
                title: "{{trans('general.are_you_sure')}}",
                text: "{{trans('form.investigationinvoice.mark_final_paid_msg')}}" + status + '?',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#34c38f",
                cancelButtonColor: "#f46a6a",
                confirmButtonText: "{{trans('general.yes_generate')}}",
                cancelButtonText: "{{trans('general.cancel')}}"
            }).then(function (result) {
                if (result.value) {
                    //  

                    $.ajax({
                        url: "{{ route('invoice.mark-final-paid') }}",
                        headers: {
                            'x-csrf-token': CSRF_TOKEN,
                        },
                        type: 'POST',
                        data: {
                            "invoice_id": invoice_id,
                            "investigation_id": investigationId,
                            "type": type,
                            "payment_type" : "full",
                            "performaId": performaId,
                        },
                        success: function (response){
                            if(response.status == "success"){
                                Swal.fire({
                                    title: "",
                                    text: response.message,
                                    confirmButtonText: "{{ trans('general.ok') }}",
                                }).then(function (result) {
                                    if (result.value) {
                                        location.reload();
                                    }
                                });
                            } else {
                                Swal.fire("{{trans('general.error_text')}}", (response.message) ? response.message : "{{trans('general.something_wrong')}}", "error");
                            }
                        }, 
                        error: function (response){
                            Swal.fire("{{trans('general.error_text')}}", (response.message) ? response.message : "{{trans('general.something_wrong')}}", "error");
                        }
                    });
                    // 
                }
            });
            
        });

        $("#discount_payment_form").validate({
            rules: {
                discount_amount: {
                    required: true,
                    number:true,
                },
            },
            messages: 
            {
                
            }
        });

        $("#parital_payment_form").validate({
            rules: {
                parital_amount: {
                    required: true,
                    number:true,
                },
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
                
            }
        });
    });

    function dosubmit() {
        if($("#discount_payment_form").valid() === true){
            return true;
        }else{
            return false;
        }
    }

    function generateinvoice(invid) {
        let docCost = 0;
        subjectsData = [];
        var docCostData = [];
        var dkey = 0;
        $(".doc-cost").each(function(){
            var val = $(this).html() ? parseFloat(($(this).html().substring(1))) : 0;
            docCostData[dkey] = {'id': $(this).attr('data-id'), price: val};
            dkey++;
            // notDocCost += $(this).html() ? parseFloat(($(this).html().substring(1))) : 0;
        })
        docCostData = docCostData.map(JSON.stringify).reverse().filter(function(item, index, arr){ return arr.indexOf(item, index + 1) === -1; }).reverse().map(JSON.parse)
        
        docCostData.map(data=>{
            docCost += data.price
        });
        let notDocCost = 0;
        var notDocCostData = [];
        var nkey = 0;
        $(".not-doc-cost").each(function(){
            var val = $(this).html() ? parseFloat(($(this).html().substring(1))) : 0;
            notDocCostData[nkey] = {'id': $(this).attr('data-id'), price: val};
            nkey++;
            // notDocCost += $(this).html() ? parseFloat(($(this).html().substring(1))) : 0;
        })
        notDocCostData = notDocCostData.map(JSON.stringify).reverse().filter(function(item, index, arr){ return arr.indexOf(item, index + 1) === -1; }).reverse().map(JSON.parse)
        
        notDocCostData.map(data=>{
            notDocCost += data.price
        })
        $("input[name='sub-amt[]']")
                .map(function(){
                    var obj = {};
                    obj['id'] = $(this).data('id');
                    obj['cost'] = $(this).val();            
                    subjectsData.push(obj);
                }).get();
        
        
        subjectsData = subjectsData.map(JSON.stringify).reverse().filter(function(item, index, arr){ return arr.indexOf(item, index + 1) === -1; }).reverse().map(JSON.parse);
        
        Swal.fire({
            title: "{{trans('general.are_you_sure')}}",
            text: "{{trans('form.investigationinvoice.generate_invoice_msg')}}" + status + '?',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#34c38f",
            cancelButtonColor: "#f46a6a",
            confirmButtonText: "{{trans('general.yes_generate')}}",
            cancelButtonText: "{{trans('general.cancel')}}"
        }).then(function (result) {
            if (result.value) {
                console.log(subjectsData);
                $.ajax(
                    {
                        url: "/investigations/client/invoice/generate",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            investigationId: invid,
                            subjects: JSON.stringify(subjectsData),
                            doccost: docCost,
                            notdoccost: notDocCost,      
                        },
                        success: function (response) {
                            if (response.status == 'success') {
                                Swal.fire({
                                    title: "{{trans('general.success_text')}}", 
                                    text: (result.message) ? result.message : "{{trans('form.investigationinvoice.invoice_generated_msg')}}", 
                                    type: "success",
                                    confirmButtonText: "{{ trans('general.ok') }}",
                                })
                                    .then((result) => {
                                        if (result.value) {
                                            location.reload(true);
                                        }
                                    });
                            } else {
                                Swal.fire("{{trans('general.error_text')}}", (result.message) ? result.message : "{{trans('general.something_wrong')}}", "error");
                            }
                        }
                    }
                );
            } else {
                Swal.close();
            }
        });
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
        var dataId = [];
        let key = 0;
        $(".sub-amt").each(function(){
            var val = $(this).val() ? parseFloat($(this).val()) : 0;
            dataId[key] = {'id': $(this).attr('data-id'), price: val};
            key++;
        });
        dataId = dataId.map(JSON.stringify).reverse().filter(function(item, index, arr){ return arr.indexOf(item, index + 1) === -1; }).reverse().map(JSON.parse)
        
        dataId.map(data => {
            yourArray.push(data.price)
        })

        var docCost = 0;
        // $(".doc-cost").each(function(){
        //     docCost += $(this).html() ? parseFloat(($(this).html().substring(1))) : 0;
        // })

        var docCostData = [];
        var dkey = 0;
        $(".doc-cost").each(function(){
            var val = $(this).html() ? parseFloat(($(this).html().substring(1))) : 0;
            docCostData[dkey] = {'id': $(this).attr('data-id'), price: val};
            dkey++;
            // notDocCost += $(this).html() ? parseFloat(($(this).html().substring(1))) : 0;
        })
        docCostData = docCostData.map(JSON.stringify).reverse().filter(function(item, index, arr){ return arr.indexOf(item, index + 1) === -1; }).reverse().map(JSON.parse)
        
        docCostData.map(data=>{
            docCost += data.price
        });


        var totalSubCost = yourArray.reduce((a, b) => a + b, 0);
        totalSubCost += docCost;
        $('.sub-total').html(totalSubCost.toFixed(2));
        var tax = $('.tax').html();
        
        var totalCost = (((totalSubCost * parseFloat(tax)) / 100) + totalSubCost)
        var totalAfterTax = parseFloat(((totalSubCost.toFixed(2) * parseFloat(tax)) / 100))
        $('.total-after-tax').html((totalAfterTax + totalSubCost).toFixed(2));
        
        var notDocCost = 0;
        var notDocCostData = [];
        var nkey = 0;
        $(".not-doc-cost").each(function(){
            var val = $(this).html() ? parseFloat(($(this).html().substring(1))) : 0;
            notDocCostData[nkey] = {'id': $(this).attr('data-id'), price: val};
            nkey++;
            // notDocCost += $(this).html() ? parseFloat(($(this).html().substring(1))) : 0;
        })
        notDocCostData = notDocCostData.map(JSON.stringify).reverse().filter(function(item, index, arr){ return arr.indexOf(item, index + 1) === -1; }).reverse().map(JSON.parse)
        
        notDocCostData.map(data=>{
            notDocCost += data.price
        })
        totalCost+=notDocCost;
        $('.final-total').html(totalCost.toFixed(2));
    }
</script>

@endsection