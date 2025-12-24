<?php

namespace App\Entities\ChatRooms;

use App\Models\User;
use App\Entities\BaseRepository;

class ChatRoomRepository extends BaseRepository
{

	public function __construct(ChatRoom $model)
	{
		parent::__construct($model);
	}

	public function getUserRoomList(int $userId)
	{
		return ChatRoom::where('deleted_at', null)
		->where(function ($query) use ($userId) {
			$query->where('sender_id', $userId)
				  ->orWhere('receiver_id', $userId);
		})->get();

	}

	public function getChatRoom(int $authID, int $userID)
	{
		return ChatRoom::where(function ($query) use ($authID)
		{
			$query->where('sender_id', $authID)->orWhere('receiver_id', $authID);
		})->where(function ($query1) use ($userID)
		{
			$query1->where('sender_id', $userID)->orWhere('receiver_id', $userID);
		})->first();
	}

}
