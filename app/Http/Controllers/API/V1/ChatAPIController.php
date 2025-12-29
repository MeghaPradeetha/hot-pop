<?php

namespace App\Http\Controllers\API\V1;

use App\Entities\ProfilePreferences\ProfilePreference;
use App\Events\BlockUser;
use App\Events\MessageRead;
use Carbon\Carbon;
use DB;
use EMedia\Api\Docs\Param;
use App\Entities\Chats\Chat;
use App\Entities\Files\File;
use EMedia\Api\Docs\APICall;
use Illuminate\Http\Request;
use App\Events\NewChatMessage;
use Illuminate\Support\Facades\Log;
use App\Entities\ChatRooms\ChatRoom;
use Illuminate\Support\Facades\Storage;
use App\Entities\ChatReports\ChatReport;
use EMedia\Devices\Auth\DeviceAuthenticator;
use App\Entities\ChatRooms\ChatRoomRepository;

class ChatAPIController extends APIBaseController
{

	public function __construct(protected ChatRoomRepository $repo)
	{
	}

	/**
	 *
	 * Chat users
	 */
	public function chatUsers(Request $request)
	{
		document(function ()
		{
			return (new APICall())
				->setGroup('Chat')
				->setName('Chat User List')
				->setSuccessObject(ChatRoom::class);
		});

		$user = \Illuminate\Support\Facades\Auth::user();

		$chat_rooms = $this->repo->getUserRoomList($user->id);
		$chat_rooms->load('lastChat');

		$chat_rooms = $chat_rooms->sortByDesc('last_message_created');

		return response()->apiSuccess($chat_rooms, 'success');
	}

	/**
	 * Chat Store
	 */
	public function store(Request $request)
	{
		document(function ()
		{
			return (new APICall())
				->setGroup('Chat')
				->setName('Send Chat Message')
				->hasFileUploads()
				->setParams([
					(new Param('receiver_id', 'number', 'Message Receiver ID')),
					(new Param('message', 'string', 'Message'))->optional(),
					(new Param('image', 'file', 'Image'))->optional()
				])
				->setSuccessObject(Chat::class);
		});

		$user = \Illuminate\Support\Facades\Auth::user();

		$request->validate([
			'receiver_id' => 'required|exists:users,id',
			'message'     => 'nullable|string|max:400',
			'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
		]);

		//check whether chat room is already initialized if not initialize a chat room
		$chat_room = $this->repo->getChatRoom($user->id, $request->receiver_id);

		// Check block status
		if ($chat_room && $chat_room->block_status) {
			return response()->apiError('You are blocked by this user');
		}

		if ($request->hasFile('image')) {
			$diskName = 'public';
			$disk = Storage::disk($diskName);

			try {
				$path = $request->file('image')->store('chat_image/' . $user->id, $diskName);
			} catch (\Exception $e) {
				Log::error($e->getMessage());
				return response()->json(['error' => 'File upload failed'], 500);
			}

			$upload = new File();
			$upload->category = "chat";
			$upload->file_path = $path;
			$upload->file_url = $disk->url($path);
			$upload->file_disk = $diskName;
			$upload->attachable_id = $user->id;
			$upload->save();

			$message = $disk->url($path);
		} else {
			$message = $request->message;
		}

		if (!$message) {
			return response()->apiError('Message is required');
		}

		if (!$chat_room) {
			$chat_room = ChatRoom::create([
				'sender_id'   => $user->id,
				'receiver_id' => $request->receiver_id
			]);
		}

		$chat = $chat_room->chats()->create([
			'sender_id' => $user->id,
			'message'   => $message
		]);

		event(new NewChatMessage($user->id, $request->receiver_id, $chat_room->id));

		return response()->apiSuccess($chat, 'success');
	}

	/**
	 *
	 * Chat lists
	 */
	public function chatLists(Request $request)
	{
		document(function ()
		{
			return (new APICall())
				->setGroup('Chat')
				->setName('Chat List')
				->setParams([
					(new Param('chat_room_id', 'number', 'Chat Room ID')),
					(new Param('page', 'number', 'Page Number'))
				])
				->setSuccessPaginatedObject(Chat::class);
		});

		$user = \Illuminate\Support\Facades\Auth::user();


		$chat_room = ChatRoom::find($request->chat_room_id);
		if (!$chat_room) {
			return response()->apiError('Chat room not found');
		}

		// update read status
		$chat_room->chats()
			->whereNot('sender_id', $user->id)
			->where('read_status', 0)
			->update(['read_status' => 1]);

		$senderId = $chat_room->sender_id == $user->id ? $chat_room->receiver_id : $chat_room->sender_id;

		event(new MessageRead($senderId, $user->id, 1));

		// Todo: user blocked_status
		// if ($chat_room) {
		//     $blocked_status = $chat_room->block_status;
		//     if ($blocked_status == 1) {

		//         $block = DB::table('block')->where('chat_room_id', $chat_room->id)->first();
		//     } else {
		//         $block = null;
		//     }

		$message_list = $chat_room->chats()->whereNull('delete_for_me')->latest()->paginate();
		// 	->filter(function ($row) {
		// 		return $row->delete_for_me === null; // Exclude records where deleted_me is not null
		// 	})
		// 	->groupBy(function ($row) {
		// 		return $row->created_at->format('d / M / Y'); // Group by the formatted date
		// 	});
		// } else {
		//     $block = null;
		//     $blocked_status = null;
		//     $message_list = null;
		//     // dd($chat_list);
		// }

		// $chatId = Chat::where('sender_id', '!=', $id)->latest()->value('id');
		//update message read if the user in the view
		//  $messages = Chat::where('sender_id',$id)->where('receiver_id', Auth::user()->id)->where('read_status', 0)->get();
		// $chat_room = ChatRoom::where('sender_id', $id)->where('receiver_id', Auth()->user()->id)->first();

		// Todo
		// if ($chat_room != null) {
		//     $messages = Chat::where('chat_room_id', $chat_room->id)->where('read_status', '0')->get();
		//     foreach ($messages as $message) {
		//         $message->read_status = "1";
		//         $message->update();
		//         // event(new MessageRead($id, Auth()->user()->id, $message->read_status));
		//     }
		// }

		return response()->apiSuccessPaginated($message_list, 'success');
	}

	/**
	 * Chat Read
	 */
	public function chatRead(Request $request)
	{
		document(function ()
		{
			return (new APICall())
				->setGroup('Chat')
				->setName('Chat Read')
				->setParams([
					(new Param('chat_room_id', 'number', 'Chat Room ID'))
				]);
		});

		$user = \Illuminate\Support\Facades\Auth::user();

		$request->validate([
			'chat_room_id' => 'required|exists:chat_rooms,id'
		]);

		$chat_room = ChatRoom::findOrFail($request->chat_room_id);

		$chat_room->chats()
			->whereNot('sender_id', $user->id)
			->where('read_status', 0)
			->update(['read_status' => 1]);

		$senderId = $chat_room->sender_id == $user->id ? $chat_room->receiver_id : $chat_room->sender_id;

		event(new MessageRead($senderId, $user->id, 1));

		return response()->apiSuccess(null, 'success');
	}

	/**
	 * Chat Block
	 */
	public function chatBlock(Request $request)
	{
		document(function ()
		{
			return (new APICall())
				->setGroup('Chat')
				->setName('Block Chat User')
				->setParams([
					(new Param('block_user_id', 'number', 'Block User ID')),
					(new Param('block_status', 'number', 'Block Status 0 or 1'))
				]);
		});

		$user = \Illuminate\Support\Facades\Auth::user();

		$request->validate([
			'block_user_id' => 'required|exists:users,id',
			'block_status'  => 'required|in:0,1'
		]);

		// un match
		ProfilePreference::where('user_id', $user->id)->where('preferred_user_id', $request->block_user_id)->delete();

		$chat_room = $this->repo->getChatRoom($user->id, $request->block_user_id);

		$chat_room->update(['block_status' => '1']);

		// Blcok
		DB::table('block')
			->updateOrInsert(
				['blocked_by_user' => $user->id, 'blocked_user' => $request->block_user_id, 'chat_room_id' => $chat_room->id]
			);

		// Notify
		event(new BlockUser($request->block_user_id, $user->id));

		return response()->apiSuccess(null, 'User blocked successfully');

	}

	/**
	 * Chat Delete
	 */
	public function destroy(Request $request)
	{
		document(function ()
		{
			return (new APICall())
				->setGroup('Chat')
				->setName('Delete Chat Message')
				->setParams([
					(new Param('chat_id', 'number', 'Chat Message ID'))
				]);
		});

		$user = \Illuminate\Support\Facades\Auth::user();

		$request->validate([
			'chat_id' => 'required|exists:chats,id',
		]);

		$chat = Chat::findOrFail($request->chat_id);

		if ($chat->sender_id != $user->id) {
			return response()->apiError('You are not authorized to delete this message');
		}

		$chat->delete_for_me = Carbon::now();
		$chat->save();

		return response()->apiSuccess(null, 'success');
	}

	/**
	 *
	 * Chat settings
	 */
	public function updateSetting(Request $request)
	{
		document(function ()
		{
			return (new APICall())
				->setGroup('Chat')
				->setName('Update Chat Settings')
				->setParams([
					(new Param('online_status', 'number', 'Online Status 0 or 1')),
					(new Param('read_receipts', 'number', 'Read Receipts 0 or 1'))
				])
				->setSuccessObject(app('oxygen')::getUserClass());
		});

		$user = \Illuminate\Support\Facades\Auth::user();

		$user->update([
			'online_status' => $request->online_status,
			'read_receipts' => $request->read_receipts
		]);

		return response()->apiSuccess($user, 'success');
	}

	public function makeReport(Request $request)
	{
		document(function ()
		{
			return (new APICall())
				->setGroup('Chat')
				->setName('Add Chat Report')
				->setParams([
					(new Param('reported_user', 'number', 'User ID')),
					(new Param('reason', 'string', 'Reason for Report')),
					(new Param('comments', 'string', 'Explaination'))
				])
				->setSuccessObject(ChatReport::class);
		});

		$user = \Illuminate\Support\Facades\Auth::user();

		$validateData = $request->validate([
			'reported_user' => 'required',
			'reason'        => 'required',
			'comments'      => 'required|string|max:400'
		]);

		$validateData['reported_by_user'] = $user->id;

		$report = ChatReport::create($validateData);

		return response()->apiSuccess($report, 'success');
	}

}