@extends('layouts.header-dashboard')

@section('content')
    <section class="d1-bg landing-body">
        <div class="container py-4 py-lg-5">
            @include('oxygen::partials.flash')

            <div class="form-card py-4 px-2 p-lg-4">
                <div class="header-bar position-relative mb-4">
                    <h3 class="headingH2">Edit Profile</h3>
                </div>

                @include('pages.edit-profile.partials.tab-items')

                <div>
                    <div class="tab-content" id="myTabContent">
                        <div aria-labelledby="profile-tab" class="tab-pane fade {{ Session::get('tab', 1) == 1 ? 'show active' : '' }}" id="profile" role="tabpanel">
                            @include('pages.edit-profile.partials.edit-profile-setup')
                        </div>
                        <div aria-labelledby="video-tab" class="tab-pane fade {{ Session::get('tab') == 2 ? 'show active' : '' }}" id="video" role="tabpanel">
                            @include('pages.edit-profile.partials.edit-profile-introduction')
                        </div>
                        <div aria-labelledby="pictures-tab" class="tab-pane fade {{ Session::get('tab') == 3 ? 'show active' : '' }}" id="pictures" role="tabpanel">
                            @include('pages.edit-profile.partials.edit-profile-setup-pictures')
                        </div>
                        <div aria-labelledby="personality-tab" class="tab-pane fade {{ Session::get('tab') == 4 ? 'show active' : '' }}" id="personality" role="tabpanel">
                            @include('pages.edit-profile.partials.edit-profile-personality')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function initialize() {
            var input = document.getElementById('hometown');
            var inputLocation = document.getElementById('location');
            var autocomplete = new google.maps.places.Autocomplete(input);
            var autocomplete2 = new google.maps.places.Autocomplete(inputLocation);

            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
            });

            autocomplete2.addListener('place_changed', function() {
                var place = autocomplete2.getPlace();
            });
        }
    </script>

    <script async type="text/javascript" src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&callback=initialize&libraries=places&loading=async"></script>
    <script>
        // JavaScript function to switch to the Pictures tab
        function switchToPicturesTab() {
            $('#myTab a[href="#video"]').removeClass('active');
            $('#video').removeClass('show active');

            $('#myTab a[href="#pictures"]').addClass('active');
            $('#pictures').addClass('show active');
            // $('#myTab a[href="#pictures"]').tab('show');
        }

        function backToVideo() {
            $('#myTab a[href="#pictures"]').removeClass('active');
            $('#pictures').removeClass('show active');

            $('#myTab a[href="#video"]').addClass('active');
            $('#video').addClass('show active');
        }
    </script>
@endsection
