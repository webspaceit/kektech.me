<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Inertia\Inertia;

class PublicProjectController extends Controller
{
    public function index()
    {
        $projects = Project::where('is_active', true)->latest()->paginate(12);

        return Inertia::render('Projects/Index', [
            'projects' => $projects,
        ]);
    }

    public function show(string $slug)
    {
        $project = Project::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return Inertia::render('Projects/Show', [
            'project' => $project,
        ]);
    }
}
