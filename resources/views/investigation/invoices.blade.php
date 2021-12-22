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
<link href="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    .focus-btn-group{
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
            @foreach($invn as $key => $invoice)
                <a href="{{route('investigation.show', [Crypt::encrypt($invoice['investigation']['id'])]) }}">{{$invoice['investigation']['work_order_number']}}@if(($key+1)!=count($invn)) ,@endif</a>
            @endforeach
            </li>
            <li class="breadcrumb-item"><a href="javascript: void(0);">{{ trans('form.investigationinvoice.invoice') }}</a></li>
        </ol>
    </div>
</div>


<div class="row" id="invoicedata">
    @include('investigation.invoice.bulk.clientinvoice')
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
<script src="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.js') }}" async></script>
<script src="{{ URL::asset('/libs/dropzone/dropzone.min.js') }}"></script>
<script src="{{ URL::asset('/libs/magnific-popup/magnific-popup.min.js')}}"></script>
<script src="{{ URL::asset('/js/pages/lightbox.init.js')}}"></script>
<script>
    Dropzone.autoDiscover = false;
    $(function () {
        calculateTotal();
        $(".sub-amt").on('change', function(){
            calculateTotal();
        });

        var jj = 0;
        var kk = 0;
        var myDropzone = new Dropzone("#filebulkupload", {
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
                    var data = $('#filebulkupload').serializeArray();
                    $.each(data, function(key, el) {
                        formData.append(el.name, el.value);
                    });
                });

                this.on("queuecomplete", function (file) {
                    myDropzone.removeAllFiles(true);
                    Swal.fire({
                        title: "{{trans('general.uploaded_text')}}", 
                        text: "{{trans('form.registration.investigation.document_uploaded')}}", 
                        type: "success",
                        confirmButtonText: "{{ trans('general.ok') }}",
                    });
                    $('#client_paid').remove();
                });
            }
        });

        $('#closmod').click(function () {
            Dropzone.forElement("#filebulkupload").removeAllFiles(true);
        });

        $('#closmod2').click(function (){
            Dropzone.forElement("#filebulkupload").removeAllFiles(true);
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
                var data=$("#filebulkupload").serialize();
                console.log(data,'data')
                hideModel('pay_model');
                myDropzone.processQueue();
            }
        });

        // admin partial form
        var jj = 0;
        var kk = 0;
        var myAdminDropzone = new Dropzone("#adminPartialFileupload", {
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
                    var data = $('#adminPartialFileupload').serializeArray();
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
                        let url = "{{route('invoices.index')}}";
                        console.log(url,'result----------------------------')
                        if(result.value){
                            location.herf = "{{route('invoices.index')}}";
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

        $('#partial_uploadfile').click(function () {
            if(myAdminDropzone.files.length==0){
                Swal.fire({
                    position: 'center',
                    type: 'error',
                    title: "{{ trans('general.upload_file_error') }}",
                    showConfirmButton: true,
                    confirmButtonText: "{{ trans('general.ok') }}",
                }); 
                return false;
            }else{
                var data=$("#adminPartialFileupload").serialize();
                console.log(data,'data')
                hideModel('admin_partial_payment');
                myAdminDropzone.processQueue();
                document.cookie = 'ids=;';
                location.herf = "{{route('invoices.index')}}";
                window.location.href = "{{ route('invoices.index')}}";
            }
        });
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
                            location.reload();
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
                document.cookie = 'ids=;';
                location.herf = "{{route('invoices.index')}}";
                window.location.href = "{{ route('invoices.index')}}";
            }
        });
        // end admin discount form

        // admin mark as paid form
        var jj = 0;
        var kk = 0;
        var myFullPaymentAdminDropzone = new Dropzone("#adminFullPaymentfileupload", {
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
                    var data = $('#adminFullPaymentfileupload').serializeArray();
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

        $('#fullPaymentUploadfile').click(function () {
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
                var data=$("#adminFullPaymentfileupload").serialize();
                console.log(data,'data')
                hideModel('admin_full_payment_model');
                myFullPaymentAdminDropzone.processQueue();
                document.cookie = 'ids=;';
                location.herf = "{{route('invoices.index')}}";
                window.location.href = "{{ route('invoices.index')}}";
            }
        });
        // end admin mark as paid form

        $("body").on("click", "#deletefile", function () {
            var id = $(this).data("id");
            $(".doctr_" + id).remove(); 
        });
    });

    function generateinvoice(invid) {
        subjectsData = [];

        $("input[name='sub-amt[]']")
                .map(function(){
                    var obj = {};
                    obj['id'] = $(this).data('id');
                    obj['cost'] = $(this).val();

                    subjectsData.push(obj);
                }).get();

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
        $(".sub-amt").each(function(){
            yourArray.push(parseFloat($(this).val()));
        });

        var totalSubCost = yourArray.reduce((a, b) => a + b, 0);
        $('.sub-total').html(totalSubCost.toFixed(2));

        var tax = $('.tax').html();
        var totalCost = ((parseFloat(tax) * totalSubCost) / 100) + (totalSubCost);
        $('.final-total').html(totalCost.toFixed(2));
    }
</script>

@endsection