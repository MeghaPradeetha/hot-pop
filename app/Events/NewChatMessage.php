<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $senderId;
    public $receiverId;
    public $chatRoomId;
    /**
     * Create a new event instance.
     */
    public function __construct($senderId, $receiverId, $chatRoomId)
    {
        $this->senderId = $senderId;
        $this->receiverId = $receiverId;
        $this->chatRoomId = $chatRoomId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('channel-new-message'),
        ];
    }

    public function broadcastAs()
    {
        return 'new-chat-message';
    }
}
