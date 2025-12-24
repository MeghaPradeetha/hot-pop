<?php

namespace App\Entities\ProfilePreferences;

use App\Entities\BaseRepository;
use App\Notifications\ProfileMatch;

class ProfilePreferencesRepository extends BaseRepository
{

	public function __construct(ProfilePreference $model)
	{
		parent::__construct($model);
	}

	public function isMutualPreference($userId, $preferredUserId)
	{
		return $this->model::where([
			['user_id', '=', $userId],
			['preferred_user_id', '=', $preferredUserId],
			['preference', '=', $this->model::PREFERENCE_LIKE],
		])->exists() &&
			$this->model::where([
				['user_id', '=', $preferredUserId],
				['preferred_user_id', '=', $userId],
				['preference', '=', $this->model::PREFERENCE_LIKE],
			])->exists();
	}

	public function notifyUser($user, $preferred_user)
	{
		if ($user->want_email_notify == "yes") {
			//send email
			$email = [
				'greeting' => 'Good Luck..!',
				'body'     => 'You have a match with ' . $preferred_user->full_name,
				'text'     => 'Thank you'
			];
			$user->notify(new ProfileMatch($email));
		}
	}

}
