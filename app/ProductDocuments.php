<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductDocuments extends Model
{
    public $table = 'product_documents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'doc_name',
        'doc_type',
        'file_name',
        'file_path',
        'uploaded_by',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function documenttype()
    {
        return $this->belongsTo(DocumentTypes::class, 'document_typeid');
    }

    public function uploadedby()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
