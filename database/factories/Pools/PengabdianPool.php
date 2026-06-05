<?php

declare(strict_types=1);

namespace Database\Factories\Pools;

trait PengabdianPool
{
    /** @return array<int, array{judul:string, ringkasan:string, konten:string}> */
    protected static function pengabdianPool(): array
    {
        return [
            [
                'judul' => 'Pendampingan Pengelolaan Keuangan Posdaya di Kelurahan Sengkotek',
                'ringkasan' => 'Tim Pengabdian Prodi SIA melakukan pendampingan pengelolaan keuangan untuk 4 Posdaya di Kelurahan Sengkotek selama 6 bulan.',
                'konten' => '<p>Tim Pengabdian Masyarakat <strong>Program Studi Sistem Informasi Akuntansi (SIA)</strong> melaksanakan program pendampingan pengelolaan keuangan untuk <strong>4 Pos Pemberdayaan Keluarga (Posdaya)</strong> di Kelurahan Sengkotek, Samarinda Seberang. Program berjalan 6 bulan dengan kunjungan rutin dwi-mingguan.</p><h3>Aktivitas Pendampingan</h3><ol><li>Sosialisasi pentingnya pencatatan keuangan organisasi</li><li>Pelatihan pencatatan kas masuk dan kas keluar</li><li>Pendampingan penyusunan laporan keuangan sederhana</li><li>Audit ringan dan rekomendasi perbaikan</li><li>Sertifikasi laporan keuangan untuk pengajuan bantuan dana</li></ol><p>4 Posdaya berhasil memiliki sistem pencatatan keuangan yang rapi dan akuntabel. Salah satu Posdaya bahkan berhasil mendapatkan bantuan dana CSR dari Pertamina sebesar <strong>Rp 25 juta</strong>.</p>',
            ],
            [
                'judul' => 'Sosialisasi Literasi Digital untuk Lansia di Panti Wreda',
                'ringkasan' => 'Mahasiswa TRPL menggelar sosialisasi literasi digital dasar untuk 35 lansia di Panti Wreda Bina Asih Samarinda.',
                'konten' => '<p>Mahasiswa <strong>Teknologi Rekayasa Perangkat Lunak (TRPL)</strong> bersama dosen pembimbing menggelar program <strong>Sosialisasi Literasi Digital untuk Lansia</strong>. Program melibatkan 35 lansia di Panti Wreda Bina Asih Samarinda selama 4 pertemuan berturut-turut.</p><h3>Topik yang Dibahas</h3><ul><li>Pengenalan smartphone dasar (panggilan, pesan, kamera)</li><li>Penggunaan WhatsApp untuk komunikasi keluarga</li><li>Mengidentifikasi pesan penipuan online (scam)</li><li>Aplikasi panggilan video untuk reuni keluarga jauh</li><li>Privasi dasar dan pengaturan keamanan akun</li></ul><p>Lansia peserta program mengaku lebih percaya diri menggunakan smartphone untuk komunikasi keluarga.</p>',
            ],
            [
                'judul' => 'Pemetaan Aset Tanah Kelurahan Sungai Dama Berbasis GPS Geodetik',
                'ringkasan' => 'Tim TRGS melakukan pemetaan aset tanah Kelurahan Sungai Dama menggunakan GPS Geodetik untuk dasar pembaruan data administrasi kelurahan.',
                'konten' => '<p>Tim Pengabdian <strong>Program Studi Teknologi Rekayasa Geomatika dan Survei (TRGS)</strong> melaksanakan program pemetaan aset tanah <strong>Kelurahan Sungai Dama</strong>, Samarinda Ilir. Program melibatkan 6 dosen dan 14 mahasiswa, berlangsung selama 1 bulan.</p><h3>Lingkup Pekerjaan</h3><ul><li>Survei batas administrasi kelurahan dengan GPS Geodetik dual-frequency</li><li>Identifikasi dan pemetaan aset tanah pemerintah (kantor, sekolah, fasum)</li><li>Pemetaan tata guna lahan permukiman, perdagangan, ruang terbuka hijau</li><li>Penyusunan peta digital terintegrasi dengan data BPN</li></ul><p>Peta digital aset tanah seluas <strong>4,8 km²</strong> diserahkan kepada Lurah Sungai Dama dalam bentuk file shapefile dan PDF cetak.</p>',
            ],
            [
                'judul' => 'Pelatihan Photoshop dan Canva untuk Karang Taruna Loa Bakung',
                'ringkasan' => 'Mahasiswa TRPL membawakan pelatihan desain grafis dasar untuk anggota Karang Taruna Kelurahan Loa Bakung.',
                'konten' => '<p>Tim Pengabdian Mahasiswa <strong>Prodi TRPL</strong> bersama 2 dosen pembimbing menggelar <strong>Pelatihan Desain Grafis Dasar</strong> untuk 25 anggota Karang Taruna Kelurahan Loa Bakung, Samarinda. Pelatihan berlangsung 3 hari di balai kelurahan.</p><h3>Materi</h3><ol><li>Pengenalan dasar desain grafis dan color theory</li><li>Photoshop CC untuk editing foto dan poster</li><li>Canva untuk konten media sosial cepat</li><li>Branding dasar untuk produk lokal</li><li>Tips fotografi produk dengan smartphone</li></ol><p>Setelah pelatihan, Karang Taruna Loa Bakung berhasil membuat 12 desain konten promosi UMKM mitra mereka.</p>',
            ],
            [
                'judul' => 'Penyuluhan Keamanan Siber untuk Pelaku UMKM Pasar Segiri',
                'ringkasan' => 'Tim dosen TRPL menggelar penyuluhan keamanan siber dasar untuk 80 pelaku UMKM Pasar Segiri terkait penipuan online dan keamanan akun.',
                'konten' => '<p>Tim dosen <strong>Prodi TRPL</strong> menggelar <strong>Penyuluhan Keamanan Siber</strong> untuk pelaku UMKM di kawasan Pasar Segiri Samarinda. Acara dihadiri 80 pelaku UMKM dari berbagai sektor: kuliner, fashion, kerajinan, dan jasa.</p><h3>Materi</h3><ul><li>Kasus penipuan online yang sering menimpa UMKM</li><li>Verifikasi pesanan online dan tanda-tanda customer scam</li><li>Pengamanan akun marketplace dan media sosial bisnis</li><li>Pengelolaan password aman dan two-factor authentication</li><li>Cara melapor jika terkena phising atau scam</li></ul><p>Tim membuat <em>booklet</em> ringkas berisi tips keamanan siber dalam Bahasa Indonesia untuk dibagikan ke peserta.</p>',
            ],
            [
                'judul' => 'Pendampingan Pemasaran Digital Olahan Ikan Kelurahan Selili',
                'ringkasan' => 'Tim pengabdian R&K mendampingi 18 ibu-ibu nelayan di Kelurahan Selili dalam pemasaran digital produk olahan ikan.',
                'konten' => '<p>Tim Pengabdian Jurusan R&K (kolaborasi TRPL dan SIA) melaksanakan program pendampingan <strong>Pemasaran Digital Produk Olahan Ikan</strong> untuk kelompok ibu-ibu nelayan di Kelurahan Selili, Samarinda Ilir. Program melibatkan 18 ibu-ibu nelayan, berlangsung 4 bulan.</p><h3>Tahapan Program</h3><ol><li>Sosialisasi peluang pemasaran digital untuk produk olahan ikan</li><li>Pelatihan WhatsApp Business: katalog, broadcast, label customer</li><li>Onboarding ke marketplace Tokopedia (foto produk, deskripsi, listing)</li><li>Pelatihan packaging menarik dan food safety</li><li>Pendampingan transaksi pertama dan customer service</li></ol><p>Penjualan online dalam 2 bulan pertama mencapai <strong>Rp 8 juta</strong>, dengan customer 60% dari luar Samarinda.</p>',
            ],
            [
                'judul' => 'Pemetaan Wilayah Rentan Banjir untuk BPBD Kutai Kartanegara',
                'ringkasan' => 'Tim TRGS membantu BPBD Kabupaten Kutai Kartanegara melakukan pemetaan wilayah rentan banjir untuk 5 kecamatan di sepanjang Mahakam.',
                'konten' => '<p>Tim Pengabdian <strong>Prodi TRGS</strong> bekerjasama dengan <strong>BPBD Kabupaten Kutai Kartanegara</strong> melakukan <strong>Pemetaan Wilayah Rentan Banjir</strong> untuk 5 kecamatan di sepanjang sungai Mahakam. Program berlangsung 3 bulan dengan total tim 10 orang.</p><h3>Wilayah Pemetaan</h3><ul><li>Kecamatan Tenggarong</li><li>Kecamatan Tenggarong Seberang</li><li>Kecamatan Loa Janan</li><li>Kecamatan Anggana</li><li>Kecamatan Sebulu</li></ul><h3>Hasil</h3><p>Peta digital wilayah rentan banjir diserahkan kepada BPBD untuk perencanaan mitigasi. Peta mencakup informasi area genangan historis, jalur evakuasi, dan lokasi titik aman pengungsian.</p>',
            ],
            [
                'judul' => 'Pelatihan Aplikasi Si Apik untuk UMKM Pasar Pagi Samarinda',
                'ringkasan' => 'Tim SIA dan Bank Indonesia memberikan pelatihan aplikasi Si Apik untuk pencatatan keuangan 50 UMKM mitra Pasar Pagi.',
                'konten' => '<p>Tim Pengabdian <strong>Prodi SIA</strong> bekerjasama dengan <strong>Bank Indonesia Perwakilan Kaltim</strong> menggelar <strong>Pelatihan Aplikasi Si Apik</strong> untuk pelaku UMKM Pasar Pagi Samarinda. Pelatihan diikuti 50 UMKM dari berbagai sektor.</p><h3>Materi Pelatihan</h3><ol><li>Pengenalan aplikasi Si Apik (Sistem Aplikasi Pencatatan Informasi Keuangan)</li><li>Pengisian data master usaha</li><li>Pencatatan transaksi harian</li><li>Pembuatan laporan laba-rugi otomatis</li><li>Cetak laporan untuk pengajuan kredit perbankan</li></ol><p>Setelah pelatihan, 35 UMKM mulai aktif menggunakan aplikasi untuk pencatatan harian. 8 UMKM berhasil mengajukan KUR dengan laporan dari Si Apik.</p>',
            ],
            [
                'judul' => 'Sosialisasi Pertanian Presisi untuk Kelompok Tani Loa Janan',
                'ringkasan' => 'Tim TG bekerjasama Dinas Pertanian Kaltim memperkenalkan teknologi pertanian presisi berbasis GIS kepada 30 petani Loa Janan.',
                'konten' => '<p>Tim Pengabdian <strong>Prodi D3 Teknologi Geomatika (TG)</strong> bekerjasama dengan Dinas Pertanian Provinsi Kaltim menggelar <strong>Sosialisasi Pertanian Presisi</strong> kepada 30 petani Kelompok Tani Loa Janan, Kutai Kartanegara.</p><h3>Topik Sosialisasi</h3><ul><li>Konsep dasar pertanian presisi (precision agriculture)</li><li>Pemanfaatan citra satelit untuk monitoring lahan</li><li>Pemetaan kesuburan tanah dengan teknologi GIS</li><li>Aplikasi smartphone untuk pertanian (Tanihub, Pak Tani)</li><li>Studi kasus: peningkatan produktivitas dengan precision farming</li></ul><p>Tim TG menyumbangkan peta kesuburan tanah seluas 25 hektar lahan tani milik kelompok untuk perencanaan pemupukan yang lebih tepat sasaran.</p>',
            ],
            [
                'judul' => 'Pengembangan Website Profil Kelurahan Sungai Keledang',
                'ringkasan' => 'Mahasiswa TRPL mengembangkan website profil resmi Kelurahan Sungai Keledang sebagai sarana publikasi informasi pelayanan masyarakat.',
                'konten' => '<p>Tim Pengabdian Mahasiswa <strong>Prodi TRPL</strong> berhasil mengembangkan <strong>Website Profil Kelurahan Sungai Keledang</strong>, Samarinda Seberang. Website ini menjadi platform publikasi informasi pelayanan masyarakat dan kegiatan kelurahan.</p><h3>Fitur Website</h3><ul><li>Profil kelurahan dan struktur organisasi</li><li>Informasi pelayanan administrasi (KTP, KK, surat domisili)</li><li>Berita kegiatan kelurahan</li><li>Galeri foto fasilitas dan ruang publik</li><li>Form pengaduan masyarakat online</li><li>Peta wilayah administrasi interaktif</li></ul><p>Website telah diserahkan ke aparat kelurahan dengan training 5 staf untuk update konten mandiri. Domain dan hosting disponsori jurusan untuk 1 tahun pertama.</p>',
            ],
            [
                'judul' => 'Pelatihan Posyandu Digital untuk Kader di Kelurahan Air Putih',
                'ringkasan' => 'Pelatihan aplikasi pencatatan posyandu untuk 40 kader Posyandu di Kelurahan Air Putih guna memodernisasi pencatatan kesehatan ibu dan anak.',
                'konten' => '<p>Tim Pengabdian Jurusan R&K menggelar <strong>Pelatihan Posyandu Digital</strong> untuk 40 kader Posyandu di Kelurahan Air Putih, Samarinda Ulu. Pelatihan bertujuan memodernisasi pencatatan kesehatan ibu dan anak dari kertas ke aplikasi digital.</p><h3>Materi Pelatihan</h3><ol><li>Pengenalan aplikasi e-Posyandu Kemkes</li><li>Pencatatan tinggi badan dan berat balita</li><li>Tracking imunisasi dan jadwal kontrol</li><li>Identifikasi anak gizi buruk dan tindak lanjut</li><li>Pembuatan laporan bulanan untuk Puskesmas</li></ol><p>Setelah pelatihan, 8 dari 12 Posyandu di Kelurahan Air Putih sudah beralih ke pencatatan digital. Data terintegrasi dengan Puskesmas untuk pemantauan kesehatan wilayah.</p>',
            ],
            [
                'judul' => 'Pelatihan Pembukuan Sederhana untuk Koperasi Mahasiswa',
                'ringkasan' => 'Tim dosen SIA memberikan pelatihan pembukuan untuk pengurus 5 koperasi mahasiswa di lingkungan Politani guna meningkatkan transparansi.',
                'konten' => '<p>Tim Pengabdian Dosen <strong>Prodi SIA</strong> menggelar <strong>Pelatihan Pembukuan Koperasi Mahasiswa</strong> untuk pengurus 5 koperasi mahasiswa di lingkungan Politani Samarinda. Pelatihan diikuti 25 pengurus dan bertujuan meningkatkan transparansi keuangan.</p><h3>Materi Pelatihan</h3><ul><li>Konsep dasar pembukuan koperasi (sesuai PSAK)</li><li>Pencatatan modal anggota dan SHU (Sisa Hasil Usaha)</li><li>Aplikasi spreadsheet untuk pembukuan koperasi sederhana</li><li>Penyusunan laporan keuangan tahunan</li><li>Audit internal koperasi</li></ul><p>Setelah pelatihan, semua koperasi mampu menyusun laporan keuangan yang akuntabel. Salah satu koperasi mendapat penghargaan Best Practice Koperasi Mahasiswa Politeknik se-Kaltim.</p>',
            ],
            [
                'judul' => 'Survey Topografi Lokasi Pembangunan Jembatan Desa',
                'ringkasan' => 'Tim TRGS membantu pemerintah Desa Bukit Pariaman melakukan survey topografi untuk rencana pembangunan jembatan akses pertanian.',
                'konten' => '<p>Tim Pengabdian <strong>Prodi TRGS</strong> membantu pemerintah <strong>Desa Bukit Pariaman</strong>, Kutai Kartanegara melakukan <strong>Survey Topografi</strong> untuk rencana pembangunan jembatan akses pertanian. Survey berlangsung 5 hari dengan tim 8 orang.</p><h3>Pekerjaan Survey</h3><ul><li>Pengukuran kontur lahan calon lokasi jembatan (luas 2 hektar)</li><li>Pengukuran lebar dan kedalaman sungai</li><li>Pemetaan area sekitar (jalan eksisting, lahan tani)</li><li>Pengambilan koordinat titik kontrol GNSS</li><li>Pembuatan gambar teknis untuk usulan APBDes</li></ul><p>Hasil survey diserahkan kepada Kepala Desa untuk dilampirkan pada proposal APBDes 2027. Pembangunan jembatan diharapkan meningkatkan akses petani ke lahan sawit dan jagung.</p>',
            ],
            [
                'judul' => 'Pendampingan Pengembangan Aplikasi Inventory UMKM Sungai Keledang',
                'ringkasan' => 'Mahasiswa TRPL mengembangkan aplikasi inventory custom untuk 5 UMKM kelontong di Sungai Keledang sebagai bagian program pengabdian.',
                'konten' => '<p>Tim Pengabdian Mahasiswa <strong>Prodi TRPL</strong> mengembangkan <strong>Aplikasi Inventory Custom</strong> untuk 5 UMKM kelontong di Kelurahan Sungai Keledang. Aplikasi dibuat sesuai kebutuhan masing-masing UMKM dengan workshop kolaborasi mahasiswa-pemilik usaha.</p><h3>Fitur Aplikasi</h3><ul><li>Pencatatan stok barang masuk dan keluar</li><li>Notifikasi stok minimum (low stock alert)</li><li>Laporan penjualan harian, mingguan, bulanan</li><li>Daftar customer dan piutang sederhana</li><li>Backup data ke Google Drive</li></ul><p>Aplikasi diserahkan dengan training penggunaan dan dukungan teknis 6 bulan. Pemilik UMKM mengaku waktu manajemen stok berkurang 50% sejak pakai aplikasi.</p>',
            ],
            [
                'judul' => 'Pemberian Fasilitas Internet Gratis di Balai Warga Tani Aman',
                'ringkasan' => 'Jurusan R&K menyumbangkan instalasi WiFi publik di balai warga Kelurahan Tani Aman untuk akses informasi gratis bagi masyarakat.',
                'konten' => '<p>Jurusan R&K menyumbangkan <strong>Instalasi WiFi Publik Gratis</strong> di Balai Warga Kelurahan Tani Aman, Loa Janan Ilir. Bantuan ini bagian dari program pengabdian masyarakat berkelanjutan untuk membantu mengurangi digital divide.</p><h3>Spesifikasi Bantuan</h3><ul><li>Akses internet 50 Mbps unlimited 1 tahun</li><li>Router enterprise dengan kapasitas 50 user simultan</li><li>Filter konten edukatif (block konten dewasa, judi online)</li><li>Logging access untuk monitoring keamanan</li><li>Maintenance gratis selama 1 tahun</li></ul><p>WiFi publik dapat dimanfaatkan masyarakat untuk akses pendidikan, mencari kerja, dan komunikasi. Penggunaan harian rata-rata 25 user/hari di bulan pertama operasional.</p>',
            ],
            [
                'judul' => 'Pelatihan Pengelolaan Surat Menyurat Digital untuk Aparat Desa',
                'ringkasan' => 'Pelatihan aplikasi e-surat untuk aparat 3 desa di Kutai Kartanegara guna memodernisasi administrasi surat menyurat.',
                'konten' => '<p>Tim Pengabdian Jurusan R&K menggelar <strong>Pelatihan Pengelolaan Surat Menyurat Digital</strong> untuk aparat dari 3 desa di Kabupaten Kutai Kartanegara. Pelatihan diikuti 18 aparat desa dan bertujuan memodernisasi administrasi.</p><h3>Materi Pelatihan</h3><ol><li>Pengantar e-Government dan transformasi digital pemerintahan</li><li>Penggunaan aplikasi e-Surat (custom by mahasiswa Politani)</li><li>Pembuatan template surat keluar</li><li>Disposisi surat secara digital</li><li>Arsip digital dan retensi dokumen</li></ol><p>Setelah pelatihan, 3 desa mulai migrasi pencatatan surat masuk dan keluar dari buku agenda manual ke aplikasi digital. Estimasi penghematan kertas 70% dalam 1 tahun pertama.</p>',
            ],
            [
                'judul' => 'Penyuluhan Hak Konsumen Digital untuk Mahasiswa SMK Samarinda',
                'ringkasan' => 'Mahasiswa SIA menggelar penyuluhan hak konsumen di era digital untuk siswa-siswi 4 SMK di Samarinda guna meningkatkan literasi konsumen muda.',
                'konten' => '<p>Tim Pengabdian Mahasiswa <strong>Prodi SIA</strong> menggelar <strong>Penyuluhan Hak Konsumen Digital</strong> untuk siswa-siswi 4 SMK di Samarinda. Total peserta 320 siswa kelas 11 dan 12 dari berbagai jurusan.</p><h3>Topik Penyuluhan</h3><ul><li>Hak-hak konsumen menurut UU No. 8 Tahun 1999</li><li>Penanganan komplain pembelian online</li><li>Verifikasi keaslian produk dan toko online</li><li>Cara melapor ke YLKI atau BPKN saat dirugikan</li><li>Studi kasus penipuan online di kalangan remaja</li></ul><p>Penyuluhan dilengkapi simulasi peran konsumen yang terkena penipuan online dan cara penyelesaiannya. Setiap SMK mendapat <em>booklet</em> hak konsumen untuk dijadikan bahan ajar.</p>',
            ],
            [
                'judul' => 'Pengembangan Sistem Antrian Online untuk Puskesmas Samarinda Utara',
                'ringkasan' => 'Tim TRPL mengembangkan sistem antrian online untuk Puskesmas Samarinda Utara guna mengurangi penumpukan pasien.',
                'konten' => '<p>Tim Pengabdian <strong>Prodi TRPL</strong> mengembangkan <strong>Sistem Antrian Online</strong> untuk Puskesmas Samarinda Utara. Sistem ini dikembangkan untuk mengatasi masalah penumpukan pasien yang sering terjadi di pagi hari.</p><h3>Fitur Sistem</h3><ul><li>Pendaftaran antrian via WhatsApp Bot</li><li>Estimasi waktu pelayanan berbasis data historis</li><li>Notifikasi otomatis 30 menit sebelum dipanggil</li><li>Display nomor antrian di ruang tunggu</li><li>Dashboard monitoring untuk staf medis</li></ul><p>Sistem diuji selama 1 bulan dengan 200 pasien per hari. Hasilnya: waktu tunggu pasien rata-rata berkurang dari 90 menit jadi 30 menit. Sistem akan diadopsi ke 2 puskesmas lain di Samarinda.</p>',
            ],
            [
                'judul' => 'Pelatihan Drone Mapping untuk Surveyor Pertanahan Daerah',
                'ringkasan' => 'Pelatihan drone mapping untuk 20 surveyor pertanahan daerah dari BPN dan dinas terkait di Kaltim guna meningkatkan kapasitas pemetaan modern.',
                'konten' => '<p>Tim Dosen <strong>Prodi TRGS</strong> menggelar <strong>Pelatihan Drone Mapping</strong> untuk 20 surveyor pertanahan dari BPN Kanwil Kaltim dan dinas pertanahan kabupaten/kota. Pelatihan berlangsung 5 hari intensif dengan teori dan praktek lapangan.</p><h3>Materi</h3><ol><li>Regulasi penerbangan drone untuk keperluan survei</li><li>Mission planning dengan Pix4D Capture dan DroneDeploy</li><li>Akuisisi foto udara dan ground control points</li><li>Photogrammetry processing dengan Pix4D dan Agisoft</li><li>Output: orthophoto, DSM, DTM, dan peta kontur</li></ol><p>Setelah pelatihan, peserta mendapat sertifikat resmi dan kemampuan mandiri melakukan drone mapping di unit kerjanya masing-masing.</p>',
            ],
            [
                'judul' => 'Pendampingan Penerapan PSAK ETAP untuk Yayasan Pendidikan',
                'ringkasan' => 'Tim SIA mendampingi 6 yayasan pendidikan di Samarinda dalam penerapan PSAK ETAP untuk laporan keuangan yang lebih akuntabel.',
                'konten' => '<p>Tim Pengabdian Dosen <strong>Prodi SIA</strong> mendampingi <strong>6 Yayasan Pendidikan</strong> di Samarinda dalam penerapan <strong>PSAK ETAP (Entitas Tanpa Akuntabilitas Publik)</strong>. Program berlangsung 4 bulan dengan workshop bulanan dan kunjungan teknis.</p><h3>Lingkup Pendampingan</h3><ul><li>Pengenalan PSAK ETAP untuk yayasan kecil-menengah</li><li>Setup chart of accounts khusus yayasan pendidikan</li><li>Pencatatan transaksi (donasi, SPP, gaji guru)</li><li>Penyusunan 4 laporan utama: laporan posisi keuangan, aktivitas, arus kas, catatan</li><li>Audit internal sebelum audit eksternal tahunan</li></ul><p>Semua yayasan mitra berhasil menyusun laporan keuangan PSAK ETAP yang lulus audit internal. 2 yayasan bahkan mendapat opini Wajar Tanpa Pengecualian dari KAP eksternal.</p>',
            ],
        ];
    }
}
