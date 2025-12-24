@extends('layouts.header-dashboard')

@section('content')
    <section class="d1-bg landing-body h-100">
        <div class="container py-4 py-lg-5">
            <div class="form-card p-4">
                <div class="header-bar position-relative mb-4">
                    <h3 class="headingH2">Chat Setting</h3>
                </div>
                <form action="{{ route('settings.data.save') }}" method="POST">
                    @csrf
                    <div class="text-center form-card p-200 shadow-none">
                        <span class="d-flex chat-setting-bar justify-content-between align-items-center">

                            <p class="f-16">Online Status</p>
                            <div class="checkbox-wrap">

                                <input {{ $user->online_status ? 'checked' : 'unchecked' }} class="check" id="one" name="online_status" type="checkbox" />
                                <label for="one">
                                    <div id="thumb"></div>
                                </label>
                            </div>
                        </span>
                        <span class="d-flex chat-setting-bar justify-content-between align-items-center">
                            <p class="f-16">Read Receipts</p>
                            <div class="checkbox-wrap">
                                <input {{ $user->read_receipts ? 'checked' : 'unchecked' }} class="check" id="two" name="read_receipts" type="checkbox" />
                                <label for="two">
                                    <div id="thumb"></div>
                                </label>
                            </div>
                        </span>
                    </div>
                    <div class="text-center my-3">
                        <button class="main-button w-25" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
