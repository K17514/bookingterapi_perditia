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
        // Jadwal Table
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->unsignedBigInteger('id_terapi');
            $table->bigInteger('biaya_jadwal')->nullable()->default(0);
            $table->enum('status', ['available', 'unavailable']);
            
            $table->date('tanggal');

            $table->timestamps();              // created_at, updated_at
            $table->softDeletes();            // deleted_at
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('id_terapi')->references('id')->on('terapis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};