<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="{{ csrf_token() }}" name="csrf-token">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Video Chat</title>

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

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Your additional styles or scripts can be included here -->
    {{--
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" /> --}}

    @vite(['resources/sass/main.scss'])
    <link rel="icon" type="image/png" href="{{ asset('images/heart-logo.png') }}">
</head>

<body>
    <section>
        <div class="position-relative bg-dark" id="video-container" style="min-height: 600px; height: 100vh;">
            <img class="start-50 top-50 translate-middle" src="{{ $caller->avatar2 }}"
                style="width: 200px; height: 200px; border-radius: 50%; position: absolute;"
            >
            <div class="text-white position-absolute top-50 start-50 fs-1 translate-middle" id="connect-text">Connecting...</div>
            <div class="align-content-around bg-secondary position-absolute px-3 py-2 rounded-pill text-white"
                style="top:20px; left:20px; z-index: 2;"
            >
                <i class="fa fa-microphone" id="mic-icon"></i>
            </div>

            <div class="position-absolute top-0 end-0" id="my-video" style="width:200px;z-index: 2;"></div>

            <div class="position-absolute w-100 d-flex justify-content-center mb-4" id="btn-list" style="z-index: 2; display:none; bottom: 10px;">
                <button class="btn btn-secondary rounded-circle mx-2 px-3" id="mic-button" onclick="toggleAudioMute(this)">
                    <i class="fa fa-microphone"></i>
                </button>
                <div class="d-inline-block">
                    <form action="{{ route('video.end-calling') }}" id="endCallForm" method="POST">
                        @csrf
                        <input
                            id="user_id"
                            name="user_id"
                            type="hidden"
                            value="{{ $caller->id }}"
                        >
                        <button class="btn btn-danger rounded-circle btn-lg mx-2" id="end-call-button" onclick="disconnect()">
                            <i class="fa fa-phone"></i>
                        </button>
                    </form>
                </div>

                <button class="btn btn-secondary rounded-circle mx-2 px-3" id="camera-button" onclick="toggleVideoMute(this)">
                    <i class="fa fa-video"></i>
                </button>
            </div>
        </div>
    </section>
</body>

</html>

{{-- <script src="https://download.agora.io/sdk/release/AgoraRTC_N.js"></script> --}}
<script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.23.1.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/agora-extension-vad/index.js"></script> --}}

<script>
    // const extension = new VAD.VADExtension({
    //     assetsPath: "./agora-extension-vad/wasm", // must contain vad.wasm + vad.worklet.bundle.min.js
    //     fetchOptions: {
    //         cache: "no-cache"
    //     },
    // });

    let localAudioTrack;
    let localVideoTrack;
    let localTracks = {
        audioTrack: null,
        videoTrack: null
    };
    let appID = '{{ config('services.agora.app_id') }}';
    AgoraRTC.setLogLevel(0);
    // AgoraRTC.registerExtensions([extension]);
    const channelName = '{{ $channel }}';
    // const token = null;
    const token = '{{ $token }}';
    const uid = Number('{{ $uid }}');

    let micIcon = document.getElementById('mic-icon');
    let bodyTest = document.getElementById('connect-text');
    let videoContainerEl = document.getElementById("video-container");

    startCall();

    async function startCall() {
        const client = AgoraRTC.createClient({
            mode: "rtc",
            codec: "vp8"
        });

        await client.join(appID, channelName, token, uid).then(() => {
            // console.log("Joined channel successfully");
            // document.getElementById('connect-text').style.display = 'none';
            // document.getElementById('btn-list').style.display = 'block';
        }).catch((error) => {
            console.error("Error joining channel:", error);
        });

        [localAudioTrack, localVideoTrack] = await AgoraRTC.createMicrophoneAndCameraTracks();

        const localPlayerContainer = document.createElement("div");
        localPlayerContainer.id = uid;
        localPlayerContainer.style.width = "100%";
        localPlayerContainer.style.height = "200px";
        document.getElementById("my-video").appendChild(localPlayerContainer);

        localVideoTrack.play(localPlayerContainer);

        await client.publish([localAudioTrack, localVideoTrack]);
        // console.log("Published local stream successfully");

        client.remoteUsers.forEach(async (user) => {
            if (user.hasVideo || user.hasAudio) {
                await client.subscribe(user, "video");
                const remotePlayerContainer = document.createElement("div");
                remotePlayerContainer.id = user.uid;
                remotePlayerContainer.style.width = "100%";
                remotePlayerContainer.style.height = "100vh";
                remotePlayerContainer.style.objectFit = "cover";
                videoContainerEl.appendChild(remotePlayerContainer);
                bodyTest.style.display = 'none';
                user.videoTrack?.play(remotePlayerContainer);
                user.audioTrack?.play();
            }
        });

        client.on("user-published", async (user, mediaType) => {
            await client.subscribe(user, mediaType);
            bodyTest.style.display = 'none';
            if (mediaType === "video") {
                const remotePlayerContainer = document.createElement("div");
                remotePlayerContainer.id = user.uid;
                remotePlayerContainer.style.width = "100%";
                remotePlayerContainer.style.height = "100vh";
                remotePlayerContainer.style.objectFit = 'cover';
                videoContainerEl.appendChild(remotePlayerContainer);
                user.videoTrack.play(remotePlayerContainer);
            }

            if (mediaType === "audio") {
                user.audioTrack.play();
                micIcon.classList.add('fa-microphone');
                micIcon.classList.remove('fa-microphone-slash');
            }
        });

        client.on("user-unpublished", (user, mediaType) => {
            if (mediaType === "video") {
                document.getElementById(user.uid).remove();
            } else {
                micIcon.classList.remove('fa-microphone');
                micIcon.classList.add('fa-microphone-slash');
            }
        });

        client.on("user-left", user => {
            disconnect();
            document.getElementById('endCallForm').submit();
        });
    }

    function disconnect() {
        bodyTest.style.display = 'block';
        bodyTest.innerHTML = "Call Ended.....";
    }

    function toggleAudioMute(button) {
        const icon = button.querySelector('i');
        if (localAudioTrack.muted) {
            localAudioTrack.setMuted(false); // Unmute
            icon.classList.remove('fa-microphone-slash');
            icon.classList.add('fa-microphone');
        } else {
            localAudioTrack.setMuted(true); // Mute
            icon.classList.add('fa-microphone-slash');
            icon.classList.remove('fa-microphone');
        }
    }

    function toggleVideoMute(button) {
        const icon = button.querySelector('i');
        if (localVideoTrack.enabled) {
            localVideoTrack.setEnabled(false); // Turn video ON
            icon.classList.add('fa-video-slash');
            icon.classList.remove('fa-video');
        } else {
            localVideoTrack.setEnabled(true); // Turn video OFF
            icon.classList.remove('fa-video-slash');
            icon.classList.add('fa-video');
        }
    }
</script>
