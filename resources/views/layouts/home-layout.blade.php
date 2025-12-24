<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="ie=edge" http-equiv="X-UA-Compatible">
    <title>HOT POP</title>
    <link rel="icon" type="image/png" href="{{ asset('images/heart-logo.png') }}">

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

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    {{-- <link rel="stylesheet" href="{{ asset ('country_code/style.css') }}"> --}}

    <!-- Bootstrap Core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script> --}}
    {{-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> --}}

    <style>
        .iti {
            width: 100%;
        }
    </style>

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

    @vite(['resources/sass/main.scss'])

</head>

<body class="fixed-bgs" id="page-top">

    @include('layouts.header')

    <!-- Header Section Ends -->
    @yield('content')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> --}}

    @include('layouts.footer')

    <!-- Alert -->
    <div aria-hidden="true" aria-labelledby="logoutModalLabel" class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center">
                        <h3 class="p-b-50">Log Out</h3>
                        <p class="f-16 d5 p-b-50">Are you sure you want to logout</p>
                        <div class="d-lg-flex d-block justify-content-between">
                            <button class="small-button" data-bs-dismiss="modal" type="button">No</button>
                            <form action="{{ route('logout') }}" id="logout-form" method="POST">
                                @csrf
                                <button class="small-button color" type="sumbit">Yes</button></a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <script src="{{ asset('country_code/script.js') }}"></script> --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script> --}}
    @stack('js')

    <script>
        // $('.btnNext').click(function(e) {
        //     e.preventDefault();
        //     $('.nav-pills .active').parent().next('li').find('button').trigger('click');

        // });

        // $('.btnPrevious').click(function(e) {
        //     e.preventDefault();
        //     $('.nav-pills .active').parent().prev('li').find('button').trigger('click');
        // });

        // Add a click event listener to each tab button
        const tabButtons = document.querySelectorAll('.nav-link');
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove the 'active' class from all tab buttons
                tabButtons.forEach(btn => btn.classList.remove('active'));

                // Add the 'active' class to the clicked tab button and all previous buttons
                button.classList.add('active');
                const currentIndex = Array.from(tabButtons).indexOf(button);
                for (let i = 0; i < currentIndex; i++) {
                    tabButtons[i].classList.add('active');
                }
            });
        });

        /* Age range script */
        // var rangeOne = document.querySelector('input[name="min_age"]'),
        //     rangeTwo = document.querySelector('input[name="max_age"]'),
        //     outputOne = document.querySelector('.outputOne'),
        //     outputTwo = document.querySelector('.outputTwo'),
        //     inclRange = document.querySelector('.incl-range')
        // updateView = function() {
        //     if (this.getAttribute('name') === 'min_age') {
        //         outputOne.innerHTML = this.value;
        //         outputOne.style.left = (this.value - 20) / (this.getAttribute('max') - 20) * 100 + '%';
        //     } else {
        //         outputTwo.style.left = (this.value - 20) / (this.getAttribute('max') - 20) * 100 + '%';
        //         outputTwo.innerHTML = this.value;
        //     }
        //     if (parseInt(rangeOne.value) > parseInt(rangeTwo.value)) {
        //         inclRange.style.width = (rangeOne.value - rangeTwo.value) / (this.getAttribute('max') - 20) * 100 + '%';
        //         inclRange.style.left = (rangeTwo.value - 20) / (this.getAttribute('max') - 20) * 100 + '%';
        //     } else {
        //         inclRange.style.width = (rangeTwo.value - rangeOne.value) / (this.getAttribute('max') - 20) * 100 + '%';
        //         inclRange.style.left = (rangeOne.value - 20) / (this.getAttribute('max') - 20) * 100 + '%';
        //     }
        // };

        // document.addEventListener('DOMContentLoaded', function() {
        //     if (rangeOne) {
        //         updateView.call(rangeOne);
        //         updateView.call(rangeTwo);
        //         $('input[type="range"]').on('mouseup', function() {
        //             this.blur();
        //         }).on('mousedown input', function() {
        //             updateView.call(this);
        //         });
        //     }
        // });

        /* IMage upload */

        $(document).ready(function() {
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
                                    var html = "<div class='upload__img-box'><div style='background-image: url(" + e.target.result +
                                        ")' data-number='" + $(
                                            ".upload__img-close").length + "' data-file='" + f.name +
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
        });

        //Tutorial tab next button

        $(document).ready(function() {
            // Initialize the next button
            var currentContent = 1;
            var contentCount = 3; // Number of content sections
            var lastContentText = "Finish";
            var finishClicked = false;

            function redirectToPage() {
                window.location.href = "/logins"; // Change this to the desired URL
            }

            $("#tabNext").click(function() {
                if (finishClicked) {
                    redirectToPage();
                    return;
                }

                // Hide the current content
                $("#tu" + currentContent).removeClass("show active");

                // Move to the next content
                currentContent++;
                if (currentContent > contentCount) {
                    // If last content is reached
                    currentContent = contentCount;
                    finishClicked = true;
                    $(this).text(lastContentText);
                }

                // Show the next content
                $("#tu" + currentContent).addClass("show active");
            });
        });


        // disable button

        //   document.getElementById("registrationLink").addEventListener("click", function(event) {
        //     event.preventDefault(); // Prevents the default behavior of the link
        //     // Optionally, you can add a class to visually indicate that the link is disabled
        //     this.classList.add("disabled-link");

        //   });

        //   document.getElementById("loginLink").addEventListener("click", function(event) {
        //     event.preventDefault(); // Prevents the default behavior of the link
        //     // Optionally, you can add a class to visually indicate that the link is disabled
        //     this.classList.add("disabled-link");

        //   });
    </script>

</body>

</html>
