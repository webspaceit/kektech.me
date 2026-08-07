@extends('admin.layouts.app')

@section('title', $project->exists ? 'Edit Project' : 'New Project')

@section('content')
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ $project->exists ? 'Edit Project' : 'New Project' }}</h1>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 max-w-2xl">
        <form method="POST" action="{{ $project->exists ? route('admin.projects.update', $project) : route('admin.projects.store') }}" enctype="multipart/form-data">
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
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Media (Images & Videos)</label>

                @if($project->exists && $project->media->count() > 0)
                    <div id="existing-media" class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-3">
                        @foreach($project->media->sortBy('sort_order') as $media)
                            <div class="relative group rounded-lg overflow-hidden border border-gray-200 dark:border-gray-600" data-id="{{ $media->id }}">
                                @if($media->type === 'video')
                                    <video src="{{ $media->url }}" class="w-full h-32 object-cover" muted></video>
                                    <div class="absolute top-1 left-1 px-1.5 py-0.5 text-[10px] font-medium bg-black/70 text-white rounded">VIDEO</div>
                                @else
                                    <img src="{{ $media->url }}" alt="{{ $media->caption }}" class="w-full h-32 object-cover">
                                @endif
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors flex items-center justify-center opacity-0 group-hover:opacity-100 gap-2">
                                    <a href="{{ $media->url }}" target="_blank" class="p-1.5 bg-white/20 rounded-full hover:bg-white/40 text-white" title="View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.projects.media.destroy', $media) }}" method="POST" class="inline" onsubmit="return confirm('Delete this media?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-red-500/80 rounded-full hover:bg-red-600 text-white" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                                <div class="absolute bottom-0 left-0 right-0 p-1 bg-gradient-to-t from-black/60 to-transparent">
                                    <input type="text" name="media_captions[{{ $media->id }}]" value="{{ $media->caption }}" placeholder="Caption..."
                                        class="w-full text-[10px] px-1.5 py-0.5 bg-white/10 text-white rounded border-0 placeholder-white/50 focus:ring-1 focus:ring-emerald-500">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="flex items-center justify-center w-full">
                    <label for="media_files" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex flex-col items-center justify-center pt-3 pb-2">
                            <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="text-xs text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> images or videos</p>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">PNG, JPG, GIF, WebP, MP4, WebM (max 10MB)</p>
                        </div>
                        <input id="media_files" name="media_files[]" type="file" class="hidden" multiple accept="image/*,video/*">
                    </label>
                </div>
                <div id="file-preview" class="mt-2 space-y-1"></div>
                @error('media_files') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
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

    <script>
        document.getElementById('media_files').addEventListener('change', function(e) {
            const preview = document.getElementById('file-preview');
            preview.innerHTML = '';
            Array.from(e.target.files).forEach(function(file) {
                const div = document.createElement('div');
                div.className = 'flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400';
                const icon = file.type.startsWith('video/') ? '&#127909;' : '&#128247;';
                const size = (file.size / 1024 / 1024).toFixed(2);
                div.innerHTML = icon + ' ' + file.name + ' <span class="text-gray-400">(' + size + ' MB)</span>';
                preview.appendChild(div);
            });
        });
    </script>
@endsection
