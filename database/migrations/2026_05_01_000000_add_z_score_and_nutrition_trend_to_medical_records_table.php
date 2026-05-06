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
            if (! Schema::hasColumn('medical_records', 'z_score')) {
                $table->decimal('z_score', 5, 2)
                    ->nullable()
                    ->after('nutrition_status')
                    ->comment('Z-score BB/U WHO/Kemenkes');
            }

            if (! Schema::hasColumn('medical_records', 'nutrition_trend')) {
                $table->enum('nutrition_trend', ['naik', 'turun', 'tetap'])
                    ->nullable()
                    ->after('z_score')
                    ->comment('Tren vs bulan sebelumnya');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropColumn(['z_score', 'nutrition_trend']);
        });
    }
};
