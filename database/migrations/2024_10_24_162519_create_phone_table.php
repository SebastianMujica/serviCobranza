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
        Schema::create('phone', function (Blueprint $table) {
            $table->id();
            $table->string('note');
            $table->string('file_url')->default('');
            $table->string('file_name')->default('');
            $table->integer('new_black_list')->default(0);
            $table->integer('errors')->default(0);
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phone');
    }
};
