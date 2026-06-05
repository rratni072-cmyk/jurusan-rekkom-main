<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Beasiswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder mandiri untuk tabel beasiswas.
 *
 * Pattern ini idempotent — bisa dijalankan kapan saja untuk reset data
 * beasiswa ke state referensi PBL lama (9 items) tanpa mengganggu tabel lain:
 *
 *     php artisan db:seed --class=BeasiswaSeeder
 */
class BeasiswaSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate supaya idempotent — aman di-rerun.
        DB::table('beasiswas')->truncate();

        $items = [
            [
                'nama' => 'Beasiswa Bank Indonesia',
                'penyelenggara' => 'Bank Indonesia',
                'deskripsi' => '<p>Beasiswa Bank Indonesia untuk seluruh Perguruan Tinggi di Indonesia. Penerima beasiswa ini akan diberikan bantuan berupa dana <strong>Rp 1 juta/bulan selama 1 tahun</strong>, mendapatkan pelatihan untuk meningkatkan kompetensi dan mendapat kesempatan untuk mengembangkan karakter dalam sebuah komunitas disebut GenBI (Generasi Baru Indonesia).</p>',
                'url_info' => 'https://www.bi.go.id/id/fungsi-utama/stabilitas-sistem-keuangan/beasiswa-bi/',
            ],
            [
                'nama' => 'Gratispol',
                'penyelenggara' => 'Pemerintah Provinsi Kalimantan Timur',
                'deskripsi' => '<p>Bantuan biaya pendidikan untuk mahasiswa dari Kalimantan Timur. Program <strong>Gratispol</strong> memberikan pembiayaan komprehensif mulai dari biaya pendaftaran, SPP, SKS, praktikum, hingga kebutuhan hidup sehari-hari bagi mahasiswa yang berkuliah di Kaltim.</p>',
                'url_info' => 'https://pendidikan.gratispol.kaltimprov.go.id/',
            ],
            [
                'nama' => 'KIP Kuliah',
                'penyelenggara' => 'Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi',
                'deskripsi' => '<p>Diberikan oleh Kemendikbud untuk calon mahasiswa baru kurang mampu. Mendapat <strong>UKT Rp 2.400.000</strong> + uang saku <strong>Rp 700.000/bulan</strong> langsung ke rekening mahasiswa.</p>',
                'url_info' => 'https://kip-kuliah.kemdiktisaintek.go.id/',
            ],
            [
                'nama' => 'Beasiswa Pertamina Hulu Mahakam',
                'penyelenggara' => 'PT Pertamina Hulu Mahakam (PHM)',
                'deskripsi' => '<p>Beasiswa ini diperuntukkan bagi mahasiswa yang berasal dari wilayah sekitar PHM pada 5 kecamatan, yaitu Kecamatan <strong>Samboja, Muara Jawa, Anggana, Muara Badak,</strong> dan <strong>Sanga-Sanga</strong>. Berupa penggantian biaya SPP/UKT tahun akademik berjalan.</p>',
                'url_info' => 'https://pertaminafoundation.org/',
            ],
            [
                'nama' => 'Pertamina Sobat Bumi',
                'penyelenggara' => 'Pertamina Foundation',
                'deskripsi' => '<p>Diberikan oleh Pertamina Foundation untuk mahasiswa berprestasi, peduli lingkungan, dan berkomitmen pada gaya hidup ramah lingkungan. <strong>Beasiswa ini untuk mahasiswa aktif minimal semester 3 dan dibuka setiap tahun.</strong></p>',
                'url_info' => 'https://beasiswa.pertaminafoundation.org/',
            ],
            [
                'nama' => 'Beasiswa Kukar Idaman',
                'penyelenggara' => 'Pemerintah Kabupaten Kutai Kartanegara',
                'deskripsi' => '<p>Diperuntukkan bagi mahasiswa dari wilayah <strong>Kutai Kartanegara</strong>. Program beasiswa jenjang D3, D4, dan S1 dengan IPK minimal 3,00 untuk pelajar dan mahasiswa Kukar.</p>',
                'url_info' => 'https://beasiswa.kukarkab.go.id/',
            ],
            [
                'nama' => 'Beasiswa KPC',
                'penyelenggara' => 'PT Kaltim Prima Coal',
                'deskripsi' => '<p>Diperuntukkan bagi mahasiswa dari wilayah <strong>Kutai Timur</strong>. Program Beasiswa Berdaya / Kutim Cerdas dari PT Kaltim Prima Coal untuk mahasiswa jenjang D3, D4, hingga S1 yang tengah menempuh pendidikan.</p>',
                'url_info' => 'https://www.kpc.co.id/media-information/scholarship/',
            ],
            [
                'nama' => 'ADik',
                'penyelenggara' => 'Kementerian Pendidikan Tinggi, Sains, dan Teknologi',
                'deskripsi' => '<p>Bantuan biaya pendidikan untuk mahasiswa dari <strong>Papua, Papua Barat, daerah 3T, anak TKI,</strong> dan <strong>disabilitas</strong>. Program Afirmasi Pendidikan Tinggi (ADik) dengan pembebasan biaya kuliah penuh.</p>',
                'url_info' => 'https://adik.kemdiktisaintek.go.id/',
            ],
            [
                'nama' => 'Beasiswa Inisiatif Zakat Indonesia (IZI)',
                'penyelenggara' => 'Inisiatif Zakat Indonesia (IZI)',
                'deskripsi' => "<p>Untuk mahasiswa laki-laki muslim semester ≥3, <strong>penghafal Al-Qur'an minimal 1 juz</strong>, dari IZI. Beasiswa meliputi biaya hidup, transportasi, sarana menghafal Al-Qur'an, dan biaya pendidikan.</p>",
                'url_info' => 'https://izi.or.id/program/program-pendidikan/',
            ],
        ];

        foreach ($items as $item) {
            Beasiswa::create([
                ...$item,
                'is_active' => true,
            ]);
        }
    }
}
