<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Skill;
use App\Models\BlogPost;
use App\Models\Testimonial;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __invoke()
    {
        $featuredProjects = Project::where('featured', true)
            ->where('is_active', true)
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
        ]);
    }
}
