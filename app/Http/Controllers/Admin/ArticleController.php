<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        $articles = Article::latest()->paginate(15);
        return view('admin.articles.index', compact('articles'));
    }

    public function create(): View
    {
        return view('admin.articles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data = $this->handleUploads($request, $data);
        $data['slug'] = $this->uniqueSlug($request->input('slug') ?: $request->input('title'));
        $data['published_at'] = $data['status'] === 'published' ? now() : null;
        $data['schema_markup'] = $this->parseSchema($request->input('schema_markup'));

        Article::create($data);

        return redirect()->route('admin.articles.index')->with('success', 'Article created.');
    }

    public function edit(Article $article): View
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $data = $this->validated($request, $article->id);
        $data = $this->handleUploads($request, $data, $article);
        $data['slug'] = $this->uniqueSlug($request->input('slug') ?: $request->input('title'), $article->id);
        if ($data['status'] === 'published' && $article->status !== 'published') {
            $data['published_at'] = now();
        } elseif ($data['status'] === 'draft') {
            $data['published_at'] = null;
        }
        $data['schema_markup'] = $this->parseSchema($request->input('schema_markup'));

        $article->update($data);

        return redirect()->route('admin.articles.index')->with('success', 'Article updated.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        if ($article->thumbnail) {
            Storage::disk('public')->delete($article->thumbnail);
        }
        if ($article->og_image) {
            Storage::disk('public')->delete($article->og_image);
        }
        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Article deleted.');
    }

    private function validated(Request $request, ?int $articleId = null): array
    {
        return $request->validate([
            'title'           => 'required|string|max:255',
            'slug'            => ['nullable', 'regex:/^[a-z0-9-]*$/', "unique:articles,slug,{$articleId}"],
            'excerpt'         => 'nullable|string',
            'body'            => 'nullable|string',
            'thumbnail'       => 'nullable|image|max:4096',
            'status'          => 'required|in:draft,published',
            'seo_title'       => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:320',
            'seo_keywords'    => 'nullable|string|max:500',
            'og_title'        => 'nullable|string|max:255',
            'og_description'  => 'nullable|string|max:320',
            'og_image'        => 'nullable|image|max:4096',
            'canonical_url'   => 'nullable|url',
            'schema_markup'   => 'nullable|string',
        ]);
    }

    private function handleUploads(Request $request, array $data, ?Article $article = null): array
    {
        if ($request->hasFile('thumbnail')) {
            if ($article?->thumbnail) {
                Storage::disk('public')->delete($article->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('articles/thumbnails', 'public');
        } else {
            unset($data['thumbnail']);
        }

        if ($request->hasFile('og_image')) {
            if ($article?->og_image) {
                Storage::disk('public')->delete($article->og_image);
            }
            $data['og_image'] = $request->file('og_image')->store('articles/og', 'public');
        } else {
            unset($data['og_image']);
        }

        return $data;
    }

    private function uniqueSlug(string $base, ?int $exceptId = null): string
    {
        $slug = Str::slug($base);
        $original = $slug;
        $i = 1;

        while (Article::where('slug', $slug)->when($exceptId, fn($q) => $q->where('id', '!=', $exceptId))->exists()) {
            $slug = "{$original}-{$i}";
            $i++;
        }

        return $slug;
    }

    private function parseSchema(?string $raw): ?array
    {
        if (empty(trim((string) $raw))) {
            return null;
        }
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : null;
    }
}
