<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('serial_number')->unique();
            $table->string('model')->nullable();
            $table->string('manufacturer')->nullable();
            $table->foreignId('category_id')->constrained('device_categories')->cascadeOnDelete();
            $table->foreignId('zone_id')->nullable()->constrained('zones')->nullOnDelete();
            $table->enum('status', ['online', 'offline', 'maintenance', 'error'])->default('offline');
            $table->boolean('is_active')->default(true);
            $table->json('current_data')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('firmware_version')->nullable();
            $table->date('installation_date')->nullable();
            $table->date('warranty_until')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'is_active']);
            $table->index(['zone_id', 'category_id']);
            $table->index('last_seen_at');
        });
    }
    public function down(): void { Schema::dropIfExists('devices'); }
};