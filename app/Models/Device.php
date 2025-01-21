<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'device_type_id',
        'work_zone_id',
        'latitude',
        'longitude',
        'device_status_id',
        'description',
    ];

    public function device_type()
    {
        return $this->belongsTo(DeviceType::class);
    }

    public function device_status()
    {
        return $this->belongsTo(DeviceStatus::class);
    }
}
