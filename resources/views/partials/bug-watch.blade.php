@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<form method="POST" action="{{ route('bug.watch', $bug->id) }}" style="margin-top:1.25rem">
    @csrf
    @include('partials.bug-public-guard')
    <p style="margin:0 0 .35rem"><strong>{{ __('app.public.bug_watch') }}</strong></p>
    <p class="small" style="opacity:.8;margin:0 0 .6rem">{{ __('app.public.bug_watch_help') }}</p>
    <div style="display:flex;gap:.5rem;flex-wrap:wrap">
        <input type="email" name="email" required maxlength="255" autocomplete="email" placeholder="{{ __('app.public.subscribe_email') }}" value="{{ old('email') }}">
        <button type="submit">{{ __('app.public.subscribe_button') }}</button>
    </div>
</form>
@error('email')
    <p class="small" style="color:#b91c1c">{{ $message }}</p>
@enderror
