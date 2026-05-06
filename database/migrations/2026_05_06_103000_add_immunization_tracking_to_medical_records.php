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
            $table->string('vaccine_name')->nullable()->after('immunization');
            $table->integer('vaccine_dose')->nullable()->after('vaccine_name');
            $table->enum('vitamin_a_color', ['biru', 'merah', 'none'])->default('none')->after('vitamin_a');
            $table->boolean('deworming_medicine')->default(false)->after('vitamin_a_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropColumn(['vaccine_name', 'vaccine_dose', 'vitamin_a_color', 'deworming_medicine']);
        });
    }
};
