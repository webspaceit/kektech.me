<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('admin_email')->nullable()->after('hero_image');
            $table->boolean('email_notifications')->default(true)->after('admin_email');
            $table->string('contact_email_subject')->nullable()->after('email_notifications');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['admin_email', 'email_notifications', 'contact_email_subject']);
        });
    }
};
