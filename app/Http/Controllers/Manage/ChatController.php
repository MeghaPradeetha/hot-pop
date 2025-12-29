<?php

namespace App\Http\Controllers\Manage;

use Exception;
use App\Models\User;
use App\Events\BlockUser;
use App\Events\MessageRead;
use LaravelFCM\Facades\FCM;
use App\Entities\Chats\Chat;
use App\Entities\Files\File;
use Illuminate\Http\Request;
use App\Events\NewChatMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Entities\ChatRooms\ChatRoom;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use LaravelFCM\Message\OptionsBuilder;
use Illuminate\Support\Facades\Storage;
use App\Entities\ChatReports\ChatReport;
use LaravelFCM\Message\PayloadDataBuilder;
use App\Entities\ChatRooms\ChatRoomRepository;
use LaravelFCM\Message\PayloadNotificationBuilder;
use App\Entities\ProfilePreferences\ProfilePreference;
use Carbon\Carbon;

class ChatController extends Controller
{

    public function __construct(protected ChatRoomRepository $repo)
    {
    }

    public function index()
    {
        $auth_user = auth()->id();

        $chat_list = $this->repo->getUserRoomList($auth_user);

        $chat_list->map(function ($list)
        {
            $unread = $list->chats()->where('read_status', '0')->count();
            $list['last_message'] = $list->last_chat->message ?? null;
            $list['participant'] = $list->participant;
            $list['unread_message'] = $unread;
        });

        return view('pages.chat.chat_list', compact('chat_list') + ['pageTitle' => 'Chats']);
    }

    public function profileChat($id)
    {


        $chat_user = User::withTrashed()->findOrFail($id);

        $auth_user = Auth::user()->id;
        $auth_user_details = User::find($auth_user);
        $chat_list = $this->repo->getUserRoomList($auth_user);

        foreach ($chat_list as $list) {
            $chat = Chat::where('chat_room_id', $list->id)->latest()->first();
            $unread_messages = Chat::where('chat_room_id', $list->id)->where('read_status', "0")->count();
            $list['user'] = $list->sender_id == $auth_user ? $list->receiver : $list->sender;

            // todo use repo
            if ($chat) {
                if (preg_match('/(http|https):\/\/[^\s]+/i', $chat->message))
                    $chat->message = "Image";
                elseif (strlen($chat->message) > 10)
                    $chat->message = substr($chat->message, 0, 10) . '...';

                $list['last_message'] = $chat->message;
                $list['last_message_created'] = $chat->created_at;
                $list['last_message_time'] = $chat->created_at->shortRelativeDiffForHumans();
                $list['unread_messages'] = $unread_messages;
            }
        }

        $chat_list = $chat_list->sortByDesc('last_message_created');

        $chats = ChatRoom::where(function ($query)
        {
            $query->where('sender_id', Auth::user()->id)->orWhere('receiver_id', Auth::user()->id);
        })->where(function ($query1) use ($id)
        {
            $query1->where('sender_id', $id)->orWhere('receiver_id', $id);
        })->first();

        // user blocked_status
        if ($chats) {
            $blocked_status = $chats->block_status;
            if ($blocked_status == 1) {

                $block = DB::table('block')->where('chat_room_id', $chats->id)->first();
            } else {
                $block = null;
            }

			$message_list = $chats->chats
			->filter(function ($row) {
				return $row->delete_for_me === null; // Exclude records where deleted_me is not null
			})
			->groupBy(function ($row) {
				return $row->created_at->format('d / M / Y'); // Group by the formatted date
			});
        } else {
            $block = null;
            $blocked_status = null;
            $message_list = null;
            // dd($chat_list);
        }

        $chatId = Chat::where('sender_id', '!=', $id)->latest()->value('id');
        //update message read if the user in the view
        //  $messages = Chat::where('sender_id',$id)->where('receiver_id', Auth::user()->id)->where('read_status', 0)->get();
        $chat_room = ChatRoom::where('sender_id', $id)->where('receiver_id', Auth()->user()->id)->first();

        if ($chat_room != null) {
            $messages = Chat::where('chat_room_id', $chat_room->id)->where('read_status', '0')->get();
            foreach ($messages as $message) {
                $message->read_status = "1";
                $message->update();
                event(new MessageRead($id, Auth()->user()->id, $message->read_status));
            }
        }


        return view(
            'pages.chat.chat',
            compact(
                'message_list',
                'chat_user',
                'chat_list',
                'chatId',
                'blocked_status',
                'block',
                'auth_user_details'
            ) + ['pageTitle' => 'Chat']
        );
    }

    public function readMessage(Request $request)
    {

        $senderId = $request->senderID;
        $receiverId = Auth::user()->id;
        $chat_room = ChatRoom::where('sender_id', $senderId)->where('receiver_id', $receiverId)->first();
        $message = Chat::where('chat_room_id', $chat_room->id)->latest()->first();
        $message->read_status = "1";
        $message->update();

        event(new MessageRead($senderId, $receiverId, $message->read_status));

        return response()->json([
            "updated successfully"
        ]);
    }

    //ajax chat data sender messages retrieving
    public function ajaxChatData($id)
    {

        $chatId = request()->query('chatId');
        $user = User::findOrFail($id);
        $auth_user = Auth::user()->id;
        $chat_list = ChatRoom::where('sender_id', $auth_user)->where('receiver_id', $id)->first();
        $chat_count = Chat::where('chat_room_id', $chat_list->id)->count();
        $chat = Chat::where('chat_room_id', $chat_list->id)->latest()->first();
        $chat_list['last_before_message'] = Chat::where('chat_room_id', $chat_list->id)->latest()->skip(1)->take(1)->value('message');
        $chat_list['last_message'] = $chat->message;


        //$chat_count = Chat::where('id', $chatId)->count();
        $chats = Chat::where('id', $chatId)->first();
        $time = $chats->created_at;
        $time = $time->format('h:i a');
        return response()->json([
            'chats'      => $chats,
            // 'user' => $user,
            'chat_list'  => $chat_list,
            'time'       => $time,
            'chat_count' => $chat_count
        ]);
    }

    //ajax chat data receive messages
    public function ajaxReceiveData()
    {
        $id = request()->query('receiverId');
        $chatRoomId = request()->query('chatRoomId');

        // $chat_model = Chat::query();
        // $auth_user = Auth::user()->id;
        // $chat_list = ChatRoom::where('sender_id', $auth_user)->where('receiver_id', $id)->first();
        // return response()->json(['chatList' => $chat_list]);

        $chat_list = ChatRoom::findOrFail($chatRoomId);
        $chat = Chat::where('chat_room_id', $chat_list->id)->latest()->first();
        $unread_messages = Chat::where('chat_room_id', $chat_list->id)->where('read_status', "0")->count();

        // $chat_list['last_before_message'] = Chat::where('chat_room_id', $chat_list->id)->latest()->skip(1)->take(1)->value('message');
        $chat_list['last_message'] = $chat->message;
        $chat_list['last_message_time'] = $chat->created_at->shortRelativeDiffForHumans();
        $chat_list['unread_messages'] = $unread_messages;

        $chat_count = Chat::where('chat_room_id', $chat_list->id)->count();

        // $chats = Chat::where('sender_id', $id)->latest()->first();

        return response()->json([
            'chat'       => $chat,
            'chat_list'  => $chat_list,
            'chat_count' => $chat_count,
        ]);
    }


    public function createChat(Request $request, $id)
    {
        $authId = auth()->id();

        if ($request->hasFile('upload')) {

            $diskName = 'public';
            $disk = Storage::disk($diskName);

            try {
                $path = $request->file('upload')->store('chat_image/' . $authId, $diskName);
            } catch (Exception $e) {
                Log::error($e->getMessage());
                return response()->json(['error' => 'File upload failed'], 500);
            }

            $upload = new File();
            $upload->category = "chat";
            $upload->file_path = $path;
            $upload->file_url = $disk->url($path);
            $upload->file_disk = $diskName;
            $upload->attachable_id = $authId;
            $upload->save();
            $message = $disk->url($path);

        } else {
            $message = $request->message;
        }

        //check whether chat room is already initialized if not initialize a chat room
        $chat_room = ChatRoom::where(function ($query) use ($authId)
        {
            $query->where('sender_id', $authId)->orWhere('receiver_id', $authId);
        })->where(function ($query1) use ($id)
        {
            $query1->where('sender_id', $id)->orWhere('receiver_id', $id);
        })->first();

        if ($chat_room == null) {
            $chat_room = new ChatRoom();
            $chat_room->sender_id = $authId;
            $chat_room->receiver_id = $id;
            $chat_room->save();
            $chat_room_id = $chat_room->id;
        } else {
            $chat_room_id = $chat_room->id;
            $chat_room->receiver_id = $id;
            $chat_room->sender_id = $authId;
            $chat_room->update();
        }

        $chat = Chat::create([
            'chat_room_id' => $chat_room_id,
            'sender_id'    => $authId,
            'message'      => $message
        ]);

        event(new NewChatMessage($authId, $id, $chat_room_id));

        return response()->json($chat);
    }



    private function broadcastMessage($senderName, $message, $receiver_id, $senderId)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 50);

        $notificationBuilder = new PayloadNotificationBuilder('New message from: ' . $senderName);
        $notificationBuilder->setBody($message, $senderId)
            ->setSound('default')

            ->setClickAction('https://localhost:3000/home');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            'sender_id'   => $senderId,
            'sender_name' => $senderName,
            'message'     => $message
        ]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        // $tokens = User::all()->pluck('fcm_token')->toArray();
        $tokens = User::where('id', $receiver_id)->pluck('fcm_token')->toArray();
        //    dd($tokens);
        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
        // dd($downstreamResponse);

        return $downstreamResponse->numberSuccess();
    }

    public function search()
    {
        $searchValue = request()->get('data');

        // $chat_list = ChatRoom::where('sender_id', Auth::user()->id)->orWhere('receiver_id', Auth::user()->id)->get();
        $user_id = Auth::user()->id;
        $chat_list = ChatRoom::where(function ($query) use ($user_id, $searchValue)
        {
            $query->where('sender_id', $user_id)
                ->whereHas('receiver', function ($q2) use ($searchValue)
                {
                    $q2->search($searchValue);
                });
        })->orWhere(function ($query) use ($user_id, $searchValue)
        {
            $query->where('receiver_id', $user_id)
                ->whereHas('sender', function ($q2) use ($searchValue)
                {
                    $q2->search($searchValue);
                });
        })->get();


        foreach ($chat_list as $list) {
            $chat = Chat::where('chat_room_id', $list->id)->latest()->first();
            $unread_messages = Chat::where('chat_room_id', $list->id)->where('read_status', "0")->count();
            $list['last_message'] = $chat->message;
            $list['last_message_created'] = $chat->created_at;
            $list['last_message_time'] = $chat->created_at->shortRelativeDiffForHumans();
            $list['unread_messages'] = $unread_messages;
        }

        $chat_list = $chat_list->sortBy('last_message_created')->values();

        return response()->json([
            'chat_list' => $chat_list
        ]);
    }

    public function block(Request $request)
    {
        $blocked_user = $request->blocked_user;

        // un match
        $preference_record = ProfilePreference::where('user_id', Auth::user()->id)->where('preferred_user_id', $blocked_user)->delete();

        $chat_room = ChatRoom::where(function ($query)
        {
            $query->where('sender_id', Auth::user()->id)->orWhere('receiver_id', Auth::user()->id);
        })->where(function ($query1) use ($blocked_user)
        {
            $query1->where('sender_id', $blocked_user)->orWhere('receiver_id', $blocked_user);
        })->first();

        ChatRoom::where('id', $chat_room->id)->update([
            'block_status' => '1'
        ]);

        DB::table('block')
            ->updateOrInsert(
                ['blocked_by_user' => Auth()->user()->id, 'blocked_user' => $blocked_user, 'chat_room_id' => $chat_room->id]
            );

        event(new BlockUser($blocked_user, Auth()->user()->id));

        return redirect()->route('chat.profile', $blocked_user);
    }


    public function report(Request $request)
    {

        $reported_by = Auth::user()->id;
        $request->merge([
            'reported_by_user' => $reported_by
        ]);
        ChatReport::create($request->all());
        return back();
    }

    public function getReport($id)
    {

        return view('pages.chat.chat-report', compact('id'));
    }

    public function onlineStatus(Request $request)
    {
        $auth_user = auth()->user();

        $auth_user->update([
            'online_status' => isset($request->online_status) ? 1 : 0,
            'read_receipts' => isset($request->read_receipts) ? 1 : 0
        ]);

        return back();
    }

	public function deleteAll($id)
    {
        $chat = Chat::find($id);
		$chat->deleted_at = Carbon::now();
		$chat->update();

		return back();
    }

	public function delete($id)
    {
        $chat = Chat::find($id);
		$chat->delete_for_me = Carbon::now();
		$chat->update();

		return back();
    }

	public function deleteRoom($id)
    {

        $chat = ChatRoom::find($id);
		$chat->deleted_at = Carbon::now();
		$chat->update();

		return back();
    }
}
