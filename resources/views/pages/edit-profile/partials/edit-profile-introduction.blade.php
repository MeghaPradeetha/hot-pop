<div class="px-xl-5 py-4">
    <h3 class="headingH3 p-b-32">Your Introduction</h3>
    <p class="f-16 d5 p-b-24">Record a 10 second video of yourself as an introducation to everyone</p>
	@if ($video)
    <div class="row">
        <div class="col-lg-7">
            <div class="carousel-item w-100 active" style="height: 500px; overflow:unset;">
                @if($video)
                <video autoplay class="video-slide w-100" controls id="video-player" loading="lazy" loop muted playsinline style="height: 500px;border-radius:39px;">
                    <source src="{{ asset('storage/'.$video->file_path ?? null) }}" type="video/mp4" />
                </video>
                @endif
                <div id="record-timer" style="position: absolute; top: 5%; left: 15%; transform: translate(-50%, -50%); color:red; display: none;">Rec. 00:00</div>
                <div style="position: absolute; top: 80%; left: 50%; transform: translate(-50%, -50%);">

                    <button class="record-btn" id="startRecording">Start Recording</button>
                    <button class="record-btn" id="stopRecording" style="display: none;">Stop Recording </button>
                </div>
            </div>
        </div>

        <div class="col-lg-5 ps-5 mt-3 align-self-end">
            <div class="text-danger text-center mb-2" id="error-msg" style="display: none;">Please Add Your Video First</div>

            @if ($video)
                <button class="secondary-button w-100 mb-2" onclick="deleteVideo()">Delete</button>
            @endif

            <button class="secondary-button w-100 mb-2" onclick="retakeVideo()">Retake</button>

            <button class="main-button w-100" id="downloadLink" onclick="saveVideo()" type="submit">Save & Next</button>
        </div>
    </div>
	@else
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
				<a class="secondary-button w-100 d-flex justify-content-center" onclick="saveVideo()">Skip</a>

			</div>
		</div>
	</div>
	@endif
</div>

@push('js')
    <script>
        const startVoiceRecordingButton = document.getElementById('voiceRecording');
        const muteVoiceRecordingButton = document.getElementById('muteVoiceRecording');
        const videoPlayer = document.getElementById('video-player');
        const startRecordingButton = document.getElementById('startRecording');
        const stopRecordingButton = document.getElementById('stopRecording');
        const button = document.getElementById('downloadLink');
        const recordTimerElement = document.getElementById('record-timer');
        const videoTab = document.getElementById('video-tab');

        let videoFile = '{{ $video }}';

        let mediaRecorder;
        let recordedChunks = [];
        let seconds = 0;
        let stream = null;
        let timerInterval;

        if (videoFile) {
            startRecordingButton.style.display = 'none';
        } else {
            videoPlayer.controls = false;
            videoTab.addEventListener('click', async function() {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: true,
                    audio: true
                })
                videoPlayer.srcObject = stream;
            });
        }
        // stopRecordingButton.style.display = 'none';

        startRecordingButton.addEventListener('click', () => {
            videoPlayer.controls = true;
            startRecordingButton.style.display = 'none';
            stopRecordingButton.style.display = 'block';
            recordTimerElement.style.display = 'block';
            document.getElementById('error-msg').style.display = 'none';
            // videoPlayer.srcObject = stream;
            seconds = 0;

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
                    videoPlayer.srcObject = null;
                    videoPlayer.src = url;
                    videoPlayer.controls = true;
                    stopRecordingButton.style.display = 'none';
					downloadLink.href = url;
                    downloadLink.style.display = 'block';
                };

                updateRecordTimer();
                mediaRecorder.start();
            }, 300);
        });

        stopRecordingButton.addEventListener('click', () => {
            if (mediaRecorder) {
                mediaRecorder.stop();
                const tracks = videoPlayer.srcObject.getTracks();
                tracks.forEach(track => track.stop());
                clearInterval(timerInterval);
                // videoPlayer.controls = false;
            }
        });

        async function retakeVideo() {
            seconds = 0;
            videoFile = null;
            // videoPlayer.src = null;
            // videoPlayer.removeAttribute('src');
            recordedChunks = [];
            recordedBlob = null;
            videoPlayer.controls = false;
            startRecordingButton.style.display = 'block';
            recordTimerElement.innerText = "Rec. 00:00";
            const sourceElement = videoPlayer.querySelector('source');
            if (sourceElement) {
                sourceElement.removeAttribute('src');
            }

            button.innerHTML = "Save & Next";
            button.disabled = false;

            stream = await navigator.mediaDevices.getUserMedia({
                video: true,
                audio: true
            })
            videoPlayer.srcObject = stream;

        }

        function deleteVideo() {
            $.ajax({
                url: '{{ route('edit-delete-videos', $user->id) }}',
                method: 'DELETE',
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                success: function(response) {
                    retakeVideo();
                },
                error: function(error) {
                    console.log("💩 ~ file: edit-profile-introduction:141 ~ error:", error)
                    // Handle error
                }
            });

        }

        function saveVideo() {
            if (mediaRecorder) {
                // console.log('save')
                saveFormData();
            } else if (videoFile) {
                switchToPicturesTab();
            } else {
				switchToPicturesTab();
            }
        }

        function saveFormData() {
            button.innerHTML = "Uploading....";
            button.disabled = true;
            const blob = new Blob(recordedChunks, {
                type: 'video/webm'
            });
            recordedChunks = [];
            const url = URL.createObjectURL(blob);
			downloadLink.href = url;
            downloadLink.style.display = 'block';
            const formData = new FormData();
            formData.append('title', 'Video Title');
            formData.append('video_data', blob);

            $.ajax({
                url: '{{ route('video-update', $user->id) }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                success: function(response) {
                    switchToPicturesTab();
                    // window.location.href = '/view';
                    // Handle success (e.g., display a confirmation message)
                },
                error: function(error) {
                    console.log("💩 ~ file: edit-profile-introduction:181 ~ error:", error)
                    // Handle error
                }
            });
        }

        function updateRecordTimer() {
            timerInterval = setInterval(() => {
                seconds++;
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = seconds % 60;
                const formattedTime = `Rec. ${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
                recordTimerElement.innerText = formattedTime;
            }, 1000);
        }
    </script>
@endpush
