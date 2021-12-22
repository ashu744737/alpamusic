<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TitleCategory extends Model
{
    use HasFactory;

    protected $table = 'title_categories';

    protected $guarded = [];

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
