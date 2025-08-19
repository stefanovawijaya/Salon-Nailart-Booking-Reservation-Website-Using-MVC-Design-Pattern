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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id('reservation_id');
            $table->foreignId('client_id')->constrained('clients', 'client_id')->onDelete('cascade');
            $table->foreignId('salon_id')->constrained('salons', 'salon_id')->onDelete('cascade');
            $table->date('reservation_date');
            $table->time('reservation_start');
            $table->time('reservation_end');
            $table->enum('reservation_status', ['Sedang Berlangsung', 'Selesai', 'Dibatalkan'])->default('Sedang Berlangsung');
            $table->decimal('reservation_total_price', 10, 2);
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers', 'voucher_id')->onDelete('set null');
            $table->string('voucher_code')->nullable();
            $table->decimal('voucher_value', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
