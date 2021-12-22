 <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu">

            <div data-simplebar class="h-100">

                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <!-- Left Menu Start -->
                    <ul class="metismenu list-unstyled" id="side-menu">

                        @if(is_menu_enable('dashboard'))
                        <li class="{{ request()->is('dashboard/index') ? 'mm-active' : '' }}">
                            <a href="{{ route('home') }}" class="waves-effect {{ request()->is('dashboard/index')  ? 'active' : '' }}">
                                <i class="mdi mdi-view-dashboard"></i>
                                <span>@lang('form.dashboard')</span>
                            </a>
                        </li>
                        @endif
                        <li>
                            <a href="{{ route('titles') }}" class=" waves-effect {{ request()->is('/titles') ? 'active' : '' }}">
                                <i class="mdi mdi-cash-usd"></i>
                                <span>@lang('form.titles')</span>
                            </a>
                        </li>
                        {{-- @if(is_menu_enable('investigation'))
                        <li>
                            <a href="{{ route('investigations') }}" class=" waves-effect {{ request()->is('/investigations') ? 'active' : '' }}">
                                <i class="mdi mdi-cash-usd"></i>
                                <span>@lang('form.titles')</span>
                            </a>
                        </li>
                        @endif --}}

                        {{-- <li>
                            <a href="#" class=" waves-effect {{ request()->is('dashboard/clients') ? 'active' : '' }}">
                                <i class="mdi mdi-account-box"></i>
                                <span>@lang('form.accounts')</span>
                            </a>
                        </li> --}}
                        @if(is_menu_enable('investigator'))
                        <li>
                            <a href="{{ route('investigators') }}" class=" waves-effect {{ request()->is('/investigators') ? 'active' : '' }}">
                                <i class="mdi mdi-account-multiple"></i>
                                <span>@lang('form.contributors')</span>
                            </a>
                        </li>
                        @endif

                       
                        
                        {{-- @if(is_menu_enable('client'))
                        <li>
                            <a href="{{ route('clients') }}" class=" waves-effect {{ request()->is('/clients') ? 'active' : '' }}">
                                <i class="mdi mdi-account-multiple"></i>
                                <span>@lang('form.clients')</span>
                            </a>
                        </li>
                        @endif
                        @if(is_menu_enable('deliveryboy'))
                        <li>
                            <a href="{{ route('deliveryboys') }}" class=" waves-effect {{ request()->is('/deliveryboys') ? 'active' : '' }}">
                                <i class="mdi mdi-account-multiple"></i>
                                <span>@lang('form.deliveryboys')</span>
                            </a>
                        </li>
                        @endif
                        @if(is_menu_enable('investigation'))
                        @if(isAdmin() || isSM()) 
                        <li>
                            <a href="{{ route('investigations') }}" class=" waves-effect {{ request()->is('/investigations') ? 'active' : '' }}">
                                <i class="mdi mdi-cash-usd"></i>
                                <span>@lang('form.Workorder')</span>
                            </a>
                        </li>
                        @endif
                        @endif
                        @if(is_menu_enable('contact'))
                        <li class="{{ request()->is('dashboard/contacts') ? 'mm-active' : '' }}">
                            <a href="{{ route('contacts') }}" class=" waves-effect {{ request()->is('dashboard/contacts') ? 'active' : '' }}">
                                <i class="mdi mdi-email"></i>
                                <span>@lang('form.contacts')</span>
                            </a>
                        </li>
                        @endif
                        @if(is_menu_enable('subject'))
                        <li>
                            <a href="{{ route('subjects') }}" class="waves-effect">
                                <i class="mdi mdi-book"></i>
                                <span>@lang('form.registration.investigation.subjects')</span>
                            </a>
                        </li>
                        @endif

                        @if (isAdmin()) 
                        <li>
                            <a href="{{ route('staff.index') }}" class=" waves-effect {{ request()->is('dashboard/staff') ? 'active' : '' }}">
                                <i class="mdi mdi-account-multiple"></i>
                                <span>@lang('form.staff')</span>
                            </a>
                        </li>
                        @endif

                        @if(is_menu_enable('product'))
                        @if (!isSM())
                        <li>
                            <a href="{{ route('product.index') }}" class=" waves-effect {{ request()->is('dashboard/products') ? 'active' : '' }}">
                                <i class="mdi mdi-basket"></i>
                                <span>@lang('form.products')</span>
                            </a>
                        </li>
                        @endif
                        @endif
                        
                        @if (isClient()) 
                            @if(is_menu_enable('invoice'))
                            <li>
                                <a href="{{ route('performainvoices.index') }}" class=" waves-effect {{ request()->is('dashboard/invoices') ? 'active' : '' }}">
                                    <i class="fas fa-file-invoice"></i>
                                    <span>@lang('form.performainvoices')</span>
                                </a>
                            </li>
                            @endif
                        @endif
                        @if(is_menu_enable('invoice'))
                            @if (isAdmin() || isSM() || isAccountant()) 
                            <li>
                                <a href="{{ route('performainvoices.index') }}" class=" waves-effect {{ request()->is('dashboard/performainvoices') ? 'active' : '' }}">
                                    <i class="fas fa-file-invoice"></i>
                                    <span>@lang('form.performainvoices')</span>
                                </a>
                            </li>
                            @endif
                            @if (isAdmin() || isSM() || isAccountant() || isClient()) 
                            <li>
                                <a href="{{ route('invoices.index') }}" class=" waves-effect {{ request()->is('dashboard/invoices') ? 'active' : '' }}">
                                    <i class="fas fa-file-invoice"></i>
                                    <span>@lang('form.paidperformainvoices')</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('invoice.pendinginvoice') }}" class=" waves-effect {{ request()->is('dashboard/invoices/pending') ? 'active' : '' }}">
                                    <i class="fas fa-file"></i>
                                    <span>@lang('form.invoicepending')</span>
                                </a>
                            </li>
                            @endif
                        @endif
                        @if (isAdmin() || isInvestigator()) 
                        @if(is_menu_enable('invoice'))
                            <li>
                                <a href="{{ route('investigator.invoices.index') }}" class=" waves-effect {{ request()->is('investigator-invoice') ? 'active' : '' }}">
                                    <i class="fas fa-file-invoice"></i>
                                    @if(isAdmin())
                                    <span>@lang('form.investigator_reg') @lang('general.payment')</span>
                                    @else
                                    <span>@lang('general.payment')</span>
                                    @endif
                                </a>
                            </li>
                            @endif
                        @endif
                        @if (isAdmin() || isDeliveryboy()) 
                        @if(is_menu_enable('invoice'))
                            <li>
                                <a href="{{ route('deliveryboy.invoices.index') }}" class=" waves-effect {{ request()->is('deliveryboy/invoice') ? 'active' : '' }}">
                                    <i class="fas fa-file-invoice"></i>
                                    @if(isAdmin())
                                    <span>@lang('form.delivery_boy_reg') @lang('general.payment')</span>
                                    @else
                                    <span>@lang('general.payment')</span>
                                    @endif
                                </a>
                            </li>
                            @endif
                        @endif
                        @if(is_menu_enable('ticket'))
                        <li>
                            <a href="{{ route('tickets.index') }}" class=" waves-effect {{ request()->is('dashboard/tickets') ? 'active' : '' }}">
                                <i class="mdi mdi-ticket"></i>
                                <span>@lang('form.tickets')</span>
                            </a>
                        </li>
                        @endif --}}
                        {{-- @if(is_menu_enable(['permission', 'usertype']))
                        @if (isAdmin())
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="mdi mdi-settings"></i>
                                <span>@lang('form.settings')</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="{{ route('settings.general') }}">@lang('form.general')</a></li>
                                @if (isAdmin()) 
                                <li><a href="{{ route('documentprice.index') }}">@lang('form.documenttypes.document_price')</a></li>
                                @endif --}}
                                {{--  @if(is_menu_enable('permission'))
                                <li><a href="{{ route('permission.index') }}">@lang('form.permissions_form.permissions')</a></li>
                                @endif
                                @if(is_menu_enable('usertype'))
                                <li><a href="{{ route('usertype.index') }}">@lang('form.usertype_form.usertypes')</a></li>
                                @endif --}}
                                
{{-- 
                            </ul>
                        </li>
                        @endif
                        @endif --}}
                    </ul>
                </div>
                <!-- Sidebar -->
            </div>
        </div>
        <!-- Left Sidebar End -->