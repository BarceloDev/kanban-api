<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'picture_id',
        'title',
        'description',
        'status'
    ];

    public function picture()
    {
        return $this->belongsTo(Picture::class);
    }
}
