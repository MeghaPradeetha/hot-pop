@extends('layouts.header-dashboard')

@section('content')
    <section class="d1-bg landing-body">
        <div class="container py-4 py-lg-5">

            @include('oxygen::partials.flash')

            <div class="form-card p-4">
                <div class="header-bar position-relative mb-4">
                    <h3 class="headingH2">Change Password</h3>
                </div>
                <form action="{{ route('account.password') }}" class="form form-horizontal" method="POST">
                    @csrf
                    @method('put')
                    <div class="form-card p-200 shadow-none">
                        <div class="p-b-24">
                            <p class="form-label">Current Password</p>
                            <div class="icon-container">
                                <input class="form-control{{ $errors->has('current_password') ? ' is-invalid' : '' }} form-text" id="current_password" name="current_password"
                                    placeholder="Type your password here" required type="password">
                                <a onclick="togglePasswordVisibility('current_password')"><i class="fa fa-eye-slash icon-over" id="current_password_img" style="color:black;"></i></a>

                                @if ($errors->has('current_password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('current_password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="p-b-24">
                            <p class="form-label">New Password</p>
                            <div class="icon-container">
                                <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} form-text" id="password" name="password"
                                    placeholder="Type your password here" required type="password">
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
                        <div class="p-b-24">
                            <p class="form-label">Confirm Password</p>
                            <div class="icon-container">
                                <input class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }} form-text" id="password_confirmed"
                                    name="password_confirmation" placeholder="Confirm your password here" required type="password">
                                <a onclick="togglePasswordVisibility('password_confirmed')"><i class="fa fa-eye-slash icon-over" id="password_confirmed_img"
                                        style="color:black;"></i></a>

                                @if ($errors->has('password_confirmation'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <script>
                            function togglePasswordVisibility(FieldId) {
                                var passwordField = document.getElementById(FieldId);
                                var icon = document.getElementById(FieldId + '_img');

                                if (passwordField.type === "password") {
                                    passwordField.type = "text";
                                    icon.classList.remove('fa-eye-slash');
                                    icon.classList.add('fa-eye');
                                } else {
                                    passwordField.type = "password";
                                    icon.classList.remove('fa-eye');
                                    icon.classList.add('fa-eye-slash');
                                }
                            }
                        </script>
                        <div class="text-center">
                            <a class="a1" href="/forgot-password">Forgot Password ?</a>
                        </div>
                    </div>
                    <div class="text-center my-3">
                        <button class="main-button w-25" type="submit">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
