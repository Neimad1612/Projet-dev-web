<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['kitchen', 'dining', 'storage', 'office', 'outdoor', 'other'])->default('other');
            $table->integer('capacity')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['type', 'is_active']);
        });
    }
    public function down(): void { Schema::dropIfExists('zones'); }
};