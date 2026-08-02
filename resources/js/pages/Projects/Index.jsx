import PublicLayout from '../../components/PublicLayout';
import { Link, usePage } from '@inertiajs/react';
import { ExternalLink, Code } from 'lucide-react';

export default function Index({ projects }) {
    const { settings } = usePage().props;

    return (
        <PublicLayout settings={settings}>
            <section className="py-20">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 className="text-3xl font-bold text-white mb-2">Projects</h1>
                    <p className="text-gray-400 mb-10">A collection of my work and side projects.</p>

                    {projects.data.length === 0 ? (
                        <p className="text-gray-500">No projects yet.</p>
                    ) : (
                        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {projects.data.map((project) => (
                                <Link
                                    key={project.id}
                                    href={`/projects/${project.slug}`}
                                    className="group block rounded-xl border border-white/10 bg-white/5 overflow-hidden hover:border-emerald-500/50 hover:bg-white/10 transition-all"
                                >
                                    {project.images?.[0] && (
                                        <img
                                            src={project.images[0]}
                                            alt={project.title}
                                            className="w-full h-48 object-cover"
                                        />
                                    )}
                                    <div className="p-6">
                                        <div className="flex items-start justify-between">
                                            <h3 className="text-lg font-semibold text-white group-hover:text-emerald-400 transition-colors">
                                                {project.title}
                                            </h3>
                                            {project.featured && (
                                                <span className="px-2 py-0.5 text-xs rounded-full bg-emerald-600/20 text-emerald-300 border border-emerald-500/30">
                                                    Featured
                                                </span>
                                            )}
                                        </div>
                                        <p className="mt-2 text-sm text-gray-400 line-clamp-2">
                                            {project.description}
                                        </p>
                                        {(() => {
                                            const tech = typeof project.tech_stack === 'string' ? JSON.parse(project.tech_stack) : (project.tech_stack || []);
                                            return tech.length > 0 && (
                                                <div className="mt-4 flex flex-wrap gap-2">
                                                    {tech.map((t) => (
                                                        <span key={t} className="px-2 py-0.5 text-xs rounded-full bg-white/10 text-gray-300">{t}</span>
                                                    ))}
                                                </div>
                                            );
                                        })()}
                                        <div className="mt-4 flex items-center gap-3">
                                            {project.live_url && (
                                                <span className="text-xs text-gray-500 flex items-center gap-1">
                                                    <ExternalLink className="w-3 h-3" /> Live
                                                </span>
                                            )}
                                            {project.github_url && (
                                                <span className="text-xs text-gray-500 flex items-center gap-1">
                                                    <Code className="w-3 h-3" /> Code
                                                </span>
                                            )}
                                        </div>
                                    </div>
                                </Link>
                            ))}
                        </div>
                    )}

                    {projects.last_page > 1 && (
                        <div className="mt-10 flex items-center justify-center gap-2">
                            {projects.prev_page_url && (
                                <Link
                                    href={projects.prev_page_url}
                                    className="px-4 py-2 text-sm rounded-lg border border-white/10 text-gray-300 hover:bg-white/10 transition-colors"
                                >
                                    Previous
                                </Link>
                            )}
                            <span className="text-sm text-gray-500">
                                Page {projects.current_page} of {projects.last_page}
                            </span>
                            {projects.next_page_url && (
                                <Link
                                    href={projects.next_page_url}
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
