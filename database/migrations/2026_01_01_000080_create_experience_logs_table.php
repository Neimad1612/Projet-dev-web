<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('experience_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('event_type', ['login', 'device_view', 'report_generated', 'device_added', 'device_controlled', 'profile_complete', 'manual_adjustment']);
            $table->integer('points_earned');
            $table->unsignedInteger('total_after');
            $table->string('description')->nullable();
            $table->nullableMorphs('loggable');
            $table->foreignId('adjusted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['user_id', 'created_at']);
            $table->index('event_type');
        });
    }
    public function down(): void { Schema::dropIfExists('experience_logs'); }
};