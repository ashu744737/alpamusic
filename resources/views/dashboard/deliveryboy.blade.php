<div class="col-xl-4">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">{{trans('form.investigation.search.deliveries')}}</h4>
            <div id="complete_incomplete_deliverys" class="morris-charts morris-charts-height" dir="ltr"></div>
        </div>
    </div>
</div>
  <!-- Deliveryboy Monthly Investigations -->
  <div class="col-xl-8">
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
<!-- End Deliveryboy Monthly Investigations -->
{{-- delivery boy time_per_case_type --}}
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
{{--  end delivery boy time_per_case_type --}}