@extends('themes.aurora.layouts.app')
@section('title', $article->title)
@section('content')
<div class="au-breadcrumb">
  <a href="{{ route('wiki') }}">{{ __('app.nav.wiki') }}</a>
  @if($article->category)
    <span>/</span>
    <a href="{{ route('wiki.category', $article->category->slug) }}">{{ $article->category->name }}</a>
  @endif
  <span>/</span>
  <span>{{ $article->title }}</span>
</div>

<div class="au-wiki">
  <aside class="au-panel">
    <div class="au-panel-pad">
      <strong style="display:block;margin-bottom:.75rem;color:#fff">{{ __('app.common.categories') }}</strong>
      <nav class="au-wiki-nav">
        <a href="{{ route('wiki') }}"><i class="fas fa-home"></i> {{ __('app.common.wiki_home') }}</a>
        @foreach($categories as $cat)
          <a href="{{ route('wiki.category', $cat->slug) }}" class="{{ $article->category && $article->category->id == $cat->id ? 'active' : '' }}">
            <i class="fas fa-folder"></i> {{ $cat->name }}
          </a>
        @endforeach
      </nav>
    </div>
  </aside>

  <article class="au-panel">
    <div class="au-panel-pad">
      <h1 class="au-title" style="margin-top:0">{{ $article->title }}</h1>
      <p class="au-muted" style="margin-top:-.5rem">{{ \App\Support\Locale::formatDate($article->updated_at) }}</p>
      <div class="au-prose" style="color:#e2e8f0">{!! $article->content !!}</div>
    </div>
  </article>

  <aside class="au-panel">
    <div class="au-panel-pad">
      <strong style="display:block;margin-bottom:.75rem;color:#fff">{{ __('app.common.articles') }}</strong>
      @forelse(($relatedArticles ?? collect()) as $related)
        <a href="{{ route('wiki.show', $related->slug) }}" style="display:block;margin-bottom:.65rem;font-weight:700;color:#fff">{{ $related->title }}</a>
      @empty
        <p class="au-muted" style="margin:0">{{ __('app.common.empty') }}</p>
      @endforelse
    </div>
  </aside>
</div>
@endsection
