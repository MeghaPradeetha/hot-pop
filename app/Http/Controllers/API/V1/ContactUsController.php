<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use EMedia\Api\Docs\APICall;
use EMedia\Api\Docs\Param;
use EMedia\Devices\Auth\DeviceAuthenticator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactUsController extends Controller
{
    protected function sendMail(Request $request)
	{
		document(function () {
            return (new APICall)
            ->setGroup('Contact Us')
            ->setName('send mail')
            ->setParams([
                (new Param('message'))->dataType('string')->setDescription('User message'),
                (new Param('name'))->dataType('string')->setDescription('User Name')->optional(),
                (new Param('email'))->dataType('string')->setDescription('User Email')->optional(),
                (new Param('country_code'))->dataType('string')->setDescription('User Phone Code')->optional(),
                (new Param('phone'))->dataType('string')->setDescription('User Phone Number')->optional(),
            ])
            ->setSuccessExample('{
                "payload": null,
                "message": "We have received your message.",
                "result": true
            }');
        });
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

            Mail::send(['text' => 'oxygen::emails.text.contact-us'], $data, function($mailMessage) use ($data, $receiverEmails)
            {
                $mailMessage->to($receiverEmails)
                            // ->replyTo($data['sender_email'])
                            ->subject(config('app.name') . ' - Contact Us - Message Received');
            });

            return response()->apiSuccess(null, "We have received your message.");

        } catch (Exception $e) {

            return response()->apiError('Something went wrong, Try again');
        }

	}
}
