<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('pseudo')->unique()->nullable()->after('name');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('pseudo');
            $table->date('birth_date')->nullable()->after('gender');
            $table->string('avatar')->nullable()->after('birth_date');

            $table->enum('role', ['visitor', 'simple', 'complex', 'admin'])->default('visitor')->after('avatar');
            $table->enum('level', ['beginner', 'intermediate', 'advanced', 'expert'])->default('beginner')->after('role');
            $table->unsignedInteger('experience_points')->default(0)->after('level');

            $table->boolean('is_approved')->default(false)->after('experience_points');
            $table->timestamp('approved_at')->nullable()->after('is_approved');
            $table->foreignId('approved_by')->nullable()->after('approved_at')->constrained('users')->nullOnDelete();
            $table->timestamp('last_login_at')->nullable()->after('approved_by');

            $table->index(['role', 'level', 'is_approved']);
            $table->index('experience_points');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role', 'level', 'is_approved']);
            $table->dropIndex(['experience_points']);
            $table->dropColumn(['pseudo', 'gender', 'birth_date', 'avatar', 'role', 'level', 'experience_points', 'is_approved', 'approved_at', 'approved_by', 'last_login_at']);
        });
    }
};