<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class TicketMessages extends Model
{
    use SoftDeletes;
    public $table = 'ticket_messages';

    protected $fillable = [
        'user_id', 'ticket_id', 'message',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Tickets::class, 'ticket_id');
    }
}
