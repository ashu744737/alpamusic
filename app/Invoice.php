<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;
    public $table = 'invoices';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'investigation_id',
        'client_id',
        'invoice_no',
        'paid_by',
        'amount',
        'tax',
        'delivery_cost',
        'client_payment_notes',
        'status',
        'payment_status',
        'discount_amount',
        'parital_amount',
        'received_date',
        'paid_date',
        'payment_mode_id',
        'bank_details',
        'admin_notes',
        'partial_invoice_id',
    ];
    public function invoiceitems()
    {
        return $this->hasMany(InvoiceItems::class, 'invoice_id');
    }

    public function invoicedocs()
    {
        return $this->hasMany(InvoiceDocuments::class, 'invoice_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id')->with('user');
    }

    public function performaInvoice()
    {
        return $this->hasOne(PerformaInvoice::class);
    }

    public function investigation()
    {
        return $this->belongsToMany(Investigations::class, 'invoice_investigations','invoice_id', 'investigation_id');
    }

    public function payment_mode()
    {
        return $this->belongsTo(PaymentMode::class, 'payment_mode_id');
    }

    public function partialInvoice()
    {
        return $this->belongsTo(Invoice::class, 'partial_invoice_id', 'id');
    }
}
