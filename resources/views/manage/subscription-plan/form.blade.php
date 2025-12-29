@extends('layouts.admin')

@section('title', isset($entity->id) ? 'Edit Plan' : 'Add New Plan')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{ isset($entity->id) ? 'Edit Plan' : 'Add New Plan' }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ isset($entity->id) ? route('manage.subs-plans.update', $entity->id) : route('manage.subs-plans.store') }}" method="POST">
                    @csrf
                    @if(isset($entity->id))
                        @method('PUT')
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $entity->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Plan ID (Stripe/Payment ID)</label>
                        <input type="text" name="plan_id" class="form-control" value="{{ old('plan_id', $entity->plan_id) }}" required>
                    </div>

                     <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $entity->price) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $entity->description) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('manage.subs-plans.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">{{ isset($entity->id) ? 'Update Plan' : 'Create Plan' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
