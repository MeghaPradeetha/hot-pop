<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Entities\Files\File;
use Illuminate\Http\Request;
use App\Services\FirebaseService;
use App\Entities\Interests\Interest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
// use App\Entities\Auth\UsersRepository;
use Illuminate\Support\Facades\Storage;
use App\Entities\Nationalities\Nationality;
use App\Entities\Personalities\Personality;
use App\Entities\InterestLists\InterestList;
// use EMedia\Http\Controllers\Auth\UpdatesUsers;

class ProfileController extends Controller
{

    // use UpdatesUsers;
    // The following code block was incorrectly placed directly inside the class.
    // Assuming it should be a method, for example, `public function updateProfile(Request $request)`
    // or `public function profileSetup2(Request $request)` based on context.
    // For now, I'm wrapping it in a placeholder method to fix the syntax.
    // You will need to define the correct method name and visibility.
    public function profileSetup(Request $request)
    {
        $this->validate($request, [
            'name'              => 'required',
            'last_name'         => 'required',
            'gender'            => 'required',
            'want_email_notify' => 'required',
            'age'               => 'numeric|required',
        ]);

        $user = Auth()->user();

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
            'profile_setup_step' => User::PROFILE_SETUP
        ]);

        FirebaseService::updateUser($user);

        return redirect()->route('get.profile-introduction');
    }


    public function profileSetup3(Request $request)
    {

        $user_id = Auth()->user()->id;

        $diskName = 'public';
        $disk = Storage::disk($diskName);

        $this->validate($request, [
            'images' => 'required|array|min:3',
        ]);

        foreach ($request->file('images', []) as $image) {
            $path = $image->store('user_images/' . $user_id, $diskName);
            $file = new File();
            $file->category = 'photos';
            $file->attachable_id = $user_id;
            $file->file_path = $path;
            $file->file_url = $disk->url($path);
            $file->file_disk = $diskName;
            $file->save();
        }
        $user = User::findOrFail($user_id);
        $user->update([
            'profile_setup_step' => User::PROFILE_IMAGES
        ]);

        FirebaseService::updateUser($user);

        return redirect()->route('get.profile-setup-personality');
    }

    public function viewPersonality()
    {
        $interestList = InterestList::where('status', 'active')->pluck('name');
        $nationalityList = Nationality::where('status', 'active')->pluck('name');

        return view('pages.profile.profile-setup-personality', compact('interestList', 'nationalityList'));
    }

    public function profileSetup4(Request $request)
    {
        $this->validate($request, [
            'occupation'  => 'required',
            'hometown'    => 'required',
            'height'      => 'required',
            'nationality' => 'required',
            'interests'   => 'required',
            'children'    => 'required',
        ]);

        $user_id = Auth()->user()->id;

        if (is_array($request->interests)) {
            $interests = implode(',', $request->interests);
        }

        $personalityModel = new Personality();



        $personalityModel->occupation = $request->occupation;
        $personalityModel->hometown = $request->hometown;
        $personalityModel->height = $request->height;
        $personalityModel->nationality = $request->nationality;
        $personalityModel->relationship_type = $request->relationship_type;
        $personalityModel->dating_intentions = $request->dating_intentions;
        $personalityModel->min_age = $request->min_age;
        $personalityModel->max_age = $request->max_age;
        $personalityModel->like_to_have_more_childran = $request->children;
        $personalityModel->user_id = $request->user_id;
        $personalityModel->interests = $interests;

        //dd($personalityModel);

        $selectedInterests = $request->input('interests');

        foreach ($selectedInterests as $interest) {
            $interestModel = new Interest();
            $interestModel->user_id = $user_id;
            $interestModel->interests = $interest;
            $interestModel->save();
        }

        $personalityModel->save();

        $user = User::findOrFail($user_id);
        $user->update([
            'profile_setup_step' => User::PROFILE_PERSONALITY
        ]);


		//add free trial
		$user->forceFill(['trial_ends_at' => now()->addDays(14)])->save();


        return redirect('/logged-in');
        // return view('pages.sign-up-tutorial');
    }

    public function storeVideo(Request $request)
    {
        $fileName = uniqid('video_') . '.mp4';
        $user_id = Auth::user()->id;
        $diskName = 'public';
        $disk = Storage::disk($diskName);


        $userAgent = $request->header('User-Agent');

        if (strpos($userAgent, 'Safari') !== false && strpos($userAgent, 'Chrome') === false) {
            // Safari detected
            $fileName = uniqid('safari_') . '.mp4';
            $path = $request->video_data->store('user_video/' . $user_id, $diskName);
            // $path = $request->file('video_data')->storeAs('user_video/' . $id, $newFileName, $diskName);
        } else {
            // Not Safari
            $fileName = uniqid('chrome_') . '.mp4';
            $videoBlob = $request->file('video_data');
            $webmPath = $videoBlob->storeAs('public/user_video/' . $user_id, 'video.webm');
            $mp4Path = storage_path("app/public/user_video/$user_id/") . $fileName;
            $path = 'user_video/' . $user_id . '/' . $fileName;
            exec("ffmpeg -i " . storage_path("app/$webmPath") . " $mp4Path");
            Storage::delete($webmPath);
        }

        $file = new File();
        $file->attachable_id = $user_id;
        $file->category = 'video';
        $file->file_path = $path;
        $file->file_disk = $diskName;
        $file->file_url = $disk->url($path);
        $file->save();

        $user = User::findOrFail($user_id);
        $user->update([
            'profile_setup_step' => User::PROFILE_INTRO
        ]);

        //return response()->json(['message' => 'Video saved successfully']);
        return redirect()->route('get.profile-setup-personality');
    }

    public function viewVideo()
    {
        $file = File::where('category', 'video')->where('attachable_id', Auth::user()->id)->first();
        if ($file) {
            return view('pages.profile.profile-introduction-finish', [
                'file' => $file,

            ]);
        }
    }

    public function videoRetake()
    {
        return view('pages.profile.profile-introduction');
    }

    public function destroy($userId)
    {

        $user = User::find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Delete video files associated with the user
        $videoFiles = File::where('attachable_id', $userId)->get();
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

    public function loggedInPage()
    {
        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);
        $user->update([
            'profile_setup_step' => User::LOGGED_IN_PAGE
        ]);

        if (!$user->trial_ends_at) {
            $user->forceFill(['trial_ends_at' => now()->addDays(14)])->save();
        }

        return view('pages.logged-in', ['pageTitle' => 'Welcome']);
    }
}

