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
            $table->decimal('waist_circumference', 5, 2)->nullable()->after('upper_arm_circumference');
            $table->string('eye_test')->nullable()->after('waist_circumference');
            $table->string('ear_test')->nullable()->after('eye_test');
            $table->string('puma_screening')->nullable()->after('ear_test');
            $table->string('tbc_screening_status')->nullable()->after('puma_screening');
            $table->string('mental_screening')->nullable()->after('tbc_screening_status');
            $table->string('contraception')->nullable()->after('mental_screening');
            $table->text('family_disease_history')->nullable()->after('contraception');
            $table->text('risk_behaviors')->nullable()->after('family_disease_history');
            $table->decimal('imt', 5, 2)->nullable()->after('weight');
            $table->text('education')->nullable()->after('counseling_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropColumn([
                'waist_circumference',
                'eye_test',
                'ear_test',
                'puma_screening',
                'tbc_screening_status',
                'mental_screening',
                'contraception',
                'family_disease_history',
                'risk_behaviors',
                'imt',
                'education'
            ]);
        });
    }
};
