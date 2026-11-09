@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<form method="POST" action="{{ route('changelog.subscribe') }}" style="margin:0 0 1.5rem">
    @csrf
    @include('partials.bug-public-guard')
    <p style="margin:0 0 .35rem"><strong>{{ __('app.public.subscribe_title') }}</strong></p>
    <p class="small" style="opacity:.8;margin:0 0 .6rem">{{ __('app.public.subscribe_help') }}</p>
    <div style="display:flex;gap:.5rem;flex-wrap:wrap">
        <input type="email" name="email" required maxlength="255" autocomplete="email" placeholder="{{ __('app.public.subscribe_email') }}" value="{{ old('email') }}" style="min-width:16rem">
        <button type="submit">{{ __('app.public.subscribe_button') }}</button>
    </div>
    <p class="small" style="margin:.6rem 0 0"><a href="{{ url('/changelog.xml') }}">{{ __('app.public.rss') }}</a></p>
</form>
@error('email')
    <p class="small" style="color:#b91c1c">{{ $message }}</p>
@enderror
