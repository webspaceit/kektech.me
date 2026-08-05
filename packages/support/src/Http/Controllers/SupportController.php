<?php

namespace App\Support\Http\Controllers;

use App\Support\Models\SupportTicket;
use App\Support\Models\SupportMessage;
use App\Support\Models\SupportTicketRead;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SupportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parcel_id' => 'nullable|exists:parcels,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'channel' => 'required|in:web,phone,email,chat,social',
            'category' => 'required|in:delivery_issue,damaged_parcel,lost_parcel,billing,complaint,inquiry,other',
        ]);

        $validated['ticket_number'] = SupportTicket::generateTicketNumber();
        $validated['user_id'] = auth()->id();

        $ticket = SupportTicket::create($validated);

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $validated['description'],
        ]);

        return redirect()->to(auth()->user()->is_admin ? route('dashboard', ['tab' => 'support']) : route('support.index'))
            ->with('success', "Ticket {$ticket->ticket_number} created successfully.");
    }

    public function update(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $user = $request->user();
        if (! $user->is_admin && $ticket->user_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'status' => 'sometimes|in:open,in_progress,waiting_customer,waiting_internal,resolved,closed',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'assigned_to' => 'sometimes|nullable|exists:users,id',
        ]);

        if (isset($validated['status'])) {
            if ($validated['status'] === 'resolved') $ticket->resolved_at = now();
            if ($validated['status'] === 'closed') $ticket->closed_at = now();
        }

        $ticket->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'ticket' => $ticket->fresh()->load(['parcel', 'user', 'assignee', 'messages.user'])]);
        }

        return redirect()->to($user->is_admin ? route('dashboard', ['tab' => 'support']) : route('support.index'))
            ->with('success', 'Ticket updated successfully.');
    }

    public function markRead(Request $request, $ticketId)
    {
        $ticket = SupportTicket::findOrFail($ticketId);

        $user = $request->user();
        if (! $user->is_admin && $ticket->user_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        SupportTicketRead::updateOrCreate(
            ['user_id' => $request->user()->id, 'ticket_id' => $ticketId],
            ['read_at' => now()]
        );

        return response()->json(['ok' => true]);
    }

    public function addMessage(Request $request, $ticketId)
    {
        $ticket = SupportTicket::findOrFail($ticketId);

        $user = $request->user();
        if (! $user->is_admin && $ticket->user_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'message' => 'required|string',
            'is_internal_note' => 'sometimes|boolean',
        ]);

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'is_internal_note' => $validated['is_internal_note'] ?? false,
        ]);

        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        } else {
            $ticket->touch();
        }

        $user = $request->user();

        return redirect()->to($user->is_admin ? route('dashboard', ['tab' => 'support']) : route('support.index'))
            ->with('success', 'Message sent.');
    }
}
