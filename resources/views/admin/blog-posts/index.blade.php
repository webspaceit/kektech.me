@extends('admin.layouts.app')

@section('title', 'Blog Posts')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Blog Posts</h1>
        <a href="{{ route('admin.blog-posts.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition">New Post</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <th class="text-left px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Title</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Category</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Published</th>
                    <th class="text-right px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($posts as $post)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-4 py-3 text-gray-900 dark:text-white font-medium max-w-xs truncate">{{ $post->title }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $post->category ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if ($post->published_at)
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $post->published_at->format('M j, Y') }}</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">Draft</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.blog-posts.edit', $post) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm mr-3">Edit</a>
                            <form method="POST" action="{{ route('admin.blog-posts.destroy', $post) }}" class="inline" onsubmit="return confirm('Delete this post?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No blog posts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($posts->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@endsection
