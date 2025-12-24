{{-- <ul class="nav nav-tabs edit-tab" id="myTab" role="tablist" style="flex-wrap:unset">
    <li class="nav-item" role="presentation">
        <a href="{{ route('profile-edit', $user->id) }}" class="text-center nav-link {{ request()->is('profile/edit*') ? 'active' : '' }}" style="font-size: 20px;">About You</a>
    </li>
    <li class="nav-item" role="presentation">
        <a href="{{ route('profile-introduction-edit', $user->id) }}"
            class="text-center nav-link {{ request()->routeIs('profile-images-edit', 'profile-introduction-edit') ? 'active' : '' }}" style="font-size: 20px;">Pictures &
            Videos</a>
    </li>
    <li class="nav-item" role="presentation">
        <a href="{{ route('profile-personality-edit', $user->id) }}" class="text-center  nav-link {{ request()->is('profile/personality/edit*') ? 'active' : '' }}"
            style="font-size: 20px;">Personality</a>
    </li>
</ul> --}}

<ul class="nav nav-tabs edit-tab" id="myTab" role="tablist" style="flex-wrap:unset">
    <li class="nav-item" role="presentation">
        <button aria-controls="profile" aria-selected="false" class="nav-link tab-button {{ Session::get('tab', 1) == 1 ? 'active' : '' }}" data-bs-target="#profile"
            data-bs-toggle="tab" id="profile-tab" role="tab" type="button">About You</button>
    </li>
    <li class="nav-item" role="presentation">
        <button aria-controls="video" aria-selected="false" class="nav-link tab-button  {{ in_array(Session::get('tab'), [2, 3]) ? 'active' : '' }}" data-bs-target="#video"
            data-bs-toggle="tab" id="video-tab" role="tab" type="button">Pictures & Videos</button>
    </li>
    <li class="nav-item" role="presentation">
        <button aria-controls="personality" aria-selected="false" class="nav-link tab-button  {{ Session::get('tab') == 4 ? 'active' : '' }}" data-bs-target="#personality"
            data-bs-toggle="tab" id="personality-tab" type="button">Personality</button>
    </li>
</ul>
