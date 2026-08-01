@extends('admin.layouts.app')

@section('title', 'Projects')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Projects</h1>
        <a href="{{ route('admin.projects.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition">New Project</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <th class="text-left px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Title</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Status</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Featured</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-700 dark:text-gray-300">URLs</th>
                    <th class="text-right px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($projects as $project)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-4 py-3 text-gray-900 dark:text-white font-medium">{{ $project->title }}</td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('admin.projects.toggle', $project) }}" class="inline">
                                @csrf
                                @if ($project->is_active)
                                    <button type="submit" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 cursor-pointer hover:bg-green-200 dark:hover:bg-green-900/50 transition">Active</button>
                                @else
                                    <button type="submit" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 cursor-pointer hover:bg-red-200 dark:hover:bg-red-900/50 transition">Inactive</button>
                                @endif
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            @if ($project->featured)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Yes</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">No</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500 dark:text-gray-400">
                            @if ($project->live_url)<a href="{{ $project->live_url }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline mr-2">Live</a>@endif
                            @if ($project->github_url)<a href="{{ $project->github_url }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline">GitHub</a>@endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.projects.edit', $project) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm mr-3">Edit</a>
                            <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" class="inline" onsubmit="return confirm('Delete this project?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No projects found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($projects->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $projects->links() }}
            </div>
        @endif
    </div>
@endsection
