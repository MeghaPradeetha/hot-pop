<?php

namespace App\Entities\Devices;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{

	protected $fillable = [
		'device_id',
		'device_type',
		'device_push_token',
		'user_id',
		'latest_ip_address',
		'apn_key_token',
	];

	public function getShowable()
	{
		return [
			'device_id',
			'device_type',
			'device_push_token',
			'user_id',
			'access_token',
            'apn_key_token',
		];
	}
}
