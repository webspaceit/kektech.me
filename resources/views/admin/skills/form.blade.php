@extends('admin.layouts.app')

@section('title', $skill->exists ? 'Edit Skill' : 'New Skill')

@section('content')
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ $skill->exists ? 'Edit Skill' : 'New Skill' }}</h1>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 max-w-lg">
        <form method="POST" action="{{ $skill->exists ? route('admin.skills.update', $skill) : route('admin.skills.store') }}">
            @csrf
            @if ($skill->exists) @method('PUT') @endif

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $skill->name) }}" required
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                @error('name') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                <input type="text" id="category" name="category" value="{{ old('category', $skill->category) }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                @error('category') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="icon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Icon (class or path)</label>
                <input type="text" id="icon" name="icon" value="{{ old('icon', $skill->icon) }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                @error('icon') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label for="level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Level (0-100)</label>
                <input type="number" id="level" name="level" value="{{ old('level', $skill->level) }}" min="0" max="100"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                @error('level') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition">
                    {{ $skill->exists ? 'Update Skill' : 'Create Skill' }}
                </button>
                <a href="{{ route('admin.skills.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
@endsection
