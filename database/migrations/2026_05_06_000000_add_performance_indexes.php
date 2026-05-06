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
        Schema::table('medical_records', function (Blueprint $table) {
            $table->index('visit_date');
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->index('category');
            $table->index(['posyandu_id', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropIndex(['visit_date']);
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropIndex(['posyandu_id', 'category']);
        });
    }
};
