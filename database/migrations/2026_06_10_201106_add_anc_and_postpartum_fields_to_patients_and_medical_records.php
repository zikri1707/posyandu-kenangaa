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
            $table->string('husband_name')->nullable()->after('full_name');
            $table->string('dusun_rt_rw')->nullable()->after('address');
            $table->string('desa_kelurahan')->nullable()->after('dusun_rt_rw');
            $table->string('kecamatan')->nullable()->after('desa_kelurahan');
        });

        Schema::table('medical_records', function (Blueprint $table) {
            // Section 1 additions
            $table->integer('pregnancy_number')->nullable()->after('patient_id');
            $table->string('pregnancy_spacing')->nullable()->after('pregnancy_number');
            $table->decimal('starting_weight', 5, 2)->nullable()->after('pregnancy_spacing');
            $table->decimal('starting_height', 5, 2)->nullable()->after('starting_weight');
            $table->date('delivery_date')->nullable()->after('starting_height');
            $table->string('delivery_method')->nullable()->after('delivery_date');

            // Section 2 additions
            $table->string('gestational_age')->nullable()->after('delivery_method');
            $table->string('imt_plotting_status')->nullable()->after('gestational_age');
            $table->string('lila_plotting_status')->nullable()->after('imt_plotting_status');
            $table->string('bp_plotting_status')->nullable()->after('lila_plotting_status');
            $table->boolean('tbc_screening_weight_loss')->default(false)->after('tbc_screening_contact');
            $table->string('nakes_gives_fe_mms')->nullable()->after('referral_type');
            $table->string('consumes_fe_mms_regularly')->nullable()->after('nakes_gives_fe_mms');
            $table->string('nakes_gives_mt_kek')->nullable()->after('consumes_fe_mms_regularly');
            $table->string('mt_package_details')->nullable()->after('nakes_gives_mt_kek');
            $table->string('consumes_mt_kek_regularly')->nullable()->after('mt_package_details');
            $table->string('counseling_topic')->nullable()->after('consumes_mt_kek_regularly');
            $table->string('joins_pregnant_class')->nullable()->after('counseling_topic');
            $table->text('anc_referral')->nullable()->after('joins_pregnant_class');

            // Section 3 additions
            $table->string('postpartum_period')->nullable()->after('anc_referral');
            $table->string('postpartum_imt_plotting')->nullable()->after('postpartum_period');
            $table->string('postpartum_bp_plotting')->nullable()->after('postpartum_imt_plotting');
            $table->string('nakes_gives_vit_a')->nullable()->after('postpartum_bp_plotting');
            $table->string('vit_a_capsule_count')->nullable()->after('nakes_gives_vit_a');
            $table->string('consumes_vit_a_regularly')->nullable()->after('vit_a_capsule_count');
            $table->string('is_breastfeeding')->nullable()->after('consumes_vit_a_regularly');
            $table->string('postpartum_kb')->nullable()->after('is_breastfeeding');
            $table->string('postpartum_counseling_topic')->nullable()->after('postpartum_kb');
            $table->text('postpartum_referral')->nullable()->after('postpartum_counseling_topic');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['husband_name', 'dusun_rt_rw', 'desa_kelurahan', 'kecamatan']);
        });

        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropColumn([
                'pregnancy_number',
                'pregnancy_spacing',
                'starting_weight',
                'starting_height',
                'delivery_date',
                'delivery_method',
                'gestational_age',
                'imt_plotting_status',
                'lila_plotting_status',
                'bp_plotting_status',
                'tbc_screening_weight_loss',
                'nakes_gives_fe_mms',
                'consumes_fe_mms_regularly',
                'nakes_gives_mt_kek',
                'mt_package_details',
                'consumes_mt_kek_regularly',
                'counseling_topic',
                'joins_pregnant_class',
                'anc_referral',
                'postpartum_period',
                'postpartum_imt_plotting',
                'postpartum_bp_plotting',
                'nakes_gives_vit_a',
                'vit_a_capsule_count',
                'consumes_vit_a_regularly',
                'is_breastfeeding',
                'postpartum_kb',
                'postpartum_counseling_topic',
                'postpartum_referral'
            ]);
        });
    }
};
