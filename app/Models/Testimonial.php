<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'role', 'company', 'content', 'avatar', 'rating'])]
class Testimonial extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }
}
