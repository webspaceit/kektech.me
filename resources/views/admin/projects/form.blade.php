@extends('admin.layouts.app')

@section('title', $project->exists ? 'Edit Project' : 'New Project')

@section('content')
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ $project->exists ? 'Edit Project' : 'New Project' }}</h1>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 max-w-2xl">
        <form method="POST" action="{{ $project->exists ? route('admin.projects.update', $project) : route('admin.projects.store') }}">
            @csrf
            @if ($project->exists) @method('PUT') @endif

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title', $project->title) }}" required
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                @error('title') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                <textarea id="description" name="description" rows="5"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">{{ old('description', $project->description) }}</textarea>
                @error('description') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="images" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Images (JSON array)</label>
                <input type="text" id="images" name="images" value="{{ old('images', $project->images ? json_encode($project->images) : '') }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm font-mono text-xs">
                @error('images') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="tech_stack" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tech Stack (JSON array)</label>
                <input type="text" id="tech_stack" name="tech_stack" value="{{ old('tech_stack', $project->tech_stack ? json_encode($project->tech_stack) : '') }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm font-mono text-xs">
                @error('tech_stack') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="live_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Live URL</label>
                    <input type="url" id="live_url" name="live_url" value="{{ old('live_url', $project->live_url) }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                </div>
                <div class="mb-4">
                    <label for="github_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">GitHub URL</label>
                    <input type="url" id="github_url" name="github_url" value="{{ old('github_url', $project->github_url) }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                </div>
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="featured" value="1" {{ old('featured', $project->featured) ? 'checked' : '' }}
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Featured</span>
                </label>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition">
                    {{ $project->exists ? 'Update Project' : 'Create Project' }}
                </button>
                <a href="{{ route('admin.projects.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
@endsection
