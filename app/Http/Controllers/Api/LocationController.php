<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Events\LocationUpdated;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    public function store(Request $request)
    {
        // Map Traccar field names to your database field names
        $data = [
            'latitude' => $request->input('lat'),
            'longitude' => $request->input('lon'),
            'speed' => $request->input('speed'),
            'bearing' => $request->input('bearing'),
            'altitude' => $request->input('altitude'),
            'timestamp' => $request->input('timestamp'),
        ];

        // Validate and convert Unix timestamp to MySQL format
        $validated = validator($data, [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'speed' => 'nullable|numeric',
            'bearing' => 'nullable|numeric',
            'altitude' => 'nullable|numeric',
            'timestamp' => 'required|numeric',
        ])->validate();

        // Convert Unix timestamp to MySQL format
        $validated['timestamp'] = Carbon::createFromTimestamp($validated['timestamp'])->format('Y-m-d H:i:s');

        // Save to the database
        $location = Location::create($validated);

        // Broadcast the LocationUpdated event
        broadcast(new LocationUpdated($location));

        return response()->json(['status' => 'success'], 200);
    }
}
