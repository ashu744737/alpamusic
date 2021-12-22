<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    public $table = 'notifications';

    protected $fillable = [
        'investigation_id',
        'event',
        'message',
        'hr_message',
        'data',
        'with_link',
        'redirect_link',
        'perform_by'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function investigation()
    {
        return $this->belongsTo(Investigations::class, 'investigation_id');
    }
    public function performby()
    {
        return $this->belongsTo(User::class, 'perform_by')->with('user_type');
    }

    public function targets()
    {
        return $this->hasMany(NotificationTarget::class, 'notification_id', 'id');
    }
}
