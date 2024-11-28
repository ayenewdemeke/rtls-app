<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_zone_id',
        'name',
        'latitude',
        'longitude',
        'location',
        'start_date',
        'work_zone_status_id',
        'description',
        'image',
        'system_id',
        'system_status_id',
        'system_start_date',
    ];

    public function work_zone_status()
    {
        return $this->belongsTo(WorkZoneStatus::class);
    }
}
