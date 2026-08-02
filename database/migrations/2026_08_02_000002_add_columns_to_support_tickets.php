<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->string('ticket_number')->unique()->after('id');
            $table->text('description')->after('subject');
            $table->enum('channel', ['web', 'email', 'chat'])->default('web')->after('status');
            $table->enum('category', ['bug', 'feature_request', 'billing', 'complaint', 'inquiry', 'other'])->default('other')->after('channel');
            $table->timestamp('resolved_at')->nullable()->after('category');
            $table->timestamp('closed_at')->nullable()->after('resolved_at');
        });

        Schema::table('support_tickets', function (Blueprint $table) {
            $table->dropColumn('message');
        });

        if (!Schema::hasTable('support_messages')) {
            Schema::create('support_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ticket_id')->constrained('support_tickets')->cascadeOnDelete();
                $table->string('sender_name')->nullable();
                $table->string('sender_email')->nullable();
                $table->text('message');
                $table->boolean('is_admin')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('support_messages');
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->dropColumn(['ticket_number', 'description', 'channel', 'category', 'resolved_at', 'closed_at']);
        });
    }
};
