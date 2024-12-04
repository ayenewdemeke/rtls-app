<?php

namespace App\Http\Controllers\WorkZone;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\WorkZone;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function index($id)
    {
        $work_zone = WorkZone::findOrFail($id);
        $incidents = $work_zone->incidents()->with(['incident_type', 'devices'])->get();
        return inertia('WorkZones/WorkZone/Incidents/Index')->with([
            'work_zone' => $work_zone,
            'incidents' => $incidents
        ]);
    }
}
