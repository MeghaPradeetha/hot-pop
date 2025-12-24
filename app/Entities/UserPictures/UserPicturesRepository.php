<?php

namespace App\Entities\UserPictures;

use App\Entities\BaseRepository;

class UserPicturesRepository extends BaseRepository
{

	public function __construct(UserPicture $model)
	{
		parent::__construct($model);
	}

}
