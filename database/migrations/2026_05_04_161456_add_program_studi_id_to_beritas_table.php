<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tambah FK `program_studi_id` ke `beritas` untuk filter Prodi
     * di halaman Tridharma (Pengajaran & Pengabdian).
     *
     * NULL = artikel lintas jurusan / tidak terkait prodi spesifik.
     * onDelete null = kalau prodi dihapus, berita tetap ada (menjadi lintas).
     */
    public function up(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->foreignId('program_studi_id')
                ->nullable()
                ->after('penulis_id')
                ->constrained('program_studis')
                ->nullOnDelete()
                ->comment('FK ke program_studis. NULL = lintas jurusan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropForeign(['program_studi_id']);
            $table->dropColumn('program_studi_id');
        });
    }
};
