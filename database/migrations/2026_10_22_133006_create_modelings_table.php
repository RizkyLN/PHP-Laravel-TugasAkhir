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
        Schema::create('modelings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('preparation_id')
            ->constrained('preparations')->cascadeOnDelete();
            $table->json('algoritma_type');
            $table->boolean('parameter_include')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modelings');
    }
};
