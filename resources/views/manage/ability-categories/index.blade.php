@extends('layouts.admin')

@section('title', 'Permission Categories')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Permission Categories</h6>
                <a href="{{ route('manage.ability-categories.create') }}" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> Add New</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($allItems as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td class="text-end">
                                    <a href="{{ route('manage.ability-categories.edit', $item->id) }}" class="btn btn-sm btn-warning text-white"><i class="fas fa-edit"></i> Edit</a>
                                    
                                     <form action="{{ route('manage.ability-categories.destroy', $item->id) }}" method="POST" class="d-inline-block delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger btn-delete"><i class="fas fa-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="2" class="text-center py-3">No categories found.</td></tr>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // SweetAlert delete logic
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
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    });
</script>
@endpush
