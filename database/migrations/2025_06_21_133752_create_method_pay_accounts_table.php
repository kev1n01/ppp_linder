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
        Schema::create('method_pay_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('mpa_name', '100');
            $table->string('mpa_cc_numer', '100')->nullable();
            $table->string('mpa_cci_numer', '100')->nullable();
            $table->string('mpa_phone_num', '9')->nullable();
            $table->enum('mpa_type', ['digital', 'bancario']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('method_pay_accounts');
    }
};
