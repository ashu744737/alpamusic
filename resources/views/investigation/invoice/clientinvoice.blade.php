<div class="col-12">
    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-12">                 
                    <div class="invoice-title">
                            @if($invn->status == 'Closed' && $invn->clientinvoice[0]->status == 'paid') 
                                <span class="badge dt-badge badge-success" style="float: right;"> {{ trans('form.timeline_status.'.ucwords($invn->clientinvoice[0]->status))}}</span> 
                            @endif
                        <h4 class="mt-0">
                            <img src="{{ URL::asset('/images/logo-dark.png')}}" alt="logo" height="24"/>
                        </h4>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h4 class="mt-0">{{trans('form.app_name')}} </h4>
                            <address>
                                <strong>
                                <div>{{-- $invn->user->getuserFirstAddresses->address1 ? $invn->user->getuserFirstAddresses->address1 : trans('form.registration.investigation.no_addresses') --}}{{trans('form.performa_invoice.address')}}
                                    <img src="{{ URL::asset('/images/logo-dark.png')}}" alt="logo" height="24"/>
                                </div>
                                <div>{{trans('form.performa_invoice.tel_fax')}}</div>
                                <div>{{trans('form.performa_invoice.cell')}}</div><br>
                                @if(config('app.locale') == 'hr')
                                <div style="text-align: left;">{{trans('form.performa_invoice.date')}}{{\Carbon\Carbon::parse($invn->clientinvoice[0]['created_at'])->format('d/m/y')}}</div>

                                <div style="text-align: left;">
                                    <h5>{{trans('form.performa_invoice.card_no')}} {{$invn->work_order_number}}</h5>
                                </div>

                                @else
                                <div>{{trans('form.performa_invoice.date')}}{{\Carbon\Carbon::parse($invn->clientinvoice[0]['created_at'])->format('d/m/y')}}</div>
                                <div style="text-align: left;">
                                    <h5>{{trans('form.performa_invoice.card_no')}} {{$invn->work_order_number}}</h5>
                                </div>
                                @endif
                                <div>{{trans('form.performa_invoice.company_entity')}}</div>
                                <div>{{trans('form.performa_invoice.copy')}}</div><br/>
                                <!-- <div>{{ trans('form.registration.investigation.req_type_inquiry') }}: {{$invn->work_order_number}}</div> -->
                                <div>{{ trans('form.performa_invoice.to') }} {{ $invn->client_type->name }}</div>
                                
                                <div>{{ trans('form.investigationinvoice.ext_claim_number') }} {{$invn->ex_file_claim_no}}</div>
                                <br>
                                <div>{{--trans('form.performa_invoice.to')}} {{$invn->client_type->name}} {{trans('form.performa_invoice.id')}} {{$invn->subjects[0]['id_number']}}<br>
                                {{$invn->client_type->userAddresses[0]->address2 ?? ''}} , {{$invn->client_type->userAddresses[0]->city}} , {{ App::isLocale('hr')? $invn->client_type->userAddresses[0]->country->hr_name:$invn->client_type->userAddresses[0]->country->en_name }}{{ '-'.$invn->client_type->userAddresses[0]->zipcode ?? '-' --}} 
                                {{ $invn->user->getuserFirstAddresses->address1 ? $invn->user->getuserFirstAddresses->address1 : trans('form.registration.investigation.no_addresses') }}
                                </div>
                            </address>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div>
                        <center class="p-2">
                            <h4 class="p-0 m-0">{{trans('form.performa_invoice.performa_invoice_txt')}} <b>{{$invn->clientinvoice[0]['invoice_no']}}</b></h4>
                        </center>
                        <center class="p-2">
                            <p class="m-0 p-0">{{trans('form.performa_invoice.doc_not_tax_invoice')}}</p>
                        </center>
                        
                        <div>
                            <h5>{{trans('form.performa_invoice.type')}} {{$invn->product->name}}</h5>
                        </div>
                        
                        <div>
                            <h5>{{trans('form.performa_invoice.subject')}} @foreach($invn->subjects as $key => $subType) {{$key+1}}. {{$subType['first_name']}} {{$subType['family_name']}} @endforeach </h5>
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
                                        @if(count($invn->clientinvoice)>0)
                                        @php $subtot=0;@endphp
                                        
                                        @foreach($invn->clientinvoice[0]->invoiceitems as $invoiceitem)
                                        <tr>
                                        <td>{{!is_null($invoiceitem->subject)?($invoiceitem->subject->first_name):''}} {{!is_null($invoiceitem->subject)?($invoiceitem->subject->family_name):''}} - @if(config('app.locale') == 'hr' && !is_null($invoiceitem->subject))
                                        {{ !is_null(\App\SubjectTypes::where('name', $invoiceitem->subject->sub_type)->first())?\App\SubjectTypes::where('name', $invoiceitem->subject->sub_type)->first()->hr_name:(!is_null($invoiceitem->subject->sub_type)?$invoiceitem->subject->sub_type:'') }}
                                        @else
                                        @if(!is_null($invoiceitem->subject))
                                        {{ !is_null(\App\SubjectTypes::where('name', $invoiceitem->subject->sub_type)->first())?\App\SubjectTypes::where('name', $invoiceitem->subject->sub_type)->first()->name:(!is_null($invoiceitem->subject->sub_type)?$invoiceitem->subject->sub_type:'') }}
                                        @endif
                                        @endif</td>
                                        <td>1</td>
                                        <td class="text-right">{{ trans('general.money_symbol') }}{{$invoiceitem->cost ?? '0' }}</td>
                                        <td class="text-right">{{ trans('general.money_symbol') }}{{$invoiceitem->cost ?? '0' }}</td>
                                        </tr>
                                        @php $subtot+=$invoiceitem->cost;@endphp
                                        @endforeach
                                        @php $includeVat = $notIncludeVat = 0; @endphp
                                        @if(!empty($invn->documents) && count($invn->documents))
                                        <tr>
                                            <td colspan="4"></td>
                                        </tr>
                                        @foreach($invn->documents as $doc)
                                            @if($doc->documenttype->include_vat && $doc->documenttype->name != "Case Report")
                                                @php $includeVat++; @endphp
                                            @else
                                                @if($doc->documenttype->name != "Case Report")
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
                                        @foreach($invn->documents as $key => $documents)
                                            @if($documents->documenttype->include_vat && $documents->documenttype->name != "Case Report")
                                            <tr>
                                                <td>{{$docKey}}</td>
                                                <td>{{$documents->doc_name}} </td>
                                                @if(config('app.locale') == 'hr')
                                                <td>{{!is_null($documents->documenttype)?$documents->documenttype->hr_name:''}}</td>
                                                @else
                                                <td>{{!is_null($documents->documenttype)?$documents->documenttype->name:''}}</td>
                                                @endif
                                                <td class="text-right {{ $documents->documenttype->include_vat?'doc-cost':'' }}">{{ trans('general.money_symbol') }}{{!is_null($documents->documenttype)?$documents->documenttype->price:''}}</td>
                                            </tr>
                                            @php $subtot+=$documents->documenttype->price; $docKey++; @endphp
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
                                            <td class="no-line text-right"><span class="tax">{{ trans('form.performa_invoice.Tax') }} {{ $invn->clientinvoice[0]->tax }}</span>%</td>
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
                                        @foreach($invn->documents as $key => $documents)
                                            @if($documents->documenttype->include_vat == 0 && $documents->documenttype->name != "Case Report" && $documents->price != 0)
                                            <tr>
                                                <td>{{($key+1)}}</td>
                                                <td>{{$documents->doc_name}} </td>
                                                @if(config('app.locale') == 'hr')
                                                <td>{{$documents->documenttype->hr_name}}</td>
                                                @else
                                                <td>{{$documents->documenttype->name}}</td>
                                                @endif
                                                <td class="text-right {{ $documents->documenttype->include_vat?'doc-cost':'' }} ">{{ trans('general.money_symbol') }}{{$documents->price}}</td>
                                            </tr>
                                            @php $subtot+=$documents->price;@endphp
                                            @endif
                                        @endforeach
                                        @endif
                                        <tr>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line text-right">
                                            @if(isClient())
                                                @if(!is_null($invn->clientinvoice[0]->invoice_id) && $invn->clientinvoice[0]->invoice->payment_status == 'discount')
                                                    <h4 class="m-0">{{ trans('form.performa_invoice.Total') }} {{ trans('general.money_symbol') }}{{($invn->clientinvoice[0]->amount - $invn->clientinvoice[0]->invoice->discount_amount)}}</h4>
                                                    {{trans('general.discount_of')}} {{ trans('general.money_symbol') }}{{$invn->clientinvoice[0]->invoice->discount_amount}}
                                                    @else
                                                        @if(!is_null($invn->clientinvoice[0]->invoice_id) && $invn->clientinvoice[0]->invoice->payment_status == 'parital' && !is_null($invn->clientinvoice[0]->invoice->parital_amount))
                                                        <h4 class="m-0">{{ trans('general.money_symbol') }}{{($invn->clientinvoice[0]->amount - $invn->clientinvoice[0]->invoice->parital_amount)}}</h4>
                                                        {{trans('form.invoice.payment.amount_paid')}} {{ trans('general.money_symbol') }}{{$invn->clientinvoice[0]->invoice->parital_amount}}
                                                        @else
                                                        <h4 class="m-0">{{ trans('form.performa_invoice.Total') }} {{ trans('general.money_symbol') }}{{!is_null($invn->clientinvoice[0]->invoice_id)?$invn->clientinvoice[0]->invoice->amount:$invn->clientinvoice[0]->amount}}</h4>
                                                        @endif
                                                    @endif
                                            @else
                                                @if(!is_null($invn->clientinvoice[0]->invoice_id) && $invn->clientinvoice[0]->invoice->payment_status == 'discount')
                                                <h4 class="m-0">{{ trans('form.performa_invoice.Total') }} {{ trans('general.money_symbol') }}{{($invn->clientinvoice[0]->invoice->amount - $invn->clientinvoice[0]->invoice->discount_amount)}}</h4>
                                                {{trans('general.discount_of')}} {{ trans('general.money_symbol') }}{{$invn->clientinvoice[0]->invoice->discount_amount}}
                                                @else
                                                    @if(!is_null($invn->clientinvoice[0]->invoice_id) && $invn->clientinvoice[0]->invoice->payment_status == 'parital' && !is_null($invn->clientinvoice[0]->invoice->parital_amount))
                                                    <h4 class="m-0">{{ trans('general.money_symbol') }}{{($invn->clientinvoice[0]->invoice->amount - $invn->clientinvoice[0]->invoice->parital_amount)}}</h4>
                                                    {{trans('form.invoice.payment.amount_paid')}} {{ trans('general.money_symbol') }}{{$invn->clientinvoice[0]->invoice->parital_amount}}
                                                    @else
                                                    <h4 class="m-0">{{ trans('form.performa_invoice.Total') }} {{ trans('general.money_symbol') }}{{!is_null($invn->clientinvoice[0]->invoice_id)?$invn->clientinvoice[0]->invoice->amount:$invn->clientinvoice[0]->amount}}</h4>
                                                    @endif
                                                @endif
                                            @endif
                                            </td>
                                        </tr>
                                       
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            @php
                            $performa = $invn->clientinvoice[0];
                            @endphp
                            @if(!is_null($performa) && !is_null($performa->invoice))
                            <div class="row">
                                @if(!is_null($performa->invoice->created_at))
                                <div class="form-group col-md-3">
                                    <label><b>{{trans('form.email_tem.ticket_create.created_on')}}</b> {{!is_null($performa->invoice)?\Carbon\Carbon::parse($performa->invoice->created_at)->format('d/m/y'):''}}</label>
                                </div>
                                @endif
                                @if(!is_null($performa->invoice->received_date))
                                <div class="form-group col-md-3">
                                    <label for="received_date"><b>{{trans('form.performa_invoice.received_date')}}:</b> {{!is_null($performa->invoice)? \Carbon\Carbon::parse($performa->invoice->received_date)->format('d/m/y'):''}}</label>
                                </div>
                                @endif
                                @if(!is_null($performa->invoice->paid_date))
                                <div class="form-group col-md-3">
                                    <label for="paid_date"><b>{{trans('form.performa_invoice.paid_date')}}:</b> {{!is_null($performa->invoice)?\Carbon\Carbon::parse($performa->invoice->paid_date)->format('d/m/y'):''}}</label>
                                </div>
                                @endif
                                @if(!is_null($performa->invoice->payment_mode_id))
                                <div class="form-group col-md-3">
                                    @php
                                    if(!is_null($performa->invoice)){
                                        $paymentMode = \App\PaymentMode::where('id', $performa->invoice->payment_mode_id)->first();
                                    } else {
                                        $paymentMode = '';
                                    }
                                    @endphp
                                    <label for="paymentForm"><b>{{ trans('form.client_approval.payment_mode') }}:</b> {{!is_null($paymentMode)?(App::isLocale('hr')?$paymentMode->hr_mode_name:$paymentMode->mode_name):''}}</label>
                                </div>
                                @endif
                                @if(!is_null($performa->invoice->bank_details))
                                <div class="form-group col-md-6">
                                    <label for="bank_details"><b>{{trans('form.performa_invoice.bank_details_cheque_number')}}:</b> {{!is_null($performa->invoice)?$performa->invoice->bank_details:''}}</label>
                                </div>
                                @endif
                                @if(!is_null($performa->invoice->admin_notes))
                                <div class="form-group col-md-6">
                                    <label for="userinquiry"><b>{{ trans('form.investigator_investigation_status.note_by_sm') }}:</b> {{!is_null($performa->invoice)?$performa->invoice->admin_notes:''}}</label>
                                </div>
                                @endif
                            </div>
                            @endif
                            <!--  -->
                            @if(count($performa->newInvoicedocs) > 0)
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
                                            <th>{{ trans('form.invoice.payment.payment_note') }}</th>
                                            <th>{{ trans('general.action') }}</th>
                                            </thead>
                                            <tbody>
                                            
                                            @php $indx = 1; @endphp
                                            @foreach($performa->newInvoicedocs as $document)
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
                                            <td>{{ $document->payment_note }}</td>
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

                                        <!--  -->
                                        <br>
                                        <div class="table table-responsive">
                                            
                                        <!-- <table class="table table-bordered">
                                            <tbody>
                                                <td width="10%">{{ trans('form.invoice.payment.payment_note') }}</td>
                                                <td width="80%">
                                                    {{ isset($performa->invoice->client_payment_notes) ? $performa->invoice->client_payment_notes : '' }}
                                                </td>
                                            </tbody>
                                        </table> -->
                                        </div>
                                        <!--  -->
                                    </div>
                                </div>
                            </div>
                            @endif
                            <!--  -->
                            <div class="pt-5">
                                <h4 class="mt-0">
                                    <img src="{{ URL::asset('/images/logo-dark.png')}}" alt="logo" height="24"/>
                                </h4>
                                <p>{{trans('form.performa_invoice.invoice_dec')}}</p>
                                <p>{{trans('form.performa_invoice.invoice_dec2')}}</p>
                                <p>{{trans('form.performa_invoice.our_bank_detail')}}</p>
                                <p>{{trans('form.performa_invoice.bank_line_1')}}</p>
                                <p><b>{{trans('form.performa_invoice.bank_line_2')}}</b></p>
                                <br>
                                @if(App::isLocale('hr'))
                                <p style="text-align: left;"><b>{{trans('form.performa_invoice.Respectfully')}}</b></p>
                                <p style="text-align: left;"><b>{{trans('general.app_name')}}</b></p>
                                @else
                                <p><b>{{trans('form.performa_invoice.Respectfully')}}</b></p>
                                <p><b>{{trans('general.app_name')}}</b></p>
                                @endif
                            </div>
                            <div class="d-print-none">
                                <div class="float-right" style="display: inline-flex;">
                                    <a href="javascript:window.print()" id="clickbind" class="btn btn-success waves-effect waves-light m-2"><i class="fa fa-print"></i></a>
                                    @if ((isSM() || isAdmin() || isAccountant()))
                                        @php
                                            if($invn->clientinvoice[0]->invoice_id ==''){
                                                $type = 'create';
                                            }
                                            else{
                                                $type = 'update';
                                            }
                                        @endphp
                                        @if($invn->clientinvoice[0]->status == 'requested' || $invn->clientinvoice[0]->status == 'pending')
                                            @if(is_null($invn->clientinvoice[0]->invoice_id))
                                                <a href="javascript:void(0)"  class="btn btn-primary waves-effect waves-light m-2" data-toggle="modal" data-target="#admin_partial_payment">{{ trans('general.partial_payment') }}</a>
                                                <a href="javascript:void(0)"  class="btn btn-primary waves-effect waves-light m-2" data-toggle="modal" data-target="#admin_discount_payment_model">{{ trans('general.discount_payment') }}</a>
                                                <a href="javascript:void(0)"  class="btn btn-primary waves-effect waves-light m-2" data-toggle="modal" data-target="#admin_full_payment_model">{{ trans('general.mark_final_paid') }}</a>
                                            @else
                                                <a href="javascript:void(0)"  class="btn btn-primary waves-effect waves-light m-2" data-toggle="modal" data-target="#partial_payment">{{ trans('general.partial_payment') }}</a>
                                                <a href="javascript:void(0)"  class="btn btn-primary waves-effect waves-light m-2" data-toggle="modal" data-target="#discount_payment_model">{{ trans('general.discount_payment') }}</a>
                                                <a href="javascript:void(0)" class="btn btn-primary waves-effect waves-light m-2" id="mark_final_paid1" data-toggle="modal" data-target="#admin_mark_as_final_payment" data-id="{{$invn->clientinvoice[0]->invoice_id}}" data-investigation="{{$invn->id}}" data-type="{{$type}}" data-performaId="{{$invn->clientinvoice[0]->id}}">{{ trans('general.mark_final_paid') }}</a>
                                            @endif
                                        @endif
                                    @else
                                        @if($performaInvoiceStatus != 'paid' && isClient())
                                            <a href="javascript:void(0)" id="client_paid"  class="btn btn-primary waves-effect waves-light m-2" data-toggle="modal" data-target="#pay_model">{{ trans('general.mark_paid') }}</a>
                                        @endif
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
                                    @if(isset($invn->clientinvoice[0]->invoice_id) && $invn->clientinvoice[0]->invoice_id)
                                        <input type="hidden" name="invoice_id" value="{{ $invn->clientinvoice[0]->invoice_id }}">
                                    @endif
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

            <div id="discount_payment_model" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
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
                                    <input type="hidden" value="{{ $invn->id }}" name="investigation_id" />
                                    <input type="hidden" value="{{ !is_null($invn->clientinvoice[0]->invoice_id)?$invn->clientinvoice[0]->invoice->id:'' }}" name="invoice_id" />
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
                                        <label>{{trans('form.email_tem.ticket_create.created_on')}}<br>{{!is_null($invn->clientinvoice[0])?\Carbon\Carbon::parse($invn->clientinvoice[0]->created_at)->format('d/m/y'):''}}</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                            @php
                                                $amount = !is_null($invn->clientinvoice[0])?$invn->clientinvoice[0]->amount:0;
                                                $tax = !is_null($invn->clientinvoice[0])?$invn->clientinvoice[0]->tax:0;
                                                $sum = ($amount/((100+$tax)/100));
                                            @endphp
                                        <label>{{trans('form.performa_invoice.Sum')}}<br>{{ trans('general.money_symbol') }}{{$subtot}}</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label>{{trans('form.performa_invoice.Tax')}}<br>{{$tax}}%</label>
                                    </div>
                                </div>
                                @if(!is_null($invn->clientinvoice[0]->invoice))
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                        <label>{{trans('form.invoice.payment.amount_paid')}}<br>{{ trans('general.money_symbol') }}{{$invn->clientinvoice[0]->invoice->parital_amount}}</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                        <label>{{trans('form.performa_invoice.Total')}}<br>{{ trans('general.money_symbol') }}{{($amount - $invn->clientinvoice[0]->invoice->parital_amount)}}</label>
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
                            <form action="{{ route('invoice.parital-payment') }}" onSubmit="return dosubmit();" method="POST" id="parital_payment_form" class="form needs-validation">
                                @csrf
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <input type="hidden" value="{{ $invn->user_id }}" name="uploaded_by" />
                                    <input type="hidden" value="{{ $invn->id }}" name="investigation_id" />
                                    <input type="hidden" value="{{ !is_null($invn->clientinvoice[0]->invoice_id)?$invn->clientinvoice[0]->invoice->id:'' }}" name="invoice_id" />
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
                                        <label>{{trans('form.email_tem.ticket_create.created_on')}}<br>{{!is_null($invn->clientinvoice[0])?\Carbon\Carbon::parse($invn->clientinvoice[0]->created_at)->format('d/m/y'):''}}</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                            @php
                                                $amount = !is_null($invn->clientinvoice[0])?$invn->clientinvoice[0]->amount:0;
                                                $tax = !is_null($invn->clientinvoice[0])?$invn->clientinvoice[0]->tax:0;
                                                $sum = ($amount/((100+$tax)/100));
                                            @endphp
                                        <label>{{trans('form.performa_invoice.Sum')}}<br>{{ trans('general.money_symbol') }}{{$subtot}}</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label>{{trans('form.performa_invoice.Tax')}}<br>{{$tax}}%</label>
                                    </div>
                                </div>
                                @if(!is_null($invn->clientinvoice[0]->invoice))
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                        <label>{{trans('form.invoice.payment.amount_paid')}}<br>{{ trans('general.money_symbol') }}{{$invn->clientinvoice[0]->invoice->parital_amount}}</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-2">
                                    <div class="form-group">
                                        <label>{{trans('form.performa_invoice.Total')}}<br>{{ trans('general.money_symbol') }}{{($amount - $invn->clientinvoice[0]->invoice->parital_amount)}}</label>
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
                                    <input type="date" class="form-control " name="received_date" required id="received_date" placeholder="{{ trans('form.performa_invoice.received_date') }}" value="{{!is_null($invn->clientinvoice[0]->invoice)?$invn->clientinvoice[0]->invoice->received_date:''}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="paid_date">{{trans('form.performa_invoice.paid_date')}} <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control " name="paid_date" required id="paid_date" placeholder="{{ trans('form.performa_invoice.paid_date') }}" value="{{!is_null($invn->clientinvoice[0]->invoice)?$invn->clientinvoice[0]->invoice->paid_date:''}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="paymentForm">{{ trans('form.client_approval.payment_mode') }}</label>
                                    <select class="form-control" name="payment_mode_id" id="payment_mode_id">
                                        @php 
                                        $payment_modes = \App\PaymentMode::orderBy('mode_name')->get();
                                        @endphp
                                        @foreach($payment_modes as $key => $mode)
                                            <option
                                                value="{{ $mode->id }}" {{ !is_null($invn->clientinvoice[0]->invoice)?($invn->clientinvoice[0]->invoice->payment_mode_id == $mode->id ? 'selected' : ''):'' }}>{{ App::isLocale('hr')?$mode->hr_mode_name:$mode->mode_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="bank_details">{{trans('form.performa_invoice.bank_details_cheque_number')}} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control " name="bank_details" required id="bank_details" placeholder="{{ trans('form.performa_invoice.bank_details_cheque_number') }}" value="{{!is_null($invn->clientinvoice[0]->invoice)?$invn->clientinvoice[0]->invoice->bank_details:''}}">
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
                                    >{{!is_null($invn->clientinvoice[0]->invoice)?$invn->clientinvoice[0]->invoice->admin_notes:''}}</textarea>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="form-group text-right">
                                        <button type="button" data-dismiss="modal" aria-hidden="true" class="closmod btn btn-secondary w-md waves-effect waves-light">{{ trans('general.cancel') }}</button>
                                        <button type="submit" class="btn btn-primary w-md waves-effect waves-light addbtn">{{ trans('general.submit') }}</button>
                                    </div>
                                </div>
                            </div>          
                            </form>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
            </div>

            <div id="admin_partial_payment" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
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
                            <form method="POST" class="" id="adminPartialFileuploadForm"
                                action="{{ route('client-invoice-pay') }}"
                                enctype="multipart/form-data" novalidate=""> 
                                @csrf
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <input type="hidden" value="partial" name="payment_type" />
                                    <input type="hidden" value="{{ $invn->user_id }}" name="uploaded_by" />
                                    <input type="hidden" value="{{ $invn->id }}" name="investigation_id" />
                                    <input type="hidden" value="{{ !is_null($invn->clientinvoice[0]->invoice_id)?$invn->clientinvoice[0]->invoice->id:'' }}" name="invoice_id" />
                                    <div class="fallback">
                                        <!-- <input name="file" type="files" multiple="multiple" class="form-control" /> -->
                                    </div>
                                    <div class="dropzone dropzone-area" id="adminPartialFileupload">
                                        <div class="dz-message">
                                            <h4>{{ trans('form.registration.investigation.document_dropfile') }}</h4>
                                        </div>
                                    </div>
                               
                            </div><br/>
                            
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label>{{trans('form.email_tem.ticket_create.created_on')}}<br>{{!is_null($invn->clientinvoice[0])?\Carbon\Carbon::parse($invn->clientinvoice[0]->created_at)->format('d/m/y'):''}}</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                            @php
                                                $amount = !is_null($invn->clientinvoice[0])?$invn->clientinvoice[0]->amount:0;
                                                $tax = !is_null($invn->clientinvoice[0])?$invn->clientinvoice[0]->tax:0;
                                                $sum = ($amount/((100+$tax)/100));
                                            @endphp
                                        <label>{{trans('form.performa_invoice.Sum')}}<br>{{ trans('general.money_symbol') }}{{$subtot}}</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label>{{trans('form.performa_invoice.Tax')}}<br>{{$tax}}%</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label>{{trans('form.performa_invoice.Total')}}<br>{{ trans('general.money_symbol') }}{{$amount}}</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="received_date">{{trans('form.performa_invoice.received_date')}} <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control " name="received_date" required id="received_date" placeholder="{{ trans('form.performa_invoice.received_date') }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="paid_date">{{trans('form.performa_invoice.paid_date')}} <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control " name="paid_date" required id="paid_date" placeholder="{{ trans('form.performa_invoice.paid_date') }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="paymentForm">{{ trans('form.client_approval.payment_mode') }}</label>
                                    <select class="form-control" name="payment_mode_id" id="payment_mode_id">
                                        @php 
                                        $payment_modes = \App\PaymentMode::orderBy('mode_name')->get();
                                        @endphp
                                        @foreach($payment_modes as $key => $mode)
                                            <option
                                                value="{{ $mode->id }}" {{ old('payment_mode_id') == $mode->id ? 'selected' : '' }}>{{ App::isLocale('hr')?$mode->hr_mode_name:$mode->mode_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="bank_details">{{trans('form.performa_invoice.bank_details_cheque_number')}} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control " name="bank_details" required id="bank_details" placeholder="{{ trans('form.performa_invoice.bank_details_cheque_number') }}">
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
                                <div class="form-group col-md-12">
                                    <label for="userinquiry">{{ trans('form.investigator_investigation_status.note_by_sm') }} <span class="text-danger">*</span></label>
                                    <textarea 
                                    type="tex" placeholder="{{ trans('form.investigator_investigation_status.note_by_sm') }}"
                                    class="form-control" 
                                    name="admin_notes"
                                    value=""
                                    required
                                    ></textarea>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="form-group text-center">
                                        <button type="submit" id="partial_uploadfile" class="partial_uploadfile btn btn-primary w-md waves-effect waves-light addbtn">{{ trans('general.submit') }}</button>
                                        <button type="button" id="closmod2" data-dismiss="modal" aria-hidden="true" class="closmod btn btn-secondary w-md waves-effect waves-light">{{ trans('general.cancel') }}</button>
                                    </div>
                                </div>
                            </div>
                            
                            </form>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
            </div>

            @if(isset($invn->clientinvoice[0]) && !is_null($invn->clientinvoice[0]->invoice_id))
            <!-- marka as final paid final amount -->
            <div id="admin_mark_as_final_payment" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title mt-0">{{ trans('form.invoice.payment.payment_form') }}</h5>
                            <button  type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <label>{{ trans('form.invoice.payment.upload_doc_message') }}
                                    <span class="text-danger">*</span>
                                </label>
                                
                            </div>
                            <form method="POST" class="" id="adminFullPaymentfileuploadForm1"
                                action="{{ route('invoice.parital-payment') }}"
                                enctype="multipart/form-data" novalidate=""> 
                                @csrf
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <input type="hidden" value="partial" name="payment_type" />
                                    <input type="hidden" value="{{ $invn->user_id }}" name="uploaded_by" />
                                    <input type="hidden" value="{{ $invn->id }}" name="investigation_id" />
                                    <input type="hidden" value="{{ !is_null($invn->clientinvoice[0]->invoice_id)?$invn->clientinvoice[0]->invoice->id:'' }}" name="invoice_id" />
                                    <div class="fallback">
                                        <!-- <input name="file" type="files" multiple="multiple" class="form-control" /> -->
                                    </div>
                                    <div class="dropzone dropzone-area" id="adminFullPaymentfileupload">
                                        <div class="dz-message">
                                            <h4>{{ trans('form.registration.investigation.document_dropfile') }}</h4>
                                        </div>
                                    </div>
                               
                            </div><br/>
                            
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label>{{trans('form.email_tem.ticket_create.created_on')}}<br>{{!is_null($invn->clientinvoice[0])?\Carbon\Carbon::parse($invn->clientinvoice[0]->created_at)->format('d/m/y'):''}}</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                            @php
                                                $amount = !is_null($invn->clientinvoice[0])?$invn->clientinvoice[0]->amount:0;
                                                $tax = !is_null($invn->clientinvoice[0])?$invn->clientinvoice[0]->tax:0;
                                                $sum = ($amount/((100+$tax)/100));
                                            @endphp
                                        <label>{{trans('form.performa_invoice.Sum')}}<br>{{ trans('general.money_symbol') }}{{$subtot}}</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label>{{trans('form.performa_invoice.Tax')}}<br>{{$tax}}%</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label>{{trans('form.performa_invoice.Total')}}<br>{{ trans('general.money_symbol') }}{{$amount}}</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="received_date">{{trans('form.performa_invoice.received_date')}} <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control " name="received_date" required id="received_date1" placeholder="{{ trans('form.performa_invoice.received_date') }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="paid_date">{{trans('form.performa_invoice.paid_date')}} <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control " name="paid_date" required id="paid_date1" placeholder="{{ trans('form.performa_invoice.paid_date') }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="paymentForm">{{ trans('form.client_approval.payment_mode') }}</label>
                                    <select class="form-control" name="payment_mode_id" id="payment_mode_id1">
                                        @php 
                                        $payment_modes = \App\PaymentMode::orderBy('mode_name')->get();
                                        @endphp
                                        @foreach($payment_modes as $key => $mode)
                                            <option
                                                value="{{ $mode->id }}" {{ old('payment_mode_id') == $mode->id ? 'selected' : '' }}>{{ App::isLocale('hr')?$mode->hr_mode_name:$mode->mode_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="bank_details">{{trans('form.performa_invoice.bank_details_cheque_number')}} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control " name="bank_details" required id="bank_details1" placeholder="{{ trans('form.performa_invoice.bank_details_cheque_number') }}">
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

                                <input type="hidden" name="parital_amount" value="@if(isset($invn->clientinvoice[0]->invoice->amount) && isset($invn->clientinvoice[0]->invoice->parital_amount)){{ $invn->clientinvoice[0]->invoice->amount - $invn->clientinvoice[0]->invoice->parital_amount }}@endif">
                                <!-- <div class="form-group col-md-12">
                                    <label>{{trans('form.invoice.payment.amount_paid')}}</label>
                                    <input type="text" name="parital_amount" value="{{ old('parital_amount') }}" class="form-control @error('parital_amount') is-invalid @enderror" autofocus id="parital_amount1" placeholder="{{trans('form.invoice.payment.amount_paid')}}" required>
                                    <div class="invalid-feedback">
                                        {{ trans('form.contact.field.validation.first_name') }}
                                    </div>
                                    @error('parital_amount')
                                    <div class="validation-errors">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div> -->
                                <div class="form-group col-md-12">
                                    <label for="userinquiry">{{ trans('form.investigator_investigation_status.note_by_sm') }} <span class="text-danger">*</span></label>
                                    <textarea 
                                    type="tex" placeholder="{{ trans('form.investigator_investigation_status.note_by_sm') }}"
                                    class="form-control" 
                                    name="admin_notes"
                                    value=""
                                    required
                                    ></textarea>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="form-group text-center">
                                        <button type="submit" id="partial_uploadfile1" class="partial_uploadfile btn btn-primary w-md waves-effect waves-light addbtn">{{ trans('general.submit') }}</button>
                                        <button type="button"  data-dismiss="modal" aria-hidden="true" class="closmod btn btn-secondary w-md waves-effect waves-light">{{ trans('general.cancel') }}</button>
                                    </div>
                                </div>
                            </div>
                            
                            </form>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
            </div>
            @endif
            <!-- marka as final paid final amount end-->

            <div id="admin_discount_payment_model" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
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
                                <form method="POST" class="dropzone dropzone-area" id="adminDiscountFileupload"
                                action="{{ route('client-invoice-pay') }}"
                                enctype="multipart/form-data"> 
                                @csrf
                                    <input type="hidden" value="discount" name="payment_type" />
                                    <input type="hidden" value="{{ $invn->user_id }}" name="uploaded_by" />
                                    <input type="hidden" value="{{ $invn->id }}" name="investigation_id" />
                                    <input type="hidden" value="{{ !is_null($invn->clientinvoice[0]->invoice_id)?$invn->clientinvoice[0]->invoice->id:'' }}" name="invoice_id" />
                                    <div class="fallback">
                                        <input name="file" type="files" multiple="multiple" class="form-control" />
                                    </div>
                                    <div class="dz-message">
                                        <h4>{{ trans('form.registration.investigation.document_dropfile') }}</h4>
                                    </div>
                               
                            </div><br/>

                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label>{{trans('form.email_tem.ticket_create.created_on')}}<br>{{!is_null($invn->clientinvoice[0])?\Carbon\Carbon::parse($invn->clientinvoice[0]->created_at)->format('d/m/y'):''}}</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                            @php
                                                $amount = !is_null($invn->clientinvoice[0])?$invn->clientinvoice[0]->amount:0;
                                                $tax = !is_null($invn->clientinvoice[0])?$invn->clientinvoice[0]->tax:0;
                                                $sum = ($amount/((100+$tax)/100));
                                            @endphp
                                        <label>{{trans('form.performa_invoice.Sum')}}<br>{{ trans('general.money_symbol') }}{{$subtot}}</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label>{{trans('form.performa_invoice.Tax')}}<br>{{$tax}}%</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label>{{trans('form.performa_invoice.Total')}}<br>{{ trans('general.money_symbol') }}{{$amount}}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="received_date">{{trans('form.performa_invoice.received_date')}} <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control " name="received_date" required id="received_date" placeholder="{{ trans('form.performa_invoice.received_date') }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="paid_date">{{trans('form.performa_invoice.paid_date')}} <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control " name="paid_date" required id="paid_date" placeholder="{{ trans('form.performa_invoice.paid_date') }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="paymentForm">{{ trans('form.client_approval.payment_mode') }}</label>
                                    <select class="form-control" name="payment_mode_id" id="payment_mode_id">
                                        @php 
                                        $payment_modes = \App\PaymentMode::orderBy('mode_name')->get();
                                        @endphp
                                        @foreach($payment_modes as $key => $mode)
                                            <option
                                                value="{{ $mode->id }}" {{ old('payment_mode_id') == $mode->id ? 'selected' : '' }}>{{ App::isLocale('hr')?$mode->hr_mode_name:$mode->mode_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="bank_details">{{trans('form.performa_invoice.bank_details_cheque_number')}} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control " name="bank_details" required id="bank_details" placeholder="{{ trans('form.performa_invoice.bank_details_cheque_number') }}">
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
                                    <label>{{trans('form.invoice.payment.discount_amount')}} <span class="text-danger">*</span></label>
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
                                <div class="form-group col-md-12">
                                    <label for="userinquiry">{{ trans('form.investigator_investigation_status.note_by_sm') }} <span class="text-danger">*</span></label>
                                    <textarea 
                                    type="tex" placeholder="{{ trans('form.investigator_investigation_status.note_by_sm') }}"
                                    class="form-control" 
                                    name="admin_notes"
                                    value=""
                                    required
                                    ></textarea>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="form-group text-center">
                                        <button type="button" id="discount_uploadfile" class="discount_uploadfile btn btn-primary w-md waves-effect waves-light addbtn">{{ trans('general.submit') }}</button>
                                        <button type="button" id="closmod2" data-dismiss="modal" aria-hidden="true" class="closmod btn btn-secondary w-md waves-effect waves-light">{{ trans('general.cancel') }}</button>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
            </div>    
            
            @if(isset($invn->clientinvoice[0]) && is_null($invn->clientinvoice[0]->invoice_id))
            <div id="admin_full_payment_model" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
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
                            <form method="POST" class="" id="adminFullPaymentfileuploadForm"
                                action="{{ route('client-invoice-pay') }}"
                                enctype="multipart/form-data" novalidate=""> 
                                @csrf
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                
                                    <input type="hidden" value="full" name="payment_type" />
                                    <input type="hidden" value="{{ $invn->user_id }}" name="uploaded_by" />
                                    <input type="hidden" value="{{ $invn->id }}" name="investigation_id" />
                                    <input type="hidden" value="{{ !is_null($invn->clientinvoice[0]->invoice_id)?$invn->clientinvoice[0]->invoice->id:'' }}" name="invoice_id" />
                                    
                                    <input type="hidden" value="{{$invn->clientinvoice[0]->invoice_id}}" name="id" />
                                    @php
                                        if($invn->clientinvoice[0]->invoice_id ==''){
                                            $type = 'create';
                                        }
                                        else{
                                            $type = 'update';
                                        }
                                    @endphp
                                    <input type="hidden" value="{{$type}}" name="type" />
                                    <input type="hidden" value="{{$invn->clientinvoice[0]->id}}" name="performaId" />
                    
                                    <div class="fallback">
                                        <!-- <input name="file" type="files" multiple="multiple" class="form-control" /> -->
                                    </div>
                                    <div class="dropzone dropzone-area" id="adminFullPaymentfileupload">
                                        <div class="dz-message">
                                            <h4>{{ trans('form.registration.investigation.document_dropfile') }}</h4>
                                        </div>
                                    </div>
                            </div><br/>
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label>{{trans('form.email_tem.ticket_create.created_on')}}<br>{{!is_null($invn->clientinvoice[0])?\Carbon\Carbon::parse($invn->clientinvoice[0]->created_at)->format('d/m/y'):''}}</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                            @php
                                                $amount = !is_null($invn->clientinvoice[0])?$invn->clientinvoice[0]->amount:0;
                                                $tax = !is_null($invn->clientinvoice[0])?$invn->clientinvoice[0]->tax:0;
                                                $sum = ($amount/((100+$tax)/100));
                                            @endphp
                                        <label>{{trans('form.performa_invoice.Sum')}}<br>{{ trans('general.money_symbol') }}{{$subtot}}</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label>{{trans('form.performa_invoice.Tax')}}<br>{{$tax}}%</label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label>{{trans('form.performa_invoice.Total')}}<br>{{ trans('general.money_symbol') }}{{$amount}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="received_date">{{trans('form.performa_invoice.received_date')}} <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control " name="received_date" required id="received_date" placeholder="{{ trans('form.performa_invoice.received_date') }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="paid_date">{{trans('form.performa_invoice.paid_date')}} <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control " name="paid_date" required id="paid_date" placeholder="{{ trans('form.performa_invoice.paid_date') }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="paymentForm">{{ trans('form.client_approval.payment_mode') }}</label>
                                    <select class="form-control" name="payment_mode_id" id="payment_mode_id">
                                        @php 
                                        $payment_modes = \App\PaymentMode::orderBy('mode_name')->get();
                                        @endphp
                                        @foreach($payment_modes as $key => $mode)
                                            <option
                                                value="{{ $mode->id }}" {{ old('payment_mode_id') == $mode->id ? 'selected' : '' }}>{{ App::isLocale('hr')?$mode->hr_mode_name:$mode->mode_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="bank_details">{{trans('form.performa_invoice.bank_details_cheque_number')}} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control " name="bank_details" required id="bank_details" placeholder="{{ trans('form.performa_invoice.bank_details_cheque_number') }}">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="userinquiry">{{ trans('form.invoice.payment.payment_note') }} <span class="text-danger">*</span></label>
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
                                    ></textarea>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="form-group text-center">
                                        <button type="submit" id="fullPaymentUploadfile" class="fullPaymentUploadfile btn btn-primary w-md waves-effect waves-light addbtn">{{ trans('general.submit') }}</button>
                                        <button type="button" id="closmod2" data-dismiss="modal" aria-hidden="true" class="closmod btn btn-secondary w-md waves-effect waves-light">{{ trans('general.cancel') }}</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                @endif
            </div>

        </div>
    </div>
</div>
<!-- end col -->    