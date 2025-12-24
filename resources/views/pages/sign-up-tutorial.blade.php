@extends('layouts.home-layout')

@section('content')
    <section class="d1-bg landing-body h-100">
        <div class="container py-4 py-lg-5">
            <div class="form-card p-200">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tu1">
                        <div class="header-card text-center">
                            <div class="position-relative mb-4">
                                <img alt="tutorial-img" class="tutorial-img" src="{{ asset('images/tutorial.jpg') }}" />
                                <h1 class="headingH1 d1 imageover-text">How to join with us</h1>
                            </div>
                            <h3 class="p-b-24">Lorem ipsum dolor</h3>
                            <p class="f-16 p-b-50">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                                enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tu2">...</div>
                    <div class="tab-pane fade" id="tu3">...</div>
                    <div class="text-center">
                        <a href="{{ route('logged-in') }}"><button class="main-button w-50 mb-3" id="">Next</button></a><br />
                        <a class="f-21 a1" href="{{ route('logged-in') }}">Skip</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
