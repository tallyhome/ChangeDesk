@php
    $tenantBrand = $currentTenant->name ?? auth()->user()?->tenant?->name;
    $publicUrl = ($currentTenant ?? auth()->user()?->tenant)?->publicBaseUrl();
@endphp
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" style="z-index: 1030; height: 56px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">{{ $tenantBrand ?: __('app.admin.my_project') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <button class="nav-link" onclick="toggleTheme()" style="background: none; border: none; cursor: pointer;" type="button" title="{{ __('app.admin.appearance') }}">
                        <i id="theme-icon" class="fas fa-moon"></i>
                    </button>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.profile.edit') }}">
                        <i class="fas fa-user-cog"></i> {{ __('app.nav.profile') }}
                    </a>
                </li>
                @if($publicUrl)
                <li class="nav-item">
                    <a class="nav-link" href="{{ $publicUrl }}" target="_blank" rel="noopener">
                        <i class="fas fa-external-link-alt"></i> {{ __('app.nav.view_site') }}
                    </a>
                </li>
                @endif
                <li class="nav-item d-flex align-items-center px-2">
                    @include('partials.lang-switcher', ['variant' => 'on-dark'])
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link">
                            <i class="fas fa-sign-out-alt"></i> {{ __('app.nav.logout') }}
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
