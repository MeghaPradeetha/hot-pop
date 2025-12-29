@extends('layouts.admin')

@section('title', 'Early Access Data')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Early Access Signups</h6>
                @if ($canCreateEntities ?? false)
                    <a href="{{ route('manage.early_access_data.create') }}" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> Add New</a>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($allItems as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->contact_number }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-3">No data found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
             <div class="card-footer bg-white">
                {{ $allItems->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
