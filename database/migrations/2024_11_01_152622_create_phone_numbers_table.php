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
        Schema::create('phone_numbers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('rut')->default('')->nullable(true);
            $table->string('dv')->default('')->nullable(true);
            $table->string('area')->default('')->nullable(true);
            $table->string('number')->default('')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phone_numbers');
    }
};
