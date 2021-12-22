<?php

namespace App\Http\Controllers;

use App\PerformaInvoice;
use App\Invoice;
use App\InvoiceInvstigations;
use App\ClientProduct;
use App\InvoiceDocuments;
use App\Client;
use App\Setting;
use App\Investigations;
use App\InvestigationTransition;
use App\InvoiceItems;
use App\InvestigatorInvoice;
use App\InvestigatorInvoiceDocuments;
use App\DeliveryboyInvoiceDocuments;
use App\DeliveryboyInvoice;
use App\UserCategories;
use App\Mail\EmailInvoice;
use App\Mail\InvoiceUpdate;
use App\Mail\InvestigatorInvoiceUpdate;
use App\Mail\InvoiceAlert;
use App\Helpers\AppHelper;
use Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Cookie;
use DB;

class InvoiceController extends Controller
{
    public function index()
    {
        if(isAdmin() || isSM() || isAccountant() || isClient()){
            return view('invoice.actual-invoice');
        }else{
            return view('invoice.index');
        }
        
    }

    public function pendinginvoice()
    {
        return view('invoice.pending-invoice');
    }

    public function performainvoices()
    {
        return view('invoice.index');
    }


    //Invoice list for customer and perofrma invoice for admin
    public function invoiceList(Request $request)
    {
        $invoices = PerformaInvoice::with([
            'client' => function ($q) {
                $q->with(['paymentTerm' => function($qq){
                    $qq->select('id', DB::raw("(CASE WHEN term_name='Immediately' THEN 0 WHEN term_name='Immediately + 15' THEN 15 WHEN term_name='Immediately + 30' THEN 30 WHEN term_name='Immediately + 60' THEN 60 WHEN term_name='Immediately + 90' THEN 90 END) as term_name"));
                }, 'user']);
            },
            'investigation', 'investigation.user', 'investigation.client_type', 'investigation.investigationCreatedBy'
        ]);

        if(isClient()){
            $client = Client::where('user_id', Auth::user()->id)->first();
            $invoices->where('client_id', $client->id);
        }

        if (isSM()) {
            $usercats = UserCategories::getUserCategories();
            $invoices->whereHas('investigation', function ($q) use ($usercats){
                $q->whereHas('product', function ($q) use ($usercats) {
                    $q->select(['id', 'name', 'category_id'])
                        ->whereIn('category_id',  $usercats);
                });
            });
        }

        $invoices = $invoices->get();

        //$invoices->orderBy('created_at', 'desc');

        return DataTables::of($invoices)
            ->addColumn('client_name', function ($invoice) {
                return !empty($invoice->investigation->user) ? $invoice->investigation->user->name : '';
            })
            ->addColumn('paying_customer', function ($invoice) {
                return !empty($invoice->investigation->client_type) ? $invoice->investigation->client_type->name : '';
            })
            ->addColumn('investigationCreatedBy', function ($invoice) {
                return !empty($invoice->invoiceCreatedBy->name) ? $invoice->invoiceCreatedBy->name : '';
            })
            ->addColumn('work_order_number', function ($invoice) {
                return !empty($invoice->investigation->work_order_number) ? $invoice->investigation->work_order_number : '';
            })
            ->editColumn('amount', function ($invoice) {
                return !empty($invoice->amount) ? trans('general.money_symbol').$invoice->amount : trans('general.money_symbol').'0';
            })
            ->editColumn('created_at', function ($invoice) {
                return Carbon::parse($invoice->created_at)->format('d/m/Y');
            })
            ->editColumn('status', function ($invoice) {
                if ($invoice->status) {
                    if ($invoice->status == 'paid') {
                        return '<td>
                            <span class="badge dt-badge badge-success">' . trans('form.timeline_status.'.ucwords($invoice->status)) . '</span>
                        </td>';
                    } else if ($invoice->status == 'pending') {
                        return '<td>
                            <span class="badge dt-badge badge-warning">' . trans('form.timeline_status.'.ucwords($invoice->status)) . '</span>
                        </td>';
                    } else {
                        $paidAmount = '';
                        if ($invoice->invoice && !blank($invoice->invoice->parital_amount)) {
                        $paidAmount = '<td>
                                <span class="mt-1 badge dt-badge badge-dark">' .trans('form.invoice.payment.amount_paid').' '.trans('general.money_symbol'). $invoice->invoice->parital_amount . '</span>
                            </td>';
                        }

                        return '<td>
                            <span class="badge dt-badge badge-dark">' . trans('form.timeline_status.'.ucwords($invoice->status)) . '</span>
                        </td>'.$paidAmount;
                    }
                }
                return "";
            })
            ->addColumn('due_date', function ($invoice) {
                if(!is_null($invoice->client) && !is_null($invoice->client->paymentTerm) && $invoice->status != 'paid'){
                    $invoiceDate = Carbon::parse($invoice->created_at);
                    if($invoice->client->paymentTerm->term_name == 0){
                        $days = 1;
                    } else {
                        $days = $invoice->client->paymentTerm->term_name;
                    }
                    return $invoiceDate->addDays($days)->format('d/m/Y');;
                    // if($invoice->client->paymentTerm->term_name == 0 && ($days > 0)){
                    //     return $invoiceDate->addDays($days)->format('d/m/Y');;
                    // } else if($invoice->client->paymentTerm->term_name == 15 && ($days > 15)) {
                    //     return $invoiceDate->addDays($days)->format('d/m/Y');;
                    // } else if($invoice->client->paymentTerm->term_name == 30 && ($days > 30)) {
                    //     return $invoiceDate->addDays($days)->format('d/m/Y');;
                    // } else if($invoice->client->paymentTerm->term_name == 60 && ($days > 60)) {
                    //     return $invoiceDate->addDays($days)->format('d/m/Y');;
                    // } else if($invoice->client->paymentTerm->term_name == 90 && ($days > 90)) {
                    //     return $invoiceDate->addDays($days)->format('d/m/Y');;
                    // }
                }
            })
            ->addColumn('late', function ($invoice) {
                if(!is_null($invoice->client) && !is_null($invoice->client->paymentTerm) && $invoice->status == 'pending'){
                    $invoiceDate = Carbon::parse($invoice->created_at);
                    $days = $invoiceDate->diffInDays(Carbon::now());
                    if($invoice->client->paymentTerm->term_name == 0 && ($days > 0)){
                        return 0;
                    } else if($invoice->client->paymentTerm->term_name == 15 && ($days > 15)) {
                        return 0;
                    } else if($invoice->client->paymentTerm->term_name == 30 && ($days > 30)) {
                        return 0;
                    } else if($invoice->client->paymentTerm->term_name == 60 && ($days > 60)) {
                        return 0;
                    } else if($invoice->client->paymentTerm->term_name == 90 && ($days > 90)) {
                        return 0;
                    }
                }
                return 1;
            })
            ->addColumn('action', function ($invoice) {
                $btns = "<span class='d-inline-flex'>";
                //$invoiceId = Crypt::encrypt($invoice->id);
                $btns .= '<a href="' . route('invoice.show', [Crypt::encrypt($invoice->id),'pinvoice']) . '" class="dt-btn-action" title="' . trans('form.email_tem.ticket_update.view_invoice') . '">
                                <i class="fas fa-money-bill-wave "></i>
                            </a>';
                $btns .= '</span>';
                return $btns;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    // For customer from investigation and invoice link
    //For admin after invoice is generated and performa invoice
    public function showInvoice($id,$type)
    {
        if($type=='invoice'){
            $invoice = Invoice::where('id', Crypt::decrypt($id))->first();
            
            $invn = Investigations::with(['clientinvoice' => function($q) use ($invoice) {
                $q->where('id', $invoice->id);
            }])->find($invoice->investigation_id);
            
            $clientId = $invoice->client_id;
            $performaInvoiceStatus = !is_null($invoice->performaInvoice)?$invoice->performaInvoice->status:$invoice->status;

            //$invno = 'INV' . date('isymHd');
            $invno = $invoice->invoice_no;
        
            $client = Client::find($clientId);
            $typeofInq = ClientProduct::with('product:id,name,is_delivery,delivery_cost')->where('client_id', $clientId)->where('product_id', 1)->first();

            $settings = Setting::all();

            return view('invoice.invoice-pay', compact('invoice', 'typeofInq', 'invno', 'client', 'settings', 'performaInvoiceStatus'));
        }else{
            $performaInvoice = PerformaInvoice::where('id', Crypt::decrypt($id))->first();
            $invn = Investigations::with(['clientinvoice' => function($q) use ($performaInvoice) {
                $q->where('id', $performaInvoice->id);
            }])->find($performaInvoice->investigation_id);

            $clientId = AppHelper::getClientIdFromUserId($invn->user_id);
            $performaInvoiceStatus = $performaInvoice->status;

            //$invno = 'INV' . date('isymHd');
            $invno = $performaInvoice->invoice_no;

            $client = Client::find($clientId);
            $typeofInq = ClientProduct::with('product:id,name,is_delivery,delivery_cost')->where('client_id', $clientId)->where('product_id', 1)->first();

            $settings = Setting::all();

            return view('investigation.invoice', compact('invn', 'typeofInq', 'invno', 'client', 'settings', 'performaInvoiceStatus','performaInvoice'));
        }

    }

    function loadInvoice(){

    }

    /**
     * Store a Client Invoice Pay Data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function clientInvoicePay(Request $request)
    {
        try {
            $client = Client::where('user_id', $request->uploaded_by)->first();
            $performainvoice = PerformaInvoice::where('investigation_id', $request->investigation_id)->where('client_id', $client->id)->first();
            if(isset($request['invoice_id']) && !is_null($request['invoice_id'])){
                $invoice = Invoice::where('id', $request['invoice_id'])->first();
            } else {
                $invno = 'INV' . AppHelper::genrateInvoiceNumber("\App\Invoice");
                $invoiceData = [ 
                                'invoice_no' => $invno,
                                'client_id' =>  $client->id,
                                'amount' => $performainvoice->amount,
                                'tax' => $performainvoice->tax,
                                'delivery_cost' => $performainvoice->delivery_cost,
                                'client_payment_notes' => $request->notes,
                                'status' => 'pending',
                            ];

                $invoice = Invoice::create($invoiceData);

                InvoiceInvstigations::create(['invoice_id' => $invoice->id, 'investigation_id'=>$request->investigation_id]);

                PerformaInvoice::where('investigation_id',$request->investigation_id)->update(['status' => 'requested', 'invoice_id' => $invoice->id, 'paid_by' => Auth::id()]);
            }
            
            if ($request->hasfile('file')) {
                foreach ($request->file as $key => $file) {
                    $filePath = public_path('invoice-pay-docs');
                    if (!file_exists($filePath)) {
                        mkdir($filePath, 0777, true);
                    }
                    $ext = $file->extension();
                    $size = $file->getSize();
                    $fileName = time() . '-' . trim($file->getClientOriginalName());
                    $originalName = $file->getClientOriginalName();
                    $fileId = 0;

                    $fileModel = new InvoiceDocuments();
                    $file->move($filePath . '/', $fileName);
                    $fileModel->invoice_id = $invoice->id;
                    $fileModel->doc_name = $originalName;
                    $fileModel->file_name = $fileName;
                    $fileModel->file_path = $filePath . '/' . $fileName;
                    $fileModel->file_extension = $ext;
                    $fileModel->payment_note = $request->notes;
                    $fileModel->file_size = $size;
                    $fileModel->uploaded_by = $request->uploaded_by;

                    $fileModel->save();
                    $invtransdata = [
                        'event' => 'investigation_markPaid_client',
                        'data' => json_encode(array('data' => array('id' => $invoice->id,'client_payment_notes' => $request->notes,'invoice_doc_id' => $fileModel->id, 'type' => 'clientmarkaspaid'))),
                    ];
                    InvestigationTransition::addTransion($invtransdata, $request->investigation_id);

                    $fileId = $fileModel->id;
                    
                }
            }

            if(isAdmin() || isSM() || isAccountant()){
                if($request->payment_type == 'discount') {
                    if($this->doDiscountPayment($request->all(), $invoice->id)){
                        return response()->json(['status' => 'success', 'message' => trans('form.registration.investigation.document_uploaded'), 'data' => ["fileId" => 1]]);
                    }
                } else if($request->payment_type == 'full') {
                    if($this->domarkAsFinalPaid($request->all(), $invoice->id)){
                        return response()->json(['status' => 'success', 'message' => trans('form.registration.investigation.document_uploaded'), 'data' => ["fileId" => 1]]);
                    }
                } else {
                    if($this->doPartialPayment($request->all(), $invoice->id)){
                        return response()->json(['status' => 'success', 'message' => trans('form.registration.investigation.document_uploaded'), 'data' => ["fileId" => 1]]);
                    }
                }
            } else {
                // print_r('here');die;
                return response()->json(['status' => 'success', 'message' => trans('form.registration.investigation.document_uploaded'), 'data' => ["fileId" => 1]]);
            }
            
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function clientInvoicePayBulk(Request $request)
    {
        if(isset($request->payment_type)){
            $ids = array_unique($request->id);
            $performainvoice = PerformaInvoice::whereIn('id', $ids)->whereIn('status', ['pending', 'on-hold'])->get();

            $invno = 'INV' . AppHelper::genrateInvoiceNumber("\App\Invoice");
            $amount = $discount_amount = $parital_amount = $delivery_cost = $tax = 0;
            $invoiceIds = [];
            foreach($performainvoice as $invoice){
                $client = $invoice['client_id'];
                $amount += $invoice['amount'];
                $discount_amount += $invoice['discount_amount'];
                $parital_amount  += $invoice['parital_amount'];
                $delivery_cost   += $invoice['delivery_cost'];
                $tax = $invoice['tax'];
                if(!is_null($invoice['invoice_id'])){
                    array_push($invoiceIds, $invoice['invoice_id']);
                }
            }
            $invoiceIds = array_unique($invoiceIds);
            if($request->payment_type == 'partial'){
                if(!empty($invoiceIds)){
                    $invoice = Invoice::whereIn('id', $invoiceIds)->first();
                } else {
                    $invoiceData = [ 
                        'invoice_no' => $invno,
                        'client_id' =>  $client,
                        'amount' => $amount,
                        'tax' => $tax,
                        'delivery_cost' => $delivery_cost,
                        'client_payment_notes' => $request->notes,
                        'discount_amount' => $discount_amount,
                        'parital_amount' => $parital_amount,
                    ];
                    $invoice = Invoice::create($invoiceData);
                    foreach($performainvoice as $key => $performainv) {
                        InvoiceInvstigations::create(['invoice_id' => $invoice->id, 'investigation_id'=>$performainv->investigation_id]);
                    }    
                }
            } else {
                $invoiceData = [ 
                    'invoice_no' => $invno,
                    'client_id' =>  $client,
                    'amount' => $amount,
                    'tax' => $tax,
                    'delivery_cost' => $delivery_cost,
                    'client_payment_notes' => $request->notes,
                    'discount_amount' => $discount_amount,
                    'parital_amount' => $parital_amount,
                ];
                $invoice = Invoice::create($invoiceData);
                foreach($performainvoice as $key => $performainv) {
                    InvoiceInvstigations::create(['invoice_id' => $invoice->id, 'investigation_id'=>$performainv->investigation_id]);
                }
            }
            
            if ($request->hasfile('file')) {
                foreach ($request->file as $key => $file) {
                    $filePath = public_path('invoice-pay-docs');
                    if (!file_exists($filePath)) {
                        mkdir($filePath, 0777, true);
                    }
                    $ext = $file->extension();
                    $size = $file->getSize();
                    $fileName = time() . '-' . trim($file->getClientOriginalName());
                    $originalName = $file->getClientOriginalName();
                    $fileId = 0;
                    try {
                        $file->move($filePath . '/', $fileName);
                        $fileModel = new InvoiceDocuments();
                        $fileModel->invoice_id = $invoice->id;
                        //$fileModel->investigation_id = $investigationId;
                        $fileModel->doc_name = $originalName;
                        $fileModel->file_name = $fileName;
                        $fileModel->file_path = $filePath . '/' . $fileName;
                        $fileModel->file_extension = $ext;
                        $fileModel->file_size = $size;
                        $fileModel->uploaded_by = Auth::id();
                        $fileModel->save();
                    } catch (\Exception $e) {
                        return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
                    }
                }
            }
            if($request->payment_type != 'partial'){
                if($request->payment_type == 'full'){
                    Invoice::where('id', $invoice)->update([
                        'paid_by' => Auth::id(),
                        'parital_amount' => NULL,
                        'discount_amount' => NULL,
                        'status' => 'paid',
                        'payment_status' => 'full',
                        'received_date' => ($request->received_date ?? NULL),
                        'paid_date' => ($request->paid_date ?? NULL),
                        'payment_mode_id' => ($request->payment_mode_id ?? NULL),
                        'bank_details' => ($request->bank_details ?? NULL),
                        'admin_notes' => ($request->admin_notes ?? NULL),
                    ]);
                    PerformaInvoice::whereIn('id', $ids)->update(['invoice_id' => $invoice->id, 'paid_by' => Auth::user()->id, 'status' => 'paid', 'payment_status' => 'full']);
                } else {
                    Invoice::where('id', $invoice)->update([
                        'paid_by' => Auth::id(),
                        'parital_amount' => NULL,
                        'discount_amount' => $request->discount_amount,
                        'status' => 'paid',
                        'payment_status' => 'discount',
                        'received_date' => ($request->received_date ?? NULL),
                        'paid_date' => ($request->paid_date ?? NULL),
                        'payment_mode_id' => ($request->payment_mode_id ?? NULL),
                        'bank_details' => ($request->bank_details ?? NULL),
                        'admin_notes' => ($request->admin_notes ?? NULL),
                    ]);
                    PerformaInvoice::whereIn('id', $ids)->update(['invoice_id' => $invoice->id, 'paid_by' => Auth::user()->id, 'status' => 'paid', 'payment_status' => 'discount', 'discount_amount' => $request->discount_amount]);
                }
            } else {
                if($amount < $request->parital_amount){
                    return redirect()->route('invoice.show', [Crypt::encrypt($invoice->id), 'invoice'])->with('error', trans('form.invoice.payment.bigger_then_amount'));
                } else if($amount > $request->parital_amount) {
                    $oldInv = Invoice::where('id', $invoice->id)->first();
                    Invoice::where('id', $invoice->id)->update([
                        'paid_by' => Auth::id(),
                        'payment_status' => 'parital',
                        'amount' => $invoice->amount,
                        'parital_amount' => $request->parital_amount,
                    ]);
                    PerformaInvoice::whereIn('id', $ids)->update(['invoice_id' => $invoice->id, 'paid_by' => Auth::user()->id, 'status' => 'requested', 'payment_status' => 'parital']);
                    $newInvno = 'INV' . AppHelper::genrateInvoiceNumber("\App\Invoice");
                    $newInv = Invoice::create([
                        'client_id' => $invoice->client_id,
                        'invoice_no' => $newInvno,
                        'paid_by' => Auth::id(),
                        'amount' => $request->parital_amount,
                        'tax' => $invoice->tax,
                        'client_payment_notes' => $invoice->client_payment_notes,
                        'status' => 'paid',
                        'payment_status' => 'full',
                        'received_date' => ($request->received_date ?? NULL),
                        'paid_date' => ($request->paid_date ?? NULL),
                        'payment_mode_id' => ($request->payment_mode_id ?? NULL),
                        'bank_details' => ($request->bank_details ?? NULL),
                        'admin_notes' => ($request->admin_notes ?? NULL),
                        'partial_invoice_id' => $oldInv->id,
                    ]);

                    $newInvInvestigation = InvoiceInvstigations::where('invoice_id', $invoice->id)->get()->toArray();
                    foreach($newInvInvestigation as $key => $oldInv) {
                        InvoiceInvstigations::create([
                            'invoice_id' => $newInv->id,
                            'investigation_id' => $newInvInvestigation[$key]['investigation_id']
                        ]);
                    }
                    $newInvDocs = InvoiceDocuments::where('invoice_id', $invoice->id)->get()->toArray();
                    foreach($newInvDocs as $key => $oldInvDoc) {
                        unset($newInvDocs[$key]['id']);
                        $newInvDocs[$key]['invoice_id'] = $newInv->id;
                        $newInvDocs[$key]['created_at'] = now();
                        $newInvDocs[$key]['updated_at'] = now();
                        InvoiceDocuments::create([
                            'invoice_id' => $newInv->id,
                            'doc_name' => $newInvDocs[$key]['doc_name'],
                            'payment_note' => $newInvDocs[$key]['payment_note'],
                            'file_name' => $newInvDocs[$key]['file_name'],
                            'file_path' => $newInvDocs[$key]['file_path'],
                            'file_extension' => $newInvDocs[$key]['file_extension'],
                            'file_size' => $newInvDocs[$key]['file_size'],
                            'uploaded_by' => $newInvDocs[$key]['uploaded_by']
                        ]);
                    }
                } else{
                    $investigations = $invoice->investigation->pluck('id')->toArray();

                    Invoice::where('id', $invoice->id)->update(['paid_by' => Auth::id(), 'payment_status' => 'full', 'amount' => $invoice->amount, 'parital_amount' => NULL, 'status' => 'paid', 'received_date' => ($request->received_date ?? NULL), 'paid_date' => ($request->paid_date ?? NULL), 'payment_mode_id' => ($request->payment_mode_id ?? NULL),  'bank_details' => ($request->bank_details ?? NULL), 'admin_notes' => ($request->admin_notes ?? NULL)]);

                    PerformaInvoice::where('invoice_id',$invoice->id)->update(['paid_by' => Auth::user()->id, 'status' => 'paid', 'payment_status' => 'full']);

                    Investigations::whereIn('id',$investigations)->update(['status' => 'Closed', 'status_hr' => trans('form.timeline_status.Closed', [], 'hr')]);
                }
            }
        } else {
            $ids = json_decode($request->investigation_id, true);
            $client = Client::where('user_id', $request->uploaded_by)->first();
            $performainvoice = PerformaInvoice::whereIn('id', $ids)->where('client_id', $client->id)->get();

            $invno = 'INV' . AppHelper::genrateInvoiceNumber("\App\Invoice");

            $amount = $discount_amount = $parital_amount = $delivery_cost = $tax = 0;
            
            foreach ($performainvoice as $key => $pinvoice) {
                $amount += $pinvoice['amount'];
                $discount_amount += $pinvoice['discount_amount'];
                $parital_amount  += $pinvoice['parital_amount'];
                $delivery_cost   += $pinvoice['delivery_cost'];
                $tax += $pinvoice['tax'];
            }

            $invoiceData = [ 'invoice_no' => $invno,
                                'client_id' =>  $client->id,
                                'amount' => $amount,
                                'tax' => $tax,
                                'delivery_cost' => $delivery_cost,
                                'client_payment_notes' => $request->notes,
                                'status' => 'pending',
                            ];
            $invoice = Invoice::create($invoiceData);
            
            foreach($performainvoice as $key => $performainv) {
                InvoiceInvstigations::create(['invoice_id' => $invoice->id, 'investigation_id'=>$performainv->investigation_id]);
            }

            PerformaInvoice::whereIn('id', $ids)->update(['status' => 'requested','invoice_id' => $invoice->id, 'paid_by' => Auth::id()]);

            
            if ($request->hasfile('file')) {
                foreach ($request->file as $key => $file) {
                    $filePath = public_path('invoice-pay-docs');
                    if (!file_exists($filePath)) {
                        mkdir($filePath, 0777, true);
                    }

                    $ext = $file->extension();
                    $size = $file->getSize();
                    $fileName = time() . '-' . trim($file->getClientOriginalName());
                    $originalName = $file->getClientOriginalName();
                    $fileId = 0;
                    
                    
                    try {
                        $file->move($filePath . '/', $fileName);

                        $fileModel = new InvoiceDocuments();

                        $fileModel->invoice_id = $invoice->id;
                        //$fileModel->investigation_id = $investigationId;
                        $fileModel->doc_name = $originalName;
                        $fileModel->file_name = $fileName;
                        $fileModel->file_path = $filePath . '/' . $fileName;
                        $fileModel->file_extension = $ext;
                        $fileModel->file_size = $size;
                        $fileModel->uploaded_by = $request->uploaded_by;

                        $fileModel->save();
                    } catch (\Exception $e) {
                        return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
                    }
                }
            }

            //$fileId = $fileModel->id;
            return response()->json(['status' => 'success', 'message' => trans('form.registration.investigation.document_uploaded'), 'data' => ["fileId" => 1]]);
        }
    }

    //Admin list of Invoices datatable
    public function invoiceListAdmin($status = '', Request $request)
    {   
        $data = $request->all();
        $invoices = Invoice::with('client', 'client.user', 'investigation', 'investigation.client_type')->has('invoicedocs')->where('payment_status','parital');

        if($status != ''){
            $invoices->where('status', '!=', 'paid');
        } else {
            $invoices->where('status', 'paid');
        }
        
        if (isSM()) {
            $usercats = UserCategories::getUserCategories();
            $invoices->whereHas('investigation', function ($q) use ($usercats){
                $q->whereHas('product', function ($q) use ($usercats) {
                    $q->select(['id', 'name', 'category_id'])
                        ->whereIn('category_id',  $usercats);
                });
            });
        }

        if(isClient()){
            $client = Client::where('user_id', Auth::user()->id)->first();
            $invoices->where('client_id', $client->id);
        }

        $invoices = $invoices->get();
        return DataTables::of($invoices)
            ->addColumn('client_name', function ($invoice) {
                return !empty($invoice->client->user) ? $invoice->client->user->name : '';
            })
            ->addColumn('work_order_number', function ($invoice) {
                $work_order_number = "";
                foreach ($invoice->investigation as $k => $investigation) {
                    $work_order_number .= '<span class="badge dt-badge badge-dark">'.$investigation->work_order_number.'</span> &nbsp;';
                }
                //return !empty($invoice->investigation->work_order_number) ? $invoice->investigation->work_order_number : '';
                return $work_order_number;
            })
            ->addColumn('paying_customer', function ($invoice) {
                $name = "";
                foreach ($invoice->investigation as $k => $investigation) {
                    $name .= $investigation->client_type->name.' ';
                }
                //return !empty($invoice->investigation->work_order_number) ? $invoice->investigation->work_order_number : '';
                return $name;
            })
            ->addColumn('claim_number', function ($invoice) {
                $claim_number = "";
                foreach ($invoice->investigation as $k => $investigation) {
                    $claim_number .= $investigation->claim_number.' ';
                }
                return $claim_number;
            })
            ->addColumn('external_file_claim', function ($invoice) {
                $external_file_claim = "";
                foreach ($invoice->investigation as $k => $investigation) {
                    $external_file_claim .= $investigation->ex_file_claim_no.' ';
                }
                return $external_file_claim;
            })
            ->editColumn('amount', function ($invoice) {
                if($invoice->payment_status == 'parital'){
                    return !empty($invoice->amount) ? trans('general.money_symbol').($invoice->amount - $invoice->parital_amount) : trans('general.money_symbol').'0';
                }
                return !empty($invoice->amount) ? trans('general.money_symbol').$invoice->amount : trans('general.money_symbol').'0';
            })
            ->editColumn('status', function ($invoice) {
                if ($invoice->status) {
                    if ($invoice->status == 'paid') {
                        return '<td>
                            <span class="badge dt-badge badge-success">' . trans('form.timeline_status.'.ucwords($invoice->status)) . '</span>
                        </td>';
                    } else if ($invoice->status == 'pending') {
                        return '<td>
                            <span class="badge dt-badge badge-warning">' . trans('form.timeline_status.'.ucwords($invoice->status)) . '</span>
                        </td>';
                    } else {
                        return '<td>
                            <span class="badge dt-badge badge-dark">' . trans('form.timeline_status.'.ucwords($invoice->status)) . '</span>
                        </td>';
                    }
                }
                return "";
            })
            ->editColumn('created_at', function ($invoice) {
                return Carbon::parse($invoice->created_at)->format('d/m/Y');
            })
            ->addColumn('action', function ($invoice) {
                $btns = "<span class='d-inline-flex'>";
                //$invoiceId = Crypt::encrypt($invoice->id);
                $btns .= '<a href="' . route('invoice.show', [Crypt::encrypt($invoice->id),'invoice']) . '" class="dt-btn-action" title="' . trans('form.email_tem.ticket_update.view_invoice') . '">
                                <i class="fas fa-money-bill-wave "></i>
                            </a>';
                $btns .= '</span>';
                return $btns;
            })
            ->rawColumns(['work_order_number','status', 'action'])
            ->make(true);
    }

    public function markAsFinalPaid(Request $request)
    {
        try {
            //check if admin has marked performa invoice as paid without client's consent from performa invoice page
            // if($request->type == 'create'){

            // }
            $this->domarkAsFinalPaid($request->all(), $request->invoice_id);
            return redirect()->route('invoices.index')->with('success', trans('form.invoice.payment_done'));
            // return response()->json(['status' => 'success', 'message' => trans('form.invoice.payment_done')]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => trans('general.something_wrong'),
                'exception' => $th->getMessage(),
            ]);
        } 
    }

    public function domarkAsFinalPaid($request, $invId = NULL)
    {
        if(!is_null($invId)){
            $invoiceId = $invId;
        } else {
            $invoiceId = $request['invoice_id'];
        }
        $invoice = Invoice::where('id', $invoiceId)->with('investigation')->first();
        
        $getOldIncoiceData = Invoice::where('id', $invoiceId)->first();

        $paymentStatus = ($getOldIncoiceData->payment_status == 'parital' ? 'parital' : 'full');
        if (isset($request['payment_type']) && $request['payment_type'] == 'full')
            $paymentStatus = 'full';
        
        Invoice::where('id', $invoiceId)->update(['paid_by' => Auth::user()->id, 'status' => 'paid', 'payment_status' => $paymentStatus, 'received_date' => ($request['received_date'] ?? NULL), 'paid_date' => ($request['paid_date'] ?? NULL), 'payment_mode_id' => ($request['payment_mode_id'] ?? NULL),  'bank_details' => ($request['bank_details'] ?? NULL), 'admin_notes' => ($request['admin_notes'] ?? NULL)]);

        PerformaInvoice::where('invoice_id',$invoice['id'])->update(['paid_by' => Auth::user()->id, 'status' => 'paid', 'payment_status' => 'full']);

        $userData = $invoice->client->user;

        $investigations = $invoice->investigation->pluck('id')->toArray();
        Investigations::whereIn('id',$investigations)->update(['status' => 'Closed', 'status_hr' => trans('form.timeline_status.Closed', [], 'hr')]);

        $invoiceData = Invoice::where('id', $invoiceId)->with('investigation')->first();
        foreach ($investigations as $key => $investigation) {
            $invtransdata = [
                'event' => 'investigation_invoicestatus_changed',
                'data' => json_encode(array('data' => array('id' => $invoiceId, 'type' => 'client', 'status' => 'Full'))),
            ];

            InvestigationTransition::addTransion($invtransdata, $investigation);
        }
        Mail::to($invoiceData->client->user->email)->queue(new InvoiceUpdate($invoiceData, $userData, Auth::user()));
        return true;
    }

    public function discountPayment(Request $request)
    {
        try {
            $this->doDiscountPayment($request->all(), $request->invoice_id);
            return redirect()->route('invoices.index')->with('success', trans('form.invoice.payment_done'));
        } catch (\Throwable $th) {
            return redirect()->route('invoices.index')->with('error', trans('form.error') . ' ( ' . $th->getMessage() . ' )');
        }
    }

    public function doDiscountPayment($request, $invId = NULL)
    {
        if(!is_null($invId)){
            $invoiceId = $invId;
        } else {
            $invoiceId = $request['invoice_id'];
        }
        $invoice = Invoice::where('id', $invoiceId)->with('investigation')->first();

        $userData = $invoice->client->user;

        Invoice::where('id', $invoiceId)->update(['paid_by' => $request['uploaded_by'], 'status' => 'paid', 'payment_status' => 'discount', 'discount_amount' => $request['discount_amount'], 'received_date' => ($request['received_date'] ?? NULL), 'paid_date' => ($request['paid_date'] ?? NULL), 'payment_mode_id' => ($request['payment_mode_id'] ?? NULL),  'bank_details' => ($request['bank_details'] ?? NULL), 'admin_notes' => ($request['admin_notes'] ?? NULL)]);

        PerformaInvoice::where('invoice_id',$invoice->id)->update(['paid_by' => Auth::user()->id, 'status' => 'paid', 'payment_status' => 'full']);
        
        $investigations = $invoice->investigation->pluck('id')->toArray();
        Investigations::whereIn('id',$investigations)->update(['status' => 'Closed', 'status_hr' => trans('form.timeline_status.Closed', [], 'hr')]);            
        $invoiceData = Invoice::where('id', $invoiceId)->with('investigation')->first();
        foreach ($investigations as $key => $investigation) {
            $invtransdata = [
                'event' => 'investigation_invoicestatus_changed',
                'data' => json_encode(array('data' => array('id' => $invoiceData->id, 'type' => 'client','status' => 'Discount'))),
            ];

            InvestigationTransition::addTransion($invtransdata, $investigation);
        }

        Mail::to($invoiceData->client->user->email)->queue(new InvoiceUpdate($invoiceData, $userData, Auth::user()));
        return true;
    }

    public function paritalPayment(Request $request)
    {
        try {
            $this->doPartialPayment($request->all(), $request->invoice_id);
            return redirect()->route('invoices.index')->with('success', trans('form.invoice.payment_done'));
        } catch (\Throwable $th) {
            return redirect()->route('invoices.index')->with('error', trans('form.error') . ' ( ' . $th->getMessage() . ' )');
        }
    }

    public function doPartialPayment($request, $invoiceId = NULL)
    {
        if(!is_null($invoiceId)){
            $invoiceId = $invoiceId;
        } else {
            $invoiceId = $request['invoice_id'];
        }   
        $invoice = Invoice::where('id', $invoiceId)->with('investigation')->first();
        
        $userData = $invoice->client->user;
        
        $invoiceItem = $invoice->invoiceitems->toArray();

        $investigations = $invoice->investigation->pluck('id')->toArray();

        if(!is_null($invoice->amount)){
            $paritalAmount = $invoice->parital_amount + $request['parital_amount'];
            
            if($paritalAmount == $invoice->amount) {
                Invoice::where('id', $invoiceId)->update(['paid_by' => $request['uploaded_by'], 'payment_status' => 'full', 'amount' => $invoice->amount, 'parital_amount' => NULL, 'status' => 'paid', 'received_date' => ($request['received_date'] ?? NULL), 'paid_date' => ($request['paid_date'] ?? NULL), 'payment_mode_id' => ($request['payment_mode_id'] ?? NULL),  'bank_details' => ($request['bank_details'] ?? NULL), 'admin_notes' => ($request['admin_notes'] ?? NULL)]);

                PerformaInvoice::where('invoice_id',$invoice->id)->update(['paid_by' => Auth::user()->id, 'status' => 'paid', 'payment_status' => 'full']);

                Investigations::whereIn('id',$investigations)->update(['status' => 'Closed', 'status_hr' => trans('form.timeline_status.Closed', [], 'hr')]);
                
                // 
                $invno = 'INV' . AppHelper::genrateInvoiceNumber("\App\Invoice");
                $invoiceData = [ 'invoice_no' => $invno,
                                'client_id' =>  $invoice->client_id,
                                'amount' => $request['parital_amount'],
                                'tax' => $invoice->tax,
                                'delivery_cost' => $invoice->delivery_cost,
                                'client_payment_notes' => isset($request['notes'])?$request['notes']:NULL,
                                'status' => 'paid',
                                'paid_by' => Auth::id(),
                                'payment_status' => 'parital',
                                'received_date' => ($request['received_date'] ?? NULL), 
                                'paid_date' => ($request['paid_date'] ?? NULL), 
                                'payment_mode_id' => ($request['payment_mode_id'] ?? NULL),  
                                'bank_details' => ($request['bank_details'] ?? NULL), 
                                'admin_notes' => ($request['admin_notes'] ?? NULL),
                                'partial_invoice_id' => $invoice->id,
                            ];
                $newInv = Invoice::create($invoiceData);

                $newInvInvestigation = InvoiceInvstigations::where('invoice_id', $invoiceId)->get()->toArray();
                foreach($newInvInvestigation as $key => $oldInv) {
                    InvoiceInvstigations::create([
                        'invoice_id' => $newInv->id,
                        'investigation_id' => $newInvInvestigation[$key]['investigation_id']
                    ]);
                }
                $newInvDocs = InvoiceDocuments::where('invoice_id', $invoiceId)->get()->toArray();
                foreach($newInvDocs as $key => $oldInvDoc) {
                    unset($newInvDocs[$key]['id']);
                    $newInvDocs[$key]['invoice_id'] = $newInv->id;
                    $newInvDocs[$key]['created_at'] = now();
                    $newInvDocs[$key]['updated_at'] = now();
                    InvoiceDocuments::create([
                        'invoice_id' => $newInv->id,
                        'doc_name' => $newInvDocs[$key]['doc_name'],
                        'payment_note' => $newInvDocs[$key]['payment_note'],
                        'file_name' => $newInvDocs[$key]['file_name'],
                        'file_path' => $newInvDocs[$key]['file_path'],
                        'file_extension' => $newInvDocs[$key]['file_extension'],
                        'file_size' => $newInvDocs[$key]['file_size'],
                        'uploaded_by' => $newInvDocs[$key]['uploaded_by']
                    ]);
                }

                // 
                foreach ($investigations as $key => $investigation) {
                    $invtransdata = [
                        'event' => 'investigation_invoicestatus_changed',
                        'data' => json_encode(array('data' => array('id' => $invoice->id, 'type' => 'client','invoice', 'status' => 'Paid', 'parital_amount' => ''))),
                    ];
    
                    InvestigationTransition::addTransion($invtransdata, $investigation);
                }
            } else if($paritalAmount < $invoice->amount){
                
                Invoice::where('id', $invoiceId)->update(['paid_by' => $request['uploaded_by'], 'payment_status' => 'parital', 'amount' => $invoice->amount, 'parital_amount' => $paritalAmount]);
                
                $invno = 'INV' . AppHelper::genrateInvoiceNumber("\App\Invoice");
                $invoiceData = [ 'invoice_no' => $invno,
                                'client_id' =>  $invoice->client_id,
                                'amount' => $request['parital_amount'],
                                'tax' => $invoice->tax,
                                'delivery_cost' => $invoice->delivery_cost,
                                'client_payment_notes' => isset($request['notes'])?$request['notes']:NULL,
                                'status' => 'paid',
                                'paid_by' => Auth::id(),
                                'payment_status' => 'parital',
                                'received_date' => ($request['received_date'] ?? NULL), 
                                'paid_date' => ($request['paid_date'] ?? NULL), 
                                'payment_mode_id' => ($request['payment_mode_id'] ?? NULL),  
                                'bank_details' => ($request['bank_details'] ?? NULL), 
                                'admin_notes' => ($request['admin_notes'] ?? NULL),
                                'partial_invoice_id' => $invoice->id,
                            ];
                $newInv = Invoice::create($invoiceData);

                $newInvInvestigation = InvoiceInvstigations::where('invoice_id', $invoiceId)->get()->toArray();
                foreach($newInvInvestigation as $key => $oldInv) {
                    InvoiceInvstigations::create([
                        'invoice_id' => $newInv->id,
                        'investigation_id' => $newInvInvestigation[$key]['investigation_id']
                    ]);
                }
                $newInvDocs = InvoiceDocuments::where('invoice_id', $invoiceId)->get()->toArray();
                foreach($newInvDocs as $key => $oldInvDoc) {
                    unset($newInvDocs[$key]['id']);
                    $newInvDocs[$key]['invoice_id'] = $newInv->id;
                    $newInvDocs[$key]['created_at'] = now();
                    $newInvDocs[$key]['updated_at'] = now();
                    InvoiceDocuments::create([
                        'invoice_id' => $newInv->id,
                        'doc_name' => $newInvDocs[$key]['doc_name'],
                        'payment_note' => $newInvDocs[$key]['payment_note'],
                        'file_name' => $newInvDocs[$key]['file_name'],
                        'file_path' => $newInvDocs[$key]['file_path'],
                        'file_extension' => $newInvDocs[$key]['file_extension'],
                        'file_size' => $newInvDocs[$key]['file_size'],
                        'uploaded_by' => $newInvDocs[$key]['uploaded_by']
                    ]);
                }
                // InvoiceDocuments::create($newInvDocs);
                
                foreach ($investigations as $key => $investigation) {
                    $invtransdata = [
                        'event' => 'investigation_invoicestatus_changed',
                        'data' => json_encode(array('data' => array('id' => $invoice->id, 'type' => 'client','invoice', 'status' => 'Partial Paid', 'parital_amount' => $paritalAmount))),
                    ];
    
                    InvestigationTransition::addTransion($invtransdata, $investigation);
                }
            } else {
                return redirect()->route('invoice.show', [Crypt::encrypt($invoice->id), 'invoice'])->with('error', trans('form.invoice.payment.bigger_then_amount'));
            }
        } else {
            Invoice::where('id', $invoiceId)->update(['paid_by' => $request['uploaded_by'], 'payment_status' => 'parital', 'amount' => $invoice->amount, 'parital_amount' => $request['parital_amount'], 'received_date' => ($request['received_date'] ?? NULL), 'paid_date' => ($request['paid_date'] ?? NULL), 'payment_mode_id' => ($request['payment_mode_id'] ?? NULL),  'bank_details' => ($request['bank_details'] ?? NULL), 'admin_notes' => ($request['admin_notes'] ?? NULL)]);
            
            foreach ($investigations as $key => $investigation) {
                $invtransdata = [
                    'event' => 'investigation_invoicestatus_changed',
                    'data' => json_encode(array('data' => array('id' => $invoice->id, 'type' => 'client','invoice', 'status' => 'Partial Paid', 'parital_amount' => $request['parital_amount']))),
                ];

                InvestigationTransition::addTransion($invtransdata, $investigation);
            }
        }
        $invoiceData = Invoice::where('id', $invoiceId)->with('investigation')->first();
        Mail::to($invoiceData->client->user->email)->queue(new InvoiceUpdate($invoiceData, $invoiceData->client->user, Auth::user()));
        return true;
    }

    // Investigator invoice
    public function investigatorIndex()
    {
        return view('invoice.investigator.index');
    }

    public function invoiceListInvestigator(Request $request)
    {
        $investigatorId = AppHelper::getInvestigatorIdFromUserId(Auth::id());
        $invoices = InvestigatorInvoice::with(['investigator', 'investigator.user', 'investigation', 'investigation.user', 'investigation.clientinvoice'])->when(isInvestigator(), function ($query) use ($investigatorId) {
            return $query->where('investigator_id', $investigatorId);
        });
        
        return DataTables::of($invoices)
            ->addColumn('investigator_name', function ($invoice) {
                return !empty($invoice->investigator->user) ? $invoice->investigator->user->name : '';
            })
            ->addColumn('client_name', function ($invoice) {
                return !empty($invoice->investigation->user) ? $invoice->investigation->user->name : '';
            })
            ->addColumn('work_order_number', function ($invoice) {
                $work_order_number = "";
                if(!empty($invoice->investigation)){
                    $work_order_number .= '<span class="badge dt-badge badge-dark">'.$invoice->investigation->work_order_number.'</span> &nbsp;';
                }
                return $work_order_number;
            })
            ->editColumn('amount', function ($invoice) {
                if($invoice->status != 'paid'){
                    return !empty($invoice->amount) ? trans('general.money_symbol').($invoice->amount + $invoice->getInvestigationDocument->where('uploaded_by',$invoice->investigator->user->id)->sum('price')) : trans('general.money_symbol').'0';
                }else{
                    return !empty($invoice->amount) ? trans('general.money_symbol').($invoice->amount) : trans('general.money_symbol').'0';
                }
            })
            ->editColumn('status', function ($invoice) {
                if ($invoice->status) {
                    if ($invoice->status == 'paid') {
                        return '<td>
                            <span class="badge dt-badge badge-success">' . trans('form.timeline_status.'.ucwords($invoice->status)) . '</span>
                        </td>';
                    } else if ($invoice->status == 'pending') {
                        return '<td>
                            <span class="badge dt-badge badge-warning">' . trans('form.timeline_status.'.ucwords($invoice->status)) . '</span>
                        </td>';
                    } else {
                        return '<td>
                            <span class="badge dt-badge badge-dark">' . trans('form.timeline_status.'.ucwords($invoice->status)) . '</span>
                        </td>';
                    }
                }
                return "";
            })
            ->addColumn('isperforma', function ($invoice) {
                if (!empty($invoice->investigation->clientinvoice) && count($invoice->investigation->clientinvoice) > 0) {
                    return '<td>
                        <span class="badge dt-badge badge-success">' . trans('general.yes') . '</span>
                        </td>';
                } else {
                    return '<td>
                            <span class="badge dt-badge badge-danger">' . trans('general.no') . '</span>
                        </td>';
                }
                return "";
            })
            ->editColumn('created_at', function ($invoice) {
                return Carbon::parse($invoice->created_at)->format('d/m/Y');
            })
            ->addColumn('action', function ($invoice) {
                $btns = "<span class='d-inline-flex'>";
                //$invoiceId = Crypt::encrypt($invoice->id);
                $btns .= '<a href="' . route('investigator.invoices.show', [Crypt::encrypt($invoice->id),'invoice']) . '" class="dt-btn-action" title="' . trans('general.view') . '">
                                <i class="fas fa-money-bill-wave "></i>
                            </a>';
                $btns .= '</span>';
                return $btns;
            })
            ->rawColumns(['work_order_number','status', 'action', 'isperforma'])
            ->make(true);
    }

    public function showInvestigatorInvoice(Request $request, $id)
    {
        $invoice = InvestigatorInvoice::find(Crypt::decrypt($id));
        $investigation = Investigations::where('id', $invoice->investigation_id)->first();
        $clientId = AppHelper::getClientIdFromUserId($investigation->user_id);
        $clientInvoice = PerformaInvoice::where('investigation_id', $invoice->investigation_id)->where('client_id', $clientId)->first();
        
        $isSend = 1;
        if(!is_null($clientInvoice)){
            if($clientInvoice->status != 'pending' && is_null($clientInvoice->invoice_id)){
                $isSend = 0;    
            } else {
                $isSend = 1;
            }
        } else {
            $isSend = 0;
        }
        
        return view('invoice.investigator.invoice', compact('invoice', 'isSend'));
    }

    public function investigatorInvoicePay(Request $request)
    {
        try {
            if ($request->hasfile('file')) {
                $invoice = InvestigatorInvoice::where('id', $request->invoice_id)->first();
                foreach ($request->file as $key => $file) {
                    $filePath = public_path('investigator-invoice-pay-docs');
                    if (!file_exists($filePath)) {
                        mkdir($filePath, 0777, true);
                    }
                    $ext = $file->extension();
                    $size = $file->getSize();
                    $fileName = time() . '-' . trim($file->getClientOriginalName());
                    $originalName = $file->getClientOriginalName();
                    $fileId = 0;

                    $fileModel = new InvestigatorInvoiceDocuments();
                    $file->move($filePath . '/', $fileName);
                    $fileModel->invoice_id = $request->invoice_id;
                    $fileModel->payment_note = $request->notes;
                    $fileModel->doc_name = $originalName;
                    $fileModel->file_name = $fileName;
                    $fileModel->file_path = $filePath . '/' . $fileName;
                    $fileModel->file_extension = $ext;
                    $fileModel->file_size = $size;
                    $fileModel->uploaded_by = $request->uploaded_by;

                    $fileModel->save();
                    $invtransdata = [
                        'event' => 'investigation_markPaid_investigator',
                        'data' => json_encode(array('data' => array('id' => $request->invoice_id, 'type' => 'investigator_invoice_markaspaid', 'user_id' => $request->user_id, 'perform_by' => Auth::user()->name, 'invoice_no' => $invoice->invoice_no))),
                    ];
                    InvestigationTransition::addTransion($invtransdata, $request->investigation_id);
                    
                    $fileId = $fileModel->id;
                    
                }

                InvestigatorInvoice::where('id', $request->invoice_id)->update(['amount' => $request->amount,'client_payment_notes' => $request->notes, 'status' => 'paid', 'payment_status' => 'full', 'received_date' => ($request['received_date'] ?? NULL), 'paid_date' => ($request['paid_date'] ?? NULL), 'payment_mode_id' => ($request['payment_mode_id'] ?? NULL),  'bank_details' => ($request['bank_details'] ?? NULL), 'admin_notes' => ($request['admin_notes'] ?? NULL)]);

                Mail::to($invoice->investigator->user->email)->queue(new InvestigatorInvoiceUpdate($invoice, $invoice->investigator->user, Auth::user(), true));                
            }
            
            return response()->json(['status' => 'success', 'message' => trans('form.registration.investigation.document_uploaded'), 'data' => ["fileId" => 1]]);
            
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function showInvestigatorInvoices(Request $request)
    {
        try{
            $invoiceIds = explode(',', $request->invoice_id);
            if ($request->hasfile('file')) {
                
                foreach ($request->file as $key => $file) {
                    $filePath = public_path('investigator-invoice-pay-docs');
                    if (!file_exists($filePath)) {
                        mkdir($filePath, 0777, true);
                    }
                    $ext = $file->extension();
                    $size = $file->getSize();
                    $fileName = time() . '-' . trim($file->getClientOriginalName());
                    $originalName = $file->getClientOriginalName();
                    $fileId = 0;
                    $file->move($filePath . '/', $fileName);
                    foreach($invoiceIds as $invoiceId){

                        $invoice = InvestigatorInvoice::where('id', $invoiceId)->whereIn('status', ['pending','on-hold','requested'])->first();
                        if(!is_null($invoice)){
                            $fileModel = new InvestigatorInvoiceDocuments();
                        
                            $fileModel->invoice_id = $invoiceId;
                            $fileModel->payment_note = $request->notes;
                            $fileModel->doc_name = $originalName;
                            $fileModel->file_name = $fileName;
                            $fileModel->file_path = $filePath . '/' . $fileName;
                            $fileModel->file_extension = $ext;
                            $fileModel->file_size = $size;
                            $fileModel->uploaded_by = $request->uploaded_by;

                            $fileModel->save();

                            $invtransdata = [
                                'event' => 'investigation_markPaid_investigator',
                                'data' => json_encode(array('data' => array('id' => $invoiceId, 'type' => 'investigator_invoice_markaspaid', 'user_id' => $request->uploaded_by, 'perform_by' => Auth::user()->name, 'invoice_no' => $invoice->invoice_no))),
                            ];
                            InvestigationTransition::addTransion($invtransdata, $invoice->investigation_id);

                            InvestigatorInvoice::where('id', $invoiceId)->update(['client_payment_notes' => $request->notes, 'status' => 'paid', 'payment_status' => 'full', 'received_date' => ($request->received_date ?? NULL), 'paid_date' => ($request->paid_date ?? NULL), 'payment_mode_id' => ($request->payment_mode_id ?? NULL),  'bank_details' => ($request->bank_details ?? NULL), 'admin_notes' => ($request->admin_notes ?? NULL)]);
                    
                            Mail::to($invoice->investigator->user->email)->queue(new InvestigatorInvoiceUpdate($invoice, $invoice->investigator->user, Auth::user(), true));
                        }
                    }
                    
                    $fileId = $fileModel->id;
                    
                } 
                return response()->json(['status' => 'success', 'message' => trans('form.registration.investigation.document_uploaded'), 'data' => ["fileId" => 1]]);               
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    // End Investigator Invoice

    // Deliveryboy Invoice

    public function deliveryBoyIndex()
    {
        return view('invoice.deliveryboy.index');
    }

    public function invoiceListDeliveryboy(Request $request)
    {
        $deliveryboyId = AppHelper::getDeliveryboyIdFromUserId(Auth::id());
        $invoices = DeliveryboyInvoice::with(['deliveryboy', 'deliveryboy.user', 'investigation', 'investigation.user'])->when(isDeliveryboy(), function ($query) use ($deliveryboyId) {
            return $query->where('deliveryboy_id', $deliveryboyId);
        });
        
        return DataTables::of($invoices)
            ->addColumn('deliveryboy_name', function ($invoice) {
                return !empty($invoice->deliveryboy->user) ? $invoice->deliveryboy->user->name : '';
            })
            ->addColumn('client_name', function ($invoice) {
                return !empty($invoice->investigation->user) ? $invoice->investigation->user->name : '';
            })
            ->addColumn('work_order_number', function ($invoice) {
                $work_order_number = "";
                $work_order_number .= '<span class="badge dt-badge badge-dark">'.$invoice->investigation->work_order_number.'</span> &nbsp;';
                return $work_order_number;
            })
            ->editColumn('amount', function ($invoice) {
                if($invoice->status != 'paid'){
                    return !empty($invoice->amount) ? trans('general.money_symbol').($invoice->amount + $invoice->getInvestigationDocument->where('uploaded_by',$invoice->deliveryboy->user->id)->sum('price')) : trans('general.money_symbol').'0';
                }else{
                    return !empty($invoice->amount) ? trans('general.money_symbol').($invoice->amount) : trans('general.money_symbol').'0';
                }
            })
            ->editColumn('status', function ($invoice) {
                if ($invoice->status) {
                    if ($invoice->status == 'paid') {
                        return '<td>
                            <span class="badge dt-badge badge-success">' . trans('form.timeline_status.'.ucwords($invoice->status)) . '</span>
                        </td>';
                    } else if ($invoice->status == 'pending') {
                        return '<td>
                            <span class="badge dt-badge badge-warning">' . trans('form.timeline_status.'.ucwords($invoice->status)) . '</span>
                        </td>';
                    } else {
                        return '<td>
                            <span class="badge dt-badge badge-dark">' . trans('form.timeline_status.'.ucwords($invoice->status)) . '</span>
                        </td>';
                    }
                }
                return "";
            })
            ->editColumn('created_at', function ($invoice) {
                return Carbon::parse($invoice->created_at)->format('d/m/Y');
            })
            ->addColumn('action', function ($invoice) {
                $btns = "<span class='d-inline-flex'>";
                //$invoiceId = Crypt::encrypt($invoice->id);
                $btns .= '<a href="' . route('deliveryboy.invoices.show', [Crypt::encrypt($invoice->id),'invoice']) . '" class="dt-btn-action" title="' . trans('general.view') . '">
                                <i class="fas fa-money-bill-wave "></i>
                            </a>';
                $btns .= '</span>';
                return $btns;
            })
            ->rawColumns(['work_order_number','status', 'action'])
            ->make(true);
    }

    public function showDeliveryboyInvoice(Request $request, $id)
    {
        $invoice = DeliveryboyInvoice::find(Crypt::decrypt($id));
        return view('invoice.deliveryboy.invoice', compact('invoice'));
    }

    public function deliveryboyInvoicePay(Request $request)
    {
        try {
            if ($request->hasfile('file')) {
                $invoice = DeliveryboyInvoice::where('id', $request->invoice_id)->first();
                foreach ($request->file as $key => $file) {
                    $filePath = public_path('deliveryboy-invoice-pay-docs');
                    if (!file_exists($filePath)) {
                        mkdir($filePath, 0777, true);
                    }
                    $ext = $file->extension();
                    $size = $file->getSize();
                    $fileName = time() . '-' . trim($file->getClientOriginalName());
                    $originalName = $file->getClientOriginalName();
                    $fileId = 0;

                    $fileModel = new DeliveryboyInvoiceDocuments();
                    $file->move($filePath . '/', $fileName);
                    $fileModel->invoice_id = $request->invoice_id;
                    $fileModel->payment_note = $request->notes;
                    $fileModel->doc_name = $originalName;
                    $fileModel->file_name = $fileName;
                    $fileModel->file_path = $filePath . '/' . $fileName;
                    $fileModel->file_extension = $ext;
                    $fileModel->file_size = $size;
                    $fileModel->uploaded_by = $request->uploaded_by;

                    $fileModel->save();
                    $invtransdata = [
                        'event' => 'investigation_markPaid_investigator',
                        'data' => json_encode(array('data' => array('id' => $request->invoice_id, 'type' => 'deliveryboy_invoice_markaspaid', 'user_id' => $request->user_id, 'perform_by' => Auth::user()->name, 'invoice_no' => $invoice->invoice_no))),
                    ];
                    InvestigationTransition::addTransion($invtransdata, $request->investigation_id);
                    
                    $fileId = $fileModel->id;
                    
                }

                DeliveryboyInvoice::where('id', $request->invoice_id)->update(['amount' => $request->amount,'client_payment_notes' => $request->notes, 'status' => 'paid', 'payment_status' => 'full', 'received_date' => ($request->received_date ?? NULL), 'paid_date' => ($request->paid_date ?? NULL), 'payment_mode_id' => ($request->payment_mode_id ?? NULL),  'bank_details' => ($request->bank_details ?? NULL), 'admin_notes' => ($request->admin_notes ?? NULL)]);
                
                Mail::to($invoice->deliveryboy->user->email)->queue(new InvestigatorInvoiceUpdate($invoice, $invoice->deliveryboy->user, Auth::user(), false));                
            }
            
            return response()->json(['status' => 'success', 'message' => trans('form.registration.investigation.document_uploaded'), 'data' => ["fileId" => 1]]);
            
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    public function showDeliveryboyInvoices(Request $request)
    {
        try{
            $invoiceIds = explode(',', $request->invoice_id);
            if ($request->hasfile('file')) {
                
                foreach ($request->file as $key => $file) {
                    $filePath = public_path('deliveryboy-invoice-pay-docs');
                    if (!file_exists($filePath)) {
                        mkdir($filePath, 0777, true);
                    }
                    $ext = $file->extension();
                    $size = $file->getSize();
                    $fileName = time() . '-' . trim($file->getClientOriginalName());
                    $originalName = $file->getClientOriginalName();
                    $fileId = 0;
                    $file->move($filePath . '/', $fileName);
                    foreach($invoiceIds as $invoiceId){

                        $invoice = DeliveryboyInvoice::where('id', $invoiceId)->whereIn('status', ['pending','on-hold','requested'])->first();
                        if(!is_null($invoice)){
                            $fileModel = new DeliveryboyInvoiceDocuments();
                        
                            $fileModel->invoice_id = $invoiceId;
                            $fileModel->payment_note = $request->notes;
                            $fileModel->doc_name = $originalName;
                            $fileModel->file_name = $fileName;
                            $fileModel->file_path = $filePath . '/' . $fileName;
                            $fileModel->file_extension = $ext;
                            $fileModel->file_size = $size;
                            $fileModel->uploaded_by = $request->uploaded_by;

                            $fileModel->save();

                            $invtransdata = [
                                'event' => 'investigation_markPaid_investigator',
                                'data' => json_encode(array('data' => array('id' => $invoiceId, 'type' => 'deliveryboy_invoice_markaspaid', 'user_id' => $request->uploaded_by, 'perform_by' => Auth::user()->name, 'invoice_no' => $invoice->invoice_no))),
                            ];
                            InvestigationTransition::addTransion($invtransdata, $invoice->investigation_id);

                            DeliveryboyInvoice::where('id', $invoiceId)->update(['client_payment_notes' => $request->notes, 'status' => 'paid', 'payment_status' => 'full', 'received_date' => ($request['received_date'] ?? NULL), 'paid_date' => ($request['paid_date'] ?? NULL), 'payment_mode_id' => ($request['payment_mode_id'] ?? NULL),  'bank_details' => ($request['bank_details'] ?? NULL), 'admin_notes' => ($request['admin_notes'] ?? NULL)]);
                    
                            Mail::to($invoice->deliveryboy->user->email)->queue(new InvestigatorInvoiceUpdate($invoice, $invoice->deliveryboy->user, Auth::user(), true));
                        }
                    }
                    
                    $fileId = $fileModel->id;
                    
                } 
                return response()->json(['status' => 'success', 'message' => trans('form.registration.investigation.document_uploaded'), 'data' => ["fileId" => 1]]);               
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    // End Deliveryboy Invoice

    public function notifyUnpaidInvoice(Request $request)
    {
        $invoices = PerformaInvoice::select('id', 'invoice_no', 'status', 'investigation_id', 'invoice_id', 'client_id', 'created_at')->with(['client' => function($q){
            $q->select('id', 'user_id', 'payment_term_id')->with(['user' => function($qq){
                $qq->select('id', 'name', 'email');
            }, 'paymentTerm' => function($qq){
                $qq->select('id', DB::raw("(CASE WHEN term_name='Immediately' THEN 0 WHEN term_name='Immediately + 15' THEN 15 WHEN term_name='Immediately + 30' THEN 30 WHEN term_name='Immediately + 60' THEN 60 WHEN term_name='Immediately + 90' THEN 90 END) as term_name"));
            }]);
        }, 'investigation' => function($q){
            $q->select('id', 'work_order_number');
        }])->whereNull('invoice_id')->where('status', 'pending')->get();

        $invoices->map(function($invoice){
            $invoiceDate = Carbon::parse($invoice->created_at);
            $days = $invoiceDate->diffInDays(Carbon::now());
            if(!is_null($invoice->client) && !is_null($invoice->client->paymentTerm)){
                if($invoice->client->paymentTerm->term_name == 0 && ($days > 0)){
                    $this->sendNotification('notify-unpaid-invoice', $invoice, 'once');
                } else if($invoice->client->paymentTerm->term_name == 15 && ($days > 15)) {
                    $this->sendNotification('notify-unpaid-invoice', $invoice, 'once');
                } else if($invoice->client->paymentTerm->term_name == 30 && ($days > 30)) {
                    $this->sendNotification('notify-unpaid-invoice', $invoice, 'once');
                } else if($invoice->client->paymentTerm->term_name == 60 && ($days > 60)) {
                    $this->sendNotification('notify-unpaid-invoice', $invoice, 'once');
                } else if($invoice->client->paymentTerm->term_name == 90 && ($days > 90)) {
                    $this->sendNotification('notify-unpaid-invoice', $invoice, 'once');
                }
            }
        });
        return response()->json([
            'status' => 'success',
            'result' => trans('general.successfully_sent_mail', [], 'en')
        ]);
    }

    public function sendNotification($event, $invoice, $type)
    {
        $invoiceDate = Carbon::parse($invoice['created_at']);
        $days = $invoiceDate->diffInDays(Carbon::now());
        $invtransdata = [
            'event' => 'notify-unpaid-invoice',
            'data' => json_encode(array('data' => array('id' => $invoice['id'], 'invoice_no' => $invoice['invoice_no'], 'user_id' => $invoice['client']['user']['id'], 'days' => $days, 'type' => $type))),
        ];
        InvestigationTransition::addTransion($invtransdata, $invoice['investigation_id']);

        Mail::to($invoice['client']['user']['email'])->queue(new InvoiceAlert($invoice));
    }
}
