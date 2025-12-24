@extends('layouts.header-dashboard')
@php
    use Carbon\Carbon;
@endphp
@section('content')
    <section class="d1-bg landing-body h-100">
        <style>
            body {
                background-color: #f4f7f6;
                margin-top: 20px;
            }

            a {
                color: #050605;
            }

            .block-dialog {
                max-width: 40%;
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
                width: 350px;
                height: 100%;
                overflow: scroll;
                position: absolute;
                left: 0;
                top: 0;
                padding: 20px;
                z-index: 7
            }

            .chat-app .chat {
                margin-left: 350px;
                border-left: 1px solid #eaeaea
            }

            .people-list {
                -moz-transition: .5s;
                -o-transition: .5s;
                -webkit-transition: .5s;
                transition: .5s;
                max-height: 500px;
                /* Set the maximum height you desire */
                overflow-y: auto;
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
                border: 1px solid #F4922D;
                border-radius: 15px;
                background: transparent;
            }

            .people-list .chat-list li .name {
                font-size: 15px
            }

            .people-list .chat-list img {
                width: 45px;
                border-radius: 50%
            }

            .people-list img {
                float: left;
                border-radius: 50%
            }

            .people-list .about {
                float: right;
                padding-left: 8px;
                font-size: 12px;
            }

            .people-list .status {
                color: #999;
                font-size: 13px
            }

            .chat .chat-header {
                padding: 15px 20px;
                border-bottom: 2px solid #d4eae2
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
                border-bottom: 2px solid #efe5e5;
                max-height: 500px;
                min-height: 300px;
                /* Set the maximum height you desire */
                overflow-y: auto;
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
                padding: 15px 20px;
                line-height: 22px;
                font-size: 16px;
                border-radius: 7px;
                display: inline-block;
                /* position: relative */
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
                border-radius: 14px;
                background-color: rgba(110, 58, 227, 0.21);
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
                /* text-align: right */
            }

            .chat .chat-history .other-message:after {
                border-bottom-color: #e8f1f3;
                left: 93%
            }

            .chat .chat-message {
                padding: 20px
            }

            .chat_date {
                font-size: 14px;
                text-align: center;
                color: #999999;
                background-color: white;
                position: sticky;
                top: -20px;
            }

            .online,
            .offline,
            .me {
                margin-right: 2px;
                font-size: 17px;
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

                .block-dialog {
                    max-width: 100%;
                }
            }

            @media only screen and (min-width: 768px) and (max-width: 992px) {
                .chat-app .chat-list {
                    height: 650px;
                    overflow-x: auto
                }

                .block-dialog {
                    max-width: 80%;
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

            .btn-send {
                border: none;
                color: #fff;
                height: 49px;
                width: 55px;
                border-radius: 0 13px 13px 0;
                background-color: #F4922D;
            }

            .chat-field {
                border-top-left-radius: 13px !important;
                border-bottom-left-radius: 13px !important;
                border: none;
                background-color: #F3F3F5;
            }

            .search-group:focus-within {
                box-shadow: 0 0 1px 1px gray;
            }

            #form_search:focus {
                box-shadow: none;
            }

            .search-group {
                border: 1px solid #ededee;
                border-radius: 15px;
                overflow: hidden;
                color: #d0d0d0;
            }

            /* Container for chat messages */
            /* Container for chat messages */
            .chat-container {
                display: flex;
                justify-content: space-between;
                /* Message and button stay in one row */
                align-items: center;
                /* Vertically align message and button */
                padding: 0;
                /* Remove extra padding */
                margin: 0;
                /* Remove extra margin */
                gap: 0;
                /* Remove any gap */
            }

            /* Message styling */
            .message {
                max-width: 100%;
                /* Limit message width */
                padding: 100px 15px;
                /* Consistent padding */
                border-radius: 10px;
                /* Rounded corners */
                font-size: 14px;
                /* Consistent font size */
                line-height: 1.5;
                /* Improve text readability */
                background-color: #e8dff8;
                /* Light purple background */
                color: #333;
                /* Dark text for readability */
                text-align: left;
                /* Align text to the left */
                word-wrap: break-word;
                /* Break long words */
                margin: 0;
                /* Remove margin */
            }

            /* Dropdown button */
            .btn-group.message-options {
                flex-shrink: 0;
                /* Prevent button from shrinking */
                margin: 0;
                /* Remove margin */
                padding: 0;
                /* Remove padding */
            }

            .btn-group .dropdown-toggle {
                padding: 5px 8px;
                /* Adjust button size */
                font-size: 14px;
                white-space: nowrap;
                /* Prevent text wrapping */
            }

            /* Dropdown menu */
            .dropdown-menu {
                min-width: 150px;
                border-radius: 8px;
                font-size: 14px;
            }

            .dropdown-menu .dropdown-item {
                padding: 8px 15px;
                font-size: 14px;
            }

            /* Media query for smaller screens */
            @media (max-width: 276px) {
                .chat-container {
                    flex-direction: column;
                    /* Stack elements vertically on mobile */
                    align-items: flex-start;
                }

                .message {
                    max-width: 100%;
                    /* Use full width for messages */
                }

                .btn-group.message-options {
                    margin-top: 5px;
                    /* Add space above dropdown */
                }

                .btn.dropdown-toggle {
                    font-size: 13px;
                    /* Reduce button size for smaller screens */
                }

                .dropdown-menu {
                    font-size: 13px;
                    /* Adjust font size in dropdown */
                }
            }
        </style>

        <div class="container py-4 py-lg-5">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card chat-app">
                        <div class="people-list" id="plist">
                            <div class="input-group mb-3 search-group">
                                <div class="btn my-auto d2"><i class="fa fa-search"></i></div>
                                <input
                                    class="form-control border-0 my-1 f-16"
                                    id="form_search"
                                    oninput="search();"
                                    placeholder="Search your matches here"
                                >
                            </div>

                            <ul class="list-unstyled chat-list mt-2 mb-0">
                                <div id="chatList">
                                    @if (!isset($message_list))
                                        <li class="clearfix active">
                                            <a href="{{ route('chat.profile', $chat_user->id) }}" style="text-decoration: unset;">
                                                <img class="rounded-circle me-2" src="{{ asset('storage/' . $chat_user->avatar) }}"
                                                    style="width: 50px; height: 50px;"
                                                >
                                                <div class="name" style="font-size: 15px;">{{ $chat_user->full_name }}
                                                    <div class="about d2" id="about_{{ $chat_user->id }}"></div>
                                                </div>
                                                <div class="last_message_{{ $chat_user->id }} d2" style="font-size: 14px;">
                                                </div>
                                            </a>
                                        </li>
                                    @endif

                                    @forelse ($chat_list as $chatGroup)
                                        <li class="clearfix {{ $chatGroup->user->id == $chat_user->id ? 'active' : '' }}">
                                            <a href="{{ route('chat.profile', $chatGroup->user->id) }}" style="text-decoration: unset;">
                                                <img class="rounded-circle me-2" src="{{ asset('storage/' . $chatGroup->user->avatar) }}"
                                                    style="width: 50px; height: 50px;"
                                                >
                                                <div class="name" style="font-size: 15px;">
                                                    {{ $chatGroup->user->full_name }}
                                                    <div class="about d2" id="about_{{ $chatGroup->user->id }}">
                                                        {{ $chatGroup->last_message_time }}
                                                    </div>
                                                </div>
                                                <div class="last_message_{{ $chatGroup->user->id }} d2" style="font-size: 14px;">
                                                    {{ $chatGroup->sender_id == $user->id ? 'You' : $chatGroup->sender->name }}:
                                                    {{ $chatGroup->last_message }}

                                                    @if ($chatGroup->user->id != $chat_user->id && $chatGroup->unread_messages > 0 && $chatGroup->receiver_id == $user->id)
                                                        <span class="badge a1-bg float-right">{{ $chatGroup->unread_messages }}</span>
                                                    @endif
                                                </div>
                                            </a>
                                        </li>
                                    @empty
                                    @endforelse
                                </div>
                            </ul>
                        </div>

                        <div class="chat">
                            <div class="chat-header clearfix">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <a data-target="#view_info" data-toggle="modal" href="{{ url('/user-profile/' . $chat_user->id) }}">
                                            <img
                                                alt="avatar"
                                                class="rounded-circle"
                                                src="{{ asset('storage/' . $chat_user->avatar) }}"
                                                style="width:50px;height:50px"
                                            >
                                        </a>
                                        <div class="chat-about">
                                            <h6 class="m-b-0">{{ $chat_user->full_name }}</h6>
                                            <small class="" id="status">
                                                @if ($chat_user->online_status)
                                                    @if ($chat_user->active_status)
                                                        <span class="online1 text-success">Online</span>
                                                    @else
                                                        <span class="offline1 text-danger">LastSeen at :
                                                            {{ $chat_user->last_active_time }}</span>
                                                    @endif
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 hidden-sm text-end">
                                        <button
                                            class="btn btn-primary"
                                            data-bs-target="#exampleModal"
                                            data-bs-toggle="modal"
                                            onclick="call('audio')"
                                            type="button"
                                        >
                                            <i class="fa fa-phone"></i>
                                        </button>
                                        <button
                                            class="btn btn-outline-primary"
                                            data-bs-target="#exampleModal"
                                            data-bs-toggle="modal"
                                            onclick="call('video')"
                                            type="button"
                                        >
                                            <i class="fa fa-video"></i>
                                        </button>

                                        <div class="btn-group">
                                            <button
                                                aria-expanded="false"
                                                class="btn btn-light dropdown-toggle"
                                                data-bs-toggle="dropdown"
                                                type="button"
                                            >
                                                <i class="fa fa-ellipsis-v a1"></i>
                                            </button>
                                            <ul class="dropdown-menu" style="border-radius: 12px;">
                                                <button
                                                    class="dropdown-item text-danger"
                                                    data-bs-target="#chatBlockModel"
                                                    data-bs-toggle="modal"
                                                    style="font-size: 15px;margin-bottom:unset;border-bottom:1px solid #e0e0e0;"
                                                    type="button"
                                                >Unmatch & Block</button>

                                                <li><a class="dropdown-item"
                                                        href="{{ route('chat.report.page', ['id' => $chat_user->id]) }}">Report</button></a>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="chat-history" id="chat-history">
                                <ul class="m-b-0">
                                    @if (!isset($message_list))
                                        <div class="alert alert-success" id="no-chat-msg">No chats</div>
                                    @else
                                        @foreach ($message_list as $date => $chats)
                                            <div class="chat_date">{{ $date == date('d / M / Y') ? 'Today' : $date }}</div>
                                            @foreach ($chats as $chat)
                                                @if ($chat->sender_id == Auth::user()->id)
                                                    @php
                                                        $colorClass = 'd4';
                                                        if ($chat_user->read_receipts == '1') {
                                                            $colorClass = $chat->read_status == '1' ? 'c2' : 'd2';
                                                        }
                                                    @endphp

                                                    <li class="clearfix">

                                                        <div class="float-end text-end">
                                                            @if (filter_var($chat->message, FILTER_VALIDATE_URL))
                                                                <div class=""> <img src="{{ $chat->message }}" style="width: 20%;"></div>
                                                            @else
                                                                <div class="chat-container">
                                                                    <!-- Message -->
                                                                    @if ($chat->deleted_at != null)
                                                                        <div class="message other-message"
                                                                            style="background-color: #dedfe3;font-style: italic;"
                                                                        >
                                                                            <i class="fa fa-ban"></i>You deleted this message
                                                                        </div>
                                                                    @else
                                                                        <div class="message my-message">
                                                                            {{ $chat->message }}
                                                                        </div>
                                                                    @endif

                                                                    <!-- Dropdown -->
                                                                    <div class="btn-group message-options">
                                                                        @if ($chat->deleted_at == null)
                                                                            <button
                                                                                aria-expanded="false"
                                                                                class="btn dropdown-toggle"
                                                                                data-bs-toggle="dropdown"
                                                                                type="button"
                                                                            >
                                                                                <i class="fa fa-ellipsis-v"></i>
                                                                            </button>
                                                                            <ul class="dropdown-menu">
                                                                                <li>
                                                                                    <a class="dropdown-item text-danger"
                                                                                        href="{{ route('chat.delete.me', ['id' => $chat->id]) }}"
                                                                                    >
                                                                                        Delete For me
                                                                                    </a>
                                                                                </li>
                                                                                @if (!Carbon::parse($chat->created_at)->lt(Carbon::now()->subHours(24)))
                                                                                    <li>
                                                                                        <a class="dropdown-item"
                                                                                            href="{{ route('chat.delete.all', ['id' => $chat->id]) }}"
                                                                                        >
                                                                                            Delete for Everyone
                                                                                        </a>
                                                                                    </li>
                                                                                @endif
                                                                            </ul>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            <div class="d-flex align-items-center justify-content-end">
                                                                @if ($chat_user->read_receipts == '1')
                                                                    <i class="fa-solid fa-check untick {{ $colorClass }}"
                                                                        style="font-size:12px;float:right;margin-right:-6px;"
                                                                    ></i>
                                                                @endif
                                                                <i class="fa-solid fa-check me-2 untick {{ $colorClass }}"
                                                                    style="font-size:12px;float:right;"
                                                                ></i>

                                                                <span class="d2" style="font-size:10px;float:right">
                                                                    {{ $chat->send_time }} </span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="clearfix">
                                                        @if (filter_var($chat->message, FILTER_VALIDATE_URL))
                                                            <div> <img src="{{ $chat->message }}" style="width: 20%;">
                                                            </div><br>
                                                        @else
                                                            @if ($chat->deleted_at != null)
                                                                <div class="message other-message"
                                                                    style="background-color: #dedfe3;font-style: italic;">
                                                                    <i class="fa fa-ban"></i>This message was deleted
                                                                </div>
                                                            @else
                                                                <div class="message other-message">{{ $chat->message }}
                                                                </div>
                                                            @endif
                                                        @endif

                                                        </i>&nbsp;<span class="d2" style="font-size:10px">{{ $chat->send_time }}</span>
                                                    </li>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endif
                                    <div id="ajax"></div>
                                </ul>
                            </div>

                            <div class="alert alert-danger my-2 text-center" id="block-message" style="display: {{ $blocked_status ? '' : 'none' }}">
                                @if ($block?->blocked_by_user == $user->id)
                                    <div>You have blocked the user and cannot send messages further</div>
                                @else
                                    <div>You have been blocked by the other user and cannot send messages further</div>
                                @endif
                            </div>

                            @if ($chat_user->deleted_at == null)
                                <div class="chat-message clearfix" id="form_area" style="display: {{ $blocked_status ? 'none' : '' }}">
                                    <form enctype="multipart/form-data" id="send_form">
                                        @csrf
                                        <div class="input-group">
                                            <input name="receiver_id" type="hidden" value="{{ $chat_user->id }}">
                                            <input
                                                autocomplete="off"
                                                class="form-control chat-field"
                                                id="message"
                                                name="message"
                                                onkeypress="submitWithEnter(event)"
                                                placeholder="message"
                                                type="text"
                                            >
                                            <input
                                                id="imgupload"
                                                name="upload"
                                                oninput="valueData();"
                                                style="display:none"
                                                type="file"
                                            />
                                            <button
                                                class="btn btn-secondary"
                                                id="OpenImgUpload"
                                                onclick="fileUpload();"
                                                type="button"
                                            ><i class="fa-solid fa-image"></i></button>

                                            <button
                                                class="btn-send"
                                                id="send_button"
                                                onclick="saveData();"
                                                type="button"
                                            ><i class="fa-solid fa-paper-plane"></i></button>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <div class="alert alert-danger my-2 text-center">
                                    <div>User Deleted his Account and cannot send messages further</div>

                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Chat Box Goes Here -->

        <div
            aria-hidden="true"
            aria-labelledby="chatBlockModelLabel"
            class="modal fade"
            id="chatBlockModel"
            tabindex="-1"
        >
            <div class="modal-dialog block-dialog">
                <div class="modal-content" style="padding: 50px;">
                    <div class="modal-body text-center">
                        <h4 class="modal-title mb-4">Unmatch & Block</h4>

                        <i class="fa-solid fa-ban" style="font-size: 50px;color: red;"></i>
                        <p class="my-4 f-16">Are you sure you want to block and unmatch {{ $chat_user->full_name }} ?</p>

                        <div class="d-xxl-flex d-block justify-content-between">
                            <div>
                                <button class="small-button mb-1" data-bs-dismiss="modal" type="button">No</button>
                            </div>
                            <form action="{{ route('block.user') }}" method="POST">
                                @csrf
                                <input name="blocked_user" type="hidden" value="{{ $chat_user->id }}">
                                <button class="small-button color" type="sumbit">Yes</button></a>
                                {{-- <button class="dropdown-item text-danger" style="font-size: 15px;margin-bottom:unset" type="submit">Unmatch & Block</button> --}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Call Popup Dialog --}}
        <div
            aria-hidden="true"
            aria-labelledby="exampleModalLabel"
            class="modal fade"
            data-bs-backdrop="static"
            data-bs-keyboard="false"
            id="exampleModal"
            tabindex="-1"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header justify-content-center">
                        <img class="rounded-circle me-2" src="{{ asset('storage/' . $chat_user->avatar) }}" style="width: 80px; height: 80px;">
                    </div>
                    <div class="modal-body text-center">
                        <div id="call_model_body">
                            <div>Calling .........</div>
                            <h5 class="modal-title">{{ $chat_user->full_name }}</h5>
                        </div>
                        <div id="call_model_body_offline" style="display: none;">
                            Connection Problem <br /> Try Again
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button
                            class="btn btn-danger rounded-circle btn-lg"
                            data-bs-dismiss="modal"
                            onclick="updateResponce(false, {{ $chat_user->id }} , callEnd);"
                            style="width: 70px; height:70px;"
                        >
                            <i class="fa fa-phone-flip"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    /**
     *     Global Variables
     **/
    callType = 'audio';
    authID = '{{ auth()->id() }}';
    let current_user = @json($chat_user);

    document.addEventListener('DOMContentLoaded', function() {
        updateChatBarPosition();
    });


    /**
     *  Pusher Events
     **/

    Pusher.logToConsole = false;
    var pusher = new Pusher("{{ config('app.PusherAppKey') }}", {
        cluster: 'ap4'
    });

    var channel = pusher.subscribe('channel-new-message');
    channel.bind('new-chat-message', function(data) {
        // console.log('new-chat ', data)
        if (data.receiverId == authID)
            newMessageTrigger(data.senderId, data.chatRoomId);
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('sign-in', function(data) {
        // console.log('sign-in')
        activeUser(data.userId, data.active_status);
    });

    var channel = pusher.subscribe('my-channel1');
    channel.bind('sign-out', function(data) {
        offlineUser(data.userId, data.active_status, data.last_active);
    });

    var channel = pusher.subscribe('my-channel2');
    channel.bind('message-read', function(data) {
        // console.log('read-msg')
        readStatus(data.receiverId, data.senderId, data.readStatus);
    });

    var channel = pusher.subscribe('my-channel3');
    channel.bind('block-user', function(data) {
        blockUser(data.blockedUser, data.blockByUser);
    });


    /**
     *  Pusher Helper Functions
     **/
    function activeUser(UserId, activeStatus) {
        if (current_user.id == UserId && current_user.online_status == 1) {
            var element = document.getElementById('status');
            element.style.color = "green";
            element.innerHTML = 'Online';
        }
        current_user.active_status = 1;
    }

    function offlineUser(userId, activeStatus, lastActive) {
        if (current_user.id == userId && current_user.online_status == 1) {
            var element = document.getElementById('status');
            element.style.color = "red";
            element.innerHTML = "Lastseen at: " + lastActive;
        }
        current_user.active_status = 0;
    }

    function blockUser(BlockedUser, BlockedByUser) {
        if (current_user.id == BlockedByUser && BlockedUser == authID) {
            $('#form_area').hide();
            $('#block-message').show();
        }
    }

    function readStatus(receiverId, senderId, readStatus) {
        var elements = document.querySelectorAll('.untick');

        if (readStatus == "1" && current_user.id == senderId && authID == receiverId) {
            var color = current_user.read_receipts == 1 ? 'green' : 'grey';

            for (var i = 0; i < elements.length; i++) {
                elements[i].style.color = color;
            }
        }
    }

    /**
     *  Other Functions
     **/
    function updateChatBarPosition() {
        var chatHistory = document.getElementById("chat-history");
        chatHistory.scrollTop = chatHistory.scrollHeight;
    }

    //press enter to send message
    function submitWithEnter(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            saveData();
        }
    }

    function displayChatList(chatList) {
        chatList.forEach(list => {
            updateChatList(list);
        })
    }

    function updateChatList(list) {
        if (list.sender_id && list.sender_id != authID) {
            var name = list.sender.full_name;
            var id = list.sender_id;
            var image = list.sender.avatar;
        } else if (list.receiver_id && list.receiver_id != authID) {
            var name = list.receiver.full_name;
            var id = list.receiver_id;
            var image = list.receiver.avatar;
        } else {
            var name = list.full_name;
            var id = list.id;
            var image = list.avatar;
        }

        var element = document.createElement('li');
        element.classList.add('clearfix');
        if (id == current_user.id)
            element.classList.add('active');

        // Create Link
        var element1 = document.createElement('a');
        var url = '{{ route('chat.profile', ':id') }}';
        url = url.replace(':id', id);
        element1.setAttribute('href', url);
        element1.style.textDecoration = "unset";

        // Add User Image
        var element2 = document.createElement('img');
        element2.classList.add('rounded-circle');
        element2.style.width = '50px'; // You can adjust the size as needed
        element2.style.height = '50px';
        element2.setAttribute('src', image ?? "https://bootdey.com/img/Content/avatar/avatar1.png");
        element2.classList.add('me-2');

        // Add User Name
        var element3 = document.createElement('div');
        element3.classList.add('name');
        element3.style.fontSize = "15px";
        element3.innerHTML = name;

        // last chat time
        var element4 = document.createElement('div');
        element4.classList.add('about', 'd2');
        element4.id = 'about_' + id;
        element4.innerHTML = list.last_message_time;

        // Add Last Chat
        var sender_name = list.sender_id == authID ? "You" : list.sender.name;
        sender_name += ": ";
        var element5 = document.createElement('div');
        element5.classList.add('last_message_' + id);
        element5.classList.add('d2');
        element5.style.fontSize = 14;
        if (isUrl(list.last_message)) {
            element5.innerHTML = sender_name + "Image";
        } else {
            if (list.last_message.length > 10) {
                list.last_message = list.last_message.substring(0, 10) + '...';
            }
            element5.innerHTML = sender_name + list.last_message;
        }

        // Add Unread Chat Count
        if (list.unread_messages > 0 && list.receiver_id == authID) {
            var element6 = document.createElement('span');
            element6.classList.add('badge', 'a1-bg', 'float-right');
            element6.innerHTML = list.unread_messages;
            element5.appendChild(element6);
        }

        var parent = document.getElementById('chatList');
        // .appendChild(element);
        parent.insertBefore(element, parent.firstChild);
        element.appendChild(element1);
        element1.appendChild(element2);
        element1.appendChild(element3);
        element3.appendChild(element4);
        element1.appendChild(element5);
    }



    function displaySendMessage(chats, time) {

        var noChatMsg = document.getElementById('no-chat-msg');
        if (noChatMsg) {
            noChatMsg.style.display = 'none';
        }

        var element1 = document.createElement('li');
        element1.classList.add('clearfix');
        document.getElementById('ajax').appendChild(element1);

        var element2 = document.createElement('div');
        element2.classList.add('float-end');
        element2.classList.add('text-end');
        element1.appendChild(element2);

        // Filter Image
        if (isUrl(chats)) {
            var element3 = document.createElement('img');
            element3.style.width = "20%";
            element3.setAttribute('src', chats);
            element3.style.fontSize = '10px';
        } else {
            var element3 = document.createElement('div');
            element3.classList.add('my-message');
            element3.classList.add('message');
            element3.innerHTML = chats;
        }
        element2.appendChild(element3);

        var element4 = document.createElement('div');
        element4.classList.add('justify-content-end');
        element4.classList.add('d-flex');
        element4.classList.add('align-items-center');
        element2.appendChild(element4);

        var checkIconElement1 = document.createElement('i');
        checkIconElement1.classList.add('fa-solid', 'd2', 'fa-check', 'untick');
        checkIconElement1.style.float = "right";
        checkIconElement1.style.fontSize = "12px";
        checkIconElement2 = checkIconElement1.cloneNode(true);

        checkIconElement1.classList.add('me-2');
        checkIconElement2.style.marginRight = "-6px";
        // checkIconElement1.setAttribute('id', "tick");
        if (current_user.read_receipts == '1')
            element4.appendChild(checkIconElement2);
        element4.appendChild(checkIconElement1);

        var timeElement = document.createElement('span');
        timeElement.classList.add('d2');
        timeElement.style.float = 'right';
        timeElement.style.fontSize = '10px';
        timeElement.innerHTML = time;
        element4.appendChild(timeElement);


        updateChatBarPosition();

    }

    // update sender chat list with last send message
    function displaySendMessageChatList(message) {
        // Update Sender
        element = document.querySelector(`.last_message_${current_user.id}`);
        aboutDiv = document.getElementById(`about_${current_user.id}`);
        if (element) {
            element.innerHTML = "You: " + message;
            aboutDiv.innerHTML = 'now';
        }
    }

    function displayLatestMessageChatList(chatList) {
        // console.log('displayLatestMessageChatList')
        // console.log(chatList)
        // Filter Images
        if (isUrl(chatList.last_message)) {
            var message = "Image";
        } else {
            var message = chatList.last_message;
            if (message.length > 10) {
                message = message.substring(0, 10) + '...';
            }
        }

        // Updare Receiver
        if (chatList.receiver_id == authID) {
            var element = document.querySelector(`.last_message_${chatList.sender_id}`);
            var aboutDiv = document.getElementById(`about_${chatList.sender_id}`);
            if (element) {
                var sender_name = chatList.sender.name + ": ";
                element.innerHTML = sender_name + message;
                aboutDiv.innerHTML = 'now';

                // Add Unread Msg Count
                if (chatList.unread_messages != 0 && chatList.sender_id != current_user.id) {
                    var element1 = document.createElement('span');
                    element1.classList.add('badge', 'a1-bg', 'float-right');
                    element1.innerHTML = chatList.unread_messages;
                    element.appendChild(element1);
                }
            } else {
                // update new chat user - not in chat list
                updateChatList(chatList)
            }
        }
    }

    function displayReceiveChat(chats, time, senderId) {

        var noChatMsg = document.getElementById('no-chat-msg');
        if (noChatMsg) {
            noChatMsg.style.display = 'none';
        }

        //  Li Element
        var liElement = document.createElement('li');
        liElement.classList.add('clearfix');

        // Time Element
        var timeEl = document.createElement('span');
        timeEl.style.fontSize = '10px';
        timeEl.innerHTML = time;

        var lineBreak = document.createElement('br');

        // Filter Image
        if (isUrl(chats)) {
            var element = document.createElement('img');
            element.setAttribute('src', chats);
            element.style.width = '20%';
        } else {
            var element = document.createElement('div');
            element.classList.add('other-message', 'message');
            element.innerHTML = chats;
        }

        element.appendChild(lineBreak);
        liElement.appendChild(element);
        liElement.appendChild(lineBreak);
        liElement.appendChild(timeEl);
        document.getElementById('ajax').appendChild(liElement);

        readMessage(senderId);
        updateChatBarPosition();
    }

    function fileUpload() {
        $('#imgupload').trigger('click');

    }

    function valueData() {
        var data = document.getElementById('imgupload').value;
        if (data) {
            document.getElementById('message').value = "Image";
        }
    }

    //url check function
    function isUrl(url) {
        const urlRegex = /^(https?|ftp):\/\/[^\s/$.?#].[^\s]*$/i;
        return urlRegex.test(url);
    }

    /**
     *  Ajax Functions
     **/
    function search() {
        var form_data = $('#form_search').val();

        $.ajax({
            method: 'POST',
            url: '{{ route('chat.search') }}',
            dataType: 'json',
            data: {
                '_token': '{{ csrf_token() }}',
                data: form_data
            },

            success: function(response) {
                $('#chatList').empty();
                displayChatList(response.chat_list);
            },
            error: function(error) {
                console.error('Error :', error);
            }
        });
    }

    function saveData() {
        var form = document.getElementById('send_form');
        var form_data = $('#send_form').serialize();
        var form_data = new FormData(form);
        var receiver_id = $('input[name="receiver_id"]').val();

        var message = document.getElementById('message').value;

        $.ajax({
            method: 'POST',
            url: '/chat/send/' + receiver_id,
            data: form_data,
            processData: false,
            contentType: false,
            success: function(response) {
                // console.log("💩 ~ chat-new:731 ~ save data:", response)
                displaySendMessage(response.message, response.send_time);
                displaySendMessageChatList(message);
            },
            error: function(error) {
                console.error('💩 ~ chat:727 ~ Send Error :', error);
            }
        });

        document.getElementById('send_form').reset();
    }

    function newMessageTrigger(senderId, chatRoomId) {

        $.ajax({
            method: 'GET',
            url: 'receive/data',
            dataType: 'json',
            data: {
                receiverId: senderId,
                chatRoomId: chatRoomId
            },
            success: function(response) {
                // console.log('new', response)
                // console.log('count', response.chat_count)

                displayLatestMessageChatList(response.chat_list);

                if (current_user.id == senderId) {
                    displayReceiveChat(response.chat.message, response.chat.send_time, response.chat
                        .sender_id);
                }
            },
            error: function(error) {
                console.error('Error :', error);
            }
        });
    };

    function readMessage(senderId) {
        $.ajax({
            method: 'POST',
            url: 'read/message',
            dataType: 'json',
            data: {
                senderID: senderId
            },
            success: function(response) {},
            error: function(error) {
                console.error('Error :', error);
            }
        });
    }

    /////////////////////////////////////////
    //          call
    /////////////////////////////////////////

    /**
     * For Audio and Video Call
     */
    async function call(type) {
        callType = type; // Identify when call accept

        var params = {
            To: current_user.id,
            Type: type,
            outgoing_caller_id: authID
        };
        const response = await fetch('/call-ring?userId=' + current_user.id + '&type=' + type);
        const data = await response.json();
        channelName = data.channel_name;
    };
</script>
