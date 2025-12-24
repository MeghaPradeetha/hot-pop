<div class="form-card shadow-none">
    <form action="{{ route('images-update', $user->id) }}" method="POST" enctype="multipart/form-data" id="form">
        <input type="hidden" id="token" name="_token" value="{{ csrf_token() }}">

        <div class="tab-content p-200" id="myTabContent">
            <h3 class="headingH3 p-b-32">Your Pictures</h3>
            <p class="f-16 d5 p-b-24">Add some more pictures of yourself to build out your profile</p>
            @if ($errors->has('images'))
                <p class="text-danger f-16 mb-2">Please add at least one image.</p>
            @endif
            <div class="upload__box d-flex">
                <div class="upload__img-wrap">
                    <div class="upload__btn-box">
                        <label class="upload__btn">
                            <img alt="upload" src="{{ asset('images/ic_profilesetup_upload.png')}}" width="" />
                            <input accept="image/*" class="upload__file" data-max_length="4" data-index="0" name="images[]" type="file">
                            @if ($errors->has('images'))
                                {{ $errors->first('images') }}
                            @endif
                        </label>
                    </div>
                </div>
            </div>

            <div id="images" class="upload__img-wrap"></div>

            <div class="text-center">
                <button type="submit" class="main-button w-50">Save</button>
            </div>
            <div class="text-center mt-2">
                <button type="button" class="secondary-button w-50" onclick="backToVideo()">Back To Video</button>
            </div>
        </div>
    </form>
</div>


@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get the images data from PHP (assuming it's in JSON format)
            var images = @json($images);

            if (images.length !== 0) {
                images.forEach((file) => {

                    const imageFileUrl = window.location.origin + '/storage/' + file.file_path;
                    const fileId = file.id;

                    // Create a container div for the image and delete icon
                    const imageContainer = document.createElement('div');
                    imageContainer.classList.add('image-container');
                    imageContainer.classList.add('upload__btn-box');

                    // Create the image element
                    const imageElement = document.createElement('img');
                    imageElement.src = imageFileUrl;


                    // Create the delete icon (Font Awesome trash icon)
                    const deleteIcon = document.createElement('div');
                    deleteIcon.classList.add('upload__img-close');

                    // Append the image and delete icon to the container
                    imageContainer.appendChild(imageElement);
                    imageContainer.appendChild(deleteIcon);

                    // Append the container to the 'images' div
                    var imagesDiv = document.getElementById('images');
                    imagesDiv.appendChild(imageContainer);

                    // Add a click event listener to the delete icon
                    deleteIcon.addEventListener('click', function () {
                        var token = $('#token').val();
                        // Call a function to delete the image from the database and remove the image from the UI
                        deleteImage(fileId, token); // Pass the image ID or any other identifier
                        imagesDiv.removeChild(imageContainer);
                    });
                });
            }
        })

        function deleteImage(fileId, token) {
            $.ajax({
                method: 'DELETE',
                url: '/profile/images/delete/' + fileId,
                data: {
                    id: fileId
                },
                headers: {
                    'X-CSRF-TOKEN': token,
                },
                success: function (response) {
                    console.log('Image deleted successfully');
                },
                error: function (error) {
                    // Handle errors, if any
                    console.error('Error deleting image:', error);
                }
            });
        }
    </script>

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
                    index = indexVal - 1
                    var html = "<div class='upload__img-box'><div style='background-image: url(" + e.target.result +
                        ")' data-number='" + $(".upload__img-close").length + "' class='img-bg'><div class='upload__img-close' data-index='" + index + "'></div></div></div>";
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