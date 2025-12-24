<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entities\EarlyAccesses\EarlyAccess;

class EarlyAccessController extends Controller
{
    public function getSignupPage()
    {
        return view('pages.auth.early_signup');
    }

    public function earlySignup(Request $request)
    {
        $validate = $this->validate($request, [
            'name'           => 'required',
            'email'          => 'required|email|unique:early_access_data,email',
            'contact_number' => 'required'
        ]);

        EarlyAccess::create($validate);

        return back()->with('success', "Thanks for registering with early access. You will receive an email from us when this is available to use.");


    }

    public function getEarlyAccessData()
    {

        $allItems = EarlyAccess::paginate();

        return view('manage.early_access_data.index', compact('allItems'));
    }
}
