<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->foreignId('parcel_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('subject');
            $table->text('description');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'waiting_customer', 'waiting_internal', 'resolved', 'closed'])->default('open');
            $table->enum('channel', ['web', 'phone', 'email', 'chat', 'social'])->default('web');
            $table->enum('category', ['delivery_issue', 'damaged_parcel', 'lost_parcel', 'billing', 'complaint', 'inquiry', 'other'])->default('other');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('support_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('support_tickets')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('message');
            $table->string('attachment_path')->nullable();
            $table->boolean('is_internal_note')->default(false);
            $table->timestamps();
        });

        Schema::create('support_ticket_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ticket_id')->constrained('support_tickets')->cascadeOnDelete();
            $table->timestamp('read_at');
            $table->timestamps();

            $table->unique(['user_id', 'ticket_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_ticket_reads');
        Schema::dropIfExists('support_messages');
        Schema::dropIfExists('support_tickets');
    }
};
