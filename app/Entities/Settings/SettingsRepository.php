<?php

namespace App\Entities\Settings;

use App\Entities\BaseRepository;

class SettingsRepository extends BaseRepository
{

	public function __construct(Setting $model)
	{
		parent::__construct($model);
	}

}