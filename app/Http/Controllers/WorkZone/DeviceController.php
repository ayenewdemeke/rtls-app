<?php

namespace App\Http\Controllers\WorkZone;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DeviceStatus;
use App\Models\DeviceType;
use App\Models\WorkZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DeviceController extends Controller
{
    public function index($id)
    {
        $work_zone = WorkZone::findOrFail($id);
        $devices = $work_zone->devices()->with(['device_type', 'device_status'])->get();
        return inertia('WorkZones/WorkZone/Devices/Index')->with([
            'work_zone' => $work_zone,
            'devices' => $devices,
            'can' => [
                'manage_device' => Gate::allows('manage', Device::class),
            ],
        ]);
    }

    public function create($id)
    {
        Gate::authorize('manage', Device::class);

        $work_zone = WorkZone::findOrFail($id);
        $device_types = DeviceType::all();
        $device_statuses = DeviceStatus::all();
        return inertia('WorkZones/WorkZone/Devices/Create')->with([
            'work_zone' => $work_zone,
            'device_types' => $device_types,
            'device_statuses' => $device_statuses,
        ]);
    }

    public function store($id, Request $request)
    {
        Gate::authorize('manage', Device::class);

        $validated = $request->validate([
            'mac_address' => 'string|required|unique:devices,mac_address',
            'device_type_id' => 'integer|required',
            'device_status_id' => 'integer|required',
            'description' => 'nullable',
        ]);

        $device = new Device($validated);
        $device->work_zone_id = $id;
        $device->save();

        return redirect(route('user.work_zone.devices.index', $id));
    }

    public function show($id)
    {
        $work_zone = WorkZone::with('work_zone_status')->findOrFail($id);
        return inertia('WorkZones/Show')->with([
            'work_zone' => $work_zone,
        ]);
    }
}
