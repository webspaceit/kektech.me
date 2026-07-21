<?php

namespace App\Mail;

use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactMessage $message,
    ) {}

    public function envelope(): Envelope
    {
        $setting = Setting::get();
        $subject = $setting->contact_email_subject
            ? str_replace('{name}', $this->message->name, $setting->contact_email_subject)
            : ($this->message->subject ?: 'New Contact Message from ' . $this->message->name);

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact-message-received',
        );
    }
}
