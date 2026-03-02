<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Picture extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'deadline',
        'user_id',
        'access_token',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
