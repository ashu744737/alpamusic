<div class="col-12">
    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-12">                 
                    <div class="invoice-title">
                        <h3 class="mt-0">
                            <img src="{{ URL::asset('/images/logo-dark.png')}}" alt="logo" height="24"/>
                            @if($invoice->status == 'paid') <span style="font-size: 10px;float: right;" class="badge dt-badge badge-success"> {{ trans('form.timeline_status.'.ucwords($invoice->status))}}</span> @else <span  style="font-size: 10px;float: right;" class="badge dt-badge badge-warning"> {{ trans('form.timeline_status.'.ucwords($invoice->status))}}</span> @endif</strong></h4>
                        </h3>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <address>
                                <strong>{{ trans('general.paid_to') }}</strong> {{$invoice->investigator->user->name ?? '-' }}<br>
                                {{$invoice->investigator->user->userAddresses[0]->address2 ?? '-' }}<br>
                                {{ $invoice->investigator->user->userAddresses[0]->city.',' ?? '-' }}{{ $invoice->investigator->user->userAddresses[0]->state.',' ?? '-' }}<br>
                                {{ App::isLocale('hr')? $invoice->investigator->user->userAddresses[0]->country->hr_name:$invoice->investigator->user->userAddresses[0]->country->en_name }}{{ '-'.$invoice->investigator->user->userAddresses[0]->zipcode ?? '-' }}
                            </address>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mt-4">
                            <address>
                                {{--<b>{{ trans('form.investigationinvoice.order_date') }}</b> {{ date('M d, Y', strtotime($invoice->investigation->created_at)) ?? '-' }}<br>--}}
                                <b>{{ trans('form.investigationinvoice.work_order_number') }}</b>  {{$invoice->investigation->work_order_number}}<br>
                                <b>{{ trans('form.investigationinvoice.required_type_inquiry') }}</b> {{$invoice->investigation->product->name}}<br>
                                <b>{{ trans('form.investigationinvoice.ext_claim_number') }}</b> {{$invoice->investigation->ex_file_claim_no}}<br>
                                <b>{{ trans('form.investigationinvoice.claim_number') }}</b> {{$invoice->investigation->claim_number}}<br>
                            </address>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div>
                        <div class="p-2">
                            <h3 class="font-size-16"><strong>{{ trans('form.investigationinvoice.item_summary') }}</strong></h3>
                        </div>
                        <div class="">
                            {{--@foreach($invoice->investigation as $invn)--}}
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <td><strong>{{ trans('form.registration.investigation.family') }}</strong></td>
                                            <td><strong>{{ trans('form.registration.investigation.firstname') }}</strong></td>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        @php
                                            $docCost = \App\InvestigatorInvestigations::where('investigator_id', $invoice->investigator_id)->where('investigation_id', $invoice->investigation_id)->first();
                                        @endphp
                                        @if(count($invoice->investigation->subjects)>0)
                                        
                                        @php $subtot=0;@endphp
                                        @php $doccost=0;@endphp
                                        @foreach($invoice->investigation->subjects as $invoiceitem)
                                        <tr>
                                            <td>{{$invoiceitem->family_name}}</td>
                                            <td>{{$invoiceitem->first_name}}</td>
                                        </tr>
                                        @php $subtot+=$invoiceitem->req_inv_cost;@endphp
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            @if($invoice->status != 'paid')
                                                @if(!is_null($docCost) && $docCost->inv_cost)
                                                <td class="text-right"><strong>{{trans('form.performa_invoice.Total')}}</strong> {{ trans('general.money_symbol') }}{{($invoice->amount==$docCost->inv_cost?$docCost->inv_cost:$invoice->amount)}}</td>
                                                @else
                                                <td class="text-right"><strong>{{trans('form.performa_invoice.Total')}}</strong> {{ trans('general.money_symbol') }}{{$invoice->amount}}</td>
                                                @endif
                                            @else
                                                <td class="text-right"><strong>{{trans('form.performa_invoice.Total')}}</strong> {{ trans('general.money_symbol') }}{{$docCost->inv_cost}}</td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <table class="table">
                                        @php $includeVat = $notIncludeVat = 0; @endphp
                                        @foreach($invoice->investigation->documents as $doc)
                                            @if($doc->documenttype->include_vat && $doc->documenttype->name != "Case Report")
                                                @php $includeVat++; @endphp
                                            @else
                                                @if($doc->documenttype->name != "Case Report")
                                                    @php $notIncludeVat++; @endphp
                                                @endif
                                            @endif
                                        @endforeach

                                        @if(!empty($invoice->investigation->documents) && count($invoice->investigation->documents) && $includeVat)
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
                                                <td><strong>{{trans('form.documenttypes.price')}}</strong></td>
                                            </tr>
                                        </thead>
                                        @foreach($invoice->investigation->documents as $key => $documents)
                                        @if($documents->documenttype->include_vat && $documents->uploaded_by == \App\Helpers\AppHelper::getUserIdFromInvestigatorId($docCost->investigator_id) && $documents->investigation_id == $docCost->investigation_id)
                                        <tr>
                                            <td>{{$documents->doc_name}}</td>
                                            @if(config('app.locale') == 'hr')
                                            <td>{{$documents->documenttype->hr_name}}</td>
                                            @else
                                            <td>{{$documents->documenttype->name}}</td>
                                            @endif
                                            <td class="text-right {{ $documents->documenttype->include_vat?'doc-cost':'' }}">{{ trans('general.money_symbol') }}{{$documents->price}}</td>
                                            @php $doccost+=$documents->price; @endphp
                                        </tr>
                                        @endif
                                        @endforeach
                                        @endif
                                        @endif

                                        @if($notIncludeVat)
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
                                        @php
;
                                        @endphp

                                        @foreach($invoice->investigation->documents as $key => $documents)

                                        @if($documents->documenttype->include_vat == 0 && $documents->uploaded_by == \App\Helpers\AppHelper::getUserIdFromInvestigatorId($docCost->investigator_id) && $documents->investigation_id == $docCost->investigation_id && $documents->investigation_id == $docCost->investigation_id && $documents->price)
                                            <tr>
                                                <td>{{$documents->doc_name}} </td>
                                                @if(config('app.locale') == 'hr')
                                                <td>{{$documents->documenttype->hr_name}}</td>
                                                @else
                                                <td>{{$documents->documenttype->name}}</td>
                                                @endif
                                                <td class="text-right {{ $documents->documenttype->include_vat?'doc-cost':'' }} ">{{ trans('general.money_symbol') }}{{$documents->price}}</td>
                                            </tr>
                                            {{--@php $subtot+=$documents->price;@endphp--}}
                                            @php $doccost+=$documents->price; @endphp
                                            @endif
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            {{--@endforeach
                            @if($invoice->payment_status == 'parital' && !is_null($invoice->parital_amount))
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                         <tr>
                                            <td class="no-line"></td>
                                            <td class="no-line text-right">
                                                <strong>{{ trans('form.invoice.payment.amount_paid') }}</strong></td>
                                            <td class="no-line text-right">
                                                <h4 class="m-0">{{ trans('general.money_symbol') }}{{$invoice->parital_amount}}</h4>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @endif--}}
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line text-right">
                                                <b>{{trans('form.registration.investigation.doc_cost')}} 
                                                @php
                                                $docCost = \App\InvestigatorInvestigations::where('investigator_id', $invoice->investigator_id)->where('investigation_id', $invoice->investigation_id)->first();
                                                @endphp
                                                
                                                @if($docCost && !is_null($docCost->doc_cost))
                                                {{trans('general.money_symbol')}}{{$docCost->doc_cost}}
                                                @else
                                                {{trans('general.money_symbol')}}{{$doccost}}
                                                @endif
                                                </b>
                                            </td>
                                        </tr>
                                         <tr>
                                            <td class="no-line"></td>
                                            <td class="no-line text-right"></td>
                                            <td class="no-line text-right">
                                                @if($invoice->payment_status == 'discount')
                                                <h4 class="m-0">{{ trans('form.investigationinvoice.grand_total') }} {{ trans('general.money_symbol') }}{{($invoice->amount - $invoice->discount_amount)}}</h4>
                                                {{trans('general.discount_of')}} {{ trans('general.money_symbol') }}{{$invoice->discount_amount}}
                                                @else
                                                    @if($invoice->payment_status == 'parital' && !is_null($invoice->parital_amount))
                                                    <h4 class="m-0">{{ trans('form.investigationinvoice.grand_total') }} {{ trans('general.money_symbol') }}{{(($invoice->amount - $invoice->parital_amount))}}</h4>
                                                    @else
                                                        @if($invoice->status != 'paid' && (isSM() || isAdmin()))
                                                        <h4 class="m-0">{{ trans('form.investigationinvoice.grand_total') }} {{ trans('general.money_symbol') }} 
                                                            {{($invoice->amount + $doccost) ?? '0' }}
                                                            <input type="hidden" id="amount" class="amount" name="amount" value="{{($invoice->amount + $doccost) ?? '0' }}" step="0.01" data-id="{{ $invoice->id }}" required style="font-weight: initial;height: 25px;width: 130px;" onchange="calAmt();"></h4>
                                                        @else
                                                            <h4 class="m-0">{{ trans('form.investigationinvoice.grand_total') }} {{ trans('general.money_symbol') }}{{($invoice->amount)}}</h4>
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @if(!is_null($invoice->client_payment_notes))
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                         <tr>
                                            <td class="no-line">{{ trans('form.registration.investigation.payment_note') }}</td>
                                            <td>{{ $invoice->client_payment_notes }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @endif
                            @if(count($invoice->invoicedocument) > 0)
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label><b>{{trans('form.email_tem.ticket_create.created_on')}}</b> {{!is_null($invoice)?\Carbon\Carbon::parse($invoice->created_at)->format('d/m/y'):''}}</label>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="received_date"><b>{{trans('form.performa_invoice.received_date')}}:</b> {{!is_null($invoice)? \Carbon\Carbon::parse($invoice->received_date)->format('d/m/y'):''}}</label>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="paid_date"><b>{{trans('form.performa_invoice.paid_date')}}:</b> {{!is_null($invoice)?\Carbon\Carbon::parse($invoice->paid_date)->format('d/m/y'):''}}</label>
                                </div>
                                <div class="form-group col-md-3">
                                    @php
                                    if(!is_null($invoice)){
                                        $paymentMode = \App\PaymentMode::where('id', $invoice->payment_mode_id)->first();
                                    } else {
                                        $paymentMode = '';
                                    }
                                    @endphp
                                    <label for="paymentForm"><b>{{ trans('form.client_approval.payment_mode') }}:</b> {{!is_null($paymentMode)?(App::isLocale('hr')?$paymentMode->hr_mode_name:$paymentMode->mode_name):''}}</label>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="bank_details"><b>{{trans('form.performa_invoice.bank_details_cheque_number')}}:</b> {{!is_null($invoice)?$invoice->bank_details:''}}</label>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="userinquiry"><b>{{ trans('form.investigator_investigation_status.note_by_sm') }}:</b> {{!is_null($invoice)?$invoice->admin_notes:''}}</label>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 resp-order mb-3 d-print-none">
                                <div class="p-2">
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
                                            @foreach($invoice->invoicedocument as $document)
                                            @php
                                            $viewable = (isAdmin() || isSM() || isAccountant() || (isInvestigator()));
                                            @endphp
                                            @if($viewable)
                                            <tr>
                                            @php $isimage=0;
                                            $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp'];
                                            if(in_array($document->file_extension, $imageExtensions)){
                                                $isimage=1;
                                            }
                                            $imgurl='/investigator-invoice-pay-docs/'.$document->file_name;
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
                            <div class="d-print-none">
                                <div class="float-right">
                                    <a href="javascript:window.print()" id="clickbind" class="btn btn-success waves-effect waves-light mr-2"><i class="fa fa-print"></i></a>
                                    @if($invoice->status != 'paid' && (isAdmin() || isSM() || isAccountant()))
                                        <a href="javascript:void(0)" id="client_paid" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#pay_model">{{ trans('general.mark_paid') }}</a>
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
                            <form method="POST" class="" id="investigatorFileuploadform"
                                action="{{ route('investigator-invoice-pay') }}"
                                enctype="multipart/form-data" novalidate="novalidate"> 
                                @csrf
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                
                                    <input type="hidden" value="{{ Auth::id() }}" name="uploaded_by" />
                                    <input type="hidden" value="{{ $invoice->investigation->id }}" name="investigation_id" />
                                    <input type="hidden" value="{{ $invoice->id }}" name="invoice_id" />
                                    <input type="hidden" value="{{ $invoice->investigator->user->id }}" name="user_id" />
                                    @if($invoice->status != 'paid' && (isSM() || isAdmin()))
                                        <input type="hidden" name="amount" id="investigatorAmt" />
                                    @else
                                        <input type="hidden" value="{{$invoice->amount}}" name="amount" />
                                    @endif
                    
                                    <div class="fallback">
                                        <!-- <input name="file" type="files" multiple="multiple" class="form-control" /> -->
                                    </div>
                                    <div id="investigatorFileupload" class="dropzone  dropzone-area">
                                        <div class="dz-message">
                                            <h4>{{ trans('form.registration.investigation.document_dropfile') }}</h4>
                                        </div>
                                    </div>
                               
                                </div><br/>
                                <div class="row">
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                        <div class="form-group">
                                            <label>{{trans('form.email_tem.ticket_create.created_on')}}<br>{{!is_null($invoice)?\Carbon\Carbon::parse($invoice->created_at)->format('d/m/y'):''}}</label>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                        <div class="form-group">
                                            @if($invoice->status != 'paid' && (isSM() || isAdmin()))
                                            <label id="amountSum"></label>
                                            @else
                                            <label>{{trans('form.performa_invoice.Sum')}}<br>{{ trans('general.money_symbol') }}{{$invoice->amount}}</label>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                        <div class="form-group">
                                        @if($invoice->status != 'paid' && (isSM() || isAdmin()))
                                        <label id="amountToPay"></label>
                                        @else
                                        <label>{{trans('form.performa_invoice.Total')}}<br>{{ trans('general.money_symbol') }}{{$invoice->amount}}</label>
                                        @endif
                                        </div>
                                    </div>
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
                                    <div class="form-group text-center">
                                        <button type="submit" id="uploadInvestigatorFile" class="uploadInvestigatorFile btn btn-primary w-md waves-effect waves-light addbtn">{{ trans('form.invoice.payment.payment_send') }}</button>
                                        <button type="button" id="closmod2" data-dismiss="modal" aria-hidden="true" class="closmod btn btn-secondary w-md waves-effect waves-light">{{ trans('general.cancel') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
            </div>

            {{--<div id="discount_payment_model" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0">{{ trans('form.invoice.payment.discount_form') }}</h5>
                            <button id="closmod" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <form action="{{ route('invoice.discount-payment') }}" onSubmit="return dosubmit();" method="POST" id="discount_payment_form" class="form needs-validation">
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
                <div class="modal-dialog modal-dialog-centered">
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
            </div>--}}             
        </div>
    </div>
</div>
<!-- end col -->