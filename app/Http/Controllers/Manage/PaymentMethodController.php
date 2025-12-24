<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $methods = $user->paymentMethods();

        return view('pages.subscription.payment-methods', compact('methods'));
    }

    public function create()
    {
        $user = auth()->user();

        return view('pages.subscription.add-new-card', [
            'user'   => $user,
            'intent' => $user->createSetupIntent(),
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $paymentMethod = $request->input('payment_method');

        $user->createOrGetStripeCustomer();
        $user->addPaymentMethod($paymentMethod);

        return redirect('/payment-methods')->with('success', 'Payment Added');
    }
}
