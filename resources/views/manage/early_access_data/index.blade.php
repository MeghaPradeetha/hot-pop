@extends('oxygen::layouts.master-dashboard')

@section('breadcrumbs')
    {{ lotus()->breadcrumbs([
        ['Dashboard', route('dashboard')],
        // ['Change The Resource Name', route('<change here>')],
        [$pageTitle, null, true]
    ]) }}
@stop

@section('pageMainActions')
    {{-- @include('oxygen::dashboard.partials.searchField') --}}

	@if ($canCreateEntities ?? false)
		<a href="{{ entity_resource_path() . '/create' }}" class="btn btn-success"><em class="fas fa-plus-circle"></em> Add New</a>
	@endif
@stop

{{-- DELETE THIS IF NOT USED
@section('pageSummary')
    <div>Content to be inserted at the bottom of the page</div>
@stop
 --}}

@section('content')
    @include('oxygen::dashboard.partials.table-allItems', [
        'tableHeader' => [
            'User ID', 'Name', 'Email', 'Contact Number'
        ]
    ])

    @foreach ($allItems as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->email }}</td>
            <td>{{ $item->contact_number }}</td>
            
            
        </tr>
    @endforeach
@stop
