@extends('admin.layouts.app')

@section('title', 'Edit Article')
@section('page-title', 'Edit: ' . $article->title)

@section('content')
<div class="max-w-4xl">
    <form method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.articles._form')
        <div class="flex items-center gap-3 mt-6 pt-6 border-t border-gray-200">
            <button type="submit"
                    class="px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
                Update Article
            </button>
            <a href="{{ route('admin.articles.index') }}"
               class="px-5 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition">
                Cancel
            </a>
            @if($article->status === 'published')
                <a href="{{ route('articles.show', $article->slug) }}" target="_blank"
                   class="ml-auto px-5 py-2 text-sm font-medium text-gray-500 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    View Live
                </a>
            @endif
        </div>
    </form>
</div>
@endsection
