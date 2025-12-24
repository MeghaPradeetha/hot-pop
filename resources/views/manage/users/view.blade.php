@extends('oxygen::layouts.master-dashboard')

<?php
$pageTitle = 'User Profile';
?>
<style>
	* {box-sizing: border-box}
	.mySlides1, .mySlides2 {display: none}
	img {vertical-align: middle;}

	/* Slideshow container */
	.slideshow-container {
	  max-width: 1000px;
	  position: relative;
	  margin: auto;
	}

	/* Next & previous buttons */
	.prev, .next {
	  cursor: pointer;
	  position: absolute;
	  top: 50%;
	  width: auto;
	  padding: 16px;
	  margin-top: -22px;
	  color: white;
	  font-weight: bold;
	  font-size: 18px;
	  transition: 0.6s ease;
	  border-radius: 0 3px 3px 0;
	  user-select: none;
	}

	/* Position the "next button" to the right */
	.next {
	  right: 0;
	  border-radius: 3px 0 0 3px;
	}

	/* On hover, add a grey background color */
	.prev:hover, .next:hover {
	  background-color: #f1f1f1;
	  color: black;
	}
	</style>
@section('content')
    {{ lotus()->pageHeadline($pageTitle) }}

    <div class="row">
        <div class="col-lg-12">
            <div class="">
                <div class="video-bg position-relative w-100">
                    @if ($video)
                        <video autoplay class="video-slide w-100" id="myVideo" loop onclick="playVideo()" playsinline>
                            <source src="{{ asset('storage/' . $video) }}" type="video/mp4" />
                        </video>

                        <div id="playIcon" onclick="playVideo()"
                            style="height: 100%;position: absolute;top: 0;width: 100%;display: none;place-items: center;">
                            <img alt="filter" class="play-icon"
                                src="{{ asset('images/btn_profilesetup_play@2x.png') }}" />
                        </div>

                        <a class="volume-btn d1" id="volumeButton" onclick="volumeButton();">
                            <i class="fa fa-volume-high fa-2xl"></i>
                        </a>
                        <a class="volume-btn d1" id="volumemuteButton" onclick="volumemuteButton();" style="display: none;">
                            <i class="fa-solid fa-volume-xmark fa-2xl"></i>
                        </a>
                    @else
					@if ($entity->latestPhotoFile)
						<img src="{{ $entity->latestPhotoFile->file_url }}"
                            style="object-fit: cover;height: 100%;width: 100%;" />
					@endif

                    @endif

                </div>
            </div>
        </div>
    </div>
	<br>
    <!-- Slideshow container -->
    <div class="slideshow-container" style="width: 40%">
        @foreach ($image_array as $photo)
		<div class="mySlides1">
                <img style="width:100%; height:20%"src="{{ asset('storage/' . $photo) }}" />
            </div>
        @endforeach


        <!-- Next and previous buttons -->
		<a class="prev" onclick="plusSlides(-1, 0)">&#10094;</a>
		<a class="next" onclick="plusSlides(1, 0)">&#10095;</a>
    </div>
    <br>

    <!-- The dots/circles -->
    <div style="text-align:center">
        <span class="dot" onclick="currentSlide(1)"></span>
        <span class="dot" onclick="currentSlide(2)"></span>
        <span class="dot" onclick="currentSlide(3)"></span>
    </div>
    <div class="form-group row mx-auto">

        <div class="col-md-10">
            <h2 class="headingH2 pb-1" style="margin-bottom: 15px;">{{ $entity->name }}</h2>

            <p class="d2 p-b-24" style="font-size: 20px; color:gray; margin-bottom: 10px;">
                <i class="fa fa-map-marker-alt me-2"></i> {{ $entity->location }}
            </p>

            <b>
                <p class="p-b-24" style="font-size: 16px; margin-bottom: 15px;">
                    {{ $entity->personality->nationality ?? 'N/A' }} | {{ $entity->age ?? 'N/A' }} Years |
                    {{ $entity->personality->occupation ?? 'N/A' }}
                </p>
            </b>

            <p class="headingH3 pb-3" style="margin-bottom: 20px;">{{ $entity->avatar_path }}</p>

            @if ($interests != null)
                @foreach ($interests as $interest)
                    <label
                        style="border: 1px solid #ccc; border-radius: 20px; padding: 5px; margin-right: 10px; margin-bottom: 10px; display: inline-block;">
                        <span class="icon-check-label">{{ $interest }}</span>
                    </label>
                @endforeach
            @endif

            <h3 class="headingH3 pb-3 pt-2">My Personality</h3>
            <div class="form-group row mx-auto">
                <div class="col-md-4">
                    <div class="personality-card">
                        <p class="d-flex align-items-center opacity-50"><img alt=""
                                src="{{ asset('images/icon_profile_relationship.png') }}" /><span
                                class="mx-2">|</span>Relationship Type</p>
                        <p class="fw-bold">{{ $entity->personality->relationship_type ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="personality-card">
                        <p class="d-flex align-items-center "><img alt=""
                                src="{{ asset('images/ic_profile_height.png') }}" /><span class="mx-2">|</span>My Height
                        </p>
                        <p class="fw-bold">{{ $entity->personality->height ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="personality-card">
                        <p class="d-flex align-items-center "><img alt=""
                                src="{{ asset('images/ic_profile_language.png') }}" /><span
                                class="mx-2">|</span>Nationality</p>
                        <p class="fw-bold">{{ $entity->personality->nationality ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            <div class="form-group row mx-auto">
                <div class="col-md-4">
                    <div class="personality-card">
                        <p class="d-flex align-items-center"><img alt=""
                                src="{{ asset('images/icon_profile_hometown.png') }}" /><span class="mx-2">|</span>My
                            Hometown</p>
                        <p class="fw-bold">{{ $entity->personality->hometown ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="personality-card">
                        <p class="d-flex align-items-center"><img alt=""
                                src="{{ asset('images/icon_profile_kids.png') }}" /><span class="mx-2">|</span>I want
                            Children in Future</p>
                        <p class="fw-bold">{{ $entity->personality->like_to_have_more_childran ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>






    <style>
        .personality-card {
            border: 1px solid #ccc;
            /* Add border */
            border-radius: 10px;
            /* Rounded corners */
            padding: 15px;
            /* Padding inside the card */
            margin-bottom: 15px;
            /* Space between cards */
            background-color: #f9f9f9;
            /* Optional: Light background color */
        }

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
    @push('js')
	<script>
		let slideIndex = [1,1];
		let slideId = ["mySlides1", "mySlides2"]
		showSlides(1, 0);
		showSlides(1, 1);

		function plusSlides(n, no) {
		  showSlides(slideIndex[no] += n, no);
		}

		function showSlides(n, no) {
		  let i;
		  let x = document.getElementsByClassName(slideId[no]);
		  if (n > x.length) {slideIndex[no] = 1}
		  if (n < 1) {slideIndex[no] = x.length}
		  for (i = 0; i < x.length; i++) {
			 x[i].style.display = "none";
		  }
		  x[slideIndex[no]-1].style.display = "block";
		}
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
@stop
