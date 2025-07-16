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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('cu_name', 100);
            $table->string('cu_num_doc');
            $table->enum('cu_type_doc', ['ruc', 'dni']);
            $table->string('cu_email', 70)->nullable();
            $table->string('cu_address', 100)->nullable();
            $table->string('cu_phone', 9)->nullable();
            $table->boolean('cu_status')->default(true);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
