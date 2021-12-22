<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerformaInvoice extends Model
{
    use SoftDeletes;
    public $table = 'performa_invoices';
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
        'status',
        'invoice_id',
        'created_by',
    ];

    public function invoiceCreatedBy()
    {
        return $this->belongsTo(User::class, 'created_by')->with('user_type');
    }

    public function invoiceitems()
    {
        return $this->hasMany(InvoiceItems::class, 'invoice_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function invoicedocs()
    {
        return $this->hasMany(InvoiceDocuments::class, 'invoice_id');
    }

    public function newInvoicedocs()
    {
        return $this->hasMany(InvoiceDocuments::class, 'invoice_id','invoice_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id')->with(['user','client_type', 'paymentTerm']);
    }

    public function investigation()
    {
        return $this->belongsTo(Investigations::class, 'investigation_id');
    }
}
