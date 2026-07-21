@extends('admin.layouts.app')

@section('title', $testimonial->exists ? 'Edit Testimonial' : 'New Testimonial')

@section('content')
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ $testimonial->exists ? 'Edit Testimonial' : 'New Testimonial' }}</h1>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 max-w-lg">
        <form method="POST" action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}">
            @csrf
            @if ($testimonial->exists) @method('PUT') @endif

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $testimonial->name) }}" required
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                @error('name') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role</label>
                    <input type="text" id="role" name="role" value="{{ old('role', $testimonial->role) }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                </div>
                <div class="mb-4">
                    <label for="company" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company</label>
                    <input type="text" id="company" name="company" value="{{ old('company', $testimonial->company) }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                </div>
            </div>

            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Content</label>
                <textarea id="content" name="content" rows="5" required
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">{{ old('content', $testimonial->content) }}</textarea>
                @error('content') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="avatar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Avatar URL</label>
                <input type="text" id="avatar" name="avatar" value="{{ old('avatar', $testimonial->avatar) }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                @error('avatar') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label for="rating" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rating (1-5)</label>
                <input type="number" id="rating" name="rating" value="{{ old('rating', $testimonial->rating) }}" min="1" max="5"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                @error('rating') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition">
                    {{ $testimonial->exists ? 'Update Testimonial' : 'Create Testimonial' }}
                </button>
                <a href="{{ route('admin.testimonials.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
@endsection
