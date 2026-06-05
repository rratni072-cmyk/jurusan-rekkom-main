<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tambah kolom `tipe` di tabel `kategoris`.
 *
 * - editorial : kategori editorial murni untuk filter di sidebar /berita
 *               (Akademik, Pengumuman, Prestasi, Kerjasama, dll).
 * - topik     : kategori "topikal" yang sudah punya halaman/menu sendiri
 *               (Pengajaran/Pengabdian/Penelitian → menu Tridharma,
 *               Kegiatan → menu Kemahasiswaan).
 *               Tidak ditampilkan di sidebar widget /berita untuk hindari
 *               duplikasi navigasi, tapi tetap valid untuk tagging berita
 *               dan akses via URL `?kategori={slug}`.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kategoris', function (Blueprint $table) {
            $table->enum('tipe', ['editorial', 'topik'])
                ->default('editorial')
                ->after('slug')
                ->comment('editorial: tampil di sidebar /berita; topik: hanya untuk tagging (mis. tridharma)');

            $table->index('tipe');
        });
    }

    public function down(): void
    {
        Schema::table('kategoris', function (Blueprint $table) {
            $table->dropIndex(['tipe']);
            $table->dropColumn('tipe');
        });
    }
};
