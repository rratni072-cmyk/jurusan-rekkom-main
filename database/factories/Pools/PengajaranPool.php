<?php

declare(strict_types=1);

namespace Database\Factories\Pools;

trait PengajaranPool
{
    /** @return array<int, array{judul:string, ringkasan:string, konten:string}> */
    protected static function pengajaranPool(): array
    {
        return [
            [
                'judul' => 'Kuliah Tamu Praktisi DevOps di Prodi TRPL',
                'ringkasan' => 'Prodi TRPL menggelar kuliah tamu praktisi DevOps dari startup teknologi nasional membahas implementasi CI/CD pipeline di proyek nyata.',
                'konten' => '<p>Program Studi <strong>Teknologi Rekayasa Perangkat Lunak (TRPL)</strong> menggelar kuliah tamu dengan menghadirkan praktisi <strong>DevOps Engineer</strong> dari startup teknologi nasional. Kuliah tamu diikuti 80 mahasiswa semester 5 dan 7.</p><h3>Materi yang Disampaikan</h3><ol><li>Konsep dasar DevOps dan budaya kolaborasi tim</li><li>Implementasi CI/CD pipeline dengan GitHub Actions dan GitLab CI</li><li>Containerization dengan Docker dan orkestrasi Kubernetes</li><li>Monitoring aplikasi production dengan Prometheus dan Grafana</li><li>Studi kasus: rilis fitur tanpa downtime di startup fintech</li></ol><p>Kuliah tamu rutin ini bagian dari program <strong>industry-driven curriculum</strong> di Prodi TRPL.</p>',
            ],
            [
                'judul' => 'Pembaruan Modul Praktikum Database Sesuai Standar Industri',
                'ringkasan' => 'Tim Pengajaran TRPL dan SIA berkolaborasi memperbarui modul praktikum database dengan menambahkan materi NoSQL, performance tuning, dan keamanan data.',
                'konten' => '<p>Tim Pengajaran <strong>Prodi TRPL dan SIA</strong> menyelesaikan pembaruan menyeluruh untuk <strong>Modul Praktikum Basis Data</strong>. Pembaruan dilakukan untuk menyesuaikan materi dengan standar industri terkini, terutama tren NoSQL dan keamanan data.</p><h3>Topik Baru</h3><ul><li>Pengenalan NoSQL: MongoDB dan Redis (basic operations)</li><li>Indexing strategies untuk performance tuning</li><li>Query optimization dan execution plan analysis</li><li>Database security: SQL injection prevention, encryption at rest</li><li>Backup dan disaster recovery</li></ul><p>Setiap topik baru dibekali studi kasus dari domain industri (e-commerce, fintech, pemerintahan). Mahasiswa juga akan mengerjakan proyek mini per topik dengan deliverable yang dievaluasi rubrik kompetensi.</p>',
            ],
            [
                'judul' => 'Sosialisasi Sistem Penilaian Berbasis OBE untuk Mahasiswa SIA',
                'ringkasan' => 'Prodi SIA menggelar sosialisasi sistem penilaian berbasis Outcome-Based Education yang akan diterapkan mulai semester depan.',
                'konten' => '<p>Program Studi <strong>Sistem Informasi Akuntansi (SIA)</strong> menggelar sosialisasi <strong>Sistem Penilaian Berbasis Outcome-Based Education (OBE)</strong> kepada seluruh mahasiswa aktif. Sosialisasi diikuti 240 mahasiswa dari semester 2, 4, dan 6.</p><h3>Konsep Penilaian OBE</h3><ol><li>Penilaian berbasis Capaian Pembelajaran Mata Kuliah (CPMK)</li><li>Setiap CPMK dipetakan ke Capaian Pembelajaran Lulusan (CPL)</li><li>Asesmen autentik: portofolio, proyek, simulasi peran profesional</li><li>Bobot penilaian: 40% UAS, 30% UTS, 30% tugas/portofolio</li></ol><p>Sistem akan diberlakukan bertahap mulai semester depan untuk mahasiswa angkatan 2024 ke atas. Mahasiswa angkatan sebelumnya tetap menggunakan sistem lama hingga lulus.</p>',
            ],
            [
                'judul' => 'Workshop Penulisan Modul Berbasis Studi Kasus untuk Dosen',
                'ringkasan' => 'Workshop dua hari diikuti 20 dosen Jurusan R&K membahas teknik penulisan modul pembelajaran berbasis studi kasus industri.',
                'konten' => '<p>Jurusan R&K menyelenggarakan <strong>Workshop Penulisan Modul Berbasis Studi Kasus</strong> untuk dosen pengampu mata kuliah praktikum. Workshop berlangsung selama 2 hari dengan narasumber dari LPPM Politani dan praktisi pendidikan.</p><h3>Materi</h3><ul><li>Anatomi modul pembelajaran berbasis kasus (case-based learning)</li><li>Pemilihan studi kasus relevan dengan kompetensi industri</li><li>Teknik penulisan kasus yang menarik dan kontekstual</li><li>Desain pertanyaan reflektif untuk mendorong critical thinking</li><li>Rubrik penilaian respons mahasiswa</li></ul><p>Sebanyak 20 dosen mengikuti workshop intensif ini. Output workshop berupa draft modul mata kuliah masing-masing dosen yang akan direview dan dipublikasikan sebagai bahan ajar resmi jurusan.</p>',
            ],
            [
                'judul' => 'Penerimaan Hibah Buku Ajar Geospasial dari Penerbit Nasional',
                'ringkasan' => 'Perpustakaan Jurusan R&K menerima hibah 50 buku ajar geospasial terbitan terbaru dari Penerbit Erlangga dan Andi.',
                'konten' => '<p>Perpustakaan Jurusan R&K menerima <strong>hibah buku ajar geospasial</strong> sebanyak 50 eksemplar dari Penerbit Erlangga dan Penerbit Andi. Hibah diserahkan langsung dalam kunjungan perwakilan penerbit ke Politani Samarinda.</p><h3>Daftar Buku Hibah</h3><ul><li>Pengantar Sistem Informasi Geografis (15 eksemplar)</li><li>Penginderaan Jauh Aplikasi Pemetaan (10 eksemplar)</li><li>GPS Surveying Modern (10 eksemplar)</li><li>Kartografi Digital dan Visualisasi Spasial (8 eksemplar)</li><li>Photogrammetry untuk Praktisi (7 eksemplar)</li></ul><p>Buku hibah ini akan menjadi rujukan utama untuk mahasiswa <strong>Prodi D3 TG dan D4 TRGS</strong>.</p>',
            ],
            [
                'judul' => 'Evaluasi Kurikulum Prodi TG Tahun 2026 Libatkan Pengguna Lulusan',
                'ringkasan' => 'Prodi D3 Teknologi Geomatika menggelar FGD evaluasi kurikulum dengan melibatkan 12 perusahaan pengguna lulusan dari berbagai sektor.',
                'konten' => '<p>Program Studi <strong>D3 Teknologi Geomatika</strong> menggelar <strong>Forum Group Discussion (FGD) Evaluasi Kurikulum 2026</strong>. FGD melibatkan 12 perusahaan pengguna lulusan dari sektor pertambangan, perkebunan, pertanian presisi, dan pemerintahan.</p><h3>Perusahaan Peserta</h3><ul><li>PT Kaltim Prima Coal (KPC)</li><li>PT Indo Tambangraya Megah</li><li>PT Pupuk Kaltim Bontang</li><li>Bappeda Provinsi Kaltim</li><li>Dinas PUPR Kota Samarinda</li><li>BPN Kanwil Kaltim</li><li>Konsultan Survey 6 perusahaan lokal</li></ul><p>Stakeholder memberikan masukan: penambahan materi UAV photogrammetry, integrasi BIM untuk konstruksi, dan penguatan soft skills komunikasi proyek.</p>',
            ],
            [
                'judul' => 'Pelatihan Penyusunan Soal Berbasis HOTS untuk Dosen R&K',
                'ringkasan' => 'Pelatihan satu hari diikuti 28 dosen membahas teknik penyusunan soal berbasis Higher Order Thinking Skills.',
                'konten' => '<p>Jurusan R&K menggelar <strong>Pelatihan Penyusunan Soal Berbasis HOTS</strong> untuk 28 dosen pengampu mata kuliah teori. Pelatihan dipimpin oleh tim ahli pendidikan dari LPMP Provinsi Kaltim dan berlangsung 1 hari penuh.</p><h3>Materi Pelatihan</h3><ol><li>Konsep dasar Higher Order Thinking Skills (HOTS)</li><li>Taksonomi Bloom revisi: Analyze, Evaluate, Create</li><li>Karakteristik soal HOTS yang baik</li><li>Workshop praktek menyusun soal kasus, multiple choice analitik, dan esai</li><li>Validasi soal: judgment expert dan uji coba terbatas</li></ol><p>Setiap dosen menghasilkan minimum 5 butir soal HOTS untuk mata kuliah ampuannya, yang akan masuk ke <em>bank soal</em> jurusan.</p>',
            ],
            [
                'judul' => 'Studi Banding Kurikulum TRPL ke Politeknik Negeri Bandung',
                'ringkasan' => 'Tim Kurikulum TRPL melakukan studi banding selama 3 hari ke Polban untuk mempelajari implementasi kurikulum berbasis industri 4.0.',
                'konten' => '<p>Tim Kurikulum <strong>Program Studi D4 Teknologi Rekayasa Perangkat Lunak (TRPL)</strong> melakukan studi banding ke <strong>Politeknik Negeri Bandung (Polban)</strong> selama 3 hari. Tim 5 orang terdiri dari Kaprodi, 3 Tim Kurikulum, dan 1 Sekretaris Jurusan.</p><h3>Fokus Studi</h3><ul><li>Implementasi kurikulum berbasis Industri 4.0 di Polban</li><li>Mekanisme link & match dengan industri (advisory board)</li><li>Pengelolaan teaching factory di kampus</li><li>Sistem akreditasi internasional ASIIN</li></ul><p>Beberapa praktik baik akan diadopsi: pembentukan Industry Advisory Board, pengembangan capstone project bersama mitra industri, dan persiapan akreditasi internasional.</p>',
            ],
            [
                'judul' => 'Pengembangan Modul E-Learning Mata Kuliah Pemrograman Web',
                'ringkasan' => 'Tim Pengajaran TRPL meluncurkan modul e-learning interaktif untuk mata kuliah Pemrograman Web yang dapat diakses mahasiswa secara mandiri.',
                'konten' => '<p>Tim Pengajaran <strong>Prodi TRPL</strong> meluncurkan <strong>Modul E-Learning Interaktif</strong> untuk mata kuliah <strong>Pemrograman Web</strong>. Modul dapat diakses mahasiswa via portal e-learning Politani dengan video tutorial, latihan koding, dan auto-grading.</p><h3>Fitur Modul</h3><ul><li>120 video pendek (3–5 menit/video) dengan bahasa Indonesia</li><li>Editor koding terintegrasi (CodePen-like) untuk HTML, CSS, JavaScript</li><li>Quiz multiple choice dan koding challenge per topik</li><li>Auto-grading untuk soal koding (output checking)</li><li>Forum diskusi dan tanya jawab dengan dosen</li></ul><p>Mahasiswa dapat belajar mandiri dengan ritme sendiri. Dosen dapat memantau progres setiap mahasiswa via dashboard analytics.</p>',
            ],
            [
                'judul' => 'Penyusunan Pedoman Tugas Akhir Edisi Revisi 2026',
                'ringkasan' => 'Tim Kurikulum jurusan menyelesaikan revisi Pedoman Tugas Akhir 2026 dengan penambahan format video presentasi dan repository GitHub.',
                'konten' => '<p>Tim Kurikulum Jurusan R&K menyelesaikan <strong>Revisi Pedoman Tugas Akhir Edisi 2026</strong>. Pedoman baru menambahkan beberapa elemen kontemporer untuk menyesuaikan tugas akhir dengan praktik industri modern.</p><h3>Penambahan Utama</h3><ol><li>Format video presentasi 5 menit (selain dokumen tertulis)</li><li>Repository GitHub wajib untuk TA berbasis software (TRPL & SIA)</li><li>Dataset dan model artifacts wajib untuk TA berbasis data science</li><li>Deliverable berbasis dokumentasi teknis (technical writing standard)</li><li>Penilaian presentasi mengacu pada IEEE Style</li></ol><p>Pedoman akan disosialisasikan kepada mahasiswa semester 5 dan 7 minggu depan.</p>',
            ],
            [
                'judul' => 'Audit Internal Kelas Praktikum Prodi TRGS',
                'ringkasan' => 'Tim Pengajaran TRGS melakukan audit internal kualitas kelas praktikum di 8 mata kuliah untuk memastikan kesesuaian dengan RPS.',
                'konten' => '<p>Tim Pengajaran <strong>Program Studi D4 Teknologi Rekayasa Geomatika dan Survei (TRGS)</strong> melaksanakan <strong>Audit Internal Kelas Praktikum</strong>. Audit dilakukan untuk 8 mata kuliah praktikum yang berjalan di semester ini, sebagai bagian dari penjaminan mutu rutin.</p><h3>Aspek Audit</h3><ul><li>Kesesuaian materi praktikum dengan RPS dan modul</li><li>Kelengkapan alat dan bahan praktikum</li><li>Kualitas instruksi dosen dan asisten lab</li><li>Tingkat keterlibatan mahasiswa</li><li>Hasil penilaian praktikum (laporan, ujian praktek)</li></ul><p>Audit menemukan beberapa praktikum perlu update peralatan: total station model lama, software GIS belum lisensi terbaru, dan beberapa modul perlu diperbarui kontennya.</p>',
            ],
            [
                'judul' => 'Diskusi Reflektif Tengah Semester Dosen Mata Kuliah Inti',
                'ringkasan' => 'Forum diskusi reflektif tengah semester yang diikuti 32 dosen pengampu mata kuliah inti membahas progress mahasiswa dan strategi remediasi.',
                'konten' => '<p>Jurusan R&K menggelar <strong>Forum Diskusi Reflektif Tengah Semester</strong> yang diikuti 32 dosen pengampu mata kuliah inti dari 4 prodi. Forum ini bagian dari mekanisme refleksi pembelajaran rutin yang baru dibakukan tahun akademik ini.</p><h3>Pokok Diskusi</h3><ol><li>Progres pencapaian CPMK setiap mata kuliah inti</li><li>Mahasiswa yang berisiko gagal mata kuliah (early warning)</li><li>Strategi remediasi: ujian susulan, mentoring, peer tutoring</li><li>Keterhubungan antar mata kuliah dalam satu semester</li><li>Konsistensi penilaian antar dosen pengampu paralel</li></ol><p>Setiap dosen wajib menyusun action plan untuk mahasiswa berisiko gagal di kelasnya.</p>',
            ],
            [
                'judul' => 'Pelaporan Beban Kerja Dosen Semester Genap Diserahkan',
                'ringkasan' => 'Seluruh dosen Jurusan R&K menyelesaikan pelaporan Beban Kerja Dosen Semester Genap 2025/2026 dan menyerahkan ke LPM Politani.',
                'konten' => '<p>Seluruh <strong>62 dosen Jurusan R&K</strong> telah menyelesaikan pelaporan <strong>Beban Kerja Dosen (BKD) Semester Genap 2025/2026</strong>. Dokumen telah diserahkan ke Lembaga Penjaminan Mutu (LPM) Politani Samarinda untuk verifikasi.</p><h3>Komponen BKD</h3><ul><li>Pengajaran: minimum 9 SKS, maksimum 16 SKS</li><li>Penelitian: minimum 1 publikasi atau setara</li><li>Pengabdian Masyarakat: minimum 1 kegiatan</li><li>Tugas tambahan: keterlibatan dalam tim akademik atau struktural</li></ul><p>LPM akan melakukan verifikasi dokumen dalam 2 minggu ke depan. Hasil verifikasi menjadi dasar pencairan tunjangan kinerja dosen.</p>',
            ],
            [
                'judul' => 'Pelatihan Aplikasi Akuntansi UMKM Berbasis Cloud untuk Mahasiswa SIA',
                'ringkasan' => 'Pelatihan internal mahasiswa Prodi SIA membahas penggunaan aplikasi akuntansi cloud yang biasa dipakai UMKM mitra binaan jurusan.',
                'konten' => '<p>Prodi <strong>Sistem Informasi Akuntansi (SIA)</strong> menggelar <strong>Pelatihan Internal Aplikasi Akuntansi Cloud</strong> bagi 50 mahasiswa semester 4 dan 6. Pelatihan bertujuan menyiapkan mahasiswa untuk pendampingan UMKM dalam program pengabdian masyarakat.</p><h3>Aplikasi yang Dipraktikkan</h3><ul><li>BukuKas: untuk pencatatan transaksi harian</li><li>Mekari Jurnal: pembukuan UMKM dan invoice elektronik</li><li>Aplikasi Si Apik (Bank Indonesia): kasir digital usaha mikro</li><li>Accurate Online: untuk UMKM menengah dengan kebutuhan inventory</li></ul><p>Mahasiswa yang lulus pelatihan akan diberangkatkan dalam tim pengabdian untuk membantu UMKM mitra di Pasar Pagi, Sungai Keledang, dan Pasar Segiri.</p>',
            ],
            [
                'judul' => 'Lokakarya Pengembangan Soft Skills Dosen R&K',
                'ringkasan' => 'Lokakarya 2 hari Effective Teaching diikuti 25 dosen Jurusan R&K dengan narasumber trainer profesional dari Jakarta.',
                'konten' => '<p>Jurusan R&K menyelenggarakan <strong>Lokakarya Pengembangan Soft Skills Dosen</strong> dengan tema <em>"Effective Teaching: From Lecture to Engaging Learning Experience"</em>. Lokakarya berlangsung 2 hari dan diikuti 25 dosen.</p><h3>Materi</h3><ol><li>Effective public speaking di kelas</li><li>Teknik membangun engagement mahasiswa</li><li>Storytelling untuk menyampaikan konsep teknis</li><li>Pengelolaan kelas besar (100+ mahasiswa)</li><li>Penggunaan tools digital untuk gamifikasi pembelajaran</li></ol><p>Hasil pre-test dan post-test menunjukkan peningkatan signifikan pada aspek confidence dan tools mastery dosen peserta.</p>',
            ],
            [
                'judul' => 'Pengukuhan Tim Pengembang Modul Praktek TRGS',
                'ringkasan' => 'Kaprodi TRGS resmi mengukuhkan tim pengembang modul praktek 6 mata kuliah yang akan dirilis sebelum semester ganjil 2026/2027.',
                'konten' => '<p>Kaprodi <strong>D4 Teknologi Rekayasa Geomatika dan Survei</strong> resmi mengukuhkan <strong>Tim Pengembang Modul Praktek</strong> untuk 6 mata kuliah praktikum. Tim terdiri dari 12 dosen dengan target rilis modul revisi sebelum semester ganjil 2026/2027.</p><h3>Mata Kuliah Target</h3><ul><li>Survei Geodetik Lanjut</li><li>Penginderaan Jauh Multiteknik</li><li>Sistem Informasi Geografis Lanjut</li><li>Pemetaan dengan UAV</li><li>Geodesi Satelit</li><li>Kartografi Digital Modern</li></ul><p>Setiap modul akan disusun dengan struktur: dasar teori, prosedur praktek, lembar kerja mahasiswa, dan rubrik penilaian. Modul akan diuji coba di kelas pilot sebelum diberlakukan secara penuh.</p>',
            ],
            [
                'judul' => 'Pelatihan Statistika Lanjut untuk Dosen Pembimbing TA',
                'ringkasan' => 'Pelatihan tiga hari membahas analisis statistika multivariate dan SEM-PLS untuk meningkatkan kualitas bimbingan tugas akhir mahasiswa.',
                'konten' => '<p>Jurusan R&K menyelenggarakan <strong>Pelatihan Statistika Lanjut</strong> untuk 18 dosen pembimbing tugas akhir. Pelatihan berlangsung 3 hari dengan narasumber profesor dari Universitas Mulawarman dan praktisi statistika industri.</p><h3>Materi Pelatihan</h3><ol><li>Multivariate analysis: MANOVA, faktor analysis</li><li>Structural Equation Modeling dengan SEM-PLS</li><li>Penggunaan R Statistical untuk mahasiswa</li><li>SmartPLS 4 untuk analisis SEM</li><li>Interpretasi hasil dan penulisan ilmiah</li></ol><p>Setelah pelatihan, dosen mampu memberikan bimbingan lebih baik untuk mahasiswa yang melakukan riset kuantitatif. Modul pelatihan tersedia untuk dosen yang berhalangan hadir.</p>',
            ],
            [
                'judul' => 'Penyusunan Buku Ajar Pemetaan Pertambangan oleh Dosen TRGS',
                'ringkasan' => 'Tim 4 dosen TRGS menyelesaikan penyusunan buku ajar Pemetaan Pertambangan setebal 320 halaman yang siap diterbitkan tahun ini.',
                'konten' => '<p>Tim 4 dosen <strong>Prodi TRGS</strong> menyelesaikan penyusunan <strong>Buku Ajar Pemetaan Pertambangan</strong> setebal 320 halaman. Buku ini hasil kolaborasi tim selama 18 bulan dan akan diterbitkan oleh penerbit Politani.</p><h3>Cakupan Buku</h3><ul><li>Konsep dasar pemetaan tambang terbuka dan bawah tanah</li><li>Survey topografi dengan total station dan GNSS</li><li>Penggunaan UAV photogrammetry untuk monitoring tambang</li><li>Perhitungan volume galian dan stockpile</li><li>Aplikasi GIS untuk reklamasi pasca tambang</li></ul><p>Buku akan menjadi bahan ajar utama mata kuliah <em>Pemetaan Pertambangan</em> dan referensi untuk industri tambang di Kaltim.</p>',
            ],
            [
                'judul' => 'Pertemuan Tim Kurikulum Bahas Penambahan Mata Kuliah AI',
                'ringkasan' => 'Tim Kurikulum TRPL membahas penambahan dua mata kuliah baru bertema Artificial Intelligence untuk angkatan 2026.',
                'konten' => '<p>Tim Kurikulum <strong>Prodi TRPL</strong> menggelar pertemuan strategis membahas penambahan dua mata kuliah baru bertema <strong>Artificial Intelligence</strong> untuk kurikulum angkatan 2026.</p><h3>Mata Kuliah Baru</h3><ol><li>Pengantar Artificial Intelligence (3 SKS, semester 5)</li><li>Machine Learning Terapan (3 SKS, semester 6)</li></ol><h3>Rasional Penambahan</h3><ul><li>Tren industri yang menuntut kompetensi AI/ML</li><li>Permintaan dari Industry Advisory Board</li><li>Persaingan dengan lulusan kampus lain</li><li>Peluang riset terapan untuk dosen dan mahasiswa</li></ul><p>Mata kuliah akan dimasukkan dalam pemetaan CPL sebagai bagian dari kompetensi tambahan. Tim akan menyusun RPS dan modul praktikum dalam 3 bulan ke depan.</p>',
            ],
            [
                'judul' => 'Pelatihan Penggunaan Plagiarism Checker untuk Dosen Pembimbing',
                'ringkasan' => 'Pelatihan internal penggunaan Turnitin dan iThenticate untuk 30 dosen pembimbing TA dan skripsi guna meningkatkan integritas akademik.',
                'konten' => '<p>Jurusan R&K menggelar <strong>Pelatihan Penggunaan Plagiarism Checker</strong> untuk 30 dosen pembimbing tugas akhir dan skripsi. Pelatihan bertujuan meningkatkan kualitas dan integritas akademik karya mahasiswa.</p><h3>Tools yang Diperkenalkan</h3><ul><li>Turnitin: untuk pengecekan tugas akhir mahasiswa</li><li>iThenticate: untuk publikasi ilmiah dosen</li><li>Grammarly Premium: untuk pemeriksaan tata bahasa</li><li>Mendeley: untuk manajemen referensi mahasiswa</li></ul><h3>Implementasi</h3><p>Mulai semester ini, semua tugas akhir wajib lulus pengecekan Turnitin dengan similarity index maksimum 25%. Mahasiswa yang melebihi batas akan diminta merevisi sebelum sidang.</p>',
            ],
        ];
    }
}
