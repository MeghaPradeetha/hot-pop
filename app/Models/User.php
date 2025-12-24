<?php

namespace App\Models;

use App\Entities\Files\File;
use Laravel\Cashier\Billable;
use App\Entities\Devices\Device;
use App\Entities\Reports\Report;
use Laravel\Sanctum\HasApiTokens;
use App\Entities\Inquiries\Inquiry;
use App\Entities\ChatRooms\ChatRoom;
use App\Entities\Interests\Interest;
use Illuminate\Notifications\Notifiable;
use App\Entities\Personalities\Personality;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use EMedia\Oxygen\Entities\Traits\OxygenUserTrait;
use App\Entities\ProfilePreferences\ProfilePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{

	// use HasApiTokens;
	use HasFactory, Notifiable;
	use OxygenUserTrait;
	use Billable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	const PROFILE_SETUP       = 1;
	const PROFILE_INTRO       = 2;
	const PROFILE_IMAGES      = 3;
	const PROFILE_PERSONALITY = 4;
	const LOGGED_IN_PAGE      = 5;

	const CALL_INCOMING      = 'incoming';
	const CALL_OUTGOING      = 'outgoing';
	const CALL_INCALL      = 'incall';
	const CALL_DECLINED      = 'free';


	protected $fillable = [
		'name',
		'age',
		'email',
		'password',
		'location',
		'gender',
		'subscription_plan',
		'last_name',
		'phone',
		'password',
		'email_confirmed_at',
		'profile_setup_step',
		'want_email_notify',
		'fcm_token',
		'active_status',
		'last_active',
		'twilio_token',
		'online_status',
		'read_receipts',
		'timezone',
		'latitude',
		'longitude',
		'pm_type',
		'pm_last_four',
		'stripe_id',
		'confirmation_code'
		//'intro_video_title',
		//'intro_video_file_path'
		//'email_notification'
	];
	protected $visible = [
		'id',
		'name',
		'first_name',
		'full_name',
		'avatar2',
		'video',
		'age',
		'email',
		'location',
		'gender',
		'subscription_plan',
		'last_name',
		'phone',
		'profile_setup_step',
		'active_status',
		'last_active',
		'online_status',
		'read_receipts',
		'is_profile_completed',
		'is_email_verified',
		'want_email_notify',
		'personality',
		'files',
		'chat_room'
	];


	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password',
		'remember_token',
		'avatar_path',
		'avatar_disk'
	];

	/**
	 *
	 * The attributes that should be visible for arrays.
	 *
	 * @var string[]
	 */


	/**
	 *
	 * The attributes that should be automatically appended.
	 *
	 * @var string[]
	 */
	protected $appends = [
		'first_name',
		'full_name',
		'is_profile_completed',
		'is_email_verified',
		'avatar',
		'avatar2',
		'video'
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $with = [
		// 'personality',
		'latestPhotoFile',

	];

	protected $casts = [
		'id'                => 'string',
		'email_verified_at' => 'datetime',
		'last_active'       => 'datetime',
		'password'          => 'hashed',
	];

	/**
	 *
	 * Columns which are searchable by default.
	 *
	 * @var string[]
	 */
	protected $searchable = [
		'name',
		'last_name',
		'email'
	];

	/**
	 *
	 * Fields that may be added to the API responses.
	 *
	 * @return string[]
	 */
	public function getExtraApiFields(): array
	{
		return [
			'access_token',
			'is_profile_completed' => 'boolean',
			'is_email_verified'    => 'boolean',
			'personality'          => ['type' => 'object', 'items' => 'Personality'],
			'files'                => ['type' => 'array', 'items' => 'File'],
			'chat_room'            => ['type' => 'object', 'items' => 'ChatRoom'],
		];
	}

	public function getIsEmailVerifiedAttribute(): bool
	{
		return (bool) $this->email_confirmed_at;
	}

	public function getIsProfileCompletedAttribute(): bool
	{
		return $this->profile_setup_step == self::LOGGED_IN_PAGE;
	}

	public function getAvatarAttribute()
	{
		// if ($this->avatar_url)
		// 	return $this->avatar_url;

		return $this->latestPhotoFile->file_path ?? $this->avatar_path;
		// $file = $this->files()->where('category', 'photos')->where('attachable_id', $this->id)->latest()->first();

		// return $file->file_url ?? null;
	}

	public function getAvatar2Attribute()
	{
		// if ($this->avatar_url)
		// 	return $this->avatar_url;

		return $this->latestPhotoFile->file_url ?? $this->avatar_url;
		// $file = $this->files()->where('category', 'photos')->where('attachable_id', $this->id)->latest()->first();

		// return $file->file_url ?? null;
	}

	public function getVideoAttribute()
	{
		$file = File::where('category', 'video')->where('attachable_id', $this->id)->latest()->first();

		return $file->file_url ?? null;
	}

	public function getLastActiveTimeAttribute()
	{
		return $this->last_active?->diffForHumans();
	}

	public function getHasSubscriptionAttribute()
	{
		// Check Trial
		$trialEndDate = $this->trial_ends_at;
		if ($trialEndDate && now()->lte($trialEndDate)) {
			return true;
		}

		$subs = auth()->user()->subscriptions()->latest()->first();
		if ($subs) {
			if ($subs->ends_at && now()->gt($subs->ends_at)) {
				return false;
			}
			return true;
		}
		//Check Subs

		return false;
	}

	public function getHasUnreadChatAttribute()
	{
		return ChatRoom::where(function ($q)
		{
			$q->where('sender_id', $this->id)->orWhere('receiver_id', $this->id);

		})
			->whereHas('chats', function ($query)
			{
				return $query->where('read_status', '0')->where('sender_id', '!=', $this->id);
			})
			->count();
		// return $this->chats()->where('read_status', '0')->count();
	}

	public function getHasMatchedProfileAttribute()
	{
		$preferred_by = ProfilePreference::where('preferred_user_id', $this->id)->pluck('user_id');
		$filter = User::whereIn('id', $preferred_by)->where('deleted_at', null)->pluck('id');

		//matched profile with user details
		$profile_match = ProfilePreference::with('preferredUser')
			->where('user_id', $this->id)
			->where('preference', ProfilePreference::PREFERENCE_LIKE)
			->whereIn('preferred_user_id', $filter)
			->get();

		$profile_count = $profile_match->count();

		return $profile_count;
	}
	public function report()
	{

		return $this->hasMany(Report::class, 'id');
	}

	public function inquiry()
	{
		return $this->hasMany(Inquiry::class, 'id');
	}

	public function files()
	{
		return $this->hasMany(File::class, 'attachable_id')->whereNot('category', 'chat');
	}
	public function latestPhotoFile()
	{
		return $this->hasOne(File::class, 'attachable_id')->where('category', 'photos')->latest();
	}

	public function personality()
	{
		return $this->hasOne(Personality::class);
	}

	public function interest()
	{
		return $this->hasMany(Interest::class, 'id');
	}

	public function preferences()
	{
		return $this->hasMany(ProfilePreference::class);
	}


	public function devices()
	{
		return $this->hasMany(Device::class);
	}

	public function routeNotificationForApn()
    {
         $devices = $this->devices()->whereNotNull('apn_key_token')->get();

    	// Return an array of device push tokens
    	return $devices->pluck('apn_key_token')->toArray();
    }
}
