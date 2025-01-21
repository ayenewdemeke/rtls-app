<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index()
    {
        return Device::all();
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'mac_address' => 'required|string|unique:devices,mac_address',
            'device_type_id' => 'integer|required|exists:device_types,id',
            'work_zone_id' => 'integer|required|exists:work_zones,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'device_status_id' => 'integer|required|exists:device_statuses,id',
            'description' => 'nullable',
        ]);

        // Create the device record
        $device = Device::create($validated);

        // Return the created device as a JSON response
        return response()->json([
            'status' => 'success',
            'device' => $device,
        ], 201);
    }
}
