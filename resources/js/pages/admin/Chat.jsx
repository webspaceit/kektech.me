import { useState, useEffect, useRef } from 'react';
import { usePage } from '@inertiajs/react';
import AdminLayout from '../../components/AdminLayout';

export default function Chat({ rooms: initialRooms }) {
    const { auth } = usePage().props;
    const [rooms] = useState(initialRooms);
    const [selectedRoom, setSelectedRoom] = useState(null);
    const [messages, setMessages] = useState([]);
    const [newMessage, setNewMessage] = useState('');
    const [loading, setLoading] = useState(false);
    const messagesEnd = useRef(null);

    useEffect(() => {
        if (selectedRoom) {
            setLoading(true);
            fetch(`/wsdashboard/chat/${selectedRoom.id}/messages`)
                .then(res => res.json())
                .then(data => {
                    setMessages(data);
                    setLoading(false);
                });
        }
    }, [selectedRoom]);

    useEffect(() => {
        messagesEnd.current?.scrollIntoView({ behavior: 'smooth' });
    }, [messages]);

    const sendMessage = async (e) => {
        e.preventDefault();
        if (!newMessage.trim() || !selectedRoom) return;

        const res = await fetch(`/wsdashboard/chat/${selectedRoom.id}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'Accept': 'application/json',
            },
            body: JSON.stringify({ message: newMessage }),
        });

        if (res.ok) {
            const msg = await res.json();
            setMessages(prev => [...prev, msg]);
            setNewMessage('');
        }
    };

    return (
        <AdminLayout title="Live Chat">
            <div className="flex h-[calc(100vh-6rem)] rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden bg-white dark:bg-gray-800">
            {/* Room List */}
            <div className="w-80 border-r border-gray-200 dark:border-gray-700 overflow-y-auto">
                <div className="p-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 className="text-lg font-semibold text-gray-900 dark:text-white">Chat Rooms</h2>
                </div>
                {rooms.length === 0 ? (
                    <p className="p-4 text-sm text-gray-500">No chat rooms.</p>
                ) : (
                    rooms.map(room => (
                        <button
                            key={room.id}
                            onClick={() => setSelectedRoom(room)}
                            className={`w-full text-left p-4 border-b border-gray-100 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition ${selectedRoom?.id === room.id ? 'bg-indigo-50 dark:bg-indigo-900/20' : ''}`}
                        >
                            <div className="flex items-center justify-between">
                                <div className="flex items-center gap-3">
                                    <div className="w-10 h-10 rounded-full bg-indigo-600/20 flex items-center justify-center text-indigo-400 text-sm font-medium">
                                        {(room.guest_name || room.name)?.charAt(0) || '?'}
                                    </div>
                                    <div>
                                        <p className="text-sm font-medium text-gray-900 dark:text-white">{room.name}</p>
                                        <p className="text-xs text-gray-500 truncate max-w-[180px]">{room.last_message || 'No messages yet'}</p>
                                    </div>
                                </div>
                                {room.unread_count > 0 && (
                                    <span className="bg-indigo-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{room.unread_count}</span>
                                )}
                            </div>
                        </button>
                    ))
                )}
            </div>

            {/* Chat Area */}
            <div className="flex-1 flex flex-col">
                {selectedRoom ? (
                    <>
                        <div className="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 className="font-semibold text-gray-900 dark:text-white">{selectedRoom.name}</h3>
                            {selectedRoom.guest_email && <p className="text-xs text-gray-500">{selectedRoom.guest_email}</p>}
                        </div>
                        <div className="flex-1 overflow-y-auto p-4 space-y-3">
                            {loading ? (
                                <p className="text-center text-gray-500">Loading messages...</p>
                            ) : messages.length === 0 ? (
                                <p className="text-center text-gray-500">No messages yet.</p>
                            ) : (
                                messages.map(msg => (
                                    <div key={msg.id} className={`flex ${msg.user?.id === auth?.user?.id ? 'justify-end' : 'justify-start'}`}>
                                        <div className={`max-w-[70%] rounded-lg px-4 py-2 ${msg.user?.id === auth?.user?.id ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white'}`}>
                                            {msg.user?.id !== auth?.user?.id && <p className="text-xs font-medium text-indigo-400 mb-1">{msg.user?.name}</p>}
                                            <p className="text-sm">{msg.message}</p>
                                            <p className={`text-xs mt-1 ${msg.user?.id === auth?.user?.id ? 'text-indigo-200' : 'text-gray-500'}`}>{new Date(msg.created_at).toLocaleTimeString()}</p>
                                        </div>
                                    </div>
                                ))
                            )}
                            <div ref={messagesEnd} />
                        </div>
                        <form onSubmit={sendMessage} className="p-4 border-t border-gray-200 dark:border-gray-700 flex gap-2">
                            <input
                                type="text"
                                value={newMessage}
                                onChange={e => setNewMessage(e.target.value)}
                                placeholder="Type a message..."
                                className="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark:bg-gray-700 text-black dark:text-white placeholder:text-black dark:placeholder:text-gray-500"
                            />
                            <button type="submit" className="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-500">Send</button>
                        </form>
                    </>
                ) : (
                    <div className="flex-1 flex items-center justify-center text-gray-500">
                        Select a chat room to start messaging
                    </div>
                )}
            </div>
            </div>
        </AdminLayout>
    );
}
