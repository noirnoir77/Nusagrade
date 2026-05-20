<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $articles = Article::published()
            ->orderByDesc('published_at')
            ->get(['slug', 'published_at', 'updated_at']);

        $content = view('sitemap', compact('articles'))->render();

        return response($content, 200, ['Content-Type' => 'application/xml']);
    }
}
