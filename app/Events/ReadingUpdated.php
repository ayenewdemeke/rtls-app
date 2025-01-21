<?php

namespace App\Events;

use App\Models\Reading;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReadingUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reading;

    public function __construct(Reading $reading)
    {
        $this->reading = $reading;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('reading-updates'),
        ];
    }
}
