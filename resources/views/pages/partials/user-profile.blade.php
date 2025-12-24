<style>
	/* Style the image container */
.image-container img {
  width: 100%;
  max-width: 300px;
  cursor: pointer;
  transition: transform 0.2s ease-in-out;
}

.image-container img:hover {
  transform: scale(1.05); /* Slight hover zoom */
}

/* Modal styles */
.img-modal {
  display: none;
  position: fixed;
  z-index: 1;
  padding-top: 100px;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.8);
}

.img-modal-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
}

.close {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #fff;
  font-size: 40px;
  font-weight: bold;
  cursor: pointer;
}

.close:hover,
.close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}
</style>
<div class="col-lg-7">
    <div class="video-bg position-relative w-100">
        @if ($user->video)
            <video autoplay class="video-slide w-100" id="myVideo" loading="lazy" loop onclick="playVideo()" playsinline>
                <source src="{{ 'storage/'.$user->video ?? '/images/sampleimages/user3.mp4' }}" type="video/mp4" />
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
            <img src="{{ asset('storage/'.$user->avatar) }}" style="object-fit: cover;height: 100%;width: 100%;" />
        @endif
        <div class="swipe-action pagination-button">
            <form id="form" method="POST">
                {{-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> --}}
                <input id="preferred_user_id" name="preferred_user_id" type="hidden" value="{{ $user->id }}">
                <input id="user_id" name="user_id" type="hidden" value="{{ Auth()->user()->id }}">
                <button id="sb1" class="nxt-btn1" name="like" onclick="saveFormData(1)" style="background-color: transparent;border:unset" type="button"><i
                        class="fa-solid fa-check circle-btn active"></i></button><br>
                <button id="sb2" class="nxt-btn" name="dislike" onclick="nextProfile()" style="background-color: transparent;border:unset" type="button"><i
                        class="fa-solid fa-xmark circle-btn"></i></button>
            </form>
        </div>
    </div>
    <div id="imageList" style="display: none;">
        <div class='swiper mySwiper mt-4 w-video'>
            <div class='swiper-wrapper'>
                @foreach ($user->files as $file)
                    @if ($file->category == 'photos')
                        <div alt='photo' class='swiper-slide slideThumb' style="width:33%">
                            <img class="zoomable-image" src="{{ asset('storage/'.$file->file_path) }}" />
                        </div>
                    @endif
                @endforeach
            </div>
			<div id="image-modal" class="img-modal">
				<span class="close">&times;</span>
				<img class="img-modal-content" id="modal-image">
			</div>
        </div>
    </div>
</div>

<div class="col-lg-5 p-t-24 d-flex flex-wrap">
    <div class="w-100">
        <h2 class="headingH2 p-b-24" id="profile-name">{{ $user->full_name }}</h2>
        <p class="d2 pb-2 pb-lg-3"><i class="fa fa-map-marker-alt me-2"></i><span id="location">{{ $user->location }}</span></p>
        <p class="p-b-50">
            <span id="nationality">{{ $user->personality->nationality ?? '' }}</span> |
            <span id="age">{{ $user->age }}</span> Years |
            <span id="occupation">{{ $user->personality->occupation ?? '' }}</span>
        </p>

        <p class="headingH3 pb-3">My Interests</p>
        @foreach (explode(',', $user->personality?->interests ?? '') as $interest)
            <label>
                <span class="icon-check-label" id="interests">{{ $interest }}</span>
            </label>
        @endforeach
        {{-- <label>
            <input class="icon-check" type="checkbox">
            <span class="icon-check-label" id="interests">{{ $user->personality->interests ?? '' }}</span>
        </label> --}}

        <div id="personality" style="display: none;">
            <h3 class='headingH3 pb-3 pt-2'>My Personality</h3>
            <div class='personality-card'>
                <p class='d-flex align-items-center opacity-5'><img src='{{ asset('images/icon_profile_relationship.png') }}' /><span class='mx-1 mx-lg-2'>|</span>Relationship
                    Type</p>
                <p class='fw-bold'>{{ $user->personality->relationship_type ?? '' }}</p>
            </div>

            <div class='personality-card'>
                <p class='d-flex align-items-center opacity-5'><img src='{{ asset('images/ic_profile_height.png') }}' /><span class='mx-1 mx-lg-2'>|</span>My Height</p>
                <p class='fw-bold'>{{ $user->personality->height ?? '' }}</p>
            </div>

            <div class='personality-card'>
                <p class='d-flex align-items-center opacity-5'><img src='{{ asset('images/ic_profile_language.png') }}' /><span class='mx-1 mx-lg-2'>|</span> Nationality</p>
                <p class='fw-bold'>{{ $user->personality->nationality ?? '' }}</p>
            </div>

            <div class='personality-card'>
                <p class='d-flex align-items-center opacity-5'><img src='{{ asset('images/icon_profile_hometown.png') }}' /><span class='mx-1 mx-lg-2'>|</span>My Hometown</p>
                <p class='fw-bold'>{{ $user->personality->hometown ?? '' }}</p>
            </div>

            <div class='personality-card'>
                <p class='d-flex align-items-center opacity-5'><img src='{{ asset('images/icon_profile_kids.png') }}' /><span class='mx-1 mx-lg-2'>|</span>I want Children in
                    Future</p>
                <p class='fw-bold'>{{ $user->personality->like_to_have_more_childran ?? '' }}</p>
            </div>
        </div>

    </div>
    <div class="align-self-end w-100">
        <button class="secondary-button w-100" id="profile" onclick="toggleMore()">
            <span class="button-text-more">View Profile</span>
            <span class="button-text-less" style="display: none;">Swipe Up For Less</span>
            <i class="fa-solid fa-chevron-down circle-border ms-4"></i>
        </button>
    </div>
</div>

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = document.getElementById("image-modal");
        var modalImg = document.getElementById("modal-image");
        var close = document.getElementsByClassName("close")[0];
		var button = document.getElementById("sb1"); // Get the button element
		var button2 = document.getElementById("sb2");
        // Attach event listeners to each image in the Swiper
        var images = document.querySelectorAll('.zoomable-image');
        images.forEach(function(image) {
            image.addEventListener('click', function() {
                modal.style.display = "block";
                modalImg.src = this.src;

				     // Hide the button when the image is zoomed
					 button.style.display = "none";
					 button2.style.display = "none";
            });
        });

        // Close the modal when the user clicks on the 'X'
        close.onclick = function() {
            modal.style.display = "none";
			button.style.display = "block";
			button2.style.display = "block";
        }

        // Close the modal when clicking outside the image
        window.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
				button.style.display = "block";
				button2.style.display = "block";
            }
        }
    });
</script>
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

        function toggleMore() {
            $('.button-text-more, .button-text-less').toggle();
            $('#imageList').fadeToggle('slow');
            $('#profile').find('i').toggleClass('fa-chevron-down fa-chevron-up');
            $('#personality').slideToggle('slow');
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
