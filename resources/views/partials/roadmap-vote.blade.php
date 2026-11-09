<form method="POST" action="{{ route('roadmap.vote', $item) }}" style="margin-top:.6rem">
    @csrf
    <button type="submit">
        {{ $item->voted ? __('app.public.voted') : __('app.public.vote') }}
        ({{ (int) ($item->votes_count ?? 0) }})
    </button>
</form>
