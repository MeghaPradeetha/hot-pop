<header>
	<style>
		a:visited { color: inherit; }
		a:active { color: inherit; }
        .d1-bg { background: transparent !important; } /* Override header bg */
	</style>
    
    <!-- Fixed Logo Top Left - Only on Landing Page -->
    @if (Request::is('/'))
    <a class="header-logo-fixed" href="/home">
        <img alt="logo" src="{{ asset('images/horizontalLogoTransparentBG.png') }}" />
    </a>
    @endif
    
    <!-- Glassmorphism Bottom Dock -->
    <nav class="glass-dock">
        @if (Auth::user() == null)
            <a href="/home" class="dock-item {{ Request::is('home') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span class="dock-label">Home</span>
            </a>
            <a href="/register" class="dock-item {{ Request::is('register') ? 'active' : '' }}">
                <i class="fas fa-user-plus"></i>
                <span class="dock-label">Register</span>
            </a>
            <a href="/login" class="dock-item {{ Request::is('login') ? 'active' : '' }}">
                <i class="fas fa-sign-in-alt"></i>
                <span class="dock-label">Login</span>
            </a>
        @else
            <a href="{{ route('home') }}" class="dock-item {{ Request::is('home') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span class="dock-label">Home</span>
            </a>
            <a href="{{ route('chats') }}" class="dock-item {{ Request::is('chats') ? 'active' : '' }}">
                <i class="fas fa-comments"></i>
                <span class="dock-label">Chats</span>
                @if($user->has_unread_chat > 0)
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" style="width: 10px; height: 10px;"></span>
                @endif
            </a>
            <a href="{{ route('matched-profiles') }}" class="dock-item {{ Request::is('matched-profiles') ? 'active' : '' }}">
                <i class="fas fa-heart"></i>
                <span class="dock-label">Matches</span>
                @if($user->has_matched_profile > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">{{ $user->has_matched_profile }}</span>
                @endif
            </a>
            <a href="{{ route('my-profile') }}" class="dock-item {{ Request::is('my-profile') ? 'active' : '' }}">
                <img alt="Profile" class="rounded-circle" src="{{ asset('storage/'.$user->avatar )?? asset('images/sampleimages/img_nav bar_profileimg.jpeg') }}" style="width: 24px; height: 24px;">
                <span class="dock-label">Profile</span>
            </a>
            
            <!-- Dropup for Settings/More -->
            <div class="dropup">
                <button class="dock-item" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </button>
                <ul class="dropdown-menu mb-3" style="background: rgba(20,20,20,0.95); border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                    <li><a class="dropdown-item text-white" href="{{ route('my-account') }}">Account</a></li>
                    <li><a class="dropdown-item text-white" href="{{ route('chat.settings') }}">Settings</a></li>
                    <li><hr class="dropdown-divider bg-secondary"></li>
                    <li><a class="dropdown-item text-danger" data-bs-target="#logoutModal" data-bs-toggle="modal" href="#">Logout</a></li>
                </ul>
            </div>
        @endif
    </nav>

    @include('layouts.logout')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logo = document.querySelector('.header-logo-fixed');
            if (logo) {
                window.addEventListener('scroll', function() {
                    const scrollPos = window.scrollY;
                    const headerHeight = 200; // Fade out completely after 200px
                    const opacity = Math.max(0, 1 - (scrollPos / headerHeight));
                    logo.style.opacity = opacity;
                    logo.style.pointerEvents = opacity <= 0 ? 'none' : 'auto';
                });
            }
        });
    </script>
</header>
