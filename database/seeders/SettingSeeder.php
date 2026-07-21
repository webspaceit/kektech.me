<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::firstOrCreate(['id' => 1], [
            'site_title' => 'KekTech',
            'bio' => 'Full-stack developer passionate about building modern web applications.',
            'social_links' => json_encode([
                'github' => 'https://github.com',
                'twitter' => 'https://twitter.com',
                'linkedin' => 'https://linkedin.com',
            ]),
        ]);
    }
}
