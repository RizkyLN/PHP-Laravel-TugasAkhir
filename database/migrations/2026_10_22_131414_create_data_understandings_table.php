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
        Schema::create('data_understandings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_understanding_id')
            ->constrained('business_understandings')->cascadeOnDelete();
            $table->string('raw_dataset');
            $table->string('dataset_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_understandings');
    }
};
