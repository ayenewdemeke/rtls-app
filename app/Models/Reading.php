<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reading extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'time',
        'gps_x',
        'gps_y',
        'imu_acceleration_x',
        'imu_acceleration_y',
        'imu_acceleration_z',
        'imu_angular_velocity_x',
        'imu_angular_velocity_y',
        'imu_angular_velocity_z',
    ];
}
