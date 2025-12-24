<?php

namespace App\Channels;

use App\Notifications\VoipPushNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\FcmChannel as FcmFcmChannel;

class FCMChannel
{
	public function send($notifiable, VoipPushNotification $notification)
    {
        $message = $notification->toFCM($notifiable);
    }
}