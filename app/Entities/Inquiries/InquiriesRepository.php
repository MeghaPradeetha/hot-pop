<?php

namespace App\Entities\Inquiries;

use App\Entities\BaseRepository;

class InquiriesRepository extends BaseRepository
{

	public function __construct(Inquiry $model)
	{
		parent::__construct($model);
	}

}
