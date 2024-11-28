<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BusLocationUpdated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $busLocation;

    public function __construct($busLocation)
    {
        $this->busLocation = $busLocation;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('bus-location-updates');
    }
}
