<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvestigationEmail extends Model
{

    protected $table = 'investigation_emails';

    protected $fillable = [
        'investigation_id',
        'value',
        'email_type',
    ];

    public function investigation()
    {
        return $this->belongsTo(Investigations::class, 'investigation_id');
    }
}
