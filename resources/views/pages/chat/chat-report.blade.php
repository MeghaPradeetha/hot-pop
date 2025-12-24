@extends('layouts.header-dashboard')

@section('content')
    <section class="d1-bg landing-body h-100">
        <div class="container py-4 py-lg-5">
            <div class="form-card p-4">
                <div class="header-bar position-relative mb-4">
                    <h3 class="headingH2">Report</h3>
                    <a href="{{ route('chat.profile', $id) }}"><i class="fa-solid fa-arrow-left circle-back"></i></a>
                </div>

                <div class="form-card p-200 shadow-none">
                    <form action="{{ route('report.user') }}" method="POST">
                        @csrf
                        <div class="p-b-24">
                            <p class="form-label">Reason</p>
                            <select class="form-select" name="reason">
                                <option>Select your reason from here</option>
                                <option value="Self Harm">Self Harm</option>
                                <option value="Violence">Violence</option>
                                <option value="Sexual Content">Sexual Content</option>
                                <option value="Abusive Behavior">Abusive Behavior</option>
                                <option value="Other Reason">Other Reason</option>
                            </select>

                        </div>
                        <div class="p-b-50">
                            <p class="form-label">Additional Comments</p>
                            <textarea class="form-text-area" name="comments" placeholder="Write your comments here"></textarea>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <input name="reported_user" type="hidden" value="{{ $id }}">
                        <div class="text-center"><button class="small-button color" type="submit">Submit</button></div>
                    </form>
                </div>

            </div>
        </div>
    </section>
@endsection
