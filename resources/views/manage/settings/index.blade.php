@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">General Settings</h6>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('manage.settings.update', ['setting' => 1]) }}" method="POST"> 
                    @csrf
                    @method('PUT') 

                    @foreach($visibleSettings as $key)
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ ucwords(str_replace('_', ' ', strtolower($key))) }}</label>
                        @if(in_array($key, ['PRIVACY_POLICY', 'TERMS_AND_CONDITIONS', 'ABOUT_US', 'FAQ']))
                            <textarea name="{{ $key }}" class="form-control" rows="5">{{ $settings[$key] ?? '' }}</textarea>
                        @else
                            <input type="text" name="{{ $key }}" class="form-control" value="{{ $settings[$key] ?? '' }}">
                        @endif
                    </div>
                    @endforeach

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
