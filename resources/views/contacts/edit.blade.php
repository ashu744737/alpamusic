@extends('layouts.master')

@section('title') {{ trans('form.contact.edit_contact') }} @endsection

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
                            <h4 class="card-title">{{ trans('form.contact.edit_contact') }}</h4>
                        </div>
                    </div>
                    @include('session-message')
                    <div class="row mt-3">
                        <div class="col-12">
                            <form action="{{ route('contacts.update', ['contact_id' => $contact->id]) }}" method="POST" id="edit_customer" class="form needs-validation" onSubmit="return dosubmit();">
                                @csrf
                                <div class="form-row">
                                    @if(auth()->user()->type_id == 1)
                                    <div class="form-group col-md-3">
                                        <label for="SelectUserType" class="ul-form__label">{{ trans('form.contact.entity_type') }} </label>
                                        <select class="form-control" name="user_type" id="user_type">
                                            <option value="">{{ trans('form.contact.field.select_entity_type') }}</option>
                                            @foreach($userType as $type)
                                            <option value="{{$type->id}}" {{ old('user_type') == $type->id ? 'selected' : $contact->user_type_id == $type->id ? 'selected' : '' }}>
                                            {{ App::isLocale('hr')?$type->hr_type_name:$type->type_name }}</
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('user_type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="SelectUser" class="ul-form__label">{{ trans('form.contact.entity_name') }} </label>
                                        <select class="form-control" name="user" id="user">
                                            <option value="">{{ trans('form.contact.field.select_entity_name') }}</option>
                                        </select>
                                        @error('user')
                                        <div class="validation-errors">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    @endif
                                    @php $seloth=1; @endphp

                                    @if(auth()->user()->type_id == 1)
                                    <div class="form-group col-md-3">
                                    @else
                                    <div class="form-group col-md-6">
                                    @endif
                                        <label for="ContactType" class="ul-form__label">{{ trans('form.contact.contact_type') }} </label>
                                        <select onchange="changeothertextbox(this)" id="contactypeid" class="form-control" name="contact_type_id" id="contact_type_id">
                                            <option value="">{{ trans('form.contact.field.select_contact_type') }}</option>
                                            @foreach($contactType as $type)
                                            <option {{ (($type->type_name == 'Other' && $seloth == 0) || ($type->type_name == $contact->contact_type)) ? 'selected' : '' }}  value="{{$type->type_name}}" >{{$type->type_name}}</option>
                                            @endforeach
                                        </select>
                                        @error('contact_type_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    
                                    @if(auth()->user()->type_id == 1)
                                    <div class="form-group col-md-3 {{ ($seloth == 0)? 'd-block' : 'd-none' }}" id="contactypeid_div">
                                    @else
                                    <div class="form-group col-md-6 {{ ($seloth == 0)? 'd-block' : 'd-none' }}">
                                    @endif
                                   
                                        <label>{{ trans('form.registration.client.type') }}</label>
                                            
                                        <input type="text" value="{{ ($seloth==0) ? $contact->contact_type  : '' }}" id="contactypeid_otext"
                                            class="form-control numeric"
                                            name="other_text"
                                            placeholder="{{ trans('form.registration.client.type_helper') }}"
                                            >
                                    </div>
                                    
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="FirstName" class="ul-form__label">{{ trans('form.contact.field.first_name') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="{{ trans('form.contact.field.first_name') }}" value="{{ old('first_name') ? old('first_name') : $contact->first_name }}" />
                                        @error('first_name')
                                        <div class="validation-errors">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="LastName" class="ul-form__label">{{ trans('form.contact.field.last_name') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="{{ trans('form.contact.field.last_name') }}" value="{{ old('last_name') ? old('last_name') : $contact->last_name }}" />
                                        @error('last_name')
                                        <div class="validation-errors">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="FamilyName" class="ul-form__label">{{ trans('form.contact.field.family_name') }} </label>
                                        <input type="text" class="form-control" id="family_name" name="family_name" placeholder="{{ trans('form.contact.field.family_name') }}" value="{{ old('family_name') ? old('family_name') : $contact->family }}" />
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="WorkPlace" class="ul-form__label">{{ trans('form.contact.field.work_place') }} </label>
                                        <input type="text" class="form-control" id="workplace" name="workplace" placeholder="{{ trans('form.contact.field.work_place') }}" value="{{ old('workplace') ? old('workplace') : $contact->workplace }}" />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="Phone" class="ul-form__label">{{trans('form.contact.field.phone')}} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="phone" name="phone" placeholder="999-999-9999" value="{{ old('phone') ? old('phone') : $contact->phone }}" />
                                        @error('phone')
                                        <div class="validation-errors">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="mobile" class="ul-form__label">{{trans('form.contact.field.mobile')}} </label>
                                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="999-999-9999" value="{{ old('mobile') ? old('mobile') : $contact->mobile }}" />
                                        @error('mobile')
                                        <div class="validation-errors">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="Fax" class="ul-form__label">{{trans('form.contact.field.fax')}} </label>
                                        <input type="text" class="form-control" id="fax" name="fax" placeholder="{{trans('general.fax_placeholder')}}" value="{{ old('fax') ? old('fax') : $contact->fax }}" />
                                        @error('fax')
                                        <div class="validation-errors">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="Email" class="ul-form__label">{{trans('form.contact.field.email')}} </label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="{{trans('form.contact.field.email')}}" value="{{ old('email') ? old('email') : $contact->email }}" />
                                        @error('email')
                                        <div class="validation-errors">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <input type="checkbox" class="ml-2" name="is_default" id="is_default" {{ ($contact->is_default==1 ? 'checked':'') }}>
                                        <span>{{trans('form.contact.field.is_default')}}</span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12 mb-4">
                                        <input type="hidden" name="contact_id" value="{{ $contact->id }}" />
                                        <button type="submit" class="btn btn-primary" id="create-customer-btn">{{trans('form.update')}}</button>
                                        <a href="{{ route('contacts') }}" class="btn btn-outline-secondary m-1">{{trans('form.cancel')}}</a>
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
        $('#fax').inputmask('999-999-9999', {
                autoUnmask: true,
                removeMaskOnSubmit:true
        });
        $('#mobile').inputmask('999-999-9999', {
                autoUnmask: true,
                removeMaskOnSubmit:true
        });
        $('#phone').inputmask('999-999-9999', {
                autoUnmask: true,
                removeMaskOnSubmit:true
         });
            $("#edit_customer").validate({           
                rules: {
                    user_type: {
                        required: true
                    },
                    user: {
                        required: true
                    },
                    contact_type_id: {
                        required: true
                    },
                    first_name: {
                        required: true 
                    },
                    last_name: {
                        required: true,
                    },
                    phone: {
                        required: true ,
                        number:true,
                       
                    },
                    mobile: {
                        number:true,
                        
                    },
                    fax: {
                        number:true,
                    },
                    email:{
                        email: true
                    }
                },
                messages: 
                {
                    
                }
            });
            let users = <?php echo $users ?>;
            let userType = $('#user_type').val();
            let userId = <?php echo $contact->user_id ?>;
            if(userType) {
                let userData = "<option value=''>{{ trans('form.contact.field.select_user') }}</option>";
                let status = false;
                users.map((user, key) => {
                    let userId = <?php echo $contact->user_id ?>;
                    if(user.type_id == userType) {
                        userData+=`<option value="${user.id}" ${userId == user.id ? 'selected' : ''}>${user.name} (${user.email})</option>`;
                        status = true;
                    }
                });
                $('#user').html(userData)
            }
            $('#user_type').on('change', function(){
                let userType = $('#user_type').val();
                let userData = "<option value=''>{{ trans('form.contact.field.select_user') }}</option>";
                let status = false;
                users.map((user, key) => {
                    if(user.type_id == userType) {
                        userData+=`<option value="${user.id}" ${userId == user.id ? 'selected' : ''}>${user.name} (${user.email})</option>`;
                        status = true;
                    }
                });
                $('#user').html(userData)
            })
        });
        function dosubmit() {
            $('#phone').rules('add', { number:true});
            $('#mobile').rules('add', { number:true});
            if($("#edit_customer").valid() === true){
                return true;
            }else{
                return false;
            }
        }
       
        function changeothertextbox(t){
			var id = t.id;
			if(t.value=='Other'){
				$("#"+id+"_div").addClass('d-block');
                $("#"+id+"_div").removeClass('d-none');
                $("#"+id+"_otext").prop('required',true);
			}else{
				$("#"+id+"_div").addClass('d-none');
                $("#"+id+"_div").removeClass('d-block');
                $("#"+id+"_otext").prop('required',false);
			}
    	}
    </script>
    <!-- Required datatable js -->
    <script src="{{ URL::asset('/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/datatables/dataTables.colReorder.min.js') }}"></script>
    <script src="{{ URL::asset('/libs/datatables/dataTables.select.min.js') }}"></script>

@endsection