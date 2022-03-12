<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'last_name',
        'first_name',
        'birthday',
        'place_birth_day',
        'nationality',
        'state',
        'email',
        'phone',
        'adress',
    ];
}
