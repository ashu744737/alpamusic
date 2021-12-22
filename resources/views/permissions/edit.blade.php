@extends('layouts.master')

@section('title') {{ trans('form.permissions_form.edit_permission') }} @endsection

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
                            <h4 class="card-title">{{ trans('form.permissions_form.edit_permission') }}</h4>
                        </div>
                    </div>
                    @include('session-message')
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ route('permission.update', ['perm_id' => $permission->id]) }}" method="POST" id="edit_permission" class="form needs-validation">
                                @csrf
                                
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="name" class="ul-form__label">{{ trans('form.permissions_form.permission_name') }} </label>
                                        <input  type="text" class="form-control" id="name" name="name" placeholder="{{ trans('form.permissions_form.permission_name') }}" value="{{ old('name') ? old('name') : $permission->name }}"  />
                                        @error('name')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                    
                                
                                <div class="form-row">
                                    <div class="col-md-12 mb-4">
                                        <input type="hidden" name="permission_id" value="{{ $permission->id }}" />
                                        <button type="submit" class="btn btn-primary" id="create-customer-btn">{{trans('form.update')}}</button>
                                        <a href="{{ route('permission.index') }}" class="btn btn-outline-secondary m-1">{{trans('form.cancel')}}</a>
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
    <!-- footerScript -->
    <script type="text/javascript">
        $(function() {  
            $("#edit_permission").validate({        
                rules: {
                    name: {
                        required: true 
                    },  
                },
                messages: {
                    name: {
                        required: "This field required" 
                    },
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