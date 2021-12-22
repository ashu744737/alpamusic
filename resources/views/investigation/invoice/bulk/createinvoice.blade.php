<div class="col-12">
    <div class="card">
        <div class="card-body">
            @foreach($invn as $key => $invnn)
            
            <div class="row">
                @if(count($invnn['user']['userAddresses']) > 0)
                <div class="col-12">
                  
                    <div class="invoice-title">
                        <h3 class="mt-0">
                                <img src="{{ URL::asset('/images/logo-dark.png')}}" alt="logo" height="24"/>
                            </h3>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <address>
                                @if(count($invnn['user']['userAddresses']) > 0)
                                <strong>{{ trans('form.investigationinvoice.bill_to') }}</strong><br>
                                {{$invnn['user']['client']['printname']}}<br>
                                @if(count($invnn['user']['userAddresses'])) {{$invnn['user']['userAddresses'][0]['address2']}} @else - @endif<br>

                                @if(count($invnn['user']['userAddresses'])){{ $invnn['user']['userAddresses'][0]['city'].','}}{{ $invnn['user']['userAddresses'][0]['state'].',' }} @else - @endif<br>

                                @if(count($invnn['user']['userAddresses'])){{ App::isLocale('hr')? $invnn['user']['userAddresses'][0]['country']['hr_name']:$invnn['user']['userAddresses'][0]['country']['en_name'] }}{{ '-'.$invnn['user']['userAddresses'][0]['zipcode'] }} @else - @endif
                                @endif
                            </address>
                        </div>
                        <div class="col-6 text-right">
                            <address>

                                    <strong>{{ trans('form.investigationinvoice.paying_customer_to') }}</strong><br>
                                    {{$invnn['client_type']['name'] ?? '-' }}<br>
                                   
                                    @if(!is_null($invnn['client_type']))
                                    @if(count($invnn['client_type']['userAddresses'])){{$invnn['client_type']['userAddresses'][0]['address2'] }} @else - @endif<br>
                                    @if(count($invnn['client_type']['userAddresses'])){{ $invnn['client_type']['userAddresses'][0]['city'].',' }}{{ $invnn['client_type']['userAddresses'][0]['state'].',' }}@else - @endif<br>
                                    @if(count($invnn['client_type']['userAddresses'])){{ App::isLocale('hr')? $invnn['client_type']['userAddresses'][0]['country']['hr_name']:$invnn['client_type']['userAddresses'][0]['country']['en_name'] }}{{ '-'.$invnn['client_type']['userAddresses'][0]['zipcode'] }} @else - @endif
                                    @endif

                                </address>
                        </div>
                    </div>
                    @if(count($invnn['user']['userAddresses']) > 0)
                    <div class="row">
                        <div class="col-6 mt-4">
                            <address>
                                <b>{{ trans('form.investigationinvoice.order_date') }}</b> {{ date('M d, Y', strtotime($invnn['created_at'])) ?? '-' }}<br>
                                <b>{{ trans('form.investigationinvoice.work_order_number') }}</b>  {{$invnn['work_order_number']}}<br>
                                <b>{{ trans('form.investigationinvoice.required_type_inquiry') }}</b> {{$invnn['product']['name']}}<br>
                            </address>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
            @if(count($invnn['subjects']) > 0)
            <div class="row">
                <div class="col-12">
                    <div>
                        <div class="p-2">
                            <h3 class="font-size-16"><strong>{{ trans('form.investigationinvoice.item_summary') }}</strong></h3>
                        </div>
                        <div class="">
                            <table class="table">
                                <thead>
                                <tr>
                                    <td><strong>{{ trans('form.investigationinvoice.subjects') }}</strong></td>
                                    <td><strong>{{ trans('form.investigationinvoice.cost') }}</strong></td>

                                    <td class="text-right"><strong>{{ trans('form.investigationinvoice.totals') }}</strong></td>
                                </tr>
                                </thead>
                                <tbody>

                                @php $subtot=0;@endphp

                                @foreach($invnn['subjects'] as $subject)
                                    <tr>
                                        <td><a target="_blank" href="{{route('subject.detail', ['subjectId' => Crypt::encrypt($subject['id'])])}}">{{$subject['family_name']}} ({{$subject['first_name']}})</a></td>
                                        <td>{{ trans('general.money_symbol') }}{{$subject['req_inv_cost'] ?? '0' }}</td>

                                        <td class="text-right">{{ trans('general.money_symbol') }}&nbsp;
                                            <input type="number" class="sub-amt" name="sub-amt[]" value="{{$subject['req_inv_cost'] ?? '0' }}" step="0.01" data-id="{{ $subject['id'] }}" required>
                                        </td>
                                    </tr>
                                    @php $subtot+=$subject['req_inv_cost'];@endphp
                                @endforeach

                                <tr>
                                    <td class="thick-line"></td>
                                    <td class="thick-line">
                                        <strong>{{ trans('form.investigationinvoice.subtotal') }}</strong>
                                    </td>
                                    <td class="thick-line text-right">{{ trans('general.money_symbol') }}<span class="sub-total"></span></td>
                                </tr>
                                <tr>
                                    <td class="no-line"></td>
                                    <td class="no-line">
                                        <strong>{{ trans('form.investigationinvoice.tax') }}</strong>
                                    </td>
                                    @php
                                        $tax = '0.0';
                                        $taxSetting = $settings->firstWhere('key', 'tax');
                                        if($taxSetting){
                                            $tax = $taxSetting->value;
                                        }
                                    @endphp
                                    <td class="no-line text-right"><span class="tax">{{ $tax }}</span>%</td>
                                </tr>
                                <tr>
                                    <td class="no-line"></td>
                                    <td class="no-line">
                                        <strong>{{ trans('form.investigationinvoice.total') }}</strong>
                                    </td>
                                    <td class="no-line text-right">
                                        <h4 class="m-0">{{ trans('general.money_symbol') }}<span class="final-total"></span></h4>
                                    </td>
                                </tr>

                                </tbody>
                            </table>

                            <div class="d-print-none">
                                <div class="float-right">
                                    <a href="javascript:window.print()" id="clickbind" class="btn btn-success waves-effect waves-light mr-2"><i class="fa fa-print"></i></a>
                                    @if(count($invnn['clientinvoice'])==0)
                                    <a href="javascript:generateinvoice({{$invnn['id']}})"  class="btn btn-primary waves-effect waves-light">{{ trans('form.investigationinvoice.generate_invoice') }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @endif
            <!-- end row -->
            @endforeach    
        </div>
    </div>
</div>
<!-- end col -->