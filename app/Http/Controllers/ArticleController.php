<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticleController extends Controller
{
    public function index(): View
    {
        $articles = Article::published()->latest('published_at')->paginate(9);
        return view('articles.index', compact('articles'));
    }

    public function show(string $slug): View
    {
        $article = Article::published()->where('slug', $slug)->first();

        if (!$article) {
            throw new NotFoundHttpException();
        }

        return view('articles.show', compact('article'));
    }
}
