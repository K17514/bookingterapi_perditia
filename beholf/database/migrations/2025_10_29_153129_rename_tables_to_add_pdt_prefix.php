<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Rename tables to add 'pdt_' prefix
        DB::statement('ALTER TABLE users RENAME TO pdt_users');
        DB::statement('ALTER TABLE terapis RENAME TO pdt_terapis');
        DB::statement('ALTER TABLE kategoris RENAME TO pdt_kategoris');
        DB::statement('ALTER TABLE jadwals RENAME TO pdt_jadwals');
        DB::statement('ALTER TABLE bookings RENAME TO pdt_bookings');
        DB::statement('ALTER TABLE pembayarans RENAME TO pdt_pembayarans');
        DB::statement('ALTER TABLE password_reset_tokens RENAME TO pdt_password_reset_tokens');
        DB::statement('ALTER TABLE sessions RENAME TO pdt_sessions');
        DB::statement('ALTER TABLE migrations RENAME TO pdt_migrations');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rename tables back to original names
        DB::statement('ALTER TABLE pdt_users RENAME TO users');
        DB::statement('ALTER TABLE pdt_terapis RENAME TO terapis');
        DB::statement('ALTER TABLE pdt_kategoris RENAME TO kategoris');
        DB::statement('ALTER TABLE pdt_jadwals RENAME TO jadwals');
        DB::statement('ALTER TABLE pdt_bookings RENAME TO bookings');
        DB::statement('ALTER TABLE pdt_pembayarans RENAME TO pembayarans');
        DB::statement('ALTER TABLE pdt_password_reset_tokens RENAME TO password_reset_tokens');
        DB::statement('ALTER TABLE pdt_sessions RENAME TO sessions');
        DB::statement('ALTER TABLE pdt_migrations RENAME TO migrations');
    }
};
