@extends('layouts.admin')

@section('title', 'Nationality Details')

@section('content')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Details: {{ $entity->name }}</h6>
                <a href="{{ route('manage.nationality.index') }}" class="btn btn-sm btn-secondary">Back</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Name</th>
                        <td>{{ $entity->name }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge {{ $entity->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($entity->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $entity->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $entity->updated_at->format('Y-m-d H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
