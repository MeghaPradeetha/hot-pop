<div class="form-card p-200 shadow-none">
    <form action="{{ route('profile-personality-update', $my_profile->id) }}" method="POST">
        @csrf
        @method('put')
        <h3 class="headingH3 p-b-32">Your Personality</h3>
        @if ($personality)
            <div class="p-b-24">
                <p class="form-label">Pick your interests to add your profie</p>
                @foreach ($interestList as $item)
                    <label>
                        <input {{ in_array($item, $interests) ? 'checked' : '' }} class="icon-check" name="interests[]" type="checkbox" value="{{ $item }}">
                        <span class="icon-check-label">{{ $item }}</span>
                    </label>
                @endforeach
            </div>

            <div class="p-b-24">
                <p class="form-label">Occupation</p>
                <input aria-describedby="emailHelp" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }} form-text" id="exampleInputEmail1" name="occupation"
                    placeholder="Type your occupationl here" required type="text" value="{{ $personality->occupation ?? null }}">
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
                        <option {{ $personality->height == $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }} cm</option>
                    @endfor
                </select>
                {{-- <input class="form-control {{ $errors->has('height') ? ' is-invalid' : '' }} form-text" name="height" placeholder="Type your height here" required type="text"
                    value="{{ $personality->height ?? null }}"> --}}
                @if ($errors->has('height'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('height') }}</strong>
                    </span>
                @endif
            </div>
            <div class="p-b-24">
                <p class="form-label">Nationality</p>
                <select class="form-select" name="nationality">
                    <option value="">Select your nationality</option>
                    @foreach ($nationalityList as $nationality)
                        <option {{ $personality->nationality == $nationality ? 'selected' : '' }} value="{{ $nationality }}">{{ $nationality }}</option>
                    @endforeach
                </select>
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="p-b-24">
                <p class="form-label">Relationship type</p>
                <select class="form-select" name="relationship_type">
                    <option>Select prefered relationshp type</option>
                    <option value="Relationship"{{ $personality->relationship_type == 'Relationship' ? 'selected' : '' }}>Relationship</option>
                    <option {{ $personality->relationship_type == 'Keeping it casual' ? 'selected' : '' }} value="Keeping it casual">Keeping it casual</option>
                    <option {{ $personality->relationship_type == 'Friendship' ? 'selected' : '' }} value="Friendship">Friendship</option>
                    <option {{ $personality->relationship_type == 'Figuring out my relationship goals' ? 'selected' : '' }} value="Figuring out my relationship goals">Figuring out
                        my relationship goals</option>
                </select>
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="p-b-24">
                <p class="form-label">Dating Intentions</p>
                <select class="form-select" name="dating_intentions">
                    <option>Select your dating intentions</option>
                    <option {{ $personality->dating_intentions == 'Male' ? 'selected' : '' }} value="Male">Male</option>
                    <option {{ $personality->dating_intentions == 'Female' ? 'selected' : '' }} value="Female"> Female</option>
                    <option {{ $personality->dating_intentions == 'Non-binary' ? 'selected' : '' }} value="Non-binary"> Non - Binary</option>
                    <option {{ $personality->dating_intentions == 'All' ? 'selected' : '' }} value="All">All</option>
                </select>
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            {{-- <div class="p-b-24">
        <p class="form-label">Age Range</p>
        <div class="range-selector">
            <section class="range-slider container">
                <span class="output outputOne"></span>
                <span class="output outputTwo"></span>
                <span class="full-range"></span>
                <span class="incl-range"></span>
                <input name="max_age" value="{{ $personality->min_age }}" min="20" max="40" step="1" type="range">
                <input name="min_age" value="{{ $personality->max_age }}" min="20" max="40" step="1" type="range">
            </section>
        </div>
    </div> --}}
            @include('pages.partials.age-range', ['personality' => $personality])

            <div class="p-b-24">
                <p class="form-label">Would you like to have more children in the future ?</p>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center w-50">
                        <input {{ $personality->like_to_have_more_childran == 'yes' ? 'checked' : '' }} class="me-3 form-check" name="like_to_have_more_childran" type="radio"
                            value="yes" />
                        <p class="f-16 d5">Yes</p>
                    </div>
                    <div class="d-flex align-items-center w-50">
                        <input {{ $personality->like_to_have_more_childran == 'no' ? 'checked' : '' }} class="me-3 form-check" name="like_to_have_more_childran" type="radio"
                            value="no" />
                        <p class="f-16 d5">No</p>
                    </div>
                </div>
            </div>

            <div class="p-b-24">
                <p class="form-label">Hometown</p>
                <input aria-describedby="emailHelp" class="form-control {{ $errors->has('hometown') ? ' is-invalid' : '' }} form-text" id="hometown" name="hometown"
                    placeholder="Type your hometown here" required type="text" value="{{ $personality->hometown ?? null }}">
                @if ($errors->has('hometown'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('hometown') }}</strong>
                    </span>
                @endif
            </div>

            <div class="text-center">
                <button class="main-button w-50" type="submit">Save</button>
            </div>
        @endif
    </form>
</div>
