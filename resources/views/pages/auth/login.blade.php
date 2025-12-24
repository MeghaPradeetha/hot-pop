@extends('layouts.app')

@section('content')
    <section class="d1-bg landing-body"> <!-- Landing page body -->

        <section class="">
            <div class="container py-4 py-lg-5">
                <div class="form-card p-200">
                    <div class="header-card text-center">
                        <a class="" href="/"><img alt="logo" class="p-b-50" src="{{ asset('images/horizontalLogoTransparentBG.png') }}" width="146px" /></a>
                        <div class="d-flex tab-bar">
                            <a class="w-50" href="{{ route('register') }}">
                                Register
                            </a>
                            <a class="w-50 active" href="{{ route('login') }}">
                                Login
                            </a>
                        </div>
                        <p class="p-t-32 p-b-24 f-16">Please enter your registered email and password below to login</p>
                    </div>
                    <div class="">
                        <form action="{{ route('login') }}" class="p-t-40" method="POST">
                            @csrf
                            <div class="p-b-24">
                                <p class="form-label">Email</p>
                                <input aria-describedby="emailHelp" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }} form-text" id="email" name="email"
                                    placeholder="Enter your email here" required type="email" value="{{ old('email', $prefilledEmail ?? null) }}">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="p-b-24">
                                <p class="form-label">Password</p>
                                <div class="icon-container">
                                    <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} form-text" id="password" name="password"
                                        placeholder="Enter your password here" required type="password">
                                    <a onclick="togglePasswordVisibility('password')">
                                        <i class="fa fa-eye-slash icon-over" id="password_img" style="color:black;"></i>
                                    </a>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="p-b-24 d-flex align-items-center justify-content-between">
                                <p class="f-16 d-flex align-items-center"><input class="form-check me-3" type="checkbox" /> Remember me</p>
                                <a class="a1" href="/reset">Forgot Password</a>
                            </div>

                            <div class="text-center">
                                <button class="main-button w-50" type="submit">Log In</button>
                            </div>

                        </form>

                        <div class="or-breaker"><span>Or</span></div>

                        <p class="text-center f-16 d5 fw-bold p-b-32">Continue With</p>
                        <div class="row p-b-32">
                            <div class="col-3 text-center">
                                <a href="{{ route('socialite.auth', 'facebook') }}"><img class="social-ic" src="{{ asset('images/ic_socialmedia_fb@2x.png') }}" width="56px" /></a>
                            </div>
                            <div class="col-3 text-center">
                                <a href="{{ route('socialite.auth', 'google') }}"><img class="social-ic" src="{{ asset('images/ic_socialmedia_gmail@2x.png') }}" width="56px" /></a>
                            </div>
                            <div class="col-3 text-center">
                                <a href="{{ route('socialite.auth', 'tiktok') }}"><img class="social-ic" src="{{ asset('images/ic_socialmedia_tiktok@2x.png') }}"
                                        width="56px" /></a>
                            </div>
                            <div class="col-3 text-center">
                                <a href="{{ route('socialite.auth', 'instagram') }}"><img class="social-ic" src="{{ asset('images/ic_socialmedia_instagram@2x.png') }}"
                                        width="56px" /></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </section> <!-- Landing page body end -->
@endsection
