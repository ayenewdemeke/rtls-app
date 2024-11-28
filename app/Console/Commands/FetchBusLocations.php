<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Events\BusLocationUpdated;
use App\Models\MtaBusLocation;
use Carbon\Carbon;

class FetchBusLocations extends Command
{
    protected $signature = 'fetch:bus-locations';

    protected $description = 'Fetch bus locations from the MTA Bus Time API';

    public function handle()
    {
        // Fetch bus locations twice per minute with a 30-second interval
        $this->fetchBusLocations();

        // Sleep for 30 seconds
        sleep(30);

        $this->fetchBusLocations();
    }

    protected function fetchBusLocations()
    {
        $apiKey = env('MTA_API_KEY'); // Store your MTA API key in .env
        $lineRef = 'MTA NYCT_B1';

        $response = Http::get('http://bustime.mta.info/api/siri/vehicle-monitoring.json', [
            'key' => $apiKey,
            'LineRef' => $lineRef,
            'VehicleMonitoringDetailLevel' => 'calls',
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $vehicles = data_get($data, 'Siri.ServiceDelivery.VehicleMonitoringDelivery.0.VehicleActivity', []);

            foreach ($vehicles as $vehicle) {
                $bus_id = data_get($vehicle, 'MonitoredVehicleJourney.VehicleRef');
                $latitude = data_get($vehicle, 'MonitoredVehicleJourney.VehicleLocation.Latitude');
                $longitude = data_get($vehicle, 'MonitoredVehicleJourney.VehicleLocation.Longitude');
                $timestamp = data_get($vehicle, 'RecordedAtTime'); // ISO8601 format

                if ($bus_id && $latitude && $longitude && $timestamp) {
                    // Prepare data
                    $data = [
                        'bus_id' => $bus_id,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'timestamp' => Carbon::parse($timestamp)->format('Y-m-d H:i:s'),
                    ];

                    // Save to the database
                    $busLocation = MtaBusLocation::create($data);

                    // Broadcast the BusLocationUpdated event
                    broadcast(new BusLocationUpdated($busLocation));
                }
            }

            $this->info('Bus locations updated successfully.');
        } else {
            $this->error('Failed to fetch bus data from MTA API');
            Log::error('Failed to fetch bus data from MTA API', ['response' => $response->body()]);
        }
    }
}
