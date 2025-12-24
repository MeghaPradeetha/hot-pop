@extends('layouts.header-dashboard')

@section('content')
    <section class="d1-bg landing-body">
        <div class="container py-4 py-lg-5">
            <div class="form-card p-4">
                <div class="header-bar position-relative mb-4">
                    <h3 class="headingH2">Payment Method</h3>
                </div>
                <div class="form-card p-200 shadow-none">
                    <div class="">
                        <div class="row">
                            <h4 class="p-b-32">Recommended Methods</h4>
                            @foreach ($methods as $method)
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div>
                                        <img src="{{ $method->card->brand == 'visa' ? asset('images/ic_payment_visa card.png') : asset('images/ic_payment_master card.png') }}" />
                                        <span class="ms-3 f-20">XXXX XXXX XXXX {{ $method->card->last4 }}</span>
                                    </div>
                                    <input class="form-check" name="card" type="radio" />
                                </div>
                            @endforeach

                            {{-- <div class="d-flex align-items-center justify-content-between">
                                <div><img src="{{ asset('images/ic_payment_visa card.png') }}" /><span class="ms-3 f-20">584X XXXX XXXX X254</span></div><input class="form-check"
                                    type="radio" />
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="text-center my-3">
                    <a href="/add-new-card"><button class="main-button" type="button">Add New Card</button></a>
                </div>
            </div>
        </div>
    </section>
@endsection
