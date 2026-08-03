import { useState, useEffect, useRef } from 'react';
import { MessageCircle, X, Send } from 'lucide-react';

export default function ChatWidget() {
    const [open, setOpen] = useState(false);
    const [started, setStarted] = useState(false);
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [sessionId, setSessionId] = useState('');
    const [roomId, setRoomId] = useState(null);
    const [messages, setMessages] = useState([]);
    const [newMessage, setNewMessage] = useState('');
    const [loading, setLoading] = useState(false);
    const messagesEnd = useRef(null);
    const prevMsgCount = useRef(0);
    const sessionIdRef = useRef('');
    const roomIdRef = useRef(null);

    const playNotifSound = () => {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const times = [0, 0.08, 0.15, 0.25];
            const freqs = [1200, 1600, 1000, 1400];
            times.forEach((t, i) => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.frequency.value = freqs[i];
                osc.type = 'sine';
                gain.gain.setValueAtTime(0.2, ctx.currentTime + t);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + t + 0.15);
                osc.start(ctx.currentTime + t);
                osc.stop(ctx.currentTime + t + 0.15);
            });
        } catch(e) {}
    };

    const showDesktopNotification = (title, body) => {
        if (Notification.permission === 'granted') {
            new Notification(title, { body, icon: '/favicon.ico', tag: 'chat-message' });
        }
    };

    useEffect(() => {
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    }, []);

    useEffect(() => {
        let sid = localStorage.getItem('chat_session_id');
        if (!sid) {
            sid = 'guest_' + Math.random().toString(36).substring(2, 15);
            localStorage.setItem('chat_session_id', sid);
        }
        setSessionId(sid);
        sessionIdRef.current = sid;

        const savedRoom = localStorage.getItem('chat_room_id');
        const savedName = localStorage.getItem('chat_name');
        const savedEmail = localStorage.getItem('chat_email');
        if (savedRoom && savedName && savedEmail) {
            const rid = parseInt(savedRoom);
            setRoomId(rid);
            roomIdRef.current = rid;
            setName(savedName);
            setEmail(savedEmail);
            setStarted(true);
            prevMsgCount.current = 0;

            fetch(`/api/chat/guest/${rid}/messages?session_id=${sid}`)
                .then(res => res.json())
                .then(data => {
                    setMessages(data);
                    prevMsgCount.current = data.length;
                })
                .catch(() => {});
        }
    }, []);

    useEffect(() => {
        messagesEnd.current?.scrollIntoView({ behavior: 'smooth' });
    }, [messages]);

    const startChat = async (e) => {
        e.preventDefault();
        if (!name.trim() || !email.trim()) return;

        const sid = sessionIdRef.current;
        const res = await fetch('/api/chat/guest/start', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            },
            body: JSON.stringify({ name, email, session_id: sid }),
        });

        if (res.ok) {
            const data = await res.json();
            setRoomId(data.room_id);
            roomIdRef.current = data.room_id;
            setStarted(true);
            prevMsgCount.current = 0;
            localStorage.setItem('chat_room_id', data.room_id);
            localStorage.setItem('chat_name', name);
            localStorage.setItem('chat_email', email);

            const msgRes = await fetch(`/api/chat/guest/${data.room_id}/messages?session_id=${sid}`);
            if (msgRes.ok) {
                const msgData = await msgRes.json();
                setMessages(msgData);
                prevMsgCount.current = msgData.length;
            }
        }
    };

    const loadMessages = async (id) => {
        const sid = sessionIdRef.current;
        const res = await fetch(`/api/chat/guest/${id}/messages?session_id=${sid}`);
        if (res.ok) {
            const data = await res.json();
            if (data.length > prevMsgCount.current) {
                const newMsgs = data.slice(prevMsgCount.current);
                if (newMsgs.some(m => !m.is_guest)) {
                    playNotifSound();
                    const adminMsg = newMsgs.find(m => !m.is_guest);
                    showDesktopNotification('New Message from KekTech', adminMsg?.message || 'You have a new message');
                }
            }
            prevMsgCount.current = data.length;
            setMessages(data);
        }
    };

    const sendMessage = async (e) => {
        e.preventDefault();
        if (!newMessage.trim() || !roomId) return;

        const sid = sessionIdRef.current;
        try {
            const res = await fetch(`/api/chat/guest/${roomId}/messages`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
                body: JSON.stringify({ message: newMessage, session_id: sid }),
            });

            if (res.ok) {
                const msg = await res.json();
                setMessages(prev => [...prev, msg]);
                setNewMessage('');
            }
        } catch (err) {
            console.error('Chat send failed:', err);
        }
    };

    useEffect(() => {
        if (!open || !started || !roomId) return;
        const poll = () => loadMessages(roomId);
        poll();
        const interval = setInterval(poll, 3000);
        return () => clearInterval(interval);
    }, [open, started, roomId]);

    return (
        <div className="fixed bottom-6 right-6 z-50">
            {open ? (
                <div className="w-80 h-96 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
                    <div className="bg-emerald-600 text-white px-4 py-3 flex items-center justify-between">
                        <h3 className="font-semibold text-sm">Live Chat</h3>
                        <button onClick={() => setOpen(false)} className="hover:bg-emerald-500 rounded p-1 transition">
                            <X className="w-4 h-4" />
                        </button>
                    </div>

                    {!started ? (
                        <form onSubmit={startChat} className="p-4 flex-1 flex flex-col gap-3">
                            <p className="text-xs text-gray-500 dark:text-gray-400">Start a conversation with us.</p>
                            <input
                                type="text"
                                placeholder="Your name"
                                value={name}
                                onChange={e => setName(e.target.value)}
                                required
                                className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark:bg-gray-700 dark:text-white placeholder:text-black dark:placeholder:text-gray-500 text-black dark:text-white"
                            />
                            <input
                                type="email"
                                placeholder="Your email"
                                value={email}
                                onChange={e => setEmail(e.target.value)}
                                required
                                className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark:bg-gray-700 dark:text-white placeholder:text-black dark:placeholder:text-gray-500 text-black dark:text-white"
                            />
                            <button type="submit" className="w-full py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-500 transition">Start Chat</button>
                        </form>
                    ) : (
                        <>
                            <div className="flex-1 overflow-y-auto p-3 space-y-2">
                                {messages.length === 0 ? (
                                    <p className="text-center text-xs text-gray-500 mt-8">Waiting for a response...</p>
                                ) : (
                                    messages.map(msg => (
                                        <div key={msg.id} className={`flex ${msg.is_guest ? 'justify-end' : 'justify-start'}`}>
                                            <div className={`max-w-[80%] rounded-lg px-3 py-2 text-xs ${msg.is_guest ? 'bg-emerald-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white'}`}>
                                                <p>{msg.message}</p>
                                                <p className={`mt-1 ${msg.is_guest ? 'text-emerald-200' : 'text-gray-500'}`}>{new Date(msg.created_at).toLocaleTimeString()}</p>
                                            </div>
                                        </div>
                                    ))
                                )}
                                <div ref={messagesEnd} />
                            </div>
                            <form onSubmit={sendMessage} className="p-2 border-t border-gray-200 dark:border-gray-700 flex gap-1">
                                <input
                                    type="text"
                                    value={newMessage}
                                    onChange={e => setNewMessage(e.target.value)}
                                    placeholder="Type a message..."
                                    className="flex-1 px-2 py-1.5 border border-gray-300 dark:border-gray-600 rounded text-xs dark:bg-gray-700 dark:text-white placeholder:text-black dark:placeholder:text-gray-500 text-black dark:text-white"
                                />
                                <button type="submit" className="px-2 py-1.5 bg-emerald-600 text-white rounded hover:bg-emerald-500 transition">
                                    <Send className="w-3 h-3" />
                                </button>
                            </form>
                        </>
                    )}
                </div>
            ) : (
                <button
                    onClick={() => setOpen(true)}
                    className="w-14 h-14 bg-emerald-600 hover:bg-emerald-500 text-white rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110"
                >
                    <MessageCircle className="w-6 h-6" />
                </button>
            )}
        </div>
    );
}
