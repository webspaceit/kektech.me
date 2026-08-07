import PublicLayout from '../../components/PublicLayout';
import HeadSEO from '../../components/HeadSEO';
import { Link, usePage } from '@inertiajs/react';
import { ArrowLeft, ExternalLink, Code } from 'lucide-react';

export default function Show({ project, seo }) {
    const { settings } = usePage().props;
    const media = project.media || [];
    const hasMedia = media.length > 0;

    return (
        <PublicLayout settings={settings}>
            <HeadSEO {...seo} />
            <section className="py-20">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <Link
                        href="/projects"
                        className="inline-flex items-center gap-1 text-sm text-gray-400 hover:text-white transition-colors mb-8"
                    >
                        <ArrowLeft className="w-4 h-4" />
                        All Projects
                    </Link>

                    <div className="flex items-start justify-between gap-4">
                        <div>
                            <h1 className="text-3xl font-bold text-white">{project.title}</h1>
                            {project.featured && (
                                <span className="mt-2 inline-block px-2.5 py-0.5 text-xs rounded-full bg-emerald-600/20 text-emerald-300 border border-emerald-500/30">
                                    Featured
                                </span>
                            )}
                        </div>
                        <div className="flex items-center gap-3 shrink-0">
                            {project.live_url && (
                                <a
                                    href={project.live_url}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="inline-flex items-center gap-1.5 px-4 py-2 text-sm rounded-lg bg-emerald-600 text-white hover:bg-emerald-500 transition-colors"
                                >
                                    <ExternalLink className="w-4 h-4" />
                                    Live Demo
                                </a>
                            )}
                            {project.github_url && (
                                <a
                                    href={project.github_url}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="inline-flex items-center gap-1.5 px-4 py-2 text-sm rounded-lg border border-white/20 text-white hover:bg-white/10 transition-colors"
                                >
                                    <Code className="w-4 h-4" />
                                    Source
                                </a>
                            )}
                        </div>
                    </div>

                    {hasMedia && (
                        <div className="mt-8 space-y-4">
                            {media.map((item) => (
                                item.type === 'video' ? (
                                    <div key={item.id} className="rounded-xl overflow-hidden border border-white/10">
                                        <video
                                            src={item.url}
                                            controls
                                            className="w-full"
                                            loading="lazy"
                                        >
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                ) : (
                                    <img
                                        key={item.id}
                                        src={item.url}
                                        alt={item.caption || `${project.title} screenshot`}
                                        className="w-full rounded-xl border border-white/10"
                                        loading="lazy"
                                    />
                                )
                            ))}
                        </div>
                    )}

                    {!hasMedia && project.images?.length > 0 && (
                        <div className="mt-8 space-y-4">
                            {project.images.map((image, i) => (
                                <img
                                    key={i}
                                    src={image}
                                    alt={`${project.title} screenshot ${i + 1}`}
                                    className="w-full rounded-xl border border-white/10"
                                    loading="lazy"
                                />
                            ))}
                        </div>
                    )}

                    {(() => {
                        const tech = typeof project.tech_stack === 'string' ? JSON.parse(project.tech_stack) : (project.tech_stack || []);
                        return tech.length > 0 && (
                            <div className="mt-8">
                                <h2 className="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Tech Stack</h2>
                                <div className="flex flex-wrap gap-2">
                                    {tech.map((t) => (
                                        <span key={t} className="px-3 py-1 text-sm rounded-full bg-emerald-600/20 text-emerald-300 border border-emerald-500/30">{t}</span>
                                    ))}
                                </div>
                            </div>
                        );
                    })()}

                    {project.description && (
                        <div className="mt-8">
                            <h2 className="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">About</h2>
                            <div className="prose prose-invert max-w-none text-gray-300 leading-relaxed whitespace-pre-wrap">
                                {project.description}
                            </div>
                        </div>
                    )}
                </div>
            </section>
        </PublicLayout>
    );
}
