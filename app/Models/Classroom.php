<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPSTORM_META\map;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'part',
        'level',
        'option_id'
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
