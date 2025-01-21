<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FusedMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'time',
        'position_x',
        'position_y',
        'velocity_x',
        'velocity_y',
    ];
}
