@extends('layouts.nusagrade')

@section('seo-title', ($article->seo_title ?? $article->title) . ' - Nusagrade')

@push('head')
{{-- Primary SEO --}}
<meta name="description" content="{{ $article->seo_description ?? $article->excerpt }}">
@if($article->seo_keywords)
<meta name="keywords" content="{{ $article->seo_keywords }}">
@endif
<link rel="canonical" href="{{ $article->canonical_url ?? request()->url() }}">

{{-- Open Graph --}}
<meta property="og:type" content="article">
<meta property="og:title" content="{{ $article->og_title ?? $article->seo_title ?? $article->title }}">
<meta property="og:description" content="{{ $article->og_description ?? $article->seo_description ?? $article->excerpt }}">
<meta property="og:url" content="{{ $article->canonical_url ?? request()->url() }}">
@if($article->og_image)
<meta property="og:image" content="{{ asset('storage/'.$article->og_image) }}">
@elseif($article->thumbnail)
<meta property="og:image" content="{{ asset('storage/'.$article->thumbnail) }}">
@endif
<meta property="og:site_name" content="Nusagrade">
<meta property="article:published_time" content="{{ $article->published_at?->toIso8601String() }}">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $article->og_title ?? $article->seo_title ?? $article->title }}">
<meta name="twitter:description" content="{{ $article->og_description ?? $article->seo_description ?? $article->excerpt }}">
@if($article->og_image)
<meta name="twitter:image" content="{{ asset('storage/'.$article->og_image) }}">
@elseif($article->thumbnail)
<meta name="twitter:image" content="{{ asset('storage/'.$article->thumbnail) }}">
@endif

{{-- JSON-LD Schema --}}
@if($article->schema_markup)
<script type="application/ld+json">{!! json_encode($article->schema_markup, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@else
<script type="application/ld+json">{!! json_encode([
    '@context'         => 'https://schema.org',
    '@type'            => 'Article',
    'headline'         => $article->seo_title ?? $article->title,
    'description'      => $article->seo_description ?? $article->excerpt,
    'datePublished'    => $article->published_at?->toIso8601String(),
    'dateModified'     => $article->updated_at->toIso8601String(),
    'author'           => ['@type' => 'Organization', 'name' => 'Nusagrade'],
    'publisher'        => ['@type' => 'Organization', 'name' => 'Nusagrade'],
    'url'              => $article->canonical_url ?? request()->url(),
    'image'            => $article->og_image ? asset('storage/'.$article->og_image) : ($article->thumbnail ? asset('storage/'.$article->thumbnail) : null),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endif
@endpush

@section('content')
<article style="padding-top: 100px; background: var(--cream); min-height: 80vh;">

    {{-- Hero / Thumbnail --}}
    @if($article->thumbnail)
    <div style="width: 100%; max-height: 480px; overflow: hidden;">
        <img src="{{ asset('storage/'.$article->thumbnail) }}"
             alt="{{ $article->title }}"
             style="width: 100%; max-height: 480px; object-fit: cover; display: block;">
    </div>
    @endif

    <div class="container" style="padding-top: 48px; padding-bottom: 80px;">
        <div style="max-width: 740px; margin: 0 auto;">

            {{-- Meta --}}
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
                @if($article->published_at)
                    <span style="font-family: var(--mono); font-size: 0.62rem; letter-spacing: 0.14em; text-transform: uppercase; color: var(--brown);">
                        {{ $article->published_at->format('F d, Y') }}
                    </span>
                @endif
            </div>

            {{-- Title --}}
            <h1 style="font-family: var(--serif); font-size: clamp(2rem, 5vw, 3rem); color: var(--ink); line-height: 1.15; margin-bottom: 20px;">
                {{ $article->title }}
            </h1>

            {{-- Excerpt --}}
            @if($article->excerpt)
                <p style="font-size: 1.1rem; color: var(--ink-mid); line-height: 1.7; margin-bottom: 36px; padding-bottom: 36px; border-bottom: 1px solid var(--cream-deep);">
                    {{ $article->excerpt }}
                </p>
            @endif

            {{-- Body --}}
            <div class="article-body" style="
                color: var(--ink-mid);
                font-size: 1rem;
                line-height: 1.8;
            ">
                {!! $article->body !!}
            </div>

            {{-- Back link --}}
            <div style="margin-top: 56px; padding-top: 32px; border-top: 1px solid var(--cream-deep);">
                <a href="{{ route('articles.index') }}"
                   style="font-family: var(--mono); font-size: 0.65rem; letter-spacing: 0.14em; text-transform: uppercase; color: var(--brown); text-decoration: none;">
                    &larr; All Articles
                </a>
            </div>
        </div>
    </div>
</article>

<style>
.article-body h1, .article-body h2, .article-body h3, .article-body h4 {
    font-family: var(--serif);
    color: var(--ink);
    margin: 1.6em 0 0.6em;
    line-height: 1.25;
}
.article-body h2 { font-size: 1.7rem; }
.article-body h3 { font-size: 1.35rem; }
.article-body p { margin-bottom: 1.2em; }
.article-body a { color: var(--brown); }
.article-body a:hover { color: var(--ink); }
.article-body ul, .article-body ol { padding-left: 1.5em; margin-bottom: 1.2em; }
.article-body li { margin-bottom: 0.4em; }
.article-body img { border-radius: 4px; margin: 1.6em 0; max-width: 100%; height: auto; }
.article-body blockquote {
    border-left: 3px solid var(--brown-light);
    margin: 1.6em 0;
    padding: 0.8em 1.2em;
    background: var(--cream-warm);
    color: var(--ink-mid);
    font-style: italic;
}
.article-body pre, .article-body code {
    font-family: var(--mono);
    font-size: 0.85em;
    background: var(--cream-warm);
    border-radius: 3px;
}
.article-body pre { padding: 1em; overflow-x: auto; }
.article-body code { padding: 2px 5px; }
</style>
@endsection
