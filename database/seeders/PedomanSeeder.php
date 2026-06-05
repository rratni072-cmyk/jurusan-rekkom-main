<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Pedoman;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Seeder Pedoman — register 7 file real yang ada di `database/data/pedoman/`
 * ke tabel `pedomans` + copy file ke `storage/app/public/pedoman/`
 * (sesuai asset-management.md: file upload WAJIB di storage, bukan public).
 *
 * Idempotent: pakai `updateOrCreate` berdasarkan nama_file — aman dijalankan
 * berulang tanpa bikin duplikat.
 */
class PedomanSeeder extends Seeder
{
    public function run(): void
    {
        // Sumber file — directory database/data/pedoman/ (seed data)
        $sourceDir = database_path('data/pedoman');

        // Metadata 7 file real + kategori + deskripsi manual (karena controller
        // auto-detect format saja tidak cukup untuk seeding yang rich).
        $items = [
            [
                'source' => '17.-Pedoman-Penulisan-Laporan-Magang-Industri_Politani-Samarinda-2021.pdf',
                'nama_file' => 'Pedoman Penulisan Laporan Magang Industri',
                'kategori' => Pedoman::KATEGORI_TUGAS_AKHIR,
                'deskripsi' => 'Panduan format dan tata cara penulisan laporan magang industri untuk mahasiswa Politani Samarinda (2021).',
                'urutan' => 30,
            ],
            [
                'source' => 'Buku-Wisuda-Ijazah-dan-Transkrip-Nilai.xlsx',
                'nama_file' => 'Buku Wisuda, Ijazah, dan Transkrip Nilai',
                'kategori' => Pedoman::KATEGORI_WISUDA,
                'deskripsi' => 'Format dan daftar data wisuda, ijazah, serta transkrip nilai lulusan.',
                'urutan' => 20,
            ],
            [
                'source' => 'Pedoman-Penulisan-SKPI.xlsx',
                'nama_file' => 'Pedoman Penulisan SKPI',
                'kategori' => Pedoman::KATEGORI_WISUDA,
                'deskripsi' => 'Panduan pengisian Surat Keterangan Pendamping Ijazah (SKPI) untuk lulusan.',
                'urutan' => 10,
            ],
            [
                'source' => 'Pedoman-Penulisan-Skripsi-D4.pdf',
                'nama_file' => 'Pedoman Penulisan Skripsi D4',
                'kategori' => Pedoman::KATEGORI_TUGAS_AKHIR,
                'deskripsi' => 'Panduan format dan tata cara penulisan skripsi untuk mahasiswa Program Sarjana Terapan (D4).',
                'urutan' => 20,
            ],
            [
                'source' => 'Pedoman-Penulisan-Tugas-Akhir-D3.pdf',
                'nama_file' => 'Pedoman Penulisan Tugas Akhir D3',
                'kategori' => Pedoman::KATEGORI_TUGAS_AKHIR,
                'deskripsi' => 'Panduan format dan tata cara penulisan tugas akhir untuk mahasiswa Program Diploma Tiga (D3).',
                'urutan' => 10,
            ],
            [
                'source' => 'Pedoman-Penyelenggaraan-Pendidikan.pdf',
                'nama_file' => 'Pedoman Penyelenggaraan Pendidikan',
                'kategori' => Pedoman::KATEGORI_AKADEMIK,
                'deskripsi' => 'Pedoman umum penyelenggaraan pendidikan di Politeknik Pertanian Negeri Samarinda.',
                'urutan' => 10,
            ],
            [
                'source' => 'Peraturan-Kemahasiswaan.pdf',
                'nama_file' => 'Peraturan Kemahasiswaan',
                'kategori' => Pedoman::KATEGORI_AKADEMIK,
                'deskripsi' => 'Tata tertib, hak, dan kewajiban mahasiswa Politeknik Pertanian Negeri Samarinda.',
                'urutan' => 20,
            ],
        ];

        $disk = Storage::disk('public');
        $targetDir = 'pedoman';
        $copiedCount = 0;
        $regCount = 0;

        foreach ($items as $item) {
            $sourcePath = $sourceDir.DIRECTORY_SEPARATOR.$item['source'];
            if (! File::exists($sourcePath)) {
                $this->command?->warn("  ⚠ File sumber tidak ditemukan: {$item['source']}");

                continue;
            }

            // Target path relatif di storage disk public
            $targetPath = $targetDir.'/'.$item['source'];

            // Copy file (skip kalau sudah ada di storage, tapi tetap register DB)
            if (! $disk->exists($targetPath)) {
                $disk->put($targetPath, File::get($sourcePath));
                $copiedCount++;
            }

            // Register / update di DB — idempotent via nama_file unique.
            Pedoman::updateOrCreate(
                ['nama_file' => $item['nama_file']],
                [
                    'kategori' => $item['kategori'],
                    'deskripsi' => $item['deskripsi'],
                    'format_file' => Pedoman::resolveFormatFromPath($item['source']),
                    'file_path' => $targetPath,
                    'urutan' => $item['urutan'],
                    'is_active' => true,
                ]
            );
            $regCount++;
        }

        $this->command?->info("PedomanSeeder: {$copiedCount} file disalin ke storage, {$regCount} record di-register.");
        $this->command?->info('  Total pedoman di DB: '.Pedoman::count());
    }
}
