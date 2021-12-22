<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryBoys extends Model
{
    public $table = 'deliveryboys';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'family',
        'idnumber',
        'dob',
        'website',
    ];

    /**
     * The area that belong to the deliveryboys.
     */
    public function delivery_areas()
    {
        return $this->belongsToMany(DeliveryArea::class, 'deliveryboy_areas');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function deliveryboyBankDetail()
    {
        return $this->hasOne(DeliveryboyBankDetail::class, 'deliveryboy_id', 'id');
    }



    /**
     * The area that belong to the deliveryboys.
     */
    public function areas()
    {
        return $this->belongsToMany(DeliveryArea::class, 'deliveryboy_areas');
    }

    /**
     * The Relation with deliveryboy_bank_details table.
     */
    public function bank_details()
    {
        return $this->hasOne(DeliveryboyBankDetail::class, 'deliveryboy_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'deliveryboy_products', 'deliveryboy_id', 'product_id')->withPivot('price');
    }

    public function investigations()
    {
        return $this->hasMany(DeliveryboyInvestigations::class, 'deliveryboy_id');
    }
    public function invoice()
    {
        return $this->hasMany(DeliveryboyInvoice::class, 'deliveryboy_id');
    }
}
