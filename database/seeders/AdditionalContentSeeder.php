<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Berita;
use App\Models\Kegiatan;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeder konten tambahan agar pagination terlihat aktif di list pages.
 *
 * Threshold pagination per halaman:
 *   - /tridharma/pengajaran  → 8 items/page (target: ≥10 items → 2 pages)
 *   - /tridharma/pengabdian  → 6 items/page (target: ≥8 items → 2 pages)
 *   - /kemahasiswaan/kegiatan → 8 items/page (target: ≥12 items → 2 pages)
 *   - /berita                → 9 items/page (sudah 14 items, tidak perlu tambah)
 *
 * Idempotent: pakai firstOrCreate by slug, aman di-rerun.
 * Run: php artisan db:seed --class=AdditionalContentSeeder
 */
class AdditionalContentSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@rekkom.ac.id')->first()
            ?? User::first();

        if (! $admin) {
            $this->command->error('User admin tidak ditemukan. Jalankan DatabaseSeeder dulu.');

            return;
        }

        // Konten Tridharma sekarang dipisah via kolom enum `tridharma_type`
        // (bukan kategori). Lihat migration `add_tridharma_type_to_beritas_table`.

        $prodiTG = ProgramStudi::where('nama', 'like', '%Geomatika%')->where('jenjang', 'D3')->first();
        $prodiSIA = ProgramStudi::where('nama', 'like', '%Akuntansi%')->first();
        $prodiTRPL = ProgramStudi::where('nama', 'like', '%Perangkat Lunak%')->first();
        $prodiTRGS = ProgramStudi::where('nama', 'like', '%Survei%')->first();

        // ============================================================
        // BERITA PENGAJARAN — Tambahan 6 item (existing 4 + 6 = 10)
        // ============================================================
        $beritaPengajaran = [
            [
                'judul' => 'Studium Generale Sertifikasi Profesi Bidang IT untuk Mahasiswa R&K',
                'ringkasan' => 'Studium generale wajib untuk seluruh mahasiswa semester akhir membahas roadmap sertifikasi profesi BNSP, MTCNA, dan AWS Cloud Practitioner.',
                'konten' => '<p>Jurusan Rekayasa dan Komputer menyelenggarakan <strong>Studium Generale</strong> bertema sertifikasi profesi bidang IT untuk membekali mahasiswa semester akhir memasuki dunia kerja.</p><h3>Materi yang Dibahas</h3><ul><li>Roadmap sertifikasi BNSP skema Junior Programmer dan Junior Network Administrator</li><li>Persiapan sertifikasi MikroTik MTCNA</li><li>Pengenalan AWS Cloud Practitioner Essentials</li><li>Strategi belajar mandiri dan pemanfaatan platform Cisco Networking Academy</li></ul><p>Kegiatan diikuti <strong>180 mahasiswa</strong> dari semua prodi jurusan dan dipandu oleh praktisi industri serta asesor BNSP.</p>',
                'tanggal_publikasi' => now()->subDays(20),
                'program_studi_id' => null,
            ],
            [
                'judul' => 'Pelatihan Penulisan Artikel Ilmiah Terindeks Sinta untuk Dosen R&K',
                'ringkasan' => 'Pelatihan dua hari untuk dosen jurusan terkait teknik penulisan dan submission artikel ke jurnal terindeks Sinta 1-3.',
                'konten' => '<p>Untuk meningkatkan produktivitas publikasi ilmiah, Jurusan R&K mengadakan <strong>Pelatihan Penulisan Artikel Ilmiah</strong> selama dua hari di ruang sidang jurusan.</p><h3>Sasaran Pelatihan</h3><ul><li>Identifikasi jurnal target sesuai bidang ilmu (Sinta 1–3)</li><li>Struktur artikel ilmiah: IMRaD dan literatur review</li><li>Penggunaan reference manager (Mendeley/Zotero)</li><li>Strategi menghadapi review dan revision</li></ul><p>Sebanyak <strong>22 dosen</strong> jurusan mengikuti pelatihan dengan target submisi minimal 1 artikel per dosen di akhir semester.</p>',
                'tanggal_publikasi' => now()->subDays(40),
                'program_studi_id' => null,
            ],
            [
                'judul' => 'Update Silabus Mata Kuliah Praktikum Geomatika Sesuai SKKNI Terbaru',
                'ringkasan' => 'Tim Pengajaran Prodi TG melakukan pembaruan silabus praktikum sesuai SKKNI Bidang Geospasial dan Pemetaan tahun 2025.',
                'konten' => '<p>Tim pengajaran Program Studi <strong>Teknologi Geomatika (TG)</strong> menyelesaikan pembaruan silabus mata kuliah praktikum agar selaras dengan SKKNI Bidang Geospasial dan Pemetaan tahun 2025.</p><h3>Mata Kuliah yang Direvisi</h3><ol><li>Praktikum Survey Terestris</li><li>Praktikum Pemetaan Digital</li><li>Praktikum Penginderaan Jauh</li><li>Praktikum SIG (Sistem Informasi Geografis)</li></ol><p>Revisi mencakup pemutakhiran software (QGIS, ArcGIS Pro), penyesuaian alat survey RTK GNSS, dan integrasi modul drone mapping.</p>',
                'tanggal_publikasi' => now()->subDays(70),
                'program_studi_id' => $prodiTG?->id,
            ],
            [
                'judul' => 'Bedah Buku Software Engineering for Industry 4.0 di Prodi TRPL',
                'ringkasan' => 'Diskusi akademik bersama dosen dan mahasiswa TRPL membedah buku referensi mata kuliah Rekayasa Perangkat Lunak terbaru.',
                'konten' => '<p>Program Studi TRPL menyelenggarakan <strong>Bedah Buku</strong> berjudul "Software Engineering for Industry 4.0: Practices and Patterns" sebagai pendamping mata kuliah Rekayasa Perangkat Lunak.</p><h3>Topik Diskusi</h3><ul><li>DevOps practices dan CI/CD pipeline</li><li>Microservices architecture pattern</li><li>Software quality assurance dengan automated testing</li><li>Studi kasus penerapan di industri manufaktur</li></ul><p>Acara dihadiri 65 mahasiswa TRPL semester 5–6 dan 8 dosen pengampu mata kuliah terkait.</p>',
                'tanggal_publikasi' => now()->subDays(95),
                'program_studi_id' => $prodiTRPL?->id,
            ],
            [
                'judul' => 'Workshop Hybrid Teaching Tools untuk Optimasi Pembelajaran Pasca Pandemi',
                'ringkasan' => 'Pelatihan dosen terkait pemanfaatan platform pembelajaran hybrid dan teknologi pendukung kelas online-offline.',
                'konten' => '<p>Untuk merespons kebutuhan pembelajaran pasca pandemi, Jurusan R&K mengadakan <strong>Workshop Hybrid Teaching Tools</strong> bagi seluruh dosen dan tenaga kependidikan.</p><h3>Tools yang Dilatih</h3><ul><li>Moodle LMS untuk manajemen kelas</li><li>OBS Studio untuk live streaming kelas</li><li>Mentimeter dan Kahoot untuk interaksi sinkron</li><li>Loom untuk recording asynchronous</li></ul><p>Output workshop: <strong>panduan SOP hybrid teaching</strong> yang menjadi referensi resmi dosen jurusan untuk semester berikutnya.</p>',
                'tanggal_publikasi' => now()->subDays(135),
                'program_studi_id' => null,
            ],
            [
                'judul' => 'Penyusunan Modul Praktikum Geomatika dan Survei Tahun Akademik 2026',
                'ringkasan' => 'Tim dosen TRGS menyelesaikan modul praktikum baru dengan integrasi alat survei modern dan studi kasus lokal Kalimantan Timur.',
                'konten' => '<p>Tim dosen Program Studi <strong>TRGS</strong> menyelesaikan penyusunan modul praktikum baru untuk Tahun Akademik 2026/2027 dengan pendekatan praktik berbasis kasus lokal.</p><h3>Modul yang Disusun</h3><ol><li>Modul Survey Pemetaan dengan Total Station Generasi Baru</li><li>Modul GNSS RTK untuk Pemetaan Presisi</li><li>Modul Fotogrametri Drone untuk Aplikasi Pertambangan</li><li>Modul GIS untuk Mitigasi Bencana</li></ol><p>Setiap modul dilengkapi <strong>studi kasus nyata</strong> dari proyek pemetaan di wilayah Kalimantan Timur, termasuk dataset terbuka untuk latihan mahasiswa.</p>',
                'tanggal_publikasi' => now()->subDays(180),
                'program_studi_id' => $prodiTRGS?->id,
            ],
        ];

        foreach ($beritaPengajaran as $data) {
            $slug = Str::slug($data['judul']);
            Berita::firstOrCreate(
                ['slug' => $slug],
                array_merge($data, [
                    'is_published' => true,
                    'penulis_id' => $admin->id,
                    'tridharma_type' => 'pengajaran',
                ])
            );
        }
        $this->command->info('Berita Pengajaran: 6 items processed.');

        // ============================================================
        // BERITA PENGABDIAN — Tambahan 4 item (existing 4 + 4 = 8)
        // ============================================================
        $beritaPengabdian = [
            [
                'judul' => 'Pelatihan Komputer Dasar untuk Karang Taruna Kelurahan Sungai Keledang',
                'ringkasan' => 'Tim pengabdian R&K mengadakan pelatihan komputer dasar untuk anggota Karang Taruna sebagai persiapan masuk dunia kerja.',
                'konten' => '<p>Tim pengabdian Jurusan R&K bersama 8 mahasiswa relawan mengadakan <strong>Pelatihan Komputer Dasar</strong> untuk anggota Karang Taruna Kelurahan Sungai Keledang, Samarinda Seberang.</p><h3>Materi Pelatihan</h3><ol><li>Pengenalan sistem operasi Windows dan Linux dasar</li><li>Microsoft Word, Excel, dan PowerPoint untuk administrasi</li><li>Etika dan keamanan internet</li><li>Pembuatan CV digital untuk lamaran kerja</li></ol><p>Pelatihan diikuti <strong>32 anggota Karang Taruna</strong> selama 4 hari di Balai Kelurahan Sungai Keledang.</p>',
                'tanggal_publikasi' => now()->subDays(50),
                'program_studi_id' => null,
                'lokasi' => 'Kelurahan Sungai Keledang, Samarinda',
                'dampak_singkat' => '32 pemuda dilatih',
            ],
            [
                'judul' => 'Pendampingan Sistem Informasi Pertanian untuk Kelompok Tani Loa Janan',
                'ringkasan' => 'Dosen TG dan mahasiswa membantu Kelompok Tani Loa Janan mengelola data lahan dan produksi via aplikasi sederhana berbasis web.',
                'konten' => '<p>Dosen Program Studi <strong>Teknologi Geomatika (TG)</strong> bersama 6 mahasiswa melakukan pendampingan teknologi informasi untuk Kelompok Tani <em>Maju Bersama</em> di Loa Janan, Kutai Kartanegara.</p><h3>Output Pengabdian</h3><ul><li>Aplikasi sederhana berbasis web untuk pencatatan produksi pertanian</li><li>Peta digital lahan kelompok tani (12 hektar) berbasis QGIS</li><li>Pelatihan operator aplikasi (3 sesi @ 2 jam)</li><li>Modul panduan penggunaan dalam Bahasa Indonesia</li></ul><p>Pengabdian rutin dilanjutkan 1x/bulan untuk monitoring penggunaan aplikasi oleh kelompok tani.</p>',
                'tanggal_publikasi' => now()->subDays(85),
                'program_studi_id' => $prodiTG?->id,
                'lokasi' => 'Loa Janan, Kutai Kartanegara',
                'dampak_singkat' => '12 hektar lahan, 28 anggota tani',
            ],
            [
                'judul' => 'Bimbingan Belajar Matematika dan Komputer untuk Anak Panti Asuhan',
                'ringkasan' => 'HIMA R&K bersama dosen pendamping mengadakan program bimbel rutin untuk anak Panti Asuhan Aisyiyah Samarinda.',
                'konten' => '<p>Himpunan Mahasiswa (HIMA) Jurusan R&K bersama 4 dosen pendamping menjalankan program <strong>bimbingan belajar rutin</strong> untuk 25 anak Panti Asuhan Aisyiyah Samarinda.</p><h3>Mata Pelajaran</h3><ul><li>Matematika dasar (kelas 4–6 SD)</li><li>Pengenalan komputer dan internet sehat</li><li>Bahasa Inggris percakapan dasar</li></ul><p>Program berjalan <strong>setiap Sabtu pagi</strong> selama 1 semester. Diakhiri dengan acara penutupan dan pembagian buku tulis serta peralatan sekolah.</p>',
                'tanggal_publikasi' => now()->subDays(125),
                'program_studi_id' => null,
                'lokasi' => 'Panti Asuhan Aisyiyah Samarinda',
                'dampak_singkat' => '25 anak binaan',
            ],
            [
                'judul' => 'Pelatihan Aplikasi Akuntansi UMKM untuk Pelaku Usaha Pasar Pagi Samarinda',
                'ringkasan' => 'Prodi SIA mengadakan pelatihan aplikasi akuntansi sederhana berbasis web untuk pedagang UMKM di Pasar Pagi Samarinda.',
                'konten' => '<p>Program Studi <strong>Sistem Informasi Akuntansi (SIA)</strong> melaksanakan pengabdian masyarakat dengan target pelaku UMKM di Pasar Pagi Samarinda.</p><h3>Aktivitas</h3><ol><li>Sosialisasi pentingnya pembukuan untuk UMKM</li><li>Pelatihan aplikasi akuntansi sederhana berbasis web (BukuKas, Aplikasi Si Apik dari Bank Indonesia)</li><li>Pendampingan input transaksi 2 minggu pertama</li><li>Konsultasi pajak UMKM dan PP 23/2018</li></ol><p>Sebanyak <strong>40 pedagang UMKM</strong> mengikuti pelatihan, dengan 28 di antaranya berhasil menerapkan pembukuan rutin setelah pendampingan.</p>',
                'tanggal_publikasi' => now()->subDays(165),
                'program_studi_id' => $prodiSIA?->id,
                'lokasi' => 'Pasar Pagi Samarinda',
                'dampak_singkat' => '40 UMKM dilatih',
            ],
        ];

        foreach ($beritaPengabdian as $data) {
            $slug = Str::slug($data['judul']);
            Berita::firstOrCreate(
                ['slug' => $slug],
                array_merge($data, [
                    'is_published' => true,
                    'penulis_id' => $admin->id,
                    'tridharma_type' => 'pengabdian',
                ])
            );
        }
        $this->command->info('Berita Pengabdian: 4 items processed.');

        // ============================================================
        // KEGIATAN — Tambahan 4 item (existing 8 + 4 = 12)
        // ============================================================
        $kegiatans = [
            [
                'judul' => 'Sharing Session Alumni R&K di Industri Cloud Computing',
                'ringkasan' => 'Diskusi terbuka bersama tiga alumni R&K yang sekarang bekerja di perusahaan cloud (AWS, GCP, Azure) tentang persiapan karier.',
                'konten' => '<p>Jurusan R&K menggelar <strong>Sharing Session</strong> dengan tiga alumni yang sukses berkarier di industri cloud computing. Acara dirancang untuk memberi gambaran realistis kepada mahasiswa terkait persiapan masuk industri teknologi.</p><h3>Pembicara Alumni</h3><ol><li>Alumni TRPL angkatan 2018 — Cloud Engineer di perusahaan fintech Jakarta</li><li>Alumni TG angkatan 2017 — DevOps Engineer di startup logistik</li><li>Alumni TRPL angkatan 2019 — Solutions Architect di konsultan IT internasional</li></ol><p><strong>Tanggal:</strong> 28 Mei 2026 | <strong>Tempat:</strong> Aula Politani | <strong>Audiens:</strong> Mahasiswa semester 5–7 (kuota 120)</p>',
                'tanggal' => now()->addDays(28),
                'tipe_kegiatan_id' => 2,
            ],
            [
                'judul' => 'Workshop UI/UX Design Bersama Praktisi Industri Startup',
                'ringkasan' => 'Pelatihan 2 hari UI/UX Design dengan tools Figma untuk mahasiswa TRPL dan SIA. Pembicara dari startup edutech.',
                'konten' => '<p>Bekerja sama dengan praktisi industri dari startup edutech, Jurusan R&K menyelenggarakan <strong>Workshop UI/UX Design</strong> menggunakan tools Figma.</p><h3>Materi Workshop</h3><ul><li>Prinsip dasar UI/UX dan design thinking</li><li>Wireframing dan prototyping di Figma</li><li>User research dan usability testing</li><li>Studi kasus: Redesign aplikasi mobile fintech</li></ul><p><strong>Peserta:</strong> 50 mahasiswa TRPL dan SIA semester 4–6 | <strong>Tanggal:</strong> 15–16 Juni 2026 | <strong>Output:</strong> Setiap peserta menghasilkan 1 prototype aplikasi sederhana yang dipresentasikan di akhir workshop.</p>',
                'tanggal' => now()->addDays(45),
                'tipe_kegiatan_id' => 1,
            ],
            [
                'judul' => 'Lomba Capture the Flag Cybersecurity Antar Politeknik Kalimantan',
                'ringkasan' => 'Kompetisi cybersecurity skala regional antar politeknik di Kalimantan dengan format CTF dan reward total Rp 10 juta.',
                'konten' => '<p>Jurusan R&K menjadi tuan rumah <strong>Lomba Capture the Flag (CTF) Cybersecurity</strong> antar politeknik se-Kalimantan. Kompetisi format jeopardy + attack-defense dengan reward total Rp 10.000.000.</p><h3>Kategori Tantangan</h3><ul><li>Web Exploitation</li><li>Cryptography</li><li>Reverse Engineering</li><li>Binary Exploitation</li><li>Forensics</li></ul><p><strong>Peserta target:</strong> 24 tim (3 anggota/tim) dari Politeknik Banjarmasin, Politeknik Pontianak, Politeknik Balikpapan, dan Politani Samarinda. <strong>Tanggal:</strong> 10–11 Juli 2026.</p>',
                'tanggal' => now()->addDays(70),
                'tipe_kegiatan_id' => 3,
            ],
            [
                'judul' => 'Studi Banding Akademik ke Politeknik Negeri Banjarmasin',
                'ringkasan' => 'Tim dosen dan mahasiswa TG melakukan studi banding ke Jurusan Teknologi Geomatika Politeknik Negeri Banjarmasin.',
                'konten' => '<p>Sebanyak <strong>15 dosen dan mahasiswa</strong> Program Studi Teknologi Geomatika melakukan kunjungan studi banding ke <strong>Jurusan Teknologi Geomatika Politeknik Negeri Banjarmasin (Poliban)</strong>.</p><h3>Agenda Kunjungan</h3><ul><li>Tur fasilitas laboratorium survei dan pemetaan Poliban</li><li>Diskusi kurikulum dan integrasi industri</li><li>Sharing program magang industri (MBKM)</li><li>Penjajakan kerjasama riset pemetaan lintas-kampus</li></ul><p>Hasil kunjungan: kesepakatan awal kolaborasi riset bidang pemetaan kawasan tambang dan rencana joint-research grant DIKTI 2027.</p>',
                'tanggal' => now()->subDays(15),
                'tipe_kegiatan_id' => 4,
            ],
        ];

        foreach ($kegiatans as $data) {
            $slug = Str::slug($data['judul']);
            Kegiatan::firstOrCreate(
                ['slug' => $slug],
                array_merge($data, ['is_published' => true])
            );
        }
        $this->command->info('Kegiatan: 4 items processed.');

        $this->command->info('AdditionalContentSeeder selesai. Pagination siap muncul di /tridharma/pengajaran, /tridharma/pengabdian, /kemahasiswaan/kegiatan.');
    }
}
