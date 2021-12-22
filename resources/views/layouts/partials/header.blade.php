
        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box">
                        <a href="/" class="logo logo-dark">
                            <span class="logo-sm">
                                {{-- <img src="{{ URL::asset('/images/logo-sm.png')}}" alt="" height="40"> --}}
                                <span style="color: white;font-size:20px;">AM</span>
                            
                            </span>
                            <span class="logo-lg">
                                {{-- <img src="{{ URL::asset('/images/logo-dark.png')}}" alt="" height="40"> --}}
                                <span style="color: white;font-size:20px;">AlpaMusic</span>
                            
                            </span>
                        </a>

                        <a href="/" class="logo logo-light">
                            <span class="logo-sm">
                                {{-- <img src="{{ URL::asset('/images/logo-sm.png')}}" alt="" height="40"> --}}
                                <span style="color: white;font-size:20px;">AM</span>
                            </span>
                            <span class="logo-lg">
                                <span style="color: white;font-size:20px;">AlpaMusic</span>
                                
                                {{-- <img src="{{ URL::asset('/images/logo-light.png')}}" alt="" height="40"> --}}
                            </span>
                        </a>
                    </div>

                    <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                        <i class="mdi mdi-menu"></i>
                    </button>
                    <font class="px-1 pt-4" style="font-size: 15px;">{{trans('form.registration.verifymail.profile_update_text')}}{{Auth::user()->name}} @if((isInvestigator())){{Auth::user()->investigator->family}}@endif @if(isDeliveryboy()){{Auth::user()->deliveryboy->family}}@endif</font>
                    
                </div>

                <div class="d-flex">
                    @if(auth()->user()->type_id == 1)
                    <div class="dropdown d-none d-md-block ml-2">
                        
                        @php $locale = session()->get('locale'); @endphp
                        @switch($locale)
                                @case('en')
                                <button type="button" class="btn header-item waves-effect" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="mr-2" src="{{ URL::asset('/images/flags/us_flag.jpg')}}" alt="Header Language" height="16"> English <span class="mdi mdi-chevron-down"></span>
                        </button>
                                @break
                                @case('hr')
                               <button type="button" class="btn header-item waves-effect" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <img src="{{ URL::asset('/images/flags/israel_flag.jpg')}}" alt="Header Language" height="16" class="mr-2" height="12"> Hebrew <span class="mdi mdi-chevron-down"></span>
                                   </button>
                                 @break
                                 @default
                                  <button type="button" class="btn header-item waves-effect" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="mr-2" src="{{ URL::asset('/images/flags/us_flag.jpg')}}" alt="Header Language" height="16"> English <span class="mdi mdi-chevron-down"></span>
                        </button>
                                @endswitch
                        <div class="dropdown-menu dropdown-menu-right">
                            <!-- item-->
                            <a href="{{ route('set_lang','en') }}" class="{{ ($locale=='en') ? 'd-none' : '' }}  dropdown-item notify-item">
                                <img src="{{ URL::asset('/images/flags/us_flag.jpg')}}" alt="user-image" class="mr-1" height="12"> <span class="align-middle"> English </span>
                            </a>
                            <!-- item-->
                            <a href="{{ route('set_lang','hr') }}" class="{{ ($locale=='hr') ? 'd-none' : '' }} dropdown-item notify-item">
                                <img src="{{ URL::asset('/images/flags/israel_flag.jpg')}}" alt="user-image" class="mr-1" height="12"> <span class="align-middle"> Hebrew </span>
                            </a>
                        </div>
                    </div>
                    @endif
                    <div class="dropdown d-none d-lg-inline-block">
                        <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                            <i class="mdi mdi-fullscreen font-size-24"></i>
                        </button>
                    </div>
                   
                    
                    <div class="dropdown d-inline-block ml-1" id="notificationdata">
                        @include('layouts.partials.notification')
                     </div>

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle header-profile-user" src="{{ URL::asset('/images/users/user-default.png')}}"
                                alt="Header Avatar">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <!-- item-->
                            <a class="dropdown-item" href="{{ route('myprofile.index') }}"><i class="mdi mdi-account-circle font-size-17 text-muted align-middle mr-1"></i> {{ trans('general.menu.profile') }}</a>
                            <a class="dropdown-item d-block" href="#">
{{--                                <span class="badge badge-success float-right">11</span>--}}
                                <i class="mdi mdi-settings font-size-17 text-muted align-middle mr-1"></i> {{ trans('general.menu.settings') }}</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="mdi mdi-power font-size-17 text-muted align-middle mr-1 text-danger"></i>{{ trans('general.menu.logout') }}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
            
                </div>
            </div>
        </header>
       
        
     