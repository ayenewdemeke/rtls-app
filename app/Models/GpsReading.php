<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GpsReading extends Model
{
    protected $fillable = [
        'device_id',
        'time',
        'gps_x',
        'gps_y',
    ];
}
