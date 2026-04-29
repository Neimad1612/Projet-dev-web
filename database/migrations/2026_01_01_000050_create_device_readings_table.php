<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('device_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->string('metric');
            $table->decimal('value', 10, 3);
            $table->string('unit')->nullable();
            $table->json('raw_payload')->nullable();
            $table->timestamp('recorded_at');
            $table->index(['device_id', 'metric', 'recorded_at']);
            $table->index('recorded_at');
        });
    }
    public function down(): void { Schema::dropIfExists('device_readings'); }
};