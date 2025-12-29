<?php

namespace App\Entities\Chats;

use App\Entities\ChatRooms\ChatRoom;
use App\Models\User;
use Hotpop\Formation\Entities\GeneratesFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{

    use HasFactory;

    protected $fillable = [
        'chat_room_id',
        'sender_id',
        'message',
        'delete_for_me',
        'deleted_at',
        'read_status',

    ];

    protected $searchable = [
        'name'
    ];

    protected $visible = [
        "id",
        "sender_id",
        "chat_room_id",
        "message",
        "read_status",
        // "delete_for_me",
        "send_time",
        "created_at"
    ];

    protected $appends = [
        'send_time',
    ];

    protected $with = [
        // 'user',
        // 'chatRoom'
    ];


    public function getExtraApiFields(): array
    {
        return [
            'id'           => 'number',
            'sender_id'    => 'number',
            'chat_room_id' => 'number',
        ];
    }

    /**
     *
     * Add any update only validation rules for this model
     *
     * @return array
     */
    public function getCreateRules()
    {
        return [
            'name' => 'required',
        ];
    }

    /**
     *
     * Add any update only validation rules for this model
     *
     * @return array
     */
    public function getUpdateRules()
    {
        return [
            'name' => 'required',
        ];
    }

    /**
     *
     * Add any update only validation messations
     *
     * @return array
     */
    public function getCreateValidationMessages()
    {
        return [];
    }

    /**
     *
     * Add any update only validation messations
     *
     * @return array
     */
    public function getUpdateValidationMessages()
    {
        return [];

    }

    public function getSendTimeAttribute()
    {
        $user = auth()->user();

        return \Carbon\Carbon::parse($this->created_at)
            ->setTimezone($user->timezone ?? config('app.timezone'))
            ->format('h:i a');
        // return $this->created_at->format('h:i a');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class, 'chat_room_id');
    }

}
