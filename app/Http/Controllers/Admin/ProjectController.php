<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'tech_stack' => ['nullable', 'json'],
            'live_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'featured' => ['boolean'],
        ]);

        $data['slug'] = Str::slug($request->title);
        $data['featured'] = $request->boolean('featured');

        $project = Project::create($data);

        $this->syncMedia($request, $project);

        return to_route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        $project->load('media');

        return view('admin.projects.form', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'tech_stack' => ['nullable', 'json'],
            'live_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'featured' => ['boolean'],
        ]);

        $data['slug'] = Str::slug($request->title);
        $data['featured'] = $request->boolean('featured');

        $project->update($data);

        $this->syncMedia($request, $project);

        return to_route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        foreach ($project->media as $item) {
            $disk = $item->type === 'video' ? 'public' : 'public';
            if (Storage::disk($disk)->exists($item->path)) {
                Storage::disk($disk)->delete($item->path);
            }
        }
        $project->media()->delete();
        $project->delete();

        return to_route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }

    public function toggle(Project $project)
    {
        $project->update(['is_active' => !$project->is_active]);

        return back()->with('success', 'Project ' . ($project->is_active ? 'enabled' : 'disabled') . ' successfully.');
    }

    public function destroyMedia(ProjectMedia $media)
    {
        $project = $media->project;
        $path = $media->path;

        $media->delete();

        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        return back()->with('success', 'Media deleted successfully.');
    }

    public function reorderMedia(Request $request)
    {
        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:project_media,id'],
        ]);

        foreach ($request->input('order') as $index => $id) {
            ProjectMedia::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    private function syncMedia(Request $request, Project $project): void
    {
        // Handle new file uploads
        if ($request->hasFile('media_files')) {
            $files = $request->file('media_files');
            $captions = $request->input('media_captions', []);
            $maxOrder = $project->media()->max('sort_order') ?? 0;

            foreach ($files as $index => $file) {
                $isVideo = str_starts_with($file->getMimeType(), 'video/');
                $type = $isVideo ? 'video' : 'image';
                $folder = $isVideo ? 'projects/videos' : 'projects/images';
                $path = $file->store($folder, 'public');
                $caption = $captions[$index] ?? null;

                $project->media()->create([
                    'type' => $type,
                    'path' => $path,
                    'caption' => $caption,
                    'sort_order' => $maxOrder + $index + 1,
                ]);
            }
        }
    }
}
