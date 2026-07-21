<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Inertia\Inertia;

class PublicBlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::whereNotNull('published_at')
            ->latest('published_at')
            ->paginate(9);

        return Inertia::render('Blog/Index', [
            'posts' => $posts,
        ]);
    }

    public function show(string $slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->whereNotNull('published_at')
            ->firstOrFail();

        return Inertia::render('Blog/Show', [
            'post' => $post,
        ]);
    }
}
