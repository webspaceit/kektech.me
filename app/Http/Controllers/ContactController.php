<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        return Inertia::render('Contact', [
            'settings' => Setting::get(),
            'seo' => [
                'title' => 'Contact',
                'description' => 'Get in touch for project inquiries, collaborations, or just to say hello.',
                'type' => 'website',
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $contactMessage = ContactMessage::create($data);

        $setting = Setting::get();
        if ($setting->email_notifications && $setting->admin_email) {
            Mail::to($setting->admin_email)->send(new ContactMessageReceived($contactMessage));
        }

        return back()->with('success', 'Message sent successfully!');
    }
}
