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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('set_name_business', 100);
            $table->string('set_ruc', 11);
            $table->string('set_phone', 9);
            $table->string('set_province', 50);
            $table->string('set_department', 50);
            $table->string('set_district', 50);
            $table->string('set_ubigeous');
            $table->string('set_address', 100);
            $table->string('set_logo', 2048)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
