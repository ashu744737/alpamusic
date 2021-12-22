    <!-- Payment from Investigators & Delivery -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-xl-9 col-md-6 col-sm-6">
                        <h4 class="card-title mb-4">{{trans('form.dashboard_screen.payment_done')}}</h4>
                    </div>
                    <div class="actiondropdown col-12 col-xl-3 col-sm-6 text-sm-right">
                        <div class="dropdown dropdown-topbar d-inline-block">
                            <a class="btn btn-light" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ trans('general.filter') }} <i class="mdi mdi-chevron-down"></i>
                            </a>

                            <div class="dropdown-menu action-dd" aria-labelledby="dropdownMenuLink">
                                <a onclick="refreshChart('inv-del-invoice-amount','current');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.current_year')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a onclick="refreshChart('inv-del-invoice-amount','previous');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.previous_year')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a onclick="refreshChart('inv-del-invoice-amount','last_3');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.last_3_years')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a onclick="refreshChart('inv-del-invoice-amount','last_5');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.last_5_years')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a onclick="refreshChart('inv-del-invoice-amount','life_time');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.life_time')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row text-center mt-4">
                    <div class="col-12">
                        <h5 class="font-size-20" id="inv_del_invoice_amt">{{trans('general.money_symbol')}} 0</h5>
                        <p class="text-muted">{{trans('form.dashboard_screen.total_amount')}}</p>
                    </div>
                </div>

                <div id="payments-line-example" class="morris-charts morris-charts-height" dir="ltr"></div>
            </div>
        </div>
    </div>
    <!-- End Payment from Investigators & Delivery -->

    <!-- Payment Didn’t Receive -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-xl-9 col-md-6 col-sm-6">
                        <h4 class="card-title mb-4">{{trans('form.dashboard_screen.payment_pending')}}</h4>
                    </div>
                    <div class="actiondropdown col-12 col-xl-3 col-sm-6 text-sm-right">
                        <div class="dropdown dropdown-topbar d-inline-block">
                            <a class="btn btn-light" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ trans('general.filter') }} <i class="mdi mdi-chevron-down"></i>
                            </a>

                            <div class="dropdown-menu action-dd" aria-labelledby="dropdownMenuLink">
                                <a onclick="refreshChart('invoice-amount-not-rev','current');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.current_year')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a onclick="refreshChart('invoice-amount-not-rev','previous');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.previous_year')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a onclick="refreshChart('invoice-amount-not-rev','last_3');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.last_3_years')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a onclick="refreshChart('invoice-amount-not-rev','last_5');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.last_5_years')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a onclick="refreshChart('invoice-amount-not-rev','life_time');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.life_time')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row text-center mt-4">
                    <div class="col-12">
                        <h5 class="font-size-20" id="total_payment_not_rec">{{trans('general.money_symbol')}} 0</h5>
                        <p class="text-muted">{{trans('form.dashboard_screen.total_amount')}}</p>
                    </div>
                </div>

                <div id="pending-payments-line-example" class="morris-charts morris-charts-height" dir="ltr"></div>
            </div>
        </div>
    </div>
    <!-- End Payment Didn’t Receive -->