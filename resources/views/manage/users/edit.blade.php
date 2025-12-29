@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">Edit User: {{ $user->name }}</h6>
            </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('manage.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Age</label>
                    <input type="number" name="age" class="form-control" value="{{ old('age', $user->age) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $user->location) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-control">
                        <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender', $user->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="{{ route('manage.users.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
        </div>
    </div>
</div>
@endsection