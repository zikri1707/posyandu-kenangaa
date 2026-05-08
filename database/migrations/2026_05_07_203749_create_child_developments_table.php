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
        Schema::create('child_developments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_record_id')->constrained()->cascadeOnDelete();
            $table->integer('age_group_months')->comment('Bulan usia untuk target KPSP (misal: 3, 6, 9, 12, dst)');
            $table->boolean('motor_gross')->default(false)->comment('Motorik Kasar');
            $table->boolean('motor_fine')->default(false)->comment('Motorik Halus');
            $table->boolean('language')->default(false)->comment('Bahasa / Bicara');
            $table->boolean('social')->default(false)->comment('Sosialisasi / Kemandirian');
            $table->enum('development_status', ['Sesuai', 'Meragukan', 'Penyimpangan'])->nullable()->comment('Kesimpulan Perkembangan');
            $table->text('note')->nullable()->comment('Catatan tambahan perkembangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_developments');
    }
};
