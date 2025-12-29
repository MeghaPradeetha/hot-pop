<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactUsController extends APIBaseController
{
    protected function sendMail(Request $request)
	{

        try {
            // $user = \Illuminate\Support\Facades\Auth::user();

            $data['timestamp'] = Carbon::now()->format('d/m/Y h:i:sA');
            $data['userIp']  = request()->ip();
            $data['sender_email'] = $request->email;
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['phone'] = $request->country_code." ". $request->phone;
            $data['userMessage'] = $request->message;

            $webmaster = env('WEBMASTER_EMAIL');
            if (empty($webmaster)) throw new \InvalidArgumentException("Email receiver email has not been set.");
            $receiverEmails = [$webmaster];

            Mail::send(['text' => 'emails.contact-us'], $data, function($mailMessage) use ($data, $receiverEmails)
            {
                $mailMessage->to($receiverEmails)
                            // ->replyTo($data['sender_email'])
                            ->subject(config('app.name') . ' - Contact Us - Message Received');
            });

            return $this->respondSuccess(null, "We have received your message.");

        } catch (Exception $e) {

            return $this->respondError('Something went wrong, Try again');
        }

	}
}
