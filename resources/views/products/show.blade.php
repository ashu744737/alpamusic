@extends('layouts.master')

@section('title') {{ trans('form.products_form.product_details') }} @endsection

@section('headerCss')
@endsection

@section('content')

    <div class="col-sm-6">
        <div class="page-title-box">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{route('product.index')}}">{{ trans('form.products_form.products') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript: void(0);">{{ $product->name }}</a></li>
            </ol>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card investogators_Details">
                <div class="card-body"> 
    
                    <div class="row">
                        <div class="col-12">

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <!-- BASIC DETAILS -->
                                <div class="tab-pane active py-3" id="basic_details" role="tabpanel">
                                    <h4 class="section-title mb-3 pb-2">{{ trans('form.products_form.basic_detail') }}</h4>

                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.products_form.product_name') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $product->name ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.products_form.price') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $product->price ?? '-' }}
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.products_form.spouse_cost') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $product->spouse_cost ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.products_form.is_del') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $product->is_delivery == 1 ? trans('general.yes') : trans('general.no')  }}
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.products_form.del_cost') }}</label>
                                                <div class="col-form-label col-8">
                                                    {{ $product->delivery_cost ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group row">
                                                <label for="inv_name"
                                                    class="col-form-label col-4">{{ trans('form.products_form.category') }}</label>
                                                <div class="col-form-label col-8">
                                                    @if(!empty($product->category))
                                                        {{ config('app.locale') == 'hr' ? $product->category->hr_name : $product->category->name }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>  
                                    </div>

                                    <hr />
                                    <h4 class="section-title mb-3 pb-2">{{ trans('form.products_form.doc_detail') }}</h4>

                                    @if($product->documents->isNotEmpty())
                                        <table class="table table-bordered">
                                            <thead>
                                                <th>{{ trans('form.products_form.doc_name') }}</th>
                                                <th>{{ trans('form.products_form.doc_type') }}</th>
                                                <th>{{ trans('form.products_form.download') }}</th>
                                            </thead>
                                            <tbody>
                                                @foreach($product->documents as $document)
                                                    <tr>
                                                        <td>
                                                            {{ $document->doc_name }}
                                                        </td>
                                                        <td>
                                                            {{ trans($document->doc_type) }}
                                                        </td>
                                                        <td>
                                                            @php
                                                                $docurl = '/product-documents/'.$document->file_name;
                                                            @endphp
                                                            <a class="doc-link" title=" {{ trans('general.download_doc')  }}" href="{{ URL::asset($docurl) }}" target="_blank"><i class="fas fa-download"></i></a>
                                                            &nbsp;<a class="doc-link" title="{{ trans('general.delete') }}" href="{{ route('product.delete', $document->id) }}"><i class="fas fa-trash"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <h6 class="text-center">{{ trans('form.products_form.no_doc') }}</h6>
                                    @endif

                                </div>

                            </div>
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
   <script src="{{ URL::asset('/libs/select2/select2.min.js')}}"></script>
   <script src="{{ URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>  
@endsection