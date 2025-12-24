@extends('layouts.header-dashboard')

@section('content')
    <section class="d1-bg landing-body h-100"> <!-- Landing page body -->

        <section class="">
            <div class="container py-4 py-lg-5">
                <div class="form-card p-200 card-bg">
                    <div class="header-card text-center">
                        <h3 class="p-b-50 headingH1">Welcome!</h3>
                        <p class="f-21 p-b-160">" Looking for love, friendship, or just some casual fun? Look no further than HOT POP App! Connect with like-minded individuals in
                            your area and find your perfect match. Whether you’re seeking a romantic partner, a new friend, or someone to hang out with, our app has you covered.
                            Search, match, and chat with potential dates to see if there’s a spark. Join our community of singles and start your dating journey today! "</p>
                        <a href="{{ route('home-profile') }}"><button class="main-button w-50">Start Exploring</button></a>
                    </div>
                </div>
            </div>
        </section>

    </section> <!-- Landing page body end -->
@endsection
