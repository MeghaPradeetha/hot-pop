@extends('layouts.admin')

@section('title', 'Edit Role Permissions: ' . $role->name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">Manage Permissions for Role: {{ $role->name }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('manage.abilities.update_role_abilities', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @foreach($abilityCategories as $category)
                    <div class="mb-4">
                        <h5>{{ $category->name }}</h5>
                        <div class="row">
                            @foreach($category->abilities as $ability)
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="abilities[]" value="{{ $ability->name }}" id="ability_{{ $ability->id }}" 
                                    {{ in_array($ability->name, $currentAbilities) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ability_{{ $ability->id }}">
                                        {{ $ability->title }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('manage.users.index') }}" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Permissions</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
