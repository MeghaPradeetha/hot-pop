@extends('layouts.header-dashboard')

@section('content')
<style>
	@media (max-width: 768px) {
		. /* Ensure profile info stays below image */
    .swipe-action {
        margin-top: 120px;
		font-size: 10px;
    }
	.match-image-container {
		height: 700px;
	}
	.close-bt {
		margin-bottom: 400px;
		right: 0;
	}
	.pb-2{
		font-size: 22px;
	}
	.icon-check-label {
            font-size: 12px !important; /* Smaller font size on mobile */
            padding: 2px 10px !important; /* Reduced padding on mobile */
        }
	}
	</style>
    <section class="d1-bg landing-body h-100">
        <div class="container py-4 py-lg-5">
            <div class="form-card">
                <div class="p-4">
                    <div class="header-bar position-relative mb-lg-4">
                        <h3 class="headingH2">Matches ({{ $profile_count }})</h3>
                    </div>
                </div>

                <div class="form-card p-200 shadow-none position-relative">
                    <div class="swiper mySwiper3">
                        <div class="swiper-wrapper">
                            @foreach ($profile_match as $match)
                                <div class="swiper-slide">
                                    <div class="match-image-container position-relative mx-auto">
                                        <div class="video-bg">
                                            @if ($match->preferredUser->video)
                                                <video
                                                    autoplay
                                                    class="video-slide w-100"
                                                    id="my-video-{{ $match->id }}"
                                                    loop
                                                    muted
                                                    onclick="playVideo({{ $match->id }})"
                                                    playsinline
                                                    style="{{ $match->preferredUser ? '' : 'filter: blur(30px)' }}"
                                                >
                                                    <source
                                                        src="{{ asset('storage/' . $match->preferredUser->video) ?? asset('images/sampleimages/user3.mp4') }}"
                                                    />
                                                </video>

                                                <div id="play-icon-{{ $match->id }}" onclick="playVideo({{ $match->id }})"
                                                    style="height: 100%;position: absolute;top: 0;width: 100%;display: grid;place-items: center;display:none;"
                                                >
                                                    <img alt="filter" class="play-icon" src="{{ asset('images/btn_profilesetup_play@2x.png') }}" />
                                                </div>

                                                <a
                                                    class="volume-btn d1"
                                                    id="volumeButton-{{ $match->id }}"
                                                    onclick="volumeButton({{ $match->id }});"
                                                    style="display: none;"
                                                >
                                                    <i class="fa fa-volume-high fa-2xl"></i>
                                                </a>
                                                <a class="volume-btn d1" id="volumemuteButton-{{ $match->id }}"
                                                    onclick="volumemuteButton({{ $match->id }});"
                                                >
                                                    <i class="fa-solid fa-volume-xmark fa-2xl"></i>
                                                </a>
                                            @else
                                                <img src="{{ asset('storage/' . $match->preferredUser->avatar) }}"
                                                    style="object-fit: cover;height: 100%;width: 100%;"
                                                />
                                            @endif
                                        </div>
                                        <div class="swipe-action match-swipe d-flex justify-content-between align-items-end">
                                            <div class="profile-info">
                                                <h3 class="pb-2" id="profile-name">
                                                    {{ $match->preferredUser->full_name }}</h3>
                                                <p class="pb-2">
                                                    <i aria-hidden="true"
                                                        class="fa {{ $match->preferredUser->gender == 'Male' ? 'fa-mars' : 'fa-venus' }}"
                                                    ></i>
                                                    <span class="mx-lg-2">|</span>
                                                    <span id="age">{{ $match->preferredUser->age }}</span> Years
                                                    <span class="mx-lg-2">|</span>
                                                    <span id="occupation">{{ $match->preferredUser->personality->occupation }}</span>
                                                </p>

                                                @php
                                                    $interests_list = explode(',', $match->preferredUser->personality->interests);
                                                @endphp
                                               @foreach ($interests_list as $index => $interest)
											   @if ($index < 4)
												   <label><span class="icon-check-label d5-bg d1 px-lg-4">{{ $interest }}</span></label>
											   @endif
										   @endforeach

                                            </div>

                                            <form class="close-bt" action="{{ route('match.unset', $match->id) }}" method="POST" >
                                                @csrf
                                                @method('DELETE')
                                                <button style="background-color: transparent;border:unset" type="submit">
                                                    <i class="fa-solid fa-xmark circle-btn"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="text-center my-3">
                                        <a href="{{ url('/user-profile/' . $match->preferredUser->id) }}">
                                            <b>
                                                <p style="color: #6E38E2; font-size:20px"><i class="fa-solid fa-user"></i>
                                                    View Profile</p>
                                            </b>

                                        </a><br>
                                        <a href="{{ route('chat.profile', $match->preferredUser->id) }}">
                                            <button class="main-button w-100" type="button">Message
                                                <img class="invert-white ms-3" src="{{ asset('images/icon_navbar_chat.png') }}" />
                                            </button>
                                        </a>
                                    </div>

                                    {{-- <div class="text-center my-3 d-flex">
                                        <a class="secondary-button w-100 d1" href="/subscription"><span class="a1">Subscribe for more</span> </a>
                                    </div> --}}
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script>
        function playVideo(id) {
            var video = document.getElementById(`my-video-${id}`);
            var icon = document.getElementById(`play-icon-${id}`);
            if (video.paused) {
                video.play();
                icon.style.display = 'none';
            } else {
                video.pause();
                icon.style.display = 'grid';
            }
        };

        function volumeButton(id) {
            document.getElementById("volumeButton-" + id).style.display = 'none';
            document.getElementById("volumemuteButton-" + id).style.display = 'block';
            document.getElementById("my-video-" + id).muted = true;
        }

        function volumemuteButton(id) {
            document.getElementById("volumeButton-" + id).style.display = 'block';
            document.getElementById("volumemuteButton-" + id).style.display = 'none';
            document.getElementById("my-video-" + id).muted = false;
        }
    </script>
@endpush
