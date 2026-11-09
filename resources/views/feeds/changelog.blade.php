{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<rss version="2.0">
  <channel>
    <title>{{ $tenant->name }}</title>
    <link>{{ $tenant->publicBaseUrl() }}/changelog</link>
    <description>{{ $tenant->name }}</description>
    @foreach($versions as $version)
      <item>
        <title>v{{ $version->version_number }}</title>
        <link>{{ $tenant->publicBaseUrl() }}/changelog#version-{{ $version->id }}</link>
        <guid isPermaLink="false">evolora-{{ $tenant->id }}-version-{{ $version->id }}</guid>
        <pubDate>{{ optional($version->release_date)->toRfc2822String() }}</pubDate>
        <description>{{ \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/u', ' ', strip_tags((string) ($version->content ?: $version->description)))), 500, '') }}</description>
      </item>
    @endforeach
  </channel>
</rss>
