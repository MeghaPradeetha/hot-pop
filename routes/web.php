<?php

use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FCMController;
use App\Http\Controllers\Manage\DashboardController;
use App\Http\Controllers\Manage\ChatController;
use App\Http\Controllers\Manage\HomeController;
use App\Http\Controllers\Common\PagesController;
use App\Http\Controllers\Manage\AgoraController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Manage\ReportsController;
use App\Http\Controllers\Manage\SettingsController;
use App\Http\Controllers\Auth\EarlyAccessController;
use App\Http\Controllers\Manage\AudioCallController;
use App\Http\Controllers\Manage\InquiriesController;
use App\Http\Controllers\Manage\MyProfileController;
use App\Http\Controllers\Manage\VideoChatController;
use App\Http\Controllers\Manage\ManageUsersController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Manage\SubscriptionController;
use App\Http\Controllers\Manage\InterestListsController;
use App\Http\Controllers\Manage\ManageDevicesController;
use App\Http\Controllers\Manage\NationalitiesController;
use App\Http\Controllers\Manage\PaymentMethodController;
use App\Http\Controllers\Manage\SubscriptionPlanController;
use App\Http\Controllers\Auth\LoginController; // ADDED
use App\Http\Controllers\Auth\ForgotPasswordController; // ADDED
use App\Http\Controllers\Auth\ResetPasswordController; // ADDED
use Laravel\Fortify\Http\Controllers\VerifyEmailController;
use Laravel\Fortify\Http\Controllers\EmailVerificationPromptController;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// If there's a DEV_BROWSERSYNC_URL given, use it for the URLs
// this will help to generate consistent URLs with BrowserSync
// * Don't use this on a production environment
// * Don't uncomment unless you understand what this will do
// if (app()->environment('local')) {
//    $domainRoot = env('DEV_BROWSERSYNC_URL', '');
//    if ($domainRoot !== '') \Illuminate\Support\Facades\URL::forceRootUrl($domainRoot);
// }



/*
 **************************************************************************************
 * 				Guest Route
 **************************************************************************************
 */
Route::group(['middleware' => config('fortify.middleware', ['web'])], function ()
{
	Route::view('/', 'home')->name('home');

	Route::group(['middleware' => ['guest']], function ()
	{
		Route::get('/early-access', [EarlyAccessController::class, 'getSignupPage'])->name('Early.SignupPage');
		Route::post('/early-access-signup', [EarlyAccessController::class, 'earlySignup'])->name('Early.Signup');

		// if (has_feature('auth.public_users_can_register')) {
		// }
		Route::view('/register', 'pages.auth.register')->name('register');
		Route::post('register', [RegisteredUserController::class, 'save'])->name('register.store');

		Route::get('auth/{provider}/redirect', [SocialiteController::class, 'loginSocial'])->name('socialite.auth');

		Route::get('auth/{provider}/callback', [SocialiteController::class, 'callbackSocial'])->name('socialite.callback');

		Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
		Route::post('/login', [LoginController::class, 'login']);


		// Password Reset Routes...
		Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
		Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
		Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
		Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

		Route::view('/reset', 'pages.auth.forgot'); // Keep legacy link if needed, but password.request is standard
	});

	// Email Verification...
	if (Features::enabled(Features::emailVerification())) {
		Route::get('/email/verify', [EmailVerificationPromptController::class, '__invoke'])
			->middleware(['auth'])
			->name('verification.notice');

		Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
			->middleware(['auth', 'signed', 'throttle:6,1'])
			->name('verification.verify');

		Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
			->middleware(['auth', 'throttle:6,1'])
			->name('verification.send');
	}

	Route::get('/privacy-policy', [PagesController::class, 'privacyPolicy'])->name('pages.privacy-policy');
	Route::get('/terms-and-conditions', [PagesController::class, 'termsConditions'])->name('pages.terms-conditions');
	Route::get('/faqs', [PagesController::class, 'faqs'])->name('pages.faqs');
	Route::get('/about', [PagesController::class, 'about'])->name('pages.about');

	Route::get('/contact-us', [PagesController::class, 'contactUs'])->name('contact-us');
	Route::post('/contact-us', [PagesController::class, 'postContactUs']);




	/*
	 ****************************************************************************************************
	 * 				Auth Route
	 ****************************************************************************************************
	 */
	Route::group(['middleware' => ['auth']], function ()
	{
		/**
		 * Admin Panel Route
		 */
		Route::group(['middleware' => ['auth'], 'as' => 'manage.', 'prefix' => 'manage'], function ()
		{
			Route::resource('dashboard', DashboardController::class);
			Route::resource('devices', ManageDevicesController::class)->only('index', 'show', 'destroy');
			Route::resource('reports', ReportsController::class);
			Route::resource('inquiries', InquiriesController::class);
			Route::get('inquiries/{id}/email', [InquiriesController::class, 'email']);
			Route::post('inquiries/{id}/email', [InquiriesController::class, 'send']);

			Route::get('users/{id}/notify', [ManageUsersController::class, 'notifyView']);
			Route::post('users/{id}/notify', [ManageUsersController::class, 'notifyStore']);
			Route::resource('users', ManageUsersController::class);
			Route::get('users/{id}/profile', [ManageUsersController::class, 'view']);

			Route::resource('interests', InterestListsController::class);
			Route::resource('nationality', NationalitiesController::class);
			Route::resource('subs-plans', SubscriptionPlanController::class);

			Route::get('early-access-data', [EarlyAccessController::class, 'getEarlyAccessData'])->name('early-access-data');

			Route::resource('settings', SettingsController::class)->only(['index', 'edit', 'update']);
		});

		/**
		 *  Web Site Route
		 */
		// TODO - Add Website Auth Route to Here
		Route::group(['namespace' => '\\App\\Http\\Controllers\\Auth'], function ()
		{
			//Auth
			Route::view('/verification', 'pages.auth.verification');
			Route::post('/otp', 'RegisteredUserController@verifyOtp')->name('verifyOTP');
			Route::get('/resend-otp', 'RegisteredUserController@resendOTP')->name('resend');

			// Profile Setup
			Route::view('/profile-setup', 'pages.profile.profile-setup')->name('get.profile-setup');
			Route::put('/profile-setup', 'ProfileController@profileSetup')->name('profile-setup');

			Route::view('/profile-setup2', 'pages.profile.profile-introduction')->name('get.profile-introduction');
			Route::post('/store-video', 'ProfileController@storeVideo');
			Route::get('/view', 'ProfileController@viewVideo')->name('nextPage');
			Route::delete('/users/{userId}/delete-videos', 'ProfileController@destroy')->name('deleteUserVideos');
			Route::post('/retake', 'ProfileController@videoRetake')->name('retake');

			Route::view('/profile-setup2-1', 'pages.profile.profile-setup-pictures')->name('get.profile-setup-pictures');
			Route::put('/profile-setup3', 'ProfileController@profileSetup3')->name('profile-setup3');

			Route::get('/profile-setup3', 'ProfileController@viewPersonality')->name('get.profile-setup-personality');
			Route::post('/profile-setup4', 'ProfileController@profileSetup4')->name('profile-setup4');

			Route::view('/sign-up-tutorial', 'pages.sign-up-tutorial');

			Route::get('/logged-in', 'ProfileController@loggedInPage');
			Route::put('/logged-in', 'ProfileController@storeVideo')->name('logged-in');

			// Register by Invitation...
			Route::get('invitations/join/{code}', [
				'as'   => 'invitations.join',
				'uses' => 'Auth\InvitationsController@showJoin'
			]);

			// Todo - Not Implement
			Route::get('/subscription', [SubscriptionController::class, 'index']);
			Route::get('/payment-details', [SubscriptionController::class, 'create']);
			Route::post('/payment-details', [SubscriptionController::class, 'store']);
		});

		Route::group(['middleware' => ['email'], 'namespace' => '\\App\\Http\\Controllers\\Manage'], function ()
		{
			Route::get('/home', 'HomeController@index')->name('home-profile');

			Route::get('/user-profile', 'MyProfileController@show')->name('my-profile');
			Route::get('/user-profile/{id}', 'MyProfileController@view')->name('user-profile');
			Route::post('/save-preference', 'HomeController@likeDislike')->name('profile-preference');
			Route::get('/get-next-profile', 'HomeController@getNextProfile');
			Route::get('match-filters', [HomeController::class, 'matchFilterIndex'])->name('match.filter');

			// Match
			Route::get('/match-profiles', 'HomeController@matchingProfiles')->name('matched-profiles');
			Route::delete('/unset-matched/{id}', 'HomeController@unsetMatched')->name('match.unset');

			// Chat
			Route::get('chat/ajax/data/{id}', [ChatController::class, 'ajaxChatData'])->name('ajax.data');
			Route::get('chat/receive/data', [ChatController::class, 'ajaxReceiveData'])->name('ajax.receive.data');
			Route::post('chat/read/message', [ChatController::class, 'readMessage'])->name('read.message');
			Route::get('chats', [ChatController::class, 'index'])->name('chats');
			Route::get('/chat/{id}', [ChatController::class, 'profileChat'])->name('chat.profile');
			Route::post('/chat/send/{id}', [ChatController::class, 'createChat'])->name('chat.create');
			Route::post('/save-token', [FCMController::class, 'index']);
			Route::post('/search', [ChatController::class, 'search'])->name('chat.search');
			Route::post('block-user', [ChatController::class, 'block'])->name('block.user');
			Route::get('report-page/{id}', [ChatController::class, 'getReport'])->name('chat.report.page');
			Route::post('report-user', [ChatController::class, 'report'])->name('report.user');
			Route::get('delete-everyone/{id}', [ChatController::class, 'deleteAll'])->name('chat.delete.all');
			Route::get('delete-me/{id}', [ChatController::class, 'delete'])->name('chat.delete.me');
			Route::get('delete-room/{id}', [ChatController::class, 'deleteRoom'])->name('chat.delete.room');


			Route::get('/call-ring', [AudioCallController::class, 'calling']);
			Route::post('/accept-event', [AudioCallController::class, 'acceptEvent'])->name('acceptEvent');

			Route::get('/call', [AudioCallController::class, 'onCall'])->name('onCall');

			Route::get('/video-chat', [VideoChatController::class, 'index'])->name('video.index');
			Route::post('/video-chat-end', [VideoChatController::class, 'endCall'])->name('video.end-calling');

			// Edit Profile
			Route::get('/profile/edit/{id}', 'MyProfileController@editProfile')->name('profile-edit');
			Route::put('/profile/update/{id}', 'MyProfileController@updateProfile')->name('profile-update');
			Route::post('/profile/images/update/{id}', 'MyProfileController@updatePictures')->name('images-update');
			Route::delete('/profile/images/delete/{id}', 'MyProfileController@deleteProfilePictures')->name('profile-images-delete');
			Route::put('/profile/personality/update/{id}', 'MyProfileController@updatePersonality')->name('profile-personality-update');
			Route::delete('/profile/intro-video/delete/{id}', [MyProfileController::class, 'destroyVideo'])->name('edit-delete-videos');
			Route::post('/profile/intro-video/retake', [MyProfileController::class, 'videoRetake'])->name('edit-video-retake');
			Route::post('/profile/intro-video/update/{id}', [MyProfileController::class, 'updateVideo'])->name('video-update');

			// Settings
			Route::get('/my-account', function () {
				$user = auth()->user();
				return view('pages.account.my-account', compact('user') + ['pageTitle' => 'My Account']);
			})->name('my-account');
			Route::view('/edit-account', 'pages.account.edit-account')->name('edit-account');
			Route::post('/edit-account', 'MyProfileController@updateAccount')->name('update-account');
			Route::view('/change-password', 'pages.account.change-password');
			Route::view('/forgot-password', 'pages.account.forgot-password');
			Route::delete('/delete-account', 'MyProfileController@deleteAccount')->name('delete-account');

			Route::get('/chat-setting', function () {
				$user = auth()->user();
				return view('pages.settings.chat-setting', compact('user') + ['pageTitle' => 'Chat Settings']);
			})->name('chat.settings');
			Route::post('chat-setting', [ChatController::class, 'onlineStatus'])->name('settings.data.save');

			Route::get('/subscription-plan', [SubscriptionController::class, 'show'])->name('subscription-plan');
			Route::get('/subscription-cancel', [SubscriptionController::class, 'cancel'])->name('subscription-cancel');
			Route::get('/payment-methods', [PaymentMethodController::class, 'index'])->name('payment-methods');
			Route::get('/add-new-card', [PaymentMethodController::class, 'create'])->name('add-card');
			Route::post('/add-new-card', [PaymentMethodController::class, 'store'])->name('add-card-post');

			Route::get('files/{uuid}/{fileName?}', 'ManageFilesController@publicView')->name('files.show');
			
		});

		// Logout Route
		Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
	});
});
