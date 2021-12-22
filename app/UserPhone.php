<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPhone extends Model
{
    protected $fillable = [
        'user_type_id',
        'user_id',
        'value',
        'type',
    ];

    public function userType()
    {
        return $this->belongsTo(UserTypes::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
