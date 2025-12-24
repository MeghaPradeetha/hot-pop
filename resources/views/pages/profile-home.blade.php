@extends('layouts.header-dashboard')

@section('content')
    <!-- Filter -->
    <div aria-hidden="true" aria-labelledby="filterModalLabel" class="modal fade" id="filterModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fas fa-filter fa-5x" style="color: #FF6600; opacity: 0.8;"></i>
                        </div>
                        <p class="f-16 d5 p-b-50">Please expand your filters to view more profiles.</p>
                        <div class="d-lg-flex d-block justify-content-between">
                            <button class="small-button" data-bs-dismiss="modal" type="button">Go back</button>
                            <a href="{{ route('match.filter') }}"><button class="small-button color" type="button">Change Filters</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="d1-bg landing-body h-100">
        <div class="container py-3 py-lg-5">
            <div class="form-card px-3 py-3 p-lg-4">
                <div class="header-bar position-relative mb-3 mb-lg-4">
                    <h3 class="headingH2">New Profiles</h3>
                    <a data-bs-target="#filterModal" data-bs-toggle="modal" href="/home" class="d-inline-block text-decoration-none filter-ico">
                        <div class="filter-icon-btn d-flex align-items-center justify-content-center" style="width: 67px; height: 67px; border-radius: 50%; background: rgba(255, 102, 0, 0.1); border: 2px solid #FF6600; transition: all 0.3s ease;">
                            <i class="fas fa-sliders-h" style="font-size: 28px; color: #FF6600;"></i>
                        </div>
                    </a>
                </div>

                <div class="row" id="profiles">
                    @if (count($profiles) > 0)
                        @include('pages.partials.user-profile', ['user' => $profiles[0]])
                    @else
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="text-center">

                                        <div class="mb-4">
                                            <i class="fas fa-filter fa-5x" style="color: #FF6600; opacity: 0.8;"></i>
                                        </div>
                                        <p class="f-16 d5 p-b-50">Please expand your filters to view more profiles.</p>
                                        <div class="d-lg-flex d-block justify-content-center">
                                            {{-- <button class="small-button" data-bs-dismiss="modal" type="button">Go back</button> --}}
                                            <a href="{{ route('match.filter') }}"><button class="small-button color" type="button">Change Filters</button></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Match -->
    <div aria-hidden="true" aria-labelledby="matchModalLabel" class="modal fade" id="matchModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center">
                        <h3 class="p-b-100">You have a match..!</h3>

                        <div class="position-relative mb-4 mt-5">
                            <img alt="profile pic" class="matchPng" src="{{ asset('images/match_speech-bubble.png') }}" width="" />
                            <img alt="profile pic" class="matchPicture" id="profile_pic_1" src="" width="" style=" margin-right: 50px;" />
							<img alt="profile pic" class="matchPicture" id="profile_pic_2" src="" width="" style=" margin-left: 30px;" />
                        </div>

                        {{-- <p class="f-16 d5 p-b-50">Claire and You are a match !</p> --}}
                        <p class="f-16 d5 p-b-50" id="phrase"></p>
                        <div class="d-lg-flex d-block justify-content-between">
                            <button class="small-button" data-bs-dismiss="modal" onclick="nextProfile()" type="button">Keep Matching</button>
                            <a href="" id="message"><button class="small-button color" type="button">Message</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')

    <script>
        var currentIndex = 1;
        var preferred_by = @json($preferred_by_profiles);
        var profiles = @json($profiles);

        function saveFormData(action) {
            var is_match = false;
            if (action == 1)
                is_match = showMatchProfile()


            var prefer_user_id = $('#preferred_user_id').val();
            var user_id = $('#user_id').val();
            formData = 'preferred_user_id=' + prefer_user_id + '&' + 'user_id=' + user_id + '&' + 'preference=' + action;

            if (!is_match)
                nextProfile();

            $.ajax({
                method: 'POST',
                url: '/save-preference',
                data: formData,
                dataType: "json",
                success: function(response) {
                    //

                    preferred_by = response.preferred_by_profiles;

                },
                error: function(error) {

                    console.log(error);
                }
            });
        }

        function showMatchProfile() {

            var logged_user = @json($logged_user);
            var current_profile = profiles[currentIndex - 1];
            var match = false;
            if (preferred_by.length != 0) {
                preferred_by.forEach((userId) => {
                    if (userId == $('#preferred_user_id').val()) {
                        //popup the modal if you like a profile that  have liked yours
                        $('#matchModal').modal('show');
						const avatar1 = "{{ asset('storage/') }}" + '/' +  current_profile.avatar;
						const avatar2 = "{{ asset('storage/') }}" + '/' +  logged_user.avatar;

						document.getElementById('profile_pic_1').setAttribute('src', avatar1);
    					document.getElementById('profile_pic_2').setAttribute('src', avatar2);

						document.getElementById('phrase').innerHTML = current_profile.name + " and you are a match..!";

                        //if going to message the person
                        var url = '{{ route('chat.profile', ':id') }}';
                        url = url.replace(':id', userId);
                        document.getElementById('message').setAttribute("href", url);

                        match = true;
                    }
                });
            }

            return match;
        }

        function nextProfile() {
            var profiles = @json($profiles);
            var user = profiles[currentIndex];

            if (profiles.length > currentIndex) {
                $.ajax({
                    url: '/get-next-profile',
                    method: 'GET',
                    data: {
                        index: user['id']
                    },
                    success: function(html) {
                        currentIndex = currentIndex + 1;
                        $('#profiles').html(html);
                    },
                    error: function(error) {
                        console.error('Error fetching next profile:', error);
                    }
                });
            } else {
                window.location.reload();
                // $('#profiles').html(" <div class='alert alert-success center'>No profiles found..!</div>");
            }
        }

    </script>
@endpush
