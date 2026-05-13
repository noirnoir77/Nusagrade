@extends('admin.layouts.app')

@section('title', 'Articles')
@section('page-title', 'Articles')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $articles->total() }} article(s) total</p>
    <a href="{{ route('admin.articles.create') }}"
       class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Article
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Title</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Status</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Published</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Updated</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($articles as $article)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3">
                    <div class="font-medium text-gray-900">{{ $article->title }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">/articles/{{ $article->slug }}</div>
                </td>
                <td class="px-5 py-3">
                    @if($article->status === 'published')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Published</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Draft</span>
                    @endif
                </td>
                <td class="px-5 py-3 text-gray-500">
                    {{ $article->published_at ? $article->published_at->format('M d, Y') : '-' }}
                </td>
                <td class="px-5 py-3 text-gray-500">{{ $article->updated_at->format('M d, Y') }}</td>
                <td class="px-5 py-3 text-right">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.articles.edit', $article) }}"
                           class="text-gray-500 hover:text-gray-900 font-medium">Edit</a>
                        @if($article->status === 'published')
                            <a href="{{ route('articles.show', $article->slug) }}" target="_blank"
                               class="text-gray-400 hover:text-gray-700">View</a>
                        @endif
                        <form method="POST" action="{{ route('admin.articles.destroy', $article) }}"
                              onsubmit="return confirm('Delete this article?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-10 text-center text-gray-400">
                    No articles yet.
                    <a href="{{ route('admin.articles.create') }}" class="text-gray-900 underline">Create one.</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($articles->hasPages())
        <div class="px-5 py-3 border-t border-gray-100">{{ $articles->links() }}</div>
    @endif
</div>
@endsection
