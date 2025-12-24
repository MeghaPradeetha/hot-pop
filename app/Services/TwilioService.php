<?php

namespace App\Services;

use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;
use Twilio\Jwt\Grants\VoiceGrant;

class TwilioService
{
    public function getToken($type, $identity, $room_name = null)
    {
        $token = $this->createAccessToken($identity);

        $grant = $this->getGrantData($type, $room_name);
        $token->addGrant($grant);

        return $token->toJWT();
    }

    private function getGrantData($type, $room_name)
    {
        if ($type == 'video') {
            return $this->getVideoGrant($room_name);
        } elseif ($type == 'audio') {
            return $this->getAudioGrant();
        } else {
            throw new \InvalidArgumentException('Invalid type. Allowed types are "audio" and "video".');
        }
    }

    private function createAccessToken($identity)
    {
        $twilioAccountSid = config('services.twilio.account_sid');
        $twilioApiKey = config('services.twilio.api_key');
        $twilioApiSecret = config('services.twilio.api_secret');

        return new AccessToken(
            $twilioAccountSid,
            $twilioApiKey,
            $twilioApiSecret,
            7200,
            $identity
        );
    }

    private function getAudioGrant()
    {
        $outgoingApplicationSid = config('services.twilio.app_sid');

        // Create Voice grant
        $voiceGrant = new VoiceGrant();
        $voiceGrant->setOutgoingApplicationSid($outgoingApplicationSid);

        // Optional: add to allow incoming calls
        $voiceGrant->setIncomingAllow(true);

        return $voiceGrant;
    }

    private function getVideoGrant($room_name)
    {
        $videoGrant = new VideoGrant();
        $videoGrant->setRoom("room-$room_name");

        return $videoGrant;
    }
}
