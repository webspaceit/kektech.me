@extends('admin.layouts.app')

@section('title', 'Contact Messages')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Contact Messages</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <th class="text-left px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Name</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Email</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Subject</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Date</th>
                    <th class="text-right px-4 py-3 font-medium text-gray-700 dark:text-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($messages as $message)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-4 py-3 text-gray-900 dark:text-white font-medium">{{ $message->name }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $message->email }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400 max-w-xs truncate">{{ $message->subject ?: '(no subject)' }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500 dark:text-gray-400">{{ $message->created_at->format('M j, Y g:i A') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.contact-messages.show', $message) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm mr-3">View</a>
                            <form method="POST" action="{{ route('admin.contact-messages.destroy', $message) }}" class="inline" onsubmit="return confirm('Delete this message?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No messages found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($messages->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
@endsection
