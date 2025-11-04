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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking', 255)->unique();
            $table->date('tanggal_booking');
            $table->unsignedBigInteger('id_user');
            $table->string('riwayat_penyakit', 255);
            $table->string('kode_terapi', 255);
            $table->unsignedBigInteger('jadwal'); // foreign key to jadwal.id
           $table->bigInteger('biaya_layanan')->nullable()->default(0);
            $table->enum('status', ['pending', 'accepted', 'cancel', 'completed']);
            $table->text('keluhan');

            $table->timestamp('created_at')->nullable();
            $table->string('created_by')->nullable();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('kode_terapi')->references('kode_terapi')->on('terapis')->onDelete('cascade');
            $table->foreign('jadwal')->references('id')->on('jadwals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};