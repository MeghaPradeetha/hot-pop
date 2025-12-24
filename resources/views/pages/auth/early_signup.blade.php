@extends('layouts.app')

@section('content')
    <section class="d1-bg landing-body"> <!-- Landing page body -->

        <section class="">
            <div class="container py-4 py-lg-5">
                <div class="form-card p-200">
                    <div class="header-card text-center">
                        <a class="" href="/"><img alt="logo" class="p-b-50" src="{{ asset('images/horizontalLogoTransparentBG.png') }}" width="146px" /></a>

                        <p class="p-t-32 p-b-24 f-16">Please provide us with the following information to get the early access.</p>
                    </div>
                    <div class="">
                        <form action="{{ route('Early.Signup') }}" class="p-t-40" method="POST">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class='alert alert-danger'>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @csrf
                            <div class="p-b-24">
                                <p class="form-label">Name</p>
                                <input class="form-control form-text" id="name" name="name" placeholder="Type your name here" required type="text">
                            </div>
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
                            <div class="p-b-24">
                                <p class="form-label">Contact Number</p>
                                <input class="form-control form-text" id="contact_number" name="contact_number" placeholder="Type your phone number here" required type="text">
                            </div>

                            <div class="text-center">
                                <button class="main-button w-50" type="submit">Submit</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </section>

    </section> <!-- Landing page body end -->
@endsection
