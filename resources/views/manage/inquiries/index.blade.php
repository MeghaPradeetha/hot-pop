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

    <!-- @if ($canCreateEntities ?? false)
    <a class="btn btn-success" href="{{ entity_resource_path() . '/create' }}"><em class="fas fa-plus-circle"></em> Add New</a>
    @endif -->
@stop

{{-- DELETE THIS IF NOT USED
@section('pageSummary')
    <div>Content to be inserted at the bottom of the page</div>
@stop
 --}}

@section('content')
    @include('oxygen::dashboard.partials.table-allItems', [
        'tableHeader' => ['Inquiry ID', 'Name', 'Email', 'Submitted', 'Actions|text-end'],
    ])

    @foreach ($allItems as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->email }}</td>
            <td>
                @if ($item->created_at)
                    {{ standard_datetime($item->created_at) }}
                @endif
            </td>
            <td class="text-end">
                <div class="btn-spaced">
                    @if ($canEditEntities ?? false)
                        <a class="btn btn-warning js-tooltip" href="{{ entity_resource_path() . '/' . $item->id . 'show' }}" id="btn_edit_{{ $item->id }}" title="Edit"><em
                                class="fa fa-eye"></em> View</a>
                    @endif

                    {{--
                    <form action="{{ entity_resource_path() . '/' . $item->id }}"
                          method="POST" class="form form-inline">
                        {{ method_field('put') }}
                        {{ csrf_field() }}
                        <input type="hidden" name="is_completed" value="{{ $item->is_completed }}" />
                        @if ($item->is_completed)
                            <button class="btn btn-info js-tooltip"
                                    title="Mark as Pending"><em class="fa fa-hourglass-half"></em></button>
                        @else
                            <button class="btn btn-success js-tooltip"
                                    title="Mark as Complete"><em class="fa fa-check"></em></button>
                        @endif
                    </form>
                    --}}

                    @if (isset($isDestroyingEntityAllowed) && $isDestroyingEntityAllowed === true)
                        <form action="{{ entity_resource_path() . '/' . $item->id }}" class="form form-inline js-confirm-delete" method="POST">
                            {{ method_field('delete') }}
                            {{ csrf_field() }}
                            <button class="btn btn-danger js-tooltip" id="btn_delete_{{ $item->id }}" title="Delete"><em class="fa fa-trash"></em></button>
                        </form>
                    @endif

                </div>
            </td>
        </tr>
    @endforeach
@stop
