<?php
// Example for a custom APN Channel (if you're using one)

namespace App\Channels;

use App\Notifications\VoipPushNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Channels\Channel;
use Illuminate\Notifications\Messages\MailMessage;

class APNChannel
{
    public function send($notifiable, VoipPushNotification $notification)
    {
        $message = $notification->toApn($notifiable);
    }
}
