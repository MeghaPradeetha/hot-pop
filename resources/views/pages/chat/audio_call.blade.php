@extends('layouts.header-dashboard')
@section('content')
<section class="row position-relative" style="background-color: rgba(110,56,226,0.16); height:100vh;">

    <div class="col-sm-6 border border-white position-relative" style="display:grid; place-items:center; ">
        <div class="d-flex justify-content-end pt-2 position-absolute" style="top: 10px; right:10px;">
            <div class="btn rounded-circle d1-bg" style="font-size:25px;width: 60px;aspect-ratio: 1/1;display: grid;place-items: center;">
                <i class="fa fa-microphone" id="caller-mic-icon"></i>
            </div>
        </div>
        <div>
            <div>
                <img
                    alt="profile_picture"
                    class="rounded-circle mb-2"
                    src="{{ asset('storage/' . $caller->avatar) }}"
                    style="width:300px;height:300px"
                >
                <p class="text-center">{{ $caller->name }}</p>
            </div>
        </div>
    </div>

    <div class="col-sm-6 border border-white position-relative" style="display:grid; place-items:center;">
        <div class="d-flex justify-content-end pt-2 position-absolute" style="top: 10px; right:10px;">
            <div class="btn rounded-circle d1-bg" style="font-size:25px;width: 60px;aspect-ratio: 1/1;display: grid;place-items: center;">
                <i class="fa fa-microphone" id="my-mic-icon"></i>
            </div>
        </div>
        <div>
            <div>
                <img
                    alt="profile_picture"
                    class="rounded-circle mb-2"
                    src="{{ asset('storage/' . $user->avatar) }}"
                    style="width:300px;height:300px"
                >
                <div class="text-center">{{ 'you' }}</div>
            </div>
        </div>
    </div>

    <div class="text-center position-absolute" style="bottom: 20px;">
        <button class="btn btn-secondary rounded-circle btn-block call-btn" onclick="toggleAudioMute(this);">
            <i class="fa fa-microphone"></i>
        </button>

        <div class="d-inline-block" style="margin-left:3%">
            <form action="{{ route('video.end-calling') }}" id="endCallForm" method="POST">
                @csrf
                <input
                    id="user_id"
                    name="user_id"
                    type="hidden"
                    value="{{ $caller->id }}"
                >
                <button class="btn btn-danger rounded-circle call-btn" id="end-call-button">
                    <i class="fa fa-phone"></i>
                </button>
            </form>
        </div>
    </div>
</section>
@endsection

@push('js')
<script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.23.1.js"></script>

<script>
    let localAudioTrack;
    let localVideoTrack;
    let appID = '{{ config('services.agora.app_id') }}';
    AgoraRTC.setLogLevel(0);
    channelName = '{{ $channel }}';
    const token = '{{ $token }}';
    const uid = Number('{{ $uid }}');

    let micIcon = document.getElementById('caller-mic-icon');
    let myMicIcon = document.getElementById('my-mic-icon');
    let bodyTest = document.getElementById('connect-text');
    let videoContainerEl = document.getElementById("video-container");

    startCall();

    async function startCall() {
        const client = AgoraRTC.createClient({
            mode: "rtc",
            codec: "vp8"
        });

        await client.join(appID, channelName, token, uid).then(() => {
            console.log("Joined channel successfully");
            // document.getElementById('connect-text').style.display = 'none';
            // document.getElementById('btn-list').style.display = 'block';
        }).catch((error) => {
            console.error("Error joining channel:", error);
        });

        localAudioTrack = await AgoraRTC.createMicrophoneAudioTrack();

        await client.publish([localAudioTrack]);
        // console.log("Published local stream successfully");

        client.remoteUsers.forEach(async (user) => {
            console.log('remote user', user);
            if (user.hasVideo || user.hasAudio) {
                console.log('remote user2', user);
                await client.subscribe(user, "audio");
                user.audioTrack?.play();
            }
        });

        client.on("user-published", async (user, mediaType) => {
            await client.subscribe(user, mediaType);
            console.log('user published', user, mediaType);
            if (mediaType === "audio") {
                user.audioTrack.play();
                micIcon.classList.add('fa-microphone');
                micIcon.classList.remove('fa-microphone-slash');
            }
        });

        client.on("user-unpublished", (user, mediaType) => {
            console.log('user un publish', user, mediaType);
                micIcon.classList.remove('fa-microphone');
                micIcon.classList.add('fa-microphone-slash');
            
        });

        client.on("user-left", user => {
            console.log('user left')
            document.getElementById('endCallForm').submit();
        });
    }

    function toggleAudioMute(button) {
        const icon = button.querySelector('i');
        if (localAudioTrack.muted) {
            console.log('un muted')
            localAudioTrack.setMuted(false); // Unmute
            icon.classList.remove('fa-microphone-slash');
            icon.classList.add('fa-microphone');

            myMicIcon.classList.remove('fa-microphone-slash');
            myMicIcon.classList.add('fa-microphone');
        } else {
            console.log('muted')
            localAudioTrack.setMuted(true); // Mute
            icon.classList.add('fa-microphone-slash');
            icon.classList.remove('fa-microphone');

            myMicIcon.classList.add('fa-microphone-slash');
            myMicIcon.classList.remove('fa-microphone');
        }
    }
</script>
@endpush