@extends('layouts.header-dashboard')

@section('content')
    <style>
        body {
            background-color: #f4f7f6;
            margin-top: 20px;
        }

        .card {
            background: #fff;
            transition: .5s;
            border: 0;
            margin-bottom: 30px;
            border-radius: .55rem;
            position: relative;
            width: 100%;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 10%);
        }

        .chat-app .people-list {
            width: 280px;
            position: absolute;
            left: 0;
            top: 0;
            padding: 20px;
            z-index: 7
        }

        .chat-app .chat {
            margin-left: 280px;
            border-left: 1px solid #eaeaea
        }

        .people-list {
            -moz-transition: .5s;
            -o-transition: .5s;
            -webkit-transition: .5s;
            transition: .5s
        }

        .people-list .chat-list li {
            padding: 10px 15px;
            list-style: none;
            border-radius: 3px
        }

        .people-list .chat-list li:hover {
            background: #efefef;
            cursor: pointer
        }

        .people-list .chat-list li.active {
            background: #efefef
        }

        .people-list .chat-list li .name {
            font-size: 15px
        }

        .people-list .chat-list img {
            width: 45px;
            height: 45px;
            border-radius: 50%
        }

        .people-list img {
            float: left;
            border-radius: 50%
        }

        .people-list .about {
            float: left;
            padding-left: 8px
        }

        .people-list .status {
            color: #999;
            font-size: 13px
        }

        .chat .chat-header {
            padding: 15px 20px;
            border-bottom: 2px solid #f4f7f6
        }

        .chat .chat-header img {
            float: left;
            border-radius: 40px;
            width: 40px
        }

        .chat .chat-header .chat-about {
            float: left;
            padding-left: 10px
        }

        .chat .chat-history {
            padding: 20px;
            border-bottom: 2px solid #fff
        }

        .chat .chat-history ul {
            padding: 0
        }

        .chat .chat-history ul li {
            list-style: none;
            margin-bottom: 30px
        }

        .chat .chat-history ul li:last-child {
            margin-bottom: 0px
        }

        .chat .chat-history .message-data {
            margin-bottom: 15px
        }

        .chat .chat-history .message-data img {
            border-radius: 40px;
            width: 40px
        }

        .chat .chat-history .message-data-time {
            color: #434651;
            padding-left: 6px
        }

        .chat .chat-history .message {
            color: #444;
            padding: 18px 20px;
            line-height: 26px;
            font-size: 16px;
            border-radius: 7px;
            display: inline-block;
            position: relative
        }

        .chat .chat-history .message:after {
            bottom: 100%;
            left: 7%;
            border: solid transparent;
            content: " ";
            height: 0;
            width: 0;
            position: absolute;
            pointer-events: none;
            border-bottom-color: #fff;
            border-width: 10px;
            margin-left: -10px
        }

        .chat .chat-history .my-message {
            background: #efefef
        }

        .chat .chat-history .my-message:after {
            bottom: 100%;
            left: 30px;
            border: solid transparent;
            content: " ";
            height: 0;
            width: 0;
            position: absolute;
            pointer-events: none;
            border-bottom-color: #efefef;
            border-width: 10px;
            margin-left: -10px
        }

        .chat .chat-history .other-message {
            background: #e8f1f3;
            text-align: right
        }

        .chat .chat-history .other-message:after {
            border-bottom-color: #e8f1f3;
            left: 93%
        }

        .chat .chat-message {
            padding: 20px
        }

        .online,
        .offline,
        .me {
            margin-right: 2px;
            font-size: 8px;
            vertical-align: middle
        }

        .online {
            color: #86c541
        }

        .offline {
            color: #e47297
        }

        .me {
            color: #1d8ecd
        }

        .float-right {
            float: right
        }

        .clearfix:after {
            visibility: hidden;
            display: block;
            font-size: 0;
            content: " ";
            clear: both;
            height: 0
        }

        @media only screen and (max-width: 767px) {
            .chat-app .people-list {
                height: 465px;
                width: 100%;
                overflow-x: auto;
                background: #fff;
                left: -400px;
                display: none
            }

            .chat-app .people-list.open {
                left: 0
            }

            .chat-app .chat {
                margin: 0
            }

            .chat-app .chat .chat-header {
                border-radius: 0.55rem 0.55rem 0 0
            }

            .chat-app .chat-history {
                height: 300px;
                overflow-x: auto
            }
        }

        @media only screen and (min-width: 768px) and (max-width: 992px) {
            .chat-app .chat-list {
                height: 650px;
                overflow-x: auto
            }

            .chat-app .chat-history {
                height: 600px;
                overflow-x: auto
            }
        }

        @media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: landscape) and (-webkit-min-device-pixel-ratio: 1) {
            .chat-app .chat-list {
                height: 480px;
                overflow-x: auto
            }

            .chat-app .chat-history {
                height: calc(100vh - 350px);
                overflow-x: auto
            }
        }

        .clearfix {
            position: relative;
            /* Ensure proper positioning of the delete icon */
            padding-right: 40px;
            /* Leave space for the delete icon */
        }

        .delete-icon {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #dc3545;
            /* Red color for delete icon */
            cursor: pointer;
            font-size: 16px;
        }

        .delete-icon:hover {
            color: #a71d2a;
            /* Darker red on hover */
        }

        @media (max-width: 600px) {
            .name .last-name {
                display: none;
            }
        }
    </style>
    <section class="d1-bg landing-body h-100">
        <div class="container py-4 py-lg-5">
            <div class="form-card p-4">
                <div class="header-bar position-relative mb-4">
                    <h3 class="headingH2">Messages</h3>
                </div>

                <div class="container">
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="people-list" id="plist">
                                <ul class="list-unstyled chat-list mt-2 mb-0">
                                    @forelse ($chat_list  as $list)
                                        @if ($list->last_message)
                                            <li class="clearfix">
                                                <a href="{{ route('chat.profile', $list->participant->id) }}" style="text-decoration:unset;display: flex; flex: 1;">
                                                    <img alt="avatar" src="{{ asset('storage/' . $list->participant->avatar) }}">

                                                    <div class="about">
                                                        <div class="name">{{ $list->participant->name }}&nbsp;

                                                            <span class="last-name">{{ $list->participant->last_name }}</span>
                                                        </div>
                                                        <div class="status">
                                                            @if (filter_var($list->last_message, FILTER_VALIDATE_URL))
                                                                {{ 'Image' }}
                                                            @else
                                                                {{ \Illuminate\Support\Str::words($list->last_message, 3) }}
                                                            @endif

                                                            @if ($list->unread_message != 0 && $list->receiver_id == Auth::user()->id)
                                                                <span class="badge bg-primary">{{ $list->unread_message }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </a>

                                                <!-- Delete icon -->
                                                <a class="delete-icon" href="{{ route('chat.delete.room', ['id' => $list->id]) }}">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </li>
                                        @endif
                                    @empty
                                        <div class="alert alert-success">No chat</div>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js"></script>
<script>
    const firebaseConfig = {
        apiKey: "AIzaSyBZ3sLIrHiClZAhIuSzoDnVEyuYBUz7G6U",
        authDomain: "milfdilf-8fb27.firebaseapp.com",
        projectId: "milfdilf-8fb27",
        storageBucket: "milfdilf-8fb27.appspot.com",
        messagingSenderId: "351573631096",
        appId: "1:351573631096:web:ac07ceb838cb3b71924d8d",
        measurementId: "G-ZY06J8R56Q"
    };

    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    //    const analytics = getAnalytics(app);
</script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const messaging = firebase.messaging();
    messaging.usePublicVapidKey(
        'BBonvqGs-HTBBT2gogYMn0B-oTaBvbgvC9r2_U3WbAEJCdHCvuDJhZc3t49ZeClrH6eLduw0A129g993MOjDmS8');

    function sendTokenToServer(fcm_token) {
        const user_id = '{{ Auth::user()->id }}';

        axios.post('/save-token', {
            fcm_token,
            user_id
        }).then(res => {
            // console.log(res);
        });
    }

    function retrieveToken() {
        messaging.getToken().then((currentToken) => {
            if (currentToken) {
                // console.log('Token received:' + currentToken);
                sendTokenToServer(currentToken);
                // updateUIFirPushEnabled(currentToken);
            } else {
                alert("you should allow notification");
                // Show permission request UI
                // console.log('No registration token available. Request permission to generate one.');
                // updateUIForPushPermissionRequired();
                // sendTokenToServer(false);
            }
        }).catch((err) => {
            console.log('An error occurred while retrieving token. ', err);
            //  showToken('Error', err);
            //  setTokenSentToServer(false);
        });
    }

    retrieveToken();
    messaging.onTokenRefresh(() => {
        retrieveToken();
    });


    messaging.onMessage((payload) => {
        console.log('Message recieved.');
        // console.log(payload);

        location.reload();

    });
</script>
