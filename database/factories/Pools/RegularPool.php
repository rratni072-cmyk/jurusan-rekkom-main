<?php

declare(strict_types=1);

namespace Database\Factories\Pools;

trait RegularPool
{
    /** @return array<int, array{judul:string, ringkasan:string, konten:string}> */
    protected static function regularPool(): array
    {
        return [
            [
                'judul' => 'Audiensi Mahasiswa dengan Ketua Jurusan Bahas Fasilitas Lab',
                'ringkasan' => 'BEM Jurusan R&K menggelar audiensi membahas peningkatan fasilitas laboratorium komputer dan rencana renovasi ruang praktikum.',
                'konten' => '<p>Badan Eksekutif Mahasiswa (BEM) Jurusan Rekayasa dan Komputer menggelar <strong>audiensi rutin</strong> dengan Ketua Jurusan dan jajaran pimpinan prodi. Pertemuan membahas peningkatan fasilitas laboratorium komputer, jadwal pemeliharaan rutin perangkat, dan rencana renovasi ruang praktikum.</p><h3>Poin Pembahasan</h3><ul><li>Penggantian 12 unit PC praktikum berusia di atas 6 tahun</li><li>Perluasan akses Wi-Fi di area lab dan ruang baca jurusan</li><li>Pengadaan perangkat survey terbaru untuk Prodi TG dan TRGS</li></ul><p>Ketua Jurusan menyambut baik masukan mahasiswa dan akan menindaklanjuti melalui rapat anggaran semester depan.</p>',
            ],
            [
                'judul' => 'Mahasiswa TG Ikuti Pelatihan ArcGIS Pro di Bappeda Provinsi Kaltim',
                'ringkasan' => '15 mahasiswa Teknologi Geomatika berkesempatan mengikuti pelatihan ArcGIS Pro di Bappeda Provinsi Kalimantan Timur selama 3 hari.',
                'konten' => '<p>Sebanyak 15 mahasiswa <strong>Program Studi D3 Teknologi Geomatika</strong> mendapat kesempatan mengikuti pelatihan intensif <strong>ArcGIS Pro</strong> selama 3 hari di Bappeda Provinsi Kalimantan Timur. Pelatihan ini bagian dari MoU jurusan dengan pemerintah provinsi.</p><h3>Materi Pelatihan</h3><ol><li>Migrasi proyek dari ArcMap ke ArcGIS Pro</li><li>Geoprocessing tools modern dan ModelBuilder</li><li>Visualisasi data spasial 3D</li><li>Studi kasus: pemetaan rencana tata ruang Kaltim</li></ol><p>Mahasiswa peserta mendapat sertifikat resmi dari Bappeda dan akses 6 bulan ke lisensi pelatihan ArcGIS Online.</p>',
            ],
            [
                'judul' => 'Tim Robotika R&K Lolos Final Kontes Robot Indonesia Regional V',
                'ringkasan' => 'Tim robotika Jurusan R&K dengan robot karya mahasiswa TRPL berhasil lolos babak final KRI Regional V wilayah Kalimantan-Sulawesi.',
                'konten' => '<p>Tim Robotika <strong>Jurusan Rekayasa dan Komputer</strong> berhasil meraih tiket babak final <strong>Kontes Robot Indonesia (KRI) Regional V</strong> wilayah Kalimantan dan Sulawesi. Tim membawakan robot kategori KRSBI Beroda hasil rancangan 6 mahasiswa Prodi TRPL.</p><h3>Spesifikasi Robot</h3><ul><li>Sistem kontrol berbasis ROS 2 dengan vision module</li><li>Dimensi: 30x30x40 cm, berat 4,5 kg</li><li>Sensor LiDAR 360° dan kamera stereo</li><li>Algoritma path planning custom</li></ul><p>Tim akan berlaga di babak final di Universitas Tadulako, Palu, bulan depan. Persiapan akhir dilakukan setiap hari di Lab Robotika kampus.</p>',
            ],
            [
                'judul' => 'Penandatanganan MoU Jurusan R&K dengan PT Pamapersada Nusantara',
                'ringkasan' => 'Jurusan R&K resmi menjalin kerjasama dengan PT Pamapersada Nusantara untuk program magang dan rekrutmen lulusan TRGS dan TRPL.',
                'konten' => '<p>Politeknik Pertanian Negeri Samarinda melalui Jurusan Rekayasa dan Komputer resmi menandatangani <strong>Memorandum of Understanding (MoU)</strong> dengan <strong>PT Pamapersada Nusantara</strong>. Penandatanganan dilakukan di Kantor Pusat PAMA Samarinda.</p><h3>Lingkup Kerjasama</h3><ul><li>Program magang industri bagi mahasiswa semester 5 dan 6</li><li>Rekrutmen prioritas untuk posisi mine surveyor dan junior software engineer</li><li>Sponsorship penelitian terapan bidang mining technology</li><li>Kunjungan industri rutin minimal 2 kali per tahun</li></ul><p>MoU berlaku 5 tahun dan akan dievaluasi setiap akhir tahun anggaran. Tahun pertama, sebanyak 20 mahasiswa direncanakan mengikuti program magang di operasi tambang Kaltim.</p>',
            ],
            [
                'judul' => 'Tujuh Lulusan TRPL Diterima Bekerja di Perusahaan Teknologi Nasional',
                'ringkasan' => 'Tujuh lulusan angkatan 2025 dari Prodi TRPL diterima sebagai software engineer di perusahaan teknologi terkemuka Jakarta dan Surabaya.',
                'konten' => '<p>Tujuh lulusan angkatan 2025 dari <strong>Program Studi D4 Teknologi Rekayasa Perangkat Lunak (TRPL)</strong> berhasil diterima bekerja di sejumlah perusahaan teknologi nasional terkemuka, sebelum upacara wisuda berlangsung.</p><h3>Daftar Penempatan Kerja</h3><ul><li>3 lulusan diterima di perusahaan e-commerce Jakarta sebagai Backend Engineer</li><li>2 lulusan bergabung dengan startup fintech Surabaya sebagai Frontend Developer</li><li>1 lulusan diterima sebagai Mobile Developer di perusahaan logistik nasional</li><li>1 lulusan kembali ke daerah sebagai Junior DevOps di Pemprov Kaltim</li></ul><p>Capaian ini menunjukkan kompetensi lulusan TRPL diakui industri nasional. Jurusan terus memperkuat kurikulum berbasis industri 4.0.</p>',
            ],
            [
                'judul' => 'Pembukaan Pendaftaran Beasiswa KIP Kuliah Tahap 2 Tahun 2026',
                'ringkasan' => 'Politeknik Pertanian Negeri Samarinda membuka pendaftaran KIP Kuliah Tahap 2 untuk mahasiswa baru jalur SBMPN dan jalur mandiri.',
                'konten' => '<p>Politeknik Pertanian Negeri Samarinda secara resmi membuka <strong>pendaftaran KIP Kuliah Tahap 2 Tahun 2026</strong> bagi calon mahasiswa baru yang lulus melalui jalur SBMPN dan jalur mandiri. Periode pendaftaran berlangsung mulai 15 Mei hingga 30 Juni 2026.</p><h3>Persyaratan Umum</h3><ol><li>Memiliki Kartu Indonesia Pintar (KIP) atau berasal dari keluarga prasejahtera</li><li>Lulus SMA/SMK/MA sederajat tahun 2024–2026</li><li>Diterima pada Politani Samarinda sebagai mahasiswa baru</li><li>Tidak sedang menerima beasiswa serupa dari sumber lain</li></ol><p>Pendaftaran dilakukan online melalui SIM KIP Kuliah Kemendikbud. Verifikasi berkas dilakukan di Bagian Akademik kampus.</p>',
            ],
            [
                'judul' => 'Diskusi Panel Karier Mahasiswa SIA Bersama Kantor Akuntan Publik',
                'ringkasan' => 'Prodi SIA menggelar diskusi panel karier mahasiswa bersama 3 Kantor Akuntan Publik Samarinda untuk membahas peluang kerja.',
                'konten' => '<p>Program Studi <strong>Sistem Informasi Akuntansi (SIA)</strong> menyelenggarakan diskusi panel karier mahasiswa dengan menghadirkan 3 Kantor Akuntan Publik di Samarinda. Acara dihadiri 80 mahasiswa semester 4 dan 6.</p><h3>Topik Diskusi</h3><ul><li>Peluang karier lulusan akuntansi di KAP regional</li><li>Sertifikasi profesi yang dibutuhkan (CPA, CA, BNSP)</li><li>Tantangan transformasi digital di profesi akuntan publik</li></ul><p>Mahasiswa antusias mengikuti sesi tanya jawab, dan beberapa langsung mengajukan permohonan magang ke ketiga KAP tersebut.</p>',
            ],
            [
                'judul' => 'Tim Esports R&K Juarai Turnamen Mobile Legends Antar Politeknik',
                'ringkasan' => 'Tim Esports Jurusan R&K berhasil menjuarai Turnamen Mobile Legends Antar Politeknik se-Kalimantan setelah mengalahkan 24 tim peserta.',
                'konten' => '<p>Tim Esports <strong>Jurusan Rekayasa dan Komputer</strong> berhasil meraih <strong>Juara 1 Turnamen Mobile Legends Antar Politeknik se-Kalimantan</strong>. Final dilangsungkan di Politeknik Negeri Samarinda dengan format LAN tournament.</p><h3>Hasil Akhir</h3><p>Tim R&K berhasil menyapu bersih 5 pertandingan dengan skor sempurna 5-0 di babak grup, dan memenangi best-of-five di final dengan skor 3-1. Hadiah uang tunai Rp 5 juta diserahkan langsung oleh Direktur Politani Samarinda.</p>',
            ],
            [
                'judul' => 'Workshop Manajemen Diri dan Soft Skills Mahasiswa Tingkat Akhir',
                'ringkasan' => 'Bagian Kemahasiswaan menyelenggarakan workshop wajib bagi 200 mahasiswa tingkat akhir membahas persiapan dunia kerja dan komunikasi profesional.',
                'konten' => '<p>Bagian Kemahasiswaan Jurusan R&K menyelenggarakan <strong>Workshop Manajemen Diri dan Soft Skills</strong> wajib bagi mahasiswa tingkat akhir. Sebanyak 200 mahasiswa dari 4 prodi mengikuti acara yang berlangsung dua hari di Aula Jurusan.</p><h3>Materi Workshop</h3><ol><li>Personal branding dan digital footprint</li><li>Komunikasi profesional di lingkungan kerja</li><li>Public speaking dan presentation skills</li><li>Time management dengan Pomodoro dan Eisenhower Matrix</li><li>Wawancara kerja: simulasi dan feedback</li></ol><p>Workshop dibawakan narasumber praktisi HR dari perusahaan tambang dan IT di Kaltim.</p>',
            ],
            [
                'judul' => 'Pengumuman Hasil Seleksi Asisten Laboratorium Semester Genap',
                'ringkasan' => 'Hasil seleksi calon asisten laboratorium Jurusan R&K untuk Semester Genap 2025/2026 telah diumumkan dengan total 24 mahasiswa diterima.',
                'konten' => '<p>Bagian Akademik Jurusan R&K resmi mengumumkan <strong>hasil seleksi calon asisten laboratorium</strong> untuk Semester Genap Tahun Akademik 2025/2026. Sebanyak 24 mahasiswa berhasil diterima dari 78 pelamar.</p><h3>Komposisi Asisten</h3><ul><li>Lab Pemrograman: 8 asisten (TRPL & SIA)</li><li>Lab Geomatika: 6 asisten (TG & TRGS)</li><li>Lab Jaringan Komputer: 4 asisten (TRPL)</li><li>Lab GIS: 4 asisten (TRGS)</li><li>Lab Akuntansi: 2 asisten (SIA)</li></ul><p>Asisten lab bertugas mendampingi praktikum mahasiswa, menyiapkan modul, dan membantu maintenance perangkat. Honorarium per jam mengikuti aturan kepegawaian Politani.</p>',
            ],
            [
                'judul' => 'Rapat Koordinasi Persiapan Wisuda Periode 1 TA 2025/2026',
                'ringkasan' => 'Pimpinan jurusan menggelar rapat koordinasi persiapan wisuda 84 lulusan dari 4 prodi yang akan dilangsungkan akhir Juni 2026.',
                'konten' => '<p>Pimpinan Jurusan R&K menggelar <strong>Rapat Koordinasi Persiapan Wisuda Periode 1 Tahun Akademik 2025/2026</strong>. Rapat dihadiri Ketua Jurusan, Sekretaris, 4 Kaprodi, perwakilan staf akademik, dan koordinator BEM.</p><h3>Jumlah Lulusan</h3><ul><li>D4 TRPL: 28 lulusan</li><li>D4 TRGS: 22 lulusan</li><li>D3 TG: 18 lulusan</li><li>D3 SIA: 16 lulusan</li></ul><p>Wisuda direncanakan tanggal 28 Juni 2026 di Aula Politani Samarinda. Pembekalan alumni dijadwalkan H-7, foto angkatan H-3, gladi resik H-1.</p>',
            ],
            [
                'judul' => 'Mahasiswa SIA Magang di BPJS Ketenagakerjaan Cabang Samarinda',
                'ringkasan' => 'Sebanyak 12 mahasiswa Prodi SIA semester 6 mengikuti program magang di BPJS Ketenagakerjaan Cabang Samarinda selama 4 bulan penuh.',
                'konten' => '<p>Sebanyak <strong>12 mahasiswa Program Studi D3 Sistem Informasi Akuntansi (SIA)</strong> semester 6 mengikuti program <strong>Magang Industri</strong> di Kantor Cabang BPJS Ketenagakerjaan Samarinda. Magang berlangsung 4 bulan, mulai Februari hingga Mei 2026.</p><h3>Penempatan</h3><ul><li>Bidang Keuangan: 4 mahasiswa</li><li>Bidang Pelayanan: 3 mahasiswa</li><li>Bidang Kepatuhan dan Hukum: 3 mahasiswa</li><li>Bidang IT dan Sistem Informasi: 2 mahasiswa</li></ul><p>Mahasiswa mendapat pengalaman langsung mengelola data peserta, melakukan rekonsiliasi keuangan, dan membantu sosialisasi program ke perusahaan mitra.</p>',
            ],
            [
                'judul' => 'Renovasi Gedung Lab Geomatika Capai 60 Persen, Selesai Juli',
                'ringkasan' => 'Renovasi Gedung Lab Geomatika untuk Prodi TG dan TRGS sudah mencapai progres 60 persen, ditargetkan selesai pertengahan Juli 2026.',
                'konten' => '<p>Renovasi <strong>Gedung Laboratorium Geomatika</strong> yang digunakan oleh Prodi D3 TG dan D4 TRGS telah mencapai progres <strong>60 persen</strong>. Pekerjaan ditargetkan rampung pada pertengahan Juli 2026, sehingga siap dipakai semester ganjil mendatang.</p><h3>Lingkup Renovasi</h3><ul><li>Pemasangan AC sentral di seluruh ruang praktikum</li><li>Penggantian lantai dengan vinyl anti-statis untuk lab GIS</li><li>Peningkatan kapasitas listrik dan instalasi UPS</li><li>Pengadaan rak khusus drone, total station, GNSS receiver</li></ul><p>Selama renovasi, praktikum dialihkan ke lab cadangan di Gedung Akademik Pusat. Diharapkan setelah selesai, kapasitas lab meningkat 30%.</p>',
            ],
            [
                'judul' => 'Sosialisasi Program MBKM di Jurusan R&K Diikuti 350 Mahasiswa',
                'ringkasan' => 'Bagian Akademik menggelar sosialisasi program MBKM kepada 350 mahasiswa membahas pertukaran pelajar, magang industri, dan studi independen.',
                'konten' => '<p>Bagian Akademik Jurusan R&K menggelar <strong>Sosialisasi Program Merdeka Belajar Kampus Merdeka (MBKM)</strong> kepada mahasiswa semester 4 ke atas. Sebanyak 350 mahasiswa hadir di Aula Jurusan untuk mendapat informasi dari Tim MBKM.</p><h3>Skema yang Tersedia</h3><ol><li>Pertukaran Pelajar Antarperguruan Tinggi (1 semester di kampus mitra)</li><li>Magang Industri Bersertifikat (4–6 bulan di mitra Kemendikbud)</li><li>Studi Independen (proyek mandiri di Bangkit, MSIB)</li><li>Wirausaha (inkubasi bisnis dengan pendampingan mentor)</li><li>Pengabdian Masyarakat (KKN tematik di desa mitra)</li></ol><p>Jurusan menyediakan tim pendampingan khusus untuk membantu konversi 20 SKS.</p>',
            ],
            [
                'judul' => 'Pelatihan E-Learning Politani untuk Dosen Jurusan R&K',
                'ringkasan' => 'Pusat TIK Politani menggelar pelatihan platform e-learning Moodle untuk 35 dosen Jurusan R&K guna meningkatkan kualitas pembelajaran daring.',
                'konten' => '<p>Pusat Teknologi Informasi dan Komunikasi (TIK) Politeknik Pertanian Negeri Samarinda menggelar <strong>Pelatihan E-Learning</strong> menggunakan platform Moodle untuk 35 dosen Jurusan R&K. Pelatihan berlangsung selama 2 hari di Lab Komputer Pusat.</p><h3>Materi</h3><ul><li>Manajemen kelas daring di Moodle</li><li>Pembuatan kuis interaktif dengan H5P</li><li>Integrasi video pembelajaran via Kaltura</li><li>Sistem penilaian berbasis rubrik elektronik</li><li>Pelaporan progres mahasiswa secara otomatis</li></ul><p>Setelah pelatihan, dosen akan migrasi mata kuliah ke platform e-learning institusi sebagai pendukung pembelajaran tatap muka.</p>',
            ],
            [
                'judul' => 'Audit Mutu Internal Prodi TRGS Tahun 2026 Bulan Depan',
                'ringkasan' => 'Lembaga Penjaminan Mutu Politani akan menggelar Audit Mutu Internal Prodi TRGS bulan depan dengan fokus pembelajaran dan kelulusan.',
                'konten' => '<p>Lembaga Penjaminan Mutu (LPM) Politani Samarinda akan menyelenggarakan <strong>Audit Mutu Internal (AMI) Tahun 2026</strong> untuk Program Studi D4 Teknologi Rekayasa Geomatika dan Survei (TRGS) bulan depan. Audit ini bagian dari siklus penjaminan mutu rutin tahunan.</p><h3>Aspek yang Diaudit</h3><ol><li>Pencapaian Capaian Pembelajaran Lulusan (CPL)</li><li>Kelengkapan dokumen RPS dan modul praktikum</li><li>Sistem penilaian dan evaluasi mahasiswa</li><li>Sarana dan prasarana praktikum</li><li>Kepuasan stakeholder (mahasiswa, alumni, pengguna lulusan)</li></ol><p>Tim auditor terdiri 3 dosen senior tersertifikasi auditor internal. Hasil audit menjadi bahan perbaikan untuk visitasi BAN-PT 2027.</p>',
            ],
            [
                'judul' => 'Mahasiswa TRGS Bantu Pemetaan Banjir Mahakam Hulu',
                'ringkasan' => 'Tim mahasiswa TRGS bekerjasama BPBD Kaltim melakukan pemetaan cepat lokasi bencana banjir Mahakam Hulu menggunakan drone dan GIS.',
                'konten' => '<p>Tim mahasiswa <strong>Teknologi Rekayasa Geomatika dan Survei (TRGS)</strong> Jurusan R&K bekerjasama dengan <strong>Badan Penanggulangan Bencana Daerah (BPBD) Kaltim</strong> melakukan <em>rapid mapping</em> lokasi bencana banjir di Mahakam Hulu. Tim 8 mahasiswa didampingi 2 dosen pembimbing diberangkatkan H+2 setelah bencana.</p><h3>Pekerjaan</h3><ul><li>Akuisisi foto udara dengan drone DJI Phantom 4 RTK</li><li>Pemetaan area genangan dengan teknologi GIS</li><li>Identifikasi infrastruktur publik yang rusak</li><li>Estimasi luas pemukiman terdampak</li></ul><p>Peta tematik tanggap darurat seluas <strong>12 km²</strong> diserahkan ke posko BPBD untuk membantu koordinasi distribusi bantuan.</p>',
            ],
            [
                'judul' => 'Peluncuran Sistem Pembayaran UKT Online Politani Samarinda',
                'ringkasan' => 'Politani Samarinda meluncurkan sistem pembayaran UKT online terintegrasi dengan mobile banking dan e-wallet untuk memudahkan mahasiswa.',
                'konten' => '<p>Politeknik Pertanian Negeri Samarinda secara resmi meluncurkan <strong>Sistem Pembayaran UKT Online</strong> mulai semester ini. Sistem ini menggantikan metode lama yang masih bergantung pada teller bank, dan terintegrasi dengan mobile banking serta e-wallet populer.</p><h3>Channel Pembayaran</h3><ul><li>Mobile banking: BRI, Mandiri, BNI, BCA</li><li>E-wallet: GoPay, OVO, DANA, ShopeePay</li><li>Virtual Account: semua bank nasional</li><li>Counter offline: tetap tersedia di 3 bank mitra utama</li></ul><p>Mahasiswa dapat melakukan pembayaran 24/7 tanpa antri di bank. Sistem juga memberikan pengingat H-7 sebelum jatuh tempo via email dan WhatsApp.</p>',
            ],
            [
                'judul' => 'Forum Komunikasi Alumni R&K Adakan Buka Puasa Bersama',
                'ringkasan' => 'Forum Komunikasi Alumni Jurusan R&K menggelar buka puasa bersama yang dihadiri lebih dari 120 alumni dari berbagai angkatan.',
                'konten' => '<p>Forum Komunikasi Alumni (Forkomal) <strong>Jurusan Rekayasa dan Komputer</strong> menggelar buka puasa bersama yang dihadiri lebih dari 120 alumni dari berbagai angkatan. Acara berlangsung di Aula Jurusan dengan suasana hangat dan kekeluargaan.</p><h3>Rangkaian Acara</h3><ol><li>Tausiyah singkat dari ustaz alumni angkatan 2010</li><li>Buka puasa dan shalat maghrib berjamaah</li><li>Sharing session: tantangan karier alumni</li><li>Penyerahan donasi alumni untuk lab jurusan</li><li>Foto angkatan dan pengumuman reuni akbar 2027</li></ol><p>Pada kesempatan ini, alumni angkatan pertama menyerahkan donasi senilai <strong>Rp 50 juta</strong> untuk pengadaan komputer baru di Lab Pemrograman.</p>',
            ],
            [
                'judul' => 'Jadwal Ujian Komprehensif Mahasiswa Tingkat Akhir Diumumkan',
                'ringkasan' => 'Bagian Akademik mengumumkan jadwal Ujian Komprehensif untuk 95 mahasiswa tingkat akhir 4 prodi yang akan dilangsungkan dua minggu mendatang.',
                'konten' => '<p>Bagian Akademik Jurusan R&K resmi mengumumkan <strong>Jadwal Ujian Komprehensif</strong> untuk 95 mahasiswa tingkat akhir dari 4 prodi. Ujian dijadwalkan dua minggu mendatang dan akan menjadi syarat kelulusan akhir sebelum sidang skripsi/tugas akhir.</p><h3>Komposisi Peserta</h3><ul><li>D4 TRPL: 28 mahasiswa (4 sesi)</li><li>D4 TRGS: 25 mahasiswa (3 sesi)</li><li>D3 TG: 22 mahasiswa (3 sesi)</li><li>D3 SIA: 20 mahasiswa (3 sesi)</li></ul><p>Setiap mahasiswa akan diuji 3 dosen penguji dengan total durasi 60 menit. Materi mencakup mata kuliah inti prodi, kemampuan analisis kasus industri, dan presentasi solusi.</p>',
            ],
            [
                'judul' => 'Pekan Olahraga Mahasiswa Politani Resmi Dibuka',
                'ringkasan' => 'Pekan Olahraga Mahasiswa Politani Samarinda 2026 resmi dibuka dengan 6 cabang olahraga, tim Jurusan R&K menurunkan 45 atlet.',
                'konten' => '<p>Pekan Olahraga Mahasiswa (Pormas) Politeknik Pertanian Negeri Samarinda 2026 resmi dibuka oleh Direktur. Acara pembukaan berlangsung meriah di Lapangan Politani dengan diikuti 5 jurusan dan 600 mahasiswa-atlet.</p><h3>Cabang Olahraga</h3><ol><li>Sepak bola putra (turnamen 1 minggu)</li><li>Voli putra dan putri (4 hari)</li><li>Badminton tunggal dan ganda (3 hari)</li><li>Tenis meja (3 hari)</li><li>Catur (2 hari)</li><li>E-sports Mobile Legends dan FIFA (2 hari)</li></ol><p>Tim Jurusan R&K menurunkan total <strong>45 atlet</strong>. Tahun lalu R&K finis sebagai juara umum dengan total 8 medali emas.</p>',
            ],
        ];
    }
}
