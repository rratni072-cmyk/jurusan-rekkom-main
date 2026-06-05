<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Refactor kegiatans.tipe (ENUM string) → kegiatans.tipe_kegiatan_id (FK).
 *
 * Strategi migrasi data aman:
 *   1. Pastikan tipe_kegiatans master ter-populasi 6 default tipe (idempotent).
 *   2. Tambah kolom `tipe_kegiatan_id` (nullable sementara).
 *   3. Backfill: map slug ENUM lama → ID master baru.
 *   4. Drop kolom ENUM `tipe`.
 *   5. Set `tipe_kegiatan_id` NOT NULL + FK constraint (restrict on delete).
 *
 * Bila tabel tipe_kegiatans masih kosong saat migration jalan, migration akan
 * meng-insert default rows dulu agar tidak ada data kegiatan yang ter-orphan.
 */
return new class extends Migration
{
    /** @var array<int, array{slug: string, label: string, icon: string, urutan: int}> */
    private const DEFAULT_TIPE = [
        ['slug' => 'workshop',  'label' => 'Workshop',  'icon' => 'bi-tools',       'urutan' => 10],
        ['slug' => 'seminar',   'label' => 'Seminar',   'icon' => 'bi-mic',         'urutan' => 20],
        ['slug' => 'lomba',     'label' => 'Lomba',     'icon' => 'bi-trophy',      'urutan' => 30],
        ['slug' => 'kunjungan', 'label' => 'Kunjungan', 'icon' => 'bi-building',    'urutan' => 40],
        ['slug' => 'hima',      'label' => 'HIMA',      'icon' => 'bi-people',      'urutan' => 50],
        ['slug' => 'akademik',  'label' => 'Akademik',  'icon' => 'bi-mortarboard', 'urutan' => 60],
    ];

    public function up(): void
    {
        // 1) Pastikan master ter-populasi (idempotent — pakai updateOrInsert by slug).
        $now = now();
        foreach (self::DEFAULT_TIPE as $tipe) {
            DB::table('tipe_kegiatans')->updateOrInsert(
                ['slug' => $tipe['slug']],
                [
                    'label' => $tipe['label'],
                    'icon' => $tipe['icon'],
                    'urutan' => $tipe['urutan'],
                    'is_active' => true,
                    'updated_at' => $now,
                    'created_at' => $now,
                ],
            );
        }

        // 2) Tambah FK column (nullable dulu agar backfill bisa jalan).
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->foreignId('tipe_kegiatan_id')
                ->nullable()
                ->after('tanggal')
                ->comment('FK ke tipe_kegiatans (master tipe kegiatan)');
        });

        // 3) Backfill: copy slug lama → id master.
        if (Schema::hasColumn('kegiatans', 'tipe')) {
            $tipeMap = DB::table('tipe_kegiatans')->pluck('id', 'slug');
            foreach ($tipeMap as $slug => $id) {
                DB::table('kegiatans')->where('tipe', $slug)->update(['tipe_kegiatan_id' => $id]);
            }

            // Fallback: kegiatan dengan slug yang tidak ter-mapping → set ke 'akademik'.
            $fallbackId = $tipeMap['akademik'] ?? null;
            if ($fallbackId !== null) {
                DB::table('kegiatans')->whereNull('tipe_kegiatan_id')->update(['tipe_kegiatan_id' => $fallbackId]);
            }

            // 4) Drop kolom ENUM lama.
            Schema::table('kegiatans', function (Blueprint $table) {
                $table->dropColumn('tipe');
            });
        }

        // 5) Set NOT NULL + FK constraint (restrict on delete agar tipe yang
        //    masih dipakai kegiatan tidak bisa dihapus tanpa migrasi data).
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->foreignId('tipe_kegiatan_id')
                ->nullable(false)
                ->change();
            $table->foreign('tipe_kegiatan_id')
                ->references('id')->on('tipe_kegiatans')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            // Drop FK constraint dulu sebelum kolom.
            $table->dropForeign(['tipe_kegiatan_id']);
        });

        // Re-create kolom ENUM lama dengan default 'akademik'.
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->enum('tipe', ['workshop', 'seminar', 'lomba', 'kunjungan', 'hima', 'akademik'])
                ->default('akademik')
                ->after('tanggal');
        });

        // Backfill ENUM dari relasi sebelum drop FK column.
        $tipeMap = DB::table('tipe_kegiatans')->pluck('slug', 'id');
        foreach ($tipeMap as $id => $slug) {
            DB::table('kegiatans')->where('tipe_kegiatan_id', $id)->update(['tipe' => $slug]);
        }

        Schema::table('kegiatans', function (Blueprint $table) {
            $table->dropColumn('tipe_kegiatan_id');
        });
    }
};
