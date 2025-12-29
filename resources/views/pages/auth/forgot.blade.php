@extends('layouts.app')

@section('content')
    <section class="d1-bg landing-body"> <!-- Landing page body -->
        <section class="">
            <div class="container py-4 py-lg-5">

                {{-- @include('oxygen::partials.flash') --}}

                <div class="form-card p-200">
                    <div class="header-card text-center">
                        <a class="" href="/"><img alt="logo" class="p-b-50" src="{{ asset('images/horizontalLogoTransparentBG.png') }}" width="146px" /></a>
                        <h3 class="p-b-24">Reset Password</h3>
                        <p class="p-b-24 f-16 d5">Please enter a valid email address to recieve the resent link.</p>
                    </div>
                    <div class="">
                        <form action="{{ route('password.email') }}" method="POST">
                            @csrf
                            <div class="p-b-24">
                                <p class="form-label">Email</p>
                                <input aria-describedby="emailHelp" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }} form-text" id="exampleInputEmail1"
                                    name="email" placeholder="Type your email here" required type="email" value="{{ old('email', $prefilledEmail ?? null) }}">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="text-center p-b-100">
                                <p class="f-16 d5">Didn’t receive the link ? <button class="a1 border-0" type="submit">Send again</button></p>
                            </div>

                            <div class="text-center">
                                <button class="main-button w-50" data-bs-target="#alertModal" data-bs-toggle="modal" type="submit">Send a password reset link</button>
                            </div>

                        </form>

                        <div class="text-center p-t-24"><a class="a1" href="{{ route('login') }}">Go back to Login</a></div>

                    </div>
                </div>
            </div>
        </section>

    </section> <!-- Landing page body end -->

    <!-- Alert -->
    <div aria-hidden="true" aria-labelledby="alertModalLabel" class="modal fade" id="alertModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center">
                        <img alt="logo" class="p-b-50" src="{{ asset('images/img_forgotpassword_Mail sent.png') }}" width="161px" />
                        <h3 class="p-b-24">Link Sent</h3>
                        <p class="f-16 d5 p-b-50">Registered password link has been sent to the registered email address.</p>
                        <button class="secondary-button w-50" data-bs-dismiss="modal" type="button">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
