    <!-- Cases or Investigation -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{trans('form.dashboard_screen.investigations')}}</h4>
                <div class="row text-center mt-4">
                    <div class="col-4">
                        <h5 class="font-size-20 pendinginv">0</h5>
                        <p class="text-muted">{{trans('form.investigation_status.PendingApproval')}}</p>
                    </div>
                    <div class="col-4">
                        <h5 class="font-size-20 openinv">0</h5>
                        <p class="text-muted">{{trans('form.dashboard_screen.open')}}</p>
                    </div>
                    <div class="col-4">
                        <h5 class="font-size-20 closedinv">0</h5>
                        <p class="text-muted">{{trans('form.dashboard_screen.closed')}}</p>
                    </div>
                </div>
                <div id="complaints-chart" class="morris-charts morris-charts-height" dir="ltr"></div>
            </div>
        </div>
    </div>
    <!-- End Cases or Investigation -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2 ml-2">
                    <h4 class="card-title mt-2 mb-2">{{trans('form.timeline_status.Pending')}}</h4>
                    <a id="pending-investigations" class="btn ml-2" data-toggle="tab" href="#pendingInvestigations" role="tab">{{trans('form.investigations')}}</a>
                    <a id="pending-clients" class="btn" data-toggle="tab" href="#pendingClient" role="tab">{{trans('form.clients')}}</a>
                    <a id="pending-investigators" class="btn" data-toggle="tab" href="#pendingInvestigators" role="tab">{{trans('form.investigators')}}</a>
                    <a id="pending-delivery-boys" class="btn" data-toggle="tab" href="#pendingDeliveryboys" role="tab">{{trans('form.delivery_boys')}}</a>
                </div>

                <div class="tab-pane table-responsive" id="pending_investigations_data" role="tabpanel"  style="height: 390px;overflow: auto;">
                    <table class="table table-centered table-vertical table-nowrap">

                        <tbody id="per_pending_investigations">

                        </tbody>
                    </table>
                </div>

                <div class="tab-pane table-responsive" id="pending_clients_data" role="tabpanel"  style="height: 390px;overflow: auto;">
                    <table class="table table-centered table-vertical table-nowrap">

                        <tbody id="del_pending_clients">

                        </tbody>
                    </table>
                </div>

                <div class="tab-pane table-responsive" id="pending_investigators_data" role="tabpanel"  style="height: 390px;overflow: auto;">
                    <table class="table table-centered table-vertical table-nowrap">

                        <tbody id="del_pending_investigators">
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane table-responsive" id="pending_delivery_boys_data" role="tabpanel"  style="height: 390px;overflow: auto;">
                    <table class="table table-centered table-vertical table-nowrap">

                        <tbody id="del_pending_delivery_boys">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Late Investigation/Invoice -->
    <div class="col-xl-6">
        <div class="card" style="height: 453px;overflow: auto;">
            <div class="card-body">
                <div class="row mb-2 ml-2">
                    <h4 class="card-title mt-2 mb-2">{{trans('form.investigation_status.Late')}}</h4>
                    <a id="late-investigation" class="btn ml-2" data-toggle="tab" href="#late_investigations" role="tab">{{trans('form.dashboard_screen.investigations')}}</a>
                    <a id="late-invoice" class="btn" data-toggle="tab" href="#late_invoice" role="tab">{{trans('form.invoices')}}</a>
                </div>

                <div class="tab-pane table-responsive" id="late_investigation_data" role="tabpanel">
                    <table class="table table-centered table-vertical table-nowrap">

                        <tbody id="late_investigation">
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane table-responsive" id="late_invoice_data" role="tabpanel">
                    <table class="table table-centered table-vertical table-nowrap">

                        <tbody id="late_invoice">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Late Investigation/Invoice -->

    <!-- Monthly Investigations -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-xl-8 col-md-6 col-sm-6">
                        <h4 class="card-title mb-4">{{trans('form.dashboard_screen.monthly_investigations')}}</h4>
                    </div>
                    <div class="actiondropdown col-12 col-xl-4 col-sm-6 text-sm-right">
                        <div class="dropdown dropdown-topbar d-inline-block">
                            <a class="btn btn-light" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ trans('general.filter') }} <i class="mdi mdi-chevron-down"></i>
                            </a>
    
                            <div class="dropdown-menu action-dd" aria-labelledby="dropdownMenuLink">
                                <a onclick="refreshChart('monthly-investigations-chart','current');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.current_year')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a onclick="refreshChart('monthly-investigations-chart','previous');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.previous_year')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a onclick="refreshChart('monthly-investigations-chart','last_3');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.last_3_years')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a onclick="refreshChart('monthly-investigations-chart','last_5');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.last_5_years')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a onclick="refreshChart('monthly-investigations-chart','life_time');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.life_time')}}
                                </a>
                            </div>
                        </div>
                    </div>
                   
                </div>
                <div id="monthly-investigations-chart" class="morris-charts morris-charts-height" dir="ltr" style="height: 370px;overflow: auto;"></div>
            </div>
        </div>
    </div>
    <!-- End Monthly Investigations -->

    {{-- cost per type --}}
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-xl-9 col-md-6 col-sm-6">
                        <h4 class="card-title mb-4">{{trans('form.dashboard_screen.cost_per_type')}}</h4>
                    </div>
                    <div class="actiondropdown col-12 col-xl-3 col-sm-6 text-sm-right">
                        <div class="dropdown dropdown-topbar d-inline-block">
                            <a class="btn btn-light" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ trans('general.filter') }} <i class="mdi mdi-chevron-down"></i>
                            </a>

                            <div class="dropdown-menu action-dd" aria-labelledby="dropdownMenuLink">
                                <a onclick="refreshChart('cost-per-type','current');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.current_year')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a onclick="refreshChart('cost-per-type','previous');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.previous_year')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a onclick="refreshChart('cost-per-type','last_3');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.last_3_years')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a onclick="refreshChart('cost-per-type','last_5');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.last_5_years')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a onclick="refreshChart('cost-per-type','life_time');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.life_time')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="cost-per-type" class="morris-charts morris-charts-height" dir="ltr" style="height: 370px;overflow: auto;"></div>
            </div>
        </div>
    </div>
    {{--  end cost per type --}}

    {{-- time_per_case_type --}}
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-xl-9 col-md-6 col-sm-6">
                        <h4 class="card-title mb-4">{{trans('form.dashboard_screen.time_per_case_type')}}</h4>
                    </div>
                    <div class="actiondropdown col-12 col-xl-3 col-sm-6 text-sm-right">
                        <div class="dropdown dropdown-topbar d-inline-block">
                            <a class="btn btn-light" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ trans('general.filter') }} <i class="mdi mdi-chevron-down"></i>
                            </a>

                            <div class="dropdown-menu action-dd" aria-labelledby="dropdownMenuLink">
                                <a onclick="refreshChart('time-per-case-type','current');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.current_year')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a onclick="refreshChart('time-per-case-type','previous');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.previous_year')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a onclick="refreshChart('time-per-case-type','last_3');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.last_3_years')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a onclick="refreshChart('time-per-case-type','last_5');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.last_5_years')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a onclick="refreshChart('time-per-case-type','life_time');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.life_time')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="time-per-case-type" class="morris-charts morris-charts-height" dir="ltr" style="height: 370px;overflow: auto;"></div>
            </div>
        </div>
    </div>
    {{--  end time_per_case_type --}}

    <!-- Employee Efficiency -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <div class="row ml-2">
                    <div class="col-12 col-xl-9 col-md-6 col-sm-6">
                        <h4 class="card-title mb-4">{{trans('form.dashboard_screen.employee_efficiency')}}</h4>
                    </div>
                    <div class="actiondropdown col-12 col-xl-3 col-sm-6 text-sm-right">
                        <div class="dropdown dropdown-topbar d-inline-block">
                            <a class="btn btn-light" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ trans('general.filter') }} <i class="mdi mdi-chevron-down"></i>
                            </a>

                            <div class="dropdown-menu action-dd" aria-labelledby="dropdownMenuLink">
                                <a onclick="refreshChart('employee-efficiency','current');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.current_year')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a onclick="refreshChart('employee-efficiency','previous');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.previous_year')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a onclick="refreshChart('employee-efficiency','last_3');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.last_3_years')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a onclick="refreshChart('employee-efficiency','last_5');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.last_5_years')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a onclick="refreshChart('employee-efficiency','life_time');" class="dropdown-item" href="javascript:void(0);">
                                    {{trans('form.dashboard_screen.filter.life_time')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="employee-efficiency" class="morris-charts morris-charts-height" dir="ltr" style="height: 370px;overflow: auto;"></div>
            </div>
        </div>
    </div>
    <!-- End Employee Efficiency -->

    <!-- Top 10 investigator/delivery -->
    <div class="col-xl-6">
        <div class="card" style="height: 453px;overflow: auto;">
            <div class="card-body">
                <div class="row mb-2 ml-2">
                    <h4 class="card-title mt-2 mb-2">{{trans('form.dashboard_screen.top_10')}}</h4>
                    <a id="top-investigator" class="btn ml-2" data-toggle="tab" href="#top_investigators" role="tab">{{trans('form.investigators')}}</a>
                    <a id="top-delivery" class="btn" data-toggle="tab" href="#top_delivery" role="tab">{{trans('form.deliveryboys')}}</a>
                </div>

                <div class="tab-pane table-responsive" id="top_investigators_data" role="tabpanel">
                    <table class="table table-centered table-vertical table-nowrap">

                        <tbody id="top_10_investigators">
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane table-responsive" id="top_delivery_data" role="tabpanel">
                    <table class="table table-centered table-vertical table-nowrap">

                        <tbody id="top_10_delivery">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Top 10 investigator/delivery -->

    <div class="col-xl-6">
        <div class="card" style="height: 453px;overflow: auto;">
            <div class="card-body">
                <div class="row mb-2 ml-2">
                    <h4 class="card-title mt-2 mb-2">{{trans('form.dashboard_screen.investigations_per')}}</h4>
                    <a id="investigations-investigator" class="btn ml-2" data-toggle="tab" href="#investigator" role="tab">{{trans('form.investigator_reg')}}</a>
                    <a id="investigations-type" class="btn" data-toggle="tab" href="#type" role="tab">{{trans('form.registration.client.type')}}</a>
                    <a id="investigations-client" class="btn" data-toggle="tab" href="#client" role="tab">{{trans('form.dashboard_screen.client')}}</a>
                </div>

                <div class="tab-pane table-responsive" id="investigator_data" role="tabpanel">
                    <table class="table table-centered table-vertical table-nowrap">

                        <tbody id="per_investigator">
                            
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane table-responsive" id="type_data" role="tabpanel">
                    <table class="table table-centered table-vertical table-nowrap">

                        <tbody id="per_type">

                        </tbody>
                    </table>
                </div>

                <div class="tab-pane table-responsive" id="client_data" role="tabpanel">
                    <table class="table table-centered table-vertical table-nowrap">

                        <tbody id="per_client">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card" style="height: 453px;overflow: auto;">
            <div class="card-body">
                <div class="row mb-2 ml-2">
                    <h4 class="card-title mt-2 mb-2">{{trans('form.dashboard_screen.deliveries_per')}}</h4>
                    <a id="deliveries-investigator" class="btn ml-2" data-toggle="tab" href="#investigator" role="tab">{{trans('form.delivery_boy_reg')}}</a>
                    <a id="deliveries-type" class="btn" data-toggle="tab" href="#type" role="tab">{{trans('form.registration.client.type')}}</a>
                    <a id="deliveries-client" class="btn" data-toggle="tab" href="#client" role="tab">{{trans('form.dashboard_screen.client')}}</a>
                </div>

                <div class="tab-pane table-responsive" id="delivery_deliveryboy_data" role="tabpanel">
                    <table class="table table-centered table-vertical table-nowrap">

                        <tbody id="per_deliveryboy">

                        </tbody>
                    </table>
                </div>

                <div class="tab-pane table-responsive" id="delivery_type_data" role="tabpanel">
                    <table class="table table-centered table-vertical table-nowrap">

                        <tbody id="del_per_type">

                        </tbody>
                    </table>
                </div>

                <div class="tab-pane table-responsive" id="delivery_client_data" role="tabpanel">
                    <table class="table table-centered table-vertical table-nowrap">

                        <tbody id="del_per_client">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Complaints -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{trans('form.dashboard_screen.complaints')}}</h4>
                <div class="row text-center mt-4">
                    <div class="col-6">
                        <h5 class="font-size-20 " id="openComplaints"></h5>
                        <p class="text-muted">{{trans('form.dashboard_screen.open')}}</p>
                    </div>
                    <div class="col-6">
                        <h5 class="font-size-20 " id="closeComplaints"></h5>
                        <p class="text-muted">{{trans('form.dashboard_screen.closed')}}</p>
                    </div>
                </div>
                <div id="complaints-chart2" class="morris-charts morris-charts-height" dir="ltr"></div>
            </div>
        </div>
    </div>
    <!-- End Complaints -->

    <div class="col-xl-6">
        <div class="card" style="height: 453px;overflow: auto;">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-xl-12 col-md-12 col-sm-12">
                        <h4 class="card-title mb-4">{{trans('form.dashboard_screen.complaints_per_client')}}</h4>
                    </div>
                </div>
                <div id="monthly-complaints-div" dir="ltr" style="height: 370px;">
                    <table class="table table-centered table-vertical table-nowrap">
                        <tbody id="monthly-complaints">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>