<?php

namespace App\Http\Controllers;

use App\Models\WorkZone;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function dashboard()
    {
        $work_zones = WorkZone::with('work_zone_status')->get();
        return inertia('User/Dashboard')->with([
            'work_zones' => $work_zones
        ]);
    }

    public function map()
    {
        $work_zones = WorkZone::all();
        return inertia('WorkZones/Map')->with([
            'work_zones' => $work_zones
        ]);
    }
}
