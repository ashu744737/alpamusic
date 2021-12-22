<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    public $table = 'categories';

    /**
     * get relation with clients table.
     *
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'title_categories', 'category_id', 'title_id');
    }

}
