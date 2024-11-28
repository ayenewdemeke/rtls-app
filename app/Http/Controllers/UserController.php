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
        $googleMapsApiKey = env('GOOGLE_MAPS_API_KEY');
        return inertia('WorkZones/Map')->with([
            'workZones' => $workZones,
            'googleMapsApiKey' => $googleMapsApiKey,
        ]);
    }
}
