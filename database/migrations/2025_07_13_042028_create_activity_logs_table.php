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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('role')->nullable(); // Role pengguna saat melakukan aksi
            $table->string('action'); // create, update, delete, login, logout, dll
            $table->string('model_type')->nullable(); // Class model yang diakses (misal: App\Models\Balita)
            $table->unsignedBigInteger('model_id')->nullable(); // ID record yang diakses
            $table->text('description')->nullable(); // Deskripsi aktivitas
            $table->json('old_values')->nullable(); // Data sebelum perubahan (untuk update/delete)
            $table->json('new_values')->nullable(); // Data setelah perubahan (untuk create/update)
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'action']);
            $table->index(['model_type', 'model_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
