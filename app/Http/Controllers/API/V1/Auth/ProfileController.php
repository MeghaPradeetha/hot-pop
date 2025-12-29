<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\API\V1\APIBaseController;
use App\Models\User;
use App\Entities\Files\File;
use Illuminate\Http\Request;
use App\Services\FirebaseService;
use App\Entities\Interests\Interest;
use Illuminate\Support\Facades\Storage;
use App\Entities\Nationalities\Nationality;
use App\Entities\InterestLists\InterestList;
use App\Models\Device;
use App\Entities\PushNotifications\SupportData;

class ProfileController extends APIBaseController
{

	/**
	 *
	 * Get currently logged-in user's profile
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index()
	{
		$user = \Illuminate\Support\Facades\Auth::user();
		$user->load('files', 'personality');
		return $this->respondSuccess($user);
	}

	public function profileSetup01(Request $request)
	{


		$user = \Illuminate\Support\Facades\Auth::user();

		$this->validate($request, [
			'name'              => 'required',
			'last_name'         => 'required',
			'gender'            => 'required',
			'want_email_notify' => 'required',
			'age'               => 'numeric|required',
		]);

		$profile_setup_step = $user->profile_setup_step < User::PROFILE_INTRO ? User::PROFILE_SETUP : $user->profile_setup_step;

		$user->update([
			'name'               => $request->name,
			'last_name'          => $request->last_name,
			'age'                => $request->age,
			'location'           => $request->location,
			'gender'             => $request->gender,
			'want_email_notify'  => $request->want_email_notify,
			'timezone'           => $request->timezone ?? 'Australia/Melbourne',
			'latitude'           => $request->latitude,
			'longitude'          => $request->longitude,
			'profile_setup_step' => $profile_setup_step
		]);

		FirebaseService::updateUser($user);

		return $this->respondSuccess($user->refresh(), 'success');
	}

	public function profileSetup02(Request $request)
	{


		$user = \Illuminate\Support\Facades\Auth::user();

		$this->validate($request, [
			'file' => 'required|file|mimes:mp4,mov|max:20480',
		]);

		$diskName = 'public';
		$disk = Storage::disk($diskName);

		$folder = "user_video/$user->id";
		$path = $request->file->store($folder, $diskName);
		$url = $disk->url($path);

		$newFie = $user->files()->create([
			'file_url'            => $url,
			'file_path'           => $path,
			'file_disk'           => $diskName,
			'allow_public_access' => true,
			'original_filename'   => $request->file->getClientOriginalName(),
			'uploaded_by_user_id' => $user->id,
			'category'            => 'video'
		]);

		if ($user->profile_setup_step < User::PROFILE_PERSONALITY)
			$user->update([
				'profile_setup_step' => User::PROFILE_INTRO
			]);

		return $this->respondSuccess($user->refresh(), 'success');
	}

	public function profileSetupImages(Request $request)
	{


		$user = \Illuminate\Support\Facades\Auth::user();

		$this->validate($request, [
			'images'   => 'required|array|min:1',
			'images.*' => 'required|file|max:10240',
			// 'images.*' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
		]);

		$diskName = 'public';
		$disk = Storage::disk($diskName);
		$folder = "user_images/$user->id";

		foreach ($request->file('images', []) as $image) {
			$path = $image->store($folder, $diskName);
			$url = $disk->url($path);

			$newFile = $user->files()->create([
				'file_url'            => $url,
				'file_path'           => $path,
				'file_disk'           => $diskName,
				'allow_public_access' => true,
				'original_filename'   => $image->getClientOriginalName(),
				'uploaded_by_user_id' => $user->id,
				'category'            => 'photos'
			]);
		}

		if ($user->profile_setup_step < User::PROFILE_PERSONALITY)
			$user->update([
				'profile_setup_step' => User::PROFILE_IMAGES
			]);

		FirebaseService::updateUser($user);

		return $this->respondSuccess($user->refresh(), 'success');
	}

	public function profileSetup03(Request $request)
	{


		$user = \Illuminate\Support\Facades\Auth::user();

		$validateData = $this->validate($request, [
			'interests'   => 'required',
			'occupation'  => 'required',
			'hometown'    => 'required',
			'height'      => 'required',
			'nationality' => 'required',
		]);

		if ($user->profile_setup_step < User::LOGGED_IN_PAGE) {
			$user->personality()->create($validateData);
			$user->update([
				'profile_setup_step' => User::PROFILE_PERSONALITY
			]);
		} else {
			$user->personality()->update($validateData);
		}

		$selectedInterests = explode(',', $request->interests);
		foreach ($selectedInterests as $interest) {
			$interestModel = new Interest();
			$interestModel->user_id = $user->id;
			$interestModel->interests = $interest;
			$interestModel->save();
		}

		$user->load('personality');

		return $this->respondSuccess($user->refresh(), 'success');
	}

	public function profileSetup04(Request $request)
	{


		$user = \Illuminate\Support\Facades\Auth::user();

		$validateData = $this->validate($request, [
			'relationship_type' => 'required',
			'dating_intentions' => 'required',
			'min_age' => 'nullable',
			'max_age' => 'nullable',
			'like_to_have_more_childran' => 'required',
		]);

		$user->personality()->update($validateData);

		$user->update([
			'profile_setup_step' => User::LOGGED_IN_PAGE
		]);

		//add free trial
		// app(UsersRepository::class)->addFreeTrial($user);

		$user->load('personality');

		return $this->respondSuccess($user->refresh(), 'success');
	}

	public function profileComplete(Request $request)
	{


		$user = \Illuminate\Support\Facades\Auth::user();

		$user->update([
			'profile_setup_step' => User::LOGGED_IN_PAGE
		]);

		//add free trial
		// app(UsersRepository::class)->addFreeTrial($user);

		return $this->respondSuccess($user->refresh(), 'success');
	}

	public function show(Request $request)
	{


		$request->validate([
			'user_id' => 'required|exists:users,id'
		]);

		$user = User::with([
			'files',
			'personality'
		])->find($request->user_id);

		return $this->respondSuccess($user, 'success');
	}

	/**
	 *
	 * Delete User Account
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function delete(Request $request)
	{
		$user = \Illuminate\Support\Facades\Auth::user();

		$user->stripPIIDAndDelete();

		if (!$user) {
			return response()->json(['message' => 'User not found'], 404);
		}
		return $this->respondSuccess([], 'Account Deleted Successfully');
	}

	public function getSupportData(Request $request)
	{


		$data = [
			'interests'   => InterestList::where('status', 'active')->pluck('name'),
			'nationality' => Nationality::where('status', 'active')->pluck('name')
		];

		return $this->respondSuccess($data, 'sucesses');
	}



}
