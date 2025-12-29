@extends('layouts.home-layout')

@section('content')
<section class="d1-bg landing-body">

    <div class="container py-4 py-lg-5">
        <div class="form-card p-200">
            <div class="header-card text-center">
                <h3 class="p-b-24">Profile Setup</h3>
            </div>
            <div class="">
                <!-- Navigation pills -->
                <ul class="nav nav-pills my-3 d-flex justify-content-between" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button aria-controls="pills-home" aria-selected="false" class="nav-link dotpag p-0 active" data-bs-target="#pills-home" data-bs-toggle="pill" id="pills-home-tab" role="tab"
                            type="button">1</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button aria-controls="pills-2" aria-selected="false" class="nav-link p-0 active d-flex align-items-center" data-bs-target="#pills-2" data-bs-toggle="pill" id="pills-2-tab" role="tab"
                            type="button"><span class="pro-bar">&nbsp;</span><span class="dotpag">2</span></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button aria-controls="pills-3" aria-selected="true" class="nav-link p-0 active d-flex align-items-center" data-bs-target="#pills-3" data-bs-toggle="pill" id="pills-3-tab" role="tab"
                            type="button"><span class="pro-bar">&nbsp;</span><span class="dotpag">3</span></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button aria-controls="pills-4" aria-selected="false" class="nav-link p-0 d-flex align-items-center" data-bs-target="#pills-4" data-bs-toggle="pill" id="pills-4-tab" role="tab" type="button"><span
                                class="pro-bar">&nbsp;</span><span class="dotpag">4</span></button>
                    </li>
                </ul>

                <form action="{{ route('profile-setup3') }}" class="p-t-40" enctype="multipart/form-data" method="POST">
                    <div class="tab-content setup-form-card" id="pills-tabContent">
                        @csrf
                        @method('put')
                        <!-- Register Step 3 -->
                        <h3 class="headingH3 p-b-32">Your Pictures</h3>
                        <p class="f-16 d5 p-b-24">Add some more pictures of yourself to build out your profile</p>

                        @if ($errors->has('images'))
                            <p class="text-danger f-16 mb-2">Please add at least three image. </p>
                        @endif

                        <div class="upload__box d-flex">
                            <div class="upload__img-wrap">
                                <div class="upload__btn-box">
                                    <label class="upload__btn">
                                        <div class="text-center">
                                            <i class="fas fa-plus fa-2x"></i>
                                        </div>
                                        <input accept="image/*" class="upload__file" data-max_length="4" data-index="0" name="images[]" type="file">
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="hiddenInputs"></div>

                        <div class="text-center">
                            <button class="main-button w-50" type="submit">Next</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection

@push('js')
    <script>
        var indexVal = 0;
        // $(document).ready(function() {
        $('.upload__btn').on('change', '.upload__file', function (event) {
            const selectedFiles = Array.from(event.target.files);
            imgWrap = $(this).closest('.upload__box').find('.upload__img-wrap');

            var files = event.target.files;
            var filesArr = Array.prototype.slice.call(files);

            $(event.target).hide();

            const newFileInput = $('<input>')
                .attr('type', 'file')
                .attr('name', 'images[]')
                .attr('accept', 'image/*')
                .attr('data-index', ++indexVal)
                .addClass('upload__file');

            $('.upload__btn').prepend(newFileInput);

            filesArr.forEach(function (f) {

                if (!f.type.match('image.*')) {
                    return;
                }

                var reader = new FileReader();
                reader.onload = function (e) {
                    index = indexVal-1
                    var html = "<div class='upload__img-box'><div style='background-image: url(" + e.target.result +
                        ")' data-number='" + $(".upload__img-close").length + "' class='img-bg'><div class='upload__img-close' data-index='" + index +"'></div></div></div>";
                    imgWrap.append(html);
                    // iterator++;
                }
                reader.readAsDataURL(f);
            });
        });

        $(document).on('click', '.upload__img-close', function () {
            var fieldId = $(this).attr('data-index');

            var fileInput = $('.upload__btn').find('input[data-index="' + fieldId + '"]');

            // Remove image preview
            $(this).closest('.upload__img-box').remove();

            // If there are no more files in the input, remove the input field
            fileInput.remove();
        });

    </script>
@endpush