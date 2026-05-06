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
        Schema::table('activity_logs', function (Blueprint $table) {
            if (! Schema::hasColumn('activity_logs', 'user_agent')) {
                $table->text('user_agent')->nullable()->after('ip_address');
            }
            if (! Schema::hasColumn('activity_logs', 'user_name')) {
                $table->string('user_name')->nullable()->after('user_id');
            }
            if (! Schema::hasColumn('activity_logs', 'action_type')) {
                // Check if old 'action' exists to rename it, or just add action_type
                if (Schema::hasColumn('activity_logs', 'action')) {
                    $table->renameColumn('action', 'action_type');
                } else {
                    $table->string('action_type', 100)->after('role');
                }
            }
            if (! Schema::hasColumn('activity_logs', 'entity_type') && Schema::hasColumn('activity_logs', 'model_type')) {
                $table->renameColumn('model_type', 'entity_type');
            }
            if (! Schema::hasColumn('activity_logs', 'entity_id') && Schema::hasColumn('activity_logs', 'model_id')) {
                $table->renameColumn('model_id', 'entity_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            // Reversing these is tricky due to potential existing names,
            // but for safety we just handle the additions.
        });
    }
};
