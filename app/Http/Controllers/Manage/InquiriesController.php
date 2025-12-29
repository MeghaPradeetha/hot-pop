<?php

namespace App\Http\Controllers\Manage;

use App\Entities\Inquiries\InquiriesRepository;
use App\Entities\Inquiries\Inquiry;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InquiriesController extends Controller
{




	protected $repo;

	public function __construct(InquiriesRepository $repo)
	{
		$this->repo = $repo;

		$this->resourceEntityName = 'Inquiry';
        $this->isDestroyAllowed = true;
	}

    protected function getResourcePrefix()
    {
        return 'manage.inquiries';
    }

	protected function getIndexRouteName($suffix = 'index'): string
	{
		return 'manage.inquiries.index';
	}



	public function email( $id)
    {
		$entity = Inquiry::find($id);

        return view('manage.inquiries.reply-email',compact('entity'));
    }

	public function send(Request $request, $id)
    {
		$entity = Inquiry::find($id);

		$email = [
			'greeting' => 'Hi ' . $entity->name,
			'body'     => $request->message,
			'text'     => 'Thank you, ParentSeeking'
		];

		$emailMessage = implode("\n\n", $email);

		Mail::raw($emailMessage, function ($mail) use($entity) {
			$mail->to($entity->email)
				 ->subject('Inquiry Reply');
		});

        return redirect()->back();
    }
}
