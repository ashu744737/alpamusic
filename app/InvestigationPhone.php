<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvestigationPhone extends Model
{
    protected $table = 'investigation_phones';

    protected $fillable = [
        'investigation_id',
        'value',
        'type',
        'phone_type',
    ];

    public function investigation()
    {
        return $this->belongsTo(Investigations::class, 'investigation_id');
    }
}
