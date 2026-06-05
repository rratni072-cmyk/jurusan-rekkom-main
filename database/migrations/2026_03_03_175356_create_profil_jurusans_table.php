<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel profil_jurusans.
 *
 * Menyimpan informasi profil jurusan secara key-value:
 * visi, misi, sejarah, sambutan ketua, dll.
 */
return new class extends Migration
{
    /**
     * Buat tabel profil_jurusans.
     */
    public function up(): void
    {
        Schema::create('profil_jurusans', function (Blueprint $table) {
            $table->id();
            $table->string('kunci', 100)->unique()->comment('Key unik: visi, misi, sejarah, sambutan_ketua');
            $table->longText('nilai')->comment('Konten HTML dari profil');
            $table->string('gambar')->nullable()->comment('Path gambar pendukung');
            $table->timestamps();
        });
    }

    /**
     * Hapus tabel profil_jurusans.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_jurusans');
    }
};
