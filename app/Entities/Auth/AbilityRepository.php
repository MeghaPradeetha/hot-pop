<?php

namespace App\Entities\Auth;

use App\Entities\BaseRepository;

class AbilityRepository extends BaseRepository
{

	public function __construct()
	{
		$model = new \App\Entities\Auth\Ability();
		parent::__construct($model);
	}

	public function deleteByCategory($id)
	{
		return $this->model->where('ability_category_id', $id)->delete();
	}

}