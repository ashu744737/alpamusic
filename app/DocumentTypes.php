<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentTypes extends Model
{
    use SoftDeletes;
    public $table = 'document_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'hr_name', 'price', 'include_vat', 'created_at', 'updated_at',
    ];

    public function investigation_document(){
        return $this->hasMany(InvestigationDocuments::class);
    }
}
