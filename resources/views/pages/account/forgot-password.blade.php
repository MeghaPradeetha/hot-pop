@extends('layouts.header-dashboard')

@section('content')
    <section class="d1-bg landing-body"> <!-- Landing page body -->

        @include('oxygen::partials.flash')

        <div class="container py-4 py-lg-5">
            <div class="form-card p-4">
                <div class="header-bar position-relative mb-4">
                    <h3 class="headingH2">Forgot Password</h3>
                </div>
                <form method="POST" action="{{ route('password.email') }}">
                    <div class="form-card p-200 shadow-none">
                        @csrf
                        <p class="text-center f-16 p-b-50">Please enter a valid email address to recieve the resent link.</p>
                        <div class="p-b-24">
                            <p class="form-label">Email</p>
                            <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }} form-text" id="exampleInputEmail1" name="email"
                                placeholder="Type your email here" aria-describedby="emailHelp" value="{{ old('email', $prefilledEmail ?? null) }}" required>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="text-center f-16">
                            Didn’t receive a link ? <button type="submit" class="a1 border-0">Send again</button>
                        </div>
                    </div>
                    <div class="text-center my-3">
                        <button type="submit" class="main-button w-50">Send a password reset link</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    @if (Session::has('status'))
        <div class="modal fade show" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true" style="display: block;">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center">
                            <img src="{{ asset('images/img_forgotpassword_Mail sent.png') }}" width="161px" alt="logo" class="p-b-50" />
                            <h3 class="p-b-24">Link Sent</h3>
                            <p class="f-16 d5 p-b-50">Registered password link has been sent to the registered email address.</p>
                            <a href="/forgot-password" class="secondary-button w-50">Ok</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
