<?php


namespace App\Entities\Devices;

use ElegantMedia\OxygenFoundation\Entities\OxygenRepository;

class DevicesRepository extends OxygenRepository
{

	public function __construct(Device $model)
	{
		parent::__construct($model);
	}

	/**
	 *
	 * Create a device or update an existing one if found
	 *
	 * @param $data
	 * @param $userID
	 *
	 * @return Device|mixed
	 */
	public function createOrUpdateByIDAndType($data, $userID = null)
	{
		if (empty($data['device_id']) || empty($data['device_type'])) {
			throw new \InvalidArgumentException("device_id and device_type are required parameters");
		}

		$request = request();

		/** @var Device $device */
		$device = Device::where('device_id', $data['device_id'])
						->where('device_type', $data['device_type'])
						->first();

		if ($device) {
			// assign the device to this user
			// just in case if the device is given to a new user
			if ($userID) {
				$device->user()->associate($userID);
			}
			if ($request) {
				$device->latest_ip_address = request()->ip();
			}
			$device->refreshAccessToken();
			return $device;
		}

		if ($userID) {
			$data['user_id'] = $userID;
		}

		if ($request) {
			$data['latest_ip_address'] = request()->ip();
		}

		return $this->create($data);
	}

	public function findByDeviceForUser($userId, $deviceId)
	{
		return Device::where('device_id', $deviceId)
					 ->where('user_id', $userId)
					 ->first();
	}


	/**
	 *
	 * Retrun a device by access token
	 *
	 * @param $accessToken
	 *
	 * @return mixed
	 */
	public function getByToken($accessToken)
	{
		return Device::where('access_token', $accessToken)->get();
	}

	/**
	 *
	 * Get all devices by a user ID
	 *
	 * @param $userId
	 *
	 * @return mixed
	 */
	public function getByUserId($userId)
	{
		return Device::where('user_id', $userId)->get();
	}

	public function getByIdAndType($deviceId, $deviceType)
	{
		return Device::where('device_id', $deviceId)
				->where('device_type', $deviceType)
				->first();
	}

	/**
	 *
	 * Reset access tokens for all devices by a user
	 *
	 * @param $userId
	 */
	public function resetAccessTokensByUserId($userId)
	{
		$devices = $this->getByUserId($userId);

		foreach ($devices as $device) {
			$device->resetAccessToken();
		}
	}

	/**
	 *
	 * Delete a device by device ID
	 *
	 * @param $deviceId
	 */
	public function deleteByDeviceId($deviceId)
	{
		$devices = Device::where('device_id', $deviceId)->get();

		foreach ($devices as $device) {
			$device->delete();
		}
	}

	/**
	 *
	 * Delete a device by access token
	 *
	 * @param $accessToken
	 */
	public function deleteByToken($accessToken)
	{
		$devices = $this->getByToken($accessToken);

		foreach ($devices as $device) {
			$device->delete();
		}
	}
}
