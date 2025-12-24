<?php

namespace App\Http\Controllers\Manage;

use App\Entities\SubscriptionPlans\SubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{
    //

    public function index()
    {
        $plan = SubscriptionPlan::latest()->first();

        return view('pages.subscription.subscriptions', [
            'plan' => $plan
        ]);
    }
    public function create()
    {
        $user = auth()->user();
        $plan = SubscriptionPlan::latest()->first();

        return view('pages.subscription.payment-details', [
            'plan'   => $plan,
            'intent' => $user->createSetupIntent(),
        ]);
    }

    public function show()
    {
        $user = auth()->user();
        $subscription = $user->subscriptions()->latest()->first();

        $next_date = null;
        if ($subscription) {
            $nextBillingDate = $subscription->asStripeSubscription()->current_period_end;
            $next_date = Carbon::createFromTimestamp($nextBillingDate)->toDateString();
        }

        return view('pages.subscription.subscription-plan', [
            'subscription' => $subscription,
            'next_date'    => $next_date,
        ]);

    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $paymentMethod = $request->input('payment_method');

        $plan = SubscriptionPlan::latest()->first();
        if (!$plan) {
            return redirect()->back()->with('error', 'Error');
        }

        $user->createOrGetStripeCustomer();
        $newPaymentMethod = $user->addPaymentMethod($paymentMethod);
        $planID = $plan->plan_id;

        try {
            $user->newSubscription('default', $planID)->create($newPaymentMethod->id);
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Error creating subscription. ' . $e->getMessage()]);
        }

        return redirect()->back()->with('success', 'Payment Added');
    }

    public function cancel()
    {
        auth()->user()->subscription('default')->cancel();

        return redirect()->back()->with('success', 'Canceled Subscription');
    }
}
