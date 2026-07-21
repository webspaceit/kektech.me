@extends('admin.layouts.app')

@section('title', 'Message from ' . $contactMessage->name)

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Message Details</h1>
        <a href="{{ route('admin.contact-messages.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Back to Messages</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 max-w-2xl">
        <dl class="space-y-4">
            <div>
                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $contactMessage->name }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                    <a href="mailto:{{ $contactMessage->email }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $contactMessage->email }}</a>
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subject</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $contactMessage->subject ?: '(no subject)' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Received</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $contactMessage->created_at->format('F j, Y \a\t g:i A') }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Message</dt>
                <dd class="mt-2 text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $contactMessage->message }}</dd>
            </div>
        </dl>

        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex gap-3">
            <a href="mailto:{{ $contactMessage->email }}?subject=Re: {{ $contactMessage->subject }}"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition">Reply via Email</a>
            <form method="POST" action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" onsubmit="return confirm('Delete this message?')">
                @csrf @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition">Delete</button>
            </form>
        </div>
    </div>
@endsection
