<?php

namespace App\Support\Http\Controllers;

use App\Support\Models\SupportTicket;
use App\Support\Models\SupportTicketRead;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MerchantSupportController extends Controller
{
    public function index(): Response
    {
        $userId = auth()->id();
        $email = auth()->user()->email;

        $readTimes = SupportTicketRead::where('user_id', $userId)
            ->pluck('read_at', 'ticket_id');

        $tickets = SupportTicket::with(['parcel', 'messages.user'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($ticket) use ($readTimes) {
                $lastRead = $readTimes[$ticket->id] ?? null;
                $ticket->has_unread = $lastRead === null || $ticket->updated_at > $lastRead;
                return $ticket;
            });

        $stats = [
            'open' => SupportTicket::where('user_id', $userId)->where('status', 'open')->count(),
            'inProgress' => SupportTicket::where('user_id', $userId)->where('status', 'in_progress')->count(),
            'waiting' => SupportTicket::where('user_id', $userId)->whereIn('status', ['waiting_customer', 'waiting_internal'])->count(),
            'resolved' => SupportTicket::where('user_id', $userId)->where('status', 'resolved')->count(),
            'total' => SupportTicket::where('user_id', $userId)->count(),
            'urgent' => SupportTicket::where('user_id', $userId)->where('priority', 'urgent')->where('status', '!=', 'closed')->count(),
        ];

        $parcels = \App\Models\Parcel::where('sender_email', $email)
            ->orWhere('receiver_email', $email)
            ->select('id', 'tracking_number')
            ->get();

        return Inertia::render('portal/support', [
            'tickets' => $tickets,
            'stats' => $stats,
            'parcels' => $parcels,
        ]);
    }
}
