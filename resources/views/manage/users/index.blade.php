@extends('oxygen::layouts.master-dashboard')

@section('breadcrumbs')
    {{ lotus()->breadcrumbs([
        ['Dashboard', route('dashboard')],
        // ['Change The Resource Name', route('<change here>')],
        [$pageTitle, null, true],
    ]) }}
@stop

@section('pageMainActions')

    @include('manage.users.searchField')

    @if ($canCreateEntities ?? false)
        <a class="btn btn-success" href="{{ entity_resource_path() . '/create' }}"><em class="fas fa-plus-circle"></em> Add New</a>
    @endif
@stop

@section('pageSummary')
    @if ($allItems->total() < 15)
        <span class="small text-muted"> Showing 1 to {{ $allItems->total() }} of {{ $allItems->total() }} results</span>
    @endif
@stop

@section('content')
    @include('oxygen::dashboard.partials.table-allItems', [
        'tableHeader' => ['User ID', 'Name/Username', 'Email', 'Status','Profile', '', 'Actions|text-end'],
    ])

    @foreach ($allItems as $item)
        @php
            $status_name = 'Not Verify';
            $status_color = 'red';
            switch ($item->profile_setup_step) {
                case 1:
                    $status_name = 'Email Verified';
                    $status_color = 'orange';
                    break;

                case 2:
                    $status_name = 'Basic Info Completed';
                    $status_color = 'lightblue';
                    break;

                case 3:
                    $status_name = 'Intro Video Uploaded';
                    $status_color = 'blue';
                    break;

                case 4:
                    $status_name = 'Images Uploaded';
                    $status_color = 'darkblue';
                    break;

                case 5:
                    $status_name = 'Profile Completed';
                    $status_color = 'green';
                    break;
            }
        @endphp
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->full_name }}</td>
            <td>{{ $item->email }}</td>
            <td>
                <span class="badge p-2" style="background-color: {{ $status_color }}">
                    {{ $status_name }}
                </span>
            </td>
			@if ($status_name == 'Profile Completed')
				<td>
				<a class="btn btn-primary js-tooltip" href="{{  '/manage/users/' . $item->id . '/profile' }}" ><em
											class="fa fa-show"></em> View Profile</a>
			</td>
			@else
			<td></td>
			@endif

            <td>
                <div class="btn-spaced">
                    <a class="btn btn-info" href="{{ '/manage/users/' . $item->id . '/notify' }}" id="btn_notify_{{ $item->id }}">
                        <em class="fa fa-envelope"></em>
                        Send Notification</a>
                </div>
            </td>


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
                            <button class="btn btn-danger js-tooltip" id="btn_delete_{{ $item->id }}" title="Delete"><em class="fa fa-trash"></em></button>

                        </form>
                    @endif

                </div>
            </td>
        </tr>
    @endforeach
@stop
