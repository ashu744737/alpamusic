@extends('layouts.auth-master')

@section('title', 'Register')

@section('content')
 <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="card-body pt-0">
                            <h3 class="text-center mt-4">
                                {{-- <a href="/" class="logo logo-admin"><img src="{{ URL::asset('/images/logo-dark.png')}}"  height="50" alt="logo"></a> --}}
                                <span style="color: blue;font-size:20px;">AlpaMusic</span>
                            
                            </h3>
                            <div class="p-3">
                                <h4 class="text-muted font-size-18 mb-1 text-center">{{ trans('form.registration.free_register') }}</h4>
                                <p class="text-muted text-center">{{ trans('form.registration.get_your_free') }} {{ config('app.name') }} {{ trans('form.registration.account_now') }}</p>
                                <form class="form-horizontal mt-4" method="POST" action="{{ route('register') }}">

                                    @csrf

                                    <div class="form-group">
                                        <label for="username">{{ trans('form.name') }}</label>
                                        <input type="text" name="name" value="{{ old('name') }}" required autocomplete="name" class="form-control @error('name') is-invalid @enderror" autofocus id="name" placeholder="Enter name">
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="useremail">{{ trans('form.email_address') }}</label>
                                          <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" id="useremail" placeholder="Enter email" autocomplete="email">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                         
                                    <div class="form-group">
                                        <label for="userpassword">{{ trans('form.password') }}</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" id="userpassword" placeholder="{{ trans('form.enter_password') }}">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="userpassword">{{ trans('form.registration.confirm_password') }}</label>
                                        <input type="password" name="password_confirmation" class="form-control" id="userconfirmpassword" placeholder="{{ trans('form.registration.confirm_password') }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="type_id">{{ trans('form.registration.who_are_you') }}</label>
                                        <select name="type_id" id="type_id" class="form-control @error('type_id') is-invalid @enderror" required>
                                            @foreach($usertypes as $id => $user_type)
                                            <option value="{{ $id }}">{{ $user_type }}</option>
                                            @endforeach
                                        </select>
                                        @error('type_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @endif                                        
                                    </div>

                                    <div class="form-group cptch-container">
                                        <div class="g-recaptcha m-b-10" data-sitekey="{{ env('GOOGLE_RECAPTCHA_SITE_KEY') }}">
                                        </div>
                                        @error('g-recaptcha-response')
                                       <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group row mt-4">
                                        <div class="col-12 text-right">
                                            <button class="btn btn-primary w-md waves-effect waves-light" type="submit">{{ trans('form.register') }}</button>
                                        </div>
                                    </div>

                                    <div class="form-group mb-0 row">
                                        <div class="col-12 mt-4">
                                            <p class="text-muted mb-0 font-size-14">{{ trans('form.registration.by_registering_you_agree') }} {{ config('app.name') }} <a href="#" class="text-primary">{{ trans('form.registration.terms_of_use') }}</a></p>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p>{{ trans('form.already_have_an_account') }} <a href="/login" class="text-primary"> {{ trans('form.login') }} </a> </p>
                        <p>{{ trans('general.developedby') }} <a target="_blank" href="https://soft-l.com/">{{ trans('general.company') }} </a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('page-js')
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

    });

</script>

@endsection