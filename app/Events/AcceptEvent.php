<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AcceptEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $is_accept;
    public $action_user_id;
    public $notify_user_id;
    /**
     * Create a new event instance.
     */
    public function __construct(
        $is_accept,
        $action_user_id,
        $notify_user_id
    )
    {

        $this->is_accept = $is_accept; // true or false
        $this->action_user_id = $action_user_id;
        $this->notify_user_id = $notify_user_id;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('my-channel4'),
        ];
    }

    public function broadcastAs()
    {
        return 'call-accept';
    }
}
