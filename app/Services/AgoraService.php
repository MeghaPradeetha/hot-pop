<?php

namespace App\Services;

use BoogieFromZk\AgoraToken\RtcTokenBuilder2;

class AgoraService
{
    public function getToken($uid, $channelName)
    {
        $appId = config('services.agora.app_id');
		$appCertificate = config('services.agora.app_certificate');
		$expireTimeInSeconds = config('services.agora.token_expiry');

		$role = RtcTokenBuilder2::ROLE_PUBLISHER;
		$privilegeExpiredTs = time() + $expireTimeInSeconds;

		return $token = RtcTokenBuilder2::buildTokenWithUid(
			$appId,
			$appCertificate,
			$channelName,
			$uid,
			$role,
			$privilegeExpiredTs
		);
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
