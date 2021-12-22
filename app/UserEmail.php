<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserEmail extends Model
{
    protected $fillable = [
        'user_type_id',
        'user_id',
        'value'
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
