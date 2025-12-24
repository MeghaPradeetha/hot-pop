@extends('layouts.header-dashboard')

@section('content')
    <section class="d1-bg landing-body h-100">
        <div class="container py-4 py-lg-5">
            <div class="form-card p-4">
                <div class="header-bar position-relative mb-4">
                    <h3 class="headingH2">FAQ's</h3>
                </div>
                <div>
                    {!! nl2br($data) !!}
                </div>
            </div>
        </div>

        <style>
            h4,
            h3,
            h2 {
                padding-bottom: 0.5rem !important;
            }

            p {
                font-size: 16px;
                line-height: 20px;
                color: #0a0a0a8f;
                padding-bottom: 24px;
            }
        </style>
    </section>
@endsection
