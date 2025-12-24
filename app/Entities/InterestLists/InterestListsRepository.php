<?php

namespace App\Entities\InterestLists;

use App\Entities\BaseRepository;

class InterestListsRepository extends BaseRepository
{

	public function __construct(InterestList $model)
	{
		parent::__construct($model);
	}

}
