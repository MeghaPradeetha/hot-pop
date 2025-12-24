@extends('layouts.home-layout')
<meta content="{{ csrf_token() }}" name="csrf-token">
@section('content')
<section class="d1-bg landing-body"> <!-- Landing page body -->

    <section class="">
        <div class="container py-4 py-lg-5">
            <div class="form-card p-200">
                <div class="header-card text-center">
                    <h3 class="p-b-24">Profile Setup</h3>
                </div>
                <div class="">
                    <!-- Navigation pills -->
                    <ul class="nav nav-pills my-3 d-flex justify-content-between" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button aria-controls="pills-home" aria-selected="false" class="nav-link dotpag p-0 active" data-bs-target="#pills-home" data-bs-toggle="pill" id="pills-home-tab" role="tab" type="button">1</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button aria-controls="pills-2" aria-selected="true" class="nav-link p-0 active d-flex align-items-center  " data-bs-target="#pills-2" data-bs-toggle="pill" id="pills-home-tab" role="tab" type="button"><span class="pro-bar">&nbsp;</span><span class="dotpag">2</span></button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button aria-controls="pills-3" aria-selected="false" class="nav-link p-0 d-flex align-items-center" data-bs-target="#pills-3" data-bs-toggle="pill" id="pills-3-tab" role="tab" type="button"><span class="pro-bar">&nbsp;</span><span class="dotpag">3</span></button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button aria-controls="pills-4" aria-selected="false" class="nav-link p-0 d-flex align-items-center" data-bs-target="#pills-4" data-bs-toggle="pill" id="pills-4-tab" role="tab" type="button"><span class="pro-bar">&nbsp;</span><span class="dotpag">4</span></button>
                        </li>
                    </ul>
                    <div class="tab-content setup-form-card" id="pills-tabContent">
                        <!-- Register Step 2 -->
                        <h3 class="headingH3 p-b-32">Your Introduction</h3>
                        <p class="f-16 d5 p-b-24">Record a 10 second video of yourself as an introduction to everyone. If you don't want to add a video at this stage you can simply skip this step by clicking "Save & Next" button and just add pictures from next page.</p>

                        <div class="row">
                            <div class="col-lg-7">
                                <div class="carousel-item w-100 active" style="height: 500px; overflow:unset;">
                                    <video autoplay class="video-slide w-100" id="video-player" loading="lazy" loop muted playsinline style="height: 500px; border-radius:39px;">
                                        @isset($video)
                                        <source src="{{ $video->file_path ? asset('storage/' . $video->file_path) : '' }}" type="video/mp4" />
                                        @endisset
                                    </video>
                                    <div id="record-timer" style="position: absolute; top: 5%; left: 50%; transform: translate(-50%, -50%); color:red; display: none; font-size:14px;">Recording...
                                    </div>
                                    <div style="position: absolute; top: 80%; left: 50%; transform: translate(-50%, -50%);">
                                        <button class="record-btn" id="startRecording">Start Recording</button>
                                        <button class="record-btn" id="stopRecording" style="display: none;">Stop Recording </button>
                                        {{-- <button class="capture-btn" id="startRecording"
                                                style="background-color: transparent; border: none; background-image: url('/images/btn_profilesetup_record.png'); width: 60px; height: 60px; background-size: cover;"></button>
                                            <button class="capture-btn" id="stopRecording"
                                                style="background-color: transparent; border: none; background-image: url('/images/btn_profilesetup_record.png'); width: 60px; height: 60px; background-size: cover; display:none;"></button> --}}
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-5 align-self-end mb-3">
                                <div class="text-danger text-center mb-2" id="error-msg" style="display: none;">Please Add Your Video First</div>
                                <button class="main-button w-100 mt-4" id="downloadLink" onclick="saveFormData()" style="display:none" type="submit">Save & Next</button>
                                <div class="mt-4 w-100">
                                    <a class="secondary-button w-100 d-flex justify-content-center" href="{{ route('get.profile-setup-pictures') }}">Skip</a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Register Step 2 end -->

    </section>

    @push('js')
    <script>
        const startVoiceRecordingButton = document.getElementById('voiceRecording');
        const muteVoiceRecordingButton = document.getElementById('muteVoiceRecording');
        const video = document.getElementById('video-player');
        const startRecordingButton = document.getElementById('startRecording');
        const stopRecordingButton = document.getElementById('stopRecording');
        const downloadLink = document.getElementById('downloadLink');
        const button = document.getElementById('downloadLink');
        const timer = document.getElementById('record-timer');

        let mediaRecorder;
        let recordedChunks = [];
        navigator.mediaDevices.getUserMedia({
                video: true,
                audio: true
            })
            .then(function(stream) {
                video.srcObject = stream;
                video.play();
            })
            .catch(function(err) {
                console.log("An error occurred: " + err);
            });

        stopRecordingButton.style.display = 'none';

        startRecordingButton.addEventListener('click', async () => {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: true,
                audio: true
            });

            // video.controls = true;
            startRecordingButton.style.display = 'none';
            stopRecordingButton.style.display = 'block';
            timer.style.display = 'block';
            video.srcObject = stream;

            // for emove stating black screen
            setTimeout(() => {
                mediaRecorder = new MediaRecorder(stream);

                mediaRecorder.ondataavailable = (event) => {
                    if (event.data.size > 0) {
                        recordedChunks.push(event.data);
                    }
                };

                mediaRecorder.onstop = () => {
                    const blob = new Blob(recordedChunks, {
                        type: 'video/webm'
                    });
                    const url = URL.createObjectURL(blob);
                    downloadLink.href = url;
                    downloadLink.style.display = 'block';
                };

                // todo
                mediaRecorder.start();
            }, 300);
        });

        stopRecordingButton.addEventListener('click', () => {
            if (mediaRecorder && mediaRecorder.state === 'recording') {
                mediaRecorder.stop();
                // video.controls = false;
            }
            timer.style.display = 'none';
            stopRecordingButton.style.display = 'none';
        });

        function mutevoiceHandle() {
            startVoiceRecordingButton.style.display = 'none';
            muteVoiceRecordingButton.style.display = 'block';
            vedio.volume(0);

        }

        function unmuteVoiceHandle() {
            startVoiceRecordingButton.style.display = 'block';
            muteVoiceRecordingButton.style.display = 'none';
        }

        function handleClick() {
            // Disable the button
            button.disabled = true;

            // Simulate some asynchronous operation (e.g., an AJAX request)
            setTimeout(() => {
                // Re-enable the button after a delay (e.g., 2 seconds)
                button.disabled = false;
            }, 2000); // 2000 milliseconds = 2 seconds
        }

        button.addEventListener('click', handleClick);

        function saveFormData() {
            button.disabled = true;
            button.innerHTML = "Uploading....";
            const blob = new Blob(recordedChunks, {
                type: 'video/webm'
            });
            recordedChunks = [];
            const url = URL.createObjectURL(blob);
            downloadLink.href = url;
            downloadLink.style.display = 'block';
            const formData = new FormData();
            formData.append('title', 'Video Title'); // Add a title for the video
            formData.append('video_data', blob);

            $.ajax({
                url: '/store-video', // Replace with your route URL
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': window.csrfToken, // Use the CSRF token from your layout or view
                },
                success: function(response) {
                    window.location.href = '/view';
                    // Handle success (e.g., display a confirmation message)
                },
                error: function(error) {
                    // Handle error
                }
            });
        }
    </script>
    @endpush

</section> <!-- Landing page body end -->
@endsection