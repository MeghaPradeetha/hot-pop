<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IncomingCall implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $type;
    public $channelName;
    public $receiver_id;
    public $caller_name;
    public $caller_avatar;
    public $caller_id;
    /**
     * Create a new event instance.
     */
    public function __construct($type, $channelName, $receiver_id, $caller_name, $caller_avatar, $caller_id)
    {

        $this->type = $type; // audio or video
        $this->channelName = $channelName;
        $this->receiver_id = $receiver_id;
        $this->caller_name = $caller_name;
        $this->caller_avatar = $caller_avatar;
        $this->caller_id = $caller_id;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('my-channel5'),
        ];
    }

    public function broadcastAs()
    {
        return 'incoming-call';
    }
}
