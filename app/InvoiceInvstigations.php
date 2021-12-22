<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceInvstigations extends Model
{
    public $table = 'invoice_investigations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_id',
        'investigation_id'
    ];
}
