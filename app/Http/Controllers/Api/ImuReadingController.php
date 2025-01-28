<?php

namespace App\Http\Controllers\Api;

use App\Events\ImuReadingUpdated;
use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\ImuReading;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ImuReadingController extends Controller
{
    public function index()
    {
        return ImuReading::all();
    }

    public function showByDevice($mac_address)
    {
        $device = Device::where('mac_address', $mac_address)->firstOrFail();
        return ImuReading::where('device_id', $device->id)->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mac_address' => 'required|string|exists:devices,mac_address',
            'time' => 'required|numeric',
            'imu_acceleration_x' => 'required|numeric',
            'imu_acceleration_y' => 'required|numeric',
            'imu_acceleration_z' => 'required|numeric',
            'imu_angular_velocity_x' => 'required|numeric',
            'imu_angular_velocity_y' => 'required|numeric',
            'imu_angular_velocity_z' => 'required|numeric',
        ]);

        // Find the device by its string-based ID field
        $device = Device::where('mac_address', $validated['mac_address'])->firstOrFail();

        // Convert UNIX timestamp to MySQL date-time
        $formattedTime = Carbon::createFromTimestamp($validated['time'])->format('Y-m-d H:i:s');

        // Create the IMU reading record with the MAC address
        $imuReading = ImuReading::create([
            'device_id' => $device->id,
            'time' => $formattedTime,
            'imu_acceleration_x' => $validated['imu_acceleration_x'],
            'imu_acceleration_y' => $validated['imu_acceleration_y'],
            'imu_acceleration_z' => $validated['imu_acceleration_z'],
            'imu_angular_velocity_x' => $validated['imu_angular_velocity_x'],
            'imu_angular_velocity_y' => $validated['imu_angular_velocity_y'],
            'imu_angular_velocity_z' => $validated['imu_angular_velocity_z'],
        ]);

        // Broadcast the ImuReadingUpdated event
        broadcast(new ImuReadingUpdated($imuReading));

        return response()->json(['status' => 'success', 'reading_id' => $imuReading->id], 200);
    }
}
