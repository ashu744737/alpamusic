<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    public $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'price', 'is_delivery', 'delivery_cost', 'spouse_cost', 'category_id',
    ];

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_products', 'product_id', 'client_id');
    }

    public function investigations()
    {
        return $this->hasMany(Investigations::class, 'type_of_inquiry');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function documents()
    {
        return $this->hasMany(ProductDocuments::class, 'product_id');
    }
}
