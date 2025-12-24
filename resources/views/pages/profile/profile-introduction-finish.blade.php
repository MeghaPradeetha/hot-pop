@extends('layouts.home-layout')
<meta content="{{ csrf_token() }}" name="csrf-token">
@section('content')
    <section class="d1-bg landing-body">
        <div class="container py-4 py-lg-5">
            <div class="form-card p-200">
                <div class="header-card text-center">
                    <h3 class="p-b-24">Profile Setup</h3>
                </div>

                <div class="">
                    <!-- Navigation pills -->
                    <ul class="nav nav-pills my-3 d-flex justify-content-between" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button aria-controls="pills-home" aria-selected="false" class="nav-link dotpag p-0 active" data-bs-target="#pills-home" data-bs-toggle="pill"
                                id="pills-home-tab" role="tab" type="button">1</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button aria-controls="pills-2" aria-selected="true" class="nav-link p-0 active d-flex align-items-center  " data-bs-target="#pills-2" data-bs-toggle="pill"
                                id="pills-home-tab" role="tab" type="button"><span class="pro-bar">&nbsp;</span><span class="dotpag">2</span></button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button aria-controls="pills-3" aria-selected="false" class="nav-link p-0 d-flex align-items-center" data-bs-target="#pills-3" data-bs-toggle="pill"
                                id="pills-3-tab" role="tab" type="button"><span class="pro-bar">&nbsp;</span><span class="dotpag">3</span></button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button aria-controls="pills-4" aria-selected="false" class="nav-link p-0 d-flex align-items-center" data-bs-target="#pills-4" data-bs-toggle="pill"
                                id="pills-4-tab" role="tab" type="button"><span class="pro-bar">&nbsp;</span><span class="dotpag">4</span></button>
                        </li>
                    </ul>

                    {{-- <form class="p-t-40" method="POST" action="{{ route('profile-setup2') }}">
                    @method('put')
                    @csrf --}}
                    <div class="tab-content setup-form-card" id="pills-tabContent">
                        <h3 class="headingH3 p-b-32">Your Introduction</h3>
                        <p class="f-16 d5 p-b-24">Record a 10 second video of yourself as an introduction to everyone. 
                            If you don't want to add a video at this stage you can simply skip this step by clicking "Save & Next" button and just add pictures from next page.
                        </p>

                        <div class="row">
                            <div class="col-lg-7">
                                <div class="carousel-item w-100 active" style="height: 500px;">
                                    <video autoplay class="video-slide w-100" controls loading="lazy" loop muted style="height: 500px;">
                                        <source src="{{ asset('storage/'.$file->file_path) }}" type="video/mp4">
                                    </video>
                                    {{-- <video id="video-player" class="video-slide w-100"  autoplay controls loop muted loading="lazy">
                                        <source src="{{ asset('storage/'.$video->file_path ?? null) }}" type="video/mp4" />
                                    </video> --}}
                                </div>
                            </div>

                            <div class="col-lg-5 align-self-end">

                                <form action="{{ route('deleteUserVideos', ['userId' => $user->id]) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <!-- <button class="btn btn-danger" type="submit">Delete User Videos</button> -->
                                    <button class="secondary-button w-100 mb-2" style="margin-top: 20%">Delete</button>

                                </form>
                                <!-- <button class="secondary-button w-100 mb-2" style="margin-top: 20%">Delete</button> -->
                                <form action="{{ route('deleteUserVideos', ['userId' => $user->id]) }}" method="POST">
                                    <!-- <form action="{{ route('retake') }}" method="POST"> -->
                                    @csrf
                                    @method('delete')
                                    <button class="secondary-button w-100  mb-2">Retake</button>
                                </form>

                                <form action="{{ route('get.profile-setup-pictures') }}" method="GET">
                                    <button class="main-button w-100" id="downloadLink" type="submit">Save & Next</button>
                                </form>

                                <div class="text-danger text-center mb-2" id="error-msg" style="display: none;">Please Add Your Video First</div>
                                <button class="main-button w-100 mt-4" id="downloadLink" onclick="saveFormData()" style="display:none" type="submit">Save & Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script>
        const video = document.getElementById('video');
        const downloadLink = document.getElementById('downloadLink');
        let mediaRecorder;
        let recordedChunks = [];
        navigator.mediaDevices.getUserMedia({
            video: true,
            audio: false
        })
    </script>
@endpush
