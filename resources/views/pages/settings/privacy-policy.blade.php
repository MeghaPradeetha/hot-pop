@extends('layouts.header-dashboard')

@section('content')
    <section class="d1-bg landing-body h-100">
        <div class="container py-4 py-lg-5">
            <div class="form-card p-4 styled">
                <div class="header-bar position-relative mb-4">
                    <h3 class="headingH2">Privacy Policy</h3>
                </div>

                <div class="text-center p-b-24">
                    <img alt="Logo" src="{{ asset('images/horizontalLogoTransparentBG.png') }}" width="146px" />
                </div>

                <div class="f-16">
                    {!! nl2br($data) !!}
                </div>

                <style>
                    h4,
                    h3,
                    h2 {
                        font-size: 16px;
                    }

                    .landing-body ul,
                    ol {
                        padding-bottom: 1rem !important;
                    }

                    p {
                        font-size: 16px;
                        line-height: 20px;
                        padding-bottom: 24px;
                    }
                </style>
            </div>
        </div>
    </section>
@endsection
