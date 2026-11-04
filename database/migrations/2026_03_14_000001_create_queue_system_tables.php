<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Services (Layanan Antrean)
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5)->unique(); // A, B, C
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color', 20)->default('#0284c7');
            $table->string('icon', 50)->default('book-open');
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(1);
            $table->timestamps();
        });

        // 2. Counters (Loket Pelayanan)
        Schema::create('counters', function (Blueprint $table) {
            $table->id();
            $table->integer('number')->unique();
            $table->string('name'); // Loket 1, Loket 2, dll.
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Tickets (Tiket Antrean)
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('counter_id')->nullable()->constrained('counters')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ticket_number', 20); // A-001, B-001, C-001
            $table->integer('queue_number'); // 1, 2, 3...
            $table->date('service_date');
            $table->enum('status', ['waiting', 'called', 'completed', 'skipped'])->default('waiting');
            $table->timestamp('called_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('call_count')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes for speed and consistency
            $table->unique(['service_id', 'service_date', 'queue_number'], 'unique_service_date_queue');
            $table->index(['service_date', 'status']);
            $table->index(['service_id', 'service_date', 'status']);
            $table->index('ticket_number');
        });

        // 4. Call Histories (Riwayat Pemanggilan)
        Schema::create('call_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('counter_id')->constrained('counters')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('call_type', ['call', 'recall'])->default('call');
            $table->timestamp('called_at');
            $table->timestamps();

            $table->index(['called_at']);
        });

        // 5. Announcements (Pengumuman TV & Teks Berjalan)
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(1);
            $table->timestamps();
        });

        // 6. Settings (Pengaturan Sistem & Audio)
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group', 50)->default('general');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_histories');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('counters');
        Schema::dropIfExists('services');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('settings');
    }
};
