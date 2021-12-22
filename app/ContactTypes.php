<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactTypes extends Model
{
    use SoftDeletes;
    public $table = 'contact_types';

    protected $fillable = [
        'type_name',
        'hr_type_name',
        'type',
    ];


    /**
     * get relation with clients table.
     *
     */
    public function contacttypes()
    {
        return $this->hasMany(Client::class, 'contact_type_id', 'id');
    }
}
