<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('site_title');
            $table->string('favicon')->nullable()->after('logo');
            $table->string('login_logo')->nullable()->after('favicon');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['logo', 'favicon', 'login_logo']);
        });
    }
};