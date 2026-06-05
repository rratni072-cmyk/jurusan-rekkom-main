<?php

namespace Database\Seeders;

use App\Models\Beasiswa;
use App\Models\Berita;
use App\Models\Kategori;
use App\Models\Kegiatan;
use App\Models\Kontak;
use App\Models\ProgramStudi;
use App\Models\Slider;
use App\Models\TipeKegiatan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder utama untuk data awal website.
 *
 * Membuat akun admin dan semua data awal
 * agar website langsung bisa digunakan setelah migrasi.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        // ===== Akun Admin =====
        $admin = User::create([
            'name' => 'Admin Jurusan RK',
            'email' => 'admin@rekkom.ac.id',
            'password' => Hash::make('password'),
        ]);

        // ===== Profil Jurusan =====
        // Konten + kunci ada di ProfilJurusanSeeder (terpisah agar bisa
        // di-run independent: `php artisan db:seed --class=ProfilJurusanSeeder`).
        $this->call(ProfilJurusanSeeder::class);

        // ===== Slider Hero =====
        $sliderData = [
            [
                'judul' => 'Selamat Datang di Jurusan Rekayasa Komputer',
                'deskripsi' => 'Politeknik Pertanian Negeri Samarinda — Mencetak lulusan yang kompeten di bidang teknologi informasi dan komputer.',
                'gambar' => 'frontend/img/hero-carousel/hero-carousel-1.jpg',
                'tombol_teks' => 'Pelajari Selengkapnya',
                'tombol_url' => '/profil/visi-misi',
                'urutan' => 1,
                'is_active' => true,
            ],
            [
                'judul' => 'Fasilitas Modern',
                'deskripsi' => 'Didukung dengan laboratorium dan peralatan terkini untuk menunjang proses pembelajaran yang berkualitas.',
                'gambar' => 'frontend/img/hero-carousel/hero-carousel-2.jpg',
                'tombol_teks' => 'Lihat Fasilitas',
                'tombol_url' => '/profil/tentang-jurusan',
                'urutan' => 2,
                'is_active' => true,
            ],
            [
                'judul' => 'Program Studi Unggulan',
                'deskripsi' => 'Kurikulum yang dirancang sesuai kebutuhan industri dengan tenaga pengajar profesional dan berpengalaman.',
                'gambar' => 'frontend/img/hero-carousel/hero-carousel-3.jpg',
                'tombol_teks' => 'Lihat Program Studi',
                'tombol_url' => '/program-studi',
                'urutan' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($sliderData as $slider) {
            Slider::create($slider);
        }

        // ===== Program Studi =====
        // Field `deskripsi`/`visi`/`misi` sudah di-drop dari tabel sejak migrasi
        // 2026_05_02_021500_drop_non_akreditasi_fields. Hanya data akreditasi
        // yang perlu di-seed di sini.
        $prodiData = [
            ['nama' => 'Teknologi Geomatika', 'jenjang' => 'D3', 'akreditasi' => 'B', 'is_active' => true],
            ['nama' => 'Sistem Informasi Akuntansi', 'jenjang' => 'D3', 'akreditasi' => 'B', 'is_active' => true],
            ['nama' => 'Teknologi Rekayasa Perangkat Lunak', 'jenjang' => 'D4', 'akreditasi' => 'Baik Sekali', 'is_active' => true],
            ['nama' => 'Teknologi Rekayasa Geomatika dan Survei', 'jenjang' => 'D4', 'akreditasi' => 'Baik Sekali', 'is_active' => true],
        ];

        foreach ($prodiData as $prodi) {
            ProgramStudi::create($prodi);
        }

        // Ambil reference prodi untuk relasi FK di Berita Tridharma.
        $prodiTG = ProgramStudi::firstWhere('nama', 'Teknologi Geomatika');
        $prodiSIA = ProgramStudi::firstWhere('nama', 'Sistem Informasi Akuntansi');
        $prodiTRPL = ProgramStudi::firstWhere('nama', 'Teknologi Rekayasa Perangkat Lunak');
        $prodiTRGS = ProgramStudi::firstWhere('nama', 'Teknologi Rekayasa Geomatika dan Survei');

        // ===== Kategori Berita =====
        // Hanya untuk Berita biasa (tridharma_type IS NULL). Konten Tridharma
        // (Pengajaran & Pengabdian) tidak pakai kategori — sudah dipisah lewat
        // kolom enum `tridharma_type` di tabel beritas.
        // Editorial = tampil di sidebar widget /berita untuk filtering.
        // Topik    = sudah punya halaman/menu sendiri (Kemahasiswaan).
        $kat1 = Kategori::create(['nama' => 'Akademik', 'tipe' => Kategori::TIPE_EDITORIAL]);
        $kat2 = Kategori::create(['nama' => 'Kegiatan', 'tipe' => Kategori::TIPE_TOPIK]);
        $kat3 = Kategori::create(['nama' => 'Pengumuman', 'tipe' => Kategori::TIPE_EDITORIAL]);

        // Kategori editorial tambahan (sesuai konvensi portal berita kampus).
        $katPrestasi = Kategori::create(['nama' => 'Prestasi', 'tipe' => Kategori::TIPE_EDITORIAL]);
        $katKerjasama = Kategori::create(['nama' => 'Kerjasama', 'tipe' => Kategori::TIPE_EDITORIAL]);
        $katMaba = Kategori::create(['nama' => 'Mahasiswa Baru', 'tipe' => Kategori::TIPE_EDITORIAL]);

        // ===== Berita =====
        $beritaData = [
            [
                'judul' => 'Penerimaan Mahasiswa Baru Tahun Akademik 2026/2027',
                'ringkasan' => 'Jurusan Rekayasa Komputer membuka pendaftaran mahasiswa baru untuk tahun akademik 2026/2027.',
                'konten' => '<h2>Pendaftaran Mahasiswa Baru Telah Dibuka!</h2><p>Politeknik Pertanian Negeri Samarinda melalui Jurusan Rekayasa Komputer membuka pendaftaran mahasiswa baru untuk <strong>Tahun Akademik 2026/2027</strong>.</p><h3>Program Studi yang Tersedia</h3><ul><li><strong>D3 Teknologi Geomatika</strong> — Akreditasi B</li><li><strong>D3 Sistem Informasi Akuntansi</strong> — Akreditasi B</li><li><strong>D4 Teknologi Rekayasa Perangkat Lunak</strong> — Akreditasi Baik Sekali</li><li><strong>D4 Teknologi Rekayasa Geomatika dan Survei</strong> — Akreditasi Baik Sekali</li></ul><h3>Persyaratan</h3><ol><li>Lulusan SMA/SMK/MA sederajat</li><li>Mengisi formulir pendaftaran online</li><li>Menyerahkan fotokopi ijazah dan rapor</li><li>Pas foto 3x4 (3 lembar)</li></ol><p>Informasi lebih lanjut hubungi: <strong>(0541) 260421</strong> atau email <strong>rekkom@politani.ac.id</strong></p>',
                'tanggal_publikasi' => now()->subDays(2),
                'is_published' => true,
                'penulis_id' => $admin->id,
                'kategoris' => [$katMaba->id, $kat3->id], // Mahasiswa Baru + Pengumuman
            ],
            [
                'judul' => 'Workshop Internet of Things Bersama Telkom Indonesia',
                'ringkasan' => 'Mahasiswa Jurusan R&K mengikuti workshop IoT selama 3 hari dengan narasumber dari Telkom Indonesia.',
                'konten' => '<p>Jurusan Rekayasa Komputer berhasil menyelenggarakan <strong>Workshop Internet of Things (IoT)</strong> bekerja sama dengan <strong>PT Telkom Indonesia Tbk</strong>. Kegiatan berlangsung selama 3 hari di Laboratorium Komputer Jurusan R&K.</p><h3>Materi Workshop</h3><ul><li>Pengenalan konsep IoT dan arsitektur sistem</li><li>Pemrograman mikrokontroler ESP32</li><li>Integrasi sensor dan aktuator</li><li>Platform IoT Cloud (Antares)</li><li>Proyek akhir: Smart Agriculture Monitoring System</li></ul><p>Sebanyak <strong>60 mahasiswa</strong> dari prodi TRPL dan TG mengikuti workshop ini. Peserta mendapatkan sertifikat kompetensi dari Telkom Indonesia.</p>',
                'tanggal_publikasi' => now()->subDays(5),
                'is_published' => true,
                'penulis_id' => $admin->id,
                'kategoris' => [$katKerjasama->id], // Kerjasama (workshop bersama Telkom)
            ],
            [
                'judul' => 'Mahasiswa TRPL Raih Juara 1 Hackathon Nasional 2026',
                'ringkasan' => 'Tim mahasiswa TRPL berhasil mengalahkan 120 tim dari seluruh Indonesia dalam kompetisi Hackathon Nasional.',
                'konten' => '<p>Tim yang terdiri dari 3 mahasiswa <strong>Teknologi Rekayasa Perangkat Lunak (TRPL)</strong> berhasil meraih <strong>Juara 1</strong> pada <strong>Hackathon Nasional 2026</strong> di Jakarta.</p><h3>Anggota Tim</h3><ol><li>Muhammad Rizki Pratama (Ketua) — TRPL Semester 6</li><li>Aulia Rahma Putri — TRPL Semester 6</li><li>Dimas Adi Nugroho — TRPL Semester 4</li></ol><h3>Penghargaan</h3><ul><li>Trofi Juara 1 Hackathon Nasional 2026</li><li>Hadiah uang tunai Rp 25.000.000</li><li>Kesempatan inkubasi startup di Telkom Digital Valley</li></ul><p>Selamat kepada tim! Prestasi ini membuktikan kualitas lulusan Jurusan Rekayasa Komputer.</p>',
                'tanggal_publikasi' => now()->subDays(8),
                'is_published' => true,
                'penulis_id' => $admin->id,
                'kategoris' => [$katPrestasi->id], // Prestasi (juara hackathon nasional)
            ],
            [
                'judul' => 'Kunjungan Industri ke PT Pupuk Kaltim Bontang',
                'ringkasan' => '45 mahasiswa melaksanakan kunjungan industri untuk mempelajari penerapan teknologi informasi di sektor industri.',
                'konten' => '<p>Sebanyak <strong>45 mahasiswa</strong> Jurusan Rekayasa Komputer melaksanakan kunjungan industri ke <strong>PT Pupuk Kalimantan Timur (PKT)</strong> di Bontang.</p><h3>Agenda Kunjungan</h3><ul><li>Presentasi profil perusahaan dan divisi IT PKT</li><li>Tur fasilitas data center dan control room</li><li>Diskusi interaktif tentang karier di bidang IT industri</li><li>Demo sistem SCADA dan IoT monitoring pabrik</li></ul><p>Kegiatan ini merupakan bagian dari kurikulum mata kuliah <strong>Kerja Praktik</strong> yang wajib diikuti oleh mahasiswa semester 5.</p><p><em>Kegiatan didampingi oleh 3 dosen pembimbing dari Jurusan R&K.</em></p>',
                'tanggal_publikasi' => now()->subDays(12),
                'is_published' => true,
                'penulis_id' => $admin->id,
                'kategoris' => [$katKerjasama->id], // Kerjasama (kunjungan industri)
            ],
            [
                'judul' => 'Seminar Nasional Keamanan Siber: Membangun Ketahanan Digital',
                'ringkasan' => 'Jurusan R&K menggelar seminar nasional dengan pembicara dari BSSN dan praktisi keamanan siber.',
                'konten' => '<p>Jurusan Rekayasa Komputer menyelenggarakan <strong>Seminar Nasional Keamanan Siber</strong> dengan tema <em>"Membangun Ketahanan Digital di Era Transformasi"</em>.</p><h3>Pembicara</h3><ol><li><strong>Dr. Ir. Hinsa Siburian</strong> — Kepala BSSN</li><li><strong>Teguh Aprianto</strong> — Founder Ethical Hacker Indonesia</li><li><strong>Dr. Ahmad Rivai, M.T.</strong> — Ketua Jurusan R&K</li></ol><h3>Topik Pembahasan</h3><ul><li>Lanskap ancaman siber di Indonesia tahun 2026</li><li>Strategi pertahanan siber untuk institusi pendidikan</li><li>Peluang karier di bidang cybersecurity</li></ul><p>Seminar dihadiri oleh lebih dari <strong>200 peserta</strong> dari mahasiswa, dosen, dan praktisi TI se-Kalimantan Timur.</p>',
                'tanggal_publikasi' => now()->subDays(15),
                'is_published' => true,
                'penulis_id' => $admin->id,
                'kategoris' => [$kat1->id, $katKerjasama->id], // Akademik + Kerjasama (kolab BSSN)
            ],
            [
                'judul' => 'Pengumuman Jadwal UAS Semester Genap 2025/2026',
                'ringkasan' => 'Jadwal UAS Semester Genap 2025/2026 telah dirilis untuk seluruh program studi Jurusan R&K.',
                'konten' => '<p><strong>Ujian Akhir Semester (UAS) Genap Tahun Akademik 2025/2026</strong> akan dilaksanakan mulai tanggal <strong>15 Juni – 26 Juni 2026</strong>.</p><h3>Ketentuan Umum</h3><ol><li>Mahasiswa wajib membawa KTM dan kartu ujian</li><li>Berpakaian rapi sesuai ketentuan kampus</li><li>Hadir 15 menit sebelum ujian dimulai</li><li>Mahasiswa dengan kehadiran kurang dari 75% tidak diperkenankan mengikuti UAS</li></ol><p>Jadwal detail per mata kuliah dapat diunduh melalui menu <strong>Kemahasiswaan → Jadwal Perkuliahan</strong> di website ini.</p><p><strong>Catatan:</strong> Bagi mahasiswa yang berhalangan hadir, wajib mengajukan surat izin ke Ketua Jurusan paling lambat 3 hari sebelum jadwal ujian.</p>',
                'tanggal_publikasi' => now()->subDays(20),
                'is_published' => true,
                'penulis_id' => $admin->id,
                'kategoris' => [$kat3->id],
            ],

            // ===== Konten Tridharma > PENGAJARAN =====
            // Dipisah dari Berita biasa via kolom enum `tridharma_type`.
            [
                'judul' => 'Seminar Hasil Penelitian Dosen Jurusan R&K Tahun 2025',
                'ringkasan' => 'Jurusan R&K menggelar seminar pemaparan hasil penelitian dosen yang dibiayai DRPM dengan tema Inovasi Teknologi untuk Pertanian Modern.',
                'konten' => '<p>Politeknik Pertanian Negeri Samarinda melalui Jurusan Rekayasa dan Komputer menyelenggarakan <strong>Seminar Hasil Penelitian Dosen Tahun 2025</strong>. Acara berlangsung selama dua hari di Aula Jurusan R&K dan dihadiri 18 dosen presenter dari 4 program studi.</p><h3>Tema Utama</h3><p><em>"Inovasi Teknologi untuk Pertanian Modern dan Digitalisasi Pedesaan"</em></p><h3>Topik yang Dipaparkan</h3><ul><li>Sistem informasi geografis untuk pemetaan lahan pertanian</li><li>Aplikasi mobile untuk tracking panen komoditas unggulan</li><li>IoT sensor tanah untuk smart farming skala kecil</li><li>Blockchain untuk traceability supply chain pertanian</li></ul><p>Seminar ini dibuka oleh Direktur Politani Samarinda dan dihadiri oleh perwakilan DRPM Kemenristekdikti, pemerintah daerah Kalimantan Timur, serta kelompok tani mitra.</p>',
                'tanggal_publikasi' => now()->subDays(35),
                'is_published' => true,
                'penulis_id' => $admin->id,
                'program_studi_id' => null, // Lintas jurusan (semua prodi presenter)
                'tridharma_type' => 'pengajaran',
            ],
            [
                'judul' => 'Implementasi Kurikulum Berbasis OBE di Program Studi TRPL',
                'ringkasan' => 'Prodi TRPL mengadopsi kurikulum Outcome-Based Education (OBE) mulai TA 2025/2026 dengan fokus capaian pembelajaran lulusan.',
                'konten' => '<p>Program Studi <strong>Teknologi Rekayasa Perangkat Lunak (TRPL)</strong> Jurusan R&K resmi mengadopsi kurikulum <strong>Outcome-Based Education (OBE)</strong> mulai Tahun Akademik 2025/2026. Pendekatan ini menempatkan capaian pembelajaran lulusan (CPL) sebagai pusat desain mata kuliah, evaluasi, dan asesmen.</p><h3>Perubahan Utama</h3><ul><li>Setiap mata kuliah dipetakan ke CPL spesifik (bukan lagi daftar materi saja)</li><li>Asesmen berbasis portofolio dan proyek nyata, bukan ujian tulis tunggal</li><li>Keterlibatan industri dalam evaluasi capaian lulusan per akhir semester</li><li>Feedback loop dari alumni untuk update kurikulum berkelanjutan</li></ul><h3>Dampak untuk Mahasiswa</h3><p>Mahasiswa TRPL angkatan 2025 dan seterusnya akan mengikuti pola pembelajaran yang lebih terstruktur dengan fokus pada kompetensi kerja nyata di industri perangkat lunak.</p>',
                'tanggal_publikasi' => now()->subDays(60),
                'is_published' => true,
                'penulis_id' => $admin->id,
                'program_studi_id' => $prodiTRPL?->id,
                'tridharma_type' => 'pengajaran',
            ],
            [
                'judul' => 'Workshop Metode Pembelajaran Project-Based Learning untuk Dosen R&K',
                'ringkasan' => 'Workshop dua hari membahas penerapan PjBL dalam mata kuliah praktikum, studi kasus dari prodi TRPL dan SIA.',
                'konten' => '<p>Jurusan R&K menyelenggarakan <strong>Workshop Project-Based Learning (PjBL)</strong> untuk dosen pengampu mata kuliah praktikum. Workshop berlangsung dua hari dengan narasumber dari Direktorat Pembelajaran dan Kemahasiswaan.</p><h3>Materi Workshop</h3><ol><li>Konsep dasar PjBL dan perbedaannya dengan problem-based learning</li><li>Merancang proyek mahasiswa yang relevan dengan industri</li><li>Rubrik asesmen autentik untuk produk mahasiswa</li><li>Studi kasus sukses: Proyek IoT Smart Farming (TRPL) dan Aplikasi Akuntansi UMKM (SIA)</li></ol><p>Workshop dihadiri oleh 24 dosen dari 4 prodi di lingkungan Jurusan R&K.</p>',
                'tanggal_publikasi' => now()->subDays(90),
                'is_published' => true,
                'penulis_id' => $admin->id,
                'program_studi_id' => null, // Lintas prodi
                'tridharma_type' => 'pengajaran',
            ],
            [
                'judul' => 'Persiapan Visitasi Akreditasi Prodi TRGS Tahun 2026',
                'ringkasan' => 'Tim Pengajaran TRGS mengadakan rapat persiapan visitasi akreditasi BAN-PT dengan fokus dokumen LED dan bukti kinerja prodi.',
                'konten' => '<p>Program Studi <strong>Teknologi Rekayasa Geomatika dan Survei (TRGS)</strong> memasuki tahap akhir persiapan visitasi akreditasi oleh <strong>BAN-PT</strong>. Tim akreditasi menggelar rapat koordinasi rutin setiap pekan untuk memastikan kesiapan dokumen dan stakeholder.</p><h3>Fokus Persiapan</h3><ul><li>Finalisasi dokumen Laporan Evaluasi Diri (LED)</li><li>Kompilasi bukti kinerja prodi 5 tahun terakhir</li><li>Simulasi wawancara dengan dosen, mahasiswa, dan alumni</li><li>Pengecekan sarana lab survei dan lab GIS</li></ul><p>Tim berharap akreditasi tetap pada peringkat <strong>Baik Sekali</strong> dengan catatan perbaikan minor yang dapat segera ditindaklanjuti.</p>',
                'tanggal_publikasi' => now()->subDays(120),
                'is_published' => true,
                'penulis_id' => $admin->id,
                'program_studi_id' => $prodiTRGS?->id,
                'tridharma_type' => 'pengajaran',
            ],

            // ===== Konten Tridharma > PENGABDIAN MASYARAKAT =====
            // Pengabdian punya field tambahan: `lokasi` & `dampak_singkat` untuk
            // konteks akademik (laporan akreditasi, statistik kegiatan).
            [
                'judul' => 'Pemanfaatan Limbah Rumah Tangga sebagai Sumber Energi Alternatif',
                'ringkasan' => 'Tim dosen R&K bersama mahasiswa melakukan pendampingan pemanfaatan limbah rumah tangga organik menjadi briket bahan bakar di Desa Tani Aman.',
                'konten' => '<p>Tim dosen Jurusan Rekayasa dan Komputer bersama mahasiswa melaksanakan program pengabdian masyarakat bertema pemanfaatan <strong>limbah rumah tangga organik menjadi briket bahan bakar</strong> di Desa Tani Aman, Kabupaten Kutai Kartanegara.</p><h3>Rangkaian Kegiatan</h3><ol><li>Sosialisasi potensi limbah dapur sebagai sumber energi</li><li>Pelatihan teknik pencacahan dan pengeringan bahan baku</li><li>Workshop pembuatan briket dengan mesin press sederhana</li><li>Pendampingan produksi berkelanjutan selama 3 bulan</li></ol><h3>Hasil Program</h3><p>Sebanyak <strong>25 keluarga binaan</strong> berhasil memproduksi briket secara mandiri, dengan output rata-rata 15 kg/minggu per keluarga. Briket digunakan untuk memasak menggantikan LPG dan sisanya dijual ke pasar lokal.</p>',
                'tanggal_publikasi' => now()->subDays(45),
                'is_published' => true,
                'penulis_id' => $admin->id,
                'program_studi_id' => null, // Lintas jurusan
                'lokasi' => 'Desa Tani Aman, Kutai Kartanegara',
                'dampak_singkat' => '25 keluarga binaan',
                'tridharma_type' => 'pengabdian',
            ],
            [
                'judul' => 'Pelatihan Digital Marketing untuk UMKM Kecamatan Samarinda Seberang',
                'ringkasan' => 'Tim Pengabdian Prodi SIA mengadakan pelatihan dasar digital marketing untuk 30 pelaku UMKM di Kecamatan Samarinda Seberang.',
                'konten' => '<p>Tim Pengabdian Masyarakat <strong>Program Studi Sistem Informasi Akuntansi (SIA)</strong> mengadakan pelatihan <strong>Digital Marketing untuk UMKM</strong> bagi 30 pelaku usaha di Kecamatan Samarinda Seberang.</p><h3>Materi Pelatihan</h3><ul><li>Dasar branding produk untuk UMKM</li><li>Fotografi produk dengan smartphone</li><li>Onboarding ke marketplace (Tokopedia, Shopee, Lazada)</li><li>Strategi konten Instagram dan TikTok untuk produk lokal</li></ul><h3>Dampak Pasca-Pelatihan</h3><p>Setelah tiga bulan pasca-pelatihan, <strong>12 UMKM</strong> berhasil onboarding ke marketplace dengan omset rata-rata naik <strong>35%</strong>. Dua UMKM binaan bahkan menembus order dari luar Kalimantan Timur.</p>',
                'tanggal_publikasi' => now()->subDays(75),
                'is_published' => true,
                'penulis_id' => $admin->id,
                'program_studi_id' => $prodiSIA?->id,
                'lokasi' => 'Kec. Samarinda Seberang',
                'dampak_singkat' => '30 UMKM binaan',
                'tridharma_type' => 'pengabdian',
            ],
            [
                'judul' => 'Pendampingan Pengajaran Komputer Dasar untuk Siswa SDN 015 Samarinda',
                'ringkasan' => 'Program pengabdian rutin Jurusan R&K bekerja sama dengan SDN 015 Samarinda Seberang, menjangkau 120 siswa kelas 5-6.',
                'konten' => '<p>Program pengabdian rutin <strong>Jurusan Rekayasa dan Komputer</strong> bekerja sama dengan <strong>SDN 015 Samarinda Seberang</strong> memasuki tahun ketiga. Mahasiswa TRPL dan TG menjadi mentor untuk 120 siswa kelas 5-6 dalam materi pengenalan komputer, mengetik cepat, dan dasar internet.</p><h3>Rangkaian Sesi</h3><ul><li>Pengenalan perangkat keras komputer dan fungsi dasar</li><li>Latihan mengetik 10 jari dengan software Rapid Typing</li><li>Pengenalan aplikasi kantor (word, spreadsheet sederhana)</li><li>Literasi digital dan etika bermedia sosial</li></ul><p>Program berjalan selama satu semester (16 pertemuan) dengan dukungan rutin dari pihak sekolah dan wali murid. Beberapa siswa yang menonjol didorong untuk mengikuti kompetisi tingkat kota.</p>',
                'tanggal_publikasi' => now()->subDays(110),
                'is_published' => true,
                'penulis_id' => $admin->id,
                'program_studi_id' => null, // Lintas prodi (TRPL + TG)
                'lokasi' => 'SDN 015 Samarinda',
                'dampak_singkat' => '120 siswa',
                'tridharma_type' => 'pengabdian',
            ],
            [
                'judul' => 'Pemetaan Wilayah Berbasis GIS untuk Perencanaan Tata Ruang Kelurahan Loa Buah',
                'ringkasan' => 'Dosen TRGS bersama 12 mahasiswa melakukan pemetaan wilayah Kelurahan Loa Buah menggunakan drone dan teknologi GIS.',
                'konten' => '<p>Dosen <strong>Program Studi TRGS</strong> bersama 12 mahasiswa melaksanakan program pengabdian pemetaan wilayah <strong>Kelurahan Loa Buah</strong>, Samarinda Utara, menggunakan teknologi drone dan Geographic Information System (GIS).</p><h3>Rangkaian Pekerjaan</h3><ol><li>Survei lapangan dan pengumpulan ground control point (GCP)</li><li>Akuisisi data foto udara dengan drone DJI Mavic 3</li><li>Pengolahan data di software Pix4D dan QGIS</li><li>Produksi peta tematik: topografi, tata guna lahan, kerentanan banjir</li></ol><h3>Hasil</h3><p>Peta wilayah seluas <strong>2,4 km²</strong> diserahkan ke Lurah Loa Buah sebagai data dasar perencanaan tata ruang dan mitigasi banjir. Data juga dihibahkan ke Dinas Tata Ruang Kota Samarinda sebagai referensi.</p>',
                'tanggal_publikasi' => now()->subDays(150),
                'is_published' => true,
                'penulis_id' => $admin->id,
                'program_studi_id' => $prodiTRGS?->id,
                'lokasi' => 'Kelurahan Loa Buah, Samarinda',
                'dampak_singkat' => '2,4 km² terpetakan',
                'tridharma_type' => 'pengabdian',
            ],
        ];

        foreach ($beritaData as $data) {
            $kategoriIds = $data['kategoris'] ?? [];
            unset($data['kategoris']);
            $berita = Berita::create($data);
            if (! empty($kategoriIds)) {
                $berita->kategoris()->attach($kategoriIds);
            }
        }

        // ===== Kontak Jurusan =====
        // Koordinat & embed URL ngarah ke Politeknik Pertanian Negeri Samarinda
        // (Jalan Samratulangi, Sungai Keledang, Samarinda Seberang). Embed URL pakai
        // Place ID resmi Google Maps (!1s0x2df68080334dac99%3A0x5327a22a4028b267)
        // sehingga marker otomatis ter-label "Politeknik Pertanian Negeri Samarinda".
        Kontak::create([
            'alamat' => 'Jalan Samratulangi, Sungai Keledang, Kec. Samarinda Seberang, Kota Samarinda, Kalimantan Timur 75131',
            'email' => 'rekkom@politani.ac.id',
            'telepon' => '(0541) 260421',
            'koordinat' => '-0.5360,117.1231',
            'google_maps_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.814342710498!2d117.12312951475658!3d-0.5360357995299879!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2df68080334dac99%3A0x5327a22a4028b267!2sPoliteknik%20Pertanian%20Negeri%20Samarinda!5e0!3m2!1sid!2sid!4v1714025000000',
            // Sosmed mengarah ke akun resmi Politani Samarinda (induk) karena
            // jurusan R&K belum punya akun sosmed dedicated. Admin bisa ganti
            // via /admin/kontak/edit kalau sudah ada akun sendiri.
            'instagram' => 'https://www.instagram.com/politani_samarinda/',
            'facebook' => 'https://www.facebook.com/infopolitanisamarinda/',
            'youtube' => 'https://www.youtube.com/@politanisamarinda',
        ]);

        // ===== Tipe Kegiatan (master) =====
        // Harus diseed SEBELUM data kegiatan karena kegiatans.tipe_kegiatan_id FK.
        $this->call(TipeKegiatanSeeder::class);
        /** @var array<string, int> slug => id */
        $tipeMap = TipeKegiatan::pluck('id', 'slug')->all();

        // ===== Kegiatan =====
        // Data dengan variasi tipe agar filter Tipe di /kemahasiswaan/kegiatan
        // bisa didemo. Kombinasi event lampau + mendatang + variasi prodi target.
        $kegiatanData = [
            [
                'judul' => 'Pelatihan Web Development dengan Laravel',
                'ringkasan' => 'Pelatihan intensif 5 hari untuk mahasiswa semester 4-6 tentang pengembangan web menggunakan framework Laravel.',
                'konten' => '<p>Jurusan R&K menyelenggarakan <strong>Pelatihan Web Development dengan Laravel</strong> selama 5 hari untuk meningkatkan kemampuan mahasiswa di bidang pengembangan web.</p><h3>Detail Kegiatan</h3><ul><li><strong>Tanggal:</strong> 10–14 Mei 2026</li><li><strong>Waktu:</strong> 08.00–16.00 WITA</li><li><strong>Tempat:</strong> Lab Komputer 2, Gedung Jurusan R&K</li><li><strong>Peserta:</strong> Mahasiswa TRPL Semester 4–6 (kuota 40 orang)</li></ul><h3>Materi</h3><ol><li>Setup environment dan dasar Laravel</li><li>Routing, Controller, dan Blade Template</li><li>Eloquent ORM dan Database Migration</li><li>Authentication dan Authorization</li><li>Proyek akhir: Membangun aplikasi CRUD lengkap</li></ol><p>Peserta yang menyelesaikan seluruh sesi akan mendapatkan <strong>sertifikat kompetensi</strong>.</p>',
                'tanggal' => now()->addDays(15),
                'tipe_kegiatan_id' => $tipeMap['workshop'],
                'is_published' => true,
            ],
            [
                'judul' => 'Lomba Desain Poster Digital HUT Politani ke-42',
                'ringkasan' => 'Kompetisi desain poster digital terbuka untuk seluruh mahasiswa Politani dalam rangka HUT ke-42.',
                'konten' => '<p>Dalam rangka memeriahkan <strong>HUT ke-42 Politeknik Pertanian Negeri Samarinda</strong>, Jurusan Rekayasa Komputer menyelenggarakan Lomba Desain Poster Digital.</p><h3>Ketentuan</h3><ul><li>Tema: <em>"Politani Berinovasi untuk Indonesia Maju"</em></li><li>Terbuka untuk seluruh mahasiswa aktif Politani Samarinda</li><li>Karya berupa poster digital ukuran A3, format PNG/PDF</li><li>Deadline pengumpulan: 25 Mei 2026</li></ul><h3>Hadiah</h3><ul><li>Juara 1: Rp 1.500.000 + Sertifikat</li><li>Juara 2: Rp 1.000.000 + Sertifikat</li><li>Juara 3: Rp 750.000 + Sertifikat</li></ul>',
                'tanggal' => now()->addDays(37),
                'tipe_kegiatan_id' => $tipeMap['lomba'],
                'is_published' => true,
            ],
            [
                'judul' => 'Webinar: Peluang Karier di Bidang Data Science',
                'ringkasan' => 'Webinar gratis membahas peluang karier data science dengan pembicara dari Tokopedia dan Gojek.',
                'konten' => '<p>Jurusan R&K mengadakan webinar bertema <strong>"Peluang Karier di Bidang Data Science"</strong> untuk memberikan wawasan kepada mahasiswa tentang profesi yang sedang berkembang pesat.</p><h3>Pembicara</h3><ol><li><strong>Rina Kartika, M.Sc.</strong> — Lead Data Scientist, Tokopedia</li><li><strong>Fajar Nugroho</strong> — Machine Learning Engineer, Gojek</li></ol><h3>Informasi</h3><ul><li><strong>Tanggal:</strong> 20 Mei 2026</li><li><strong>Waktu:</strong> 13.00–15.30 WITA</li><li><strong>Platform:</strong> Zoom Meeting</li><li><strong>Biaya:</strong> Gratis</li></ul><p>Link pendaftaran akan dibagikan melalui grup kelas masing-masing.</p>',
                'tanggal' => now()->addDays(25),
                'tipe_kegiatan_id' => $tipeMap['seminar'],
                'is_published' => true,
            ],
            [
                'judul' => 'Kunjungan Industri ke PT Pupuk Kalimantan Timur Bontang',
                'ringkasan' => 'Kunjungan industri mahasiswa TRPL semester 5 ke divisi IT PT Pupuk Kaltim untuk studi langsung penerapan SCADA dan sistem monitoring.',
                'konten' => '<p>Sebanyak 45 mahasiswa <strong>TRPL semester 5</strong> melaksanakan kunjungan industri ke <strong>PT Pupuk Kalimantan Timur (PKT) Bontang</strong> sebagai bagian dari mata kuliah Kerja Praktik.</p><h3>Agenda</h3><ul><li>Presentasi profil PKT dan divisi IT enterprise</li><li>Tur data center dan control room SCADA</li><li>Diskusi interaktif tentang karier di industri energi</li><li>Demo sistem monitoring IoT pabrik urea</li></ul>',
                'tanggal' => now()->subDays(30),
                'tipe_kegiatan_id' => $tipeMap['kunjungan'],
                'is_published' => true,
            ],
            [
                'judul' => 'Buka Bersama Civitas Akademika R&K Ramadhan 1447H',
                'ringkasan' => 'HIMA R&K menginisiasi acara buka puasa bersama dosen, tendik, dan mahasiswa untuk mempererat silaturahmi di bulan Ramadhan.',
                'konten' => '<p>Himpunan Mahasiswa (HIMA) Jurusan Rekayasa dan Komputer menginisiasi acara <strong>Buka Bersama Ramadhan 1447H</strong> untuk seluruh civitas akademika jurusan. Acara dihadiri sekitar 150 peserta: dosen, tenaga kependidikan, dan mahasiswa aktif.</p><h3>Rangkaian Acara</h3><ol><li>Tausiyah Ramadhan oleh ustaz undangan</li><li>Buka puasa bersama dengan menu khas Kaltim</li><li>Sharing session dosen-mahasiswa</li><li>Shalat Maghrib dan Isya berjamaah</li></ol>',
                'tanggal' => now()->subDays(60),
                'tipe_kegiatan_id' => $tipeMap['hima'],
                'is_published' => true,
            ],
            [
                'judul' => 'Seminar Penyusunan KRS Mahasiswa Baru TA 2026/2027',
                'ringkasan' => 'Bimbingan akademik wajib untuk mahasiswa baru 2026/2027 sebelum perkuliahan dimulai.',
                'konten' => '<p>Seluruh mahasiswa baru Jurusan R&K TA 2026/2027 wajib mengikuti <strong>Seminar Penyusunan KRS</strong> sebelum perkuliahan dimulai. Kegiatan berlangsung di Aula Politani Samarinda.</p><h3>Materi</h3><ul><li>Pengenalan struktur kurikulum per prodi</li><li>Aturan pengambilan mata kuliah dan SKS</li><li>Panduan penggunaan SIAKAD</li><li>Konsultasi dengan dosen pembimbing akademik</li></ul>',
                'tanggal' => now()->addDays(60),
                'tipe_kegiatan_id' => $tipeMap['akademik'],
                'is_published' => true,
            ],
            [
                'judul' => 'Workshop IoT Bersama Telkom Indonesia untuk Mahasiswa TRPL & TG',
                'ringkasan' => 'Pelatihan IoT & Smart Farming selama 3 hari untuk 60 mahasiswa TRPL dan TG.',
                'konten' => '<p>Jurusan R&K bekerja sama dengan <strong>PT Telkom Indonesia Tbk</strong> menyelenggarakan Workshop IoT selama 3 hari di Laboratorium Komputer Jurusan.</p><h3>Materi</h3><ul><li>Pengenalan konsep IoT dan arsitektur sistem</li><li>Pemrograman mikrokontroler ESP32</li><li>Integrasi sensor dan aktuator</li><li>Platform IoT Cloud (Antares)</li><li>Proyek akhir: Smart Agriculture Monitoring System</li></ul><p>Sebanyak <strong>60 mahasiswa</strong> TRPL dan TG mengikuti, mendapat sertifikat kompetensi dari Telkom.</p>',
                'tanggal' => now()->subDays(180),
                'tipe_kegiatan_id' => $tipeMap['workshop'],
                'is_published' => true,
            ],
            [
                'judul' => 'Lomba Programming Antar Mahasiswa Politani 2025',
                'ringkasan' => 'Kompetisi internal jurusan dengan kategori Web Development, Mobile App, dan Algoritma.',
                'konten' => '<p>Jurusan R&K mengadakan Lomba Programming internal dengan 3 kategori kompetisi: Web Development, Mobile App, dan Algoritma Kompetitif. Diikuti 24 tim dari seluruh prodi.</p><h3>Juara</h3><ul><li><strong>Web Development:</strong> Tim TRPL semester 5 (aplikasi manajemen inventaris laboratorium)</li><li><strong>Mobile App:</strong> Tim TRPL semester 3 (aplikasi kehadiran kuliah berbasis GPS)</li><li><strong>Algoritma:</strong> Tim lintas prodi (solusi 8 dari 10 soal dalam 4 jam)</li></ul>',
                'tanggal' => now()->subDays(240),
                'tipe_kegiatan_id' => $tipeMap['lomba'],
                'is_published' => true,
            ],
        ];

        foreach ($kegiatanData as $data) {
            Kegiatan::create($data);
        }

        // ===== Beasiswa =====
        // Data di-adaptasi dari halaman beasiswa website PBL lama (9 beasiswa).
        // `url_info` = null untuk beasiswa internal/tanpa portal online (CTA fallback ke /kontak).
        // `url_info` = URL resmi untuk beasiswa dengan portal publik.
        Beasiswa::create([
            'nama' => 'Beasiswa Bank Indonesia',
            'penyelenggara' => 'Bank Indonesia',
            'deskripsi' => '<p>Beasiswa Bank Indonesia untuk seluruh Perguruan Tinggi di Indonesia. Penerima beasiswa ini akan diberikan bantuan berupa dana <strong>Rp 1 juta/bulan selama 1 tahun</strong>, mendapatkan pelatihan untuk meningkatkan kompetensi dan mendapat kesempatan untuk mengembangkan karakter dalam sebuah komunitas disebut GenBI (Generasi Baru Indonesia).</p>',
            'url_info' => 'https://www.bi.go.id/id/fungsi-utama/stabilitas-sistem-keuangan/beasiswa-bi/',
            'is_active' => true,
        ]);

        Beasiswa::create([
            'nama' => 'Gratispol',
            'penyelenggara' => 'Pemerintah Provinsi Kalimantan Timur',
            'deskripsi' => '<p>Bantuan biaya pendidikan untuk mahasiswa dari Kalimantan Timur. Program <strong>Gratispol</strong> memberikan pembiayaan komprehensif mulai dari biaya pendaftaran, SPP, SKS, praktikum, hingga kebutuhan hidup sehari-hari bagi mahasiswa yang berkuliah di Kaltim.</p>',
            'url_info' => 'https://pendidikan.gratispol.kaltimprov.go.id/',
            'is_active' => true,
        ]);

        Beasiswa::create([
            'nama' => 'KIP Kuliah',
            'penyelenggara' => 'Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi',
            'deskripsi' => '<p>Diberikan oleh Kemendikbud untuk calon mahasiswa baru kurang mampu. Mendapat <strong>UKT Rp 2.400.000</strong> + uang saku <strong>Rp 700.000/bulan</strong> langsung ke rekening mahasiswa.</p>',
            'url_info' => 'https://kip-kuliah.kemdiktisaintek.go.id/',
            'is_active' => true,
        ]);

        Beasiswa::create([
            'nama' => 'Beasiswa Pertamina Hulu Mahakam',
            'penyelenggara' => 'PT Pertamina Hulu Mahakam (PHM)',
            'deskripsi' => '<p>Beasiswa ini diperuntukkan bagi mahasiswa yang berasal dari wilayah sekitar PHM pada 5 kecamatan, yaitu Kecamatan <strong>Samboja, Muara Jawa, Anggana, Muara Badak,</strong> dan <strong>Sanga-Sanga</strong>. Berupa penggantian biaya SPP/UKT tahun akademik berjalan.</p>',
            'url_info' => 'https://pertaminafoundation.org/',
            'is_active' => true,
        ]);

        Beasiswa::create([
            'nama' => 'Pertamina Sobat Bumi',
            'penyelenggara' => 'Pertamina Foundation',
            'deskripsi' => '<p>Diberikan oleh Pertamina Foundation untuk mahasiswa berprestasi, peduli lingkungan, dan berkomitmen pada gaya hidup ramah lingkungan. <strong>Beasiswa ini untuk mahasiswa aktif minimal semester 3 dan dibuka setiap tahun.</strong></p>',
            'url_info' => 'https://beasiswa.pertaminafoundation.org/',
            'is_active' => true,
        ]);

        Beasiswa::create([
            'nama' => 'Beasiswa Kukar Idaman',
            'penyelenggara' => 'Pemerintah Kabupaten Kutai Kartanegara',
            'deskripsi' => '<p>Diperuntukkan bagi mahasiswa dari wilayah <strong>Kutai Kartanegara</strong>. Program beasiswa jenjang D3, D4, dan S1 dengan IPK minimal 3,00 untuk pelajar dan mahasiswa Kukar.</p>',
            'url_info' => 'https://beasiswa.kukarkab.go.id/',
            'is_active' => true,
        ]);

        Beasiswa::create([
            'nama' => 'Beasiswa KPC',
            'penyelenggara' => 'PT Kaltim Prima Coal',
            'deskripsi' => '<p>Diperuntukkan bagi mahasiswa dari wilayah <strong>Kutai Timur</strong>. Program Beasiswa Berdaya / Kutim Cerdas dari PT Kaltim Prima Coal untuk mahasiswa jenjang D3, D4, hingga S1 yang tengah menempuh pendidikan.</p>',
            'url_info' => 'https://www.kpc.co.id/media-information/scholarship/',
            'is_active' => true,
        ]);

        Beasiswa::create([
            'nama' => 'ADik',
            'penyelenggara' => 'Kementerian Pendidikan Tinggi, Sains, dan Teknologi',
            'deskripsi' => '<p>Bantuan biaya pendidikan untuk mahasiswa dari <strong>Papua, Papua Barat, daerah 3T, anak TKI,</strong> dan <strong>disabilitas</strong>. Program Afirmasi Pendidikan Tinggi (ADik) dengan pembebasan biaya kuliah penuh.</p>',
            'url_info' => 'https://adik.kemdiktisaintek.go.id/',
            'is_active' => true,
        ]);

        Beasiswa::create([
            'nama' => 'Beasiswa Inisiatif Zakat Indonesia (IZI)',
            'penyelenggara' => 'Inisiatif Zakat Indonesia (IZI)',
            'deskripsi' => '<p>Untuk mahasiswa laki-laki muslim semester ≥3, <strong>penghafal Al-Qur\'an minimal 1 juz</strong>, dari IZI. Beasiswa meliputi biaya hidup, transportasi, sarana menghafal Al-Qur\'an, dan biaya pendidikan.</p>',
            'url_info' => 'https://izi.or.id/program/program-pendidikan/',
            'is_active' => true,
        ]);

        // ===== Jadwal Perkuliahan =====
        // Tidak di-seed otomatis. Admin upload manual via menu Kelola Jadwal.
        // Untuk demo, jalankan opsional: php artisan db:seed --class=JadwalSeeder

        // ===== Pedoman Akademik =====
        // Seeder ini register 7 file PDF/Excel real yang ada di public/pedoman/
        // ke tabel + copy ke storage/app/public/pedoman/. Idempotent.
        $this->call(PedomanSeeder::class);

        // ===== Konten Tambahan untuk Demo Pagination =====
        // Tambahan 6 Berita Pengajaran, 4 Berita Pengabdian, 4 Kegiatan
        // agar list pages /tridharma/pengajaran, /tridharma/pengabdian,
        // /kemahasiswaan/kegiatan punya cukup data untuk trigger pagination.
        // Idempotent (firstOrCreate by slug) — aman di-rerun.
        $this->call(AdditionalContentSeeder::class);

        // ===== Konten Massal Realistis =====
        // Generate 21 Berita biasa, 18 Pengajaran, 18 Pengabdian, dan
        // 22 Kegiatan (12 lampau + 10 mendatang) menggunakan factory.
        // Konten dari pool template realistis tema Politani Samarinda
        // (sesuai .windsurf/rules/anti-ai-generated.md - bukan Lorem Ipsum).
        $this->call(BulkContentSeeder::class);
    }
}
