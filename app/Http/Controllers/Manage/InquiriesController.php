<?php

namespace App\Http\Controllers\Manage;

use App\Entities\Inquiries\InquiriesRepository;
use App\Entities\Inquiries\Inquiry;
use App\Http\Controllers\Controller;
use EMedia\Formation\Builder\Formation;
use ElegantMedia\OxygenFoundation\Http\Traits\Web\CanCRUD;
use ElegantMedia\OxygenFoundation\Http\Traits\Web\CanRead;
use ElegantMedia\OxygenFoundation\Http\Traits\Web\FollowsConventions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InquiriesController extends Controller
{

	use FollowsConventions;

	// Uncomment this line if you're going to use Oxygen's Default Controller Methods
	 use CanCRUD;
	 use CanRead;

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

    /**
     *
     * This is the form shown when creating a new record.
     *
     * @param null $entity
     *
     * @return Formation
     */
    protected function getCreateForm($entity = null)
    {
        return new Formation($entity);
    }

    /**
     *
     * This is the form shown when editing an existing record.
     *
     * @param null $entity
     *
     * @return Formation
     */
    protected function getEditForm($entity = null)
    {
        return new Formation($entity);
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
