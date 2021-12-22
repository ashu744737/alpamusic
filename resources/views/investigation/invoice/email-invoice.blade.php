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
                                <a href="/" class="logo logo-admin"><img src="{{ URL::asset('/images/logo-dark.png')}}"  height="50" alt="logo"></a>
                            </h3>
                            <div class="p-3">
                                <h4 class="text-muted font-size-18 mb-1 text-center">{{ trans('form.registration.verifymail.profile_update_text') }} {{ $userData['name'] ?? '' }}, {{ trans('content.email.invoice_ready') }}</h4>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                <div class="row">
                                    <div class="col-12">
                                        {{--<address>
                                            <strong>{{trans('form.performa_invoice.address')}}<br>
                                            {{trans('form.performa_invoice.tel_fax')}}<br>
                                            {{trans('form.performa_invoice.cell')}}<br><br>
                                            {{trans('form.performa_invoice.date')}}{{\Carbon\Carbon::parse($invn['clientinvoice'][0]['created_at'])->format('d/m/y')}}<br>
                                            {{trans('form.performa_invoice.company_entity')}}<br>
                                            {{trans('form.performa_invoice.copy')}}<br><br>
                                            {{trans('form.performa_invoice.to')}} {{$invn['client_type']['name']}} {{trans('form.performa_invoice.id')}} {{$invn['subjects'][0]['id_number']}}<br>
                                            {{$invn['client_type']['userAddresses'][0]->address2 ?? ''}} , {{$invn['client_type']['userAddresses'][0]->city}} , {{ App::isLocale('hr')? $invn['client_type']['userAddresses'][0]['country']['hr_name']:$invn['client_type']['userAddresses'][0]['country']['en_name'] }}{{ '-'.$invn['client_type']['userAddresses'][0]['zipcode'] ?? '-' }}. 
                                        </address>--}}
                                        <address>
                                            <strong>
                                            <div>{{trans('form.performa_invoice.address')}}</div>
                                            <div>{{trans('form.performa_invoice.tel_fax')}}</div>
                                            <div>{{trans('form.performa_invoice.cell')}}</div><br>
                                            @if(config('app.locale') == 'hr')
                                            <div style="text-align: left;">{{trans('form.performa_invoice.date')}}{{\Carbon\Carbon::parse($invn['clientinvoice'][0]['created_at'])->format('d/m/y')}}</div>
                                            @else
                                            <div>{{trans('form.performa_invoice.date')}}{{\Carbon\Carbon::parse($invn['clientinvoice'][0]['created_at'])->format('d/m/y')}}</div>
                                            @endif
                                            <div>{{trans('form.performa_invoice.company_entity')}}</div>
                                            <div>{{trans('form.performa_invoice.copy')}}</div><br>
                                            <!-- <div>{{--trans('form.performa_invoice.to')}} {{trans('form.performa_invoice.bank_name')}} {{trans('form.performa_invoice.id')}} {{$invn['client']['legal_entity_no'] ?? ''--}} </div> -->
                                            <div>
                                                {{ trans('form.registration.investigation.paying_customer') }} : {{ $invn->client_customer->name }}
                                            </div>
                                            <div>{{$invn['client_type']['userAddresses'][0]->address2 ?? ''}} , {{$invn['client_type']['userAddresses'][0]->city}} , {{ App::isLocale('hr')? $invn['client_type']['userAddresses'][0]['country']['hr_name']:$invn['client_type']['userAddresses'][0]['country']['en_name'] }}{{ '-'.$invn['client_type']['userAddresses'][0]['zipcode'] ?? '-' }}. </div>
                                            </strong>
                                        </address>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div>
                                        <center class="p-2">
                                            <h4 class="p-0 m-0">{{trans('form.performa_invoice.performa_invoice_txt')}}<b>{{$invn['clientinvoice'][0]['invoice_no']}}</b></h4>
                                        </center>
                                        <center class="p-2">
                                            <p class="m-0 p-0">{{trans('form.performa_invoice.doc_not_tax_invoice')}}</p>
                                        </center>
                                        <div>
                                            <h5>{{trans('form.performa_invoice.card_no')}} {{$invn['work_order_number']}}</h5>
                                        </div>
                                        <div>
                                            <h5>{{trans('form.performa_invoice.type')}} {{$invn['product']['name']}}</h5>
                                        </div>
                                        <div>
                                            <h5>{{trans('form.performa_invoice.subject')}} @foreach($invn['subjects'] as $key => $subType) {{$key+1}}. {{$subType['first_name']}} {{$subType['family_name']}} 
                                            @endforeach </h5>
                                        </div>
                                        <div class="">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <td><strong>{{ trans('form.performa_invoice.Description') }}</strong></td>
                                                            <td><strong>{{ trans('form.performa_invoice.Quantity') }}</strong></td>
                                                            <td class="text-right"><strong>{{ trans('form.performa_invoice.Sum') }}</strong></td>
                                                            <td class="text-right"><strong>{{ trans('form.performa_invoice.Price') }}</strong></td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if(count($invn['clientinvoice'])>0)
                                                    @php $subtot=0;@endphp
                                                    @foreach($invn['clientinvoice'][0]['invoiceitems'] as $invoiceitem)
                                                    <tr>
                                                        <td>{{$invoiceitem['subject']['first_name']}} - {{\App\SubjectTypes::where('name', $invoiceitem['subject']['sub_type'])->first()->hr_name}}</td>
                                                        <td>1</td>
                                                        <td>{{ trans('general.money_symbol') }}{{$invoiceitem['cost'] ?? '0' }}</td>
                                                        <td>{{ trans('general.money_symbol') }}{{$invoiceitem['cost'] ?? '0' }}</td>
                                                    </tr>
                                                    @php $subtot+=$invoiceitem['cost'];@endphp
                                                    @endforeach
                                                    @endif
                                                   
                                                    @php $includeVat = $notIncludeVat = 0; @endphp
                                                    @if(!empty($invn['documents']) && count($invn['documents']))
                                                    <tr>
                                                        <td colspan="4"></td>
                                                    </tr>
                                                    @foreach($invn['documents'] as $doc)
                                                        @if($doc['documenttype']['include_vat'] && $doc['documenttype']['name'] != "Case Report")
                                                            @php $includeVat++; @endphp
                                                        @else
                                                            @if($doc['documenttype']['name'] != "Case Report")
                                                                @php $notIncludeVat++; @endphp
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                    @if($includeVat)
                                                    <tr>
                                                        <td colspan="4">
                                                            <h4 class="font-size-16">{{trans('form.registration.investigation.documents')}} <small>{{ trans('form.documenttypes.includevat') }}</small></h4>
                                                        </td>
                                                    </tr>
                                                    <thead>
                                                        <tr>
                                                            <td><strong>{{trans('general.sr_no')}}</strong></td>
                                                            <td><strong>{{trans('form.registration.investigation.documents')}}</strong></td>
                                                            <td><strong>{{trans('form.registration.investigation.document_type')}}</strong></td>
                                                            <td class="text-right"><strong>{{trans('form.products_form.price')}}</strong></td>
                                                        </tr>
                                                    </thead>
                                                    @php $docKey = 1; @endphp
                                                    @foreach($invn['documents'] as $key => $documents)
                                                        @if($documents['documenttype']['include_vat'] && $documents['documenttype']['name'] != "Case Report")
                                                        <tr>
                                                            <td>{{$docKey}}</td>
                                                            <td>{{$documents['doc_name']}} </td>
                                                            @if(config('app.locale') == 'hr')
                                                            <td>{{$documents['documenttype']['hr_name']}}</td>
                                                            @else
                                                            <td>{{$documents['documenttype']['name']}}</td>
                                                            @endif
                                                            <td class="text-right {{ $documents['documenttype']['include_vat']?'doc-cost':'' }}">{{ trans('general.money_symbol') }}{{$documents['documenttype']['price']}}</td>
                                                        </tr>
                                                        @php $subtot+=$documents['documenttype']['price']; $docKey++; @endphp
                                                        @endif
                                                    @endforeach
                                                    
                                                    @endif
                                                    @endif
                                                    <tr>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line text-right">{{ trans('form.performa_invoice.Total before tax') }} {{ trans('general.money_symbol') }}{{$subtot}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-right"> מע"מ {{ $invn['clientinvoice'][0]['tax'] }}%</td>
                                                    </tr>
                                                    @if($notIncludeVat)
                                                    <tr>
                                                        <td colspan="4">
                                                            <h4 class="font-size-16">{{trans('form.registration.investigation.documents')}} <small>{{ trans('form.documenttypes.notincludevat') }}</small></h4>
                                                        </td>
                                                    </tr>
                                                    <thead>
                                                        <tr>
                                                            <td><strong>{{trans('general.sr_no')}}</strong></td>
                                                            <td><strong>{{trans('form.registration.investigation.documents')}}</strong></td>
                                                            <td><strong>{{trans('form.registration.investigation.document_type')}}</strong></td>
                                                            <td class="text-right"><strong>{{trans('form.products_form.price')}}</strong></td>
                                                        </tr>
                                                    </thead>
                                                    @foreach($invn['documents'] as $key => $documents)
                                                        @if($documents['documenttype']['include_vat'] == 0 && $documents['documenttype']['name'] != "Case Report")
                                                        <tr>
                                                            <td>{{($key+1)}}</td>
                                                            <td>{{$documents['doc_name']}} </td>
                                                            @if(config('app.locale') == 'hr')
                                                            <td>{{$documents['documenttype']['hr_name']}}</td>
                                                            @else
                                                            <td>{{$documents['documenttype']['name']}}</td>
                                                            @endif
                                                            <td class="text-right {{ $documents['documenttype']['include_vat']?'doc-cost':'' }} ">{{ trans('general.money_symbol') }}{{$documents['documenttype']['price']}}</td>
                                                        </tr>
                                                        @php $subtot+=$documents['documenttype']['price'];@endphp
                                                        @endif
                                                    @endforeach
                                                    @endif
                                                    <tr>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-right">
                                                            @if($invn['clientinvoice'][0]['payment_status'] == 'discount')
                                                            <h4 class="m-0">{{ trans('form.performa_invoice.Total') }} {{ trans('general.money_symbol') }}{{($invn['clientinvoice'][0]->amount - $invn['clientinvoice'][0]->discount_amount)}}</h4>
                                                            {{trans('general.discount_of')}} {{ trans('general.money_symbol') }}{{$invn['clientinvoice'][0]->discount_amount}}
                                                            @else
                                                            <h4 class="m-0">{{ trans('form.performa_invoice.Total') }} {{ trans('general.money_symbol') }}{{$invn['clientinvoice'][0]->amount}}</h4>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            
                                            <div class="d-print-none">
                                                <h4 class="mt-0">
                                                    <img src="{{ URL::asset('/images/logo-dark.png')}}" alt="logo" height="24"/>
                                                </h4>
                                                <p>{{trans('form.performa_invoice.invoice_dec')}}</p>
                                                <p>{{trans('form.performa_invoice.invoice_dec2')}}</p>
                                                <p>{{trans('form.performa_invoice.our_bank_detail')}}</p>
                                                <p>{{trans('form.performa_invoice.bank_line_1')}}</p>
                                                <p><b>{{trans('form.performa_invoice.bank_line_2')}}</b></p>
                                                <br>
                                                <p><b>{{trans('form.performa_invoice.Respectfully')}}</b></p>
                                                <p><b>{{trans('general.app_name')}}</b></p>
                                            </div>
                                            <div class="d-print-none">
                                                @php $link=route('invoice.show', [Crypt::encrypt($invn['clientinvoice'][0]['id']), 'pinvoice']); @endphp
                                                <a href="{{$link}}" target="_blank">{{trans('form.email_tem.ticket_update.view_invoice')}}</a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
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

