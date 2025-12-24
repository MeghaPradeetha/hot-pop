@extends('layouts.header-dashboard')

@section('content')
    <section class="d1-bg landing-body"> <!-- Landing page body -->

        <section class="">
            <div class="container py-4 py-lg-5">
                <div class="form-card p-4">
                    <div class="header-bar position-relative mb-4">
                        <h3 class="headingH2">Add New Card</h3>
                    </div>
                    <div class="form-card p-200 shadow-none">
                        <form id="subscription-form" method="post">
                            @csrf
                            <h4 class="p-b-24">Payment Details</h4>
                            <p class="f-20">Credit or Debit</p>
                            <div class="p-b-24">
                                <p class="form-label">Name on Card</p>
                                <input aria-describedby="emailHelp" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }} form-text" id="exampleInputEmail1"
                                    name="email" placeholder="Card Name" required type="email" value="{{ old('email', $prefilledEmail ?? null) }}">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="p-b-24">
                                <p class="form-label">Card Number</p>
                                <!-- Payment Method Element -->
                                <div class="form-control form-text" id="card-element" style="height: auto;padding:20px 10px;">
                                    <!-- A Stripe Element will be inserted here. -->
                                </div>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                    </div>
                    <div class="text-center my-3">
                        <button class="main-button w-50" data-secret="{{ $intent->client_secret }}" id="card-button" type="submit">Save Changes</button>
                        {{-- <button class="main-button w-25" type="button">Save Changes</button> --}}
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
            // if (event.error) {
            //     displayError.textContent = event.error.message;
            // } else {
            //     displayError.textContent = '';
            // }
        });
        const cardHolderName = document.getElementById('exampleInputEmail1');
        const cardButton = document.getElementById('card-button');
        const clientSecret = cardButton.dataset.secret;
        cardButton.addEventListener('click', async (e) => {
            e.preventDefault();
            console.log("attempting");
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
