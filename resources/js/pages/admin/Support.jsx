import { usePage, useForm, router } from '@inertiajs/react';

export default function Support({ tickets, stats }) {
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
            <p className="text-sm text-gray-500 dark:text-gray-400 mb-8">Manage incoming support requests.</p>

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
                    <p className="text-sm text-gray-500 dark:text-gray-400">Urgent</p>
                    <p className="text-3xl font-bold text-red-500 mt-1">{stats?.urgent || 0}</p>
                </div>
            </div>

            {tickets?.length > 0 ? (
                <div className="space-y-4">
                    {tickets.map((ticket) => (
                        <div key={ticket.id} className="rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                            <div className="flex items-start justify-between">
                                <div className="flex-1">
                                    <div className="flex items-center gap-3">
                                        <h3 className="text-lg font-semibold text-gray-900 dark:text-white">{ticket.subject}</h3>
                                        <span className={`px-2 py-0.5 text-xs rounded-full ${
                                            ticket.status === 'open' ? 'bg-green-100 dark:bg-green-600/20 text-green-700 dark:text-green-400' :
                                            ticket.status === 'resolved' ? 'bg-blue-100 dark:bg-blue-600/20 text-blue-700 dark:text-blue-400' :
                                            ticket.status === 'closed' ? 'bg-gray-100 dark:bg-gray-600/20 text-gray-600 dark:text-gray-400' :
                                            'bg-yellow-100 dark:bg-yellow-600/20 text-yellow-700 dark:text-yellow-400'
                                        }`}>
                                            {ticket.status?.replace('_', ' ')}
                                        </span>
                                        <span className={`px-2 py-0.5 text-xs rounded ${
                                            ticket.priority === 'urgent' ? 'bg-red-100 dark:bg-red-600/20 text-red-700 dark:text-red-400' :
                                            'bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-gray-400'
                                        }`}>{ticket.priority}</span>
                                    </div>
                                    <p className="text-sm text-gray-600 dark:text-gray-400 mt-1">{ticket.message}</p>
                                    <div className="mt-3 flex items-center gap-4 text-xs text-gray-500">
                                        <span>#{ticket.id}</span>
                                        {ticket.name && <span>{ticket.name}</span>}
                                        {ticket.email && <span>{ticket.email}</span>}
                                        <span>{new Date(ticket.created_at).toLocaleDateString()}</span>
                                    </div>
                                </div>
                                <div className="flex items-center gap-2 ml-4">
                                    <select
                                        value={ticket.status}
                                        onChange={(e) => handleStatusChange(ticket.id, e.target.value)}
                                        className="text-xs border border-gray-300 dark:border-gray-600 rounded-md px-2 py-1 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300"
                                    >
                                        <option value="open">Open</option>
                                        <option value="in_progress">In Progress</option>
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
                        </div>
                    ))}
                </div>
            ) : (
                <p className="text-gray-500 dark:text-gray-400">No support tickets yet.</p>
            )}
        </div>
    );
}
