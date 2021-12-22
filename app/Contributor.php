<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contributor extends Model
{
    use softDeletes;
    
    public $table = 'contributors';

    protected $guarded = [];
   
    public function contributors()
    {
        return $this->belongsToMany(Contributor::class, 'title_contributors', 'contributor_id', 'title_id');
    }

    public function types()
    {
        return $this->belongsToMany('App\Type','contributor_types','contributor_id','type_id');

    }
}
