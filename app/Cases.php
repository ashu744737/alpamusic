<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cases extends Model
{
    public $table = 'cases';

    protected $fillable = [
        'status'
    ];

    /**
     * get relation with investigation table.
     *
     */
    public function investigations()
    {
        return $this->hasMany(Investigations::class, 'case_id', 'id');
    }
}
