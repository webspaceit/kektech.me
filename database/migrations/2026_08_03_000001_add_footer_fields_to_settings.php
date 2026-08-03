<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->text('footer_text')->nullable()->after('login_logo');
            $table->string('footer_font')->nullable()->after('footer_text');
            $table->string('footer_color')->nullable()->after('footer_font');
            $table->string('footer_bg_color')->nullable()->after('footer_color');
            $table->string('footer_font_size')->nullable()->after('footer_bg_color');
            $table->string('footer_align')->nullable()->after('footer_font_size');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'footer_text', 'footer_font', 'footer_color',
                'footer_bg_color', 'footer_font_size', 'footer_align',
            ]);
        });
    }
};
