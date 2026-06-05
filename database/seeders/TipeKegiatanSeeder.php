<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\TipeKegiatan;
use Illuminate\Database\Seeder;

/**
 * Seeder default 6 tipe kegiatan untuk dropdown filter publik.
 *
 * Idempotent (updateOrCreate by slug) — aman dijalankan berulang.
 * Sinkron dengan default yang juga diinsert oleh migration
 * `change_kegiatans_tipe_to_fk_tipe_kegiatan_id`.
 */
class TipeKegiatanSeeder extends Seeder
{
    public function run(): void
    {
        $tipeList = [
            ['slug' => 'workshop',  'label' => 'Workshop',  'icon' => 'bi-tools',       'urutan' => 10],
            ['slug' => 'seminar',   'label' => 'Seminar',   'icon' => 'bi-mic',         'urutan' => 20],
            ['slug' => 'lomba',     'label' => 'Lomba',     'icon' => 'bi-trophy',      'urutan' => 30],
            ['slug' => 'kunjungan', 'label' => 'Kunjungan', 'icon' => 'bi-building',    'urutan' => 40],
            ['slug' => 'hima',      'label' => 'HIMA',      'icon' => 'bi-people',      'urutan' => 50],
            ['slug' => 'akademik',  'label' => 'Akademik',  'icon' => 'bi-mortarboard', 'urutan' => 60],
        ];

        foreach ($tipeList as $tipe) {
            TipeKegiatan::updateOrCreate(
                ['slug' => $tipe['slug']],
                [
                    'label' => $tipe['label'],
                    'icon' => $tipe['icon'],
                    'urutan' => $tipe['urutan'],
                    'is_active' => true,
                ],
            );
        }
    }
}
