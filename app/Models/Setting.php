<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'site_title', 'bio', 'hero_image', 'social_links',
    'admin_email', 'email_notifications', 'contact_email_subject',
    'hero_greeting', 'hero_name', 'hero_subtitle', 'hero_cta_text', 'hero_cta_url',
    'about_title', 'about_location', 'about_role',
    'testimonials_title', 'testimonials_subtitle',
    'contact_title', 'contact_description', 'contact_email', 'contact_phone', 'contact_address',
    'resume_path',
    'logo', 'favicon', 'login_logo',
    'footer_text', 'footer_font', 'footer_color', 'footer_bg_color', 'footer_font_size', 'footer_align',
])]
class Setting extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'social_links' => 'array',
            'email_notifications' => 'boolean',
        ];
    }

    public static function get(): static
    {
        return static::firstOrCreate(['id' => 1]);
    }
}
