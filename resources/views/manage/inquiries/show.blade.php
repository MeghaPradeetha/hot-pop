@extends('oxygen::layouts.master-dashboard')

@section('breadcrumbs')
    {{ lotus()->breadcrumbs([
        ['Dashboard', route('dashboard')],
        // ['Change The Resource Name', route('<change here>')],
        [$pageTitle, null, true],
    ]) }}
@stop

@section('content')
    {{ lotus()->pageHeadline($pageTitle) }}

    <div class="page-main-actions">
        @yield('breadcrumbs')
    </div>

    <x-oxygen::data.card :title="$pageTitle" class="mt-4">
        <x-oxygen::data.row label="Inquiry ID">{{ $entity->id }}</x-oxygen::data.row>
        <x-oxygen::data.row label="Name">{{ $entity->name }}</x-oxygen::data.row>
        <x-oxygen::data.row label="Email">{{ $entity->email }}</x-oxygen::data.row>
        <x-oxygen::data.row label="Message">{{ $entity->message }}</x-oxygen::data.row>
        <x-oxygen::data.row label="Submitted">{{ standard_datetime($entity->created_at) }}</x-oxygen::data.row>
    </x-oxygen::data.card>

    <div class="text-center mb-3">
        <a class="btn btn-primary mt-3 btn-sm mx-2" href="{{ url('manage/inquiries/'. $entity->id .'/email') }}">Reply In Email</a>
    </div>

@stop
