<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\TtileCategory;

class Title extends Model
{
    use SoftDeletes;

    public $table = 'titles';

    // public function categories()
    // {
    //     return $this->hasMany(Category::class);
    // }
    public function titlecategories()
    {
        return $this->hasMany(TitleCategory::class,'title_id');
    }
    public function categories()
    {
        return $this->belongsToMany('App\Category','title_categories','title_id','category_id');
    }

    public function category(){
        return $this->belongsTo('App\Category');
    }

    public function user(){
        return $this->belongsTo('App\User','created_by_user_id');
    }
    public function contributors()
    {
        return $this->belongsToMany('App\Contributor','title_contributors','title_id','contributor_id');
    }
    public function owner(){
        return $this->belongsToMany('App\Contributor','title_owners','title_id','contributor_id');
    }
    public function files(){
        return $this->hasMany('App\TitleFiles','title_id');
    }
   
   
}
