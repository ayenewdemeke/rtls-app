<?php

namespace App\Http\Controllers\Api;

use App\Events\ReadingUpdated;
use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Reading;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReadingController extends Controller
{
    public function index()
    {
        return Reading::all();
    }

    public function showByDevice($device_id)
    {
        $device = Device::where('device_id', $device_id)->firstOrFail();
        return Reading::where('device_id', $device->id)->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_id' => 'required|string|exists:devices,device_id', // user-assigned device ID
            'time' => 'required|numeric',
            'gps_x' => 'required|numeric',
            'gps_y' => 'required|numeric',
            'imu_acceleration_x' => 'required|numeric',
            'imu_acceleration_y' => 'required|numeric',
            'imu_acceleration_z' => 'required|numeric',
            'imu_angular_velocity_x' => 'required|numeric',
            'imu_angular_velocity_y' => 'required|numeric',
            'imu_angular_velocity_z' => 'required|numeric',
        ]);

        // Find the device by its string-based ID field
        $device = Device::where('device_id', $validated['device_id'])->firstOrFail();

        // Convert UNIX timestamp to MySQL date-time
        $formattedTime = Carbon::createFromTimestamp($validated['time'])->format('Y-m-d H:i:s');

        // Create the reading record with the numeric device ID
        $reading = Reading::create([
            'device_id' => $device->id,
            'time' => $formattedTime,
            'gps_x' => $validated['gps_x'],
            'gps_y' => $validated['gps_y'],
            'imu_acceleration_x' => $validated['imu_acceleration_x'],
            'imu_acceleration_y' => $validated['imu_acceleration_y'],
            'imu_acceleration_z' => $validated['imu_acceleration_z'],
            'imu_angular_velocity_x' => $validated['imu_angular_velocity_x'],
            'imu_angular_velocity_y' => $validated['imu_angular_velocity_y'],
            'imu_angular_velocity_z' => $validated['imu_angular_velocity_z'],
        ]);

        Log::info('Reading data stored successfully', ['reading_id' => $reading->id]);

        // Broadcast the ReadingUpdated event
        broadcast(new ReadingUpdated($reading));

        return response()->json(['status' => 'success', 'reading_id' => $reading->id], 200);
    }
}
