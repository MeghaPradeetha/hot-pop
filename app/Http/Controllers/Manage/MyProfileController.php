<?php

namespace App\Http\Controllers\Manage;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use App\Entities\Files\File;
use Illuminate\Http\Request;
use App\Services\FirebaseService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Entities\Nationalities\Nationality;
use App\Entities\Personalities\Personality;
use App\Entities\InterestLists\InterestList;
use BaconQrCode\Renderer\RendererStyle\Fill;

class MyProfileController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show()
    {

        $my_profile = auth()->user();
        if ($my_profile->personality) {
            $interests = explode(',', $my_profile->personality->interests);
        } else {
            $interests = null;
        }

        $files = $my_profile->files;
        $image_array = array();
        $video = null;
        foreach ($files as $photo) {
            if ($photo->category == 'photos') {
                array_push($image_array, $photo->file_path);
            }

            if ($photo->category == 'video') {
                $video = $photo->file_path;
            }
        }


        return view('pages.user-profile', compact('my_profile', 'interests', 'image_array', 'video') + ['pageTitle' => 'My Profile']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editProfile($id)
    {
        $my_profile = User::findOrFail($id);
        $personality = $my_profile->personality;
        $interests = explode(',', $personality->interests ?? '');
        $interestList = InterestList::where('status', 'active')->pluck('name');
        $nationalityList = Nationality::where('status', 'active')->pluck('name');
        $video = File::where('category', 'video')->where('attachable_id', $id)->first();
        $images = File::where('category', 'photos')->where('attachable_id', $id)->get();


        return view('pages.edit-profile.edit-my-profile', compact('my_profile', 'personality', 'interests', 'video', 'images', 'interestList', 'nationalityList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'name'              => $request->name,
            'last_name'         => $request->last_name,
            'age'               => $request->age,
            'location'          => $request->location,
            'gender'            => $request->gender,
            'want_email_notify' => $request->want_email_notify
        ]);

        FirebaseService::updateUser($user);

        return back()->with(['success' => "Updated Successfully..!", "tab" => 1]);
    }

    public function videoRetake()
    {
        return view('pages.edit-profile.edit-profile-introduction');
    }

    public function destroyVideo($userId)
    {

        $user = User::find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Delete video files associated with the user
        $videoFiles = File::where('category', 'video')->where('attachable_id', $userId)->get();
        foreach ($videoFiles as $videoFile) {

            // Delete the video file from storage
            $diskName = 'public';
            $disk = Storage::disk($diskName);
            if ($disk->exists($videoFile->file_path)) {
                $disk->delete($videoFile->file_path);
            }

            $videoFile->delete();
        }

        return view('pages.profile.profile-introduction');
    }

    public function updateVideo(Request $request)
    {
        $userAgent = $request->header('User-Agent');

        $id = Auth::user()->id;
        $diskName = 'public';
        $disk = Storage::disk($diskName);

        $videoFiles = File::where('category', 'video')->where('attachable_id', $id)->get();

        foreach ($videoFiles as $videoFile) {
            if ($disk->exists($videoFile->file_path)) {
                $disk->delete($videoFile->file_path);
            }
            $videoFile->delete();
        }
        // $fileName = uniqid('video_') . '.mp4';
        // $newFileName = Str::random(20) . '.webm';

        if ($request->hasFile('video_data')) {

            if (strpos($userAgent, 'Safari') !== false && strpos($userAgent, 'Chrome') === false) {
                // Safari detected
                $fileName = uniqid('safari_') . '.mp4';
                $path = $request->video_data->store('user_video/' . $id, $diskName);
                // $path = $request->file('video_data')->storeAs('user_video/' . $id, $newFileName, $diskName);
            } else {
                // Not Safari
                $fileName = uniqid('chrome_') . '.mp4';
                $videoBlob = $request->file('video_data');
                $webmPath = $videoBlob->storeAs('public/user_video/' . $id, 'video.webm');
                $mp4Path = storage_path("app/public/user_video/$id/") . $fileName;
                $path = 'user_video/' . $id . '/' . $fileName;
                exec("ffmpeg -i " . storage_path("app/$webmPath") . " $mp4Path");
                Storage::delete($webmPath);
            }


            $file = new File();
            $file->attachable_id = $id;
            $file->category = 'video';
            $file->file_path = $path;
            $file->file_disk = $diskName;
            $file->file_url = $disk->url($path);
            $file->save();
        }

        return back()->with(['success' => "Updated Successfully..!", "tab" => 2]);

    }



    public function editProfileIntro($id)
    {
        $video = File::where('category', 'video')->where('attachable_id', $id)->first();


        return view('pages.edit-profile.edit-profile-introduction', compact('id', 'video'));
    }

    public function editProfilePictures($id)
    {
        $images = File::where('category', 'photos')->where('attachable_id', $id)->get();
        return view('pages.edit-profile.edit-profile-setup-pictures', compact('id', 'images'));
    }


	public function updatePictures(Request $request, $id)
	{
		// Find the user by ID
		$findUser = User::find($id);

		if (!$findUser) {
			return back()->with([
				'error' => 'User not found.',
				'tab' => 3
			]);
		}

		// Count the current number of images for the user
		$imageCount = File::where('category', 'photos')->where('attachable_id', $id)->count();

		// Check if the user has enough images
		$diskName = 'public';
		$disk = Storage::disk($diskName);

		// Handle image file uploads
		if ($request->hasFile('images')) {
			$images = $request->file('images');

			// Check if the total number of images will be less than 3 after upload
			if ((count($images) + $imageCount) < 3) {
				return back()->with([
					'error' => 'Update failed! Please add at least three images.',
					'tab' => 3
				]);
			}

			// Loop through each uploaded image and save it
			foreach ($images as $image) {
				// Store the image in the user_images folder
				$path = $image->store('user_images/' . $id, $diskName);

				// Create a new file record
				$file = new File();
				$file->category = 'photos';
				$file->attachable_id = $id;
				$file->file_path = $path;
				$file->file_url = $disk->url($path);
				$file->file_disk = $diskName;
				$file->save();
			}

			return back()->with([
				'success' => 'Updated Successfully!',
				'tab' => 3
			]);
		}

		// Case when there are no new images, but the user already has fewer than 3
		if ($imageCount < 3) {
			return back()->with([
				'error' => 'Update failed! Please add at least three images.',
				'tab' => 3
			]);
		}

		// Case when the user already has 3 or more images and no new images are uploaded
		return back()->with([
			'success' => 'No new images added, already have enough images.',
			'tab' => 3
		]);
	}



    public function deleteProfilePictures($id)
    {
        // return response()->json(['message' => 'Video saved successfully']);
        $user_id = Auth()->user()->id;
        $image = File::find($id);

        if (!$image) {
            return redirect()->back()->with('error', 'User not found.');
        }
        $diskName = 'public';
        $disk = Storage::disk($diskName);

        if ($disk->exists($image->file_path)) {
            $disk->delete($image->file_path);
        }

        $image->delete();
    }




    // public function editPersonality($id)
    // {

    //     $personality = Personality::where('user_id', $id)->first();
    //     if ($personality != null) {
    //         $interests = explode(',', $personality->interests);
    //     } else {
    //         $interests = null;
    //     }

    //     return view('pages.edit-profile.edit-profile-personality', compact('id', 'personality', 'interests'));
    // }

    public function updatePersonality(Request $request, $id)
    {
        $this->validate($request, [
            'occupation'                 => 'required',
            'hometown'                   => 'required',
            'height'                     => 'required',
            'nationality'                => 'required',
            'interests'                  => 'required',
            'like_to_have_more_childran' => 'required',
        ]);

        $personality = Personality::where('user_id', $id)->first();

        if (is_array($request->interests)) {
            $interests = implode(',', $request->interests);
            $personality->update([
                'interests' => $interests
            ]);
        }

        $personality->update([
            'occupation'                 => $request->occupation,
            'hometown'                   => $request->hometown,
            'height'                     => $request->height,
            'nationality'                => $request->nationality,
            'relationship_type'          => $request->relationship_type,
            'dating_intentions'          => $request->dating_intentions,
            'min_age'                    => $request->min_age,
            'max_age'                    => $request->max_age,
            'like_to_have_more_childran' => $request->like_to_have_more_childran

        ]);

        return back()->with(['success' => "Updated Successfully..!", "tab" => 4]);

    }

    /**
     * Remove the specified resource from storage.
     */

    public function updateAccount(Request $request)
    {

        $my_profile = auth()->user();

        $request->validate([
            'email' => 'required|email|max:255|unique:users,email,' . $my_profile->id,
        ]);

        $diskName = 'public';
        $disk = Storage::disk($diskName);
        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            $path = $image->store('user_images/' . $my_profile->id, $diskName);
            $file = new File();
            $file->category = 'photos';
            $file->attachable_id = $my_profile->id;
            $file->file_path = $path;
            $file->file_url = $disk->url($path);
            $file->file_disk = $diskName;
            $file->save();
        }

        $my_profile->update([
            "email" => $request->email
        ]);

        return redirect()->route('my-account');
    }

    public function view($id)
    {

        $profile = User::find($id);
        if ($profile->personality) {
            $interests = explode(',', $profile->personality->interests);
        } else {
            $interests = null;
        }

        $files = $profile->files;
        $image_array = array();
        $video = null;
        foreach ($files as $photo) {
            if ($photo->category == 'photos') {
                array_push($image_array, $photo->file_path);
            }

            if ($photo->category == 'video') {
                $video = $photo->file_path;
            }
        }


        return view('pages.profile.profile-view', compact('profile', 'interests', 'image_array', 'video'));
    }

    public function deleteAccount()
    {
        $user = auth()->user();
        $results = uniqid();
        $user->email = 'DELETED _' . $results;
        $user->deleted_at = Carbon::now();
        $user->update();

        // Log the user out
        Auth::logout();

        // Redirect to the login page or another route
        return redirect('/login')->with('message', 'Your account has been deleted.');
    }

}
