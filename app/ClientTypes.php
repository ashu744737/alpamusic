<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientTypes extends Model
{
    use SoftDeletes;
    public $table = 'client_types';

    /**
     * get relation with clients table.
     *
     */
    public function client()
    {
        return $this->hasMany(Client::class, 'client_type_id', 'id');
    }
}
