<?php

namespace App\Http\Controllers\API\V1\Auth;

use EMedia\Api\Docs\Param;
use EMedia\Api\Docs\APICall;
use Illuminate\Http\Request;
use App\Services\FirebaseService;
use App\Entities\Auth\UsersRepository;
use Illuminate\Auth\Events\Registered;
use EMedia\Api\Domain\Postman\PostmanVar;
use App\Entities\Devices\DevicesRepository;
use EMedia\Devices\Auth\DeviceAuthenticator;

class AuthController extends \EMedia\Oxygen\Http\Controllers\API\V1\Auth\AuthController
{

	protected $usersRepository;
	protected $devicesRepo;

	public function __construct(UsersRepository $usersRepository, DevicesRepository $devicesRepo)
	{
		$this->usersRepository = $usersRepository;
		$this->devicesRepo = $devicesRepo;
	}

	/**
	 *
	 * Fillable parameters when registering a new user
	 * Only add fields that must be auto-filled
	 *
	 */
	protected $fillable = [
		'email',
	];

	protected $fillableDeviceParams = [
		'device_id',
		'device_type',
		'device_push_token',
		'apn_key_token'
	];

	/**
	 *
	 * Validation rules to be enforced when registering.
	 *
	 * @return array
	 */
	protected function getRegistrationValidationRules(): array
	{
		return [
			'email'       => 'required|email|unique:users,email',
			'password'    => 'required|confirmed|min:8',
			'device_id'   => 'required',
			'device_type' => 'required',
		];
	}

	/**
	 *
	 * These are the parameters for APIDoc
	 *
	 * @return array
	 */
	protected function getRegistrationApiDocParams(): array
	{
		return [
			'device_id|Unique ID of the device|{{$guid}}',
			'device_type|Type of the device `APPLE` or `ANDROID`|example:apple',
			'device_push_token|optional|Unique push token for the device',
			'apn_key_token|optional|nique apn token for the device',
			'email|Email address of user|{{$randomExampleEmail}}',
			'password|Password. Must be at least 8 characters.|{{login_user_pass}}',
			'password_confirmation|Confirm password. Must be at least 8 characters.|{{login_user_pass}}',
		];
	}

	// Add your logic here
	/**
	 *
	 * Register a user.
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function register(Request $request)
	{
		document($this->getRegisterApiDocumentFunction());

		$this->validate($request, $this->getRegistrationValidationRules());

		$data = $request->only($this->fillable);
		$data['password'] = bcrypt($request->password);
		$data['confirmation_code'] = mt_rand(1000, 9999);
		$user = $this->usersRepository->create($data);

		$responseData = $user->refresh()->toArray();
		$deviceData = $request->only($this->fillableDeviceParams);
		$deviceData['apn_key_token'] = $request->apn_key_token ?? null;
		$device = $this->devicesRepo->createOrUpdateByIDAndType($deviceData, $user->id);
		$responseData['access_token'] = $device->access_token;

		// $stripeCustomer = $user->createAsStripeCustomer();
		// $user->stripe_id = $stripeCustomer->id;

		event(new Registered($user));
		$user->email_confirmation_sent_at = now()->toDateTimeString();

		$user->save();

		FirebaseService::addNewUser($user);

		return response()->apiSuccess($responseData);
	}

	public function login(Request $request)
	{
		document(function ()
		{
			return (new APICall())->setName('Login')
				->setParams([
					(new Param('device_id', Param::TYPE_STRING, 'Unique ID of the device'))
						->setVariable(PostmanVar::UUID),
					(new Param('device_type', Param::TYPE_STRING, 'Type of the device `APPLE` or `ANDROID`'))
						->setExample('apple'),
					(new Param(
						'device_push_token',
						Param::TYPE_STRING,
						'Unique push token for the device'
					))->optional(),
					(new Param('apn_key_token', Param::TYPE_STRING, 'Unique apn token for the device'))->optional(),
					(new Param('email'))->setExample('test@example.com')->setVariable('{{test_user_email}}'),
					(new Param('password'))->setVariable('{{login_user_pass}}'),
				])
				->setApiKeyHeader()
				->setSuccessObject(app('oxygen')->getUserClass())
			;
		});

		$this->validate($request, [
			'device_id'   => 'required',
			'device_type' => 'required',
			'email'       => 'required|email',
			'password'    => 'required',
		]);

		if (!auth()->attempt($request->only('email', 'password'), true)) {
			return response()->apiErrorUnauthorized(trans('auth.failed'));
		}

		$user = auth()->user();
		$response = $user->toArray();
		$device = $this->devicesRepo->findByDeviceForUser($user->id, $request->get('device_id'));

		// return an existing device
		if ($device) {
			// reset the push token and access tokens
			// because someone else could be logging in from the same device
			if ($request->device_push_token && ($device->device_push_token !== $request->device_push_token)) {
				$device->device_push_token = $request->device_push_token;
			}
			if ($request->apn_key_token && ($device->apn_key_token !== $request->apn_key_token)) {
				$device->apn_key_token = $request->apn_key_token;
			}

			$device->refreshAccessToken();
		} else {
			// if this is a new device, create it
			$device = $this->devicesRepo->createOrUpdateByIDAndType(
				$request->only('device_id', 'device_type', 'device_push_token', 'apn_key_token'),
				$user->id
			);
		}

		$response['access_token'] = $device->access_token;

		return response()->apiSuccess($response);
	}

	public function verifyEmail(Request $request)
	{
		document(function ()
		{
			return (new APICall)
				->setGroup('Auth')
				->setName('Email Verification')
				->setParams([
					(new Param('code', Param::TYPE_STRING, 'Verification Code'))->setDefaultValue('1234')
				])
				->setSuccessObject(app('oxygen')->getUserClass());
		});

		$request->validate([
			'code' => 'required',
		]);

		$user = DeviceAuthenticator::getUserByAccessToken();

		if ($user->confirmation_code == $request->code) {
			$user->update(['email_confirmed_at' => now()->toDateTimeString()]);
		} else {
			return response()->apiError('Invalid verification code, Resend and try again');
		}

		return response()->apiSuccess($user);
	}

	public function resendCode()
	{
		document(function ()
		{
			return (new APICall)
				->setGroup('Auth')
				->setName('Resend Verification Code');
		});

		$user = DeviceAuthenticator::getUserByAccessToken();
		$user->confirmation_code = mt_rand(1000, 9999);
		$user->email_confirmation_sent_at = now()->toDateTimeString();
		$user->save();

		// Send Email verification code
		event(new Registered($user));

		return response()->apiSuccess(null, 'A verification code has been sent to your email.');
	}

	public function pushTokenUpdate(Request $request)
	{

		document(function ()
		{
			return (new APICall)
				->setGroup('Auth')
				->setName('Push Token Update')
				->setParams([
					'device_push_token|optional|Unique push token for the device',
					'apn_key_token|optional|Unique apn token for the device'
				]);
		});

		$accessToken = request()->header('X-Access-Token');
		$device = DeviceAuthenticator::findDeviceByToken($accessToken);

		if (!$device) {
			return response()->apiError('Device not found');
		}

		if ($request->device_push_token && ($device->device_push_token !== $request->device_push_token)) {
			$device->device_push_token = $request->device_push_token;
		}
		if ($request->apn_key_token && ($device->apn_key_token !== $request->apn_key_token)) {
			$device->apn_key_token = $request->apn_key_token;
		}

		// $device->device_push_token = $request->device_push_token;
		$device->save();

		return response()->apiSuccess(null, 'success');
	}
}

