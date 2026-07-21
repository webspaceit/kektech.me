import PublicLayout from '../components/PublicLayout';
import { useForm, usePage, Head } from '@inertiajs/react';
import { Mail, Phone, MapPin, Send } from 'lucide-react';

export default function Contact() {
    const { settings, flash } = usePage().props;
    const { data, setData, post, processing, errors, wasSuccessful } = useForm({
        name: '',
        email: '',
        subject: '',
        message: '',
    });

    function handleSubmit(e) {
        e.preventDefault();
        post('/contact');
    }

    const contactEmail = settings?.contact_email || settings?.social_links?.email || 'hello@kektech.me';

    return (
        <PublicLayout settings={settings}>
            <Head title="Contact" />
            <section className="py-20">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid lg:grid-cols-2 gap-12">
                        <div>
                            <h1 className="text-3xl font-bold text-white mb-2">{settings?.contact_title || 'Get in Touch'}</h1>
                            <p className="text-gray-400 mb-8">
                                {settings?.contact_description || 'Have a question or want to work together? Fill out the form and I\'ll get back to you.'}
                            </p>

                            <div className="space-y-4">
                                <div className="flex items-center gap-3 text-gray-400">
                                    <div className="w-10 h-10 rounded-lg bg-indigo-600/20 flex items-center justify-center">
                                        <Mail className="w-5 h-5 text-indigo-400" />
                                    </div>
                                    <span>{contactEmail}</span>
                                </div>

                                {settings?.contact_phone && (
                                    <div className="flex items-center gap-3 text-gray-400">
                                        <div className="w-10 h-10 rounded-lg bg-indigo-600/20 flex items-center justify-center">
                                            <Phone className="w-5 h-5 text-indigo-400" />
                                        </div>
                                        <span>{settings.contact_phone}</span>
                                    </div>
                                )}

                                {settings?.contact_address && (
                                    <div className="flex items-center gap-3 text-gray-400">
                                        <div className="w-10 h-10 rounded-lg bg-indigo-600/20 flex items-center justify-center">
                                            <MapPin className="w-5 h-5 text-indigo-400" />
                                        </div>
                                        <span>{settings.contact_address}</span>
                                    </div>
                                )}
                            </div>
                        </div>

                        <div>
                            {wasSuccessful && (
                                <div className="mb-6 p-4 rounded-lg bg-green-600/20 border border-green-500/30 text-green-300 text-sm">
                                    {flash?.success || 'Message sent successfully!'}
                                </div>
                            )}

                            <form onSubmit={handleSubmit} className="space-y-5">
                                <div>
                                    <label className="block text-sm font-medium text-gray-300 mb-1.5">Name</label>
                                    <input type="text" value={data.name} onChange={(e) => setData('name', e.target.value)}
                                        className="w-full px-4 py-2.5 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors"
                                        placeholder="Your name" />
                                    {errors.name && <p className="mt-1 text-sm text-red-400">{errors.name}</p>}
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-300 mb-1.5">Email</label>
                                    <input type="email" value={data.email} onChange={(e) => setData('email', e.target.value)}
                                        className="w-full px-4 py-2.5 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors"
                                        placeholder="you@example.com" />
                                    {errors.email && <p className="mt-1 text-sm text-red-400">{errors.email}</p>}
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-300 mb-1.5">Subject</label>
                                    <input type="text" value={data.subject} onChange={(e) => setData('subject', e.target.value)}
                                        className="w-full px-4 py-2.5 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors"
                                        placeholder="What's this about?" />
                                    {errors.subject && <p className="mt-1 text-sm text-red-400">{errors.subject}</p>}
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-300 mb-1.5">Message</label>
                                    <textarea value={data.message} onChange={(e) => setData('message', e.target.value)} rows={5}
                                        className="w-full px-4 py-2.5 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors resize-none"
                                        placeholder="Your message..." />
                                    {errors.message && <p className="mt-1 text-sm text-red-400">{errors.message}</p>}
                                </div>

                                <button type="submit" disabled={processing}
                                    className="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 disabled:opacity-50 transition-colors font-medium">
                                    <Send className="w-4 h-4" />
                                    {processing ? 'Sending...' : 'Send Message'}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </PublicLayout>
    );
}
