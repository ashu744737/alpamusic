@extends('layouts.email-master')

@section('title', '')

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
                            <h4 class="text-muted font-size-18 mb-1 text-center">{{ trans('form.registration.verifymail.welcome_text') }} {{ config('app.name') }} {{ $user['name'] ?? '' }} !</h4>
                                <p class="text-muted text-center">{{ trans('form.registration.verifymail.profile_created_text') }}{{ $user['type'] ?? '' }}, {{ trans('form.registration.verifymail.you_can_use') }}</p>
                                <br/>
                                <p class="text-muted text-center">
                                    <b>{{ trans('form.email') }}:</b> {{ $user['email'] ?? '' }}<br/>
                                    <b>{{ trans('form.password') }}:</b> {{ $user['password'] ?? '' }}</b>
                                </p>  
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

