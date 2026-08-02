<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PublicSupportController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with('messages')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'open' => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
            'total' => SupportTicket::count(),
        ];

        return Inertia::render('portal/Support', [
            'tickets' => $tickets,
            'stats' => $stats,
            'settings' => Setting::get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required|in:bug,feature_request,billing,complaint,inquiry,other',
        ]);

        $validated['ticket_number'] = SupportTicket::generateTicketNumber();
        $validated['channel'] = 'web';
        $validated['status'] = 'open';

        $ticket = SupportTicket::create($validated);

        $ticket->messages()->create([
            'sender_name' => $validated['name'],
            'sender_email' => $validated['email'],
            'message' => $validated['description'],
            'is_admin' => false,
        ]);

        return back()->with('success', "Ticket {$ticket->ticket_number} created successfully.");
    }

    public function reply(Request $request, int $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $ticket->messages()->create([
            'sender_name' => $ticket->name,
            'sender_email' => $ticket->email,
            'message' => $validated['message'],
            'is_admin' => false,
        ]);

        $ticket->touch();

        return back()->with('success', 'Reply sent.');
    }
}
