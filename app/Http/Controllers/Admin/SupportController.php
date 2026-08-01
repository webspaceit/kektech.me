<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SupportController extends Controller
{
    public function index()
    {
        $tickets = DB::table('support_tickets')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($t) => (array) $t);

        $stats = [
            'open' => DB::table('support_tickets')->where('status', 'open')->count(),
            'in_progress' => DB::table('support_tickets')->where('status', 'in_progress')->count(),
            'resolved' => DB::table('support_tickets')->where('status', 'resolved')->count(),
            'urgent' => DB::table('support_tickets')->where('priority', 'urgent')->count(),
        ];

        return Inertia::render('admin/Support', [
            'tickets' => $tickets,
            'stats' => $stats,
        ]);
    }

    public function updateStatus(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        DB::table('support_tickets')
            ->where('id', $id)
            ->update(['status' => $validated['status'], 'updated_at' => now()]);

        return back();
    }

    public function destroy(int $id)
    {
        DB::table('support_tickets')->where('id', $id)->delete();
        return back();
    }
}
