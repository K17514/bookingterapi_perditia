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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pembayaran', 255)->unique();
            $table->string('kode_booking', 255);
            $table->decimal('grand_total', 15, 2);
            $table->enum('metode_pembayaran', ['dana', 'BCA', 'gopay']);
            $table->string('foto_pembayaran', 255)->nullable();
            $table->enum('status', ['gagal', 'lunas', 'pending']);
            
            $table->timestamp('created_at')->nullable();
            $table->string('created_by')->nullable();

            $table->foreign('kode_booking')->references('kode_booking')->on('bookings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};