@extends('layouts.admin')

@section('title', isset($item->id) ? 'Edit Permission Category' : 'Add Permission Category')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{ isset($item->id) ? 'Edit Permission Category' : 'Add Permission Category' }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ isset($item->id) ? route('manage.ability-categories.update', $item->id) : route('manage.ability-categories.store') }}" method="POST">
                    @csrf
                    @if(isset($item->id))
                        @method('PUT')
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $item->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Default Abilities (comma separated)</label>
                        {{-- Assuming default_abilities is JSON encoded --}}
                        <input type="text" name="default_abilities" class="form-control" value="{{ old('default_abilities', is_array($item->default_abilities) ? implode(',', $item->default_abilities) : $item->default_abilities) }}">
                        <small class="text-muted">E.g., view,create,edit,delete</small>
                    </div>

                    {{-- Simple dynamic fields for permissions would go here, but omitted for brevity in minimal refactor --}}

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('manage.ability-categories.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">{{ isset($item->id) ? 'Update' : 'Create' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
