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
            $table->decimal('upper_arm_circumference', 5, 2)->nullable()->after('head_circumference')->comment('Lingkar lengan atas dalam cm');
            $table->boolean('mp_asi')->default(false)->after('is_exclusive_breastfeeding')->comment('Pemberian Makanan Pendamping ASI');
            $table->boolean('is_basic_immunization_complete')->default(false)->after('vaccine_dose')->comment('Status Imunisasi Dasar Lengkap');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropColumn([
                'upper_arm_circumference',
                'mp_asi',
                'is_basic_immunization_complete'
            ]);
        });
    }
};
