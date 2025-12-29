@extends('layouts.app')

@section('content')
    <section class="d1-bg landing-body"> <!-- Landing page body -->
        <section class="">
            <div class="container py-4 py-lg-5">
                {{-- @include('oxygen::partials.flash') --}}

                <div class="form-card p-200">
                    <div class="header-card text-center">
                        <a class="" href="/"><img alt="logo" class="p-b-50" src="{{ asset('images/horizontalLogoTransparentBG.png') }}" width="146px" /></a>
                        <h2 class="headingH2 d5 p-b-50">Login Verification</h2>
                        <p class="d5 f-16 p-b-16">Please enter your verification code</p>
                        <p class="d5 f-16">A verification code has sent to your dedicated email.</p>
                    </div>
                    <div class="">
                        <form action="{{ route('verifyOTP') }}" class="p-t-40" method="POST">
                            @csrf
                            <div class="p-b-32 d-flex justify-content-center">
                                <input class="form-number" id="digit_one" maxlength="1" name="code[]" onkeyup="myFunction()" style="text-align: center" type="number" />
                                <input class="form-number" id="digit_two" maxlength="1" name="code[]" onkeyup="myFunction()" style="text-align: center" type="number" />
                                <input class="form-number" id="digit_three" maxlength="1" name="code[]" onkeyup="myFunction()" style="text-align: center" type="number" />
                                <input class="form-number" id="digit_four" maxlength="1" name="code[]" onkeyup="myFunction()" style="text-align: center" type="number" />
                            </div>

                            <div class="text-center">
                                <button class="main-button w-50" type="submit">Verify</button>
                            </div>
                        </form>

                        <div class="text-center p-t-50">
                            <p class="f-16 d5 p-b-16">Didn’t receive the verification code ? <a class="a1" href="{{ route('resend') }}">Resend the code</a></p>

                            {{-- <p class="f-16 d5 p-b-16">Want to receive the code on mobile number? <a href="" class="a1">Send Code to the Mobile Number</a></p> --}}
                        </div>
                        <form action="{{ route('logout') }}" id="logout-form" method="POST">
                            @csrf
                            <div class="text-center p-t-32"><button class="a1" style="background-color: transparent;border:none" type="submit">Go back to Login</button></div>
                        </form>
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
@push('js')
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script>
        function myFunction() {
            var first = document.getElementById("digit_one").value;
            var two = document.getElementById("digit_two").value;
            var three = document.getElementById("digit_three").value;
            var four = document.getElementById("digit_four").value;

            if (first) {
                document.getElementById("digit_two").focus();
            }
            if (two) {
                document.getElementById("digit_three").focus();
            }
            if (three) {
                document.getElementById("digit_four").focus();
            }
        }
    </script>
@endpush
