<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Inertia\Inertia;

class PublicProjectController extends Controller
{
    public function index()
    {
        $projects = Project::where('is_active', true)
            ->with(['media' => fn($q) => $q->orderBy('sort_order')])
            ->latest()
            ->paginate(12);

        return Inertia::render('Projects/Index', [
            'projects' => $projects,
            'seo' => [
                'title' => 'Projects',
                'description' => 'Browse my portfolio of web development projects — WordPress, Laravel, React, and more.',
                'type' => 'website',
            ],
        ]);
    }

    public function show(string $slug)
    {
        $project = Project::where('slug', $slug)
            ->where('is_active', true)
            ->with(['media' => fn($q) => $q->orderBy('sort_order')])
            ->firstOrFail();

        $firstImage = $project->media->where('type', 'image')->first()?->url;

        return Inertia::render('Projects/Show', [
            'project' => $project,
            'seo' => [
                'title' => $project->title,
                'description' => strip_tags(substr($project->description, 0, 160)) ?: $project->title,
                'image' => $firstImage,
                'type' => 'article',
                'publishedTime' => $project->created_at?->toIso8601String(),
                'modifiedTime' => $project->updated_at?->toIso8601String(),
            ],
        ]);
    }
}
