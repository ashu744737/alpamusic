<div class="col-12">
    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-12">
                  
                    <div class="invoice-title">
                        <h4 class="float-right font-size-16"><strong>{{ trans('form.performa_invoice.performa_invoice_txt') }} #  {{$invno}}</strong></h4>
                        <h3 class="mt-0">
                                <img src="{{ URL::asset('/images/logo-dark.png')}}" alt="logo" height="24"/>
                            </h3>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                        {{--<h4 class="mt-0">{{trans('form.app_name')}} </h4>--}}
                            <address>
                                    <strong>{{ trans('form.investigationinvoice.paying_customer_to') }}</strong><br>
                                    {{$invn->client_type->name ?? '-' }}<br>
                                   
                                    @if(!empty($invn->client_type)>0)
                                    {{$invn->client_type->userAddresses[0]->address2 ?? '-' }}<br>
                                    {{ $invn->client_type->userAddresses[0]->city.',' ?? '-' }}{{ $invn->client_type->userAddresses[0]->state.',' ?? '-' }}<br>
                                    {{ App::isLocale('hr')? $invn->client_type->userAddresses[0]->country->hr_name:$invn->client_type->userAddresses[0]->country->en_name }}{{ '-'.$invn->client_type->userAddresses[0]->zipcode ?? '-' }}
                                    @endif
                            
                                </address>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-4">
                            <address>
                            @if(config('app.locale') == 'hr')
                                <div style="text-align: left;">
                                    <b>{{ trans('form.investigationinvoice.order_date') }}</b> {{ date('M d, Y', strtotime($invn->created_at)) ?? '-' }}
                                </div>
                            @else
                                <div style="text-align: left;">
                                    <b>{{ trans('form.investigationinvoice.order_date') }}</b> {{ date('M d, Y', strtotime($invn->created_at)) ?? '-' }}
                                </div>
                            @endif
                                <b>{{ trans('form.investigationinvoice.work_order_number') }}</b>  {{$invn->work_order_number}}<br>
                                <b>{{ trans('form.investigationinvoice.required_type_inquiry') }}</b> {{$invn->product->name}}<br>
                                <b>{{ trans('form.investigationinvoice.ext_claim_number') }}</b> {{$invn->ex_file_claim_no}}<br>
                                <b>{{ trans('form.investigationinvoice.claim_number') }}</b> {{$invn->claim_number}}<br>
                            </address>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div>
                    <center class="p-2">
                            <h4 class="p-0 m-0">{{trans('form.performa_invoice.performa_invoice_txt')}} <b>{{$invno}}</b></h4>
                        </center>
                        {{--<center class="p-2">
                            <p class="m-0 p-0">{{trans('form.performa_invoice.doc_not_tax_invoice')}}</p>
                        </center>--}}
                        <div class="p-2">
                            <h3 class="font-size-16"><strong>{{ trans('form.investigationinvoice.item_summary') }}</strong></h3>
                        </div>
                        <div class="table-responsive">
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
                                @foreach($invn->subjects as $subject)
                                    <tr>
                                        <td><a target="_blank" href="{{route('subject.detail', ['subjectId' => Crypt::encrypt($subject->id)])}}">{{$subject->full_name }} - @if(config('app.locale') == 'hr')
                                        {{ !is_null(\App\SubjectTypes::where('name', $subject->sub_type)->first())?\App\SubjectTypes::where('name', $subject->sub_type)->first()->hr_name:(!is_null($subject->sub_type)?$subject->sub_type:'') }}
                                        @else
                                        {{ !is_null(\App\SubjectTypes::where('name', $subject->sub_type)->first())?\App\SubjectTypes::where('name', $subject->sub_type)->first()->name:(!is_null($subject->sub_type)?$subject->sub_type:'') }}
                                        @endif</a></td>
                                        <td>{{ trans('general.money_symbol') }}{{$subject->req_inv_cost ?? '0' }}</td>

                                        <td class="text-right">{{ trans('general.money_symbol') }}&nbsp;
                                            <input type="number" class="sub-amt" name="sub-amt[]" value="{{$subject->req_inv_cost ?? '0' }}" step="0.01" data-id="{{ $subject->id }}" required>
                                        </td>
                                    </tr>
                                    @php $subtot+=$subject->req_inv_cost;@endphp
                                @endforeach
                                @php $includeVat = $notIncludeVat = 0; @endphp
                                @foreach($invn->documents as $doc)
                                    @if($doc->documenttype->include_vat && $doc->documenttype->name != "Case Report")
                                        @php $includeVat++; @endphp
                                    @else
                                        @if($doc->documenttype->name != "Case Report")
                                            @php $notIncludeVat++; @endphp
                                        @endif
                                    @endif
                                @endforeach
                                @if(!empty($invn->documents) && count($invn->documents) && $includeVat)
                                <tr>
                                    <td colspan="3"></td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <h4 class="font-size-16">{{trans('form.registration.investigation.documents')}} <small>{{ trans('form.documenttypes.includevat') }}</small></h4>
                                    </td>
                                </tr>
                                <thead>
                                    <tr>
                                        <td><strong>{{trans('form.registration.investigation.documents')}}</strong></td>
                                        <td><strong>{{trans('form.registration.investigation.document_type')}}</strong></td>
                                        <td class="text-right"><strong>{{trans('form.products_form.price')}}</strong></td>
                                    </tr>
                                </thead>
                                @foreach($invn->documents as $documents)
                                @if($documents->documenttype->include_vat && $documents->documenttype->name != "Case Report")
                                <tr>
                                    <td>{{$documents->doc_name}}</td>
                                    @if(config('app.locale') == 'hr')
                                    <td>{{$documents->documenttype->hr_name}}</td>
                                    @else
                                    <td>{{$documents->documenttype->name}}</td>
                                    @endif
                                    <td class="text-right {{ $documents->documenttype->include_vat?'doc-cost':'' }}" data-id="{{ $documents->id }}">{{ trans('general.money_symbol') }}{{$documents->price}}</td>
                                </tr>
                                @endif
                                @endforeach
                                @endif
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
                                    <td class="thick-line"></td>
                                    <td class="thick-line">
                                        <strong>{{trans('form.investigationinvoice.total_after_tax')}}</strong>
                                    </td>
                                    <td class="thick-line text-right">{{ trans('general.money_symbol') }}<span class="total-after-tax"></span></td>
                                </tr>
                                @if($notIncludeVat)
                                    @php $nonVatDocTotal = 0; @endphp
                                    <tr>
                                        <td colspan="3">
                                            <h4 class="font-size-16">{{trans('form.registration.investigation.documents')}} <small>{{ trans('form.documenttypes.notincludevat') }}</small></h4>
                                        </td>
                                    </tr>
                                    <thead>
                                        <tr>
                                            <td><strong>{{trans('form.registration.investigation.documents')}}</strong></td>
                                            <td><strong>{{trans('form.registration.investigation.document_type')}}</strong></td>
                                            <td class="text-right"><strong>{{trans('form.products_form.price')}}</strong></td>
                                        </tr>
                                    </thead>
                                    @foreach($invn->documents as $key => $documents)
                                        @if($documents->documenttype->include_vat == 0 && $documents->documenttype->name != "Case Report")
                                        <tr>
                                            <td>{{$documents->doc_name}} </td>
                                            @if(config('app.locale') == 'hr')
                                            <td>{{$documents->documenttype->hr_name}}</td>
                                            @else
                                            <td>{{$documents->documenttype->name}}</td>
                                            @endif
                                            <td class="text-right {{ $documents->documenttype->include_vat==0?'not-doc-cost':'' }} " data-id="{{ $documents->id }}">{{ trans('general.money_symbol') }}{{$documents->price}}</td>
                                        </tr>
                                        @php 
                                            $subtot+=$documents->price;
                                            $nonVatDocTotal+=$documents->price;
                                        @endphp
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td class="thick-line"></td>
                                        <td class="thick-line">
                                            <strong>{{trans('form.investigationinvoice.total_non_vat_doc')}}</strong>
                                        </td>
                                        <td class="thick-line text-right">{{ trans('general.money_symbol') }}<span class="total-non-vat-doc">{{$nonVatDocTotal}}</span></td>
                                    </tr>
                                    @endif
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
                                    @if(count($invn->clientinvoice)==0)
                                    <a href="javascript:generateinvoice({{$invn->id}})"  class="btn btn-primary waves-effect waves-light">{{ trans('form.investigationinvoice.generate_invoice') }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- end row -->

        </div>
    </div>
</div>
<!-- end col -->