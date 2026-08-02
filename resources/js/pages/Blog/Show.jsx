import PublicLayout from '../../components/PublicLayout';
import { Link, usePage, Head } from '@inertiajs/react';
import { ArrowLeft, Calendar, Tag } from 'lucide-react';
import { useMemo } from 'react';

function renderMarkdown(content) {
    if (!content) return '';

    let html = content;

    // Fenced code blocks with language tag
    html = html.replace(/```(\w*)\n([\s\S]*?)```/g, (_, lang, code) => {
        const langLabel = lang ? `<span class="absolute top-2 right-3 text-xs text-gray-500">${lang}</span>` : '';
        return `<div class="relative my-6"><div class="bg-[#0d1117] border border-white/10 rounded-xl p-5 overflow-x-auto">${langLabel}<pre class="text-sm text-gray-300 leading-relaxed"><code>${escapeHtml(code.trim())}</code></pre></div></div>`;
    });

    // Headings
    html = html.replace(/^#### (.*$)/gm, '<h4 class="text-lg font-semibold text-white mt-6 mb-2">$1</h4>');
    html = html.replace(/^### (.*$)/gm, '<h3 class="text-xl font-semibold text-white mt-8 mb-3 pb-2 border-b border-white/10">$1</h3>');
    html = html.replace(/^## (.*$)/gm, '<h2 class="text-2xl font-bold text-white mt-10 mb-4 pb-2 border-b border-white/10">$1</h2>');
    html = html.replace(/^# (.*$)/gm, '<h1 class="text-3xl font-bold text-white mt-0 mb-4">$1</h1>');

    // Bold and italic
    html = html.replace(/\*\*(.*?)\*\*/g, '<strong class="text-white font-semibold">$1</strong>');
    html = html.replace(/\*(.*?)\*/g, '<em>$1</em>');

    // Inline code
    html = html.replace(/`([^`\n]+)`/g, '<code class="px-1.5 py-0.5 rounded-md bg-white/10 text-emerald-300 text-sm font-mono">$1</code>');

    // Unordered list items
    html = html.replace(/^- (.*$)/gm, '<li class="text-gray-300 leading-relaxed ml-4 mb-1 list-disc">$1</li>');

    // Ordered list items
    html = html.replace(/^(\d+)\. (.*$)/gm, '<li class="text-gray-300 leading-relaxed ml-4 mb-1 list-decimal">$2</li>');

    // Wrap consecutive <li> in <ul>
    html = html.replace(/((?:<li class="[^"]*list-disc">.*?<\/li>\n?)+)/g, '<ul class="my-4 space-y-1">$1</ul>');

    // Wrap consecutive <li> in <ol>
    html = html.replace(/((?:<li class="[^"]*list-decimal">.*?<\/li>\n?)+)/g, '<ol class="my-4 space-y-1">$1</ol>');

    // Horizontal rule
    html = html.replace(/^---$/gm, '<hr class="my-8 border-white/10" />');

    // Paragraphs
    html = html.split('\n\n').map(block => {
        block = block.trim();
        if (!block) return '';
        if (block.startsWith('<h') || block.startsWith('<pre') || block.startsWith('<div') ||
            block.startsWith('<ul') || block.startsWith('<ol') || block.startsWith('<hr') ||
            block.startsWith('<li') || block.startsWith('<table')) {
            return block;
        }
        return `<p class="text-gray-300 leading-relaxed mb-4">${block.replace(/\n/g, '<br/>')}</p>`;
    }).join('\n');

    return html;
}

function escapeHtml(text) {
    return text
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}

export default function Show({ post }) {
    const { settings } = usePage().props;
    const htmlContent = useMemo(() => renderMarkdown(post.content), [post.content]);

    return (
        <PublicLayout settings={settings}>
            <Head title={post.title} />
            <section className="py-20">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <Link
                        href="/blog"
                        className="inline-flex items-center gap-1 text-sm text-gray-400 hover:text-white transition-colors mb-8"
                    >
                        <ArrowLeft className="w-4 h-4" />
                        All Posts
                    </Link>

                    <div className="flex items-start justify-between gap-4">
                        <div className="flex-1">
                            <div className="flex items-center gap-3 mb-3">
                                {post.category && (
                                    <span className="inline-flex items-center gap-1 px-2.5 py-0.5 text-xs rounded-full bg-emerald-600/20 text-emerald-300 border border-emerald-500/30">
                                        <Tag className="w-3 h-3" />
                                        {post.category}
                                    </span>
                                )}
                                {post.published_at && (
                                    <span className="text-sm text-gray-500 flex items-center gap-1">
                                        <Calendar className="w-4 h-4" />
                                        {new Date(post.published_at).toLocaleDateString('en-US', {
                                            year: 'numeric',
                                            month: 'long',
                                            day: 'numeric',
                                        })}
                                    </span>
                                )}
                            </div>
                            <h1 className="text-3xl font-bold text-white">
                                {post.title}
                            </h1>
                        </div>
                    </div>

                    {post.featured_image && (
                        <img
                            src={post.featured_image}
                            alt={post.title}
                            className="w-full rounded-xl border border-white/10 mt-8 mb-8"
                        />
                    )}

                    <div className="mt-8">
                        <div
                            className="max-w-none"
                            dangerouslySetInnerHTML={{ __html: htmlContent }}
                        />
                    </div>
                </div>
            </section>
        </PublicLayout>
    );
}
