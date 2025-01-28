<?php

namespace App\Events;

use App\Models\GpsReading;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GpsReadingUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $gpsReading;

    public function __construct(GpsReading $gpsReading)
    {
        $this->gpsReading = $gpsReading;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('gps-reading-updates'),
        ];
    }
}
