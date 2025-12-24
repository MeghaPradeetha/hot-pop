<?php

namespace App\Entities\SubscriptionPlans;

use App\Entities\BaseRepository;

class SubscriptionPlansRepository extends BaseRepository
{

	public function __construct(SubscriptionPlan $model)
	{
		parent::__construct($model);
	}

}
