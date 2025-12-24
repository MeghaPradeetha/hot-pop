<?php

namespace App\Http\Controllers\Manage;

use App\Models\User;
use App\Mail\NotifyMail;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Features;
use Illuminate\Routing\Pipeline;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use EMedia\Formation\Builder\Formation;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\Http\Requests\LoginRequest;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use App\Entities\ManageUsers\ManageUsersRepository;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use ElegantMedia\OxygenFoundation\Http\Traits\Web\CanCRUD;
use ElegantMedia\OxygenFoundation\Http\Traits\Web\CanRead;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use ElegantMedia\OxygenFoundation\Http\Traits\Web\FollowsConventions;


class ManageUsersController extends Controller
{

    use FollowsConventions;

    // Uncomment this line if you're going to use Oxygen's Default Controller Methods
    use CanCRUD;
    use CanRead;

    protected $repo;

    public function __construct(ManageUsersRepository $repo)
    {
        $this->repo = $repo;

        $this->resourceEntityName = 'ManageUser';
        $this->isDestroyAllowed = true;
    }

    public function getResourcePrefix()
    {
        return 'manage.users';
    }

    protected function getIndexRouteName($suffix = 'index'): string
    {
        return 'manage.users.index';
    }

    public function index()
    {
        $request = request();
        $users = User::query();

        if ($request->status) {
            if ($request->status == 6)
                $users->where('profile_setup_step', null);
            else
                $users->where('profile_setup_step', $request->status);
        }

        $users = $users->search($request->q);

        return view('manage.users.index', [
            'pageTitle'                 => 'Manage Users',
            'allItems'                  => $users->Paginate(),
            'isDestroyingEntityAllowed' => $this->isDestroyAllowed(),
            'canCreateEntities'         => $this->canCreateEntities(),
            'canEditEntities'           => $this->canEditEntities(),
        ]);
    }

    /**
     *
     * This is the form shown when creating a new record.
     *
     * @param null $entity
     *
     * @return Formation
     */
    protected function getCreateForm($entity = null)
    {
        return new Formation($entity);
    }

    /**
     *
     * This is the form shown when editing an existing record.
     *
     * @param null $entity
     *
     * @return Formation
     */
    protected function getEditForm($entity = null)
    {
        return new Formation($entity);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        // Validate the request data
        $validatedData = $request->validate([
            'name'              => 'required|string|max:255',
            'age'               => 'required|integer',
            'email'             => 'required|email|unique:users,email,' . $user->id, // Exclude the current user's email
            'location'          => 'required|string',
            'gender'            => 'required|string', // Adjust the values as needed
            'subscription_plan' => 'required|string', // You might want to validate this based on your plans
            'avatar_image'      => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Make sure the file input field is named 'avatar_image'
        ]);

        // Update user data
        $user->name = $validatedData['name'];
        $user->age = $validatedData['age'];
        $user->email = $validatedData['email'];
        $user->location = $validatedData['location'];
        $user->gender = $validatedData['gender'];
        $user->subscription_plan = $validatedData['subscription_plan'];

        // Handle avatar image upload
        if ($request->hasFile('avatar_image')) {
            $image = $request->file('avatar_image');
            $imagePath = $image->store('avatar/users', 'public');

            // Delete the old avatar image if it exists
            if ($user->avatar_path) {
                Storage::disk($user->avatar_disk)->delete($user->avatar_path);
            }

            // Update the user's avatar information
            $user->avatar_url = asset('storage/' . $imagePath);
            $user->avatar_path = $imagePath;
            $user->avatar_disk = 'public';
        }

        //dd($user);
        // Save the updated user to the database
        if ($user->save()) {
            return redirect()->route('manage.users.index')->with('success', 'User data and image updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Oops! Could not update the user data. Please try again.');
        }
    }

    public function logins(LoginRequest $request)
    {

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed

            $user = Auth::user();

            $boolean = $user->isAn('super-admins', 'admins', 'developers');
            //$boolean = $user->isAn('user');

            //dd($boolean);

            if ($boolean == true) {

                return redirect()->intended('/dashboard'); // Redirect to a protected page

                //dd('admin');
            } else {

                return redirect('/logged-in'); // Redirect to a protected page

            }
        }

        // Authentication failed
        //return back()->withErrors(['email' => 'Invalid credentials']);
        // return $this->loginPipeline($request)->then(function ($request) {
        //     return app(LoginResponse::class);
        // });
    }

    protected function loginPipeline(LoginRequest $request)
    {

        if (Fortify::$authenticateThroughCallback) {

            return (new Pipeline(app()))->send($request)->through(
                array_filter(
                    call_user_func(Fortify::$authenticateThroughCallback, $request)
                )
            );
        }

        if (is_array(config('fortify.pipelines.login'))) {

            return (new Pipeline(app()))->send($request)->through(
                array_filter(
                    config('fortify.pipelines.login')
                )
            );
        }

        return (new Pipeline(app()))->send($request)->through(array_filter([
            config('fortify.limiters.login') ? null : EnsureLoginIsNotThrottled::class,
            Features::enabled(Features::twoFactorAuthentication()) ? RedirectIfTwoFactorAuthenticatable::class : null,
            AttemptToAuthenticate::class,
            PrepareAuthenticatedSession::class,
        ]));
    }


    public function notifyView($id)
    {
        $user = User::findOrFail($id);

        return view('manage.users.send-notification', [
            'entity'    => $user,
            'pageTitle' => 'Send Notification'
        ]);
    }

    public function notifyStore(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'title'   => 'required|max:100',
            'message' => 'required|max:500'
        ]);

        Mail::to($user->email)->send(new NotifyMail($request->title, $request->message, $user->name));

        return back()->with('success', 'Message Sended');
    }


	public function view($id)
    {
        $user = User::findOrFail($id);
		$files = $user->files;
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
		if ($user->personality) {
            $interests = explode(',', $user->personality->interests);
        } else {
            $interests = null;
        }
        return view('manage.users.view', [
            'entity'    => $user,
			'video'		=>$video,
			'image_array' =>$image_array,
			'interests' =>$interests,
            'pageTitle' => 'View Profile'
        ]);
    }


}
