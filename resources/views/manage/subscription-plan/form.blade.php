@extends('oxygen::layouts.master-dashboard')

@section('content')
    {{ lotus()->pageHeadline($pageTitle) }}

    <form action="{{ entity_resource_path() }}" class="form-horizontal" enctype="multipart/form-data" method="post">
        {{ csrf_field() }}

        @if ($entity->id)
            {{ method_field('put') }}
            <input name="id" type="hidden" value="{{ $entity->id }}" />
        @endif

        {!! $form->render() !!}
        {!! $form->renderSubmit() !!}
    </form>
@stop
