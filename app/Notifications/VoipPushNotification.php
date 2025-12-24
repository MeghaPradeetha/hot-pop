<?php

namespace App\Notifications;

use App\Channels\APNChannel;
use App\Channels\FCMChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification as FacadesNotification;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use NotificationChannels\Apn\ApnChannel as ApnApnChannel;
use NotificationChannels\Apn\ApnMessage;
use Pushok\Client;
use Pushok\AuthProvider\Token;

class VoipPushNotification extends Notification
{
	use Queueable;

	use Queueable;

	protected $name;
	// protected $status;
	// protected $roomType;
	protected $profilePic;
	protected $callType;
	protected $token;
	protected $channelName;
	protected $user;

	public function __construct($name, $profilePic, $callType, $channelName, $token, $user)
	{
		$this->name = $name;
		// $this->status = $status;
		// $this->roomType = $roomType;
		$this->profilePic = $profilePic;
		$this->callType = $callType;
		$this->channelName = $channelName;
		$this->token = $token;
		$this->user = $user;
	}


	/**
	 * Get the notification's delivery channels.
	 *
	 * @return array<int, string>
	 */
	// Choose the correct notification channel based on the device type
	public function via($notifiable)
	{
		// Retrieve the device associated with the user
		$device = $notifiable->devices()->where('device_type', 'apple')->active()->first() ??
			$notifiable->devices()->where('device_type', 'android')->active()->first();

		if (!$device) {
			return [];  // No device information found, so do not send notification
		}

		// Check device type and send notification through the correct channel
		return $device->device_type == 'apple' ? [ApnApnChannel::class] : [FCMChannel::class];
	}

	public function toFCM($notifiable)
	{
		// Fetch all devices
		$devices = $notifiable->devices()->whereNotNull('device_push_token')->active()->get();

		if ($devices->isEmpty()) {
			Log::error("No valid device tokens found for user: " . $notifiable->id);
			return null;
		}

		// FCM data payload
		$data = [
			'name' => $this->name,
			// 'status' => $this->status,
			// 'roomType' => $this->roomType,
			'profile_pic' => $this->profilePic,
			'callType' => $this->callType,
			'channelName' => $this->channelName,
			'token' => $this->token,
			'user' => $this->user,
			'title' => 'Incoming Call',
			'body' => 'You have a new VoIP call',
			'type' => 'incoming_call',
			'created_at' => now()->toDateTimeString(),
			'timestamp' =>  now()->valueOf()
		];

		// Firebase Messaging
		try {
			$messaging = app('firebase.messaging');
			foreach ($devices as $device) {
				$message = CloudMessage::withTarget('token', $device->device_push_token)
					// ->withNotification(MessagingNotification::create('Incoming Call', 'You have a new VoIP call'))
					->withData($data)
					->withAndroidConfig(AndroidConfig::fromArray(['priority' => 'high']))
					->withApnsConfig(ApnsConfig::fromArray(['headers' => ['apns-priority' => '10']]));

				Log::info("Sending FCM notification to user: " . $notifiable->id . " on device: " . $device->id);

				$response = $messaging->send($message);

				Log::info("FCM Response for device " . $device->id . ": " . json_encode($response));
			}
		} catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
			Log::error("Firebase push token NOT FOUND for user: " . $notifiable->id . ". Error: " . $e->getMessage());
		} catch (\Exception $e) {
			Log::error("Error sending FCM notification to user: " . $notifiable->id . ". Error: " . $e->getMessage());
		}
	}



	public function toApn($notifiable)
	{

		$device = $notifiable->devices()->where('device_type', 'apple')->where('apn_key_token', '!=', null)->active()->latest()->first();
		if (!$device) {
			Log::error("No valid APNs token found for user: " . $notifiable->id);
			return null;
		}

		if ($device) {
			return ApnMessage::create()
				->title('Incoming Call')
				->body('You have a new VoIP call')
				->sound('default')
				->badge(1)
				->pushType('voip')
				->setCustom([                        // Correct method name is `setCustom()`
					'name' => $this->name,
					// 'status' => $this->status,
					// 'roomType' => $this->roomType,
					'profile_pic' => $this->profilePic,
					'callType' => $this->callType,
					'channelName' => $this->channelName,
					'token' => $this->token,
					'user' => $this->user,
					'title' => 'Incoming Call',
					'body' => 'You have a new VoIP call',
					'type' => 'incoming_call',
					'created_at' => now()->toDateTimeString(),
					'timestamp' =>  now()->valueOf(),
				]);
		}
	}


	// public function routeNotificationForApn($notifiable)
	// {
	// 	return $notifiable->apn_token; // User's APNs token
	// }
}
