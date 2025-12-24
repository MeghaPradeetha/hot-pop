@extends('oxygen::layouts.master-dashboard')

@section('breadcrumbs')
    {{ lotus()->breadcrumbs([['Home', route('dashboard')], [$pageTitle, null, true]]) }}
@stop


@section('content')
    @include('oxygen::dashboard.partials.table-allItems', [
        'tableHeader' => ['Setting ID', 'Page', 'Actions'],
    ])

    @foreach ($allItems as $item)
        <tr>
            <td>{{ sprintf('%04d', $item->id) }}</td>
            <td> {{ $item->setting_key }} </td>
            <td>
                <a href="{{ route('manage.settings.edit', $item->id) }}" class="btn btn-warning js-tooltip" title="View User"><em class="fa fa-edit"></em> Edit</a>
            </td>
        </tr>
    @endforeach
@stop
