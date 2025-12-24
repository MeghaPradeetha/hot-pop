@extends('layouts.header-dashboard')

@section('content')
    <section class="d1-bg landing-body">
        <div class="container py-4 py-lg-5">
            @include('oxygen::partials.flash')

            <div class="form-card p-4">
                <div class="header-bar position-relative mb-4">
                    <h3 class="headingH2">Subscription Plan</h3>
                </div>
                <div class="form-card p-200 shadow-none">
                    <div class="subscard p-lg-5 p-4">
                        <div class="row">
                            <div class="col-lg-6 pb-lg-4">
                                <p class="f-20">Subscription Status</p>
                            </div>
                            <div class="col-lg-6 text-end pb-lg-4">
                                <p class="f-20 fw-bold text-capitalize">{{ $subscription->stripe_status ?? 'None' }}</p>
                            </div>
                            <div class="col-lg-6 pb-lg-4">
                                <p class="f-20">Subscription Type</p>
                            </div>
                            <div class="col-lg-6 text-end pb-lg-4">
                                <p class="f-20 fw-bold text-capitalize">{{ $user->subscription_plan ?? ($subscription->type ?? 'Free') }}</p>
                                @if ($user->trial_ends_at)
                                    <p class="f-20">{{ \Carbon\Carbon::parse($user->trial_ends_at)->diffInDays() }} Day Left</p>
                                @endif
                                {{-- <p class="f-20">{{ date($user->trial_ends_at)->diffForHumans() }}</p> --}}
                            </div>
                            <div class="col-lg-6 pb-lg-4">
                                <p class="f-20">Next Billing Date</p>
                            </div>
                            <div class="col-lg-6 text-end pb-lg-4">
                                <p class="f-20 fw-bold">{{ $next_date }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center my-3">
                    <a href="{{ route('subscription-cancel') }}"><button class="secondary-button m-b-20" type="button">Cancel Subscription</button></a><br />
                    <a href="{{ route('payment-methods') }}"><button class="main-button" type="button">Payment Method</button></a>
                </div>
            </div>
        </div>
    </section>
@endsection
