<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $table = 'user_addresses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_type_id',
        'user_id',
        'address1',
        'address2',
        'country_id',
        'state',
        'city',
        'street',
        'number',
        'zipcode'
    ];

    public function userType()
    {
        return $this->belongsTo(UserTypes::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
