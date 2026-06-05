<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel program_studis.
 *
 * Menyimpan data program studi yang ada di jurusan.
 */
return new class extends Migration
{
    /**
     * Buat tabel program_studis.
     */
    public function up(): void
    {
        Schema::create('program_studis', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255)->comment('Nama program studi');
            $table->string('jenjang', 50)->comment('Jenjang: D3, D4, S1');
            $table->string('akreditasi', 20)->nullable()->comment('A/B/C/Unggul/Baik Sekali');
            $table->longText('deskripsi')->comment('Deskripsi lengkap prodi');
            $table->text('visi')->nullable()->comment('Visi program studi');
            $table->text('misi')->nullable()->comment('Misi program studi');
            $table->string('gambar')->nullable()->comment('Path gambar prodi');
            $table->boolean('is_active')->default(true)->comment('Status aktif/nonaktif');
            $table->timestamps();
        });
    }

    /**
     * Hapus tabel program_studis.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_studis');
    }
};
