@extends('layouts.admin')

@section('title', isset($item->id) ? 'Edit Permission' : 'Add Permission')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{ isset($item->id) ? 'Edit Permission' : 'Add Permission' }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ isset($item->id) ? route('manage.abilities.update', $item->id) : route('manage.abilities.store') }}" method="POST">
                    @csrf
                    @if(isset($item->id))
                        @method('PUT')
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="display_name" class="form-control" value="{{ old('display_name', $item->display_name ?? $item->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Abilities (comma separated)</label>
                        {{-- Ability controller logic for 'abilities' input --}}
                        <textarea name="abilities" class="form-control">{{ old('abilities') }}</textarea>
                         <small class="text-muted">Enter ability names separated by comma.</small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('manage.abilities.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">{{ isset($item->id) ? 'Update' : 'Create' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
