<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Specialization extends Model
{
    use SoftDeletes;
    public $table = 'specializations';

    /**
     * The specializations that belongs to the many investigator users.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'investigator_specializations', 'specialization_id', 'user_id');
    }
}
