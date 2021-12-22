<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceDocuments extends Model
{
    use SoftDeletes;
    public $table = 'invoice_documents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
        return $this->belongsTo(PerformaInvoice::class, 'invoice_id');
    }

    public function uploadedby()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
