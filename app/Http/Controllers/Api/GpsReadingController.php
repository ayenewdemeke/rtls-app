<?php

namespace App\Http\Controllers\Api;

use App\Events\GpsReadingUpdated;
use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\GpsReading;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GpsReadingController extends Controller
{
    public function index()
    {
        return GpsReading::all();
    }

    public function showByDevice($mac_address)
    {
        $device = Device::where('mac_address', $mac_address)->firstOrFail();
        return GpsReading::where('device_id', $device->id)->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mac_address' => 'required|string|exists:devices,mac_address',
            'time' => 'required|numeric',
            'gps_x' => 'required|numeric',
            'gps_y' => 'required|numeric',
        ]);

        // Find the device by its string-based ID field
        $device = Device::where('mac_address', $validated['mac_address'])->firstOrFail();

        // Convert UNIX timestamp to MySQL date-time
        $formattedTime = Carbon::createFromTimestamp($validated['time'])->format('Y-m-d H:i:s');

        // Create the GPS reading record with the MAC address
        $gpsReading = GpsReading::create([
            'device_id' => $device->id,
            'time' => $formattedTime,
            'gps_x' => $validated['gps_x'],
            'gps_y' => $validated['gps_y'],
        ]);

        // Broadcast the GpsReadingUpdated event
        broadcast(new GpsReadingUpdated($gpsReading));

        return response()->json(['status' => 'success', 'reading_id' => $gpsReading->id], 200);
    }
}
