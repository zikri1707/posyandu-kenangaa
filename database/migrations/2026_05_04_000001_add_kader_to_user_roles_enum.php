<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'kader' to the role enum in users table
        // Using raw SQL for MySQL to ensure it works correctly with ENUM types
        if (config('database.default') === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('superadmin', 'admin', 'coordinator', 'kader', 'staff', 'medical', 'patient', 'partner') DEFAULT 'admin'");
        } else {
            // For SQLite and others, we use Schema builder
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('admin')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original (removing 'kader')
        if (config('database.default') === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('superadmin', 'admin', 'coordinator', 'staff', 'medical', 'patient', 'partner') DEFAULT 'admin'");
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('admin')->change();
            });
        }
    }
};
