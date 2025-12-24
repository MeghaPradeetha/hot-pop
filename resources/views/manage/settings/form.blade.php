@extends('oxygen::layouts.master-dashboard')

@section('content')
    {{ lotus()->pageHeadline(ucfirst(strtolower(str_replace('_', ' ', $entity->setting_key)))) }}

    {{ lotus()->breadcrumbs([['Home', route('dashboard')], ['Settings', route('manage.settings.index')], [$entity->setting_key, null, true]]) }}

    <form action="{{ entity_resource_path() }}" method="post" class="form-horizontal" enctype="multipart/form-data">
        {{ csrf_field() }}

        @if ($entity->id)
            {{ method_field('put') }}
            <input type="hidden" name="id" value="{{ $entity->id }}" />
        @endif

        {{-- <textarea id="description" name="setting_value">
            {{ $entity->setting_value }}
        </textarea> --}}

        <div class="col-md-12 mb-5">
            <textarea name="{{ strtolower($entity->setting_key) }}" id="description" style="width: 100%; height: 100vh">{{ $entity->setting_value }}</textarea>
        </div>
        @if ($entity->setting_key == 'ABOUT_US')
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2">
                        <p style="font-size: 28px">
                            <i class="fab fa-facebook-square"></i>
                            Facebook
                        </p>
                    </div>
                    <div class="col-md-10">
                        <input type="text" value="{{ setting('FACEBOOK_URL') }}" name="facebook_url"
                            class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2">
                        <p style="font-size: 28px">
                            <i class="fab fa-instagram-square"></i>
                            Instagram
                        </p>
                    </div>
                    <div class="col-md-10">
                        <input type="text" value="{{ setting('INSTAGRAM_URL') }}" name="instagram_url"
                            class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2">
                        <p style="font-size: 28px">
                            <i class="fas fa-envelope-square"></i>
                            Email
                        </p>
                    </div>
                    <div class="col-md-10">
                        <input type="text" value="{{ setting('SUPPORT_EMAIL') }}" name="support_email"
                            class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2">
                        <p style="font-size: 28px">
                            <i class="fas fa-phone"></i>
                            Mobile
                        </p>
                    </div>
                    <div class="col-md-10">
                        <input type="text" value="{{ setting('SUPPORT_PHONE') }}" name="support_phone"
                            class="form-control">
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <p style="font-size: 28px">
                            <i class="fas fa-vr-cardboard"></i>
                            Playstore app link
                        </p>
                    </div>
                    <div class="col-md-8">
                        <input type="text" value="{{ setting('GOOGLE_STORE_LINK') }}" name="google_store_link"
                            class="form-control">
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <p style="font-size: 28px">
                            <i class="fas fa-apple-alt"></i>
                            Apple store app link
                        </p>
                    </div>
                    <div class="col-md-8">
                        <input type="text" value="{{ setting('APPLE_STORE_LINK') }}" name="apple_store_link"
                            class="form-control">
                    </div>
                </div>
            </div> --}}
        @endif

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success col-3">Save</button>
        </div>
    </form>
    {{-- <script src="https://cdn.tiny.cloud/1/your-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#description',
            // Additional configuration options
        });
    </script> --}}
    <!-- include summernote css/js -->


    <script src="https://cdn.ckeditor.com/ckeditor5/38.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#description'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
