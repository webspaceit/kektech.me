import PublicLayout from '../components/PublicLayout';
import { Link, usePage } from '@inertiajs/react';
import { ArrowRight, Code, Mail, MapPin, Quote, Star } from 'lucide-react';

export default function Home({ settings, featuredProjects, skills, recentPosts, testimonials }) {
    return (
        <PublicLayout settings={settings}>
            {/* Hero */}
            <section className="py-14 sm:py-20">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid md:grid-cols-12 gap-10 items-center">
                        {/* Image - 25% */}
                        <div className="md:col-span-3">
                            <div className="w-full aspect-square rounded-2xl bg-gradient-to-br from-indigo-600/20 to-purple-600/20 border border-white/10 flex items-center justify-center overflow-hidden">
                                {settings?.hero_image ? (
                                    <img src={settings.hero_image} alt={settings?.hero_name || 'Profile'} className="w-full h-full object-cover rounded-2xl" />
                                ) : (
                                    <span className="text-6xl font-bold text-indigo-500">{settings?.hero_name?.charAt(0) || 'S'}</span>
                                )}
                            </div>
                        </div>
                        {/* Text - 75% */}
                        <div className="md:col-span-9">
                            {settings?.hero_greeting && (
                                <p className="text-indigo-400 font-medium mb-4">{settings.hero_greeting}</p>
                            )}
                            {settings?.hero_name && (
                                <h1 className="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight">
                                    {settings.hero_name}
                                    {settings?.hero_subtitle && (
                                        <span className="block text-indigo-500">{settings.hero_subtitle}</span>
                                    )}
                                </h1>
                            )}
                            <p className="mt-6 text-lg text-gray-400 leading-relaxed">
                                {settings?.bio || 'Full-stack developer passionate about building modern web applications.'}
                            </p>
                            <div className="mt-8 flex flex-wrap gap-4">
                                {settings?.hero_cta_text && (
                                    <Link
                                        href={settings?.hero_cta_url || '/projects'}
                                        className="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 transition-colors font-medium"
                                    >
                                        {settings.hero_cta_text}
                                        <ArrowRight className="w-4 h-4" />
                                    </Link>
                                )}
                                <Link
                                    href="/contact"
                                    className="inline-flex items-center gap-2 px-6 py-3 border border-white/20 text-white rounded-lg hover:bg-white/10 transition-colors font-medium"
                                >
                                    <Mail className="w-4 h-4" />
                                    Get in Touch
                                </Link>
                                <a
                                    href="/resume/download"
                                    className="inline-flex items-center gap-2 px-6 py-3 border border-indigo-500/50 text-indigo-400 rounded-lg hover:bg-indigo-600/10 transition-colors font-medium"
                                >
                                    <ArrowRight className="w-4 h-4 rotate-45" />
                                    Download Resume
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Featured Projects */}
            {featuredProjects.length > 0 && (
                <section className="py-20 border-t border-white/5">
                    <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex items-center justify-between mb-10">
                            <h2 className="text-2xl font-bold text-white">Featured Projects</h2>
                            <Link href="/projects" className="text-indigo-400 hover:text-indigo-300 text-sm font-medium flex items-center gap-1">
                                View all <ArrowRight className="w-4 h-4" />
                            </Link>
                        </div>
                        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {featuredProjects.map((project) => (
                                <Link
                                    key={project.id}
                                    href={`/projects/${project.slug}`}
                                    className="group block rounded-xl border border-white/10 bg-white/5 p-6 hover:border-indigo-500/50 hover:bg-white/10 transition-all"
                                >
                                    {project.images?.[0] && (
                                        <img src={project.images[0]} alt={project.title} className="w-full h-40 object-cover rounded-lg mb-4" />
                                    )}
                                    <h3 className="text-lg font-semibold text-white group-hover:text-indigo-400 transition-colors">{project.title}</h3>
                                    <p className="mt-2 text-sm text-gray-400 line-clamp-2">{project.description}</p>
                                    {(() => {
                                        const tech = typeof project.tech_stack === 'string' ? JSON.parse(project.tech_stack) : (project.tech_stack || []);
                                        return tech.length > 0 && (
                                            <div className="mt-4 flex flex-wrap gap-2">
                                                {tech.slice(0, 4).map((t) => (
                                                    <span key={t} className="px-2 py-0.5 text-xs rounded-full bg-indigo-600/20 text-indigo-300 border border-indigo-500/30">{t}</span>
                                                ))}
                                            </div>
                                        );
                                    })()}
                                </Link>
                            ))}
                        </div>
                    </div>
                </section>
            )}

            {/* Skills Preview */}
            {skills.length > 0 && (
                <section className="py-20 border-t border-white/5">
                    <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex items-center justify-between mb-10">
                            <h2 className="text-2xl font-bold text-white">Skills</h2>
                            <Link href="/skills" className="text-indigo-400 hover:text-indigo-300 text-sm font-medium flex items-center gap-1">View all <ArrowRight className="w-4 h-4" /></Link>
                        </div>
                        <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            {skills.slice(0, 8).map((skill) => (
                                <div key={skill.id} className="rounded-lg border border-white/10 bg-white/5 p-4">
                                    <div className="flex items-center gap-3">
                                        <div className="w-10 h-10 rounded-lg bg-indigo-600/20 flex items-center justify-center"><Code className="w-5 h-5 text-indigo-400" /></div>
                                        <div>
                                            <p className="text-sm font-medium text-white">{skill.name}</p>
                                            {skill.category && <p className="text-xs text-gray-500">{skill.category}</p>}
                                        </div>
                                    </div>
                                    {skill.level != null && (
                                        <div className="mt-3">
                                            <div className="w-full bg-white/10 rounded-full h-1.5"><div className="bg-indigo-500 h-1.5 rounded-full" style={{ width: `${skill.level}%` }} /></div>
                                        </div>
                                    )}
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
            )}

            {/* Recent Blog Posts */}
            {recentPosts.length > 0 && (
                <section className="py-20 border-t border-white/5">
                    <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex items-center justify-between mb-10">
                            <h2 className="text-2xl font-bold text-white">Recent Posts</h2>
                            <Link href="/blog" className="text-indigo-400 hover:text-indigo-300 text-sm font-medium flex items-center gap-1">View all <ArrowRight className="w-4 h-4" /></Link>
                        </div>
                        <div className="grid md:grid-cols-3 gap-6">
                            {recentPosts.map((post) => (
                                <Link key={post.id} href={`/blog/${post.slug}`} className="group block rounded-xl border border-white/10 bg-white/5 p-6 hover:border-indigo-500/50 hover:bg-white/10 transition-all">
                                    {post.featured_image && <img src={post.featured_image} alt={post.title} className="w-full h-40 object-cover rounded-lg mb-4" />}
                                    <div className="flex items-center gap-2 mb-2">
                                        {post.category && <span className="px-2 py-0.5 text-xs rounded-full bg-indigo-600/20 text-indigo-300">{post.category}</span>}
                                        {post.published_at && <span className="text-xs text-gray-500">{new Date(post.published_at).toLocaleDateString()}</span>}
                                    </div>
                                    <h3 className="text-lg font-semibold text-white group-hover:text-indigo-400 transition-colors">{post.title}</h3>
                                    <p className="mt-2 text-sm text-gray-400 line-clamp-2">{post.content?.replace(/[#*`]/g, '').substring(0, 120)}...</p>
                                </Link>
                            ))}
                        </div>
                    </div>
                </section>
            )}

            {/* Testimonials */}
            {testimonials.length > 0 && (
                <section className="py-20 border-t border-white/5">
                    <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-12">
                            <h2 className="text-2xl font-bold text-white">{settings?.testimonials_title || 'What People Say'}</h2>
                            <p className="mt-2 text-gray-400">{settings?.testimonials_subtitle || 'Testimonials from clients and colleagues'}</p>
                        </div>
                        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {testimonials.map((t) => (
                                <div key={t.id} className="rounded-xl border border-white/10 bg-white/5 p-6 hover:border-indigo-500/50 transition-all">
                                    <div className="flex items-center gap-1 mb-4">
                                        {Array.from({ length: t.rating || 5 }, (_, i) => <Star key={i} className="w-4 h-4 fill-yellow-500 text-yellow-500" />)}
                                    </div>
                                    <p className="text-gray-400 text-sm leading-relaxed mb-6 italic">&ldquo;{t.content}&rdquo;</p>
                                    <div className="flex items-center gap-3">
                                        {t.avatar ? <img src={t.avatar} alt={t.name} className="w-10 h-10 rounded-full object-cover" /> : <div className="w-10 h-10 rounded-full bg-indigo-600/20 flex items-center justify-center"><Quote className="w-4 h-4 text-indigo-400" /></div>}
                                        <div>
                                            <p className="text-sm font-medium text-white">{t.name}</p>
                                            {(t.role || t.company) && <p className="text-xs text-gray-500">{t.role}{t.role && t.company ? ' at ' : ''}{t.company}</p>}
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
            )}
        </PublicLayout>
    );
}
