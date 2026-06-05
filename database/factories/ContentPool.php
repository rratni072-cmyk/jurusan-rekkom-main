<?php

declare(strict_types=1);

namespace Database\Factories;

/**
 * Pool konten realistis untuk seeder Berita & Tridharma.
 * Bertema Jurusan Rekayasa dan Komputer, Politeknik Pertanian Negeri Samarinda.
 *
 * Sumber template dipisah ke trait per kategori agar file modular dan
 * tidak overflow tool call. Setiap method return random template
 * berbentuk array{judul, ringkasan, konten}.
 */
class ContentPool
{
    use Pools\PengabdianPool;
    use Pools\PengajaranPool;
    use Pools\RegularPool;

    public const LOKASI_PENGABDIAN = [
        'Kelurahan Sengkotek, Samarinda Seberang',
        'Kelurahan Sungai Keledang, Samarinda Seberang',
        'Kelurahan Loa Bakung, Sungai Kunjang',
        'Kelurahan Selili, Samarinda Ilir',
        'Kelurahan Sungai Dama, Samarinda Ilir',
        'Pasar Segiri, Samarinda Ulu',
        'Pasar Pagi, Samarinda Kota',
        'Desa Loa Janan Ulu, Kutai Kartanegara',
        'Desa Bukit Pariaman, Kutai Kartanegara',
        'Kelurahan Tani Aman, Loa Janan Ilir',
        'Kelurahan Air Putih, Samarinda Ulu',
        'Desa Sungai Siring, Samarinda Utara',
    ];

    public const DAMPAK = [
        'Peningkatan literasi digital warga',
        'UMKM lokal mulai memanfaatkan media sosial untuk pemasaran',
        'Tersedianya peta digital aset wilayah yang dapat diakses publik',
        'Kelompok mitra mampu menyusun laporan keuangan mandiri',
        'Aparat kelurahan memperoleh data spasial untuk perencanaan',
        'Kader posyandu dapat mengoperasikan aplikasi pencatatan kesehatan',
        'Lansia mampu berkomunikasi dengan keluarga via panggilan video',
        'Karang Taruna memproduksi konten promosi UMKM mitra',
        'Pelaku UMKM lebih waspada terhadap penipuan online',
        'Tersedia sistem informasi sederhana untuk koperasi mitra',
    ];

    /** @return array{judul:string, ringkasan:string, konten:string} */
    public static function regular(): array
    {
        return fake()->randomElement(self::regularPool());
    }

    /** @return array{judul:string, ringkasan:string, konten:string} */
    public static function pengajaran(): array
    {
        return fake()->randomElement(self::pengajaranPool());
    }

    /** @return array{judul:string, ringkasan:string, konten:string} */
    public static function pengabdian(): array
    {
        return fake()->randomElement(self::pengabdianPool());
    }
}
