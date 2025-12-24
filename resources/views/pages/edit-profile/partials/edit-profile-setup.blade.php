<div class="form-card p-200 shadow-none">
    <h3 class="headingH3 p-b-32">About You</h3>
    <form action="{{ route('profile-update', $my_profile->id) }}" method="post">
        @csrf
        @method('put')
        <div class="p-b-24">
            <p class="form-label">First Name</p>
            <input aria-describedby="emailHelp" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }} form-text" name="name" placeholder="Type your first name here"
                required type="text" value="{{ $my_profile->name }}">
            @if ($errors->has('name'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
        <div class="p-b-24">
            <p class="form-label">Last Name</p>
            <input aria-describedby="emailHelp" class="form-control {{ $errors->has('last_name') ? ' is-invalid' : '' }} form-text" name="last_name"
                placeholder="Type your last name here" required type="text" value="{{ $my_profile->last_name }}">
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
                    <option {{ old('age', $my_profile->age ?? null) == $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
            {{-- <input aria-describedby="emailHelp" class="form-control {{ $errors->has('age') ? ' is-invalid' : '' }} form-text" max="100" min="0" name="age"
                placeholder="Type your age here" required type="number" value="{{ $my_profile->age }}"> --}}
            @if ($errors->has('age'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('age') }}</strong>
                </span>
            @endif
        </div>

        <div class="p-b-24">
            <p class="form-label">Gender</p>
            <select class="form-select" name="gender">
                <option>Select your preference</option>
                <option {{ $my_profile->gender == 'Male' ? 'selected' : '' }} value="Male">Male</option>
                <option {{ $my_profile->gender == 'Female' ? 'selected' : '' }} value="Female">Female</option>
                <option {{ $my_profile->gender == 'Non-binary' ? 'selected' : '' }} value="Non-binary">Non-Binary</option>
                <option {{ $my_profile->gender == 'Custom' ? 'selected' : '' }} value="Custom">Custom</option>
            </select>
            @if ($errors->has('gender'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('gender') }}</strong>
                </span>
            @endif
        </div>
        <div class="p-b-24">
            <p class="form-label">Want a email notification for match</p>
            <select class="form-select" name="want_email_notify">
                <option {{ $my_profile->want_email_notify == 'Yes' ? 'selected' : '' }} value="Yes">Yes</option>
                <option {{ $my_profile->want_email_notify == 'No' ? 'selected' : '' }} value="No">No</option>
            </select>
            @if ($errors->has('want_email_notify'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('want_email_notify') }}</strong>
                </span>
            @endif
        </div>

        <div class="p-b-24">
            <p class="form-label">Location</p>
            <input aria-describedby="emailHelp" class="form-control {{ $errors->has('location') ? ' is-invalid' : '' }} form-text" id="location" name="location" placeholder=""
                required type="text" value="{{ $my_profile->location }}">
            @if ($errors->has('location'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('location') }}</strong>
                </span>
            @endif
        </div>

        <div class="text-center">
            {{-- <a href="{{ route('profile-introduction-edit',$my_profile->id) }}" type="submit" class="main-button w-50 btnNext2">Next</a> --}}
            <button class="main-button w-50" type="submit">Save</button>
        </div>
    </form>
</div>
