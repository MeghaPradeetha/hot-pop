@extends('layouts.admin')

@section('title', isset($entity->id) ? 'Edit User' : 'Add New User')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold text-primary">{{ isset($entity->id) ? 'Edit User' : 'Add New User' }}</h6>
    </div>
    <div class="card-body">

<form action="{{ entity_resource_path() }}" method="post" class="form-horizontal" enctype="multipart/form-data">
    {{ csrf_field() }}

    @if ($entity->id)
    {{ method_field('put') }}
    <input type="hidden" name="id" value="{{ $entity->id }}" />
    @endif


    <div class="form-group row">
        <div class="col-sm-4">

            <div class="image-upload">

                <img id="avatar_preview" name="avatar_image" src="{{ $user->avatar_url }}" alt="Avatar Image" style="max-width: 200px; max-height: 200px;">
                <input type="file" name="avatar_image" id="avatar_image" accept="image/*">
            </div>
        </div>
    </div>

    <div class="form-group row mx-auto">
        <div class="col-md-3 text-left text-md-right">
            <label for="name">Name / Username</label>
        </div>
        <div class="col-md-7">
            <input type="text" value="{{$entity->name}}" name="name" class="form-control">
        </div>
    </div>

    <div class="form-group row mx-auto">
        <div class="col-md-3 text-left text-md-right">
            <label for="age">Age</label>
        </div>
        <div class="col-md-7">
            <input type="text" value="{{$entity->age}}" name="age" class="form-control">
        </div>
    </div>

    <div class="form-group row mx-auto">
        <div class="col-md-3 text-left text-md-right">
            <label for="email">Email</label>
        </div>
        <div class="col-md-7">
            <input type="text" value="{{$entity->email}}" name="email" class="form-control">
        </div>
    </div>

    <div class="form-group row mx-auto">
        <div class="col-md-3 text-left text-md-right">
            <label for="location">Location</label>
        </div>
        <div class="col-md-7">
            <input type="text" value="{{$entity->location}}" name="location" class="form-control">
        </div>
    </div>

    <div class="form-group row mx-auto">
        <div class="col-md-3 text-left text-md-right">
            <label for="type">Gender</label>
        </div>
        <div class="col-md-7">
            <select name="gender" class="form-control">
            <option>Male</option>
            <option>Female</option>
            <option>Non-Binary</option>
            </select>
        </div>
    </div>

    <div class="form-group row mx-auto">
        <div class="col-md-3 text-left text-md-right">
            <label for="subscription_plan">Subscription Plan</label>
        </div>
        <div class="col-md-7">
            <select name="subscription_plan" class="form-control">
            <option>Free Trial</option>
            <option>Premium Plan</option>
            </select>
        </div>
    </div>
    <!-- {!! $form->render() !!} -->
    {!! $form->renderSubmit() !!}
</form>


@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const avatarInput = document.getElementById('avatar_image');
        const avatarPreview = document.getElementById('avatar_preview');

        avatarInput.addEventListener('change', function(event) {
            const selectedFile = event.target.files[0];

            if (selectedFile) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                };

                reader.readAsDataURL(selectedFile);
            }
        });
    });
</script>

@endpush

@push('js')
<style>
    /* styles for the image box and image preview area  */
    .image-box {
        position: relative;
        width: 150px;
        height: 150px;
        border: 2px solid #ccc;
        overflow: hidden;
    }

    .preview-image {
        display: none;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        font-size: 24px;
        color: #ccc;
    }
</style>
    </div>
</div>
@endpush
@stop