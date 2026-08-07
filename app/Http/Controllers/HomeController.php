<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Skill;
use App\Models\BlogPost;
use App\Models\Testimonial;
use App\Models\Setting;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __invoke()
    {
        $setting = Setting::get();

        $featuredProjects = Project::where('featured', true)
            ->where('is_active', true)
            ->with(['media' => fn($q) => $q->orderBy('sort_order')])
            ->latest()
            ->limit(3)
            ->get();

        $skills = Skill::all();

        $recentPosts = BlogPost::latest('published_at')
            ->limit(3)
            ->get();

        $testimonials = Testimonial::latest()->get();

        return Inertia::render('Home', [
            'featuredProjects' => $featuredProjects,
            'skills' => $skills,
            'recentPosts' => $recentPosts,
            'testimonials' => $testimonials,
            'seo' => [
                'title' => $setting->hero_name ?: config('app.name'),
                'description' => $setting->bio ?: 'Full-stack developer portfolio showcasing projects, skills, and blog posts.',
                'image' => $setting->hero_image,
                'type' => 'profile',
            ],
        ]);
    }
}
