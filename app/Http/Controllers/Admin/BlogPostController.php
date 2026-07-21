<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function index()
    {
        $posts = BlogPost::latest()->paginate(10);

        return view('admin.blog-posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.blog-posts.form', ['post' => new BlogPost]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
        ]);

        $data['slug'] = Str::slug($request->title);

        BlogPost::create($data);

        return to_route('admin.blog-posts.index')->with('success', 'Blog post created successfully.');
    }

    public function edit(BlogPost $blogPost)
    {
        return view('admin.blog-posts.form', ['post' => $blogPost]);
    }

    public function update(Request $request, BlogPost $blogPost)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
        ]);

        $data['slug'] = Str::slug($request->title);

        $blogPost->update($data);

        return to_route('admin.blog-posts.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $blogPost)
    {
        $blogPost->delete();

        return to_route('admin.blog-posts.index')->with('success', 'Blog post deleted successfully.');
    }
}
