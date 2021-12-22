<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryboyInvoice extends Model
{
    use SoftDeletes;
    public $table = 'deliveryboy_invoices';
    
    protected $fillable = [
        'investigation_id',
        'deliveryboy_id',
        'invoice_no',
        'amount',
        'discount_amount',
        'parital_amount',
        'client_payment_notes',
        'status',
        'payment_status',
        'received_date',
        'paid_date',
        'payment_mode_id',
        'bank_details',
        'admin_notes',
    ];

    public function deliveryboy()
    {
        return $this->belongsTo(DeliveryBoys::class)->with('user');
    }

    public function investigation()
    {
        return $this->belongsTo(Investigations::class);
    }

    public function invoicedocument()
    {
        return $this->hasMany(DeliveryboyInvoiceDocuments::class, 'invoice_id');
    }

    public function getInvestigationDocument()
    {
        return $this->hasMany(InvestigationDocuments::class, 'investigation_id','investigation_id');
    }

    public function payment_mode()
    {
        return $this->belongsTo(PaymentMode::class, 'payment_mode_id');
    }
}
