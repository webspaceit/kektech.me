import { usePage, useForm, router } from '@inertiajs/react';
import { useState } from 'react';

const STATUS_COLORS = {
    open: 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
    in_progress: 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400',
    waiting_customer: 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400',
    resolved: 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400',
    closed: 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400',
};

const PRIORITY_COLORS = {
    low: 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400',
    medium: 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
    high: 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400',
    urgent: 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
};

export default function Support({ tickets, stats }) {
    const [expandedTicket, setExpandedTicket] = useState(null);

    const handleStatusChange = (id, status) => {
        router.post(`/wsdashboard/support/${id}/status`, { status }, { preserveScroll: true });
    };

    const handleDelete = (id) => {
        if (!confirm('Delete this ticket?')) return;
        router.delete(`/wsdashboard/support/${id}`, { preserveScroll: true });
    };

    return (
        <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 className="text-2xl font-bold text-gray-900 dark:text-white mb-2">Support Tickets</h1>
            <p className="text-sm text-gray-500 dark:text-gray-400 mb-8">Manage incoming support requests from the website.</p>

            <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div className="rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-5">
                    <p className="text-sm text-gray-500 dark:text-gray-400">Open</p>
                    <p className="text-3xl font-bold text-gray-900 dark:text-white mt-1">{stats?.open || 0}</p>
                </div>
                <div className="rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-5">
                    <p className="text-sm text-gray-500 dark:text-gray-400">In Progress</p>
                    <p className="text-3xl font-bold text-gray-900 dark:text-white mt-1">{stats?.in_progress || 0}</p>
                </div>
                <div className="rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-5">
                    <p className="text-sm text-gray-500 dark:text-gray-400">Resolved</p>
                    <p className="text-3xl font-bold text-gray-900 dark:text-white mt-1">{stats?.resolved || 0}</p>
                </div>
                <div className="rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-5">
                    <p className="text-sm text-gray-500 dark:text-gray-400">Total</p>
                    <p className="text-3xl font-bold text-gray-900 dark:text-white mt-1">{stats?.total || 0}</p>
                </div>
            </div>

            {tickets?.length > 0 ? (
                <div className="space-y-4">
                    {tickets.map((ticket) => {
                        const isExpanded = expandedTicket === ticket.id;
                        return (
                            <div
                                key={ticket.id}
                                className="rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 cursor-pointer hover:bg-gray-50 dark:hover:bg-white/10 transition-all"
                                onClick={() => setExpandedTicket(isExpanded ? null : ticket.id)}
                            >
                                <div className="flex items-start justify-between">
                                    <div className="flex-1">
                                        <div className="flex items-center gap-2 mb-2">
                                            <span className="font-mono text-xs text-gray-500">{ticket.ticket_number}</span>
                                            <span className={`px-2 py-0.5 rounded-full text-xs font-semibold ${STATUS_COLORS[ticket.status] || STATUS_COLORS.open}`}>
                                                {ticket.status?.replace('_', ' ')}
                                            </span>
                                            <span className={`px-2 py-0.5 rounded text-xs font-medium ${PRIORITY_COLORS[ticket.priority] || ''}`}>
                                                {ticket.priority}
                                            </span>
                                            <span className="px-2 py-0.5 rounded text-xs text-gray-500 bg-gray-100 dark:bg-gray-700">
                                                {ticket.category?.replace('_', ' ')}
                                            </span>
                                        </div>
                                        <h3 className="text-lg font-semibold text-gray-900 dark:text-white">{ticket.subject}</h3>
                                        <div className="flex items-center gap-3 mt-1 text-xs text-gray-500">
                                            <span>{ticket.name}</span>
                                            <span>{ticket.email}</span>
                                            <span>{new Date(ticket.created_at).toLocaleDateString()}</span>
                                            {ticket.messages && <span>{ticket.messages.length} messages</span>}
                                        </div>
                                    </div>
                                    <div className="flex items-center gap-2 ml-4" onClick={e => e.stopPropagation()}>
                                        <select
                                            value={ticket.status}
                                            onChange={(e) => handleStatusChange(ticket.id, e.target.value)}
                                            className="text-xs border border-gray-300 dark:border-gray-600 rounded-md px-2 py-1 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300"
                                        >
                                            <option value="open">Open</option>
                                            <option value="in_progress">In Progress</option>
                                            <option value="waiting_customer">Waiting (Customer)</option>
                                            <option value="resolved">Resolved</option>
                                            <option value="closed">Closed</option>
                                        </select>
                                        <button
                                            onClick={() => handleDelete(ticket.id)}
                                            className="text-xs text-red-500 hover:text-red-700 px-2 py-1 rounded hover:bg-red-50 dark:hover:bg-red-600/10"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </div>

                                {isExpanded && (
                                    <div className="mt-4 pt-4 border-t border-gray-200 dark:border-white/10" onClick={e => e.stopPropagation()}>
                                        <p className="text-sm text-gray-600 dark:text-gray-400 mb-4">{ticket.description}</p>

                                        {ticket.messages?.length > 0 && (
                                            <div className="space-y-3 mb-4">
                                                {ticket.messages.map(msg => (
                                                    <div key={msg.id} className={`flex ${msg.is_admin ? 'justify-start' : 'justify-end'}`}>
                                                        <div className={`max-w-[80%] rounded-lg px-4 py-2 ${msg.is_admin ? 'bg-emerald-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white'}`}>
                                                            <div className="flex items-center gap-2 mb-1">
                                                                <span className={`text-xs font-medium ${msg.is_admin ? 'text-emerald-200' : 'text-emerald-600 dark:text-emerald-400'}`}>{msg.sender_name}</span>
                                                                <span className={`text-xs ${msg.is_admin ? 'text-emerald-200/60' : 'text-gray-500'}`}>{new Date(msg.created_at).toLocaleString()}</span>
                                                            </div>
                                                            <p className="text-sm">{msg.message}</p>
                                                        </div>
                                                    </div>
                                                ))}
                                            </div>
                                        )}

                                        {(ticket.status !== 'closed' && ticket.status !== 'resolved') && (
                                            <AdminReply ticketId={ticket.id} />
                                        )}
                                    </div>
                                )}
                            </div>
                        );
                    })}
                </div>
            ) : (
                <p className="text-gray-500 dark:text-gray-400">No support tickets yet.</p>
            )}
        </div>
    );
}

function AdminReply({ ticketId }) {
    const { data, setData, post, processing, reset } = useForm({ message: '' });

    const handleReply = (e) => {
        e.preventDefault();
        post(`/wsdashboard/support/${ticketId}/reply`, {
            onSuccess: () => reset(),
        });
    };

    return (
        <form onSubmit={handleReply} className="flex gap-2">
            <input
                type="text"
                value={data.message}
                onChange={e => setData('message', e.target.value)}
                placeholder="Type a reply..."
                className="flex-1 px-4 py-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white placeholder-gray-400 text-sm"
                required
            />
            <button
                type="submit"
                disabled={processing || !data.message.trim()}
                className="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-500 disabled:opacity-50 transition-colors text-sm font-medium"
            >
                Send
            </button>
        </form>
    );
}
