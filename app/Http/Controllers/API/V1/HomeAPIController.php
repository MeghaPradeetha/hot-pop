<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use EMedia\Api\Docs\Param;
use EMedia\Api\Docs\APICall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Entities\ChatRooms\ChatRoom;
use EMedia\Devices\Auth\DeviceAuthenticator;
use App\Entities\ChatRooms\ChatRoomRepository;
use App\Entities\ProfilePreferences\ProfilePreference;
use EMedia\Oxygen\Entities\Traits\FiltersByLatLngTrait;
use App\Entities\ProfilePreferences\ProfilePreferencesRepository;

class HomeAPIController extends APIBaseController
{
	use FiltersByLatLngTrait;

	public function __construct(protected ProfilePreferencesRepository $profilePreferencesRepository)
	{
	}
	/**
	 *
	 */
	public function index(Request $request)
	{
		document(function ()
		{
			return (new APICall())
				->setName('Home')
				->setDescription('Get Profile Details for match')
				->setParams([
					(new Param('page'))->setDescription('Page Number')->optional(),
					(new Param('gender'))->setDescription('Male, Female, Non-binary, All')->optional(),
					(new Param('relationship_type'))->setDescription('Relationship, Keeping it casual, Friendship, Figuring out my relationship goals')->optional(),
					(new Param('dating_preference'))->setDescription('Male, Female, Non-binary, All')->optional(),
					(new Param('min_age', 'number'))->optional(),
					(new Param('max_age', 'number'))->optional(),
					(new Param('min_distance', 'number'))->optional(),
					(new Param('max_distance', 'number'))->optional(),
					(new Param('latitude'))->optional(),
					(new Param('longitude'))->optional(),
					(new Param('like_to_have_more_childran'))->setDescription('yes or no')->optional()
				])
				->setSuccessPaginatedObject(app('oxygen')::getUserClass());
		});


		$user = DeviceAuthenticator::getUserByAccessToken();

		//get only the profiles that have not liked
		$preference = $user->preferences->pluck('preferred_user_id');

		//check intrest type
		$query = User::where('id', '!=', $user->id)
			->with('personality', 'files')
			->where('profile_setup_step', User::LOGGED_IN_PAGE)
			->whereNotIn('id', $preference);

		$datingPreference = $request->input('dating_preference', $user->personality?->dating_intentions);

		$query->when($request->filled('relationship_type'), function ($query) use ($request)
		{
			$query->whereHas('personality', fn($q) => $q->where('relationship_type', $request->relationship_type))
				->with(['personality' => fn($q) => $q->where('relationship_type', $request->relationship_type)]);

		})->when($datingPreference, function ($query) use ($datingPreference)
		{
			if ($datingPreference != "All")
				$query->whereHas('personality', fn($q) => $q->where('dating_intentions', $datingPreference))
					->with(['personality' => fn($q) => $q->where('dating_intentions', $datingPreference)]);
		})->when($request->filled('like_to_have_more_childran'), function ($query) use ($request)
		{
			$query->whereHas('personality', fn($q) => $q->where('like_to_have_more_childran', $request->like_to_have_more_childran))
				->with(['personality' => fn($q) => $q->where('like_to_have_more_childran', $request->like_to_have_more_childran)]);
		})->when($request->filled('gender'), function ($query) use ($request)
		{
			if ($request->gender != "All")
				$query->where('gender', $request->gender);
		})->when(
				$request->filled('min_age') && $request->filled('max_age') && $request->max_age > 20,
				fn($query) => $query->whereBetween('age', [$request->min_age, $request->max_age])
			)->when($request->filled(['latitude', 'longitude', 'min_distance']), function ($q) use ($request)
			{
				$q->whereRaw('6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))) >= ?', [
					$request->latitude,
					$request->longitude,
					$request->latitude,
					$request->min_distance
				]);
			})->when($request->filled(['latitude', 'longitude', 'max_distance']), function ($q) use ($request)
			{
				$q->whereRaw('6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))) <= ?', [
					$request->latitude,
					$request->longitude,
					$request->latitude,
					$request->max_distance
				]);
			});

		// $this->filterByLatLng($request, $query, $request->max_distance);

		$profiles = $query->inRandomOrder()->paginate(10);

		return response()->apiSuccessPaginated($profiles, 'sucesses');
	}

	public function accept(Request $request)
	{
		document(function ()
		{
			return (new APICall())
				->setName('Accept Profile')
				->setDescription('Accept Profile')
				->setParams([
					(new Param('preferred_user_id'))->setDescription('Accept Profile ID')
				]);
		});

		$user = DeviceAuthenticator::getUserByAccessToken();
		$preferred_user = User::findOrFail($request->preferred_user_id);
		$is_match = false;

		ProfilePreference::firstOrCreate([
			'user_id'           => $user->id,
			'preferred_user_id' => $preferred_user->id,
			'preference'        => ProfilePreference::PREFERENCE_LIKE
		]);

		// if user is block want to unblock
		$chat_room = app(ChatRoomRepository::class)->getChatRoom($user->id, $request->preferred_user_id);
		if ($chat_room) {
			$chat_room->block_status = 0;
			$chat_room->save();

			DB::table('block')->where('blocked_by_user', $user->id)->where('blocked_user', $request->preferred_user_id)->where('chat_room_id', $chat_room->id)->delete();
		}else{
			$chat_room = ChatRoom::create([
				'sender_id'   => $user->id,
				'receiver_id' => $request->preferred_user_id
			]);
		}


		if ($this->profilePreferencesRepository->isMutualPreference($user->id, $preferred_user->id)) {
			$is_match = true;

			$this->profilePreferencesRepository->notifyUser($user, $preferred_user);

			$this->profilePreferencesRepository->notifyUser($preferred_user, $user);

		}

		return response()->apiSuccess([
			'is_match' => $is_match,
			'user'     => $preferred_user,
		], 'sucesses');
	}

	public function getMatchProfile(Request $request)
	{
		document(function ()
		{
			return (new APICall())
				->setName('Get Match Profile')
				->setDescription('Get Match Profile')
				->setSuccessPaginatedObject(User::class);
		});

		$user = DeviceAuthenticator::getUserByAccessToken();

		$preferred_by = ProfilePreference::where('preferred_user_id', $user->id)->pluck('user_id');

		$filter = User::whereIn('id', $preferred_by)->where('deleted_at', null)->pluck('id');

		//matched profile with user details
		$profile_match = ProfilePreference::with('preferredUser.personality')
			->where('user_id', $user->id)
			->where('preference', ProfilePreference::PREFERENCE_LIKE)
			->whereIn('preferred_user_id', $filter)
			->paginate();

		$preferredUsers = $profile_match->through(function ($item) use($user)
		{
			$preferredUser = $item->preferredUser;
			$chat_room = app(ChatRoomRepository::class)->getChatRoom($user->id, $preferredUser->id);
			if ($chat_room) {
				$chat_room['participant'] = $chat_room->participant->load('personality','files');
			}
			$preferredUser->chat_room = $chat_room;
			return $preferredUser;
		});

		return response()->apiSuccessPaginated($preferredUsers, 'sucesses');
	}

	public function unmatchProfile(Request $request)
	{
		document(function ()
		{
			return (new APICall())
				->setName('Unmatch Profile')
				->setDescription('unmatch Profile')
				->setParams([
					(new Param('preferred_user_id'))->setDescription('User Profile ID')
				])
				->setSuccessObject(app('oxygen')::getUserClass());
		});

		$user = DeviceAuthenticator::getUserByAccessToken();

		$preference = $user->preferences()->where('preferred_user_id', $request->preferred_user_id)->first();

		if (!$preference) {
			return response()->apiError('Profile not found', 404);
		}

		$preference->delete();

		return response()->apiSuccess($user, 'sucesses');
	}
}