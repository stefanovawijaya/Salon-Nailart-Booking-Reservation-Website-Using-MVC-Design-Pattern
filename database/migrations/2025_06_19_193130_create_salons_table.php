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
        Schema::create('salons', function (Blueprint $table) {
            $table->id('salon_id');
            $table->string('salon_name')->nullable();
            $table->string('salon_email')->unique();
            $table->string('salon_password');
            $table->string('salon_description')->nullable();
            $table->string('salon_phonenumber')->nullable();
            $table->string('salon_location')->nullable();
            $table->string('salon_operational_hour')->nullable();
            $table->string('salon_image')->nullable();
            $table->string('salon_pinpoint')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salons');
    }
};
