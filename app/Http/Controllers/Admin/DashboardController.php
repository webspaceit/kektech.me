<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\ContactMessage;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\Testimonial;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'projects' => Project::count(),
            'skills' => Skill::count(),
            'blogPosts' => BlogPost::count(),
            'testimonials' => Testimonial::count(),
            'messages' => ContactMessage::count(),
            'latestMessages' => ContactMessage::latest()->take(5)->get(),
            'setting' => Setting::first(),
        ];

        return view('admin.dashboard.index', $stats);
    }
}
