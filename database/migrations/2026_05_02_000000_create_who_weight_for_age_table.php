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
        if (! Schema::hasTable('who_weight_for_age')) {
            Schema::create('who_weight_for_age', function (Blueprint $table) {
                $table->id();
                $table->char('gender', 1)->comment('M untuk laki-laki, F untuk perempuan');
                $table->unsignedTinyInteger('age_months')->comment('Usia dalam bulan (0-59)');
                $table->decimal('sd_minus3', 5, 2)->comment('Standar deviasi -3 (Gizi Buruk)');
                $table->decimal('sd_minus2', 5, 2)->comment('Standar deviasi -2 (Gizi Kurang)');
                $table->decimal('median', 5, 2)->comment('Median (Normal)');
                $table->decimal('sd_plus2', 5, 2)->comment('Standar deviasi +2 (Gizi Lebih)');
                $table->decimal('sd_plus3', 5, 2)->comment('Standar deviasi +3');

                // Unique constraint untuk kombinasi gender dan age_months
                $table->unique(['gender', 'age_months'], 'uk_gender_age');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('who_weight_for_age');
    }
};
