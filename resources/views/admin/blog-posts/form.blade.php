@extends('admin.layouts.app')

@section('title', $post->exists ? 'Edit Blog Post' : 'New Blog Post')

@section('content')
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ $post->exists ? 'Edit Blog Post' : 'New Blog Post' }}</h1>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 max-w-3xl">
        <form method="POST" action="{{ $post->exists ? route('admin.blog-posts.update', $post) : route('admin.blog-posts.store') }}">
            @csrf
            @if ($post->exists) @method('PUT') @endif

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}" required
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                @error('title') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                <input type="text" id="category" name="category" value="{{ old('category', $post->category) }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                @error('category') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="featured_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Featured Image URL</label>
                <input type="text" id="featured_image" name="featured_image" value="{{ old('featured_image', $post->featured_image) }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                @error('featured_image') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Content</label>
                <textarea id="content" name="content" rows="15" required
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm font-mono">{{ old('content', $post->content) }}</textarea>
                @error('content') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label for="published_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Published At</label>
                <input type="datetime-local" id="published_at" name="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                @error('published_at') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty to save as draft.</p>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition">
                    {{ $post->exists ? 'Update Post' : 'Create Post' }}
                </button>
                <a href="{{ route('admin.blog-posts.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
@endsection
