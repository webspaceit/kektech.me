<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupportController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with('messages')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'open' => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'waiting_customer' => SupportTicket::where('status', 'waiting_customer')->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
            'closed' => SupportTicket::where('status', 'closed')->count(),
            'total' => SupportTicket::count(),
        ];

        return Inertia::render('admin/Support', [
            'tickets' => $tickets,
            'stats' => $stats,
        ]);
    }

    public function show(int $id)
    {
        $ticket = SupportTicket::with('messages')->findOrFail($id);

        return Inertia::render('admin/SupportShow', [
            'ticket' => $ticket,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $validated = $request->validate([
            'status' => 'sometimes|in:open,in_progress,waiting_customer,resolved,closed',
            'priority' => 'sometimes|in:low,medium,high,urgent',
        ]);

        if (isset($validated['status'])) {
            if ($validated['status'] === 'resolved') $ticket->resolved_at = now();
            if ($validated['status'] === 'closed') $ticket->closed_at = now();
        }

        $ticket->update($validated);

        return back()->with('success', 'Ticket updated.');
    }

    public function reply(Request $request, int $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $ticket->messages()->create([
            'sender_name' => auth()->user()->name ?? 'Admin',
            'sender_email' => auth()->user()->email ?? null,
            'message' => $validated['message'],
            'is_admin' => true,
        ]);

        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        $ticket->touch();

        return back()->with('success', 'Reply sent.');
    }

    public function destroy(int $id)
    {
        SupportTicket::findOrFail($id)->delete();
        return back()->with('success', 'Ticket deleted.');
    }
}
