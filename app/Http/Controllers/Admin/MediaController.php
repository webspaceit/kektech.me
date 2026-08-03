<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index()
    {
        $files = collect(Storage::disk('public')->allFiles())
            ->filter(fn ($path) => in_array(pathinfo($path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'pdf', 'doc', 'docx']))
            ->map(fn ($path) => [
                'name' => pathinfo($path, PATHINFO_BASENAME),
                'path' => '/storage/' . $path,
                'size' => Storage::disk('public')->size($path),
                'url' => Storage::disk('public')->url($path),
                'last_modified' => Storage::disk('public')->lastModified($path),
            ])
            ->sortByDesc('last_modified')
            ->values();

        return view('admin.media.index', compact('files'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $path = $request->file('file')->store('media', 'public');

        return back()->with('success', 'File uploaded successfully.');
    }

    public function destroy(Request $request)
    {
        $filename = $request->route('filename');
        
        $files = Storage::disk('public')->allFiles();
        $found = null;
        foreach ($files as $path) {
            if (basename($path) === $filename) {
                $found = $path;
                break;
            }
        }

        if ($found && Storage::disk('public')->delete($found)) {
            return back()->with('success', 'File deleted successfully.');
        }

        return back()->with('error', 'File not found.');
    }
}
