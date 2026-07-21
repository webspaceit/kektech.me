<x-mail::message>
# New Contact Message

You received a new message from your portfolio contact form.

<x-mail::table>
| Field | Value |
|-------------|----------------------|
| **Name** | {{ $message->name }} |
| **Email** | {{ $message->email }} |
| **Subject** | {{ $message->subject ?: '(no subject)' }} |
| **Date** | {{ $message->created_at->format('M d, Y h:i A') }} |
</x-mail::table>

## Message

{{ $message->message }}

<x-mail::button :url="route('admin.contact-messages.show', $message)">
View Message
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
