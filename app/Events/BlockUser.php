<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BlockUser implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $blockedUser;
    public $blockByUser;
    /**
     * Create a new event instance.
     */
    public function __construct($blockedUser, $blockByUser)
    {
        $this->blockedUser = $blockedUser;
        $this->blockByUser = $blockByUser;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('my-channel3'),
        ];
    }

    public function broadcastAs()
    {
        return 'block-user';
    }
}
