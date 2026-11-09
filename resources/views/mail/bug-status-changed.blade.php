@php
    $url = $tenant->publicBaseUrl().'/bug-report/'.$bug->id;
    $leave = $tenant->publicBaseUrl().'/bug-unwatch/'.$watcher->token;
@endphp
<p>{{ __('app.mail.bug_lead', ['name' => $tenant->name]) }}</p>
<p>{{ $bug->title }}</p>
<p>{{ \App\Support\ThemeUi::statusLabel($bug->status) }}</p>
<p><a href="{{ $url }}">{{ $url }}</a></p>
<p><a href="{{ $leave }}">{{ __('app.mail.unsubscribe') }}</a></p>
