@extends('themes.aurora.layouts.app')
@section('title', $category->name)
@section('content')
<h1 class="au-title">{{ $category->name }}</h1>
<div class="au-breadcrumb">
  <a href="{{ route('wiki') }}">{{ __('app.nav.wiki') }}</a> <span>/</span> <span>{{ $category->name }}</span>
</div>

<div class="au-wiki">
  <aside class="au-panel">
    <div class="au-panel-pad">
      <strong style="display:block;margin-bottom:.75rem;color:#fff">{{ __('app.common.categories') }}</strong>
      <nav class="au-wiki-nav">
        <a href="{{ route('wiki') }}"><i class="fas fa-home"></i> {{ __('app.common.wiki_home') }}</a>
        @foreach($categories as $cat)
          <a href="{{ route('wiki.category', $cat->slug) }}" class="{{ $category->id == $cat->id ? 'active' : '' }}">
            <i class="fas fa-folder"></i> {{ $cat->name }}
          </a>
        @endforeach
      </nav>
    </div>
  </aside>

  <div class="au-panel">
    <div class="au-panel-pad">
      @if($category->description)
        <p class="au-muted" style="margin-top:0">{{ $category->description }}</p>
      @endif
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

  <aside class="au-panel">
    <div class="au-panel-pad">
      <strong style="display:block;margin-bottom:.75rem;color:#fff">{{ __('app.common.search') }}</strong>
      <form class="au-form" action="{{ route('wiki.search') }}" method="GET">
        <div class="field"><input type="search" name="q" placeholder="{{ __('app.common.search') }}"></div>
        <button type="submit" class="au-btn" style="width:100%">{{ __('app.common.search') }}</button>
      </form>
    </div>
  </aside>
</div>
@endsection
