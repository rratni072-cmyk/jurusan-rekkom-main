<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Extend tabel beasiswas agar card frontend bisa menampilkan:
 * - Nama penyelenggara (siapa yang memberi, mis. "Bank Indonesia")
 * - Tautan info eksternal (website resmi / sosmed penyelenggara)
 *
 * Kedua kolom nullable — data lama tetap valid, admin isi bertahap via UI.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beasiswas', function (Blueprint $table) {
            // Nama penyelenggara: tampil sebagai subtitle di card frontend.
            $table->string('penyelenggara', 150)
                ->nullable()
                ->after('nama');

            // Tautan info eksternal: website resmi / sosmed beasiswa.
            // Max 500 karakter — cukup untuk URL panjang dengan query string.
            $table->string('url_info', 500)
                ->nullable()
                ->after('deskripsi');
        });
    }

    public function down(): void
    {
        Schema::table('beasiswas', function (Blueprint $table) {
            $table->dropColumn(['penyelenggara', 'url_info']);
        });
    }
};
