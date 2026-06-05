<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pisahkan konten Tridharma (Pengajaran & Pengabdian) dari Berita biasa.
 *
 * Sebelum: Tridharma dikelola via Berita + kategori "Pengajaran" / "Pengabdian Masyarakat".
 *          Rentan kalau admin hapus kategori sistem; mental model admin tidak jelas.
 * Sesudah: Kolom enum `tridharma_type` jadi pemisah. Pengajaran & Pengabdian punya
 *          menu admin terpisah (Admin\TridharmaController), Penelitian = external link.
 *
 * Field khusus Pengabdian (`lokasi`, `dampak_singkat`) sudah ada di tabel ini sejak
 * migration awal. Pengajaran tidak butuh field tambahan (sesuai keputusan user).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            // ENUM agar invalid value ditolak di level DB. Penelitian tidak masuk
            // enum karena tidak ada CRUD-nya (hanya external link di navbar).
            $table->enum('tridharma_type', ['pengajaran', 'pengabdian'])
                ->nullable()
                ->after('program_studi_id')
                ->comment('NULL = Berita biasa. Selain itu = konten Tridharma.');

            // Index supaya filter by type cepat (dipakai TridharmaController frontend & admin).
            $table->index('tridharma_type', 'beritas_tridharma_type_idx');
        });
    }

    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropIndex('beritas_tridharma_type_idx');
            $table->dropColumn('tridharma_type');
        });
    }
};
