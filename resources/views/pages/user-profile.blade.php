@extends('layouts.header-dashboard')

@section('content')

    <body onload="onLoad()">
        <section class="d1-bg landing-body h-100"> <!-- Landing page body -->

            <section class="">
                <div class="container py-4 py-lg-5">
                    <div class="form-card p-4">
                        <div class="header-bar position-relative mb-4">
                            <h3 class="headingH2">Your Profile</h3>
                            <a href="{{ route('profile-edit', $my_profile->id) }}" class="d-inline-block text-decoration-none filter-ico">
                                <div class="filter-icon-btn d-flex align-items-center justify-content-center" style="width: 67px; height: 67px; border-radius: 50%; background: rgba(255, 102, 0, 0.1); border: 2px solid #FF6600; transition: all 0.3s ease;">
                                    <i class="fas fa-pen" style="font-size: 24px; color: #FF6600;"></i>
                                </div>
                            </a>
                        </div>

                        <div class="row">
                            <div class="col-lg-7">
                                <div class="">
                                    <div class="video-bg position-relative w-100">
                                        @if ($video)
                                            <video autoplay class="video-slide w-100" id="myVideo" loop onclick="playVideo()" playsinline>
                                                <source src="{{ asset('storage/'.$video) }}" type="video/mp4" />
                                            </video>

                                            <div id="playIcon" onclick="playVideo()" style="height: 100%;position: absolute;top: 0;width: 100%;display: none;place-items: center;">
                                                <img alt="filter" class="play-icon" src="{{ asset('images/btn_profilesetup_play@2x.png') }}" />
                                            </div>

                                            <a class="volume-btn d1" id="volumeButton" onclick="volumeButton();">
                                                <i class="fa fa-volume-high fa-2xl"></i>
                                            </a>
                                            <a class="volume-btn d1" id="volumemuteButton" onclick="volumemuteButton();" style="display: none;">
                                                <i class="fa-solid fa-volume-xmark fa-2xl"></i>
                                            </a>
                                        @else
                                            <img src="{{ asset('storage/'.$my_profile->avatar) }}" style="object-fit: cover;height: 100%;width: 100%;" />
                                        @endif

                                    </div>
                                </div>

                                <div class="swiper mySwiper mt-4 w-video" thumbsSlider="">
                                    <div class="swiper-wrapper">
                                        @foreach ($image_array as $photo)
                                            <div class="swiper-slide slideThumb h-auto">
                                                <img height="100%" src="{{ asset('storage/'.$photo) }}" />
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-5 p-t-24 position-relative">
                                <h2 class="headingH2 pb-1">{{ $my_profile->full_name }}</h2>
                                <p class="d2 p-b-24"><i class="fa fa-map-marker-alt me-2"></i> {{ $my_profile->location }}</p>
                                <p class="p-b-24">{{ $my_profile->personality->nationality ?? 'N/A' }} | {{ $my_profile->age ?? 'N/A' }} Years |
                                    {{ $my_profile->personality->occupation ?? 'N/A' }}</p>

                                <p class="headingH3 pb-3">{{ $my_profile->avatar_path }}</p>
                                @if ($interests != null)
                                    @foreach ($interests as $interest)
                                        <label><span class="icon-check-label">{{ $interest }}</span></label>
                                        {{-- <label><span class="icon-check-label">💃 Partying</span></label>
						<label><span class="icon-check-label">🏝️ Travel</span></label> --}}
                                    @endforeach
                                @endif
                                <p class="headingH3 pb-3 pt-2">My Personality</p>
                                <div class="personality-card">
                                    <p class="d-flex align-items-center opacity-50"><img alt="" src="{{ asset('images/icon_profile_relationship.png') }}" /><span
                                            class="mx-2">|</span>Relationship Type</p>
                                    <p class="fw-bold">{{ $my_profile->personality->relationship_type ?? 'N/A' }}</p>
                                </div>
                                <div class="personality-card">
                                    <p class="d-flex align-items-center opacity-50"><img alt="" src="{{ asset('images/ic_profile_height.png') }}" /><span
                                            class="mx-2">|</span>My Height</p>
                                    <p class="fw-bold">{{ $my_profile->personality->height ?? 'N/A' }}</p>
                                </div>
                                <div class="personality-card">
                                    <p class="d-flex align-items-center opacity-50"><img alt="" src="{{ asset('images/ic_profile_language.png') }}" /><span
                                            class="mx-2">|</span>Nationality</p>
                                    <p class="fw-bold">{{ $my_profile->personality->nationality ?? 'N/A' }}</p>
                                </div>
                                <div class="personality-card">
                                    <p class="d-flex align-items-center opacity-50"><img alt="" src="{{ asset('images/icon_profile_hometown.png') }}" /><span
                                            class="mx-2">|</span>My Hometown</p>
                                    <p class="fw-bold">{{ $my_profile->personality->hometown ?? 'N/A' }}</p>
                                </div>
                                <div class="personality-card">
                                    <p class="d-flex align-items-center opacity-50"><img alt="" src="{{ asset('images/icon_profile_kids.png') }}" /><span
                                            class="mx-2">|</span>I want Children in Future</p>
                                    <p class="fw-bold">{{ $my_profile->personality->like_to_have_more_childran ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </section> <!-- Landing page body end -->
    </body>
@endsection

@push('js')
    <script>
        function playVideo() {
            var video = document.getElementById("myVideo");
            var icon = document.getElementById("playIcon");
            if (video.paused) {
                video.play();
                icon.style.display = 'none';
            } else {
                video.pause();
                icon.style.display = 'grid';
            }
        };

        function onLoad() {

            //display the volume button on load hide mute button
            $("#volumemuteButton").hide();
        }


        function volumeButton() {

            document.getElementById("volumeButton").style.display = 'none';
            document.getElementById("volumemuteButton").style.display = 'block';
            document.getElementById("myVideo").muted = true;
        }

        function volumemuteButton() {

            document.getElementById("volumeButton").style.display = 'block';
            document.getElementById("volumemuteButton").style.display = 'none';
            document.getElementById("myVideo").muted = false;
        }
    </script>
@endpush
