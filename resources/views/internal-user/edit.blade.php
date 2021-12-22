@extends('layouts.master')

@section('title') {{ trans('form.internal_user.edit_user') }} @endsection

@section('headerCss')
    <!-- headerCss -->
    <!-- DataTables -->
    <link href="{{ URL::asset('/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/colReorder.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/datatables/select.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}" rel="stylesheet" />
    <link href="{{ URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet">
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 col-sm-4 col-xs-12">
                            <h4 class="card-title">{{ trans('form.internal_user.edit_user') }}</h4>
                        </div>
                    </div>
                    @include('session-message')
                    <div class="row mt-3">
                        <div class="col-12">
                            <form action="{{ route('staff.update', ['user_id' => $user->id]) }}" method="POST" id="edit_user" class="form needs-validation" onSubmit="return dosubmit();">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="ContactType" class="ul-form__label">{{ trans('form.internal_user.type') }} </label>
                                        <select onchange="changeusertype('user_type_id')" class="form-control" name="user_type_id" id="user_type_id">
                                            <option value="">{{ trans('form.contact.field.select_contact_type') }}</option>
                                            @foreach($userTypes as $type)
                                            <option value="{{$type->id}}" {{ (!empty(old('user_type_id')) && old('user_type_id') == $type->id ? 'selected' : $user->type_id == $type->id) ? 'selected' : '' }}>{{ App::isLocale('hr')?$type->hr_type_name:$type->type_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('user_type_id')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row  categorydiv d-none }}">
                                    <div class="form-group col-12">
                                        <label  for="category">{{ trans('form.products_form.category') }} <span class="text-danger">*</span></label>
                                             
                                            <select name="category[]" id="category" class="select2 form-control select2-multiple" multiple="multiple">
                                                @foreach($categories as $id=>$category)
                                                <option value="{{$category['id']}}" {{ (in_array($category['id'], old("category", [])) || isset($user) && $user->userCategories->contains('category_id',$category['id'])) ? 'selected' : '' }}>
                                                    {{ App::isLocale('hr')?$category['hr_name']:$category['name'] }}
                                                </option>
                                                @endforeach
                                        </select>
                                        @if($errors->has('category'))
                                        <em class="invalid-feedback">
                                            {{ $errors->first('category') }}
                                        </em>
                                    @endif
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="Name" class="ul-form__label">{{ trans('form.internal_user.name') }} </label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="{{ trans('form.internal_user.name') }}" value="{{ old('name') ? old('name') : $user->name }}" />
                                        @error('name')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="Email" class="ul-form__label">{{ trans('form.contact.field.email') }} </label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="{{ trans('form.contact.field.email') }}" value="{{ old('email') ? old('email') : $user->email }}" />
                                        @error('email')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="userpassword">{{ trans('form.password') }} </label>
                                        <input type="password" minlength="6" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password" id="userpassword" placeholder="{{ trans('form.enter_password') }}">
                                        @error('password')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="userpassword">{{ trans('form.registration.confirm_password') }}</label>
                                        <input type="password" minlength="6" name="password_confirmation" class="form-control" id="userconfirmpassword" placeholder="{{ trans('form.registration.confirm_password') }}">
                                    </div>
                                </div>
                                <div class="form-row salarydiv {{ old('salary') ? 'd-block' : 'd-none' }}">
                                    <div class="form-group col-12">
                                        <label  for="salary">{{ trans('form.products_form.salary') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="salary" name="salary" placeholder="{{ trans('form.products_form.salary') }}" value="{{ old('salary') ? old('salary') : $user->salary }}"  />
                                        @if($errors->has('salary'))
                                        <em class="invalid-feedback">
                                            {{ $errors->first('salary') }}
                                        </em>
                                    @endif
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12 mb-4">
                                        <input type="hidden" name="user_id" value="{{ $user->id }}" />
                                        <button type="submit" class="btn btn-primary" id="create-customer-btn">{{trans('form.update')}}</button>
                                        <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary m-1">{{trans('form.cancel')}}</a>
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
<script src="{{ URL::asset('/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{ URL::asset('/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')}}"></script>
<script src="{{ URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{ URL::asset('/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
<script src="{{ URL::asset('/js/pages/form-advanced.init.js')}}"></script>

@if (App::isLocale('hr'))
    <script src="{{ URL::asset('/libs/jquery-validate/1.19.0/localization/messages_he.js')}}"></script>
    @endif
    <!-- footerScript -->
    <script type="text/javascript">
        $(function() {  
            $("#edit_user").validate({        
                rules: {
                    user_type_id: {
                        required: true
                    },
                    name: {
                        required: true 
                    },
                    email:{
                        required: true,
                        email: true
                    },
                },
                messages: {
                   
                }
            });
        });
        function dosubmit() {
            var val=$('#user_type_id option:selected').text();
            var sm="{{env('USER_TYPE_STATION_MANAGER')}}";
            
            $.ajax({
                type: "GET",
                url:"/usertypes/get-usertype/"+val,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if(data == 1){

                    } else {
                        $("#category").empty();
                    }
                    let errorMsg = "{{trans('general.password_validation')}}";
                    $.validator.addMethod('checkParams', function (value) { 
                        return /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/i.test(value); 
                    }, errorMsg);
                    if($('.salarydiv').hasClass('d-block')){
                        $('#salary').rules('add', {required: true, number: true});
                    }
                    $('#userpassword').rules('add', {minlength: 6, checkParams: true});
                    $('#userconfirmpassword').rules('add', {minlength: 6,equalTo: '[name="password"]'});
                    if($("#create_user").valid() === true){
                        return true;
                    }else{
                        return false;
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                },
            });
        }
        //Change User Type for SM
        function changeusertype(id){
            var id = id;
            var val=$('#'+id+'').val();
            $.ajax({
                type: "GET",
                url:"/usertypes/get-usertype/"+val,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if(data == 1){
                        $(".categorydiv").removeClass('d-none');
                        $(".categorydiv").addClass('d-block');
                        $(".select2-container").css({'width': '100%'});
                        $("#category").prop('required',true);

                        $(".salarydiv").removeClass('d-none');
                        $(".salarydiv").addClass('d-block');
                        // $(".select2-container").css({'width': '100%'});
                        $("#salary").prop('required',true);
                    }else{
                        if(data == "Accountant"){
                            $(".salarydiv").removeClass('d-none');
                            $(".salarydiv").addClass('d-block');
                            // $(".select2-container").css({'width': '100%'});
                            $("#salary").prop('required',true);

                            $(".categorydiv").removeClass('d-block');
                            $(".categorydiv").addClass('d-none');
                            $("#category").prop('required',false);
                        } else {
                            $(".categorydiv").removeClass('d-block');
                            $(".categorydiv").addClass('d-none');
                            $("#category").prop('required',false);

                            $(".salarydiv").removeClass('d-block');
                            $(".salarydiv").addClass('d-none');
                            $("#salary").prop('required',false);
                        }
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log("XHR",xhr);
                    console.log("status",textStatus);
                    console.log("Error in",errorThrown);
                }
            });
        }
    changeusertype('user_type_id');
    </script>
    <!-- Required datatable js -->
    <script src="{{ URL::asset('/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/datatables/dataTables.colReorder.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/datatables/dataTables.select.min.js') }}"></script>

@endsection