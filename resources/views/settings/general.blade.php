@extends('layouts.master')

@section('title') {{ trans('form.general_setting.general_setting') }} @endsection

@section('headerCss')
    <!-- headerCss -->
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/colReorder.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/select.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.css') }}">

@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 col-sm-4 col-xs-12">
                            <h4 class="card-title">{{ trans('form.general_setting.update_tax') }}</h4>
                        </div>
                    </div>
                    @include('session-message')
                    <div class="row mt-3">
                        <div class="col-12">
                            <form action="{{ route('settings.update-tax') }}" method="POST" id="update-tax" class="form needs-validation">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-12 col-md-6">
                                        @php
                                            $tax = '0.0';
                                            $taxSetting = $settings->firstWhere('key', 'tax');
                                            if($taxSetting){
                                                $tax = $taxSetting->value;
                                            }
                                        @endphp
                                        <input  type="number" class="form-control" id="tax" name="tax" placeholder="{{ trans('form.general_setting.update_tax') }}" value="{{ $tax }}"  step="0.01" required/>
                                        @error('tax')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-12 col-md-6">
                                        <button type="submit" class="btn btn-primary" id="update-tax-btn">{{trans('form.update')}}</button>
                                    </div>
                                </div>
                               
                            </form>
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
<script src="{{ URL::asset('/libs/jquery-validate/1.19.0/jquery.validate.js')}}"></script>
<script src="{{ URL::asset('/libs/inputmask/5.0.5/jquery.inputmask.js') }}" async></script>
@if (App::isLocale('hr'))
    <script src="{{ URL::asset('/libs/jquery-validate/1.19.0/localization/messages_he.js')}}"></script>
@endif
<script src="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.js') }}" async></script>

<!-- footerScript -->
<script type="text/javascript"> 
    
    $(function() {
        $('#update-tax').on('submit',function(e){
            e.preventDefault();

            Swal.fire({
                title: "{{trans('general.are_you_sure')}}",
                text: "{{trans('form.general_setting.update_tax_msg')}}",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#34c38f",
                cancelButtonColor: "#f46a6a",
                confirmButtonText: "{{trans('general.yes_change')}}",
                cancelButtonText: "{{trans('general.cancel')}}"
            }).then(function (result) {
                if (result.value) {
                    var form = $('#update-tax');
                    var url = form.attr('action');
                    var data = form.serialize();
                    console.log(data);
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: data,
                        success: function(response)
                        {
                            if (response.status == 'success') {
                                Swal.fire("{{trans('general.success_text')}}", (result.message) ? result.message : "{{trans('form.general_setting.confirm_updated')}}", "success")
                                    .then((result) => {
                                        if (result.value) {
                                            location.reload(true);
                                        }
                                    });
                            } else {
                                Swal.fire("{{trans('general.error_text')}}", (result.message) ? result.message : "{{trans('general.something_wrong')}}", "error");
                            }
                        }
                    });
                } else {
                    Swal.close();
                }
            });
        });
    });

</script>

@endsection