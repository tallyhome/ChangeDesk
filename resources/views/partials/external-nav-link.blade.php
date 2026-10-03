@php
    $externalUrl = trim((string) \App\Models\Setting::getValue('external_link_url', ''));
    $externalText = trim((string) \App\Models\Setting::getValue('external_link_text', ''));
    $externalOn = (string) \App\Models\Setting::getValue('external_link_enabled', '0') === '1';
@endphp
@if($externalOn && $externalUrl !== '' && $externalText !== '')
    <a href="{{ $externalUrl }}" target="_blank" rel="noopener noreferrer">{{ $externalText }}</a>
@endif
