@extends('layouts.auth-master')

@section('title') {{trans('form.login')}} @endsection

@section('content')
 <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-6">
                    <div class="card overflow-hidden">
                        <div class="card-body pt-0">
                            <h3 class="text-center mt-4">
                                {{-- <a href="/" class="logo logo-admin"><img src="{{ URL::asset('/images/logo-dark.png')}}" height="50" alt="logo"></a> --}}
                                <span style="color: blue;font-size:20px;">AlpaMusic</span>
                            
                            </h3>
                            <div class="p-3">
                                <h4 class="text-muted font-size-18 mb-1 text-center">@lang('form.welcome_back') !</h4>
                                <p class="text-muted text-center">@lang('form.sign_in_to_continue_to') @lang('form.app_name')</p>
                                @if(Session::has('status'))
                                <div class="alert alert-card alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    {{ Session::get('status') }}
                                </div>
                                @endif
                                
                                <form method="POST" id="login-form" class="form-horizontal mt-4" action="{{ route('login') }}">
                                       @csrf
                                    <div class="form-group">
                                        <label for="username">@lang('form.email_address')<span class="text-danger">*</span></label>
                                         <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="@lang('form.email')">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror

                                    </div>
                                    <div class="form-group">
                                        <label for="userpassword">@lang('form.password')<span class="text-danger">*</span></label>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="@lang('form.password')">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group cptch-container">
                                        <div class="g-recaptcha m-b-10"
                                             data-sitekey="{{ env('GOOGLE_RECAPTCHA_SITE_KEY') }}">
                                        </div>
                                        <span class="invalid-feedback gr-error" role="alert" style="display: none;">
                                            <strong>{{ trans('form.captcha_is_required') }}</strong>
                                        </span>
                                        @error('g-recaptcha-response')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-6">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="remember">@lang('form.remember_me')</label>
                                            </div>
                                        </div>
                                        <div class="col-6 text-right">
                                            <button class="btn btn-primary w-md waves-effect waves-light" type="submit">@lang('form.login')</button>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0 row">
                                        <div class="col-12">
                                            <a href="{{ route('password.request') }}" class="text-muted"><i class="mdi mdi-lock"></i> @lang('form.forgot_your_password')</a>
                                        </div>
                                    </div>
                                    <div class="mt-5 text-center">
                                        <p class="mb-0">@lang('form.dont_have_an_account') <a href="{{ route('clientregister') }}" class="text-primary"> {{ trans('form.clients') }}</a> | <a href="{{ route('investigatorregister') }}" class="text-primary">{{ trans('form.investigators') }}</a> | <a href="{{ route('deliveryboyregister') }}" class="text-primary">{{ trans('form.deliveryboys') }}</a> </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        
                        <p>{{ trans('general.developedby') }} <a target="_blank" href="https://soft-l.com/">{{ trans('general.company') }} </a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-js')
<script src="{{ URL::asset('/libs/jquery-validate/1.19.0/jquery.validate.js')}}"></script> 
@if (App::isLocale('hr'))
<script src="{{ URL::asset('/libs/jquery-validate/1.19.0/localization/messages_he.js')}}"></script>
@endif
    <script type="text/javascript">
    // This function for Set Responsive Google ReCaptcha 
    function rescaleCaptcha() {
        var width = $('.g-recaptcha').parent().width();
        var scale;
        if (width < 302) {
            scale = width / 302;
        } else {
            scale = 1.0;
        }
        $('.g-recaptcha').css('transform', 'scale(' + scale + ')');
        $('.g-recaptcha').css('-webkit-transform', 'scale(' + scale + ')');
        $('.g-recaptcha').css('transform-origin', '0 0');
        $('.g-recaptcha').css('-webkit-transform-origin', '0 0');
    }
    $(document).ready(function($) {
        rescaleCaptcha();
        $(window).resize(function() {
            rescaleCaptcha();
        });
        
        $("#login-form").validate({
            rules: { 
                email: {
                        required: true ,
                        email: true
                    },
                    password:{
                        required: true,
                    }
                },
            });
          
    });

    $("#login-form").submit(function (event) {
     if (grecaptcha.getResponse() == "") {
            $('.gr-error').show();
            return false;
        } else {
            $('.gr-error').hide();
            return true;
        }
         
 
  
    });

</script>

@endsection

