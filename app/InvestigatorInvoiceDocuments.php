<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvestigatorInvoiceDocuments extends Model
{
    use SoftDeletes;
    public $table = 'investigator_invoice_documents';

    protected $fillable = [
        'invoice_id',
        'doc_name',
        'payment_note',
        'file_name',
        'file_path',
        'file_extension',
        'file_size',
        'uploaded_by',
    ];

    public function invoice()
    {
        return $this->belongsTo(InvestigatorInvoice::class, 'invoice_id');
    }

    public function uploadedby()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
