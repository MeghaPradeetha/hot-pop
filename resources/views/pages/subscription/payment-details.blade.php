@extends('layouts.header-dashboard')

@section('content')
    <section class="d1-bg landing-body"> <!-- Landing page body -->

        <section class="">
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

                    <!-- Subscription Form -->
                    <form id="subscription-form" method="POST">
                        @csrf

                        <div class="payment-card">
                            <h4 class="p-b-24">Payment Details</h4>

                            <p class="f-20">Credit or Debit</p>
                            <div class="p-b-24">
                                <p class="form-label">Name on Card</p>
                                <input class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }} form-text" id="cardHolderName" name="email" placeholder="Card Name"
                                    required value="{{ old('email', $prefilledEmail ?? null) }}">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="p-b-50">
                                <p class="form-label">Card Number</p>
                                <!-- Payment Method Element -->
                                <div class="form-control form-text" id="card-element" style="height: auto;padding:20px 10px;">
                                    <!-- A Stripe Element will be inserted here. -->
                                </div>

                                <div class="text-danger f-16" id="card-errors"></div>
                            </div>

                            <span class="d-flex profile-bar justify-content-between">
                                <p>Total</p>
                                <p>$ {{ $plan->price }}</p>
                            </span>

                            <div class="text-center my-3">
                                <button class="main-button w-50" data-secret="{{ $intent->client_secret }}" id="card-button" type="submit">Pay Now</button>
                                {{-- <button class="main-button w-50" id="submit-button" type="submit">Pay Now</button> --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>

    </section> <!-- Landing page body end -->
@endsection

@push('js')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = Stripe('{{ env('STRIPE_KEY') }}');
        var elements = stripe.elements();
        var style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };
        var card = elements.create('card', {
            hidePostalCode: true,
            style: style
        });
        card.mount('#card-element');
        card.addEventListener('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });
        const cardHolderName = document.getElementById('cardHolderName');
        const cardButton = document.getElementById('card-button');
        const clientSecret = cardButton.dataset.secret;
        cardButton.addEventListener('click', async (e) => {
            e.preventDefault();
            cardButton.disabled = true;
            // console.log("attempting");
            const {
                setupIntent,
                error
            } = await stripe.confirmCardSetup(
                clientSecret, {
                    payment_method: {
                        card: card,
                        billing_details: {
                            name: cardHolderName.value
                        }
                    }
                }
            );
            if (error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
            } else {
                paymentMethodHandler(setupIntent.payment_method);
            }
        });

        function paymentMethodHandler(payment_method) {
            var form = document.getElementById('subscription-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'payment_method');
            hiddenInput.setAttribute('value', payment_method);
            form.appendChild(hiddenInput);
            form.submit();
        }
    </script>
@endpush
