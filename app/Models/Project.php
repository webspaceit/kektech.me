<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'slug', 'description', 'images', 'tech_stack', 'live_url', 'github_url', 'featured', 'is_active'])]
class Project extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'tech_stack' => 'array',
            'featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
