@extends('layouts.master')

@section('title') {{ trans('form.usertype_form.edit_usertype') }} @endsection

@section('headerCss')
    <!-- headerCss -->
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/colReorder.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/select.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}" rel="stylesheet" />
    
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 col-sm-4 col-xs-12">
                            <h4 class="card-title">{{ trans('form.usertype_form.edit_usertype') }}</h4>
                        </div>
                    </div>
                    @include('session-message')
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ route('usertype.update', ['type_id' => $usertype->id]) }}" method="POST" id="edit_usertype" class="form needs-validation">
                                @csrf
                                
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="type_name" class="ul-form__label">{{ trans('form.usertype_form.usertype') }} </label>
                                        <input required disabled type="text" class="form-control" id="type_name" name="type_name" placeholder="{{ trans('form.usertype_form.usertype') }}" value="{{ old('type_name') ? old('type_name') : $usertype->type_name }}"  />
                                        @error('type_name   ')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label class="control-label" for="permissions">{{ trans('form.permissions_form.permissions') }}*
                                        <span class="btn btn-primary btn-xs select-all">{{ trans('general.select_all') }}</span>
                                        <span class="btn btn-primary btn-xs deselect-all">{{ trans('general.deselect_all') }}</span></label>
                                        
                                        <select name="permissions[]" id="permissions" class="select2 form-control select2-multiple" multiple="multiple" required>
                                        @foreach($permissions as $id => $permission)
                                            <option value="{{ $id }}" {{ (in_array($id, old('permissions', [])) || isset($usertype) && $usertype->permissions->contains($id)) ? 'selected' : '' }}>{{ $permission }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('permissions'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('permissions') }}
                                    </em>
                                @endif
                                </div>
                                </div>
                    
                                
                                <div class="form-row">
                                    <div class="col-md-12 mb-4">
                                        <input type="hidden" name="type_id" value="{{ $usertype->id }}" />
                                        <button type="submit" class="btn btn-primary" id="create-customer-btn">{{trans('form.update')}}</button>
                                        <a href="{{ route('usertype.index') }}" class="btn btn-outline-secondary m-1">{{trans('form.cancel')}}</a>
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
<script src="{{ URL::asset('/libs/select2/select2.min.js')}}"></script>
   <script src="{{ URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{ URL::asset('/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
    <script src="{{ URL::asset('/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')}}"></script>
    <script src="{{ URL::asset('/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{ URL::asset('/js/pages/form-advanced.init.js')}}"></script>
    <!-- footerScript -->
    <script type="text/javascript">
        $(function() {  
            $('.select-all').click(function () {
                let $select2 = $(this).parent().siblings('.select2')
                $select2.find('option').prop('selected', 'selected')
                $select2.trigger('change')
            })
            $('.deselect-all').click(function () {
                let $select2 = $(this).parent().siblings('.select2')
                $select2.find('option').prop('selected', '')
                $select2.trigger('change')
            })
            $("#edit_usertype").validate({        
                rules: {
                    permissions: {
                        required: true 
                    },  
                },
                messages: {
                    permissions: {
                        required: '{{ trans("form.usertype_form.select_permission_helper") }}' 
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