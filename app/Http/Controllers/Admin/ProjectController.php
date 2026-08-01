<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::latest()->paginate(10);

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.form', ['project' => new Project]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'images' => ['nullable', 'json'],
            'tech_stack' => ['nullable', 'json'],
            'live_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'featured' => ['boolean'],
        ]);

        $data['slug'] = Str::slug($request->title);
        $data['featured'] = $request->boolean('featured');

        Project::create($data);

        return to_route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.form', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'images' => ['nullable', 'json'],
            'tech_stack' => ['nullable', 'json'],
            'live_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'featured' => ['boolean'],
        ]);

        $data['slug'] = Str::slug($request->title);
        $data['featured'] = $request->boolean('featured');

        $project->update($data);

        return to_route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return to_route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }

    public function toggle(Project $project)
    {
        $project->update(['is_active' => !$project->is_active]);

        return back()->with('success', 'Project ' . ($project->is_active ? 'enabled' : 'disabled') . ' successfully.');
    }
}
