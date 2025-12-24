@extends('layouts.header-dashboard')

@section('content')
    <section class="d1-bg landing-body">
        <div class="container py-4 py-lg-5">
            <div class="form-card p-4">
                <div class="header-bar position-relative mb-4">
                    <h3 class="headingH2">Your Account</h3>
                </div>
                <div class="text-center p-200">
                    <img alt="Profile Picture" class="account-profile mb-5" src="{{ asset('storage/'.$user->avatar) }}" />
                    <span class="d-flex flex-wrap profile-bar justify-content-between">
                        <p>My email</p>
                        <p class="d2 f-21">{{ $user->email }}</p>
                    </span>
                </div>
                <div class="text-center my-3">
                    <a href="{{ route('profile-edit', $user->id) }}"><button class="secondary-button m-b-20 w-50" type="button">Edit Profile</button></a><br />
                    <a href="/change-password"><button class="main-button w-50" type="button">Change Password</button></a><br /><br />
					<form action="{{ url('/delete-account') }}"
						method="POST" class="form form-inline js-confirm-delete">
					  {{ method_field('delete') }}
					  {{ csrf_field() }}<button type="submit" class="secondary-button m-b-20 w-50" type="button">Delete Account</button>
					</form>
                </div>
            </div>
        </div>
    </section>
@endsection
