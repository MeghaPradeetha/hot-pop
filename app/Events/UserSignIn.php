<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserSignIn implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
public $active_status;
public $userId;

    /**
     * Create a new event instance.
     */
    public function __construct($userId,$active_status)
    {
       
        $this->active_status = $active_status;
        $this->userId = $userId;
       
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('my-channel'),
        ];
    }

    public function broadcastAs(){
        return 'sign-in';
    }
}
