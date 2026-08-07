import PublicLayout from '../../components/PublicLayout';
import HeadSEO from '../../components/HeadSEO';
import { Link, usePage } from '@inertiajs/react';
import { Calendar } from 'lucide-react';

export default function Index({ posts, seo }) {
    const { settings } = usePage().props;

    return (
        <PublicLayout settings={settings}>
            <HeadSEO {...seo} />
            <section className="py-20">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 className="text-3xl font-bold text-white mb-2">Blog</h1>
                    <p className="text-gray-400 mb-10">Thoughts, tutorials, and insights.</p>

                    {posts.data.length === 0 ? (
                        <p className="text-gray-500">No posts published yet.</p>
                    ) : (
                        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {posts.data.map((post) => (
                                <Link
                                    key={post.id}
                                    href={`/blog/${post.slug}`}
                                    className="group block rounded-xl border border-white/10 bg-white/5 overflow-hidden hover:border-emerald-500/50 hover:bg-white/10 transition-all"
                                >
                                    {post.featured_image && (
                                        <img
                                            src={post.featured_image}
                                            alt={post.title}
                                            className="w-full h-48 object-cover"
                                            loading="lazy"
                                        />
                                    )}
                                    <div className="p-6">
                                        <div className="flex items-center gap-2 mb-2">
                                            {post.category && (
                                                <span className="px-2 py-0.5 text-xs rounded-full bg-emerald-600/20 text-emerald-300">
                                                    {post.category}
                                                </span>
                                            )}
                                            {post.published_at && (
                                                <span className="text-xs text-gray-500 flex items-center gap-1">
                                                    <Calendar className="w-3 h-3" />
                                                    {new Date(post.published_at).toLocaleDateString()}
                                                </span>
                                            )}
                                        </div>
                                        <h2 className="text-lg font-semibold text-white group-hover:text-emerald-400 transition-colors">
                                            {post.title}
                                        </h2>
                                        <p className="mt-2 text-sm text-gray-400 line-clamp-3">
                                            {post.content?.replace(/[#*`_>\[\]()!]/g, '').substring(0, 150)}...
                                        </p>
                                    </div>
                                </Link>
                            ))}
                        </div>
                    )}

                    {posts.last_page > 1 && (
                        <div className="mt-10 flex items-center justify-center gap-2">
                            {posts.prev_page_url && (
                                <Link
                                    href={posts.prev_page_url}
                                    className="px-4 py-2 text-sm rounded-lg border border-white/10 text-gray-300 hover:bg-white/10 transition-colors"
                                >
                                    Previous
                                </Link>
                            )}
                            <span className="text-sm text-gray-500">
                                Page {posts.current_page} of {posts.last_page}
                            </span>
                            {posts.next_page_url && (
                                <Link
                                    href={posts.next_page_url}
                                    className="px-4 py-2 text-sm rounded-lg border border-white/10 text-gray-300 hover:bg-white/10 transition-colors"
                                >
                                    Next
                                </Link>
                            )}
                        </div>
                    )}
                </div>
            </section>
        </PublicLayout>
    );
}
