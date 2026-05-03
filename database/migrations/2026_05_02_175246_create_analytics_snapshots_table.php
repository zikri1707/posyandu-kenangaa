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
        Schema::create('analytics_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('posyandu_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('key'); // e.g. 'dashboard_stats', 'monthly_trends'
            $table->json('data');
            $table->timestamp('last_computed_at')->nullable();
            $table->timestamps();

            $table->index(['posyandu_id', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_snapshots');
    }
};
