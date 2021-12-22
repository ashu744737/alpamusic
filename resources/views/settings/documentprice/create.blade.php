@extends('layouts.master')

@section('title') {{ trans('form.documenttypes.add_document_type') }} @endsection

@section('headerCss')
    <!-- headerCss -->
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/colReorder.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/select.dataTables.min.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 col-sm-4 col-xs-12">
                            <h4 class="card-title">{{ trans('form.documenttypes.add_document_type') }}</h4>
                        </div>
                    </div>
                    @include('session-message')
                    <div class="row mt-3">
                        <div class="col-12">
                            <form action="{{ route('documentprice.store') }}" method="POST" id="create_product" class="form needs-validation">
                                @csrf
                               
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="name" class="ul-form__label">{{ trans('form.documenttypes.name') }} <span class="text-danger">*</span></label>
                                        <input  type="text" class="form-control" id="name" name="name" placeholder="{{ trans('form.documenttypes.name') }}" value="{{ old('name') }}"  />
                                        @error('name')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="hr_name" class="ul-form__label">{{ trans('form.documenttypes.hebrew_name') }} <span class="text-danger">*</span></label>
                                        <input  type="text" class="form-control" id="hr_name" name="hr_name" placeholder="{{ trans('form.documenttypes.hebrew_name') }}" value="{{ old('hr_name') }}"  />
                                        @error('hr_name')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="price" class="ul-form__label">{{trans('form.documenttypes.price')}}({{ trans('general.money_symbol') }}) <span class="text-danger">*</span></label>
                                        <input  type="text" class="form-control" id="price" name="price" placeholder="{{trans('form.documenttypes.price')}}" value="{{ old('price') }}" />
                                        @error('price')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-group row">
                                            <label for="inv_name" class="col-form-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">{{ trans('form.documenttypes.include_vat') }}</label>
                                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                                                <input name="include_vat" id="include_vat" type="checkbox" switch="bool" />
                                                <label class="mt-2" for="include_vat" data-on-label="{{ trans('general.yes') }}" data-off-label="{{ trans('general.no') }}"> </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="col-md-12 mb-4">
                                        <button type="submit" class="btn btn-primary" id="create-customer-btn">{{trans('form.create')}}</button>
                                        <a href="{{ route('documentprice.index') }}" class="btn btn-outline-secondary m-1">{{trans('form.cancel')}}</a>
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
    <!-- footerScript -->
    <script type="text/javascript"> 
        
        $(function() {
            $("#create_product").validate({           
                rules: { 
                    name: {
                        required: true,
                    },
                    hr_name: {
                        required: true,
                    },
                    price:{
                        required: true,
                    }
                },
                messages: 
                {
                   
                }
            });

          

          });

        </script>
    <!--  datatable js -->
    <script src="{{ URL::asset('/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/datatables/dataTables.colReorder.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/datatables/dataTables.select.min.js') }}"></script>

@endsection