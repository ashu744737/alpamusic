@extends('layouts.master')

@section('title') {{trans('form.dashboard')}} @endsection

@section('headerCss')
    <style type="text/css">
        .active{
            background: #eee;
            font-weight: bold;
            box-shadow: none;
        }
        #top_delivery_data{
            display: none;
        }
        #type_data{
            display: none;
        }
        #client_data{
            display: none;
        }
        #delivery_type_data{
            display: none;
        }
        #delivery_client_data{
            display: none;
        }
        .action-dd{
            right: 0 !important;
            left: auto !important;
        }

        @media (max-width: 575px) {
            .action-dd {
                left: 0 !important;
                right: auto !important;
            }
        }
    </style>
@endsection

@section('content')              
    <!-- row -->
    <div class="row">
        @if(isAdmin())
        @include('dashboard.admin')
        @endif
        @if(isInvestigator())
        @include('dashboard.investigator')       
        @endif
        @if(isDeliveryboy())
        @include('dashboard.deliveryboy')
        @endif
        @if(isClient())
        @include('dashboard.client')
        @endif  
        @if(isSM())
        @include('dashboard.stationmanager')
        @endif
        @if(isAccountant())
        @include('dashboard.accountant')
        @endif      
    </div>
    <!-- end row -->
@endsection

@section('footerScript')
<!--Morris Chart-->
    <script src="{{ URL::asset('/libs/morris.js/morris.js.min.js')}}"></script>
    <script src="{{ URL::asset('/libs/raphael/raphael.min.js')}}"></script>
    <script src="{{ URL::asset('/js/pages/dashboard.init.js')}}"></script>
    <script>
        // Chart label with translation
        let days = '{{trans("form.dashboard_screen.days")}}'
        let cost = '{{trans("form.dashboard_screen.cost")}}'
        let service = '{{trans("form.dashboard_screen.service")}}';
        let total_income = '{{trans("form.dashboard_screen.total_income")}}';
        let total_outcome = '{{trans("form.dashboard_screen.total_outcome")}}';
        let open = '{{trans("form.dashboard_screen.open")}}';
        let closed = '{{trans("form.dashboard_screen.closed")}}';
        let pendingapproval = '{{trans("form.investigation_status.PendingApproval")}}';
        let costPerType = '{{trans("form.dashboard_screen.cost_per_type")}}';
        let costOfInvestigator = '{{trans("form.dashboard_screen.cost_of_investigator")}}';
        let amount = '{{trans("form.dashboard_screen.amount")}}';
        let complaints = '{{trans("form.dashboard_screen.complaints")}}';
        let income = '{{trans("form.dashboard_screen.income")}}';
        let moneySymbole = '{{trans("general.money_symbol")}}';
        let currentLogintype = "<?php echo config('constants.user_type') ?>";
        let invInvoiceAmt = '{{trans("form.inv_invoice_amount")}}';
        let delInvoiceAmt = '{{trans("form.del_invoice_amount")}}';
        
        // Chart config
        var morrisLine;
        function createLineChart(element, data, xkey, ykeys, labels, lineColors, smooth=true, preUnits) {
            morrisLine = Morris.Line({
              element: element,
              data: data,
              xkey: xkey,
              ykeys: ykeys,
              labels: labels,
              parseTime: false,
              hideHover: 'auto',
              gridLineColor: 'rgba(108, 120, 151, 0.1)',
              resize: true,
              //defaulted to true
              lineColors: lineColors,
              lineWidth: 2,
              smooth: smooth,
              preUnits: preUnits
            });
          }

        function createAreaChart(element, data, xkey, ykeys, labels, lineColors) {
            Morris.Area({
              element: element,
              pointSize: 0,
              lineWidth: 1,
              data: data,
              xkey: xkey,
              ykeys: ykeys,
              labels: labels,
              resize: true,
              gridLineColor: 'rgba(108, 120, 151, 0.1)',
              hideHover: 'auto',
              lineColors: lineColors,
              fillOpacity: .9,
              behaveLikeLine: true,
              parseTime: false,
              pointFillColors:['#ffffff'],
              pointStrokeColors: ['black']
            });
        }
        function createDonutChart(element, data, colors) {
            Morris.Donut({
              element: element,
              data: data,
              resize: true,
              colors: colors,
            });
        }
        function createStackedChart(element, data, xkey, ykeys, labels, lineColors) {
            Morris.Bar({
              element: element,
              data: data,
              xkey: xkey,
              ykeys: ykeys,
              stacked: true,
              labels: labels,
              hideHover: 'auto',
              resize: true,
              //defaulted to true
              gridLineColor: 'rgba(108, 120, 151, 0.1)',
              barColors: function (row, series, type) {
                if(row.label == "Legal Document Delivery") return "#7A6FBE";
                else if(row.label == "WAC + Delivery") return "#DEBB27";
                else if(row.label == "Extended + Delivery") return "#fec04c";
                else if(row.label == "WAC locator") return "#28BBE3";
                else if(row.label == "Extended locator") return "#79BBE4";
                else if(row.label == "4") return "#7A6FBE";
                else if(row.label == "3") return "#DEBB27";
                else if(row.label == "2") return "#fec04c";
                else if(row.label == "1") return "#28BBE3";
                else if(row.label == "5") return "#79BBE4";
                else return lineColors;
              }
            });
        }

        // Income vs Outcome
        refreshChart('all', 'current');
        function refreshChart(chart, year) {
            $.ajax({
                url: '{{ route("chart.refresh") }}',
                type: 'post',
                data: {
                    chart: chart,
                    year: year,
                    _token: "{{ csrf_token() }}"
                },
                success: function(result){
                    console.log(result,'result')

                    if($('#income_outcome_chart').length && year == 'current'){
                      $('#income_outcome_chart').empty();
                      $('#totalOutcome').html('{{trans("general.money_symbol")}}'+(result.totalOutCome?result.totalOutCome:0));
                      $('#totalIncome').html('{{trans("general.money_symbol")}}'+(result.totalIncome?result.totalIncome:0));
                        var $donutData = [
                          {
                            label: total_income,
                            value: result.totalIncome?result.totalIncome:0
                          }, {
                            label: total_outcome,
                            value: result.totalOutCome?result.totalOutCome:0
                          }
                        ];
                        createDonutChart('income_outcome_chart', $donutData, ['#7a6fbe', '#28bbe3']);
                    }

                    if(result.investigationCount){
                        if(result.investigationCount.length > 0){
                            if($('#complete_incomplete_investigation').length){
                                var $donutData = [
                                  {
                                    label: "{{trans('form.investigator_investigation_status.InProgress')}}",
                                    value: result.investigationCount[0].in_progress
                                  },{
                                    label: "{{trans('form.investigator_investigation_status.Completed')}}",
                                    value: result.investigationCount[0].total_completed
                                  }, {
                                    label: "{{trans('form.investigator_investigation_status.NotCompleted')}}",
                                    value: result.investigationCount[0].total_not_completed
                                  }
                                  , {
                                    label: "{{trans('form.investigator_investigation_status.ReportWriting')}}",
                                    value: result.investigationCount[0].report_writing
                                  }, {
                                    label: "{{trans('form.investigator_investigation_status.ReportSubmitted')}}",
                                    value: result.investigationCount[0].report_submitted
                                  }
                                  , {
                                    label: "{{trans('form.investigator_investigation_status.Declined')}}",
                                    value: result.investigationCount[0].declined
                                  }
                                ];
                                createDonutChart('complete_incomplete_investigation', $donutData, ['#FF7F27', '#58DB83','#000000','#DEBB27','#7a6fbe','#EC536C']);
                            }   
                        }
                    }
                    
                    
                    if(result.deliveriesCount){
                        if(result.deliveriesCount.length > 0){
                            if($('#complete_incomplete_deliverys').length){
                                var $donutData = [
                                    {
                                    label: "{{trans('form.deliveryboy_investigation_status.InProgress')}}",
                                    value: result.deliveriesCount[0].in_progress
                                  },
                                  {
                                    label: "{{trans('form.deliveryboy_investigation_status.Delivered')}}",
                                    value: result.deliveriesCount[0].total_completed
                                  }, {
                                    label: "{{trans('form.deliveryboy_investigation_status.NotDelivered')}}",
                                    value: result.deliveriesCount[0].total_not_completed
                                  },
                                  {
                                    label: "{{trans('form.deliveryboy_investigation_status.ReportWriting')}}",
                                    value: result.deliveriesCount[0].report_writing
                                  },
                                  {
                                    label: "{{trans('form.deliveryboy_investigation_status.ReportSubmitted')}}",
                                    value: result.deliveriesCount[0].report_submitted
                                  }
                                ];
                                createDonutChart('complete_incomplete_deliverys', $donutData, ['#FF7F27','#7a6fbe', '#28bbe3','#DEBB27','#7a6fbe',]);
                            }   
                        }
                    }

                    if(result.topInvestigators) {
                        if(result.topInvestigators.length > 0){
                            if($('#top_10_investigators').length){
                                let topInvestigators = result.topInvestigators
                                let html = ``;
                                let open_cases = '{{trans("form.investigator.open_cases")}}';
                                let completed = '{{trans("form.investigator.completed")}}';
                                let not_completed = '{{trans("form.investigator.not_completed")}}';
                                topInvestigators.map((topInvestigator, index) => {
                                    let htmlSpecializations = '';
                                    let specializations = topInvestigator.investigator.user?topInvestigator.investigator.user.specializations:[];
                                    specializations.map(specialization=>{
                                      @if(config('app.locale') == 'hr')
                                        htmlSpecializations+=`<li>${specialization.hr_name}</li>`;
                                      @else
                                        htmlSpecializations+=`<li>${specialization.name}</li>`;
                                      @endif
                                    });
                                    
                                    html+=`<tr>
                                            <td>
                                                <div><b>${topInvestigator.investigator.user?topInvestigator.investigator.user.name:''}</b></div>
                                                <div>${topInvestigator.investigator.user?topInvestigator.investigator.user.email:''}</div>
                                            </td>
                                            <td>
                                                <ul class="speclist-ul">
                                                    ${htmlSpecializations}
                                                </ul>
                                            </td>
                                            <td>
                                                <div>${open_cases} (${topInvestigator.total_open?topInvestigator.total_open:'0' })</div>
                                                <div>${completed} (${topInvestigator.total_completed?topInvestigator.total_completed:'0'})</div>
                                                <div>${not_completed} (${topInvestigator.total_not_completed?topInvestigator.total_not_completed:'0' })</div>
                                            </td>
                                        </tr>`;
                                });

                                $('#top_10_investigators').html(html);
                            }
                        }  else {
                        let penInvestigationsHTML = `<tr>
                                                <td colspan="3" style="text-align: center;">`;
                        penInvestigationsHTML += '{{trans("general.no_record_found")}}';
                        penInvestigationsHTML += `</td>
                                                </tr>`;
                        $('#top_10_investigators').html(penInvestigationsHTML)
                      }
                    }

                    if(result.topDeliveryboys) {
                        if(result.topDeliveryboys.length > 0){
                            if($('#top_10_delivery').length){
                                let topDeliveryboys = result.topDeliveryboys
                                let html = ``;
                                console.log(topDeliveryboys,'topDeliveryboys')
                                let open_cases = '{{trans("form.investigation.search.open_cases")}}';
                                let completed = '{{trans("form.investigation.search.delivered")}}';
                                let not_completed = '{{trans("form.investigation.search.not_delivered")}}';
                                topDeliveryboys.map((topDelivery, index) => {
                                    let htmlSpecializations = '';
                                    let specializations = topDelivery.deliveryboy.user?topDelivery.deliveryboy.user.delivery_areas:[];
                                    specializations.map(specialization=>{
                                      @if(config('app.locale') == 'hr')
                                        htmlSpecializations+=`<li>${specialization.hr_area_name}</li>`;
                                      @else
                                        htmlSpecializations+=`<li>${specialization.area_name}</li>`;
                                      @endif
                                    });
                                    
                                    html+=`<tr>
                                            <td>
                                                <div><b>${topDelivery.deliveryboy.user?topDelivery.deliveryboy.user.name:''}</b></div>
                                                <div>${topDelivery.deliveryboy.user?topDelivery.deliveryboy.user.email:''}</div>
                                            </td>
                                            <td>
                                                <ul class="speclist-ul">
                                                    ${htmlSpecializations}
                                                </ul>
                                            </td>
                                            <td>
                                                <div>${open_cases} (${topDelivery.total_open?topDelivery.total_open:'0' })</div>
                                                <div>${completed} (${topDelivery.total_completed?topDelivery.total_completed:'0'})</div>
                                                <div>${not_completed} (${topDelivery.total_not_completed?topDelivery.total_not_completed:'0' })</div>
                                            </td>
                                        </tr>`;
                                });

                                $('#top_10_delivery').html(html);
                            }
                        } else {
                        let penInvestigationsHTML = `<tr>
                                                <td colspan="3" style="text-align: center;">`;
                        penInvestigationsHTML += '{{trans("general.no_record_found")}}';
                        penInvestigationsHTML += `</td>
                                                </tr>`;
                        $('#top_10_delivery').html(penInvestigationsHTML)
                      }
                    }

                    if($('#late-investigations').length) {
                        let employeeEfficiencyData = [
                            {
                              y: "{{trans('general.month.Jan')}}",
                              a: 5,
                            }, {
                              y: "{{trans('general.month.Feb')}}",
                              a: 7,
                            }, {
                              y: "{{trans('general.month.Mar')}}",
                              a: 9,
                            }, {
                              y: "{{trans('general.month.Apr')}}",
                              a: 2,
                            }, {
                              y: "{{trans('general.month.May')}}",
                              a: 0,
                            }, {
                              y: "{{trans('general.month.Jun')}}",
                              a: 13,
                            }, {
                              y: "{{trans('general.month.Jul')}}",
                              a: 6,
                            }, {
                              y: "{{trans('general.month.Aug')}}",
                              a: 7,
                            }, {
                              y: "{{trans('general.month.Sept')}}",
                              a: 10,
                            }, {
                              y: "{{trans('general.month.Oct')}}",
                              a: 15,
                            }, {
                              y: "{{trans('general.month.Nov')}}",
                              a: 17,
                            }, {
                              y: "{{trans('general.month.Dec')}}",
                              a: 19,
                            }
                        ];
                        
                        createLineChart('late-investigations', employeeEfficiencyData, 'y', ['a'], ['Days'], ['#28bbe3', '#7a6fbe', '#28bbe3'], true);
                    }

                    if(result.clientInvestigationCount){
                        if(result.clientInvestigationCount.length > 0){
                            if($('#complete_incomplete_client').length){
                                var $donutData = [
                                    {
                                    label: "{{trans('form.investigation_status.Waiting')}}",
                                    value: result.clientInvestigationCount[0].waiting
                                  },
                                  {
                                    label: "{{trans('form.timeline_status.In Progress')}}",
                                    value: result.clientInvestigationCount[0].in_progress
                                  }, {
                                    label: "{{trans('form.timeline_status.Closed')}}",
                                    value: result.clientInvestigationCount[0].closed
                                  }
                                ];
                                createDonutChart('complete_incomplete_client', $donutData, ['#FF7F27','#7a6fbe', '#28bbe3']);
                            }   
                        }
                    }

                    if(result.perInvestigator){
                        if(result.perInvestigator.length > 0){
                            let investigators = result.perInvestigator;
                            let investigatorHTML = '';
                            investigators.map((investigator, idx) => {
                                investigatorHTML += `<tr>
                                                        <td>
                                                            <div><b>${investigator.name}</b></div>
                                                            <div>${investigator.email}</div>
                                                        </td>
                                                        <td>
                                                            <div>${investigator.total}</div>
                                                        </td>
                                                    </tr>`;
                            });
                            $('#per_investigator').html(investigatorHTML)
                        } else {
                        let penInvestigationsHTML = `<tr>
                                                <td colspan="2" style="text-align: center;">`;
                        penInvestigationsHTML += '{{trans("general.no_record_found")}}';
                        penInvestigationsHTML += `</td>
                                                </tr>`;
                        $('#per_investigator').html(penInvestigationsHTML)
                      }
                    }
                    if(result.perType){
                        if(result.perType.length > 0){
                            let types = result.perType;
                            let typeHTML = '';
                            types.map((type, idx) => {
                                typeHTML += `<tr>
                                                <td>
                                                    <div><b>${type.name}</b></div>
                                                </td>
                                                <td>
                                                    <div>${type.total}</div>
                                                </td>
                                            </tr>`;
                            });
                            $('#per_type').html(typeHTML)
                        }else {
                        let penInvestigationsHTML = `<tr>
                                                <td colspan="2" style="text-align: center;">`;
                        penInvestigationsHTML += '{{trans("general.no_record_found")}}';
                        penInvestigationsHTML += `</td>
                                                </tr>`;
                        $('#per_type').html(penInvestigationsHTML)
                      }
                    }
                    if(result.perClient){
                        if(result.perClient.length > 0){
                            let clients = result.perClient;
                            let clientHTML = '';
                            clients.map((client, idx) => {
                                clientHTML += `<tr>
                                                    <td>
                                                        <div><b>${client.name}</b></div>
                                                        <div>${client.email}</div>
                                                    </td>
                                                    <td>
                                                        <div>${client.total}</div>
                                                    </td>
                                                </tr>`;
                            });
                            $('#per_client').html(clientHTML)
                        }else {
                        let penInvestigationsHTML = `<tr>
                                                <td colspan="2" style="text-align: center;">`;
                        penInvestigationsHTML += '{{trans("general.no_record_found")}}';
                        penInvestigationsHTML += `</td>
                                                </tr>`;
                        $('#per_client').html(penInvestigationsHTML)
                      }
                    }

                    if(result.delPerInvestigator){
                        if(result.delPerInvestigator.length > 0){
                            let deliveryboys = result.delPerInvestigator;
                            let deiliveryboyHTML = '';
                            deliveryboys.map((deliveryboy, idx) => {
                                deiliveryboyHTML += `<tr>
                                                        <td>
                                                            <div><b>${deliveryboy.name}</b></div>
                                                            <div>${deliveryboy.email}</div>
                                                        </td>
                                                        <td>
                                                            <div>${deliveryboy.total}</div>
                                                        </td>
                                                    </tr>`;
                            });
                            $('#per_deliveryboy').html(deiliveryboyHTML)
                        }else {
                        let penInvestigationsHTML = `<tr>
                                                <td colspan="2" style="text-align: center;">`;
                        penInvestigationsHTML += '{{trans("general.no_record_found")}}';
                        penInvestigationsHTML += `</td>
                                                </tr>`;
                        $('#per_deliveryboy').html(penInvestigationsHTML)
                      }
                    }
                    if(result.delPerType){
                        if(result.delPerType.length > 0){
                            let delTypes = result.delPerType;
                            let delTypeHTML = '';
                            delTypes.map((delType, idx) => {
                                delTypeHTML += `<tr>
                                                <td>
                                                    <div><b>${delType.name}</b></div>
                                                </td>
                                                <td>
                                                    <div>${delType.total}</div>
                                                </td>
                                            </tr>`;
                            });
                            $('#del_per_type').html(delTypeHTML)
                        }else {
                        let penInvestigationsHTML = `<tr>
                                                <td colspan="2" style="text-align: center;">`;
                        penInvestigationsHTML += '{{trans("general.no_record_found")}}';
                        penInvestigationsHTML += `</td>
                                                </tr>`;
                        $('#del_per_type').html(penInvestigationsHTML)
                      }
                    }
                    if(result.delPerClient){
                        if(result.delPerClient.length > 0){
                            let delClients = result.delPerClient;
                            let delClientHTML = '';
                            delClients.map((delClient, idx) => {
                                delClientHTML += `<tr>
                                                    <td>
                                                        <div><b>${delClient.name}</b></div>
                                                        <div>${delClient.email}</div>
                                                    </td>
                                                    <td>
                                                        <div>${delClient.total}</div>
                                                    </td>
                                                </tr>`;
                            });
                            $('#del_per_client').html(delClientHTML)
                        } else {
                        let penInvestigationsHTML = `<tr>
                                                <td colspan="2" style="text-align: center;">`;
                        penInvestigationsHTML += '{{trans("general.no_record_found")}}';
                        penInvestigationsHTML += `</td>
                                                </tr>`;
                        $('#del_per_client').html(penInvestigationsHTML)
                      }
                    }

                    if(result.pendingInvestigations){
                      if(result.pendingInvestigations.length > 0){
                        let penInvestigations = result.pendingInvestigations;
                        let penInvestigationsHTML = '';
                        penInvestigations.map((penInvestigation, idx) => {
                          penInvestigationsHTML += `<tr>
                                                <td>
                                                    <a href="${penInvestigation.route}">
                                                      <div>
                                                        <b>${penInvestigation.work_order_number}</b>
                                                      </div>
                                                    <div>${penInvestigation.product.name}</div>
                                                    </a>
                                                </td>
                                                <td>
                                                  <a href="${penInvestigation.route}">
                                                    <div>${penInvestigation.user_inquiry}</div>
                                                  </a>
                                                </td>
                                            </tr>`;
                        });
                        $('#per_pending_investigations').html(penInvestigationsHTML)
                      } else {
                        let penInvestigationsHTML = `<tr>
                                                <td colspan="3" style="text-align: center;">`;
                        penInvestigationsHTML += '{{trans("general.no_record_found")}}';
                        penInvestigationsHTML += `</td>
                                                </tr>`;
                        $('#per_pending_investigations').html(penInvestigationsHTML)
                      }
                    }

                    if(result.lateInvestigations){
                      if(result.lateInvestigations.length > 0){
                        let lateInvestigations = result.lateInvestigations;
                        let lateInvestigationsHTML = '';
                        lateInvestigations.map((lateInvestigation, idx) => {
                          lateInvestigationsHTML += `<tr>
                                                <td>
                                                    <a href="${lateInvestigation.route}">
                                                      <div>
                                                        <b>${lateInvestigation.work_order_number}</b>
                                                      </div>
                                                    <div>${lateInvestigation.product.name}</div>
                                                    </a>
                                                </td>
                                                <td>
                                                  <a href="${lateInvestigation.route}">
                                                    <div>${lateInvestigation.user_inquiry}</div>
                                                  </a>
                                                </td>
                                            </tr>`;
                        });
                        $('#late_investigation').html(lateInvestigationsHTML)
                      } else {
                        let penInvestigationsHTML = `<tr>
                                                <td colspan="3" style="text-align: center;">`;
                        penInvestigationsHTML += '{{trans("general.no_record_found")}}';
                        penInvestigationsHTML += `</td>
                                                </tr>`;
                        $('#late_investigation').html(penInvestigationsHTML)
                      }
                    }
                    
                    if(result.lateInvoices){
                      if(result.lateInvoices.length > 0){
                        let lateInvoices = result.lateInvoices;
                        let lateInvoicesHTML = '';
                        lateInvoices.map((lateInvoice, idx) => {
                          lateInvoicesHTML += `<tr>
                                                <td>
                                                    <a href="${lateInvoice.route}">
                                                      <div>
                                                        <b>${lateInvoice.invoice_no}</b>
                                                      </div>
                                                    <div>${lateInvoice.investigation.work_order_number}</div>
                                                    </a>
                                                </td>
                                                <td>
                                                  <a href="${lateInvoice.route}">
                                                    <div>${lateInvoice.price}</div>
                                                  </a>
                                                </td>
                                            </tr>`;
                        });
                        $('#late_invoice').html(lateInvoicesHTML)
                      }else {
                        let penInvestigationsHTML = `<tr>
                                                <td colspan="3" style="text-align: center;">`;
                        penInvestigationsHTML += '{{trans("general.no_record_found")}}';
                        penInvestigationsHTML += `</td>
                                                </tr>`;
                        $('#late_invoice').html(penInvestigationsHTML)
                      }
                    }

                    if(result.openInvoice){
                      if(result.openInvoice.length > 0){
                        let openInvoice = result.openInvoice;
                        let openInvoiceHTML = '';
                        openInvoice.map((invoice, idx) => {
                          openInvoiceHTML += `<tr>
                                                <td>
                                                    <a href="${invoice.route}">
                                                      <div>
                                                        <b>${invoice.invoice_no}</b>
                                                      </div>
                                                    <div>${invoice.investigation.work_order_number}</div>
                                                    </a>
                                                </td>
                                                <td>
                                                  <a href="${invoice.route}">
                                                    <div>${invoice.price}</div>
                                                  </a>
                                                </td>
                                            </tr>`;
                        });
                        $('#open_invoice').html(openInvoiceHTML)
                      }  else {
                        let penInvestigationsHTML = `<tr>
                                                <td colspan="2" style="text-align: center;">`;
                        penInvestigationsHTML += '{{trans("general.no_record_found")}}';
                        penInvestigationsHTML += `</td>
                                                </tr>`;
                        $('#open_invoice').html(penInvestigationsHTML)
                      }
                    }

                    if(result.closeInvoice){
                      if(result.closeInvoice.length > 0){
                        let closeInvoice = result.closeInvoice;
                        let closeInvoiceHTML = '';
                        closeInvoice.map((invoice, idx) => {
                          closeInvoiceHTML += `<tr>
                                                <td>
                                                    <a href="${invoice.route}">
                                                      <div>
                                                        <b>${invoice.invoice_no}</b>
                                                      </div>
                                                    <div>${invoice.investigation.work_order_number}</div>
                                                    </a>
                                                </td>
                                                <td>
                                                  <a href="${invoice.route}">
                                                    <div>${invoice.price}</div>
                                                  </a>
                                                </td>
                                            </tr>`;
                        });
                        $('#close_invoice').html(closeInvoiceHTML)
                      }   else {
                        let penInvestigationsHTML = `<tr>
                                                <td colspan="2" style="text-align: center;">`;
                        penInvestigationsHTML += '{{trans("general.no_record_found")}}';
                        penInvestigationsHTML += `</td>
                                                </tr>`;
                        $('#close_invoice').html(penInvestigationsHTML)
                      }
                    }

                    if(result.pendingClients){
                      if(result.pendingClients.length > 0){
                        let pendingClients = result.pendingClients;
                        let pendingClientsHTML = '';
                        pendingClients.map((pendingClient, idx) => {
                          let type_name = '';
                          @if(config('app.locale') == 'hr')
                          type_name = pendingClient.client.client_type.type_name!='' ? pendingClient.client.client_type.hr_type_name : '';
                          @else
                          type_name = pendingClient.client.client_type.type_name!='' ? pendingClient.client.client_type.type_name : '';
                          @endif
                          pendingClientsHTML += `<tr>
                                                <td>
                                                    <a href="${pendingClient.route}">
                                                      <div>
                                                        <b>${pendingClient.name}</b>
                                                      </div>
                                                    <div>${pendingClient.email}</div>
                                                    </a>
                                                </td>
                                                <td>
                                                  <a href="${pendingClient.route}">
                                                    <div>${type_name}</div>
                                                  </a>
                                                </td>
                                            </tr>`;
                        });
                        $('#del_pending_clients').html(pendingClientsHTML)
                      } else {
                        let penInvestigationsHTML = `<tr>
                                                <td colspan="2" style="text-align: center;">`;
                        penInvestigationsHTML += '{{trans("general.no_record_found")}}';
                        penInvestigationsHTML += `</td>
                                                </tr>`;
                        $('#del_pending_clients').html(penInvestigationsHTML)
                      }
                    }

                    if(result.pendingInvestigators){
                      if(result.pendingInvestigators.length > 0){
                        let pendingInvestigators = result.pendingInvestigators;
                        let pendingInvestigatorsHTML = '';
                        pendingInvestigators.map((pendingInvestigator, idx) => {
                          pendingInvestigatorsHTML += `<tr>
                                                <td>
                                                    <a href="${pendingInvestigator.route}">
                                                      <div>
                                                        <b>${pendingInvestigator.name}</b>
                                                      </div>
                                                    <div>${pendingInvestigator.email}</div>
                                                    </a>
                                                </td>
                                                <td>
                                                  <a href="${pendingInvestigator.route}">
                                                    <div>${pendingInvestigator.specialization}</div>
                                                  </a>
                                                </td>
                                            </tr>`;
                        });
                        $('#del_pending_investigators').html(pendingInvestigatorsHTML)
                      } else {
                        let penInvestigationsHTML = `<tr>
                                                <td colspan="2" style="text-align: center;">`;
                        penInvestigationsHTML += '{{trans("general.no_record_found")}}';
                        penInvestigationsHTML += `</td>
                                                </tr>`;
                        $('#del_pending_investigators').html(penInvestigationsHTML)
                      }
                    }

                    if(result.pendingDeliveryBoys){
                      if(result.pendingDeliveryBoys.length > 0){
                        let pendingDeliveryBoys = result.pendingDeliveryBoys;
                        let pendingDeliveryBoysHTML = '';
                        pendingDeliveryBoys.map((pendingDeliveryBoy, idx) => {
                          pendingDeliveryBoysHTML += `<tr>
                                                <td>
                                                    <a href="${pendingDeliveryBoy.route}">
                                                      <div>
                                                        <b>${pendingDeliveryBoy.name}</b>
                                                      </div>
                                                    <div>${pendingDeliveryBoy.email}</div>
                                                    </a>
                                                </td>
                                                <td>
                                                  <a href="${pendingDeliveryBoy.route}">
                                                    <div>${pendingDeliveryBoy.deliveryAreas}</div>
                                                  </a>
                                                </td>
                                            </tr>`;
                        });
                        $('#del_pending_delivery_boys').html(pendingDeliveryBoysHTML)
                      } else {
                        let penInvestigationsHTML = `<tr>
                                                <td colspan="2" style="text-align: center;">`;
                        penInvestigationsHTML += '{{trans("general.no_record_found")}}';
                        penInvestigationsHTML += `</td>
                                                </tr>`;
                        $('#del_pending_delivery_boys').html(penInvestigationsHTML)
                      }
                    }

                    // Cases or Investigation
                    if(currentLogintype=="{{env('USER_TYPE_STATION_MANAGER')}}"){
                        if(result.opencloseInvestigation){
                          $('.openinv').html(result.opencloseInvestigation.open);
                          $('.closedinv').html(result.opencloseInvestigation.closed);
                          $('.pendinginv').html(result.opencloseInvestigation.pending_approval);
                          if($('#complaints-chart').length){
                              var $donutData = [
                                  {
                                  label: pendingapproval,
                                  value: result.opencloseInvestigation.pending_approval
                              },
                              {
                                  label: open,
                                  value: result.opencloseInvestigation.open
                              }, {
                                  label: closed,
                                  value: result.opencloseInvestigation.closed
                              }
                              ];
                              createDonutChart('complaints-chart', $donutData, ['#28bbe3', '#7a6fbe','#EC536C']);
                          }
                        }
                    }else{
                        if(result.opencloseInvestigation){
                          $('.openinv').html(result.opencloseInvestigation.open);
                          $('.closedinv').html(result.opencloseInvestigation.closed);
                          if($('#complaints-chart').length){
                              var $donutData = [
                              {
                                  label: open,
                                  value: result.opencloseInvestigation.open
                              }, {
                                  label: closed,
                                  value: result.opencloseInvestigation.closed
                              }
                              ];
                              createDonutChart('complaints-chart', $donutData, ['#28bbe3', '#7a6fbe']);
                          }
                      }
                    }

                    let employeeEfficiencyData = result.employee_efficiency;
                    if(result.employee_efficiency){
                      if(employeeEfficiencyData && employeeEfficiencyData.length > 0){
                        employeeEfficiencyData = result.employee_efficiency
                        $("#employee-efficiency").empty();
                        if($('#employee-efficiency').length){
                            createLineChart('employee-efficiency', employeeEfficiencyData, 'y', ['a', 'b'], [costPerType, costOfInvestigator], ['#ccc', '#7a6fbe', '#28bbe3'], true, moneySymbole);
                        }
                      } else if(employeeEfficiencyData) {
                          employeeEfficiencyData = [
                              {
                                y: "{{trans('general.month.Jan')}}",
                                a: null,
                                b: null
                              }, {
                                y: "{{trans('general.month.Feb')}}",
                                a: null,
                                b: null
                              }, {
                                y: "{{trans('general.month.Mar')}}",
                                a: null,
                                b: null
                              }, {
                                y: "{{trans('general.month.Apr')}}",
                                a: null,
                                b: null
                              }, {
                                y: "{{trans('general.month.May')}}",
                                a: null,
                                b: null
                              }, {
                                y: "{{trans('general.month.Jun')}}",
                                a: null,
                                b: null
                              }, {
                                y: "{{trans('general.month.Jul')}}",
                                a: null,
                                b: null
                              }, {
                                y: "{{trans('general.month.Aug')}}",
                                a: null,
                                b: null
                              }, {
                                y: "{{trans('general.month.Sept')}}",
                                a: null,
                                b: null
                              }, {
                                y: "{{trans('general.month.Oct')}}",
                                a: null,
                                b: null
                              }, {
                                y: "{{trans('general.month.Nov')}}",
                                a: null,
                                b: null
                              }, {
                                y: "{{trans('general.month.Dec')}}",
                                a: null,
                                b: null
                              }
                          ];
                          $("#employee-efficiency").empty();
                          if($('#employee-efficiency').length){
                              createLineChart('employee-efficiency', employeeEfficiencyData, 'y', ['a', 'b'], [costPerType, costOfInvestigator], ['#ccc', '#7a6fbe', '#28bbe3'], true, moneySymbole);
                          }
                      }
                    }

                    // Monthly Investigation Chart
                    let monthlyinvestigationData = result.monthly_investigations;
                    if(monthlyinvestigationData && monthlyinvestigationData.length > 0){
                        monthlyinvestigationData = result.monthly_investigations
                        $("#monthly-investigations-chart").empty();
                        if($('#monthly-investigations-chart').length){
                            //createLineChart('monthly-investigations-chart', monthlyinvestigationData, 'y', ['a', 'b'], [costPerType, costOfInvestigator], ['#ccc', '#7a6fbe', '#28bbe3'], true, '$');
                            createLineChart('monthly-investigations-chart', monthlyinvestigationData, 'y', ['a'], ['{{trans("form.dashboard_screen.investigations")}}'], ['#dc86ef'], true, ''); //creating bar chart   
                        }
                    } else if(monthlyinvestigationData) {
                        monthlyinvestigationData = [
                            {
                              y: "{{trans('general.month.Jan')}}",
                              a: null,
                            }, {
                              y: "{{trans('general.month.Feb')}}",
                              a: null,
                            }, {
                              y: "{{trans('general.month.Mar')}}",
                              a: null,
                            }, {
                              y: "{{trans('general.month.Apr')}}",
                              a: null,
                            }, {
                              y: "{{trans('general.month.May')}}",
                              a: null,
                            }, {
                              y: "{{trans('general.month.Jun')}}",
                              a: null,
                            }, {
                              y: "{{trans('general.month.Jul')}}",
                              a: null,
                            }, {
                              y: "{{trans('general.month.Aug')}}",
                              a: null,
                            }, {
                              y: "{{trans('general.month.Sept')}}",
                              a: null,
                            }, {
                              y: "{{trans('general.month.Oct')}}",
                              a: null,
                            }, {
                              y: "{{trans('general.month.Nov')}}",
                              a: null,
                            }, {
                              y: "{{trans('general.month.Dec')}}",
                              a: null,
                            }
                        ];
                        $("#monthly-investigations-chart").empty();
                        if($('#monthly-investigations-chart').length){
                           // createLineChart('monthly-investigations-chart', monthlyinvestigationData, 'y', ['a', 'b'], [costPerType, costOfInvestigator], ['#ccc', '#7a6fbe', '#28bbe3'], true, '$');
                             createLineChart('monthly-investigations-chart', monthlyinvestigationData, 'y', ['a'], ['{{trans("form.dashboard_screen.investigations")}}'], ['#dc86ef'], true, ''); //creating bar chart   
                        }
                    }

                    let timePerCase = result.time_per_case?result.time_per_case:[];
                    if(result.time_per_case){
                      if(timePerCase && timePerCase.length > 0){
                        if($('#time-per-case-type').length){
                            $("#time-per-case-type").empty();
                            createAreaChart('time-per-case-type', timePerCase, 'y', ['a'], [days], ['#7a6fbe']);
                        }
                      } else if(result.time_per_case) {
                          if($('#time-per-case-type').length){
                              $("#time-per-case-type").empty();
                              let incomePerEmpHTML = '<div style="text-align: center;">{{trans("general.no_record_found")}}</div>';
                              $('#time-per-case-type').html(incomePerEmpHTML)
                          }
                      }
                    }

                    // cost per type chart
                    let costPerTypeData = result.cost_per_type?result.cost_per_type:[];
                    if(result.cost_per_type){
                      if(costPerTypeData && costPerTypeData.length > 0){
                        if($('#cost-per-type').length){
                            $("#cost-per-type").empty();
                            createAreaChart('cost-per-type', costPerTypeData, 'y', ['a'], [cost+'('+moneySymbole+')'], ['#7a6fbe']);
                        }
                      } else if(result.cost_per_type) {
                          if($('#cost-per-type').length){
                              $("#cost-per-type").empty();
                              let incomePerEmpHTML = '<div style="text-align: center;">{{trans("general.no_record_found")}}</div>';
                              $('#cost-per-type').html(incomePerEmpHTML)
                          }
                      }
                    }

                    // Complaints
                    if($('#complaints-chart2').length && year == 'current'){
                        $('#openComplaints').html(result.openComplaints);
                        $('#closeComplaints').html(result.closeComplaints);
                        var $donutData = [
                          {
                            label: open,
                            value: result.openComplaints
                          }, {
                            label: closed,
                            value: result.closeComplaints
                          }
                        ];
                        createDonutChart('complaints-chart2', $donutData, ['#28bbe3', '#7a6fbe']);
                    }

                    let paymentPaidToInvDel = result.paymentPaidToInvDel?result.paymentPaidToInvDel:[];
                    if(result.paymentPaidToInvDel){
                      if(paymentPaidToInvDel && paymentPaidToInvDel.length > 0){
                        let totalAmt = 0;
                        paymentPaidToInvDel.map((payment, index) => {
                          totalAmt += (payment.a + payment.b)
                        });
                        $("#payments-line-example").empty();
                        $('#inv_del_invoice_amt').html(`{{trans('general.money_symbol')}} ${totalAmt}`);
                        createLineChart('payments-line-example', result.paymentPaidToInvDel, 'y', ['a', 'b'], [invInvoiceAmt, delInvoiceAmt], ['#7a6fbe', '#28bbe3'], true, '{{trans("general.money_symbol")}}'); //creating bar chart   
                      } else {
                        $('#inv_del_invoice_amt').html(`{{trans('general.money_symbol')}} 0`);
                        let incomePerEmpHTML = '{{trans("general.no_record_found")}}';
                        $('#payments-line-example').html(incomePerEmpHTML)
                      }
                    }

                    // Payment Didn’t Receive
                    let paymentNotRev = result.paymentNotRev?result.paymentNotRev:[];
                    if(result.paymentNotRev){
                      if(paymentNotRev && paymentNotRev.length > 0) {
                        let totalAmt = 0;
                        paymentNotRev.map((payment, index) => {
                          totalAmt += payment.a
                        });
                        $("#pending-payments-line-example").empty();
                        $('#total_payment_not_rec').html(`{{trans('general.money_symbol')}} ${totalAmt}`);
                        createLineChart('pending-payments-line-example', paymentNotRev, 'y', ['a'], [amount], ['#FD545E'], true, '{{trans("general.money_symbol")}}'); //creating bar chart   
                      } else {
                        $('#total_payment_not_rec').html(`{{trans('general.money_symbol')}} 0`);
                        let incomePerEmpHTML = '{{trans("general.no_record_found")}}';
                        $('#pending-payments-line-example').html(incomePerEmpHTML)
                      }
                    }

                    //Income per employee
                    let incomePerEmp = result.incomePerEmp?result.incomePerEmp:[];
                    let incomePerEmpHTML = "";
                    if(result.incomePerEmp){
                      if(incomePerEmp && incomePerEmp.length > 0){
                        incomePerEmp.map((income,idx) => {
                            let color = "red";
                            if(income.diff > 0){
                              color = "green";
                            }
                            incomePerEmpHTML += `<tr>
                                                  <td>
                                                      <div>
                                                        <b>${income.name}</b>
                                                      </div>
                                                  </td>
                                                  <td>
                                                    <div style="display: inline-flex;">
                                                      <div style="padding: 2px;width:5px;height:5px;border-radius:50%;background:${color};margin: 8px;"></div>
                                                      <div>{{trans('general.money_symbol')}}${income.diff}</div>
                                                    </div>
                                                  </td>
                                              </tr>`;
                          });
                          $('#monthly-income-employee-tab').html(incomePerEmpHTML)
                      } else {
                        incomePerEmpHTML = `<tr>
                                                  <td colspan="2" style="text-align: center;">`;
                          incomePerEmpHTML += '{{trans("general.no_record_found")}}';
                          incomePerEmpHTML += `</td>
                                                  </tr>`;
                          $('#monthly-income-employee-tab').html(incomePerEmpHTML)
                      }
                    }

                    //complaints Per Client
                    let complaintsPerClient = result.complaintsPerClient?result.complaintsPerClient:[];
                    let complaintsPerClientHTML = "";
                    if(result.complaintsPerClient){
                      if(complaintsPerClient && complaintsPerClient.length > 0){
                        complaintsPerClient.map((complaint,idx) => {
                            complaintsPerClientHTML += `<tr>
                                                  <td>
                                                      <div>
                                                        <b>${complaint.name}</b>
                                                      </div>
                                                  </td>
                                                  <td>
                                                    <div>
                                                      ${complaint.total_complaint}
                                                    </div>
                                                  </td>
                                              </tr>`;
                          });
                          $('#monthly-complaints').html(complaintsPerClientHTML)
                      } else {
                        complaintsPerClientHTML = `<tr>
                                                  <td colspan="2" style="text-align: center;">`;
                          complaintsPerClientHTML += '{{trans("general.no_record_found")}}';
                          complaintsPerClientHTML += `</td>
                                                  </tr>`;
                          $('#monthly-complaints').html(complaintsPerClientHTML)
                      }
                    }
                },
                error: function(error){

                }
            });
        }

            // User Satisfaction
            if($('#user-satisfaction').length){   
                var $stckedData = [
                    {
                      y: '1',
                      a: 4
                    }, {
                      y: '2',
                      a: 10
                    }, {
                      y: '3',
                      a: 17
                    }, {
                      y: '4',
                      a: 10,
                    }, {
                      y: '5',
                      a: 20
                    }
                ];
              createStackedChart('user-satisfaction', $stckedData, 'y', ['a'], [service], ['#7a6fbe']);
            }   
        //}
        // End chart config


        $('#top-investigator').addClass('active');
        $('#top_investigators_data').css('display', 'block');
        $('#top_delivery_data').css('display', 'none');
        $('#top-investigator').on('click', function(){
            $('#top-investigator').addClass('active');
            $('#top-delivery').removeClass('active');  
            $('#top_investigators_data').css('display', 'block');
            $('#top_delivery_data').css('display', 'none');  
        });
        $('#top-delivery').on('click', function(){
            $('#top-investigator').removeClass('active');
            $('#top-delivery').addClass('active');    
            $('#top_investigators_data').css('display', 'none');
            $('#top_delivery_data').css('display', 'block');
        });


        $('#open-invoice').addClass('active');
        $('#open_invoice_data').css('display', 'block');
        $('#close_invoice_data').css('display', 'none');
        $('#open-invoice').on('click', function(){
            $('#open-invoice').addClass('active');
            $('#close-invoice').removeClass('active');  
            $('#open_invoice_data').css('display', 'block');
            $('#close_invoice_data').css('display', 'none');
        });
        $('#close-invoice').on('click', function(){
            $('#open-invoice').removeClass('active');
            $('#close-invoice').addClass('active');  
            $('#open_invoice_data').css('display', 'none');
            $('#close_invoice_data').css('display', 'block');
        });

        $('#investigations-investigator').addClass('active');
        $('#investigator_data').css('display', 'block');
        $('#type_data').css('display', 'none');
        $('#client_data').css('display', 'none');
        $('#investigations-investigator').on('click', function(){
            $('#investigations-investigator').addClass('active');
            $('#investigations-type').removeClass('active');
            $('#investigations-client').removeClass('active');

            $('#investigator_data').css('display', 'block');
            $('#type_data').css('display', 'none');
            $('#client_data').css('display', 'none');
        });
        $('#investigations-type').on('click', function(){
            $('#investigations-investigator').removeClass('active');
            $('#investigations-type').addClass('active');
            $('#investigations-client').removeClass('active');

            $('#investigator_data').css('display', 'none');
            $('#type_data').css('display', 'block');
            $('#client_data').css('display', 'none');
        });
        $('#investigations-client').on('click', function(){
            $('#investigations-investigator').removeClass('active');
            $('#investigations-type').removeClass('active');
            $('#investigations-client').addClass('active');

            $('#investigator_data').css('display', 'none');
            $('#type_data').css('display', 'none');
            $('#client_data').css('display', 'block');
        });


        $('#late-investigation').addClass('active');
        $('#late_investigation_data').css('display', 'block');
        $('#late_invoice_data').css('display', 'none');
        
        $('#late-investigation').on('click', function(){
            $('#late-investigation').addClass('active');
            $('#late-invoice').removeClass('active');

            $('#late_investigation_data').css('display', 'block');
            $('#late_invoice_data').css('display', 'none');
        });
        $('#late-invoice').on('click', function(){
            $('#late-investigation').removeClass('active');
            $('#late-invoice').addClass('active');

            $('#late_investigation_data').css('display', 'none');
            $('#late_invoice_data').css('display', 'block');
        });


        $('#pending-investigations').addClass('active');
        $('#pending_investigations_data').css('display', 'block');
        $('#pending_clients_data').css('display', 'none');
        $('#pending_investigators_data').css('display', 'none');
        $('#pending_delivery_boys_data').css('display', 'none');
        $('#pending-investigations').on('click', function(){
            $('#pending-investigations').addClass('active');
            $('#pending-clients').removeClass('active');
            $('#pending-investigators').removeClass('active');
            $('#pending-delivery-boys').removeClass('active');

            $('#pending_investigations_data').css('display', 'block');
            $('#pending_clients_data').css('display', 'none');
            $('#pending_investigators_data').css('display', 'none');
            $('#pending_delivery_boys_data').css('display', 'none');
        });
        $('#pending-clients').on('click', function(){
            $('#pending-clients').addClass('active');
            $('#pending-investigations').removeClass('active');
            $('#pending-investigators').removeClass('active');
            $('#pending-delivery-boys').removeClass('active');

            $('#pending_clients_data').css('display', 'block');
            $('#pending_investigations_data').css('display', 'none');
            $('#pending_investigators_data').css('display', 'none');
            $('#pending_delivery_boys_data').css('display', 'none');
        });
        $('#pending-investigators').on('click', function(){
            $('#pending-investigators').addClass('active');
            $('#pending-clients').removeClass('active');
            $('#pending-investigations').removeClass('active');
            $('#pending-delivery-boys').removeClass('active');

            $('#pending_investigators_data').css('display', 'block');
            $('#pending_clients_data').css('display', 'none');
            $('#pending_investigations_data').css('display', 'none');
            $('#pending_delivery_boys_data').css('display', 'none');
        });
        $('#pending-delivery-boys').on('click', function(){
            $('#pending-delivery-boys').addClass('active');
            $('#pending-clients').removeClass('active');
            $('#pending-investigators').removeClass('active');
            $('#pending-investigations').removeClass('active');

            $('#pending_delivery_boys_data').css('display', 'block');
            $('#pending_clients_data').css('display', 'none');
            $('#pending_investigators_data').css('display', 'none');
            $('#pending_investigations_data').css('display', 'none');
        });

        

        $('#deliveries-investigator').addClass('active');
        $('#delivery_deliveryboy_data').css('display', 'block');
        $('#delivery_type_data').css('display', 'none');
        $('#delivery_client_data').css('display', 'none');
        $('#deliveries-investigator').on('click', function(){
            $('#deliveries-investigator').addClass('active');
            $('#deliveries-type').removeClass('active');
            $('#deliveries-client').removeClass('active');

            $('#delivery_deliveryboy_data').css('display', 'block');
            $('#delivery_type_data').css('display', 'none');
            $('#delivery_client_data').css('display', 'none');
        });
        $('#deliveries-type').on('click', function(){
            $('#deliveries-investigator').removeClass('active');
            $('#deliveries-type').addClass('active');
            $('#deliveries-client').removeClass('active');

            $('#delivery_deliveryboy_data').css('display', 'none');
            $('#delivery_type_data').css('display', 'block');
            $('#delivery_client_data').css('display', 'none');
        });
        $('#deliveries-client').on('click', function(){
            $('#deliveries-investigator').removeClass('active');
            $('#deliveries-type').removeClass('active');
            $('#deliveries-client').addClass('active');

            $('#delivery_deliveryboy_data').css('display', 'none');
            $('#delivery_type_data').css('display', 'none');
            $('#delivery_client_data').css('display', 'block');
        });

    </script>
@endsection