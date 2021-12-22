<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryArea extends Model
{
    public $table = 'delivery_areas';


    /**
     * The Delivery Areas that belongs to the many Delivery boy users.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'deliveryboy_areas', 'delivery_area_id', 'user_id');
    }
}
