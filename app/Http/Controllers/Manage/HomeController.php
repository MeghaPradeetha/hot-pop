<?php

namespace App\Http\Controllers\Manage;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\ProfileMatch;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Entities\ChatRooms\ChatRoomRepository;
use App\Entities\Filters\Filter;
use App\Entities\ProfilePreferences\ProfilePreference;
use App\Entities\Personalities\PersonalitiesRepository;
use App\Entities\Personalities\Personality;

class HomeController extends Controller
{
    // protected $personalityRepo;
    /**
     * Display a listing of the resource.
     */
    public function __construct(protected ChatRoomRepository $chatRoomRepo, protected PersonalitiesRepository $personalityRepo)
    {
        // $this->personalityRepo = $personalityRepo;
    }

    public function index(Request $request)
    {
        // return $request;
        $logged_user = Auth::user();
        $user = User::findOrFail($logged_user->id);
        $user->update([
            'profile_setup_step' => User::LOGGED_IN_PAGE
        ]);
        //get only the profiles that have not liked
        $preference = ProfilePreference::where('user_id', $logged_user->id)->pluck('preferred_user_id');

        //check intrest type

        if ($user->personality?->dating_intentions == 'All') {
            $profiles = User::where('id', '!=', $logged_user->id)->where('profile_setup_step', User::LOGGED_IN_PAGE)->whereNotIn('id', $preference)->get();

        } else {
            // $persernality = Personality::where('dating_intentions', $user->personality->dating_intentions)->pluck('user_id');
            $profiles = User::where('id', '!=', $logged_user->id)->where('profile_setup_step', User::LOGGED_IN_PAGE)->where('gender', $user->personality?->dating_intentions)->whereNotIn('id', $preference)->inRandomOrder()->get();
        }
        //to popup the match modal who have liked the logged user
        $preferred_by_profiles = ProfilePreference::where('preferred_user_id', $logged_user->id)->pluck('user_id')->all();
        //logged user data to show in the popup modal


        //if match filter exist
        if ($request->filter) {
            $profiles = $this->personalityRepo->matchFilter($request);
        }
        // return $profiles;
        return view('pages.profile-home', compact('profiles', 'preferred_by_profiles', 'logged_user'));
    }

    public function getNextProfile(Request $request)
    {
        $index = $request->input('index', 0);
        $user = User::findOrFail($index);

        return view('pages.partials.user-profile', ['user' => $user]);

    }


    public function show(string $id)
    {
        $profile = User::findOrFail($id);


        //to popup the match modal who have liked the logged user
        $preferred_by_profiles = ProfilePreference::where('preferred_user_id', $id)->get();

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
                array_push($image_array, $photo->file_url);
            }

            if ($photo->category == 'video') {
                $video = $photo->file_url;
            }
        }

        return view('pages.profile-home-full', compact('profile', 'interests', 'image_array', 'video', 'preferred_by_profiles'));
    }

    public function likeDislike(Request $request)
    {

        ProfilePreference::firstOrCreate($request->all());

        //email sending if profiles match
        $logged_user = auth()->user();
        $preferred_user = User::findOrFail($request->preferred_user_id);
        $preferred_by_profiles = ProfilePreference::where('preferred_user_id', $logged_user->id)->pluck('user_id')->all();

        // if user is block want to unblock
        $chat_room = $this->chatRoomRepo->getChatRoom($logged_user->id, $request->preferred_user_id);
        if ($chat_room) {
            $chat_room->block_status = 0;
            $chat_room->save();

            DB::table('block')->where('blocked_by_user', Auth()->user()->id)->where('blocked_user', $request->preferred_user_id)->where('chat_room_id', $chat_room->id)->delete();
        }


        if ($request->preference == 1 && in_array((int) $request->preferred_user_id, $preferred_by_profiles, TRUE)) {
            if ($logged_user->want_email_notify == "yes") {

                //send email
                $email = [
                    'greeting' => 'Good Luck..!',
                    'body'     => 'You have a match with ' . $preferred_user->full_name,
                    'text'     => 'Thank you'
                ];
                $logged_user->notify(new ProfileMatch($email));
            }


            if ($preferred_user->want_email_notify == "yes") {
                //send email
                $email = [
                    'greeting' => 'Good Luck..!',
                    'body'     => 'You have a match with ' . $logged_user->full_name,
                    'text'     => 'Thank you'
                ];
                $preferred_user->notify(new ProfileMatch($email));
            }
        }

        return response()->json(['message' => 'Form submitted successfully', 'preferred_by_profiles' => $preferred_by_profiles]);
    }

    public function matchingProfiles()
    {

        $logged_user = Auth()->user()->id;

        $preferred_by = ProfilePreference::where('preferred_user_id', $logged_user)->pluck('user_id');

		$filter =  User::whereIn('id',$preferred_by)->where('deleted_at',null)->pluck('id');

        //matched profile with user details
        $profile_match = ProfilePreference::with('preferredUser')
            ->where('user_id', $logged_user)
            ->where('preference', ProfilePreference::PREFERENCE_LIKE)
            ->whereIn('preferred_user_id', $filter)
            ->get();

        $profile_count = $profile_match->count();
        return view('pages.matches', compact('profile_match', 'profile_count'));
    }

    public function unsetMatched($id)
    {
        ProfilePreference::find($id)->delete();

        return redirect()->route('matched-profiles');
    }

    //latest matches to popup in home page modal
    // public function latestMatchings(){
    //     $logged_user = Auth()->user()->id;

    //     //all the profiles that have liked the logged users profile
    //     $preferred_by_profiles = ProfilePreference::where('preferred_user_id', $logged_user)->get();

    //     return view('pages.profile-home', compact('preferred_by_profiles'));
    // }

    public function matchFilterIndex(Request $request)
    {
		$filter = Filter::where('user_id',Auth::user()->id)->first();

        return view('pages.match-filter',compact('filter'));
    }
}
