<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCategories extends Model
{
    protected $table = 'user_categories';

    protected $fillable = [
        'user_id',
        'category_id',
        'created_at',
        'updated_at',

    ];


    /**
     * getUserCategories.
     * @param 
     *  @return array
     */
    static function getUserCategories()
    {
        $usercategories = auth()->user()->userCategories;
        $catarr = array();
        foreach ($usercategories as $key) {
            $catarr[] = $key->category_id;
        }
        return $catarr;
    }

    /* public function category()
    {
        return $this->hasMany(Category::class, 'category_id');
    } */
}
