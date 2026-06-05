<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Kegiatan;
use App\Models\TipeKegiatan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kegiatan>
 *
 * Factory Kegiatan dengan state upcoming() dan past() untuk distribusi
 * tanggal yang realistis. Konten bertema akademik Politani Samarinda
 * (sesuai .windsurf/rules/anti-ai-generated.md - bukan Lorem Ipsum).
 */
class KegiatanFactory extends Factory
{
    protected $model = Kegiatan::class;

    public function definition(): array
    {
        $tpl = $this->randomTemplate();

        return [
            'judul' => $tpl['judul'],
            'slug' => Str::slug($tpl['judul']).'-'.Str::random(4),
            'ringkasan' => $tpl['ringkasan'],
            'konten' => $tpl['konten'],
            'gambar' => null,
            'tanggal' => fake()->dateTimeBetween('-90 days', '+90 days'),
            // Resolve slug → ID. Kalau master tipe belum di-seed (mis. di test
            // tanpa TipeKegiatanSeeder), firstOrCreate jadi safety net agar
            // factory tetap jalan tanpa error FK constraint.
            'tipe_kegiatan_id' => TipeKegiatan::firstOrCreate(
                ['slug' => $tpl['tipe']],
                ['label' => Str::ucfirst($tpl['tipe']), 'icon' => 'bi-tag', 'urutan' => 0, 'is_active' => true],
            )->id,
            'is_published' => true,
            'views' => fake()->numberBetween(0, 250),
        ];
    }

    /** State: kegiatan akan datang (digunakan di card "Acara Mendatang"). */
    public function upcoming(): static
    {
        return $this->state(fn () => [
            'tanggal' => fake()->dateTimeBetween('+1 days', '+120 days'),
        ]);
    }

    /** State: kegiatan yang sudah berlangsung. */
    public function past(): static
    {
        return $this->state(fn () => [
            'tanggal' => fake()->dateTimeBetween('-180 days', '-1 day'),
        ]);
    }

    /** @return array{judul:string, ringkasan:string, konten:string, tipe:string} */
    private function randomTemplate(): array
    {
        $pool = [
            [
                'tipe' => 'seminar',
                'judul' => 'Seminar Nasional Cyber Security 2026',
                'ringkasan' => 'Seminar Nasional Cyber Security menghadirkan praktisi keamanan siber dari BSSN dan akademisi membahas tantangan keamanan siber di era 5G.',
                'konten' => '<p>Politeknik Pertanian Negeri Samarinda menggelar <strong>Seminar Nasional Cyber Security 2026</strong> dengan tema <em>"Securing Indonesia in the Era of 5G"</em>. Seminar dijadwalkan berlangsung di Aula Politani dan diikuti 350 peserta dari berbagai kampus dan industri.</p><h3>Narasumber</h3><ul><li>Brigjen TNI Pol. Ahmad Subagio - Direktur Operasi Keamanan BSSN</li><li>Dr. Maya Sari, M.Kom. - Dosen Cyber Security ITB</li><li>Adi Pratama, CISM - Senior Security Analyst PT Telkom Indonesia</li></ul><h3>Topik Utama</h3><ol><li>Lanskap ancaman siber Indonesia 2026</li><li>Keamanan jaringan 5G dan IoT</li><li>Karier di bidang Cyber Security</li><li>Sertifikasi profesional yang diakui industri</li></ol><p>Pendaftaran terbatas untuk 350 peserta. Sertifikat seminar diberikan dengan e-certificate berlogo Politani.</p>',
            ],
            [
                'tipe' => 'workshop',
                'judul' => 'Workshop Pengembangan Aplikasi Mobile Flutter',
                'ringkasan' => 'Workshop intensif 3 hari pengembangan aplikasi mobile dengan Flutter untuk mahasiswa TRPL dan SIA, dibimbing developer profesional.',
                'konten' => '<p>Prodi TRPL menggelar <strong>Workshop Pengembangan Aplikasi Mobile dengan Flutter</strong> selama 3 hari intensif. Workshop diikuti 50 mahasiswa TRPL dan SIA semester 4 dan 6.</p><h3>Materi Workshop</h3><ol><li>Hari 1: Setup Flutter, dasar Dart, widget tree</li><li>Hari 2: State management dengan Provider dan Riverpod</li><li>Hari 3: Integrasi REST API, deployment ke Play Store</li></ol><p>Setiap peserta menghasilkan aplikasi mobile sederhana yang siap dipublish. Workshop dibimbing 4 developer Flutter dari komunitas Google Developer Group Samarinda.</p>',
            ],
            [
                'tipe' => 'lomba',
                'judul' => 'Lomba Karya Tulis Ilmiah Mahasiswa Politani 2026',
                'ringkasan' => 'Lomba Karya Tulis Ilmiah tingkat Politeknik se-Kaltim dengan tema teknologi terapan untuk pertanian dan perkebunan berkelanjutan.',
                'konten' => '<p>Politani Samarinda menggelar <strong>Lomba Karya Tulis Ilmiah (LKTI) Mahasiswa</strong> tingkat Politeknik se-Kalimantan Timur dengan tema <em>"Teknologi Terapan untuk Pertanian dan Perkebunan Berkelanjutan"</em>.</p><h3>Kategori Lomba</h3><ul><li>Kategori Bidang Pertanian</li><li>Kategori Bidang Perkebunan</li><li>Kategori Bidang Teknologi (R&K menjadi tuan rumah)</li><li>Kategori Bidang Sosial Ekonomi Pertanian</li></ul><h3>Hadiah</h3><ol><li>Juara 1: Rp 5 juta + Trophy + Sertifikat</li><li>Juara 2: Rp 3 juta + Trophy + Sertifikat</li><li>Juara 3: Rp 2 juta + Trophy + Sertifikat</li><li>Harapan 1-3: Rp 500 ribu + Sertifikat</li></ol><p>Pendaftaran dibuka untuk mahasiswa S1/D3/D4 aktif dengan tim 2-3 orang. Final akan dilangsungkan di Aula Politani dengan presentasi 15 menit per tim.</p>',
            ],
            [
                'tipe' => 'kunjungan',
                'judul' => 'Kunjungan Industri ke PT Pupuk Kaltim Bontang',
                'ringkasan' => 'Mahasiswa TRPL dan TRGS melakukan kunjungan industri ke PT Pupuk Kaltim Bontang untuk melihat implementasi sistem digitalisasi pabrik.',
                'konten' => '<p>Mahasiswa Prodi <strong>TRPL dan TRGS</strong> Jurusan R&K melakukan <strong>Kunjungan Industri</strong> ke PT Pupuk Kaltim, Bontang. Kunjungan diikuti 60 mahasiswa semester 5 dan didampingi 4 dosen pembimbing.</p><h3>Aktivitas Kunjungan</h3><ul><li>Tour pabrik Urea dan NPK</li><li>Sharing session dengan tim IT dan SCADA Pupuk Kaltim</li><li>Demo sistem monitoring produksi real-time</li><li>Diskusi peluang magang dan rekrutmen lulusan</li><li>Site visit ke fasilitas Quality Control</li></ul><p>Kunjungan ini memberikan gambaran nyata implementasi industri 4.0 di pabrik kimia. Beberapa mahasiswa langsung mengajukan aplikasi magang untuk semester depan.</p>',
            ],
            [
                'tipe' => 'hima',
                'judul' => 'Pelantikan Pengurus HIMA TRPL Periode 2026/2027',
                'ringkasan' => 'Himpunan Mahasiswa TRPL menggelar pelantikan pengurus baru periode 2026/2027 sekaligus orasi visi misi ketua himpunan terpilih.',
                'konten' => '<p>Himpunan Mahasiswa <strong>Teknologi Rekayasa Perangkat Lunak (HIMA TRPL)</strong> menggelar <strong>Pelantikan Pengurus Periode 2026/2027</strong>. Acara dihadiri 120 anggota himpunan dan jajaran prodi.</p><h3>Susunan Pengurus</h3><ul><li>Ketua: Andi Fadhlan (TRPL Semester 5)</li><li>Wakil Ketua: Bayu Setiawan (TRPL Semester 5)</li><li>Sekretaris: Citra Lestari (TRPL Semester 3)</li><li>Bendahara: Diana Sari (TRPL Semester 3)</li><li>5 Bidang Departemen (Akademik, Eksternal, Internal, Kewirausahaan, Sosial Masyarakat)</li></ul><h3>Program Kerja Unggulan</h3><ol><li>Sertifikasi BNSP untuk anggota</li><li>Hackathon TRPL Internal</li><li>Coding Bootcamp dengan industri mitra</li><li>Kegiatan Bakti Sosial Tahunan</li></ol><p>Acara ditutup dengan orasi ketua terpilih yang menyampaikan visi "TRPL Berdaya, Mahasiswa Berkarya".</p>',
            ],
            [
                'tipe' => 'akademik',
                'judul' => 'Sidang Tugas Akhir Mahasiswa D4 Periode 1 Tahun 2026',
                'ringkasan' => 'Sidang Tugas Akhir mahasiswa D4 TRPL dan TRGS Periode 1 Tahun 2026 dengan total 50 mahasiswa peserta sidang dari berbagai topik penelitian.',
                'konten' => '<p>Bagian Akademik Jurusan R&K mengumumkan jadwal <strong>Sidang Tugas Akhir Mahasiswa D4 Periode 1 Tahun 2026</strong>. Sidang akan diikuti 50 mahasiswa: 28 dari TRPL dan 22 dari TRGS.</p><h3>Format Sidang</h3><ul><li>Durasi: 60 menit per mahasiswa</li><li>Penguji: 1 pembimbing + 2 penguji eksternal</li><li>Format: presentasi 15 menit + tanya jawab 45 menit</li><li>Dokumen: laporan TA, slides, demo aplikasi/peta hasil</li></ul><h3>Topik TA TRPL</h3><p>Topik populer mahasiswa TRPL: aplikasi web e-commerce (8), mobile app (6), sistem informasi manajemen (7), aplikasi machine learning (4), dan tugas akhir kolaborasi industri (3).</p><h3>Topik TA TRGS</h3><p>Topik mahasiswa TRGS: pemetaan pertambangan (5), GIS untuk perencanaan wilayah (6), drone mapping pertanian (4), survey topografi (4), dan pemetaan kebencanaan (3).</p>',
            ],
            [
                'tipe' => 'seminar',
                'judul' => 'Seminar Nasional Geomatika Modern dan Aplikasinya',
                'ringkasan' => 'Seminar nasional geomatika modern menghadirkan akademisi dan praktisi industri membahas perkembangan teknologi survey dan pemetaan.',
                'konten' => '<p>Prodi TG dan TRGS menyelenggarakan <strong>Seminar Nasional Geomatika Modern dan Aplikasinya</strong>. Seminar dijadwalkan dihadiri 250 peserta dari kampus, industri tambang, perkebunan, dan dinas pemerintah Kaltim.</p><h3>Pembicara Utama</h3><ul><li>Prof. Dr. Joko Susilo - Departemen Teknik Geodesi UGM</li><li>Ir. Hendrawan, M.T. - Direktur Survey PT Topcon Indonesia</li><li>Drs. Wahyu Pratama - Kepala Bagian Survey Bappenas</li></ul><h3>Topik</h3><ol><li>Perkembangan teknologi GNSS multi-konstelasi</li><li>UAV photogrammetry untuk industri tambang</li><li>BIM (Building Information Modeling) untuk infrastruktur</li><li>LiDAR airborne untuk pemetaan hutan tropis</li></ol><p>Setelah seminar akan dilanjutkan workshop teknis 1 hari untuk peserta yang berminat mendalami salah satu topik secara hands-on.</p>',
            ],
            [
                'tipe' => 'workshop',
                'judul' => 'Workshop Akuntansi Forensik untuk Mahasiswa SIA',
                'ringkasan' => 'Workshop akuntansi forensik 2 hari untuk 60 mahasiswa SIA membahas teknik investigasi fraud dan audit forensik di lingkungan korporasi.',
                'konten' => '<p>Prodi <strong>Sistem Informasi Akuntansi (SIA)</strong> menggelar <strong>Workshop Akuntansi Forensik</strong> untuk 60 mahasiswa semester 4 dan 6. Workshop berlangsung 2 hari dengan narasumber praktisi forensik dari KAP Big Four.</p><h3>Materi</h3><ol><li>Pengantar fraud examination dan akuntansi forensik</li><li>Red flags fraud dan teknik deteksi awal</li><li>Investigasi transaksi mencurigakan</li><li>Penyusunan laporan investigasi</li><li>Studi kasus: skandal akuntansi terkenal di Indonesia</li></ol><p>Workshop dilengkapi simulasi investigasi fraud dengan kasus rekayasa. Mahasiswa dibagi tim 5 orang untuk menemukan bukti fraud dan menyajikan laporan ringkas.</p>',
            ],
            [
                'tipe' => 'lomba',
                'judul' => 'Hackathon TRPL: Solving Local Problems with Code',
                'ringkasan' => 'Hackathon 24 jam non-stop bertema solving local problems untuk mahasiswa TRPL dengan total hadiah Rp 15 juta dan kesempatan inkubasi startup.',
                'konten' => '<p>HIMA TRPL menggelar <strong>Hackathon 24 Jam Non-Stop</strong> dengan tema <em>"Solving Local Problems with Code"</em>. Hackathon diikuti 20 tim (3-4 orang/tim) yang berkompetisi membangun solusi software untuk masalah lokal Kaltim.</p><h3>Kategori Lomba</h3><ul><li>Pertanian Digital - aplikasi membantu petani Kaltim</li><li>UMKM Tech - solusi untuk UMKM lokal</li><li>Smart City Samarinda - aplikasi pelayanan publik</li><li>Education Tech - aplikasi pembelajaran</li></ul><h3>Hadiah</h3><ol><li>Juara 1 per kategori: Rp 3 juta</li><li>Best of the Best: Rp 5 juta + inkubasi startup 6 bulan</li><li>Best UI/UX: Rp 1 juta</li><li>Most Innovative: Rp 1 juta</li></ol><p>Tim pemenang Best of the Best akan mendapat mentoring inkubasi dari Bisma Tech Hub Samarinda.</p>',
            ],
            [
                'tipe' => 'kunjungan',
                'judul' => 'Kunjungan Industri ke PT Pertamina Hulu Mahakam',
                'ringkasan' => 'Mahasiswa TRGS dan TG mengikuti kunjungan industri ke PT Pertamina Hulu Mahakam, Balikpapan untuk melihat implementasi GIS di operasi migas.',
                'konten' => '<p>Mahasiswa Prodi <strong>TRGS dan TG</strong> mengikuti <strong>Kunjungan Industri</strong> ke PT Pertamina Hulu Mahakam, Balikpapan. Kunjungan diikuti 45 mahasiswa semester 5 dan didampingi 3 dosen.</p><h3>Aktivitas</h3><ul><li>Site visit ke Field Sangatta dan Senipah Processing Area</li><li>Sharing session dengan Tim GIS Pertamina</li><li>Demo aplikasi GIS untuk monitoring sumur produksi</li><li>Diskusi pengembangan karier di industri migas</li><li>Pelajaran K3 dan SOP keselamatan di area operasi migas</li></ul><p>Kunjungan ini memberikan wawasan implementasi GIS di industri hulu migas. Mahasiswa mendapat sertifikat dan goodie bag dari Pertamina.</p>',
            ],
            [
                'tipe' => 'hima',
                'judul' => 'HIMA TG Adakan Bakti Sosial Renovasi Posyandu',
                'ringkasan' => 'Himpunan Mahasiswa Teknologi Geomatika menggelar bakti sosial renovasi Posyandu di Kelurahan Sengkotek dengan dana iuran anggota.',
                'konten' => '<p>Himpunan Mahasiswa <strong>Teknologi Geomatika (HIMA TG)</strong> menggelar <strong>Bakti Sosial Renovasi Posyandu</strong> di Kelurahan Sengkotek, Samarinda Seberang. Bakti sosial melibatkan 40 anggota HIMA TG.</p><h3>Aktivitas</h3><ul><li>Pengecatan ulang ruang Posyandu</li><li>Perbaikan fasilitas sanitasi (wastafel, kamar mandi)</li><li>Donasi alat ukur antropometri (timbangan bayi, alat ukur tinggi)</li><li>Donasi 50 paket vitamin untuk balita</li><li>Edukasi singkat literasi digital untuk kader Posyandu</li></ul><h3>Sumber Dana</h3><p>Total dana yang dikeluarkan Rp 4,5 juta, bersumber dari iuran anggota dan sponsor 3 alumni TG. Posyandu akan dimanfaatkan kembali untuk kegiatan kesehatan rutin warga.</p>',
            ],
            [
                'tipe' => 'akademik',
                'judul' => 'Ujian Akhir Semester Genap 2025/2026 Dimulai',
                'ringkasan' => 'Ujian Akhir Semester Genap 2025/2026 akan dilaksanakan untuk semua prodi Jurusan R&K dengan total 4 prodi dan lebih dari 800 mahasiswa.',
                'konten' => '<p>Bagian Akademik Jurusan R&K mengumumkan dimulainya <strong>Ujian Akhir Semester (UAS) Genap 2025/2026</strong>. UAS diikuti oleh seluruh mahasiswa aktif dari 4 prodi dengan total lebih dari 800 mahasiswa.</p><h3>Jadwal Pelaksanaan</h3><ul><li>Pekan ujian: 2 minggu (mata kuliah teori dan praktek)</li><li>Hari ujian: Senin-Jumat, 4 sesi/hari</li><li>Pengumuman jadwal: 1 minggu sebelum ujian</li><li>Pengumuman nilai: maksimum 14 hari setelah ujian terakhir</li></ul><h3>Aturan UAS</h3><ol><li>Mahasiswa wajib memenuhi minimum 75% kehadiran</li><li>Membawa Kartu Tanda Mahasiswa (KTM) saat ujian</li><li>Tidak diizinkan membawa HP atau alat elektronik lain</li><li>Cheating berakibat nilai 0 dan sanksi akademik</li></ol><p>Mahasiswa yang berhalangan hadir karena sakit dapat mengajukan ujian susulan dengan surat keterangan dokter.</p>',
            ],
            [
                'tipe' => 'seminar',
                'judul' => 'Seminar Karier: Peluang Lulusan IT di Era AI',
                'ringkasan' => 'Seminar karier khusus untuk mahasiswa tingkat akhir membahas peluang dan tantangan lulusan IT di era kecerdasan buatan.',
                'konten' => '<p>Career Development Center Politani bekerjasama dengan Jurusan R&K menggelar <strong>Seminar Karier: Peluang Lulusan IT di Era AI</strong>. Seminar khusus untuk mahasiswa tingkat akhir TRPL dan SIA, total peserta 180 mahasiswa.</p><h3>Pembicara</h3><ul><li>Andi Wijaya - Senior AI Engineer Telkomsel</li><li>Sarah Putri - Recruitment Lead Tokopedia</li><li>Doni Setiawan - Founder & CEO TechHive Samarinda</li></ul><h3>Topik</h3><ol><li>Peta industri IT Indonesia 2026-2030</li><li>Job roles yang naik daun di era AI</li><li>Skill yang harus dipelajari fresh graduate</li><li>Tips wawancara dengan tech company</li><li>Membangun portofolio kompetitif di GitHub</li></ol><p>Setelah seminar dilanjutkan dengan job fair mini dengan 8 perusahaan yang membuka stand on-site recruitment.</p>',
            ],
            [
                'tipe' => 'workshop',
                'judul' => 'Workshop Penulisan Proposal PKM untuk Mahasiswa R&K',
                'ringkasan' => 'Workshop intensif penulisan proposal Program Kreativitas Mahasiswa untuk mahasiswa R&K guna meningkatkan partisipasi PKM 2026.',
                'konten' => '<p>Bagian Akademik Jurusan R&K menggelar <strong>Workshop Penulisan Proposal PKM</strong> (Program Kreativitas Mahasiswa) untuk meningkatkan partisipasi mahasiswa di PKM 2026. Workshop diikuti 80 mahasiswa dari 4 prodi.</p><h3>Skema PKM yang Dibahas</h3><ul><li>PKM-K (Kewirausahaan)</li><li>PKM-T (Penerapan Teknologi)</li><li>PKM-M (Pengabdian Masyarakat)</li><li>PKM-RE (Riset Eksakta)</li><li>PKM-KC (Karsa Cipta)</li></ul><h3>Materi Workshop</h3><ol><li>Sistematika proposal PKM Kemendikbud</li><li>Tips menemukan ide PKM yang baru dan layak</li><li>Penyusunan latar belakang dan rumusan masalah</li><li>Perencanaan biaya dan metode pelaksanaan</li><li>Sesi review proposal draft mahasiswa</li></ol><p>Mahasiswa yang lolos seleksi internal jurusan akan mendapat mentoring tambahan dari dosen pembimbing PKM hingga submisi ke Kemendikbud.</p>',
            ],
            [
                'tipe' => 'lomba',
                'judul' => 'Lomba Web Design Politani Cup 2026',
                'ringkasan' => 'Lomba Web Design tingkat SMA/SMK se-Kaltim untuk menjaring talenta muda dengan total hadiah Rp 8 juta dan beasiswa kuliah di Politani.',
                'konten' => '<p>Politani Samarinda melalui Jurusan R&K menggelar <strong>Lomba Web Design Politani Cup 2026</strong> tingkat SMA/SMK se-Kalimantan Timur. Lomba bertujuan menjaring talenta muda di bidang IT dan promosi prodi TRPL.</p><h3>Kategori Lomba</h3><ul><li>Web Design (HTML, CSS, JavaScript) - Wajib</li><li>Mobile Web Responsive - Bonus</li><li>UI/UX Quality - Penilaian khusus</li></ul><h3>Hadiah</h3><ol><li>Juara 1: Rp 4 juta + Beasiswa kuliah TRPL/SIA</li><li>Juara 2: Rp 2,5 juta + Beasiswa parsial</li><li>Juara 3: Rp 1,5 juta + Sertifikat</li><li>Pemenang Best UI/UX: Rp 500 ribu</li></ol><p>Pendaftaran gratis untuk siswa SMA/SMK aktif. Lomba final akan dilangsungkan di Lab Komputer Politani dengan format on-site coding 6 jam.</p>',
            ],
            [
                'tipe' => 'kunjungan',
                'judul' => 'Studi Lapangan TG ke Kawasan Tambang Samarinda Utara',
                'ringkasan' => 'Mahasiswa Prodi TG melakukan studi lapangan ke kawasan tambang batubara di Samarinda Utara untuk praktek pemetaan tambang.',
                'konten' => '<p>Mahasiswa <strong>Prodi D3 Teknologi Geomatika (TG)</strong> melakukan <strong>Studi Lapangan</strong> ke kawasan tambang batubara di Samarinda Utara. Studi lapangan diikuti 30 mahasiswa semester 4 dan dibimbing 3 dosen.</p><h3>Aktivitas</h3><ul><li>Pengamatan langsung operasi tambang batubara terbuka</li><li>Praktek pengukuran kontur lokasi tambang dengan total station</li><li>Demo penggunaan drone untuk monitoring stockpile batubara</li><li>Sharing session dengan mine surveyor perusahaan</li><li>Pengambilan data untuk laporan studi lapangan</li></ul><p>Studi lapangan ini bagian dari mata kuliah <em>Survey Pertambangan</em> di semester 4. Mahasiswa wajib menyusun laporan teknis individu yang akan dinilai sebagai komponen UAS.</p>',
            ],
            [
                'tipe' => 'hima',
                'judul' => 'HIMA SIA Adakan Pelatihan Excel Lanjutan untuk Anggota',
                'ringkasan' => 'Himpunan Mahasiswa SIA menggelar pelatihan Excel lanjutan untuk anggota guna meningkatkan kompetensi mahasiswa di analisis data spreadsheet.',
                'konten' => '<p>Himpunan Mahasiswa <strong>Sistem Informasi Akuntansi (HIMA SIA)</strong> menggelar <strong>Pelatihan Excel Lanjutan</strong> untuk 50 anggota himpunan. Pelatihan dibawakan 2 alumni SIA yang sudah bekerja di industri keuangan.</p><h3>Materi Pelatihan</h3><ol><li>Advanced formulas: VLOOKUP, INDEX-MATCH, SUMIFS</li><li>Pivot Table dan Pivot Chart untuk analisis data</li><li>Power Query untuk data cleaning otomatis</li><li>Macro VBA dasar untuk automasi tugas</li><li>Dashboard interaktif untuk laporan keuangan</li></ol><p>Pelatihan berlangsung 1 hari penuh dengan praktek di Lab Komputer Jurusan. Setiap peserta mendapat e-certificate dan akses materi recording untuk review mandiri.</p>',
            ],
            [
                'tipe' => 'akademik',
                'judul' => 'Yudisium Mahasiswa D3 Periode Mei 2026',
                'ringkasan' => 'Yudisium mahasiswa D3 TG dan SIA Periode Mei 2026 dengan total 38 lulusan yang akan diserahkan ijazah dan transkrip nilai.',
                'konten' => '<p>Bagian Akademik Jurusan R&K menggelar <strong>Yudisium Mahasiswa D3 Periode Mei 2026</strong>. Yudisium diikuti 38 lulusan: 18 dari Prodi TG dan 20 dari Prodi SIA.</p><h3>Susunan Acara</h3><ul><li>Pembukaan oleh Ketua Jurusan R&K</li><li>Pembacaan SK Yudisium oleh Sekretaris Jurusan</li><li>Pembacaan nama lulusan dengan IPK Cum Laude</li><li>Penyerahan ijazah dan transkrip nilai</li><li>Sambutan perwakilan lulusan</li><li>Foto bersama lulusan, orang tua, dan dosen pembimbing</li></ul><h3>Lulusan Berprestasi</h3><p>Lulusan Cum Laude (IPK ≥ 3,75): 5 mahasiswa TG dan 7 mahasiswa SIA. Lulusan terbaik akan diumumkan saat upacara wisuda formal Politani bulan Juni 2026.</p>',
            ],
            [
                'tipe' => 'seminar',
                'judul' => 'Seminar Internasional Sustainable Computing 2026',
                'ringkasan' => 'Seminar internasional sustainable computing menghadirkan akademisi dari Malaysia dan Singapore membahas teknologi ramah lingkungan.',
                'konten' => '<p>Politani Samarinda dan Jurusan R&K menyelenggarakan <strong>Seminar Internasional Sustainable Computing 2026</strong> dengan tema <em>"Green Technology for Better Future"</em>. Seminar dihadiri 200 peserta termasuk akademisi internasional.</p><h3>Pembicara Internasional</h3><ul><li>Prof. Dr. Lim Wei Ming - Universiti Sains Malaysia</li><li>Dr. Siti Aminah - Singapore Institute of Technology</li><li>Dr. Bambang Riyanto - Universitas Indonesia</li></ul><h3>Topik</h3><ol><li>Green data center dan energy efficiency</li><li>Sustainable software engineering</li><li>Carbon footprint dari komputasi cloud</li><li>Tren riset sustainable computing global</li></ol><p>Seminar berlangsung 1 hari dengan format hybrid (offline di Aula Politani + online via Zoom). Best paper akan dipublikasikan di proceeding terindeks Scopus.</p>',
            ],
            [
                'tipe' => 'workshop',
                'judul' => 'Workshop Hands-on Internet of Things untuk Mahasiswa TRPL',
                'ringkasan' => 'Workshop IoT hands-on 2 hari menggunakan ESP32 dan platform cloud untuk mahasiswa TRPL semester 5 dan 7.',
                'konten' => '<p>Prodi TRPL menggelar <strong>Workshop Hands-on Internet of Things (IoT)</strong> selama 2 hari. Workshop diikuti 40 mahasiswa TRPL semester 5 dan 7 yang mengambil mata kuliah IoT.</p><h3>Materi Workshop</h3><ol><li>Pengenalan IoT dan ekosistem perangkatnya</li><li>Programming ESP32 dengan Arduino IDE</li><li>Komunikasi MQTT dengan broker public</li><li>Integrasi sensor (DHT22, ultrasonik, pir)</li><li>Dashboard IoT dengan Blynk dan ThingSpeak</li><li>Mini project: monitoring lingkungan smart building</li></ol><p>Setiap peserta mendapat IoT starter kit (ESP32, sensor, breadboard, kabel) untuk dipakai di workshop. Hasil mini project akan dipajang di Open Lab TRPL di akhir semester.</p>',
            ],
        ];

        return fake()->randomElement($pool);
    }
}
