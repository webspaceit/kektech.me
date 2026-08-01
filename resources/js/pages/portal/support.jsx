import PublicLayout from '../../components/PublicLayout';
import { usePage } from '@inertiajs/react';

export default function Support({ tickets, stats, parcels }) {
    const { settings } = usePage().props;

    return (
        <PublicLayout settings={settings}>
            <section className="py-20">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 className="text-3xl font-bold text-white mb-2">Support</h1>
                    <p className="text-gray-400 mb-10">Manage your support tickets.</p>

                    <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
                        <div className="rounded-xl border border-white/10 bg-white/5 p-5">
                            <p className="text-sm text-gray-400">Open</p>
                            <p className="text-3xl font-bold text-white mt-1">{stats?.open || 0}</p>
                        </div>
                        <div className="rounded-xl border border-white/10 bg-white/5 p-5">
                            <p className="text-sm text-gray-400">In Progress</p>
                            <p className="text-3xl font-bold text-white mt-1">{stats?.in_progress || 0}</p>
                        </div>
                        <div className="rounded-xl border border-white/10 bg-white/5 p-5">
                            <p className="text-sm text-gray-400">Resolved</p>
                            <p className="text-3xl font-bold text-white mt-1">{stats?.resolved || 0}</p>
                        </div>
                        <div className="rounded-xl border border-white/10 bg-white/5 p-5">
                            <p className="text-sm text-gray-400">Urgent</p>
                            <p className="text-3xl font-bold text-red-400 mt-1">{stats?.urgent || 0}</p>
                        </div>
                    </div>

                    {tickets?.length > 0 ? (
                        <div className="space-y-4">
                            {tickets.map((ticket) => (
                                <div key={ticket.id} className="rounded-xl border border-white/10 bg-white/5 p-6">
                                    <div className="flex items-start justify-between">
                                        <div>
                                            <h3 className="text-lg font-semibold text-white">{ticket.subject}</h3>
                                            <p className="text-sm text-gray-400 mt-1">{ticket.description}</p>
                                        </div>
                                        <span className={`px-2 py-0.5 text-xs rounded-full ${
                                            ticket.status === 'open' ? 'bg-green-600/20 text-green-400' :
                                            ticket.status === 'resolved' ? 'bg-blue-600/20 text-blue-400' :
                                            'bg-yellow-600/20 text-yellow-400'
                                        }`}>
                                            {ticket.status?.replace('_', ' ')}
                                        </span>
                                    </div>
                                    <div className="mt-3 flex items-center gap-4 text-xs text-gray-500">
                                        <span>#{ticket.ticket_number}</span>
                                        <span className={`px-2 py-0.5 rounded ${
                                            ticket.priority === 'urgent' ? 'bg-red-600/20 text-red-400' : 'bg-white/10 text-gray-400'
                                        }`}>{ticket.priority}</span>
                                        <span>{new Date(ticket.created_at).toLocaleDateString()}</span>
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <p className="text-gray-500">No support tickets yet.</p>
                    )}
                </div>
            </section>
        </PublicLayout>
    );
}
