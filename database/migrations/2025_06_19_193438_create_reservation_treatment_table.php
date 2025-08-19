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
        Schema::create('reservation_treatment', function (Blueprint $table) {
            $table->id('reservation_treatment_id');
            $table->foreignId('reservation_id')->constrained('reservations', 'reservation_id')->onDelete('cascade');
            $table->foreignId('treatment_id')->constrained('treatments', 'treatment_id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_treatment');
    }
};
