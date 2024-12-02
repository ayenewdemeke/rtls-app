<?php

namespace App\Http\Controllers;

use App\Models\WorkZone;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function dashboard()
    {
        $workZones = WorkZone::with('work_zone_status')->get();
        return inertia('User/Dashboard')->with([
            'workZones' => $workZones
        ]);
    }

    public function map()
    {
        $workZones = WorkZone::all();
        $google_maps_api_key = config('services.google_maps.api_key');
        return inertia('WorkZones/Map')->with([
            'workZones' => $workZones,
            'google_maps_api_key' => $google_maps_api_key,
        ]);
    }
}
