<?php

namespace App\Http\Controllers\Common;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Entities\Inquiries\Inquiry;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use EMedia\AppSettings\Entities\Settings\Setting;

class PagesController extends Controller
{

	public function privacyPolicy()
	{
		$data = Setting::where('setting_key', 'PRIVACY_POLICY')->first()->setting_value ?? null;
		return view('pages.settings.privacy-policy', ['pageTitle' => 'Privacy Policy', 'data' => $data]);
	}

	public function termsConditions()
	{
		$data = Setting::where('setting_key', 'TERMS_AND_CONDITIONS')->first()->setting_value ?? null;
		return view('pages.settings.terms-and-conditions', ['pageTitle' => 'Terms & Conditions', 'data' => $data]);
	}

	public function faqs()
	{
		$data = Setting::where('setting_key', 'FAQ')->first()->setting_value ?? null;
		return view('pages.settings.faqs', ['pageTitle' => 'Frequently Asked Questions', 'data' => $data]);
	}

	public function about()
	{
		$data = Setting::where('setting_key', 'ABOUT_US')->first()->setting_value ?? null;
		return view('pages.settings.about', ['pageTitle' => 'About Us', 'data' => $data]);
	}

	/**
	 *
	 * Show Contact Us Page
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function contactUs()
	{
		return view('pages.settings.contact', ['pageTitle' => 'Contact Us']);
	}

	/**
	 *
	 * Submit Contact Us Page
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postContactUs(Request $request)
	{
		$this->validate($request, [
			'name'        => 'required',
			'email'       => 'required|email',
			'userMessage' => 'required|max:255'
		]);
		$data = $request->only('name', 'email', 'address', 'userMessage');

		// recaptcha validation
		if (config('features.security.recaptcha_enabled')) {
			$secret = env('RECAPTCHA_SECRET_KEY');
			$recaptcha = new \ReCaptcha\ReCaptcha($secret);
			$response = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());
			if ($response->isSuccess()) {
				// Verified!
				// do nothing, proceed forward
			} else {
				$errors = $response->getErrorCodes();

				if (count($errors)) {
					return redirect()->route('contact-us')
						->with('error', 'Captcha validation failed. Try again or email us for support.')
						->withInput($data);
				}
			}
		}

		$data['timestamp'] = Carbon::now()->format('d/m/Y h:i:sA');
		$data['userIp'] = request()->ip();
		$data['sender_email'] = $request->get('email');

		// add to inquiry table
		Inquiry::create([
			'name'    => $request->name,
			'email'   => $request->email,
			// 'submitted_id' => $request->name,
			// 'user_id'      => $request->name,
			'message' => $request->userMessage,
		]);

		$webmaster = env('WEBMASTER_EMAIL') ?? env('MAIL_FROM_ADDRESS');
		if (empty($webmaster)) {
			throw new \InvalidArgumentException("Email receiver email has not been set.");
		}
		$receiverEmails = [$webmaster];

		Mail::send(['text' => 'oxygen::emails.text.contact-us'], $data, function ($mailMessage) use ($data, $receiverEmails)
		{
			$mailMessage->to($receiverEmails)
				->replyTo($data['sender_email'])
				->subject(config('app.name') . ' - Contact Us - Message Received');
		});

		return redirect()->back()->with('success', 'Your message has been sent.');
	}
}
