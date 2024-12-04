<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    public function incident_type()
    {
        return $this->belongsTo(IncidentType::class);
    }

    public function devices()
    {
        return $this->belongsToMany(Device::class);
    }
}
