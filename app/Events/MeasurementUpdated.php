<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MeasurementUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $fusedMeasurement;

    public function __construct($fusedMeasurement)
    {
        $this->fusedMeasurement = $fusedMeasurement;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('measurement-updates'),
        ];
    }
}
