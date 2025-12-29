@extends('layouts.admin')

@section('title', isset($entity->id) ? 'Edit Nationality' : 'Add New Nationality')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{ isset($entity->id) ? 'Edit Nationality' : 'Add New Nationality' }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ isset($entity->id) ? route('manage.nationality.update', $entity->id) : route('manage.nationality.store') }}" method="POST">
                    @csrf
                    @if(isset($entity->id))
                        @method('PUT')
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $entity->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status', $entity->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $entity->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('manage.nationality.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">{{ isset($entity->id) ? 'Update' : 'Create' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection