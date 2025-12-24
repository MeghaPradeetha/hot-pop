@extends('layouts.header-dashboard')

@section('content')
    <section class="d1-bg landing-body">
        <div class="container py-4 py-lg-5">
            <div class="form-card p-4">
                <div class="header-bar position-relative mb-4">
                    <h3 class="headingH2">Subscription Plan</h3>
                </div>
                <div class="subscard text-center">
                    <h3>Premium Plan</h3>

                    <div class="row p-t-50 p-b-50">
                        <div class="col-lg-6 offset-lg-3 styled">
                            <h1 class="headingH1 p-b-32">${{ $plan->price }} <span class="d2">Per month</span></h1>
                            <ul>
                                <li>Lorem ipsum dolor sit amet. lorem ipsum dolor sit</li>
                                <li>Lorem ipsum dolor sit amet. lorem ipsum dolor sit</li>
                                <li>Lorem ipsum dolor sit amet. lorem ipsum dolor sit</li>
                                <li>Lorem ipsum dolor sit amet. lorem ipsum dolor sit</li>
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="text-center my-3">
                    <a href="/payment-details"><button class="main-button w-50" type="button">Subscribe</button></a>
                </div>
            </div>
        </div>
    </section>
@endsection
