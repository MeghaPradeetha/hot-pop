<!DOCTYPE html>
<html lang="en">

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

    <!-- Bootstrap Core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/sass/main.scss'])
    <link rel="icon" type="image/png" href="{{ asset('images/heart-logo.png') }}">
</head>

<body class="fixed-bgs" id="page-top">

    @include('layouts.header')

    <section class="d1-bg landing-body"> <!-- Landing page body -->
 
         <!-- 2-Column Video Slider Hero Section -->
         <section class="hero-slider-section">
             <div class="container h-100">
                 <div class="row h-100 align-items-center">
                     <!-- Left Column: Text & CTA -->
                     <div class="col-lg-7 col-md-12 hero-text-col">
                         <img src="{{ asset('images/heart-logo.png') }}" alt="Heart Logo" class="mb-3" style="max-height: 150px; width: auto; object-fit: contain;">
                         <h1 class="hero-headline text-start mb-4">Defy the Standard.</h1>
                         <p class="mb-5 f-20 opacity-75 text-start" style="color: #ccc; max-width: 600px;">
                             Elevate your dating experience with HOT POP - the community that goes beyond the ordinary.
                         </p>
                         <div class="text-start" style="margin-top: -30px;">
                             <a href="/register" class="floating-btn">Find Your New Partner</a>
                         </div>
                     </div>
 
                     <!-- Right Column: Video Slider -->
                     <div class="col-lg-5 col-md-12 hero-slider-col">
                         <div class="hero-slider-container">
                             <div class="swiper heroSwiper">
                                 <div class="swiper-wrapper">
                                     <!-- Slide 1 -->
                                     <div class="swiper-slide hero-video-slide">
                                         <video autoplay loop muted playsinline>
                                             <source src="{{ asset('videos/1.mp4') }}" type="video/mp4">
                                         </video>
                                     </div>
                                     <!-- Slide 2 -->
                                     <div class="swiper-slide hero-video-slide">
                                         <video autoplay loop muted playsinline loading="lazy">
                                             <source src="{{ asset('videos/2.mp4') }}" type="video/mp4">
                                         </video>
                                     </div>
                                     <!-- Slide 3 -->
                                     <div class="swiper-slide hero-video-slide">
                                         <video autoplay loop muted playsinline loading="lazy">
                                             <source src="{{ asset('videos/3.mp4') }}" type="video/mp4">
                                         </video>
                                     </div>
                                 </div>
                                 <!-- Pagination -->
                                 <div class="swiper-pagination"></div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             
             <!-- Interactive Debris -->
             <div class="debris-container" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; overflow: hidden; z-index: 1;">
                 <div class="debris-item"></div>
                 <div class="debris-item"></div>
                 <div class="debris-item"></div>
                 <div class="debris-item"></div>
                 <div class="debris-item"></div>
                 <div class="debris-item line"></div>
                 <div class="debris-item line" style="top: 70%; left: 10%; transform: rotate(-45deg);"></div>
             </div>
         </section>
 
         <!-- OLD HERO (Hidden/Removed) -->
        {{-- Previous Hero Section Removed for Redesign --}}


        <div class="why-bg"><img alt="lines" class="" src="images/img_landing_bgVec@2x.png" width="596px" /></div>

        <div class="feature-bg"><img alt="lines" class="" src="images/img_landing_bgVec@2x.png" width="899px" /></div>

        <section class="red-section p-t-80 p-b-40">
            <div class="container text-center">
                <h2 class="headingH1 text-center p-b-50">What is <span class="a1">HOT POP</span></h2>
                <p>HOT POP is a high-voltage orange surge designed for the restless and the bold to collide, shattering the mundane and launching into a weightless, high-octane reality where every connection hits like a lightning bolt.
                    <br />Join us today and experience the difference of HOT POP – where you can find your spice.
                </p>
            </div>
        </section>

        <!-- Orbital Features Section -->
        <section class="features-section p-t-80 p-b-100">
            <div class="container">
                <div class="text-center p-b-50">
                    <h2 class="headingH1">Features</h2>
                    <p class="opacity-75">Everything you need to connect.</p>
                </div>
                
                <div class="orbital-grid">
                    <!-- Feature 1 -->
                    <div class="orbital-card-wrapper">
                        <div class="feature-card text-center">
                            <div class="card-icon mx-auto mb-4">
                                <i class="fas fa-phone"></i>
                            </div>
                            <h3 class="font-bold f-24 mb-3">In app calling</h3>
                            <ul class="text-start opacity-75 list-unstyled">
                                <li class="mb-2"><i class="fas fa-check me-2 text-warning"></i>Ensures safety and security</li>
                                <li class="mb-2"><i class="fas fa-check me-2 text-warning"></i>Establishes a personal connection</li>
                                <li class="mb-2"><i class="fas fa-check me-2 text-warning"></i>Verifies compatibility</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="orbital-card-wrapper">
                        <div class="feature-card text-center">
                            <div class="card-icon mx-auto mb-4">
                                <i class="fas fa-comments"></i>
                            </div>
                            <h3 class="font-bold f-24 mb-3">Instant message</h3>
                            <ul class="text-start opacity-75 list-unstyled">
                                <li class="mb-2"><i class="fas fa-check me-2 text-warning"></i>Enables real-time communication</li>
                                <li class="mb-2"><i class="fas fa-check me-2 text-warning"></i>Enhances convenience</li>
                                <li class="mb-2"><i class="fas fa-check me-2 text-warning"></i>Fosters deeper connections</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="orbital-card-wrapper">
                        <div class="feature-card text-center">
                            <div class="card-icon mx-auto mb-4">
                                <i class="fas fa-video"></i>
                            </div>
                            <h3 class="font-bold f-24 mb-3">Video call</h3>
                            <ul class="text-start opacity-75 list-unstyled">
                                <li class="mb-2"><i class="fas fa-check me-2 text-warning"></i>Face to face communication</li>
                                <li class="mb-2"><i class="fas fa-check me-2 text-warning"></i>Authenticity and trust</li>
                                <li class="mb-2"><i class="fas fa-check me-2 text-warning"></i>Virtual dates</li>
                            </ul>
                        </div>
                    </div>
                 
                    <!-- Feature 4 -->
                    <div class="orbital-card-wrapper">
                        <div class="feature-card text-center">
                            <div class="card-icon mx-auto mb-4">
                                <i class="fas fa-images"></i>
                            </div>
                            <h3 class="font-bold f-24 mb-3">Profile Photos</h3>
                            <ul class="text-start opacity-75 list-unstyled">
                                <li class="mb-2"><i class="fas fa-check me-2 text-warning"></i>Visual attraction</li>
                                <li class="mb-2"><i class="fas fa-check me-2 text-warning"></i>Showcase personality</li>
                                <li class="mb-2"><i class="fas fa-check me-2 text-warning"></i>Conversion starters</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section> <!-- Landing page body end -->

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" async defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var swiper = new Swiper(".heroSwiper", {
                slidesPerView: 1,
                spaceBetween: 0,
                direction: 'horizontal',
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                effect: 'slide',
                speed: 800,
                grabCursor: true, // Indicates draggable
            });
        });
        
        function createVideoElement(src) {
            var videoElement = document.createElement('video');
            videoElement.classList.add('video-slide');
            videoElement.autoplay = true;
            // videoElement.playsinline = true;
            videoElement.loop = true;
            videoElement.muted = true;
            videoElement.setAttribute("playsinline", "");

            var sourceElement = document.createElement('source');
            sourceElement.src = src;
            sourceElement.type = 'video/mp4';

            videoElement.appendChild(sourceElement);
            return videoElement;
        }

        // Video sources
        var videoSources = [
            'images/sampleimages/Parent.mp4',
            'images/sampleimages/user.mp4',
            'images/sampleimages/user2.mp4'
        ];

        document.addEventListener('DOMContentLoaded', function() {
            var videoContainer = document.getElementById('videoContainer');

            // Iterate over video sources and create video elements
            videoSources.forEach(function(src, index) {
                var videoElement = createVideoElement(src);

                var carouselItem = document.createElement('div');
                carouselItem.classList.add('carousel-item');
                if (index === 0) {
                    carouselItem.classList.add('active');
                }

                var videoWrapper = document.createElement('div');
                videoWrapper.classList.add('video-slide');
                videoWrapper.appendChild(videoElement);

                carouselItem.appendChild(videoWrapper);
                videoContainer.appendChild(carouselItem);
            });
        });
    </script>
</body>

</html>
