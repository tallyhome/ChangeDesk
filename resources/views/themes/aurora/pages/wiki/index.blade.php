@extends('themes.aurora.layouts.app')
@section('title', $wikiTitle)
@section('content')
<h1 class="au-title">{{ $wikiTitle }}</h1>

<div class="au-wiki">
  <aside class="au-panel">
    <div class="au-panel-pad">
      <strong style="display:block;margin-bottom:.75rem;color:#fff">Catégories</strong>
      <nav class="au-wiki-nav">
        <a href="{{ route('wiki') }}" class="active"><i class="fas fa-home"></i> Accueil</a>
        @foreach($categories as $category)
          <a href="{{ route('wiki.category', $category->slug) }}"><i class="fas fa-folder"></i> {{ $category->name }}</a>
        @endforeach
      </nav>
    </div>
  </aside>

  <div class="au-panel">
    <div class="au-panel-pad">
      @if($welcomeTitle || $welcomeText)
        <div class="au-panel-head">
          <div>
            @if($welcomeTitle)<h2 style="margin:0;font-size:1.15rem;font-weight:800;color:#fff">{{ $welcomeTitle }}</h2>@endif
            @if($welcomeText)
              <p class="au-muted" style="margin:.35rem 0 0">{!! nl2br(e($welcomeText)) !!}</p>
            @endif
          </div>
        </div>
      @endif

      <h2 style="margin:0 0 1rem;font-size:1.1rem;color:#fff">Articles récents</h2>
      @forelse($recentArticles as $article)
        <article class="au-article">
          <h3><a href="{{ route('wiki.show', $article->slug) }}">{{ $article->title }}</a></h3>
          <p class="au-muted" style="font-size:.85rem;margin:.2rem 0 .45rem">
            @if($article->category)<span class="au-chip">{{ $article->category->name }}</span>@endif
            {{ $article->updated_at?->format('d/m/Y') }}
          </p>
          <p class="au-muted" style="margin:0">{{ \Illuminate\Support\Str::limit(strip_tags($article->content), 160) }}</p>
        </article>
      @empty
        <p class="au-muted">Aucun article publié pour le moment.</p>
      @endforelse
    </div>
  </div>

  <aside class="au-panel">
    <div class="au-panel-pad">
      <strong style="display:block;margin-bottom:.75rem;color:#fff">Recherche</strong>
      <form class="au-form" action="{{ route('wiki.search') }}" method="GET">
        <div class="field">
          <input type="search" name="q" placeholder="Rechercher…" value="{{ request('q') }}">
        </div>
        <button type="submit" class="au-btn" style="width:100%">Chercher</button>
      </form>
      @if($categories->count())
        <hr style="border:0;border-top:1px solid rgba(148,163,184,.14);margin:1.1rem 0">
        <strong style="display:block;margin-bottom:.55rem;color:#fff;font-size:.9rem">Parcourir</strong>
        <nav class="au-wiki-nav">
          @foreach($categories as $category)
            <a href="{{ route('wiki.category', $category->slug) }}">{{ $category->name }}</a>
          @endforeach
        </nav>
      @endif
    </div>
  </aside>
</div>
@endsection
