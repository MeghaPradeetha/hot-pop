@extends('oxygen::layouts.master-dashboard')

@section('breadcrumbs')
{{ lotus()->breadcrumbs([
		['Dashboard', route('dashboard')],
		// ['Change The Resource Name', route('<change here>')],
		[$pageTitle, null, true]
	]) }}
@stop

@section ('content')
{{ lotus()->pageHeadline($pageTitle) }}

<div class="page-main-actions">
	@yield('breadcrumbs')
</div>

<x-oxygen::data.card :title="$pageTitle" class="mt-4">
	<x-oxygen::data.row label="Report ID">{{ $entity->id }}</x-oxygen::data.row>
	<x-oxygen::data.row label="Report Account Name">{{ $entity->reportedUser->name }}</x-oxygen::data.row>
	<x-oxygen::data.row label="Report Account Email">{{ $entity->user->email }}</x-oxygen::data.row>
	<x-oxygen::data.row label="Report By">{{ $entity->user->name }}</x-oxygen::data.row>
	<x-oxygen::data.row label="Reason for Report">{{ $entity->reason }}</x-oxygen::data.row>
	<x-oxygen::data.row label="Message">{{ $entity->message }}</x-oxygen::data.row>
</x-oxygen::data.card>

<div class="text-center mb-3">
	<a href="{{ entity_resource_path() . '/block/'  }}" class="btn btn-primary mt-3 btn-sm mx-2">No action Required</a>
	<a href="{{ entity_resource_path() . '/mark-as-read/'  }}" class="btn btn-danger mt-3 btn-sm mx-2 mark-as-read">Delete Account</a>

</div>


@stop