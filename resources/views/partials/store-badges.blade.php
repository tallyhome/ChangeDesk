@php
    $playOn = (string) \App\Models\Setting::getValue('play_store_enabled', '0') === '1';
    $appOn = (string) \App\Models\Setting::getValue('app_store_enabled', '0') === '1';
    $playUrl = trim((string) \App\Models\Setting::getValue('play_store_url', ''));
    $appUrl = trim((string) \App\Models\Setting::getValue('app_store_url', ''));
@endphp
@if(($playOn && $playUrl !== '') || ($appOn && $appUrl !== ''))
    <span class="store-badges">
        @if($playOn && $playUrl !== '')
            <a href="{{ $playUrl }}" target="_blank" rel="noopener noreferrer">
                <img src="{{ asset('images/google-play-badge.svg') }}" alt="Google Play" height="40" width="135">
            </a>
        @endif
        @if($appOn && $appUrl !== '')
            <a href="{{ $appUrl }}" target="_blank" rel="noopener noreferrer">
                <img src="{{ asset('images/app-store-badge.svg') }}" alt="App Store" height="40" width="120">
            </a>
        @endif
    </span>
    <style>
        .store-badges{display:inline-flex;gap:.45rem;align-items:center;flex-wrap:wrap}
        .store-badges a{display:inline-flex!important;line-height:0;background:#fff;border-radius:7px;overflow:hidden;padding:0!important;margin:0!important}
        .store-badges img{height:40px;width:auto;display:block}
    </style>
@endif
