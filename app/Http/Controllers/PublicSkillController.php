<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Inertia\Inertia;

class PublicSkillController extends Controller
{
    public function index()
    {
        $skills = Skill::all();
        $categories = Skill::distinct()->pluck('category')->filter()->values();

        return Inertia::render('Skills', [
            'skills' => $skills,
            'categories' => $categories,
            'seo' => [
                'title' => 'Skills',
                'description' => 'Technologies, tools, and frameworks I work with — JavaScript, PHP, Laravel, React, WordPress, and more.',
                'type' => 'website',
            ],
        ]);
    }
}
