<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserSignOut implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
public $userId;
public $active_status;
public $last_active;
    /**;
     * Create a new event instance.
     */
    public function __construct($userId,$active_status,$last_active)
    {
       
        $this->userId = $userId;
        $this->active_status = $active_status;
        $this->last_active = $last_active;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('my-channel1'),
        ];
    }

    public function broadcastAs(){
        return 'sign-out';
    }
}
