@extends('layouts.auth-master')

@section('title') {{trans('form.resetform.reset_password')}} @endsection

@section('content')
 <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="card-body pt-0">
                            <h3 class="text-center mt-4">
                                {{-- <a href="/" class="logo logo-admin"><img src="{{ URL::asset('/images/logo-dark.png')}}" height="50" alt="logo"></a> --}}
                                <span style="color: blue;font-size:20px;">AlpaMusic</span>
                            
                            </h3>
                            <div class="p-3">
                                <h4 class="text-muted font-size-18 mb-1 text-center">{{ trans('form.resetform.reset_password') }}</h4>

                     @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                                <form class="form-horizontal mt-4" method="POST" action="{{ route('password.email') }}">
                                       @csrf
                                    <div class="form-group">
                                        <label for="username">{{ trans('form.email_address') }}</label>
                                         <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ trans('form.enter_email') }}">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                     <div class="mt-4">
                                        <button class="btn btn-primary btn-block waves-effect waves-light" id="register" type="submit"> {{ trans('form.resetform.send_password_reset_link') }}</button>
                                    </div>
                                    
                                   </form>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p>@lang('form.dont_have_an_account') <a href="{{ route('clientregister') }}" class="text-primary"> {{ trans('form.customers') }}</a> | <a href="{{ route('investigatorregister') }}" class="text-primary">{{ trans('form.freelancers') }}</a> | <a href="{{ route('deliveryboyregister') }}" class="text-primary">{{ trans('form.employees') }} </a></p>
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