<div class="col-12">
    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-12">                 
                    <div class="invoice-title">
                            @if($invoice->status == 'paid') 
                                <span class="badge dt-badge badge-success" style="float: right;"> {{ trans('form.timeline_status.'.ucwords($invoice->status))}}</span> 
                            @endif
                        <h4 class="mt-0">
                            <img src="{{ URL::asset('/images/logo-dark.png')}}" alt="logo" height="24"/>
                        </h4>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h4 class="mt-0">{{trans('form.app_name')}} </h4>
                            {{--<address>
                                <strong>{{ trans('form.investigationinvoice.paying_customer_to') }}</strong> {{$invoice->client->user->name ?? '-' }}<br>
                                {{$invoice->client->user->userAddresses[0]->address2 ?? '-' }}<br>
                                {{ $invoice->client->user->userAddresses[0]->city.',' ?? '-' }}{{ $invoice->client->user->userAddresses[0]->state.',' ?? '-' }}<br>
                                {{ App::isLocale('hr')? $invoice->client->user->userAddresses[0]->country->hr_name:$invoice->client->user->userAddresses[0]->country->en_name }}{{ '-'.$invoice->client->user->userAddresses[0]->zipcode ?? '-' }}
                            </address>--}}
                            <address>
                                <strong>
                                <div>{{ $invoice->client->getUserAddress->address1 ? $invoice->client->getUserAddress->address1 : trans('form.registration.investigation.no_addresses') }}</div>
                                <div>{{trans('form.performa_invoice.tel_fax')}}</div>
                                <div>{{trans('form.performa_invoice.cell')}}</div><br>
                                @if(config('app.locale') == 'hr')
                                <div style="text-align: left;">{{trans('form.performa_invoice.date')}}{{\Carbon\Carbon::parse($invoice->created_at)->format('d/m/y')}}</div>
                                @else
                                <div>{{trans('form.performa_invoice.date')}}{{\Carbon\Carbon::parse($invoice->created_at)->format('d/m/y')}}</div>
                                @endif
                                <div>{{trans('form.performa_invoice.company_entity')}}</div>
                                @if(isClient())
                                <div>{{trans('form.performa_invoice.copy')}}</div><br>
                                @endif
                                <!-- <div>{{trans('form.performa_invoice.to')}} {{trans('form.performa_invoice.bank_name')}} {{trans('form.performa_invoice.id')}} {{$invoice->client->legal_entity_no ?? ''}} </div> -->
                                <div>
                                    @php
                                        $name = '';
                                        foreach ($invoice->investigation as $k => $investigation) {
                                            $name .= $investigation->client_type->name.' ';
                                        }
                                    @endphp
                                    {{ trans('form.registration.investigation.paying_customer') }} : {{ $name }}
                                </div>
                            </address>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div>
                        <center>
                            <h4 class="p-0 m-0">{{ trans('form.performa_invoice.invoice_number') }} {{$invoice->invoice_no}}</h4>
                        </center>
                        <div class="">
                            <h3 class="font-size-16"><strong>{{ trans('form.investigationinvoice.item_summary') }}</strong></h3>
                        </div>
                        <div class="">
                            @foreach($invoice->investigation as $invn)
                            <div class="row">
                                <div class="col-12 mt-2">
                                    <address>
                                        <b>{{ trans('form.investigationinvoice.order_date') }}</b> {{ date('M d, Y', strtotime($invn->created_at)) ?? '-' }}<br>
                                        <b>{{ trans('form.investigationinvoice.work_order_number') }}</b>  {{$invn->work_order_number}}<br>
                                        <b>{{ trans('form.investigationinvoice.required_type_inquiry') }}</b> {{$invn->product->name}}<br>
                                        <b>{{ trans('form.investigationinvoice.ext_claim_number') }}</b> {{$invn->ex_file_claim_no}}<br>
                                        <b>{{ trans('form.investigationinvoice.claim_number') }}</b> {{$invn->claim_number}}<br>
                                    </address>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <td colspan="2"><strong>{{ trans('form.investigationinvoice.subjects') }}</strong></td>
                                            <td><strong>{{ trans('form.investigationinvoice.cost') }}</strong></td>                                            
                                            <td class="text-right"><strong>{{ trans('form.investigationinvoice.totals') }}</strong></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($invn->clientinvoice)>0)
                                        
                                        @php $subtot=0;@endphp
                                        @foreach($invn->clientinvoice->first()->invoiceitems as $invoiceitem)
                                        <tr>
                                            <td colspan="2">{{!is_null($invoiceitem->subject)?$invoiceitem->subject->family_name:''}} ({{!is_null($invoiceitem->subject)?$invoiceitem->subject->first_name:''}})</td>
                                            <td>{{ trans('general.money_symbol') }}{{$invoiceitem->cost ?? '0' }}</td>
                                            <td class="text-right">{{ trans('general.money_symbol') }}{{$invoiceitem->cost ?? '0' }}</td>
                                        </tr>
                                        @php $subtot+=$invoiceitem->cost;@endphp
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
                                            <td colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <h4 class="font-size-16">{{trans('form.registration.investigation.documents')}} <small>{{ trans('form.documenttypes.includevat') }}</small></h4>
                                            </td>
                                        </tr>
                                        <thead>
                                            <tr>
                                                <td><strong>{{trans('form.investigationinvoice.case_id')}}</strong></td>
                                                <td><strong>{{trans('form.registration.investigation.documents')}}</strong></td>
                                                <td><strong>{{trans('form.registration.investigation.document_type')}}</strong></td>
                                                <td class="text-right"><strong>{{trans('form.products_form.price')}}</strong></td>
                                            </tr>
                                        </thead>
                                        @foreach($invn->documents as $key => $documents)
                                        @if($documents->documenttype->include_vat && $documents->documenttype->name != "Case Report")
                                        <tr>
                                            <td>{{$invn->work_order_number}}</td>
                                            <td>{{$documents->doc_name}}</td>
                                            @if(config('app.locale') == 'hr')
                                            <td>{{$documents->documenttype->hr_name}}</td>
                                            @else
                                            <td>{{$documents->documenttype->name}}</td>
                                            @endif
                                            <td class="text-right {{ $documents->documenttype->include_vat?'doc-cost':'' }}">{{ trans('general.money_symbol') }}{{$documents->documenttype->price}}</td>
                                        </tr>
                                        @php $subtot+=$documents->documenttype->price;@endphp
                                        @endif
                                        @endforeach
                                        @endif

                                        <tr>
                                            <td colspan="2" class="thick-line"></td>
                                            <td class="thick-line">
                                                <strong>{{ trans('form.performa_invoice.Total before tax') }}</strong></td>
                                            <td class="thick-line text-right">{{ trans('general.money_symbol') }}{{$subtot}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="no-line"></td>
                                            <td class="no-line">
                                                <strong>{{ trans('form.investigationinvoice.tax') }} {{ $invn->clientinvoice->first()->tax }}%</strong>
                                            </td>
                                            @php
                                                $taxAmount = (($subtot * $invn->clientinvoice->first()->tax) / 100);
                                            @endphp
                                            <td class="no-line text-right"><span class="tax">{{ trans('general.money_symbol') }}{{ $taxAmount }}</span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="no-line"></td>
                                            <td class="no-line">
                                                <strong>{{ trans('form.investigationinvoice.total_sum') }} </strong>
                                            </td>
                                            @php
                                                $taxAmount = (($subtot * $invn->clientinvoice->first()->tax) / 100);
                                            @endphp
                                            <td class="no-line text-right"><span class="tax">{{ trans('general.money_symbol') }}{{ ($subtot+$taxAmount) }}</span></td>
                                        </tr>
                                        @php $docsubtot = 0; @endphp
                                        @if($notIncludeVat)
                                        <tr>
                                            <td colspan="4">
                                                <h4 class="font-size-16">{{trans('form.registration.investigation.documents')}} <small>{{ trans('form.documenttypes.notincludevat') }}</small></h4>
                                            </td>
                                        </tr>
                                        <thead>
                                            <tr>
                                                <td><strong>{{trans('form.investigationinvoice.case_id')}}</strong></td>
                                                <td><strong>{{trans('form.registration.investigation.documents')}}</strong></td>
                                                <td><strong>{{trans('form.registration.investigation.document_type')}}</strong></td>
                                                <td class="text-right"><strong>{{trans('form.products_form.price')}}</strong></td>
                                            </tr>
                                        </thead>
                                        
                                        @foreach($invn->documents as $key => $documents)
                                            @if($documents->documenttype->include_vat == 0 && $documents->documenttype->name != "Case Report" && $documents->documenttype->price != 0)
                                            <tr>
                                                <td>{{$invn->work_order_number}}</td>
                                                <td>{{$documents->doc_name}} </td>
                                                @if(config('app.locale') == 'hr')
                                                <td>{{$documents->documenttype->hr_name}}</td>
                                                @else
                                                <td>{{$documents->documenttype->name}}</td>
                                                @endif
                                                <td class="text-right {{ $documents->documenttype->include_vat?'doc-cost':'' }} ">{{ trans('general.money_symbol') }}{{$documents->documenttype->price}}</td>
                                            </tr>
                                            @php $docsubtot+=$documents->documenttype->price;@endphp
                                            @endif
                                        @endforeach
                                        @endif
                                        <tr>
                                            <td colspan="2" class="no-line"></td>
                                            <td class="no-line">
                                                <strong>{{ trans('form.investigationinvoice.total_for_doc') }}</strong></td>
                                            <td class="no-line text-right">
                                                {{ trans('general.money_symbol') }}{{$docsubtot}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="no-line"></td>
                                            <td class="no-line">
                                                <strong>{{ trans('form.investigationinvoice.total_sum') }}</strong></td>
                                            <td class="no-line text-right">
                                                {{ trans('general.money_symbol') }}{{($subtot+$taxAmount+$docsubtot)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="no-line"></td>
                                            <td class="no-line">
                                                <strong>{{ trans('form.investigationinvoice.Remove Tax') }}</strong></td>
                                            <td class="no-line text-right">
                                                {{ trans('general.money_symbol') }}0.00
                                            </td>
                                        </tr>
                                       
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            @endforeach
                            @php
                                $leftAmount = 0;
                                if($invoice->payment_status == 'discount'){
                                    $leftAmount = $invoice->amount - $invoice->discount_amount;
                                }else{
                                    if($invoice->payment_status == 'parital' && !is_null($invoice->parital_amount)){
                                        $leftAmount = $invoice->amount - $invoice->parital_amount;
                                    }else{
                                        $partialInvoices = \App\Invoice::where('partial_invoice_id',$invoice->partial_invoice_id)->get();
                                    
                                        if(!is_null($invoice->partial_invoice_id) && !is_null($invoice->partialInvoice) && $invoice->partialInvoice->payment_status == 'parital'){
                                            foreach($partialInvoices as $pInvoice){
                                                $leftAmount+=$pInvoice->amount;
                                            }
                                        }else{
                                            $leftAmount=$invoice->amount;
                                        }
                                    }
                                }
                            @endphp
                            @if($invoice->payment_status == 'parital' && !is_null($invoice->parital_amount))
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                         <tr>
                                            <td colspan="2" class="no-line"></td>
                                            <td class="no-line text-right">
                                                <strong>{{ trans('form.investigationinvoice.grand_total') }}</strong></td>
                                            <td class="no-line text-right">
                                                <h4 class="m-0">{{ trans('general.money_symbol') }}{{($leftAmount + $invoice->parital_amount)}}</h4>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @endif
                            @if($invoice->payment_status == 'parital' && !is_null($invoice->parital_amount))
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                         <tr>
                                            <td colspan="2" class="no-line"></td>
                                            <td class="no-line text-right">
                                                <strong>{{ trans('form.invoice.payment.amount_paid') }}</strong></td>
                                            <td class="no-line text-right">
                                                <h4 class="m-0">{{ trans('general.money_symbol') }}{{$invoice->parital_amount}}</h4>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                         <tr>
                                            <td colspan="2" class="no-line"></td>
                                            <td class="no-line text-right">
                                                @if(!is_null($invoice->partial_invoice_id) && !is_null($invoice->partialInvoice))
                                                    @if($invoice->partialInvoice->payment_status == 'parital')
                                                        <strong>{{ trans('form.invoice.payment.amount_paid') }}</strong></td>
                                                    @else
                                                        <strong>{{ trans('form.invoice.payment.amount_paid') }}</strong></td>
                                                    @endif
                                                @else
                                                    @if($invoice->payment_status == 'parital')
                                                        <strong>{{ trans('form.investigationinvoice.left_amount') }}</strong></td>
                                                    @else
                                                        <strong>{{ trans('form.investigationinvoice.total') }}</strong></td>
                                                    @endif
                                                @endif
                                            <td class="no-line text-right">
                                                @if($invoice->payment_status == 'discount')
                                                <h4 class="m-0">{{ trans('general.money_symbol') }}{{($invoice->amount - $invoice->discount_amount)}}</h4>
                                                {{trans('general.discount_of')}} {{ trans('general.money_symbol') }}{{$invoice->discount_amount}}
                                                @else
                                                    @if($invoice->payment_status == 'parital' && !is_null($invoice->parital_amount))
                                                    <h4 class="m-0">{{ trans('general.money_symbol') }}{{($invoice->amount - $invoice->parital_amount)}}</h4>
                                                    @else
                                                        @php 
                                                            $partialInvoices = \App\Invoice::where('partial_invoice_id', $invoice->partial_invoice_id)->get();
                                                        @endphp
                                                    
                                                        @if(!is_null($invoice->partial_invoice_id) && !is_null($invoice->partialInvoice) && $invoice->partialInvoice->payment_status == 'parital')
                                                            @foreach($partialInvoices as $pInvoice)
                                                                <h4 class="m-0">{{ trans('general.money_symbol') }}{{$pInvoice->amount}}</h4><br/>
                                                            @endforeach
                                                        @else
                                                            <h4 class="m-0">{{ trans('general.money_symbol') }}{{$invoice->amount}}</h4>
                                                        @endif
                                                @endif
                                            </td>
                                        </tr>
                                        @if(!is_null($invoice->partial_invoice_id) && !is_null($invoice->partialInvoice) && $invoice->payment_status == 'parital')
                                        <tr>
                                            <td colspan="2" class="no-line"></td>
                                            <td class="no-line text-right">
                                                <strong>{{ trans('form.investigationinvoice.grand_total') }}</strong></td>
                                            <td class="no-line text-right"><h4 class="m-0">{{ trans('general.money_symbol') }}{{($invoice->partialInvoice->amount - $invoice->partialInvoice->parital_amount)}}</h4></td>
                                        </tr>
                                        @endif
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            @php
                            $performa = $invn->clientinvoice->first();
                            @endphp
                            @if(!is_null($performa) && !is_null($performa->invoice) && (!is_null($invoice->received_date) || !is_null($invoice->payment_mode_id) || !is_null($invoice->payment_mode_id) || !is_null($invoice->bank_details) || !is_null($invoice->admin_notes)))
                            <h4 class="mt-0 mb-3">{{trans('form.investigationinvoice.payment_details')}}</h4>
                            <div class="row">
                                @if(!is_null($invoice) && !is_null($invoice->received_date))
                                <div class="form-group col-md-4">
                                    <label for="received_date"><b>{{trans('form.performa_invoice.received_date')}}:</b> {{!is_null($invoice)? \Carbon\Carbon::parse($invoice->received_date)->format('d/m/y'):''}}</label>
                                </div>
                                @endif
                                @if(!is_null($invoice) && !is_null($invoice->paid_date))
                                <div class="form-group col-md-4">
                                    <label for="paid_date"><b>{{trans('form.performa_invoice.paid_date')}}:</b> {{!is_null($invoice)?\Carbon\Carbon::parse($invoice->paid_date)->format('d/m/y'):''}}</label>
                                </div>
                                @endif
                                @if(!is_null($invoice) && !is_null($invoice->payment_mode_id))
                                <div class="form-group col-md-4">
                                    @php
                                    if(!is_null($invoice)){
                                        $paymentMode = \App\PaymentMode::where('id', $invoice->payment_mode_id)->first();
                                    } else {
                                        $paymentMode = '';
                                    }
                                    @endphp
                                    <label for="paymentForm"><b>{{ trans('form.client_approval.payment_mode') }}:</b> {{!is_null($paymentMode)?(App::isLocale('hr')?$paymentMode->hr_mode_name:$paymentMode->mode_name):''}}</label>
                                </div>
                                @endif
                                @if(!is_null($invoice) && !is_null($invoice->bank_details))
                                <div class="form-group col-md-6">
                                    <label for="bank_details"><b>{{trans('form.performa_invoice.bank_details_cheque_number')}}:</b> {{!is_null($invoice)?$invoice->bank_details:''}}</label>
                                </div>
                                @endif
                                @if(!is_null($invoice) && !is_null($invoice->admin_notes) && !isClient())
                                <div class="form-group col-md-6">
                                    <label for="userinquiry"><b>{{ trans('form.investigator_investigation_status.note_by_sm') }}:</b> {{!is_null($invoice)?$invoice->admin_notes:''}}</label>
                                </div>
                                @endif
                            </div>
                            @endif
                            {{--<div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                         <tr>
                                            <td class="no-line">{{ trans('form.registration.investigation.payment_note') }}</td>
                                            <td>{{ $invoice->client_payment_notes }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>--}}
                            @if(count($invoice->invoicedocs) > 0)
                            <div class="resp-order d-print-none">
                                <div class="">
                                    <h3 class="font-size-16"><strong>{{ trans('form.invoice.documents') }}</strong></h3>
                                </div>
                                <div class="table-rep-plugin">
                                    <div class="table-responsive mb-0" data-pattern="priority-columns">
                                        <table id="inve_documents" class="table table-bordered">
                                            <thead>
                                            <th>{{ trans('general.sr_no') }}</th>
                                            <th>{{ trans('form.registration.investigation.documents') }}</th>
                                            @if(isAdmin() || isSM() || isAccountant())
                                            <th>{{ trans('form.invoice.uploaded_by') }}</th>
                                            @endif
                                            <th>{{ trans('general.action') }}</th>
                                            </thead>
                                            <tbody>
                                            
                                            @php $indx = 1; @endphp
                                            @foreach($invoice->invoicedocs as $document)
                                            @php
                                            $viewable = (isAdmin() || isSM() || isAccountant() || (isClient()));
                                            @endphp
                                            @if($viewable)
                                            <tr>
                                            @php $isimage=0;
                                            $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp'];
                                            if(in_array($document->file_extension, $imageExtensions)){
                                                $isimage=1;
                                            }
                                            $imgurl='/invoice-pay-docs/'.$document->file_name;
                                            @endphp
                                            <td>{{$indx}}</td>
                                            <td><a href="javascript:void(0);">{{ $document->doc_name }}</a></td>
                                            @if(isAdmin() || isSM() || isAccountant())
                                            <td>{{$document->uploadedby->name}}</td>
                                            @endif
                                            <td>
                                                <div class="action_btns">
                                                    @if($isimage==1)
                                                        <a class="view image-popup-no-margins" href="{{URL::asset($imgurl)}}" target="_blank">
                                                            <i class="fas fa-eye"></i>
                                                            <img class="img-fluid d-none" alt="" src="{{URL::asset($imgurl)}}" width="75">
                                                        </a>
                                                    @else
                                                        <a class="view" href="{{URL::asset($imgurl)}}" target="_blank"><i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            </tr>
                                            @endif
                                            @php $indx++; @endphp
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(config('app.locale') == 'hr')
                            <div class="pt-3" style="text-align: left;">
                            @else
                            <div class="pt-3">
                            @endif
                                <p><b>{{trans('form.performa_invoice.Respectfully')}}</b></p>
                                <p><b> {{trans('form.app_name')}}</b></p>
                            </div>
                            <div class="d-print-none">
                                <div class="float-right" style="display: inline-flex;">
                                    <a href="javascript:window.print()" id="clickbind" class="btn btn-success waves-effect waves-light m-2"><i class="fa fa-print"></i></a>
                                    @if($invoice->status != 'paid')
                                        @if(isAdmin() || isSM() || isAccountant())
                                            <a href="javascript:void(0)"  class="btn btn-primary waves-effect waves-light m-2" data-toggle="modal" data-target="#partial_payment">{{ trans('general.partial_payment') }}</a>
                                            <a href="javascript:void(0)"  class="btn btn-primary waves-effect waves-light m-2" data-toggle="modal" data-target="#discount_payment_model">{{ trans('general.discount_payment') }}</a>
                                            <a href="javascript:void(0)"  class="btn btn-primary waves-effect waves-light m-2" data-toggle="modal" data-target="#mark_final_paid_model">{{ trans('general.mark_final_paid') }}</a>
                                        @endif
                                    @endif
                                    @if($performaInvoiceStatus != 'requested' && $performaInvoiceStatus != 'paid' && (isClient() || isAdmin() || isSM() || isAccountant()))
                                        <a href="javascript:void(0)" id="client_paid"  class="btn btn-primary waves-effect waves-light m-2" data-toggle="modal" data-target="#pay_model">{{ trans('general.mark_paid') }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- end row -->
            <div id="pay_model" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0">{{ trans('form.invoice.payment.payment_form') }}</h5>
                            <button id="closmod" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <label>{{ trans('form.invoice.payment.upload_doc_message') }}
                                    <span class="text-danger">*</span>
                                </label>
                                
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <form method="POST" class="dropzone dropzone-area" id="fileupload"
                                action="{{ route('client-invoice-pay') }}"
                                enctype="multipart/form-data"> 
                                @csrf
                                
                                    <input type="hidden" value="{{ Auth::id() }}" name="uploaded_by" />
                                    <input type="hidden" value="{{ $invn->id }}" name="investigation_id" />
                    
                                    <div class="fallback">
                                        <input name="file" type="files" multiple="multiple" class="form-control" />
                                    </div>
                                    <div class="dz-message">
                                        <h4>{{ trans('form.registration.investigation.document_dropfile') }}</h4>
                                    </div>
                               
                            </div><br/>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <label>{{ trans('form.invoice.payment.payment_note') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea 
                                    type="tex" placeholder="{{ trans('form.invoice.payment.enter_payment_note') }}"
                                    class="form-control" 
                                    name="notes"
                                    value=""
                                        required
                                    ></textarea>
                                </div>
                                </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="form-group text-center">
                                    <button type="button" id="uploadfile" class="uploadfile btn btn-primary w-md waves-effect waves-light addbtn">{{ trans('form.invoice.payment.payment_send') }}</button>
                                    <button type="button" id="closmod2" data-dismiss="modal" aria-hidden="true" class="closmod btn btn-secondary w-md waves-effect waves-light">{{ trans('general.cancel') }}</button>
                                </div>
                            </div>
                           
                            
                            </form>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
            </div>

            <div id="mark_final_paid_model" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0">{{ trans('general.mark_final_paid') }}</h5>
                            <button id="closmod" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('invoice.mark-final-paid') }}" method="POST" id="mark_as_final_paid_form" class="form needs-validation">
                            @csrf
                                <input type="hidden" value="{{ $invn->user_id }}" name="uploaded_by" />
                                <input type="hidden" value="{{ $invoice->id }}" name="invoice_id" />
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label>{{trans('form.email_tem.ticket_create.created_on')}}<br>{{!is_null($invoice)?\Carbon\Carbon::parse($invoice->created_at)->format('d/m/y'):''}}</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                            @php
                                                $amount = !is_null($invoice)?$invoice->amount:0;
                                                $tax = !is_null($invoice)?$invoice->tax:0;
                                                $sum = number_format($amount/((100+$tax)/100), 2);
                                            @endphp
                                        <label>{{trans('form.performa_invoice.Sum')}}<br>{{ trans('general.money_symbol') }}{{$sum}}</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label>{{trans('form.performa_invoice.Tax')}}<br>{{$tax}}%</label>
                                    </div>
                                </div>
                                @if(!is_null($invoice) && !is_null($invoice->parital_amount))
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                        <label>{{trans('form.invoice.payment.amount_paid')}}<br>{{ trans('general.money_symbol') }}{{$invoice->parital_amount}}</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                        <label>{{trans('form.performa_invoice.Total')}}<br>{{ trans('general.money_symbol') }}{{($amount - $invoice->parital_amount)}}</label>
                                    </div>
                                </div>
                                @else
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                        <label>{{trans('form.performa_invoice.Total')}}<br>{{ trans('general.money_symbol') }}{{$amount}}</label>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="received_date">{{trans('form.performa_invoice.received_date')}} <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control " name="received_date" required id="received_date" placeholder="{{ trans('form.performa_invoice.received_date') }}" value="{{!is_null($invoice)?$invoice->received_date:''}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="paid_date">{{trans('form.performa_invoice.paid_date')}} <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control " name="paid_date" required id="paid_date" placeholder="{{ trans('form.performa_invoice.paid_date') }}" value="{{!is_null($invoice)?$invoice->paid_date:''}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="paymentForm">{{ trans('form.client_approval.payment_mode') }}</label>
                                    <select class="form-control" name="payment_mode_id" id="payment_mode_id">
                                        @php 
                                        $payment_modes = \App\PaymentMode::orderBy('mode_name')->get();
                                        @endphp
                                        @foreach($payment_modes as $key => $mode)
                                            <option
                                                value="{{ $mode->id }}" {{ !is_null($invoice)?($invoice->payment_mode_id == $mode->id ? 'selected' : ''):'' }}>{{ App::isLocale('hr')?$mode->hr_mode_name:$mode->mode_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="bank_details">{{trans('form.performa_invoice.bank_details_cheque_number')}} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control " name="bank_details" required id="bank_details" placeholder="{{ trans('form.performa_invoice.bank_details_cheque_number') }}" value="{{!is_null($invoice)?$invoice->bank_details:''}}">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="userinquiry">{{ trans('form.investigator_investigation_status.note_by_sm') }} <span class="text-danger">*</span></label>
                                    <textarea 
                                    type="tex" placeholder="{{ trans('form.investigator_investigation_status.note_by_sm') }}"
                                    class="form-control" 
                                    name="admin_notes"
                                    value=""
                                    required
                                    >{{!is_null($invoice)?$invoice->admin_notes:''}}</textarea>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="form-group text-right">
                                    <button type="button" data-dismiss="modal" aria-hidden="true" class="closmod btn btn-secondary w-md waves-effect waves-light">{{ trans('general.cancel') }}</button>
                                    <button type="submit" class="btn btn-primary w-md waves-effect waves-light addbtn">{{ trans('general.submit') }}</button>
                                </div>
                            </div>          
                            </form>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
            </div> 

            <div id="discount_payment_model" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0">{{ trans('form.invoice.payment.discount_form') }}</h5>
                            <button id="closmod" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <form action="{{ route('invoice.discount-payment') }}" method="POST" id="discount_payment_form" class="form needs-validation">
                                @csrf
                                    <input type="hidden" value="{{ $invn->user_id }}" name="uploaded_by" />
                                    <input type="hidden" value="{{ $invoice->id }}" name="invoice_id" />
                                    <div class="fallback">
                                        <label>{{trans('form.invoice.payment.discount_amount')}}</label>
                                        <input type="text" name="discount_amount" value="{{ old('discount_amount') }}" class="form-control @error('discount_amount') is-invalid @enderror" autofocus id="discount_amount" placeholder="{{trans('form.invoice.payment.discount_amount')}}" required>
                                        <div class="invalid-feedback">
                                            {{ trans('form.contact.field.validation.first_name') }}
                                        </div>
                                        @error('discount_amount')
                                        <div class="validation-errors">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                            </div><br/>
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label>{{trans('form.email_tem.ticket_create.created_on')}}<br>{{!is_null($invoice)?\Carbon\Carbon::parse($invoice->created_at)->format('d/m/y'):''}}</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                            @php
                                                $amount = !is_null($invoice)?$invoice->amount:0;
                                                $tax = !is_null($invoice)?$invoice->tax:0;
                                                $sum = number_format($amount/((100+$tax)/100), 2);
                                            @endphp
                                        <label>{{trans('form.performa_invoice.Sum')}}<br>{{ trans('general.money_symbol') }}{{$sum}}</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label>{{trans('form.performa_invoice.Tax')}}<br>{{$tax}}%</label>
                                    </div>
                                </div>
                                @if(!is_null($invoice))
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                        <label>{{trans('form.invoice.payment.amount_paid')}}<br>{{ trans('general.money_symbol') }}{{$invoice->parital_amount}}</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                        <label>{{trans('form.performa_invoice.Total')}}<br>{{ trans('general.money_symbol') }}{{($amount - $invoice->parital_amount)}}</label>
                                    </div>
                                </div>
                                @else
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                        <label>{{trans('form.performa_invoice.Total')}}<br>{{ trans('general.money_symbol') }}{{$amount}}</label>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="received_date">{{trans('form.performa_invoice.received_date')}} <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control " name="received_date" required id="received_date" placeholder="{{ trans('form.performa_invoice.received_date') }}" value="{{!is_null($invoice)?$invoice->received_date:''}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="paid_date">{{trans('form.performa_invoice.paid_date')}} <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control " name="paid_date" required id="paid_date" placeholder="{{ trans('form.performa_invoice.paid_date') }}" value="{{!is_null($invoice)?$invoice->paid_date:''}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="paymentForm">{{ trans('form.client_approval.payment_mode') }}</label>
                                    <select class="form-control" name="payment_mode_id" id="payment_mode_id">
                                        @php 
                                        $payment_modes = \App\PaymentMode::orderBy('mode_name')->get();
                                        @endphp
                                        @foreach($payment_modes as $key => $mode)
                                            <option
                                                value="{{ $mode->id }}" {{ !is_null($invoice)?($invoice->payment_mode_id == $mode->id ? 'selected' : ''):'' }}>{{ App::isLocale('hr')?$mode->hr_mode_name:$mode->mode_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="bank_details">{{trans('form.performa_invoice.bank_details_cheque_number')}} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control " name="bank_details" required id="bank_details" placeholder="{{ trans('form.performa_invoice.bank_details_cheque_number') }}" value="{{!is_null($invoice)?$invoice->bank_details:''}}">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="userinquiry">{{ trans('form.investigator_investigation_status.note_by_sm') }} <span class="text-danger">*</span></label>
                                    <textarea 
                                    type="tex" placeholder="{{ trans('form.investigator_investigation_status.note_by_sm') }}"
                                    class="form-control" 
                                    name="admin_notes"
                                    value=""
                                    required
                                    >{{!is_null($invoice)?$invoice->admin_notes:''}}</textarea>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="form-group text-right">
                                    <button type="button" data-dismiss="modal" aria-hidden="true" class="closmod btn btn-secondary w-md waves-effect waves-light">{{ trans('general.cancel') }}</button>
                                    <button type="submit" class="btn btn-primary w-md waves-effect waves-light addbtn">{{ trans('general.submit') }}</button>
                                </div>
                            </div>          
                            </form>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
            </div> 

            <div id="partial_payment" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0">{{ trans('form.invoice.payment.parital_form') }}</h5>
                            <button id="closmod" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <form action="{{ route('invoice.parital-payment') }}" onSubmit="return dosubmit();" method="POST" id="parital_payment_form" class="form needs-validation">
                                @csrf
                                    <input type="hidden" value="{{ $invn->user_id }}" name="uploaded_by" />
                                    <input type="hidden" value="{{ $invoice->id }}" name="invoice_id" />

                                    <div class="fallback">
                                        <label>{{trans('form.invoice.payment.amount_paid')}}</label>
                                        <input type="text" name="parital_amount" value="{{ old('parital_amount') }}" class="form-control @error('parital_amount') is-invalid @enderror" autofocus id="parital_amount" placeholder="{{trans('form.invoice.payment.amount_paid')}}" required>
                                        <div class="invalid-feedback">
                                            {{ trans('form.contact.field.validation.first_name') }}
                                        </div>
                                        @error('parital_amount')
                                        <div class="validation-errors">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                            </div><br/>
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label>{{trans('form.email_tem.ticket_create.created_on')}}<br>{{!is_null($invoice)?\Carbon\Carbon::parse($invoice->created_at)->format('d/m/y'):''}}</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                            @php
                                                $amount = !is_null($invoice)?$invoice->amount:0;
                                                $tax = !is_null($invoice)?$invoice->tax:0;
                                                $sum = number_format($amount/((100+$tax)/100), 2);
                                            @endphp
                                        <label>{{trans('form.performa_invoice.Sum')}}<br>{{ trans('general.money_symbol') }}{{$sum}}</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label>{{trans('form.performa_invoice.Tax')}}<br>{{$tax}}%</label>
                                    </div>
                                </div>
                                @if(!is_null($invoice))
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                        <label>{{trans('form.invoice.payment.amount_paid')}}<br>{{ trans('general.money_symbol') }}{{$invoice->parital_amount}}</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                        <label>{{trans('form.performa_invoice.Total')}}<br>{{ trans('general.money_symbol') }}{{($amount - $invoice->parital_amount)}}</label>
                                    </div>
                                </div>
                                @else
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                        <label>{{trans('form.performa_invoice.Total')}}<br>{{ trans('general.money_symbol') }}{{$amount}}</label>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="received_date">{{trans('form.performa_invoice.received_date')}} <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control " name="received_date" required id="received_date" placeholder="{{ trans('form.performa_invoice.received_date') }}" value="{{!is_null($invoice)?$invoice->received_date:''}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="paid_date">{{trans('form.performa_invoice.paid_date')}} <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control " name="paid_date" required id="paid_date" placeholder="{{ trans('form.performa_invoice.paid_date') }}" value="{{!is_null($invoice)?$invoice->paid_date:''}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="paymentForm">{{ trans('form.client_approval.payment_mode') }}</label>
                                    <select class="form-control" name="payment_mode_id" id="payment_mode_id">
                                        @php 
                                        $payment_modes = \App\PaymentMode::orderBy('mode_name')->get();
                                        @endphp
                                        @foreach($payment_modes as $key => $mode)
                                            <option
                                                value="{{ $mode->id }}" {{ !is_null($invoice)?($invoice->payment_mode_id == $mode->id ? 'selected' : ''):'' }}>{{ App::isLocale('hr')?$mode->hr_mode_name:$mode->mode_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="bank_details">{{trans('form.performa_invoice.bank_details_cheque_number')}} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control " name="bank_details" required id="bank_details" placeholder="{{ trans('form.performa_invoice.bank_details_cheque_number') }}" value="{{!is_null($invoice)?$invoice->bank_details:''}}">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="userinquiry">{{ trans('form.investigator_investigation_status.note_by_sm') }} <span class="text-danger">*</span></label>
                                    <textarea 
                                    type="tex" placeholder="{{ trans('form.investigator_investigation_status.note_by_sm') }}"
                                    class="form-control" 
                                    name="admin_notes"
                                    value=""
                                    required
                                    >{{!is_null($invoice)?$invoice->admin_notes:''}}</textarea>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="form-group text-right">
                                    <button type="button" data-dismiss="modal" aria-hidden="true" class="closmod btn btn-secondary w-md waves-effect waves-light">{{ trans('general.cancel') }}</button>
                                    <button type="submit" class="btn btn-primary w-md waves-effect waves-light addbtn">{{ trans('general.submit') }}</button>
                                </div>
                            </div>          
                            </form>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
            </div>             
        </div>
    </div>
</div>
<!-- end col -->