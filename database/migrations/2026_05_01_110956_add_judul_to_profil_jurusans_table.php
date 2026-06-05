<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tambah kolom `judul` (nullable) ke tabel profil_jurusans.
 *
 * Rasional: Admin perlu fleksibilitas untuk mengedit heading utama
 * di dalam card konten (mis. "Struktur Organisasi Jurusan Rekayasa dan Komputer").
 *
 * Kolom ini OPTIONAL — kalau null, frontend fallback ke teks hardcoded default
 * untuk menjaga backward-compat dan graceful degradation.
 *
 * Scope editable: HANYA heading card (H4 di dalam content card).
 * Tidak editable: breadcrumb, hero page-title, navbar, SEO meta title
 * (tetap hardcoded untuk konsistensi Information Architecture).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profil_jurusans', function (Blueprint $table) {
            $table->string('judul', 255)
                ->nullable()
                ->after('kunci')
                ->comment('Judul heading utama di card konten (editable admin). NULL = pakai default hardcoded.');
        });
    }

    public function down(): void
    {
        Schema::table('profil_jurusans', function (Blueprint $table) {
            $table->dropColumn('judul');
        });
    }
};
