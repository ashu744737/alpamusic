@extends('layouts.master')

@section('title') {{ trans('form.my_profile.myprofile') }}  @endsection

@section('headerCss')
<link rel="stylesheet" href="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.css') }}">
<link href="{{ URL::asset('/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet">
<link href="{{ URL::asset('/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}" rel="stylesheet">
<link href="{{ URL::asset('/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}" rel="stylesheet" />

@endsection

@section('content')

    <div class="col-sm-6">
        <div class="page-title-box">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('general.home') }}</a></li>
                <li class="breadcrumb-item"><a href="#">{{ trans('form.my_profile.myprofile') }}</a></li>
                
            </ol>
        </div>
    </div>
    <!-- content -->
    <div class="row">
        <div class="col-12">
            <div class="card investogators_Details">
                <div class="card-body">

                    <div class="row">
                        <div class="col-12">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-2" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#basic_details" role="tab">
                                        <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                        <span class="d-none d-sm-block">{{ trans('form.registration.client.basic_details') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#addresses" role="tab">
                                        <span class="d-block d-sm-none"><i class="far fa-address-book"></i></span>
                                        <span class="d-none d-sm-block">{{ trans('form.registration.client.address') }}</span>
                                    </a>
                                </li>  
                            </ul>
    
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <!-- BASIC DETAILS -->
                                <div class="tab-pane active py-3" id="basic_details" role="tabpanel">
                                    <h4 class="section-title mb-3 pb-2">{{ trans('form.registration.client.personal_details') }} <button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" onclick="shohideprofile('edit_profile','old_profile')"><i class="fas fa-edit"></i> {{ trans('form.my_profile.editprofile') }} </button>
                                         </h4>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-card alert-success d-none" id="msg_div">
                                                <span id="res_message"></span>
                                            </div>
                                        </div>
                
                                     </div>
                                    
                                <div id="old_profile">
                                    @include('myprofile.clientajax.personaldetail', ['type'=>'investigator','user' => $user])
                                    
                                </div>
                                <div class="d-none" id="edit_profile">
                                    <form name="client-form" id="client-form" class="form-horizontal mt-4" method="POST" action="javascript:void(0)"   >

                                        @csrf

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-card alert-danger d-none" id="msg_div2">
                                                    <span id="res_message2"></span>
                                                </div>
                                            </div>
                    
                                         </div>

                                       <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <div class="form-group">
                                                <label for="username">{{ trans('form.name') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" class="form-control @error('name') is-invalid @enderror" autofocus id="name" placeholder="{{ trans('form.enter_name') }}">
                                                @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="form-group">
                                                <label for="useremail">{{ trans('form.email_address') }} <span class="text-danger">*</span></label>
                                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" id="useremail" placeholder="{{ trans('form.enter_email') }}" autocomplete="email" required>
                                                @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                         <div class="form-group col-md-4">
                                            <label>{{ trans('form.registration.investigator.family') }} </label>
                                                                <input type="text" value="{{ $user->investigator->family }}" class="form-control" id="family" name="family" placeholder="{{ trans('form.registration.investigator.family') }}">
                                                                @error('family')
                                                                <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                        </div>
                                    </div>
                                    <div class="form-row">
                                    
                                        <div class="form-group col-md-4">
                                            <label>{{ trans('form.registration.investigator.id_number') }} </label>
                                                                <input type="text" value="{{ $user->investigator->idnumber}}" class="form-control" id="idnumber" name="idnumber" placeholder="{{ trans('form.registration.investigator.id_number') }}">
                                                                @error('idnumber')
                                                                <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror 
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>{{ trans('form.registration.client.website') }}</label>
                                            <input type="text" value="{{ $user->investigator->website }}" class="form-control" id="website" name="website" placeholder="{{ trans('form.registration.client.website') }}">
                                            @error('website')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror 
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label>{{ trans('form.registration.investigator.date_of_birth') }}</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="{{trans('general.date_format')}}" id="dob" name="dob" value="{{ date('d/m/Y', strtotime($user->investigator->dob)) }}">
                                                <div class="input-group-append">
                                                <span class="input-group-text"><i
                                                    class="mdi mdi-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label>{{ trans('form.registration.investigator.fields_of_specialization') }} <span class="text-danger">*</span></label>
                                            <select required multiple="multiple" class="select2 form-control select2-multiple input_required_s2" id="specializations" name="specializations[]" data-placeholder="{{ trans('form.registration.investigator.fos_helper') }}">
                                                @foreach($specializations as $id => $specialization)
                                                    <option value="{{ $specialization['id'] }}" {{ in_array($specialization['id'], $specs) == $specialization['id'] ? 'selected' : '' }}>{{ App::isLocale('hr')?$specialization['hr_name']:$specialization['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="type" value="investigator"/>
                                    <div class="form-row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <div class="form-group row">
                                            <div class="col-12 text-center text-xs-center">
                                                <button id="submit_personaldata" onclick="personaldetailupate('old_profile','edit_profile')" type="submit" class="btn btn-primary">{{ trans('general.save') }}</button>
                                                <a href="javascript:void(0);" onclick="shohideprofile('old_profile','edit_profile')" class="btn btn-secondary">{{ trans('general.cancel') }}</a>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                                
                                 <hr />
                                    <h4 class="section-title mb-3 pb-2">{{ trans('form.registration.client.additional_details') }}</h4>
                                   
                                    <div class="row">
                                        
                                        <div id="phone_edit_model" class="modal fade bs-example-modal-center  bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                
                                        </div>
                                        <div id="phone_add_model" class="modal fade bs-example-modal-center  bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                
                                        </div>
                                        <div class="col-lg-3" id="phones_data">
                                        @include('myprofile.clientajax.viewajaxmodel', ['type'=>'phones','user' => $user, 'contacttypes' => $contacttypes])
                                        </div>
                                        <div class="col-lg-3" id="mobiles_data">
                                        @include('myprofile.clientajax.viewajaxmodel', ['type'=>'mobiles','user' => $user, 'contacttypes' => $contacttypes])
                                        </div>
                                        <div class="col-lg-3" id="emails_data">
                                        @include('myprofile.clientajax.viewajaxmodel', ['type'=>'emails','user' => $user, 'contacttypes' => $contacttypes])
                                        </div>
                                        <div class="col-lg-3" id="faxes_data">
                                        @include('myprofile.clientajax.viewajaxmodel', ['type'=>'faxes','user' => $user, 'contacttypes' => $contacttypes])                                
                                        </div>
                                    </div>
                                    <hr/>
                                    <h4 class="section-title mb-3 pb-2">{{ trans('form.registration.investigator.bank_details') }} <button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" onclick="shohideprofile('edit_bank','old_bank')"><i class="fas fa-edit"></i> {{ trans('form.my_profile.editbank') }} </button>
                                    </h4>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-card alert-success d-none" id="bankmsg_div">
                                                <span id="bankres_message"></span>
                                            </div>
                                        </div>
                
                                     </div>
                                    
                                    <div id="old_bank">
                                        @include('myprofile.clientajax.bankdetails', ['type'=>'investigator','user' => $user])
                                        
                                    </div>
                                    <div class="d-none" id="edit_bank">
                                        <form name="bank-form" id="bank-form" class="form-horizontal mt-4" method="POST" action="javascript:void(0)"   >
    
                                            @csrf
    
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="alert alert-card alert-danger d-none" id="bankmsg_div2">
                                                        <span id="bankres_message2"></span>
                                                    </div>
                                                </div>
                        
                                             </div>
    
                                           <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <div class="form-group">
                                                    <label for="company">{{ trans('form.registration.investigator.company') }} <span class="text-danger">*</span></label>
                                                    <input value="{{  !is_null($user->investigator->investigatorBankDetail)?$user->investigator->investigatorBankDetail->company:'' }}" type="text" class="form-control input_required_s3 @error('company') is-invalid @enderror" name="company" id="company" placeholder="{{ trans('form.registration.investigator.company') }}">
                                                    @error('company')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <div class="form-group">
                                                    <label for="name_of_bank">{{ trans('form.registration.investigator.name_of_bank') }} <span class="text-danger">*</span>
                                                                </label>
                                                                <select id="name_of_bank"
                                                                        name="name_of_bank"
                                                                        class="form-control input_required_s3">
                                                                    <option value="FIBI" {{ (!is_null($user->investigator->investigatorBankDetail) && $user->investigator->investigatorBankDetail->name == 'FIBI') ? 'selected' : '' }}>{{trans('form.name_of_bank.FIBI')}}</option>
                                                                    <option value="PNC" {{ (!is_null($user->investigator->investigatorBankDetail) && $user->investigator->investigatorBankDetail->name == 'PNC') ? 'selected' : '' }}>{{trans('form.name_of_bank.PNC')}}</option>
                                                                    <option value="CitiGroup" {{ (!is_null($user->investigator->investigatorBankDetail) && $user->investigator->investigatorBankDetail->name == 'CitiGroup') ? 'selected' : '' }}>{{trans('form.name_of_bank.CitiGroup')}}</option>
                                                                </select>

                                                                @error('name_of_bank')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                </div>
                                            </div>
                                             <div class="form-group col-md-4">
                                                <label for="bank_number">{{ trans('form.registration.investigator.bank_number') }} <span class="text-danger">*</span></label>
                                                                <input type="text"
                                                                       value="{{ !is_null($user->investigator->investigatorBankDetail)?$user->investigator->investigatorBankDetail->number:'' }}"
                                                                       class="form-control input_required_s3 @error('name_of_bank') is-invalid @enderror"
                                                                       name="bank_number"  id="bank_number"
                                                                       placeholder="{{ trans('form.registration.investigator.bank_number') }}">
                                                                @error('bank_number')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-row">
                                        
                                            <div class="form-group col-md-4">
                                                <label for="branch_name">{{ trans('form.registration.investigator.branch_name') }} <span class="text-danger">*</span></label>
                                                                <input value="{{ !is_null($user->investigator->investigatorBankDetail)?$user->investigator->investigatorBankDetail->branch_name:'' }}" type="text" class="form-control input_required_s3 @error('branch_name') is-invalid @enderror" name="branch_name" id="branch_name" placeholder="{{ trans('form.registration.investigator.branch_name') }}">
                                                                @error('branch_name')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="branch_no">{{ trans('form.registration.investigator.branch_no') }} <span class="text-danger">*</span></label>
                                                                <input value="{{ !is_null($user->investigator->investigatorBankDetail)?$user->investigator->investigatorBankDetail->branch_number:'' }}" type="text" class="form-control input_required_s3 @error('branch_no') is-invalid @enderror" name="branch_no" id="branch_no" placeholder="{{ trans('form.registration.investigator.branch_no') }}">
                                                                @error('branch_no')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                            </div>
    
                                            <div class="form-group col-md-4">
                                                <label for="account_no">{{ trans('form.registration.investigator.account_no') }} <span class="text-danger">*</span></label>
                                                                <input value="{{ !is_null($user->investigator->investigatorBankDetail)?$user->investigator->investigatorBankDetail->account_no:'' }}" type="text" class="form-control input_required_s3 @error('account_no') is-invalid @enderror" name="account_no" id="account_no" placeholder="{{ trans('form.registration.investigator.account_no') }}">
                                                                @error('account_no')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                            </div>
                                        </div>
                          
                                        <input type="hidden" name="type" value="investigator"/>
                                        <div class="form-row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                            <div class="form-group row">
                                                <div class="col-12 text-center text-xs-center">
                                                    <button id="submit_bankdata" onclick="bankdetailupate('old_bank','edit_bank')" type="submit" class="btn btn-primary">{{ trans('general.save') }}</button>
                                                    <a href="javascript:void(0);" onclick="shohideprofile('old_bank','edit_bank')" class="btn btn-secondary">{{ trans('general.cancel') }}</a>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                    
                                </div>
    
                                <!-- DOCUMENTS -->
                                <div class="tab-pane p-3" id="addresses" role="tabpanel">
                                    <div class="row">
                                        <div class="col-lg-12">
                                       
                                                <h4 class="section-title mb-3 pb-2">{{ trans('form.registration.client.address_details') }} <button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" onclick="addeditModal('address_add_model','address',1,'add')"><i class="fas fa-plus"></i> {{ trans('form.my_profile.addnew_address') }} </button>
                                                </h4>
                                                </div>
                                        
                                    </div>
                                    <div id="address_edit_model" class="modal fade bs-example-modal-center  bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"">
                                                
                                    </div>
                                    <div id="address_add_model" class="modal fade bs-example-modal-center  bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                   
                                    </div>
                                
                                <div id="address_data">
                                    @include('myprofile.clientajax.viewajaxmodel', ['type'=>'address','user' => $user,]) 
                                    </div>
                                </div>
    
                                
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
    <script src="{{ URL::asset('/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
    <script src="{{ URL::asset('/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')}}"></script>
    <script src="{{ URL::asset('/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{ URL::asset('/js/pages/form-advanced.init.js')}}"></script>
    <script src="{{ URL::asset('/libs/jquery-validate/1.19.0/jquery.validate.js')}}"></script>  
   <script src="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.js') }}" async></script>
   <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_KEY')}}&libraries=places&language={{ App::isLocale('hr') ? 'iw' : 'en' }}" async defer></script>
   <script src="{{ URL::asset('/libs/inputmask/5.0.5/jquery.inputmask.js') }}" async></script>
   @if (App::isLocale('hr'))
    <script src="{{ URL::asset('/libs/jquery-validate/1.19.0/localization/messages_he.js')}}"></script>
    @endif
   <script>
      $(document).ready(function() {
            $('#dob').datepicker({
                autoclose: true,
                todayHighlight: true
            }); 
        });
        function addeditModal(modelid,type,id,modeltype) {
            $.ajax({
                url: '{{ route("myprofile.contactsdata") }}' ,
                type: "GET",
                data: {
                   "_token": "{{ csrf_token() }}",
                   "type": type,
                   "modeltype": modeltype,
                   "id": id
                },
                success: function( response ) {
                    if(response.status==1){
                            $('#'+modelid).html(response.html);
                            showModal(modelid);  
                    }else{
                        $('#'+modelid).modal('hide');      
                    }
                }

            });
           
            }
            function showModal(id) {
                $('#'+id).modal('show');
            }
            function hideModel(id) {
                $('#'+id).modal('hide');
            }
        function shohideprofile(id,id2) {
             $("#"+id).removeClass('d-none');
				$("#"+id).addClass('d-block');
                $("#"+id2).removeClass('d-block');
				$("#"+id2).addClass('d-none');			
        }
        // This Function for Address Type Dropdown Othertext to open textbox
		function changeotheroldtextbox(t){
			var id = t.id;
			if(t.value=='Other' || t.value=='Contact'){
				$("#"+id+"_div").addClass('d-block');
				$("#"+id+"_div").removeClass('d-none');

                var contactType = <?php echo $contacttypes; ?>;
                
                contactType.map((contact, idx) => {
                    if(contact.type_name == t.value){
                        @if(config('app.locale') == 'hr')
                            $("#"+id+"_olabel").html(contact.hr_type_name+' {{trans("form.registration.client.type")}}');
                            $("#"+id+"_otext").attr("placeholder", '{{trans("general.enter")}} '+contact.hr_type_name+' {{trans("form.registration.client.type")}}');
                        @else
                        $("#"+id+"_olabel").html(contact.type_name+' {{trans("form.registration.client.type")}}');
                        $("#"+id+"_otext").attr("placeholder", '{{trans("general.enter")}} '+contact.type_name+' {{trans("form.registration.client.type")}}');
                        @endif
                    }
                })

                $("#"+id+"_otext").rules('add', {required: true });
                $("#"+id+"_otext").attr("required", true);
			}else{
				$("#"+id+"_div").addClass('d-none');
				$("#"+id+"_div").removeClass('d-block');
                $("#"+id+"_otext").rules('remove', 'required');
                $("#"+id+"_otext").removeAttr('required');
			}
    	}
        function deleteform(id,type,outputid){
            
            $.ajax({
                url: '{{ route("myprofile.contactsdata.update") }}' ,
                type: "POST",
                data: "id="+id+"&type="+type+"&optype=delete&_token={{ csrf_token() }}",
                success: function( response ) {
                    if(response.status==1){
                            
                                Swal.fire(
                                "{{trans('general.deleted_text')}}",
                                (response.msg) ? response.msg : "{{trans('general.successfully_delete')}}",
                                "{{trans('general.success_text')}}"
                                )
                                $('#'+outputid).html(response.html);
                    }else{ 
                       Swal.fire(
                                "{{trans('general.error_text')}}",
                                (response.msg) ? response.msg : "{{trans('general.something_wrong')}}",
                                "{{trans('general.error_text')}}"
                            )    
                    }
                    
                }

            });
        }
        function updateform(id,type,outputid){
            if($("#"+id).valid() === true){
            $('.editbtn').text('{{ trans("general.updating") }}');
            $.ajax({
                url: '{{ route("myprofile.contactsdata.update") }}' ,
                type: "POST",
                data: $('#'+id).serialize()+ "&type="+type+"&optype=update",
                success: function( response ) {
                    if(response.status==1){
                            
                                $('.editbtn').text('{{ trans("general.update") }}');
                            Swal.fire(
                                "{{ trans('general.updated_text') }}",
                                (response.msg) ? response.msg : "{{ trans('general.successfully_update') }}",
                                "{{ trans('general.success_text') }}"
                            )
                            if(type=='address')
                            hideModel('address_edit_model');
                            else if(type=='contact')
                            hideModel('contact_edit_model');
                            else
                            hideModel('phone_edit_model');
                           $('#'+outputid).html(response.html);
                    }else{ 
                       Swal.fire(
                                "{{ trans('general.error_text') }}",
                                (response.msg) ? response.msg : "{{ trans('general.something_wrong') }}",
                                "{{ trans('general.error_text') }}"
                            ) 
                            $('.editbtn').text('{{ trans("general.update") }}');     
                    }
                    
                }

            });
            }
        }
      
        function savecontactform(id,type,outputid){
            if($("#"+id).valid() === true){
                    $('.addbtn').text('{{ trans("general.adding") }}');
                    $.ajax({
                        url: '{{ route("myprofile.contactsdata.update") }}' ,
                        type: "POST",
                        data: $('#'+id).serialize()+ "&type="+type+"&optype=add",
                        
                        success: function( response ) {
                            if(response.status==1){
                                    
                                   $('.addbtn').text('{{ trans("general.save") }}');
                                    Swal.fire(
                                        "{{ trans('general.added') }}",
                                        (response.msg) ? response.msg : "{{ trans('general.successfully_added') }}",
                                        "{{ trans('general.success_text') }}"
                                    )
                                    if(type=='address')
                                    hideModel('address_add_model');
                                    else if(type=='contact')
                                    hideModel('contact_add_model');
                                    else
                                    hideModel('phone_add_model');
                                $('#'+outputid).html(response.html);
                            }else{ 
                            Swal.fire(
                                        "{{ trans('general.error_text') }}",
                                        (response.msg) ? response.msg : "{{ trans('general.something_wrong') }}",
                                        "{{ trans('general.error_text') }}"
                                    )  
                                    $('.addbtn').text('{{ trans("general.save") }}');    
                            }
                            
                        }

                        });
            }    
     }
        $("body").on("click", ".delete-record", function(e){
                var id = $(this).data('id');
                var type = $(this).data('type');
                var outputid = $(this).data('outputid');       
                Swal.fire({
                    title: "{{ trans('general.confirm_delete') }}",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0CC27E',
                    cancelButtonColor: '#FF586B',
                    confirmButtonText: "{{ trans('general.yes_delete') }}",
                    cancelButtonText: "{{ trans('general.no_cancel') }}",
                    confirmButtonClass: 'btn btn-lg btn-success mr-5',
                    cancelButtonClass: 'btn btn-lg btn-danger',
                    buttonsStyling: false
                }).then((result) => {
                  if (result.value) {
                    deleteform(id,type,outputid);
                  } else if (result.dismiss == "cancel") {
                    
                  }
                })
            });
    </script>
  <script>
function personaldetailupate(id1,id2){
    if ($("#client-form").length > 0) {
 
            $("#client-form").validate({

            rules: {
            name: {required: true,},
            email: {
                required: true,email: true,
                remote: {

                url: "myprofile/checkemail",
                type: "post",
                data: {
                   "_token": "{{ csrf_token() }}",
                    email: $("input[email='email']").val()
                },
                dataFilter: function(data) {
                    var json = JSON.parse(data);
                    if (json.msg == "true") {
                        return "\"" + "Email address already in use" + "\"";
                    } else {
                        return 'true';
                    }
                }
            }      

            },
            },
            messages: {
             
            },

            submitHandler: function(form) {

            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

            });

            $('#submit_personaldata').html('{{ trans("general.updating") }}');
            $.ajax({
                url: '{{ route("myprofile.update") }}' ,
                type: "POST",
                data: $('#client-form').serialize(),
                success: function( response ) {
                    if(response.status==1){
                            $('#submit_personaldata').html('{{ trans("general.save") }}');

                            $('#res_message').show();
                            $('#msg_div').show();
                            $('#res_message').html(response.msg);
                            $('#msg_div').removeClass('d-none');

                            $('#'+id1).html(response.html);
                            shohideprofile(id1,id2);
                            setTimeout(function(){
                            $('#res_message').hide();
                            $('#msg_div').hide();
                            },7000);
                    }else{
                            $('#res_message2').show();
                            $('#msg_div2').show();
                            $('#res_message2').html(response.msg);
                            $('#msg_div2').removeClass('d-none');
                            setTimeout(function(){
                            $('#res_message2').hide();
                            $('#msg_div2').hide();
                            $('#submit_personaldata').html('{{ trans("general.save") }}');
                            },7000);
                    }                   

                }
            });
            }

            })

            }
}

function bankdetailupate(id1,id2){
    if ($("#bank-form").length > 0) {
 
            $("#bank-form").validate({
            rules: {
            company: {required: true,},
            bank_number: {required: true,},
            branch_name: {required: true,},
            branch_no: {required: true,},
            account_no: {required: true,},
            },
            messages: {
                company: {required: "Please Enter Company Name",},  
            },

            submitHandler: function(form) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#submit_bankdata').html('{{ trans("general.updating") }}');
            $.ajax({
                url: '{{ route("myprofile.bankupdate") }}' ,
                type: "POST",
                data: $('#bank-form').serialize(),
                success: function( response ) {
                    if(response.status==1){
                            $('#submit_bankdata').html('{{ trans("general.save") }}');

                            $('#bankres_message').show();
                            $('#bankmsg_div').show();
                            $('#bankres_message').html(response.msg);
                            $('#bankmsg_div').removeClass('d-none');

                            $('#'+id1).html(response.html);
                            shohideprofile(id1,id2);
                            setTimeout(function(){
                            $('#bankres_message').hide();
                            $('#bankmsg_div').hide();
                            },7000);
                    }else{
                            $('#bankres_message2').show();
                            $('#bankmsg_div2').show();
                            $('#bankres_message2').html(response.msg);
                            $('#bankmsg_div2').removeClass('d-none');
                            setTimeout(function(){
                            $('#bankres_message2').hide();
                            $('#bankmsg_div2').hide();
                            $('#submit_bankdata').html('{{ trans("general.save") }}');
                            },7000);
                    }                   

                }
            });
            }

            })

            }
}
    
 
 </script>
  <script type="text/javascript">
      
      
    // this code for set  google api autocomplete address dynamically add 
    function setAddressnew(count){
        var autocompletes  = [];
        var options = {
            types: ['geocode'],
            language: '{{ App::isLocale('hr') ? 'iw' : 'en' }}',
        //componentRestrictions: { country: ["fr","IL","us","UK"] } 
        };
        
        var input = document.getElementById('address_complete_' + count);
        
        var autocomplete = new google.maps.places.Autocomplete(input, options);
        autocomplete.inputId = input.id;
        autocomplete.count = count;
        autocomplete.addListener('place_changed', fillIn);
        autocompletes.push(autocomplete);
    }
     // find the country id fron given json
     function getcountryid(cntr, value) {
        for (var i = 0; i < cntr.length; i++){
            if (cntr[i].code == value){
                return cntr[i].id;
                }
            }
            return 0;
        }
    function fillIn() {
        const componentForm = {
          street_number: "long_name",
          route: "long_name",
          locality: "long_name",
          administrative_area_level_1: "long_name",
          country: "short_name",
          postal_code: "short_name",
        };
        var cntr=@json($countries);
        var place = this.getPlace();
      
        document.getElementById('address2_'+this.count).value="";
        document.getElementById('state_'+this.count).value="";
        document.getElementById('city_'+this.count).value="";
        document.getElementById('zipcode_'+this.count).value="";      
        for (const component of place.address_components) {
                const addressType = component.types[0];
                if (componentForm[addressType]) {
                const val = component[componentForm[addressType]];
                    if(addressType==="country"){
                        var countryid=getcountryid(cntr, val);
                        if(countryid!=0){
                            document.getElementById('country_'+this.count).value=countryid;
                        }  
                    }
                    if(addressType==="street_number"){
                        if(val)
                        {document.getElementById('address2_'+this.count).value+=val+' ';}
                    }
                    if(addressType==="route"){
                        if(val)
                        document.getElementById('address2_'+this.count).value+=val;
                    }
                    if(addressType==="administrative_area_level_1"){
                        document.getElementById('state_'+this.count).value=val;
                    }
                    if(addressType==="locality"){
                        document.getElementById('city_'+this.count).value=val;
                    }
                    if(addressType==="postal_code"){
                        document.getElementById('zipcode_'+this.count).value=val;
                    }
                }
            }
      
    }
 
</script>
 
@endsection