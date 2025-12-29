@extends('layouts.admin')

@section('title', 'Manage Users')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="col-6">
            <h1>{{ $pageTitle }}</h1>
        </div>
        <div class="col-6 text-end">
             <form action="" method="GET" class="d-inline-block">
                <input type="text" name="q" class="form-control d-inline-block w-auto" placeholder="Search..." value="{{ request('q') }}">
                <button type="submit" class="btn btn-primary">Search</button>
             </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('manage.users.edit', $user->id) }}" class="btn btn-sm btn-info text-white">Edit</a>
                            <form action="{{ route('manage.users.destroy', $user->id) }}" method="POST" class="d-inline-block delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('.delete-form');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
