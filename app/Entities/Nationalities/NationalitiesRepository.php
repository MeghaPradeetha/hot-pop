<?php

namespace App\Entities\Nationalities;

use App\Entities\BaseRepository;

class NationalitiesRepository extends BaseRepository
{

	public function __construct(Nationality $model)
	{
		parent::__construct($model);
	}

}
