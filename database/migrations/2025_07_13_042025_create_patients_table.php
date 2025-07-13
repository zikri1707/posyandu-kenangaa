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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('posyandu_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->string('id_number')->unique();
            $table->date('birth_date');
            $table->char('gender');  // M or F
            $table->text('address');
            $table->string('phone_number');
            $table->text('profile_photo')->nullable();
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
