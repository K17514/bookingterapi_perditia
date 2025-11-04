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
        Schema::create('terapis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');

            $table->string('name', 255);
            $table->string('spesialis', 255);
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->string('no_hp', 255);
            $table->string('kode_terapi', 255)->unique(); // optional
            $table->text('deskripsi'); // changed from string to text
            $table->string('foto', 255)->nullable();

           
            $table->timestamps();              // created_at, updated_at
            $table->softDeletes();            // deleted_at
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terapis');
    }
};
