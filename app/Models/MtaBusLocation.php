<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MtaBusLocation extends Model
{
    protected $fillable = ['bus_id', 'latitude', 'longitude', 'timestamp'];
}
