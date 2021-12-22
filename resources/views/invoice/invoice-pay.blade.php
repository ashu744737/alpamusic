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
            <li class="breadcrumb-item">
                @foreach($invoice->investigation as $investigation)
                <a href="{{route('investigation.show', [Crypt::encrypt($investigation->id)]) }}">
                    <span class="badge dt-badge badge-dark">{{$investigation->work_order_number}} </span> &nbsp;
                </a>
                @endforeach
            </li>
            <li class="breadcrumb-item"><a href="javascript: void(0);">{{ trans('form.investigationinvoice.invoice') }}</a></li>
        </ol>
    </div>
</div>

@include('layouts.partials.session-message')

<div class="row" id="invoicedata">
        @include('invoice.invoice-pay-inner')
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

        $("body").on("click", "#deletefile", function () {
            var id = $(this).data("id");
            $(".doctr_" + id).remove(); 
        });

        $("body").on("click", "#mark_final_paid", function () {
            var id = $(this).data("id");
            $.ajax({
                url: "{{ route('invoice.mark-final-paid') }}",
                type: 'POST',
                data: {
                    "invoice_id": id,
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (response){
                    console.log(response,'response')
                    if(response.status == "success"){
                        Swal.fire({
                            title: "",
                            text: response.message,
                            confirmButtonText: "{{ trans('general.ok') }}",
                        }).then(function (result) {
                            if (result.value) {
                                // location.reload();
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
        });

        $("#discount_payment_form").validate({
            rules: {
                discount_amount: {
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

        $("#parital_payment_form").validate({
            rules: {
                parital_amount: {
                    required: true,
                    number:true,
                },
            },
            messages: 
            {
                
            }
        });

        $("#mark_as_final_paid_form").validate({
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