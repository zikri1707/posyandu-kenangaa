<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            if (! Schema::hasColumn('patients', 'category')) {
                $table->enum('category', ['balita', 'ibu_hamil', 'remaja', 'lansia'])->default('balita')->after('posyandu_id');
            }
            if (! Schema::hasColumn('patients', 'parent_name')) {
                $table->string('parent_name')->nullable()->after('full_name');
            }
        });

        Schema::table('medical_records', function (Blueprint $table) {
            if (! Schema::hasColumn('medical_records', 'vitamin_a')) {
                $table->boolean('vitamin_a')->default(false)->after('immunization');
            }
            if (! Schema::hasColumn('medical_records', 'pill_fe')) {
                $table->boolean('pill_fe')->default(false)->after('vitamin_a');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['category', 'parent_name']);
        });

        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropColumn(['vitamin_a', 'pill_fe']);
        });
    }
};
