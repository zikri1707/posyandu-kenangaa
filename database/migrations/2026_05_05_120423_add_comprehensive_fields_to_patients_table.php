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
        Schema::table('patients', function (Blueprint $table) {
            // General
            $table->string('place_of_birth')->nullable()->after('birth_date');
            $table->string('head_of_family_name')->nullable()->after('full_name');

            // Children Specific
            $table->string('mother_nik', 16)->nullable()->after('parent_name');
            $table->boolean('kia_book_ownership')->default(false)->after('mother_nik');

            // Adult/Mother Specific
            $table->string('guardian_status')->nullable()->after('parent_name')
                ->comment('ibu kandung / wali');
            $table->string('education')->nullable()->after('guardian_status');
            $table->string('job')->nullable()->after('education');
            $table->integer('number_of_children')->default(0)->after('job');
            $table->boolean('is_pregnant')->default(false)->after('number_of_children');

            // Elderly Specific
            $table->string('living_status')->nullable()->after('is_pregnant')
                ->comment('sendiri / dengan keluarga');
            $table->string('independence_status')->nullable()->after('living_status')
                ->comment('mandiri / butuh bantuan');

            // Environmental/Family
            $table->integer('family_member_count')->nullable()->after('independence_status');
            $table->string('house_condition')->nullable()->after('family_member_count');
            $table->string('water_access')->nullable()->after('house_condition');
            $table->boolean('has_latrine')->default(false)->after('water_access');
            $table->string('economic_status')->nullable()->after('has_latrine');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'place_of_birth', 'head_of_family_name', 'mother_nik', 'kia_book_ownership',
                'guardian_status', 'education', 'job', 'number_of_children', 'is_pregnant',
                'living_status', 'independence_status', 'family_member_count',
                'house_condition', 'water_access', 'has_latrine', 'economic_status',
            ]);
        });
    }
};
