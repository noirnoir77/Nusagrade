@extends('admin.layouts.app')

@section('title', 'New Article')
@section('page-title', 'New Article')

@section('content')
<div class="max-w-4xl">
    <form method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.articles._form')
        <div class="flex items-center gap-3 mt-6 pt-6 border-t border-gray-200">
            <button type="submit"
                    class="px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
                Save Article
            </button>
            <a href="{{ route('admin.articles.index') }}"
               class="px-5 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
