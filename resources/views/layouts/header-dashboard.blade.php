<?php
if (isset($_GET['id'])) {
    $auth = 1;
} else {
    $auth = 0;
}
?>
<!DOCTYPE html>
<html class="h-100" lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="ie=edge" http-equiv="X-UA-Compatible">
    <title>@yield('pageTitle', $pageTitle)</title>

    <!-- Google tag (gtag.js) : For advertise-->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-11453411855"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'AW-11453411855');
    </script>
    <!-- Favicon-->
    <link rel="icon" type="image/png" href="{{ asset('images/heart-logo.png') }}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    {{--
    <link rel="stylesheet" href="{{ asset('country_code/style.css') }}"> --}}
    <!-- Bootstrap Core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <link href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" rel="stylesheet" />

    @vite(['resources/sass/main.scss'])
    <style>
        .iti {
            width: 100%;
        }

        .image-container {
            position: relative;
            width: 200px;
            height: 200px;
        }

        .image-container img {
            width: 200px;
            height: 200px;
            border-radius: 20px;
            object-fit: cover;
        }

        .image-container i {
            position: absolute;
            top: 10px;
            right: 10px;
            height: 20px;
            width: 20px;
            border-radius: 50%;
            background: #212121;
            color: #fff;
            text-align: center;
            line-height: 20px;
        }
    </style>
</head>

<body class="d-flex fixed-bgs flex-column h-100 m-0" id="page-top">

    @include('layouts.header')

    <!-- Header Section Ends -->
    <div class="flex-grow-1" id="content-container">
        @yield('content')
    </div>

    @include('layouts.footer')

    {{-- incoming call modal --}}
    <div
        aria-hidden="true"
        aria-labelledby="staticBackdropLabel"
        class="modal fade"
        data-bs-backdrop="static"
        data-bs-caller-id=""
        data-bs-keyboard="false"
        id="staticBackdrop"
        tabindex="-1"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-center">
                    <img
                        class="rounded-circle me-2"
                        id="caller_avatar"
                        src=""
                        style="width: 80px; height: 80px;"
                    >
                </div>
                <div class="modal-body text-center">
                    <h5 class="modal-title" id="caller_name"></h5>
                    <div id="staticBackdropLabel">Incoming Call</div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button class="btn btn-success rounded-circle btn-lg mx-2" id="Answer" onclick="answerCall()">
                        <i class="fa fa-phone"></i>
                    </button>
                    <button class="btn btn-danger rounded-circle btn-lg mx-2" id="Reject" onclick="rejectCall()">
                        <i class="fa fa-phone-flip"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- agora -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        
        Pusher.logToConsole = false;
        let authID = '{{ auth()->id() }}';
        let callType = 'audio';
        let channelName = null;
        var pusher = new Pusher("{{ config('app.PusherAppKey') }}", {
            cluster: 'ap4'
        });

        // incoming call broadcast
        var channel = pusher.subscribe('my-channel5');
        channel.bind('incoming-call', function(data) {
            // check receiver and popup answer modal
            if (data.receiver_id == authID) {
                showAnswerPopup(data);
            }
        });

        // call accept and reject event broadcast
        var channel = pusher.subscribe('my-channel4');
        channel.bind('call-accept', function(data) {
            // this is for reveiver side
            if (data.notify_user_id == authID) {
                if (data.is_accept == 'true') {
                    $('#exampleModal').modal('hide');
                    redirectCallScreen(data.action_user_id);
                } else {
                    callEnd();
                    $('#exampleModal').modal('hide');
                }
            }
        });

        // this trigger when the call ringing in receiver side
        function showAnswerPopup(data) {
            document.getElementById('staticBackdropLabel').innerHTML = "Incoming " + data.type + " Call";
            document.getElementById('staticBackdrop').setAttribute("data-bs-caller-id", data.caller_id);
            document.getElementById("caller_name").innerHTML = data.caller_name;
            document.getElementById("caller_avatar").src = data.caller_avatar;
            $("#staticBackdrop").modal('show');
            callType = data.type;
            channelName = data.channelName;
        }

        // this for the answer and reject call
        function updateResponce(status, caller, callback) {
            $.ajax({
                url: '{{ route('acceptEvent') }}',
                method: 'POST',
                data: {
                    type: status,
                    caller_id: caller
                },
                success: function(response) {
                    callback();
                },
                error: function(error) {
                    console.error('Error triggering Laravel event:', error);
                }
            });
        }

        function answerCall() {
            var caller_id = document.getElementById('staticBackdrop').getAttribute("data-bs-caller-id");
            //calling event whether to know the call is accepted
            updateResponce(true, caller_id, () => redirectCallScreen(caller_id));
        }

        function redirectCallScreen(caller) {
            callEnd();
            if (callType == 'video') {
                window.location.href = "{{ route('video.index') }}?channel=" + encodeURIComponent(channelName) + "&caller=" + caller;
            } else {
                window.location.href = '{{ route('onCall') }}?channel=' + encodeURIComponent(channelName) + "&caller=" + encodeURIComponent(caller);
            }
        }

        function rejectCall() {
            var caller_id = document.getElementById('staticBackdrop').getAttribute("data-bs-caller-id");
            updateResponce(false, caller_id, callEnd);
        }

        function callEnd() {
            document.activeElement.blur();
            $("#staticBackdrop").modal('hide');
            $('.landing-body').show();
            // $('#content-container').hide();
        }
    </script>

    @stack('js')

    <script>
        /* IMage upload */

        jQuery(document).ready(function() {
            ImgUpload();
        });

        function ImgUpload() {
            var imgWrap = "";
            var imgArray = [];

            $('.upload__inputfile').each(function() {
                $(this).on('change', function(e) {
                    imgWrap = $(this).closest('.upload__box').find('.upload__img-wrap');
                    var maxLength = $(this).attr('data-max_length');

                    var files = e.target.files;
                    var filesArr = Array.prototype.slice.call(files);
                    var iterator = 0;
                    filesArr.forEach(function(f, index) {

                        if (!f.type.match('image.*')) {
                            return;
                        }

                        if (imgArray.length > maxLength) {
                            return false
                        } else {
                            var len = 0;
                            for (var i = 0; i < imgArray.length; i++) {
                                if (imgArray[i] !== undefined) {
                                    len++;
                                }
                            }
                            if (len > maxLength) {
                                return false;
                            } else {
                                imgArray.push(f);

                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    var html = "<div class='upload__img-box'><div style='background-image: url(" + e.target
                                        .result + ")' data-number='" + $(
                                            ".upload__img-close").length +
                                        "' data-file='" + f.name +
                                        "' class='img-bg'><div class='upload__img-close'></div></div></div>";
                                    imgWrap.append(html);
                                    iterator++;
                                }
                                reader.readAsDataURL(f);
                            }
                        }
                    });
                });
            });

            $('body').on('click', ".upload__img-close", function(e) {
                var file = $(this).parent().data("file");
                for (var i = 0; i < imgArray.length; i++) {
                    if (imgArray[i].name === file) {
                        imgArray.splice(i, 1);
                        break;
                    }
                }
                $(this).parent().parent().remove();
            });
        }

        //Image upload preview function
        $(document).ready(function() {
            // Use the "change" event on both #images and #images2
            $('#imagesb1').on('change', function(e) {
                var $container = $(this).closest('.drop-container');
                var $preview = $container.find('img[id^="uploadimage"]');

                if (this.files) {
                    $.each(this.files, readAndPreview);
                }

                function readAndPreview(i, file) {
                    if (!/\.(jpe?g|png|gif)$/i.test(file.name)) {
                        return alert(file.name + ' is not an image');
                    } else {
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            $preview.attr('src', e.target.result);
                        };

                        reader.readAsDataURL(file);
                    }
                }
            });

        });
    </script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <!-- Initialize Swiper -->
    <script>
        var swiper = new Swiper(".mySwiper", {
            spaceBetween: 16,
            slidesPerView: 3.5,
            freeMode: true,
            watchSlidesProgress: true,
        });
        var swiper2 = new Swiper(".mySwiper2", {
            spaceBetween: 10,
            thumbs: {
                swiper: swiper,
            },
        });
        var swiper3 = new Swiper(".mySwiper3", {
            spaceBetween: 30,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get references to the buttons and tab panes
            const nextButton = document.querySelectorAll(".btnNext2");
            const tabPanes = document.querySelectorAll(".tab-pane");
            const navItems = document.querySelectorAll(".nav-link");

            // Add click event listeners to the "Next" buttons
            nextButton.forEach(function(button) {
                button.addEventListener("click", function() {
                    // Find the index of the current active tab
                    const activeTabIndex = Array.from(tabPanes).findIndex(function(pane) {
                        return pane.classList.contains("show", "active");
                    });

                    // Calculate the index of the next tab
                    const nextTabIndex = activeTabIndex + 1;

                    // Switch to the next tab by adding/removing appropriate classes
                    tabPanes[activeTabIndex].classList.remove("show", "active");
                    tabPanes[nextTabIndex].classList.add("show", "active");

                    // Update the active navigation items
                    navItems[activeTabIndex].classList.remove("active");
                    navItems[nextTabIndex].classList.add("active");
                });
            });
        });
    </script>
</body>

</html>
