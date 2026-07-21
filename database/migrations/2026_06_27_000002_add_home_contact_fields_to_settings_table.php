<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('hero_greeting')->nullable()->after('social_links');
            $table->string('hero_name')->nullable()->after('hero_greeting');
            $table->string('hero_subtitle')->nullable()->after('hero_name');
            $table->string('hero_cta_text')->nullable()->after('hero_subtitle');
            $table->string('hero_cta_url')->nullable()->after('hero_cta_text');
            $table->text('about_title')->nullable()->after('hero_cta_url');
            $table->text('about_location')->nullable()->after('about_title');
            $table->string('about_role')->nullable()->after('about_location');
            $table->string('testimonials_title')->nullable()->after('about_role');
            $table->text('testimonials_subtitle')->nullable()->after('testimonials_title');
            $table->string('contact_title')->nullable()->after('testimonials_subtitle');
            $table->text('contact_description')->nullable()->after('contact_title');
            $table->string('contact_email')->nullable()->after('contact_description');
            $table->string('contact_phone')->nullable()->after('contact_email');
            $table->text('contact_address')->nullable()->after('contact_phone');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'hero_greeting', 'hero_name', 'hero_subtitle', 'hero_cta_text', 'hero_cta_url',
                'about_title', 'about_location', 'about_role',
                'testimonials_title', 'testimonials_subtitle',
                'contact_title', 'contact_description', 'contact_email', 'contact_phone', 'contact_address',
            ]);
        });
    }
};
