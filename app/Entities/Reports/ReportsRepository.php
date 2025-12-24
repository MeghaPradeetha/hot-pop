<?php

namespace App\Entities\Reports;

use App\Entities\BaseRepository;

class ReportsRepository extends BaseRepository
{

	public function __construct(Report $model)
	{
		parent::__construct($model);
	}

}
