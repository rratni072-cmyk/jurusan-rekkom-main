<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Berita;
use App\Models\Kegiatan;
use Illuminate\Database\Seeder;

/**
 * Seeder konten massal untuk tampilan demo & testing UI.
 *
 * Generate konten Berita biasa, Berita Tridharma (Pengajaran/Pengabdian),
 * dan Kegiatan dengan jumlah cukup banyak agar fitur pagination, filter,
 * dan DataTables admin terasa realistis.
 *
 * Konten dibangkitkan factory dengan pool template realistis bertema
 * Politeknik Pertanian Negeri Samarinda — bukan Lorem Ipsum
 * (sesuai .windsurf/rules/anti-ai-generated.md Section 2.2).
 *
 * Targetnya:
 * - Berita biasa: 21 entry baru (total dengan seeder existing >= 25)
 * - Pengajaran:   18 entry baru (total >= 22)
 * - Pengabdian:   18 entry baru (total >= 22)
 * - Kegiatan:     22 entry baru, kombinasi past + upcoming
 */
class BulkContentSeeder extends Seeder
{
    public function run(): void
    {
        // === Berita biasa (regular) ===
        Berita::factory()->count(21)->create();

        // === Tridharma > Pengajaran ===
        Berita::factory()->count(18)->pengajaran()->create();

        // === Tridharma > Pengabdian Masyarakat ===
        Berita::factory()->count(18)->pengabdian()->create();

        // === Kegiatan ===
        // Mix: 12 kegiatan sudah lewat (untuk arsip) + 10 mendatang
        // (mengisi card "Acara Mendatang" di dashboard admin & frontend).
        Kegiatan::factory()->count(12)->past()->create();
        Kegiatan::factory()->count(10)->upcoming()->create();
    }
}
