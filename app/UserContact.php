<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserContact extends Model
{
    protected $fillable = [
        'user_type_id',
        'user_id',
        'contact_type_id',
        'is_default',
        'first_name',
        'last_name',
        'family',
        'workplace',
        'phone',
        'mobile',
        'fax',
        'email',
        'contact_type',
        'primary_email',
        'secondary_email',
    ];

    public function userType()
    {
        return $this->belongsTo(UserTypes::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contactType()
    {
        return $this->belongsTo(ContactTypes::class, 'contact_type_id');
    }
}
