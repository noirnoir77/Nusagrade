<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ url('/articles') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    @foreach ($articles as $article)
    <url>
        <loc>{{ url('/articles/' . $article->slug) }}</loc>
        <lastmod>{{ ($article->published_at ?? $article->updated_at)->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
</urlset>
