<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvestigationDocuments extends Model
{
    use SoftDeletes;
    public $table = 'investigation_documents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'investigation_id',
        'doc_name',
        'file_name',
        'file_path',
        'file_extension',
        'file_size',
        'uploaded_by',
        'document_typeid',
        'price',
        'share_to_client',
        'share_to_investigator',
        'share_to_delivery_boy'
    ];

    public function investigation()
    {
        return $this->belongsTo(Investigations::class, 'investigation_id');
    }

    public function documenttype()
    {
        return $this->belongsTo(DocumentTypes::class, 'document_typeid', 'id');
    }
    public function uploadedby()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
