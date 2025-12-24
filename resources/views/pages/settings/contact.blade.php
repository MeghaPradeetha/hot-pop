@extends('layouts.header-dashboard')

@push('meta')
    <meta content="NOINDEX, NOFOLLOW" name="ROBOTS">

    @if (config('features.security.recaptcha_enabled'))
        <script src='https://www.google.com/recaptcha/api.js'></script>
    @endif
@endpush

@section('content')
    <section class="d1-bg landing-body">
        <div class="container py-4 py-lg-5">
            <div class="form-card p-4">
                <div class="header-bar position-relative mb-4">
                    <h3 class="headingH2">Contact Us</h3>
                </div>
                <div class="form-card p-200 shadow-none">
                    <form action="{{ route('contact-us') }}" class="form-horizontal" method="POST">
                        @csrf
                        <p class="f-20 p-b-24">Leave us a message</p>

                        <div class="p-b-24">
                            <p class="form-label">Name</p>
                            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }} form-text" name="name" placeholder="Type your name here" required
                                value="{{ old('name') }}">
                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="p-b-24">
                            <p class="form-label">Email</p>
                            <input aria-describedby="emailHelp" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }} form-text" name="email"
                                placeholder="Type your email address here" required type="email" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="p-b-24">
                            <p class="form-label">Address</p>
                            <input class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }} form-text" name="address" placeholder="Type your address here" required
                                value="{{ old('address') }}">
                            @if ($errors->has('address'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="p-b-24">
                            <p class="form-label">Message</p>
                            <textarea class="form-text-area" name="userMessage" placeholder="Type your message here">{{ old('userMessage') }}</textarea>
                            @if ($errors->has('userMessage'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('userMessage') }}</strong>
                                </span>
                            @endif
                        </div>

                        @if (config('features.security.recaptcha_enabled'))
                            <div class="form-group">
                                <div class="control-group col-md-9 col-md-offset-3">
                                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                                </div>
                            </div>
                        @endif

                        <div class="text-center my-3">
                            <center><button class="main-button w-25" type="submit">Submit</button></center>
                            {{-- <button type="submit" class="main-button w-25" data-bs-toggle="modal" data-bs-target="#contactModal">Submit</button> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Alert -->
    @if (Session::has('success'))
        <div aria-hidden="true" aria-labelledby="contactModalLabel" class="modal fade show" id="contactModal" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                            <h3 class="p-b-50">Thank you for submitting</h3>
                            <p class="f-16 d5 p-b-50">Our team will contact you via email shortly</p>
                            {{-- <p class="f-16 d5 p-b-50">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p> --}}
                            <a href=""><button class="main-button" type="button">Submit Another Form</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
