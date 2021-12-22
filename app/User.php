<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use SoftDeletes, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'type_id', 'verify_token', 'approved_at', 'approved_by', 'salary', 'referred_by',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * get relation with user_types table.
     *
     */
    public function user_type()
    {
        return $this->belongsTo(UserTypes::class, 'type_id');
    }

    /**
     * get Users types.
     *
     * @return array
     */
    static function users_dropdown()
    {
        return UserTypes::where('id', '>', 0)->pluck('type_name', 'id')->toarray();
    }


    /**
     * Get User data by token passed.
     * @param mixed $token
     * 
     */
    static function getUserveryfyByToken($token)
    {
        return User::select('id', 'email_verified_at', 'status')->where('verify_token', $token)->first();
    }

    /**
     * Make relation with clients table.
     *
     */
    public function client()
    {
        return $this->hasOne(Client::class);
    }

    /**
     * Make relation with investigators table.
     *
     */
    public function investigator()
    {
        return $this->hasOne(Investigators::class);
    }

    /**
     * Make relation with investigator_specializations table.
     *
     */
    public function investigatorspecilizations()
    {
        return $this->hasMany(InvestigatorSpecilization::class);
    }

    /**
     * Make relation with deliveryboys table.
     *
     */
    public function deliveryboy()
    {
        return $this->hasOne(DeliveryBoys::class);
    }

    /**
     * Make relation with deliveryboy_areas table.
     *
     */
    public function deliveryboyAreas()
    {
        return $this->hasMany(DeliveryboyAreas::class);
    }

    public function userAddresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function getuserFirstAddresses()
    {
        return $this->hasOne(UserAddress::class);
    }

    public function userContacts()
    {
        return $this->hasMany(UserContact::class);
    }

    public function userEmails()
    {
        return $this->hasMany(UserEmail::class);
    }

    public function userPhones()
    {
        return $this->hasMany(UserPhone::class);
    }

    /**
     * The investigator user belongs to the many specializations..
     */
    public function specializations()
    {
        return $this->belongsToMany(Specialization::class, 'investigator_specializations', 'user_id', 'specialization_id');
    }

    /**
     * The Deliveryboy user belongs to the many Delivery areas.
     */
    public function delivery_areas()
    {
        return $this->belongsToMany(DeliveryArea::class, 'deliveryboy_areas', 'user_id', 'delivery_area_id');
    }

    /**
     * Make relation with Investigation table.
     *
     */
    public function investigation()
    {
        return $this->hasOne(Investigations::class);
    }

    public function userCategories()
    {
        return $this->hasMany(UserCategories::class);
    }
}
