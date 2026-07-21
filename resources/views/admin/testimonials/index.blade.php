@extends('admin.layouts.app')

@section('title', 'Testimonials')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Testimonials</h1>
        <a href="{{ route('admin.testimonials.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition">New Testimonial</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <th class="text-left px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Name</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Role</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Rating</th>
                    <th class="text-right px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($testimonials as $testimonial)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-4 py-3 text-gray-900 dark:text-white font-medium">{{ $testimonial->name }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $testimonial->role ? $testimonial->role . ($testimonial->company ? ' at ' . $testimonial->company : '') : '-' }}</td>
                        <td class="px-4 py-3">
                            @if ($testimonial->rating)
                                <span class="text-yellow-500">{{ str_repeat('★', $testimonial->rating) }}{{ str_repeat('☆', 5 - $testimonial->rating) }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm mr-3">Edit</a>
                            <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" class="inline" onsubmit="return confirm('Delete this testimonial?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No testimonials found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($testimonials->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $testimonials->links() }}
            </div>
        @endif
    </div>
@endsection
