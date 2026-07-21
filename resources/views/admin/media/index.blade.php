@extends('admin.layouts.app')

@section('title', 'Media Library')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Media Library</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Upload File</h2>
        <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="flex items-end gap-3">
                <div class="flex-1">
                    <input type="file" name="file" required
                        class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 dark:file:bg-indigo-900/30 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/50">
                    @error('file') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition shrink-0">Upload</button>
            </div>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max 10MB. Allowed: jpg, jpeg, png, gif, webp, svg, pdf, doc, docx</p>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        @if (count($files))
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 p-4">
                @foreach ($files as $file)
                    <div class="group relative bg-gray-50 dark:bg-gray-700/50 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                        <div class="aspect-square flex items-center justify-center p-2">
                            @if (in_array(pathinfo($file['name'], PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" class="max-w-full max-h-full object-contain">
                            @else
                                <div class="flex flex-col items-center text-gray-400">
                                    <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    <span class="text-xs">{{ strtoupper(pathinfo($file['name'], PATHINFO_EXTENSION)) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-2 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-xs text-gray-600 dark:text-gray-400 truncate" title="{{ $file['name'] }}">{{ $file['name'] }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ number_format($file['size'] / 1024, 1) }} KB</p>
                        </div>
                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition">
                            <a href="{{ $file['url'] }}" target="_blank"
                                class="px-2 py-1 bg-white text-gray-800 text-xs rounded hover:bg-gray-100 transition">View</a>
                            <form method="POST" action="{{ route('admin.media.destroy', $file['name']) }}" onsubmit="return confirm('Delete this file?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-2 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700 transition">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p>No files uploaded yet.</p>
            </div>
        @endif
    </div>
@endsection
