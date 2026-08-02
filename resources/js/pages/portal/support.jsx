import PublicLayout from '../../components/PublicLayout';
import { usePage, useForm } from '@inertiajs/react';
import { useState } from 'react';
import { HeadphonesIcon, Plus, X, Send, MessageSquare } from 'lucide-react';

export default function Support({ tickets, stats }) {
    const { settings } = usePage().props;
    const [showForm, setShowForm] = useState(false);
    const [expandedTicket, setExpandedTicket] = useState(null);

    const { data, setData, post, processing, reset, errors } = useForm({
        name: '',
        email: '',
        subject: '',
        description: '',
        priority: 'medium',
        category: 'other',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post('/support', {
            onSuccess: () => {
                reset();
                setShowForm(false);
            },
        });
    };

    const STATUS_COLORS = {
        open: 'bg-blue-100 text-blue-700',
        in_progress: 'bg-amber-100 text-amber-700',
        waiting_customer: 'bg-orange-100 text-orange-700',
        resolved: 'bg-emerald-100 text-emerald-700',
        closed: 'bg-gray-100 text-gray-500',
    };

    const PRIORITY_COLORS = {
        low: 'bg-gray-100 text-gray-600',
        medium: 'bg-blue-100 text-blue-700',
        high: 'bg-orange-100 text-orange-700',
        urgent: 'bg-red-100 text-red-700',
    };

    return (
        <PublicLayout settings={settings}>
            <section className="py-20">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex items-center justify-between mb-8">
                        <div>
                            <h1 className="text-3xl font-bold text-white mb-2">Support</h1>
                            <p className="text-gray-400">Need help? Submit a ticket and we'll get back to you.</p>
                        </div>
                        <button
                            onClick={() => setShowForm(!showForm)}
                            className="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-500 transition-colors font-medium text-sm"
                        >
                            {showForm ? <X className="w-4 h-4" /> : <Plus className="w-4 h-4" />}
                            {showForm ? 'Cancel' : 'New Ticket'}
                        </button>
                    </div>

                    {/* Stats */}
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                        <div className="rounded-xl border border-white/10 bg-white/5 p-4 text-center">
                            <p className="text-2xl font-bold text-white">{stats?.open || 0}</p>
                            <p className="text-xs text-gray-400">Open</p>
                        </div>
                        <div className="rounded-xl border border-white/10 bg-white/5 p-4 text-center">
                            <p className="text-2xl font-bold text-white">{stats?.in_progress || 0}</p>
                            <p className="text-xs text-gray-400">In Progress</p>
                        </div>
                        <div className="rounded-xl border border-white/10 bg-white/5 p-4 text-center">
                            <p className="text-2xl font-bold text-white">{stats?.resolved || 0}</p>
                            <p className="text-xs text-gray-400">Resolved</p>
                        </div>
                        <div className="rounded-xl border border-white/10 bg-white/5 p-4 text-center">
                            <p className="text-2xl font-bold text-white">{stats?.total || 0}</p>
                            <p className="text-xs text-gray-400">Total</p>
                        </div>
                    </div>

                    {/* New Ticket Form */}
                    {showForm && (
                        <div className="rounded-xl border border-white/10 bg-white/5 p-6 mb-8">
                            <h2 className="text-lg font-semibold text-white mb-4">Create Support Ticket</h2>
                            <form onSubmit={handleSubmit} className="space-y-4">
                                <div className="grid sm:grid-cols-2 gap-4">
                                    <div>
                                        <label className="block text-sm font-medium text-gray-300 mb-1">Name</label>
                                        <input
                                            type="text"
                                            value={data.name}
                                            onChange={e => setData('name', e.target.value)}
                                            className="w-full px-4 py-2.5 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition-colors"
                                            placeholder="Your name"
                                            required
                                        />
                                        {errors.name && <p className="mt-1 text-xs text-red-400">{errors.name}</p>}
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-300 mb-1">Email</label>
                                        <input
                                            type="email"
                                            value={data.email}
                                            onChange={e => setData('email', e.target.value)}
                                            className="w-full px-4 py-2.5 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition-colors"
                                            placeholder="you@example.com"
                                            required
                                        />
                                        {errors.email && <p className="mt-1 text-xs text-red-400">{errors.email}</p>}
                                    </div>
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-300 mb-1">Subject</label>
                                    <input
                                        type="text"
                                        value={data.subject}
                                        onChange={e => setData('subject', e.target.value)}
                                        className="w-full px-4 py-2.5 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition-colors"
                                        placeholder="Brief description of your issue"
                                        required
                                    />
                                    {errors.subject && <p className="mt-1 text-xs text-red-400">{errors.subject}</p>}
                                </div>
                                <div className="grid sm:grid-cols-2 gap-4">
                                    <div>
                                        <label className="block text-sm font-medium text-gray-300 mb-1">Priority</label>
                                        <select
                                            value={data.priority}
                                            onChange={e => setData('priority', e.target.value)}
                                            className="w-full px-4 py-2.5 rounded-lg bg-white/5 border border-white/10 text-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition-colors"
                                        >
                                            <option value="low">Low</option>
                                            <option value="medium">Medium</option>
                                            <option value="high">High</option>
                                            <option value="urgent">Urgent</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-300 mb-1">Category</label>
                                        <select
                                            value={data.category}
                                            onChange={e => setData('category', e.target.value)}
                                            className="w-full px-4 py-2.5 rounded-lg bg-white/5 border border-white/10 text-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition-colors"
                                        >
                                            <option value="bug">Bug Report</option>
                                            <option value="feature_request">Feature Request</option>
                                            <option value="billing">Billing</option>
                                            <option value="complaint">Complaint</option>
                                            <option value="inquiry">Inquiry</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-300 mb-1">Description</label>
                                    <textarea
                                        value={data.description}
                                        onChange={e => setData('description', e.target.value)}
                                        rows={4}
                                        className="w-full px-4 py-2.5 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition-colors resize-none"
                                        placeholder="Describe your issue in detail..."
                                        required
                                    />
                                    {errors.description && <p className="mt-1 text-xs text-red-400">{errors.description}</p>}
                                </div>
                                <div className="flex gap-3">
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-500 disabled:opacity-50 transition-colors font-medium text-sm"
                                    >
                                        <Send className="w-4 h-4" />
                                        {processing ? 'Submitting...' : 'Submit Ticket'}
                                    </button>
                                    <button
                                        type="button"
                                        onClick={() => setShowForm(false)}
                                        className="px-5 py-2.5 border border-white/10 text-gray-300 rounded-lg hover:bg-white/5 transition-colors text-sm"
                                    >
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    )}

                    {/* Tickets List */}
                    <div className="space-y-4">
                        {tickets?.length > 0 ? (
                            tickets.map(ticket => (
                                <div
                                    key={ticket.id}
                                    className="rounded-xl border border-white/10 bg-white/5 p-6 cursor-pointer hover:bg-white/10 transition-all"
                                    onClick={() => setExpandedTicket(expandedTicket === ticket.id ? null : ticket.id)}
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
                                                <span className="px-2 py-0.5 rounded text-xs text-gray-400 bg-white/10">
                                                    {ticket.category?.replace('_', ' ')}
                                                </span>
                                            </div>
                                            <h3 className="text-lg font-semibold text-white">{ticket.subject}</h3>
                                            <div className="flex items-center gap-3 mt-1 text-xs text-gray-500">
                                                <span>{ticket.name}</span>
                                                <span>{ticket.email}</span>
                                                <span>{new Date(ticket.created_at).toLocaleDateString()}</span>
                                                {ticket.messages && <span>{ticket.messages.length} messages</span>}
                                            </div>
                                        </div>
                                    </div>

                                    {expandedTicket === ticket.id && (
                                        <div className="mt-4 pt-4 border-t border-white/10" onClick={e => e.stopPropagation()}>
                                            <p className="text-sm text-gray-400 mb-4">{ticket.description}</p>
                                            {ticket.messages?.length > 0 && (
                                                <div className="space-y-3 mb-4">
                                                    {ticket.messages.map(msg => (
                                                        <div key={msg.id} className={`flex gap-3 ${msg.is_admin ? 'justify-start' : 'justify-end'}`}>
                                                            <div className={`max-w-[80%] rounded-lg px-4 py-2 ${msg.is_admin ? 'bg-emerald-600/20 border border-emerald-500/30' : 'bg-white/10'}`}>
                                                                <div className="flex items-center gap-2 mb-1">
                                                                    <span className="text-xs font-medium text-emerald-400">{msg.sender_name}</span>
                                                                    <span className="text-xs text-gray-500">{new Date(msg.created_at).toLocaleString()}</span>
                                                                </div>
                                                                <p className="text-sm text-gray-300">{msg.message}</p>
                                                            </div>
                                                        </div>
                                                    ))}
                                                </div>
                                            )}
                                            {(ticket.status !== 'closed' && ticket.status !== 'resolved') && (
                                                <TicketReply ticketId={ticket.id} />
                                            )}
                                        </div>
                                    )}
                                </div>
                            ))
                        ) : (
                            <div className="text-center py-16 rounded-xl border border-white/10 bg-white/5">
                                <HeadphonesIcon className="w-12 h-12 text-gray-600 mx-auto mb-4" />
                                <h3 className="text-lg font-semibold text-white mb-1">No support tickets yet</h3>
                                <p className="text-sm text-gray-400">Click "New Ticket" to submit a request.</p>
                            </div>
                        )}
                    </div>
                </div>
            </section>
        </PublicLayout>
    );
}

function TicketReply({ ticketId }) {
    const { data, setData, post, processing, reset } = useForm({ message: '' });

    const handleReply = (e) => {
        e.preventDefault();
        post(`/support/${ticketId}/reply`, {
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
                className="flex-1 px-4 py-2 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none text-sm"
                required
            />
            <button
                type="submit"
                disabled={processing || !data.message.trim()}
                className="px-3 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-500 disabled:opacity-50 transition-colors"
            >
                <Send className="w-4 h-4" />
            </button>
        </form>
    );
}
