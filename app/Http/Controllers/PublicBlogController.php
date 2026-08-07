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
            'seo' => [
                'title' => 'Blog',
                'description' => 'Thoughts, tutorials, and insights on web development, Laravel, React, and modern technologies.',
                'type' => 'website',
            ],
        ]);
    }

    public function show(string $slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->whereNotNull('published_at')
            ->firstOrFail();

        $excerpt = strip_tags(substr($post->content, 0, 160));

        return Inertia::render('Blog/Show', [
            'post' => $post,
            'seo' => [
                'title' => $post->title,
                'description' => $excerpt ?: $post->title,
                'image' => $post->featured_image,
                'type' => 'article',
                'publishedTime' => $post->published_at?->toIso8601String(),
                'modifiedTime' => $post->updated_at?->toIso8601String(),
            ],
        ]);
    }
}
