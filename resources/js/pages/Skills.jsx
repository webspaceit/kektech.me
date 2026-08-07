import PublicLayout from '../components/PublicLayout';
import HeadSEO from '../components/HeadSEO';
import { usePage } from '@inertiajs/react';
import { useState } from 'react';
import { Code, Layers, Database, Globe, Smartphone, Server, Palette, Terminal } from 'lucide-react';

const iconMap = {
    code: Code,
    layers: Layers,
    database: Database,
    globe: Globe,
    smartphone: Smartphone,
    server: Server,
    palette: Palette,
    terminal: Terminal,
};

export default function Skills({ skills, categories, seo }) {
    const { settings } = usePage().props;
    const [activeCategory, setActiveCategory] = useState('all');

    const filtered = activeCategory === 'all'
        ? skills
        : skills.filter((s) => s.category === activeCategory);

    return (
        <PublicLayout settings={settings}>
            <HeadSEO {...seo} />
            <section className="py-20">
                <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 className="text-3xl font-bold text-white mb-2">Skills</h1>
                    <p className="text-gray-400 mb-10">Technologies and tools I work with.</p>

                    {categories.length > 0 && (
                        <div className="flex flex-wrap gap-2 mb-8">
                            <button
                                onClick={() => setActiveCategory('all')}
                                className={`px-4 py-1.5 text-sm rounded-full transition-colors ${
                                    activeCategory === 'all'
                                        ? 'bg-emerald-600 text-white'
                                        : 'border border-white/10 text-gray-400 hover:text-white hover:bg-white/10'
                                }`}
                            >
                                All
                            </button>
                            {categories.map((cat) => (
                                <button
                                    key={cat}
                                    onClick={() => setActiveCategory(cat)}
                                    className={`px-4 py-1.5 text-sm rounded-full transition-colors ${
                                        activeCategory === cat
                                            ? 'bg-emerald-600 text-white'
                                            : 'border border-white/10 text-gray-400 hover:text-white hover:bg-white/10'
                                    }`}
                                >
                                    {cat}
                                </button>
                            ))}
                        </div>
                    )}

                    <div className="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        {filtered.map((skill) => {
                            const Icon = skill.icon && iconMap[skill.icon.toLowerCase()] ? iconMap[skill.icon.toLowerCase()] : Code;
                            return (
                                <div
                                    key={skill.id}
                                    className="rounded-xl border border-white/10 bg-white/5 p-5 hover:border-emerald-500/50 transition-all"
                                >
                                    <div className="flex items-center gap-3 mb-3">
                                        <div className="w-10 h-10 rounded-lg bg-emerald-600/20 flex items-center justify-center">
                                            <Icon className="w-5 h-5 text-emerald-400" />
                                        </div>
                                        <div>
                                            <p className="font-medium text-white">{skill.name}</p>
                                            {skill.category && (
                                                <p className="text-xs text-gray-500">{skill.category}</p>
                                            )}
                                        </div>
                                    </div>
                                    {skill.level != null && (
                                        <div>
                                            <div className="flex items-center justify-between mb-1">
                                                <span className="text-xs text-gray-500">Proficiency</span>
                                                <span className="text-xs text-gray-400">{skill.level}%</span>
                                            </div>
                                            <div className="w-full bg-white/10 rounded-full h-1.5">
                                                <div
                                                    className="bg-emerald-500 h-1.5 rounded-full transition-all"
                                                    style={{ width: `${skill.level}%` }}
                                                />
                                            </div>
                                        </div>
                                    )}
                                </div>
                            );
                        })}
                    </div>

                    {filtered.length === 0 && (
                        <p className="text-gray-500">No skills found.</p>
                    )}
                </div>
            </section>
        </PublicLayout>
    );
}
