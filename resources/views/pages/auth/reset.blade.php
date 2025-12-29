@extends('layouts.app')

@section('content')
    <section class="d1-bg landing-body">
        <section class="">
            <div class="container py-4 py-lg-5">
                <div class="form-card p-200">
                    <div class="header-card text-center">
                        <a class="" href="/"><img alt="logo" class="p-b-50" src="{{ asset('images/horizontalLogoTransparentBG.png') }}" width="146px" /></a>
                        <h3 class="p-b-24">Reset Password</h3>
                    </div>
                    <div class="">
                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="p-b-24">
                                <p class="form-label">Email</p>
                                <input class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }} form-text"
                                    name="email" type="email" value="{{ $email ?? old('email') }}" required autofocus>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="p-b-24">
                                <p class="form-label">New Password</p>
                                <input class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }} form-text"
                                    name="password" type="password" required>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="p-b-24">
                                <p class="form-label">Confirm Password</p>
                                <input class="form-control" name="password_confirmation" type="password" required>
                            </div>

                            <div class="text-center">
                                <button class="main-button w-50" type="submit">Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection
