<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryboyAreas extends Model
{
    public $table = 'deliveryboy_areas';

    protected $fillable = [
        'user_id',
        'delivery_area_id',
    ];

    /**
     * The deliveryboyarea that belong to the delivery area.
     */
    public function areas()
    {
        return $this->belongsTo(DeliveryArea::class, 'delivery_area_id');
    }
}
