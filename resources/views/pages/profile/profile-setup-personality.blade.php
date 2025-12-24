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
                                <button aria-controls="pills-home" aria-selected="false" class="nav-link dotpag p-0 active" data-bs-target="#pills-home" data-bs-toggle="pill"
                                    id="pills-home-tab" role="tab" type="button">1</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button aria-controls="pills-2" aria-selected="false" class="nav-link p-0 active d-flex align-items-center" data-bs-target="#pills-2"
                                    data-bs-toggle="pill" id="pills-2-tab" role="tab" type="button"><span class="pro-bar">&nbsp;</span><span class="dotpag">2</span></button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button aria-controls="pills-3" aria-selected="false" class="nav-link p-0  active d-flex align-items-center" data-bs-target="#pills-3"
                                    data-bs-toggle="pill" id="pills-3-tab" role="tab" type="button"><span class="pro-bar">&nbsp;</span><span class="dotpag">3</span></button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button aria-controls="pills-4" aria-selected="true" class="nav-link p-0 active d-flex align-items-center" data-bs-target="#pills-4"
                                    data-bs-toggle="pill" id="pills-4-tab" role="tab" type="button"><span class="pro-bar">&nbsp;</span><span class="dotpag">4</span></button>
                            </li>
                        </ul>

                        <form action="{{ route('profile-setup4') }}" class="p-t-40" method="POST">
                            <div class="tab-content setup-form-card" id="pills-tabContent">
                                @csrf

                                <!-- Register Step 4 -->
                                <h3 class="headingH3 p-b-32">Your Personality</h3>

                                <div class="p-b-24">
                                    <div>
                                        <p class="form-label">Pick your interests to add your profie</p>
                                        @foreach ($interestList as $item)
                                            <label><input class="icon-check" name="interests[]" type="checkbox" value="{{ $item }}"><span
                                                    class="icon-check-label">{{ $item }}</span></label>
                                        @endforeach
                                    </div>
                                    @if ($errors->has('interests'))
                                        <span class="text-danger form-label" role="alert">
                                            <strong>{{ $errors->first('interests') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="p-b-24">
                                    <p class="form-label">Occupation</p>
                                    <input aria-describedby="emailHelp" class="form-control {{ $errors->has('occupation') ? 'is-invalid' : '' }} form-text" id="occupation"
                                        name="occupation" placeholder="Type your occupationl here" required type="text" value="{{ old('occupation', $user->occupation ?? null) }}">
                                    @if ($errors->has('occupation'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('occupation') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="p-b-24">
                                    <p class="form-label">Height</p>
                                    <select class="form-control form-select {{ $errors->has('height') ? ' is-invalid' : '' }} " id="height" name="height" required>
                                        <option value="">Select your Height</option>
                                        @for ($i = 100; $i <= 250; $i++)
                                            <option {{ old('height', $user->height ?? null) == $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }} cm</option>
                                        @endfor
                                    </select>
                                    {{-- <input aria-describedby="heightHelp" class="form-control {{ $errors->has('height') ? ' is-invalid' : '' }} form-text" id="height" name="height"
                                        placeholder="Type your height here" required type="text" value="{{ old('height', $user->height ?? null) }}"> --}}
                                    @if ($errors->has('height'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('height') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="p-b-24">
                                    <p class="form-label">Nationality</p>
                                    <select class="form-select" name="nationality" required>
                                        <option value="">Select your nationality</option>
                                        @foreach ($nationalityList as $nationality)
                                            <option>{{ $nationality }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('nationality'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('nationality') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="p-b-24">
                                    <p class="form-label">Relationship type</p>
                                    <select class="form-select" name="relationship_type" required>
                                        <option value="">Select prefered relationshp type</option>
                                        <option value="Relationship">Relationship</option>
                                        <option value="Keeping it casual">Keeping it casual</option>
                                        <option value="Friendship">Friendship</option>
                                        <option value="Figuring out my relationship goals">Figuring out my relationship goals</option>
                                    </select>
                                    @if ($errors->has('relationship_type'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('relationship_type') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="p-b-24">
                                    <p class="form-label">Dating Intentions</p>
                                    <select class="form-select" name="dating_intentions" required>
                                        <option value="">Select your dating intentions</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Non-binary">Non - Binary</option>
                                        <option value="All">All</option>
                                    </select>
                                    @if ($errors->has('dating_intentions'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('dating_intentions') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                @include('pages.partials.age-range')

                                <div class="p-b-24">
                                    <p class="form-label">Would you like to have more children in the future ?</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center w-50">
                                            <input class="me-3 form-check" id="yes" name="children" type="radio" value="yes">
                                            <label class="f-16 d5" for="yes">Yes</label>
                                        </div>
                                        <div class="d-flex align-items-center w-50">
                                            <input class="me-3 form-check" id="no" name="children" type="radio" value="no">
                                            <label class="f-16 d5" for="no">No</label>
                                            <input name="user_id" type="hidden" value="{{ Auth()->user()->id }}">
                                        </div>
                                    </div>
                                    @if ($errors->has('children'))
                                        <span class="text-danger form-label" role="alert">
                                            <strong>This field is requireed.</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="p-b-24">
                                    <p class="form-label">Hometown</p>
                                    <input aria-describedby="emailHelp" class="form-control {{ $errors->has('hometown') ? 'is-invalid' : '' }} form-text" id="hometown"
                                        name="hometown" placeholder="Type your hometown here" required type="text" value="{{ old('hometown', $user->hometown ?? null) }}">
                                    @if ($errors->has('hometown'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('hometown') }}</strong>
                                        </span>
                                    @endif

                                    <script>
                                        function initialize() {
                                            var input = document.getElementById('hometown');
                                            var autocomplete = new google.maps.places.Autocomplete(input);

                                            autocomplete.addListener('place_changed', function() {
                                                var place = autocomplete.getPlace();
                                            });
                                        }
                                    </script>

                                    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&callback=initialize&libraries=places"></script>
                                </div>

                                <div class="text-center">
                                    <a href="/sign-up-tutorial?id=1">
                                        <input class="main-button w-50" type="submit" value="Finish"></a>
                                </div>
                                <!-- Register Step 4 end -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </section> <!-- Landing page body end -->
@endsection
