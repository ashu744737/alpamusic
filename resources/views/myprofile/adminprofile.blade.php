@extends('layouts.master')

@section('title') {{ trans('form.my_profile.myprofile') }} @endsection

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
                                    @include('myprofile.clientajax.personaldetail', ['type'=>'admin','user' => $user])
                                    
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
                                        <div class="form-group col-md-6">
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
                                        <div class="form-group col-md-6">
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
                                        <input type="hidden" name="user_id" value="{{ $user->id }}" />
                                        <input type="hidden" name="changetype" value="basic" />
                                        <input type="hidden" name="type" value="admin" />
                                    </div>
                                    
                                   
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
   <script src="{{ URL::asset('/libs/jquery-validate/1.19.0/jquery.validate.js')}}"></script>  
   <script src="{{ URL::asset('/libs/sweetalert2/sweetalert2.min.js') }}" async></script>
   <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_KEY')}}&libraries=places&language={{ App::isLocale('hr') ? 'iw' : 'en' }}" async defer></script>
   @if (App::isLocale('hr'))
    <script src="{{ URL::asset('/libs/jquery-validate/1.19.0/localization/messages_he.js')}}"></script>
    @endif
  
  <script>
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

            $('#submit_personaldata').html("{{ trans('general.updating') }}");
            $.ajax({
                url: '{{ route("myprofile.update") }}' ,
                type: "POST",
                data: $('#client-form').serialize(),
                success: function( response ) {
                    if(response.status==1){
                            $('#submit_personaldata').html("{{ trans('general.save') }}");

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
                            $('#submit_personaldata').html("{{ trans('general.save') }}");
                            },7000);
                    }                   

                }
            });
            }

            })

            }
}


 
 </script>
 
@endsection