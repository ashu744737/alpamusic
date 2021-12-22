<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryFilesConnection extends Model
{
    public $table = 'history_files_connection';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id',
        'attach_date',
        'source_name', 
        'file_name',
        'comment',
        'is_delivered',
    ];

    public function subject()
    {
        return $this->belongsTo(Subjects::class, 'file_id');
    }
}
