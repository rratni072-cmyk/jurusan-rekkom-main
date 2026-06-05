<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tambah 2 kolom opsional untuk pattern community-card (Pengabdian Masyarakat):
     *   - lokasi:         Lokasi geografis pengabdian (mis. "Desa Tani Aman, Kukar")
     *   - dampak_singkat: Ringkasan dampak kuantitatif (mis. "25 keluarga binaan")
     *
     * Keduanya nullable — hanya relevan untuk kategori Pengabdian. Berita umum
     * dan Pengajaran biarkan NULL. Banner overlay di card community-card hanya
     * tampil jika field terisi.
     */
    public function up(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->string('lokasi', 150)
                ->nullable()
                ->after('program_studi_id')
                ->comment('Lokasi pengabdian (banner overlay community-card) — opsional');

            $table->string('dampak_singkat', 100)
                ->nullable()
                ->after('lokasi')
                ->comment('Ringkasan dampak kuantitatif (badge community-card) — opsional');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropColumn(['lokasi', 'dampak_singkat']);
        });
    }
};
