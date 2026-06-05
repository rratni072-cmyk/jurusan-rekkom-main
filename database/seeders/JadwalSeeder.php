<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;

/**
 * Seeder Jadwal Perkuliahan — sample data realistis.
 *
 * Generate jadwal untuk 5 tahun ajaran × 2 semester × N prodi:
 * - 2025/2026 → 4 prodi (terbaru, ditandai "Periode Aktif" di frontend)
 * - 2024/2025 → 4 prodi (lengkap)
 * - 2023/2024 → 4 prodi (lengkap)
 * - 2022/2023 → 3 prodi (D4 TRGS belum buka)
 * - 2021/2022 → 2 prodi (hanya D3, demo skalabilitas)
 *
 * Total: ~30 entries → demonstrasi scalable Year Tabs (5 tabs scrollable),
 * filter semester per tab, dan multiple prodi.
 *
 * file_path memakai placeholder yang sama (dummy) agar seeder tidak butuh
 * file PDF nyata. Untuk demo/dev only.
 */
class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        // Lookup prodi by jenjang+nama (matches DatabaseSeeder:101-107).
        $prodis = [
            'tg' => ProgramStudi::where(['jenjang' => 'D3', 'nama' => 'Teknologi Geomatika'])->first(),
            'sia' => ProgramStudi::where(['jenjang' => 'D3', 'nama' => 'Sistem Informasi Akuntansi'])->first(),
            'trpl' => ProgramStudi::where(['jenjang' => 'D4', 'nama' => 'Teknologi Rekayasa Perangkat Lunak'])->first(),
            'trgs' => ProgramStudi::where(['jenjang' => 'D4', 'nama' => 'Teknologi Rekayasa Geomatika dan Survei'])->first(),
        ];

        // Konfigurasi data per tahun ajaran.
        // Setiap entry: ['ta' => 'YYYY/YYYY', 'prodi_keys' => [...]]
        // Coverage 2020/2021 → 2030/2031 untuk uji skalabilitas Year Tabs (11 tab).
        $coverage = [
            // Tahun depan (proyeksi) — uji slide horizontal di frontend.
            ['ta' => '2030/2031', 'prodi_keys' => ['tg', 'sia', 'trpl', 'trgs']],
            ['ta' => '2029/2030', 'prodi_keys' => ['tg', 'sia', 'trpl', 'trgs']],
            ['ta' => '2028/2029', 'prodi_keys' => ['tg', 'sia', 'trpl', 'trgs']],
            ['ta' => '2027/2028', 'prodi_keys' => ['tg', 'sia', 'trpl', 'trgs']],
            ['ta' => '2026/2027', 'prodi_keys' => ['tg', 'sia', 'trpl', 'trgs']],
            // Periode existing.
            ['ta' => '2025/2026', 'prodi_keys' => ['tg', 'sia', 'trpl', 'trgs']],
            ['ta' => '2024/2025', 'prodi_keys' => ['tg', 'sia', 'trpl', 'trgs']],
            ['ta' => '2023/2024', 'prodi_keys' => ['tg', 'sia', 'trpl', 'trgs']],
            ['ta' => '2022/2023', 'prodi_keys' => ['tg', 'sia', 'trpl']],
            ['ta' => '2021/2022', 'prodi_keys' => ['tg', 'sia']],
        ];

        $semesters = [Jadwal::SEMESTER_GANJIL, Jadwal::SEMESTER_GENAP];
        $placeholderFile = 'jadwal/sample-jadwal.pdf';

        $created = 0;
        foreach ($coverage as $row) {
            foreach ($row['prodi_keys'] as $key) {
                $prodi = $prodis[$key] ?? null;
                if (! $prodi) {
                    continue;
                }
                foreach ($semesters as $semester) {
                    // Skip duplikat (idempotent) — jangan double-seed.
                    $exists = Jadwal::where([
                        'program_studi_id' => $prodi->id,
                        'tahun_ajaran' => $row['ta'],
                        'semester' => $semester,
                    ])->exists();
                    if ($exists) {
                        continue;
                    }

                    Jadwal::create([
                        'program_studi_id' => $prodi->id,
                        'program_studi' => $prodi->jenjang.' - '.$prodi->nama,
                        'tahun_ajaran' => $row['ta'],
                        'semester' => $semester,
                        'file_path' => $placeholderFile,
                        'is_active' => true,
                    ]);
                    $created++;
                }
            }
        }

        $this->command?->info("JadwalSeeder: {$created} jadwal dibuat. Total di DB: ".Jadwal::count());
    }
}
