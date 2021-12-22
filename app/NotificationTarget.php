<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationTarget extends Model
{
    public $table = 'notification_target';

    protected $fillable = [
        'notification_id',
        'user_id',
        'is_read',
        'read_at'
    ];


    public function notification()
    {
        return $this->belongsTo(UserNotification::class, 'notification_id');
    }
}
