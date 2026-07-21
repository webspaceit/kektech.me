@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Projects</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $projects }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Skills</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $skills }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Blog Posts</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $blogPosts }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Testimonials</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $testimonials }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Messages</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $messages }}</p>
        </div>
    </div>

    @if ($setting)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Site Info</h2>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Title:</span>
                    <span class="text-gray-900 dark:text-white ml-2">{{ $setting->site_title ?? 'Not set' }}</span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Hero Image:</span>
                    <span class="text-gray-900 dark:text-white ml-2">{{ $setting->hero_image ? 'Uploaded' : 'Not set' }}</span>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Messages</h2>
            <a href="{{ route('admin.contact-messages.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">View all</a>
        </div>
        @if ($latestMessages->count())
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($latestMessages as $msg)
                    <div class="py-3 flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $msg->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $msg->email }} &middot; {{ $msg->created_at->diffForHumans() }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1 line-clamp-1">{{ $msg->subject ?: '(no subject)' }}</p>
                        </div>
                        <a href="{{ route('admin.contact-messages.show', $msg) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline shrink-0 ml-4">View</a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 dark:text-gray-400">No messages yet.</p>
        @endif
    </div>
@endsection
