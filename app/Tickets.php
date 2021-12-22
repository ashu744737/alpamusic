<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    use SoftDeletes;
    public $table = 'tickets';

    protected $fillable = [
        'investigation_id', 'user_id', 'subject', 'type', 'message', 'file', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function investigations()
    {
        return $this->belongsTo(Investigations::class, 'investigation_id');
    }
}
