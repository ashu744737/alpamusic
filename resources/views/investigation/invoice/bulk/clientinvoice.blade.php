<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">                  
                    <div class="invoice-title">
                        @if($invn[0]->status == 'Closed' && $invn[0]['investigation']['clientinvoice'][0]['status'] == 'paid') 
                            <span class="badge dt-badge badge-success" style="float: right;"> {{ trans('form.timeline_status.'.ucwords($invn[0]['investigation']['clientinvoice'][0]['status']))}}</span> 
                        @endif
                        <h4 class="mt-0">
                            {{trans('form.app_name')}} 
                            {{--<img src="{{ URL::asset('/images/logo-dark.png')}}" alt="logo" height="24"/>--}}
                        </h4>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                        
                            <address>
                                <strong>{{trans('form.performa_invoice.address')}}<br>
                                {{trans('form.performa_invoice.tel_fax')}}<br>
                                {{trans('form.performa_invoice.cell')}}<br><br>
                                @foreach($invn as $key => $invnn)
                                {{($key+1)}}. {{trans('form.performa_invoice.date')}}{{\Carbon\Carbon::parse($invnn['investigation']['clientinvoice'][0]['created_at'])->format('d/m/y')}}<br><br>
                                @endforeach
                                {{trans('form.performa_invoice.company_entity')}}<br>
                                {{trans('form.performa_invoice.copy')}}<br><br>
                                @foreach($invn as $key => $invnn)
                                {{($key+1)}}. {{trans('form.performa_invoice.to')}} {{$invnn['client']['user']['name']}} {{trans('form.performa_invoice.id')}} {{$invnn['investigation']['subjects'][0]['id_number']}}<br>
                                {{$invnn['client']['user']['userAddresses'][0]['address2'] ?? ''}} , {{$invnn['client']['user']['userAddresses'][0]['city']}} , {{ App::isLocale('hr')? $invnn['client']['user']['userAddresses'][0]['country']['hr_name']:$invnn['client']['user']['userAddresses'][0]['country']['en_name'] }}{{ '-'.$invnn['client']['user']['userAddresses'][0]['zipcode'] ?? '-' }}. 
                                <br/><br/>
                                @endforeach
                                </strong>
                            </address>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <center class="p-2">
                        <p class="m-0 p-0">{{trans('form.performa_invoice.doc_not_tax_invoice')}}</p>
                    </center>
                    <div>
                        @php
                        $indx = 1;
                        $lastInx = count($invn);
                        $grandTotal = 0.00;
                        @endphp
                        @foreach($invn as $key => $invnn)
                            @if($invnn->status != 'paid')
                                <div class="row p-3" style="background: #eee">
                                    <div class="col-4 mt-1">
                                        <h5 class="p-0 m-0">{{trans('form.performa_invoice.performa_invoice_txt')}}<b>{{$invnn['investigation']['clientinvoice'][0]['invoice_no']}}</b></h5>
                                    </div>
                                    <div class="col-2 mt-1">
                                        <h5>{{trans('form.performa_invoice.card_no')}} {{$invnn['investigation']['work_order_number']}}</h5>
                                    </div>
                                    <div class="col-3 mt-1">
                                        <h5>{{trans('form.performa_invoice.type')}} {{$invnn['investigation']['product']['name']}}</h5>
                                    </div>
                                    <div class="col-3 mt-1">
                                        <h5>
                                            {{trans('form.performa_invoice.subject')}} @foreach($invnn['investigation']['subjects'] as $key => $subType) {{$key+1}}. @if(config('app.locale') == 'hr')
                                            {{ !is_null(\App\SubjectTypes::where('name', $subType['sub_type'])->first())?\App\SubjectTypes::where('name', $subType['sub_type'])->first()->hr_name:(!is_null($subType['sub_type'])?$subType['sub_type']:'') }}
                                            @else
                                            {{ !is_null(\App\SubjectTypes::where('name', $subType['sub_type'])->first())?\App\SubjectTypes::where('name', $subType['sub_type'])->first()->name:(!is_null($subType['sub_type'])?$subType['sub_type']:'') }}
                                            @endif @endforeach
                                        </h5>
                                    </div>
                                </div>
                                @if(count($invnn->invoiceitems) > 0)
                                <div class="">
                                    <div class="invoice-bulk table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <td width="50%"><strong>{{ trans('form.investigationinvoice.subjects') }}</strong></td>
                                                    <td width="20%"><strong>{{ trans('form.investigationinvoice.cost') }}</strong></td>
                                                    <td width="30%" class="text-right"><strong>{{ trans('form.investigationinvoice.totals') }}</strong></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(count($invnn->invoiceitems)>0)
                                                
                                                @php $subtot=0;@endphp
                                                @foreach($invnn->invoiceitems as $invoiceitem)
                                                <tr>
                                                <td>{{$invoiceitem->subject->family_name}} ({{$invoiceitem->subject->first_name}})</td>
                                                    <td>{{ trans('general.money_symbol') }}{{$invoiceitem->cost ?? '0' }}</td>
                                                    <td class="text-right">{{ trans('general.money_symbol') }}{{$invoiceitem->cost ?? '0' }}</td>
                                                </tr>
                                                @php $subtot+=$invoiceitem->cost;@endphp
                                                @endforeach

                                                @php $includeVat = $notIncludeVat = 0; @endphp
                                                @foreach($invnn['investigation']['documents'] as $key => $documents)
                                                    @if($documents->documenttype->include_vat && $documents->documenttype->name != "Case Report")
                                                        @php $includeVat++; @endphp
                                                    @else
                                                        @if($documents->documenttype->name != "Case Report")
                                                            @php $notIncludeVat++; @endphp
                                                        @endif
                                                    @endif
                                                @endforeach

                                                @if(!empty($invnn['investigation']['documents']) && count($invnn['investigation']['documents']) && $includeVat)
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

                                                @foreach($invnn['investigation']['documents'] as $key => $documents)
                                                @if($documents->documenttype->include_vat && $documents->documenttype->name != "Case Report")
                                                <tr>
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
                                                @endif
                                                <tr>
                                                    <td class="thick-line"></td>
                                                    <td class="thick-line">
                                                        <strong>{{ trans('form.investigationinvoice.subtotal') }}</strong></td>
                                                    <td class="thick-line text-right">{{ trans('general.money_symbol') }}{{$subtot}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="no-line"></td>
                                                    <td class="no-line">
                                                        <strong>{{ trans('form.investigationinvoice.tax') }}</strong>
                                                    </td>
                                                    <td class="no-line text-right"><span class="tax">{{ $invnn->tax }}</span>%</td>
                                                </tr>
                                                @if($notIncludeVat)
                                                <tr>
                                                    <td colspan="4">
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
                                                @foreach($invnn['investigation']['documents'] as $key => $documents)
                                                    @if($documents->documenttype->include_vat == 0 && $documents->documenttype->name != "Case Report")
                                                    <tr>
                                                        <td>{{$documents->doc_name}} </td>
                                                        @if(config('app.locale') == 'hr')
                                                        <td>{{$documents->documenttype->hr_name}}</td>
                                                        @else
                                                        <td>{{$documents->documenttype->name}}</td>
                                                        @endif
                                                        <td class="text-right {{ $documents->documenttype->include_vat?'doc-cost':'' }} ">{{ trans('general.money_symbol') }}{{$documents->documenttype->price}}</td>
                                                    </tr>
                                                    @php $subtot+=$documents->documenttype->price;@endphp
                                                    @endif
                                                @endforeach
                                                @endif
                                                <tr>
                                                    <td class="no-line"></td>
                                                    <td class="no-line">
                                                        <strong>{{ trans('form.investigationinvoice.total') }}</strong></td>
                                                    <td class="no-line text-right">
                                                        <h4 class="m-0">{{ trans('general.money_symbol') }}{{$invnn->amount}}</h4>
                                                        @php $grandTotal = $grandTotal + $invnn->amount; @endphp
                                                    </td>
                                                </tr>
                                                @if($indx == $lastInx)    
                                                @if(!is_null($invnn['invoice_id']) && $invnn['invoice']['payment_status'] == 'discount')
                                                <tr>
                                                    <td class="no-line"></td>
                                                    <td class="no-line">
                                                        <strong>{{trans('general.discount_of')}}</strong></td>
                                                    <td class="no-line text-right">
                                                        <h4 class="m-0">{{ trans('general.money_symbol') }}{{$invnn['invoice']['discount_amount']}}</h4>
                                                        @php $grandTotal = $grandTotal - $invnn['invoice']['discount_amount']; @endphp
                                                    </td>
                                                </tr>
                                                @else
                                                <tr>
                                                    <td class="no-line"></td>
                                                    @if(!is_null($invnn['invoice_id']) && $invnn['invoice']['payment_status'] == 'parital' && !is_null($invnn['invoice']['parital_amount']))
                                                    <td class="no-line">
                                                        <strong>{{trans('form.invoice.payment.amount_paid')}}</strong></td>
                                                    <td class="no-line text-right">
                                                        <h4 class="m-0">{{ trans('general.money_symbol') }}{{$invnn['invoice']['parital_amount']}}</h4>
                                                        @php $grandTotal = $grandTotal - $invnn['invoice']['parital_amount']; @endphp
                                                    </td>
                                                    @endif
                                                </tr>
                                                @endif
                                                @endif
                                            </tbody>
                                            @if($indx == $lastInx)
                                                <tfoot>
                                                    <td class="thick-line"></td>
                                                    <td class="thick-line">
                                                        <h4 class="mt-2"><strong>{{ trans('form.investigationinvoice.grand_total') }}</strong></h4>
                                                    </td>
                                                    <td class="thick-line text-right">
                                                        <h4 class="mt-2">{{ trans('general.money_symbol') }}{{$grandTotal}}</h4>
                                                    </td>
                                                </tfoot>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                                @php $indx++; @endphp
                                @endif
                            @endif
                        @endforeach
                        <div class="pt-5">
                            <p>{{trans('form.performa_invoice.invoice_dec')}}</p>
                            <p>{{trans('form.performa_invoice.our_bank_detail')}}</p>
                            <p>{{trans('form.performa_invoice.bank_line_1')}}</p>
                            <p><b>{{trans('form.performa_invoice.bank_line_2')}}</b></p>
                            <br>
                            <p><b>{{trans('form.performa_invoice.Respectfully')}}</b></p>
                            <p><b>{{trans('general.app_name')}}</b></p>
                        </div>
                        <div class="">
                            <div class="d-print-none">
                                <div class="float-right">
                                    <a href="javascript:window.print()" id="clickbind" class="btn btn-success waves-effect waves-light mr-2"><i class="fa fa-print"></i></a>
                                    @if ((isSM() || isAdmin()))
                                        @if($invn[0]['investigation']['clientinvoice'][0]['status'] == 'requested' || $invn[0]['investigation']['clientinvoice'][0]['status'] == 'pending')
                                            @if(is_null($invn[0]['investigation']['clientinvoice'][0]['invoice_id']))
                                            <a href="javascript:void(0)"  class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#admin_partial_payment">{{ trans('general.partial_payment') }}</a>
                                            <a href="javascript:void(0)"  class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#admin_discount_payment_model">{{ trans('general.discount_payment') }}</a>
                                            <a href="javascript:void(0)"  class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#admin_full_payment_model">{{ trans('general.mark_final_paid') }}</a>
                                            @endif
                                        @endif
                                    @else
                                        @if($performaInvoiceStatus != 'requested' && isClient())
                                        <a href="javascript:void(0)"  id= "client_paid" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#pay_model">{{ trans('general.mark_paid') }}</a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

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
                                    <form method="POST" class="dropzone dropzone-area" id="filebulkupload"
                                    action="{{ route('client-invoice-pay-bulk') }}"
                                    enctype="multipart/form-data"> 
                                    @csrf
                                    
                                        <input type="hidden" value="{{ Auth::id() }}" name="uploaded_by" />
                                        <div class="fallback">
                                            <input id="file" name="file" type="files" multiple="multiple" class="form-control" />
                                        </div>
                                        @php $invIds = []; @endphp
                                        @foreach($invn as $key => $invnn)
                                            @php array_push($invIds, $invnn['id']); @endphp
                                        @endforeach
                                        @php $invIds = json_encode($invIds); @endphp
                                        <input type="hidden" value="{{ $invIds }}" name="investigation_id" />
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

                <!-- mark as partial paid -->
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
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <form method="POST" class="dropzone dropzone-area" id="adminPartialFileupload"
                                    action="{{ route('client-invoice-pay-bulk') }}"
                                    enctype="multipart/form-data"> 
                                    @csrf
                                        <input type="hidden" value="partial" name="payment_type" />
                                        @foreach($invn as $key => $invnn)
                                        <input type="hidden" value="{{$invnn['id']}}" name="id[]" />
                                        @endforeach
                                        <div class="fallback">
                                            <input name="file" type="files" multiple="multiple" class="form-control" />
                                        </div>
                                        <div class="dz-message">
                                            <h4>{{ trans('form.registration.investigation.document_dropfile') }}</h4>
                                        </div>
                                
                                </div><br/>
                                
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-group">
                                            <label>{{trans('form.email_tem.ticket_create.created_on')}}<br>
                                            @foreach($invn as $key => $invnn)
                                            {{($key+1)}}. {{!is_null($invnn['investigation']['clientinvoice'][0])?\Carbon\Carbon::parse($invnn['investigation']['clientinvoice'][0]['created_at'])->format('d/m/y'):''}} 
                                            @endforeach</label>
                                        </div>
                                    </div>
                                        @php
                                        $amount = $sum = 0;
                                        @endphp
                                        @foreach($invn as $key => $invnn)
                                                @php
                                                    $amount += !is_null($invnn['investigation']['clientinvoice'][0])?$invnn['investigation']['clientinvoice'][0]['amount']:0;
                                                    $tax = !is_null($invnn['investigation']['clientinvoice'][0])?$invnn['investigation']['clientinvoice'][0]['tax']:0;
                                                @endphp
                                        @endforeach
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
                                            <button type="button" id="partial_uploadfile" class="partial_uploadfile btn btn-primary w-md waves-effect waves-light addbtn">{{ trans('general.submit') }}</button>
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
                <!-- End  -->

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
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <form method="POST" class="dropzone dropzone-area" id="adminFullPaymentfileupload"
                                    action="{{ route('client-invoice-pay-bulk') }}"
                                    enctype="multipart/form-data"> 
                                    @csrf
                                    
                                        <input type="hidden" value="full" name="payment_type" />
                                        @foreach($invn as $key => $invnn)
                                        <input type="hidden" value="{{$invnn['id']}}" name="id[]" />
                                        @endforeach
                        
                                        <div class="fallback">
                                            <input name="file" type="files" multiple="multiple" class="form-control" />
                                        </div>
                                        <div class="dz-message">
                                            <h4>{{ trans('form.registration.investigation.document_dropfile') }}</h4>
                                        </div>
                                </div><br/>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-group">
                                            <label>{{trans('form.email_tem.ticket_create.created_on')}}<br>
                                            @foreach($invn as $key => $invnn)
                                            {{($key+1)}}. {{!is_null($invnn['investigation']['clientinvoice'])?\Carbon\Carbon::parse($invnn['investigation']['clientinvoice'][0]['created_at'])->format('d/m/y'):''}} 
                                            @endforeach</label>
                                        </div>
                                    </div>
                                        @php
                                        $amount = $sum = 0;
                                        @endphp
                                        @foreach($invn as $key => $invnn)
                                                @php
                                                    $amount += !is_null($invnn['investigation']['clientinvoice'])?$invnn['investigation']['clientinvoice'][0]['amount']:0;
                                                    $tax = !is_null($invnn['investigation']['clientinvoice'])?$invnn['investigation']['clientinvoice'][0]['tax']:0;
                                                @endphp
                                        @endforeach
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
                                            <button type="button" id="fullPaymentUploadfile" class="fullPaymentUploadfile btn btn-primary w-md waves-effect waves-light addbtn">{{ trans('general.submit') }}</button>
                                            <button type="button" id="closmod2" data-dismiss="modal" aria-hidden="true" class="closmod btn btn-secondary w-md waves-effect waves-light">{{ trans('general.cancel') }}</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                </div>

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
                                    action="{{ route('client-invoice-pay-bulk') }}"
                                    enctype="multipart/form-data"> 
                                    @csrf
                                        <input type="hidden" value="discount" name="payment_type" />
                                        @foreach($invn as $key => $invnn)
                                        <input type="hidden" value="{{$invnn['id']}}" name="id[]" />
                                        @endforeach
                                        <div class="fallback">
                                            <input name="file" type="files" multiple="multiple" class="form-control" />
                                        </div>
                                        <div class="dz-message">
                                            <h4>{{ trans('form.registration.investigation.document_dropfile') }}</h4>
                                        </div>
                                
                                </div><br/>

                                <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group">
                                                <label>{{trans('form.email_tem.ticket_create.created_on')}}<br>
                                                @foreach($invn as $key => $invnn)
                                                {{($key+1)}}. {{!is_null($invnn['investigation']['clientinvoice'][0])?\Carbon\Carbon::parse($invnn['investigation']['clientinvoice'][0]['created_at'])->format('d/m/y'):''}} 
                                                @endforeach</label>
                                            </div>
                                        </div>
                                            @php
                                            $amount = $sum = 0;
                                            @endphp
                                            @foreach($invn as $key => $invnn)
                                                    @php
                                                        $amount += !is_null($invnn['investigation']['clientinvoice'][0])?$invnn['investigation']['clientinvoice'][0]['amount']:0;
                                                        $tax = !is_null($invnn['investigation']['clientinvoice'][0])?$invnn['investigation']['clientinvoice'][0]['tax']:0;
                                                    @endphp
                                            @endforeach
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
            </div>
        </div>
    </div>
</div>
<!-- end col -->