<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceItems extends Model
{
    protected $table = 'invoice_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_id',
        'subject_id',
        'cost'
    ];

    public function subject()
    {
        return $this->belongsTo(Subjects::class, 'subject_id');
    }
}
