@extends('oxygen::layouts.master-dashboard')

@section('content')
    {{ lotus()->pageHeadline($pageTitle) }}

    <div class="page-main-actions">
        {{ lotus()->breadcrumbs([['Dashboard', route('dashboard')], ['Users', route('manage.users.index')], [$pageTitle, null, true]]) }}
    </div>

    <form action="{{ entity_resource_path() }}" class="form-horizontal" enctype="multipart/form-data" method="post">
        {{ csrf_field() }}

        @if ($entity->id)
            <input name="id" type="hidden" value="{{ $entity->id }}" />
        @endif

        <div class="form-group row mx-auto">
            <div class="col-md-3 text-left text-md-right">
                <label for="title">Title</label>
            </div>
            <div class="col-md-7">
                <input class="form-control" name="title" placeholder="Maximum 100 Characters" type="text">
            </div>
        </div>

        <div class="form-group row mx-auto">
            <div class="col-md-3 text-left text-md-right">
                <label for="message">Message</label>
            </div>
            <div class="col-md-7">
                <textarea class="form-control" name="message" placeholder="Maximum 500 Characters" rows="10" type="text"></textarea>
            </div>
        </div>

        <div class="form-group row mx-auto">
            <div class="col-md-3 text-left text-md-right">
                <label for="title">Sender User</label>
            </div>
            <div class="col-md-7">{{ $entity->full_name }}</div>
        </div>

        <div class="form-group row mx-auto">
            <div class="col-md-3 text-left text-md-right">
                <label for="title">Sender Email</label>
            </div>
            <div class="col-md-7">{{ $entity->email }}</div>
        </div>

        <hr>
        <div class="d-flex justify-content-center">
            <a class="btn btn-secondary" href="{{ route('manage.users.index') }}" type="button">Cancel</a>
            <button class="btn btn-success mx-2" type="submit">Save</button>
        </div>
    </form>
@stop
