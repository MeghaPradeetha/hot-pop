@extends('layouts.header-dashboard')

@section('content')
    <section class="d1-bg landing-body">

        @include('oxygen::partials.flash')

        <div class="container py-4 py-lg-5">
            <div class="form-card p-4">
                <form action="{{ route('update-account') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="header-bar position-relative mb-4">
                        <h3 class="headingH2">Your Account</h3>
                    </div>
                    <div class="text-center form-card p-200 shadow-none">
                        <label class="drop-container text-center mb-4" for="imagesb1">
                            <div class="imagePreview"><img alt="Profile Picture" id="uploadimageb1" src="{{ $user->avatar }}" /></div>
                            <br />
                            <span class="drop-title" id="fileNameDisplayb1"><i class="fa-solid fa-camera"></i></span>
                            <input accept="image/*" id="imagesb1" name="avatar" type="file">
                        </label>
                        <span class="d-flex profile-bar justify-content-between">
                            <p class="my-auto">My email</p>
                            <input class="d2 form-control form-text w-50" name="email" value="{{ $user->email }}" />
                        </span>
                    </div>

                    <div class="text-center my-3">
                        <button class="main-button w-25" type="submit">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
