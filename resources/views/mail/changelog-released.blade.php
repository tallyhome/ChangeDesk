@php
    $excerpt = \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/u', ' ', strip_tags((string) $version->content))), 400, '');
    $url = $tenant->publicBaseUrl().'/changelog#version-'.$version->id;
    $leave = $tenant->publicBaseUrl().'/changelog/unsubscribe/'.$subscriber->token;
@endphp
<p>{{ __('app.mail.release_lead', ['name' => $tenant->name, 'version' => $version->version_number]) }}</p>
@if($excerpt !== '')
<p>{{ $excerpt }}</p>
@endif
<p><a href="{{ $url }}">{{ $url }}</a></p>
<p><a href="{{ $leave }}">{{ __('app.mail.unsubscribe') }}</a></p>
