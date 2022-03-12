<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'designation',
        'short',
        'section_id'
    ];

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }
}
