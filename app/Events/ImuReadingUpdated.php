<?php

namespace App\Events;

use App\Models\ImuReading;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImuReadingUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $imuReading;

    public function __construct(ImuReading $imuReading)
    {
        $this->imuReading = $imuReading;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('imu-reading-updates'),
        ];
    }
}
