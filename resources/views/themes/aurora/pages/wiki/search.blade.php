@extends('themes.aurora.layouts.app')
@section('title', __('app.common.search'))
@section('content')
<h1 class="au-title">{{ __('app.common.search') }}</h1>
<p class="au-muted" style="margin-top:-.5rem;margin-bottom:1.25rem">Résultats pour « {{ $query }} »</p>

<div class="au-wiki">
  <aside class="au-panel">
    <div class="au-panel-pad">
      <strong style="display:block;margin-bottom:.75rem;color:#fff">{{ __('app.common.categories') }}</strong>
      <nav class="au-wiki-nav">
        <a href="{{ route('wiki') }}"><i class="fas fa-home"></i> {{ __('app.common.wiki_home') }}</a>
        @foreach($categories as $cat)
          <a href="{{ route('wiki.category', $cat->slug) }}"><i class="fas fa-folder"></i> {{ $cat->name }}</a>
        @endforeach
      </nav>
    </div>
  </aside>

  <div class="au-panel">
    <div class="au-panel-pad">
      <form class="au-form" action="{{ route('wiki.search') }}" method="GET" style="margin-bottom:1.25rem">
        <div class="field"><input type="search" name="q" value="{{ $query }}" placeholder="{{ __('app.common.search') }}"></div>
        <button type="submit" class="au-btn">{{ __('app.common.search') }}</button>
      </form>
      @forelse($articles as $article)
        <article class="au-article">
          <h3><a href="{{ route('wiki.show', $article->slug) }}">{{ $article->title }}</a></h3>
          <p class="au-muted" style="margin:0">{{ \Illuminate\Support\Str::limit(strip_tags($article->content), 160) }}</p>
        </article>
      @empty
        <p class="au-muted">{{ __('app.common.empty') }}</p>
      @endforelse
    </div>
  </div>

  <div></div>
</div>
@endsection
