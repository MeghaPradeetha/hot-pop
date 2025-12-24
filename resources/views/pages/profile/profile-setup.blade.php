@extends('layouts.home-layout')

@section('content')
    <section class="d1-bg landing-body"> <!-- Landing page body -->
        <section class="">
            <div class="container py-4 py-lg-5">
                <div class="form-card p-200">
                    <div class="header-card text-center">
                        <h3 class="p-b-24">Profile Setup</h3>
                    </div>
                    <div class="">
                        <!-- Navigation pills -->
                        <ul class="nav nav-pills my-3 d-flex justify-content-between" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button aria-controls="pills-home" aria-selected="true" class="nav-link dotpag p-0 active" data-bs-target="#pills-home" data-bs-toggle="pill"
                                    id="pills-home-tab" role="tab" type="button">1</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button aria-controls="pills-2" aria-selected="false" class="nav-link p-0 d-flex align-items-center" data-bs-target="#pills-2" data-bs-toggle="pill"
                                    id="pills-2-tab" role="tab" type="button"><span class="pro-bar">&nbsp;</span><span class="dotpag">2</span></button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button aria-controls="pills-3" aria-selected="false" class="nav-link p-0 d-flex align-items-center" data-bs-target="#pills-3" data-bs-toggle="pill"
                                    id="pills-3-tab" role="tab" type="button"><span class="pro-bar">&nbsp;</span><span class="dotpag">3</span></button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button aria-controls="pills-4" aria-selected="false" class="nav-link p-0 d-flex align-items-center" data-bs-target="#pills-4" data-bs-toggle="pill"
                                    id="pills-4-tab" role="tab" type="button"><span class="pro-bar">&nbsp;</span><span class="dotpag">4</span></button>
                            </li>
                        </ul>

                        <form action="{{ route('profile-setup') }}" class="p-t-40" method="POST">
                            @method('put')
                            <div class="tab-content setup-form-card" id="pills-tabContent">
                                <!-- Register Step 1 -->
                                @csrf

                                <input name="timezone" type="hidden" />
                                <input id="latitude" name="latitude" type="hidden" />
                                <input id="longitude" name="longitude" type="hidden" />

                                <div aria-labelledby="pills-home-tab" class="tab-pane fade show active step1 " id="pills-home" role="tabpanel" tabindex="0">
                                    <h3 class="headingH3 p-b-32">About You</h3>
                                    <div class="p-b-24">
                                        <p class="form-label">First Name</p>
                                        <input aria-describedby="emailHelp" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }} form-text" name="name"
                                            placeholder="Type your first name here" required type="text" value="{{ old('name', $user->name ?? null) }}">
                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="p-b-24">
                                        <p class="form-label">Last Name</p>
                                        <input aria-describedby="emailHelp" class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }} form-text" name="last_name"
                                            placeholder="Type your last name here" required type="text" value="{{ old('last_name', $user->last_name ?? null) }}">
                                        @if ($errors->has('last_name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('last_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="p-b-24">
                                        <p class="form-label">Age</p>
                                        <select class="form-control form-select {{ $errors->has('age') ? ' is-invalid' : '' }} " id="age" name="age" required>
                                            <option value="">Select your Age</option>
                                            @for ($i = 18; $i <= 96; $i++)
                                                <option {{ old('age', $user->age ?? null) == $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                        {{-- <input aria-describedby="emailHelp" class="form-control {{ $errors->has('age') ? 'is-invalid' : '' }} form-text" max="100" min="0"
                                            name="age" placeholder="Type your age here" required type="number" value="{{ old('age', $user->age ?? null) }}"> --}}
                                        @if ($errors->has('age'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('age') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="p-b-24">
                                        <p class="form-label">Gender</p>
                                        <select class="form-select" name="gender" required>
                                            <option value="">Select</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Non-binary">Non-Binary</option>
                                            <option value="Custom">Custom</option>
                                        </select>
                                        @if ($errors->has('gender'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('gender') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="p-b-24">
                                        <p class="form-label">Want a email notification for match</p>
                                        <select class="form-select" name="want_email_notify" required>
                                            <option value="">Select your preference</option>
                                            <option value="yes"> YES</option>
                                            <option value="no"> NO</option>
                                        </select>
                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="p-b-24">
                                        <p class="form-label">Location</p>
                                        <input aria-describedby="emailHelp" class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }} form-text" id="location"
                                            name="location" placeholder="" required type="text" value="{{ old('location', $user->location ?? null) }}">
                                        @if ($errors->has('location'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('location') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <script>
                                        function initialize() {
                                            var input = document.getElementById('location');
                                            var autocomplete = new google.maps.places.Autocomplete(input);

                                            autocomplete.addListener('place_changed', function() {
                                                var place = autocomplete.getPlace();
                                                $('#latitude').val(place.geometry['location'].lat());
                                                $('#longitude').val(place.geometry['location'].lng());
                                            });
                                        }
                                    </script>

                                    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&callback=initialize&libraries=places"></script>

                                    <div class="text-center">
                                        <button class="main-button w-50" id="Next1" type="submit">Next</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var timezoneInputs = document.getElementsByName('timezone');
        var userTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;

        if (timezoneInputs.length > 0) {
            timezoneInputs[0].value = userTimeZone;
        }

    })

    function save() {

        var data1 = $('.step1 input').serialize();

        axios.post('/user', {
                Name: 'Fred',
                lastName: 'Flintstone'
            })
            .then(function(response) {
                // console.log(response);
                alert(response)
            })
            .catch(function(error) {
                console.log(error);
            });
    };
</script>
