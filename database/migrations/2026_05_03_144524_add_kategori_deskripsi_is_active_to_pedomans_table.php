<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Extend tabel pedomans untuk mendukung kategorisasi, deskripsi, dan toggle
 * aktif/nonaktif — selaras dengan pola fitur lain (jadwal, beasiswa).
 *
 * Kategori pakai string (bukan enum) supaya fleksibel kalau di masa depan admin
 * mau tambah kategori baru tanpa migration (mis. "Penelitian", "PkM").
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedomans', function (Blueprint $table) {
            // Kategori: default 'akademik' supaya data lama tidak orphan.
            // Indexed karena sering dipakai untuk filter di frontend.
            $table->string('kategori', 50)->default('akademik')->after('nama_file')->index();

            // Deskripsi singkat opsional — untuk subtitle di card frontend.
            $table->string('deskripsi', 500)->nullable()->after('kategori');

            // Status aktif — hanya yang aktif yang tampil di halaman publik.
            $table->boolean('is_active')->default(true)->after('urutan')->index();
        });
    }

    public function down(): void
    {
        Schema::table('pedomans', function (Blueprint $table) {
            $table->dropIndex(['kategori']);
            $table->dropIndex(['is_active']);
            $table->dropColumn(['kategori', 'deskripsi', 'is_active']);
        });
    }
};
