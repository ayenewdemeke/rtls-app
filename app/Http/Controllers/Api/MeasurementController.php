<?php

namespace App\Http\Controllers\Api;

use App\Events\MeasurementUpdated;
use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\FusedMeasurement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MeasurementController extends Controller
{
    public function index()
    {
        return FusedMeasurement::all();
    }

    public function showByDevice($device_id)
    {
        $device = Device::where('device_id', $device_id)->firstOrFail();
        return FusedMeasurement::where('device_id', $device->id)->get();
    }

    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'device_id' => 'required|string|exists:devices,device_id',  // user-assigned device ID
            'time' => 'required|numeric',
            'position_x' => 'required|numeric',
            'position_y' => 'required|numeric',
            'velocity_x' => 'required|numeric',
            'velocity_y' => 'required|numeric',
        ]);

        // Find the actual device row by the user-assigned device_id
        $device = Device::where('device_id', $validated['device_id'])->firstOrFail();

        // Convert UNIX timestamp to a MySQL date-time format
        $formattedTime = Carbon::createFromTimestamp($validated['time'])->format('Y-m-d H:i:s');

        // Create a new FusedMeasurement record
        $fusedMeasurement = FusedMeasurement::create([
            'device_id'   => $device->id,
            'time'        => $formattedTime,
            'position_x'  => $validated['position_x'],
            'position_y'  => $validated['position_y'],
            'velocity_x'  => $validated['velocity_x'],
            'velocity_y'  => $validated['velocity_y'],
        ]);

        Log::info('FusedMeasurement data stored successfully', ['fused_measurement_id' => $fusedMeasurement->id]);

        // Broadcast the event for real-time updates
        broadcast(new MeasurementUpdated($fusedMeasurement));

        return response()->json(['status' => 'success', 'fused_measurement_id' => $fusedMeasurement->id], 200);
    }
}
