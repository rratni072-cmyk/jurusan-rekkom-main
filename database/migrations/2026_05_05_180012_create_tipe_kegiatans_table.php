<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel master tipe kegiatan untuk klasifikasi/filter di /kemahasiswaan/kegiatan.
 *
 * Menggantikan kolom ENUM lama di `kegiatans.tipe` agar admin dapat menambah,
 * mengedit, atau menghapus tipe lewat panel admin tanpa migration manual.
 *
 * Kolom:
 *   - slug      : identifier unik mesin (snake_case), dipakai di route/anchor.
 *   - label     : teks tampil di badge & dropdown.
 *   - icon      : kelas Bootstrap Icon untuk visual cue (mis. "bi-trophy").
 *   - urutan    : urutan tampil di dropdown (kecil = atas).
 *   - is_active : toggle non-destructive (sembunyikan dari publik tanpa hapus).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipe_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 50)->unique()->comment('Slug unik (snake_case) untuk identifier');
            $table->string('label', 100)->comment('Teks tampil di badge & dropdown');
            $table->string('icon', 50)->default('bi-tag')->comment('Bootstrap Icon class (mis. bi-trophy)');
            $table->unsignedSmallInteger('urutan')->default(0)->comment('Urutan tampil di dropdown (kecil = atas)');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipe_kegiatans');
    }
};
