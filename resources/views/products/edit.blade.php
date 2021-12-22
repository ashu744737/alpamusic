@extends('layouts.master')

@section('title') {{ trans('form.products_form.edit_product') }} @endsection

@section('headerCss')
    <!-- headerCss -->
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/colReorder.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/select.dataTables.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        #product_doc-error{
            display: block;
        }

        #product_doc_del-error{
            display: block;
        }
    </style>

@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 col-sm-4 col-xs-12">
                            <h4 class="card-title">{{ trans('form.products_form.edit_product') }}</h4>
                        </div>
                    </div>
                    @include('session-message')
                    <div class="row mt-3">
                        <div class="col-12">
                            <form action="{{ route('product.update', ['prod_id' => $product->id]) }}" method="POST" id="edit_product" class="form needs-validation" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="name" class="ul-form__label">{{ trans('form.products_form.product_name') }} <span class="text-danger">*</span></label>
                                        <input  type="text" class="form-control" id="name" name="name" placeholder="{{ trans('form.products_form.product_name') }}" value="{{ old('name') ? old('name') : $product->name }}"  />
                                        @error('name')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="spouse_cost" class="ul-form__label">{{trans('form.products_form.spouse_cost')}} </label>
                                <select name="spouse_cost" id="spouse_cost" class="form-control " required>
                                  <option value="0"  {{ old('spouse_cost', $product->spouse_cost) == 0 ? 'selected' : '' }}>0%</option>
                                  <option value="50" {{ old('spouse_cost', $product->spouse_cost) == 50 ? 'selected' : '' }}>50%</option>
                                  <option value="100" {{ old('spouse_cost', $product->spouse_cost) == 100 ? 'selected' : '' }}>100%</option>
                                        
                                    </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="price" class="ul-form__label">{{trans('form.products_form.price')}}({{ trans('general.money_symbol') }}) <span class="text-danger">*</span></label>
                                        <input  type="text" class="form-control" id="price" name="price" placeholder="{{trans('form.products_form.price')}}" value="{{ old('price') ? old('price') : $product->price }}" />
                                        @error('price')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="price" class="ul-form__label">{{trans('form.products_form.category')}} </label>
                                        <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                            @foreach($categories as $id => $category)
                                                <option value="{{ $category['id'] }}" {{ old('category_id', $product->category_id) == $category['id'] ? 'selected' : '' }}>{{ App::isLocale('hr')?$category['hr_name']:$category['name'] }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                        <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="is_delivery" name="is_delivery" value="1" {{$product->is_delivery == '1' ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="is_delivery">{{ trans('form.products_form.is_del') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row dc-div" style="{{ $product->is_delivery != '1' ? 'display:none' : '' }}">
                                    <div class="form-group col-md-12">
                                        <label for="name" class="ul-form__label">{{ trans('form.products_form.del_cost') }}({{ trans('general.money_symbol') }}) </label>
                                        <input  type="number" class="form-control" id="delivery_cost" name="delivery_cost" placeholder="{{ trans('form.products_form.del_cost') }}" value="{{ old('delivery_cost', $product->delivery_cost) }}"  step="0.01" required/>
                                        @error('delivery_cost')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <hr>

                                <div class="form-row">
                                    <div class="col-12 mb-2">
                                        <h4 class="card-title">{{ trans('form.products_form.upload_document') . ' ' . trans('form.products_form.for_inv') }}</h4>
                                    </div>
                                    @php
                                        $investigationDoc = $product->documents->where('doc_type','Investigator Document')->first();

                                        $deliveryboyDoc = $product->documents->where('doc_type','Deliveryboy Document')->first();
                                        
                                    @endphp

                                    <div class="form-group col-md-6">
                                        <input type="file" class="" id="product_doc" name="product_doc">
                                        @if($investigationDoc)
                                            {{ $investigationDoc->doc_name }}
                                        @endif
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-12 mb-2">
                                        <h4 class="card-title">{{ trans('form.products_form.upload_document') . ' ' . trans('form.products_form.for_del') }}</h4>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <input type="file" class="" id="product_doc_del" name="product_doc_del">
                                        @if($deliveryboyDoc)
                                            {{ $deliveryboyDoc->doc_name }}
                                        @endif
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-12 mb-4">
                                        <input type="hidden" name="product_id" value="{{ $product->id }}" />
                                        <button type="submit" class="btn btn-primary" id="create-customer-btn">{{trans('form.update')}}</button>
                                        <a href="{{ route('product.index') }}" class="btn btn-outline-secondary m-1">{{trans('form.cancel')}}</a>
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

        $.validator.addMethod('filesize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        }, '{{ trans('form.products_form.filesize_val') }}');

        $(function() {  
            $("#edit_product").validate({        
                rules: {
                    name: {
                        required: true 
                    },
                    price:{
                        required: true
                    },
                    product_doc: {
                        filesize: 1024*1024*8,
                    },
                    product_doc_del: {
                        filesize: 1024*1024*8,
                    },
                },
                messages: {
                   
                }
            });

            $('#is_delivery').on('change', function(){
                if(this.checked){
                    $('.dc-div').show();
                }else{
                    $('.dc-div').hide();
                }
            });

        }); 
    </script>
    <!-- Required datatable js -->
    <script src="{{ URL::asset('/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/datatables/dataTables.colReorder.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/datatables/dataTables.select.min.js') }}"></script>

@endsection