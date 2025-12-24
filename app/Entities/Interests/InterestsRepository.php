<?php

namespace App\Entities\Interests;

use App\Entities\BaseRepository;

class InterestsRepository extends BaseRepository
{

	public function __construct(Interest $model)
	{
		parent::__construct($model);
	}

}
