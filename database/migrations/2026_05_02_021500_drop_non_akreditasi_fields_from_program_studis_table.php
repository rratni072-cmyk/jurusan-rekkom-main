<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Refocus tabel program_studis ke aspek akreditasi saja.
 *
 * Hapus kolom yang tidak relevan untuk pengelolaan akreditasi:
 *   - deskripsi, visi, misi, gambar, website
 *
 * Kolom yang dipertahankan (akreditasi-focused):
 *   - nama, jenjang, akreditasi, no_sk, tahun_sk,
 *     tanggal_kedaluwarsa, sertifikat, is_active.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_studis', function (Blueprint $table) {
            // Tiap kolom dicek dulu agar idempotent (aman re-run di env lain).
            foreach (['deskripsi', 'visi', 'misi', 'gambar', 'website'] as $col) {
                if (Schema::hasColumn('program_studis', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('program_studis', function (Blueprint $table) {
            // Bikin ulang kolom dengan tipe semula. Data yang sudah dihapus
            // tidak bisa dikembalikan oleh migration ini.
            if (! Schema::hasColumn('program_studis', 'deskripsi')) {
                $table->longText('deskripsi')->nullable()->after('akreditasi');
            }
            if (! Schema::hasColumn('program_studis', 'visi')) {
                $table->text('visi')->nullable()->after('deskripsi');
            }
            if (! Schema::hasColumn('program_studis', 'misi')) {
                $table->text('misi')->nullable()->after('visi');
            }
            if (! Schema::hasColumn('program_studis', 'gambar')) {
                $table->string('gambar')->nullable()->after('misi');
            }
            if (! Schema::hasColumn('program_studis', 'website')) {
                $table->string('website')->nullable()->after('gambar');
            }
        });
    }
};
