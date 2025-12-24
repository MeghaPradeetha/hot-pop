<?php

namespace App\Entities\ChatRooms;

use App\Models\User;
use App\Entities\Chats\Chat;
use App\Entities\Blocks\Block;
use Illuminate\Database\Eloquent\Model;
use EMedia\Devices\Auth\DeviceAuthenticator;
use EMedia\Formation\Entities\GeneratesFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use ElegantMedia\SimpleRepository\Search\Eloquent\SearchableLike;

class ChatRoom extends Model
{

    use HasFactory;
    use SearchableLike;
    use GeneratesFields;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'block_status',
        'deleted_at'
    ];

    protected $searchable = [
        'name'
    ];

    protected $editable = [
        'name',
    ];
    protected $appends = [
        'participant',
        'unread_message'
    ];
    protected $with = [
        // 'chats',
        'sender',
        'receiver',
    ];

    protected $visible = [
        'id',
        'block_status',
        'last_message',
        'sender_id',
        'unread_message',
        'participant',
        'last_message_time',
        'last_message_created',
        'lastChat',
    ];

    public function getExtraApiFields(): array
    {
        return [
            'id'             => 'number',
            'sender_id'      => 'number',
            'unread_message' => 'number',
            'participant'    => ['type' => 'object', 'items' => 'User'],
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

    public function getLastChatAttribute()
    {
        return $this->chats()->latest()->first();
    }

    public function getUnreadMessageAttribute():int
    {
        $user = request()->user();
        return $this->chats()->where('read_status', '0')->whereNot('sender_id', $user->id)->count();
    }

    public function getParticipantAttribute(): ?User
    {
        $userID = auth()->id() ?? DeviceAuthenticator::getUserByAccessToken()->id ?? null;
        if ($this->sender_id == $userID) {
            return $this->receiver;
        } elseif ($this->receiver_id == $userID) {
            return $this->sender;
        }

        return null;
    }


    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function lastChat()
    {
        return $this->hasOne(Chat::class)->latest();
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id')->withTrashed();
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id')->withTrashed();

    }



}
