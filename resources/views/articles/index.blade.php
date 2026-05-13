@extends('layouts.nusagrade')

@section('seo-title', 'Blog - Nusagrade')

@push('head')
<meta name="description" content="Latest insights on Indonesian commodity exports - spices, coffee, and cocoa from Nusagrade.">
@endpush

@section('content')
<div style="padding-top: 120px; min-height: 80vh; background: var(--cream);">
    <div class="container" style="padding-top: 32px; padding-bottom: 80px;">

        <div style="margin-bottom: 48px;">
            <div class="tag">Blog</div>
            <h1 style="font-family: var(--serif); font-size: 2.8rem; color: var(--ink); margin-top: 12px; line-height: 1.15;">Latest Insights</h1>
            <p style="color: var(--ink-light); margin-top: 12px; font-size: 0.95rem; max-width: 520px;">Industry news, export guides, and commodity insights from the Nusagrade team.</p>
        </div>

        @if($articles->isEmpty())
            <p style="color: var(--ink-light);">No articles published yet. Check back soon.</p>
        @else
            <div class="blog-grid">
                @foreach($articles as $i => $article)
                    <a href="{{ route('articles.show', $article->slug) }}"
                       class="blog-card reveal {{ $i > 0 ? 'reveal-delay-' . min($i, 5) : '' }}"
                       style="text-decoration:none;color:inherit;">
                        <div class="blog-img">
                            @if($article->thumbnail)
                                <img src="{{ asset('storage/'.$article->thumbnail) }}" alt="{{ $article->title }}" class="blog-img-real">
                            @else
                                <div class="img-ph" style="width:100%;height:100%;">article image</div>
                            @endif
                        </div>
                        <div class="blog-date">{{ $article->published_at ? $article->published_at->format('M d, Y') : '' }}</div>
                        <h2 class="blog-name" style="font-size: 1.05rem;">{{ $article->title }}</h2>
                        <p class="blog-excerpt">{{ $article->excerpt }}</p>
                    </a>
                @endforeach
            </div>

            @if($articles->hasPages())
                <div style="margin-top: 48px; display: flex; justify-content: center;">
                    {{ $articles->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
