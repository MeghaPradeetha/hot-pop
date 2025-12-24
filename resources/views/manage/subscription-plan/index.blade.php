@extends('oxygen::layouts.master-dashboard')

@section('breadcrumbs')
    {{ lotus()->breadcrumbs([
        ['Dashboard', route('dashboard')],
        // ['Change The Resource Name', route('<change here>')],
        [$pageTitle, null, true],
    ]) }}
@stop

@section('pageMainActions')
    @include('oxygen::dashboard.partials.searchField')

    @if ($canCreateEntities ?? false)
        <a class="btn btn-success" href="{{ entity_resource_path() . '/create' }}"><em class="fas fa-plus-circle"></em> Add New</a>
    @endif
@stop

@section('content')
    @include('oxygen::dashboard.partials.table-allItems', [
        'tableHeader' => ['ID', 'Name', 'Plan', 'Price', 'Actions|text-end'],
    ])

    @foreach ($allItems as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td> {{ $item->name }} </td>
            <td> {{ $item->plan_id }} </td>
            <td> {{ $item->price }} </td>
            <td class="text-end">
                <div class="btn-spaced">
                    @if ($canEditEntities ?? false)
                        <a class="btn btn-warning js-tooltip" href="{{ entity_resource_path() . '/' . $item->id . '/edit' }}" id="btn_edit_{{ $item->id }}" title="Edit"><em
                                class="fa fa-edit"></em> Edit</a>
                    @endif

                    @if (isset($isDestroyingEntityAllowed) && $isDestroyingEntityAllowed === true)
                        <form action="{{ entity_resource_path() . '/' . $item->id }}" class="form form-inline js-confirm-delete" method="POST">
                            {{ method_field('delete') }}
                            {{ csrf_field() }}
                            <button class="btn btn-danger js-tooltip" id="btn_delete_{{ $item->id }}" title="Delete"><em class="fa fa-times"></em> Delete</button>
                        </form>
                    @endif

                </div>
            </td>
        </tr>
    @endforeach
@stop
