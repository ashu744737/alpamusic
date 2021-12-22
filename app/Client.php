<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Client extends Model
{
    use LogsActivity;

    public $table = 'clients';

    protected static $logAttributes = ['credit_limit'];

    protected static $logOnlyDirty = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'client_type_id',
        'printname',
        'legal_entity_no',
        'website',
        'address',
        'address2',
        'city',
        'state',
        'zip_code',
        'phone',
        'mobile',
        'fax',
        'country_id',
        'contact_type_id',
        'payment_mode_id',
        'payment_term_id',
        'credit_limit'
    ];

    /**
     * get relation with client_types table.
     *
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * get relation with client_types table.
     *
     */
    public function client_type()
    {
        return $this->belongsTo(ClientTypes::class, 'client_type_id');
    }

    /**
     * get relation with contact_types table.
     *
     */
    public function contact_type()
    {
        return $this->belongsTo(ContactTypes::class, 'contact_type_id');
    }

    /**
     * Relation for payment Mode
     *
     */
    public function paymentMode()
    {
        return $this->belongsTo(PaymentMode::class, 'payment_mode_id');
    }

    /**
     * Relation for payment Term
     *
     */
    public function paymentTerm()
    {
        return $this->belongsTo(PaymentTerm::class, 'payment_term_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'client_products', 'client_id', 'product_id')->withPivot('price');
    }

    public function customers()
    {
        return $this->hasMany(ClientCustomer::class);
    }

    public function getUserAddress()
    {
        return $this->hasOne(UserAddress::class,'user_id','user_id');
    }
}
