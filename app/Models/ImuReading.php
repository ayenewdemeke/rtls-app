<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImuReading extends Model
{
    protected $fillable = [
        'device_id',
        'time',
        'imu_acceleration_x',
        'imu_acceleration_y',
        'imu_acceleration_z',
        'imu_angular_velocity_x',
        'imu_angular_velocity_y',
        'imu_angular_velocity_z',
    ];
}
