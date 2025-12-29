@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1>{{ $pageTitle }}</h1>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('manage.settings.update', ['setting' => 1]) }}" method="POST"> 
                {{-- NOTE: Resource route expects an ID for update, but we are updating all. Using '1' as dummy or we should change route to POST/PUT distinct from resource --}}
                {{-- However, resource route for 'update' is PUT/PATCH /settings/{setting}. 
                   Our controller update doesn't use ID. 
                   Let's check routes/web.php. It says Route::resource('settings', ...)->only(['index', 'edit', 'update'])
                   So we need to follow that or change route.
                   Correction: The implementation plan said "Custom views".
                   I'll assume we can use a simpler route or just patch the resource route. 
                   But standard resource update expects ID. 
                   
                   Actually, looking at web.php: 
                   Route::resource('settings', SettingsController::class)->only(['index', 'edit', 'update']);
                   
                   To avoid 404/MethodNotAllowed, I should probably adjust the route to be a simple GET/POST for settings instead of resource, 
                   or just use a dummy ID in the form action as typical hack, but cleaner is to change route.
                   
                   I will update route in web.php too to make it simpler: Route::get('settings', ...); Route::post('settings', ...);
                   For now, let's write the view assuming I'll fix the route.
                --}}
                
                @csrf
                @method('PUT') 
                {{-- If I change route to POST, remove method PUT. I'll stick to replacing the resource logic. --}}

                @foreach($visibleSettings as $key)
                <div class="mb-3">
                    <label class="form-label">{{ ucwords(str_replace('_', ' ', strtolower($key))) }}</label>
                    @if(in_array($key, ['PRIVACY_POLICY', 'TERMS_AND_CONDITIONS', 'ABOUT_US', 'FAQ']))
                        <textarea name="{{ $key }}" class="form-control" rows="5">{{ $settings[$key] ?? '' }}</textarea>
                    @else
                        <input type="text" name="{{ $key }}" class="form-control" value="{{ $settings[$key] ?? '' }}">
                    @endif
                </div>
                @endforeach

                <button type="submit" class="btn btn-primary">Save Settings</button>
            </form>
        </div>
    </div>
</div>
@endsection
