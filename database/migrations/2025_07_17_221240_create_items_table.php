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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('ite_name');
            $table->text('ite_description')->nullable();
            $table->decimal('ite_price', 10, 2);
            $table->boolean('ite_status')->default(true);
            $table->enum('ite_type', ['producto', 'servicio']);
            $table->string('ite_image', 2048)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
