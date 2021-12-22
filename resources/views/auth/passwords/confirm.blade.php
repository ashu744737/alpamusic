@extends('layouts.auth-master')

@section('title') {{trans('form.registration.confirm_password')}} @endsection

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
                                <h4 class="text-muted font-size-18 mb-1 text-center">{{ trans('form.registration.confirm_password') }}</h4>
                               <form class="form-horizontal mt-4" method="POST" action="{{ route('password.confirm') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="useremail">{{ trans('form.password') }}</label>
                                          <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="{{ trans('form.enter_password') }}">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-0 row">
                                       <button class="btn btn-primary btn-block waves-effect waves-light" id="register" type="submit">{{ trans('form.registration.confirm_password') }}</button>
                                        @if (Route::has('password.request'))
                                        <br>
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                           {{ trans('form.forgot_your_password') }}
                                        </a>
                                        @endif
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
@if (App::isLocale('hr'))
<script src="{{ URL::asset('/libs/jquery-validate/1.19.0/localization/messages_he.js')}}"></script>
@endif
@endsection