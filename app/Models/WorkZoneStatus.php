<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkZoneStatus extends Model
{
    use HasFactory;

    public function work_zones()
    {
        return $this->hasMany(WorkZone::class);
    }
}
