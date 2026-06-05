<?php

declare(strict_types=1);

namespace App\Support;

use Carbon\CarbonInterface;

/**
 * Helper konversi & format waktu ke zona lokal (WITA / Asia/Makassar).
 *
 * Project menyimpan timestamp di DB dalam UTC (default Laravel — best practice
 * untuk portabilitas). Class ini single source of truth untuk menampilkan
 * waktu dalam zona lokal Samarinda agar konsisten di seluruh aplikasi.
 *
 * Pakai lewat Blade directive {@see \App\Providers\AppServiceProvider}:
 *
 *   @waktuLokal($carbon)                    -> "01 Mei 2026, 17:15 WITA"
 *   @waktuLokal($carbon, 'd F Y')           -> "01 Mei 2026 WITA"
 *   @waktuLokal($carbon, 'd F Y', false)    -> "01 Mei 2026"  (tanpa suffix)
 *
 *   @waktuRelatif($carbon)                  -> "2 jam yang lalu"
 *
 * Atau langsung di PHP:
 *   WaktuLokal::format($model->updated_at)
 *   WaktuLokal::relatif($model->created_at)
 */
final class WaktuLokal
{
    /**
     * IANA timezone untuk WITA (Waktu Indonesia Tengah).
     * Mencakup Kalimantan Tengah/Timur/Selatan/Utara, Sulawesi, NTB, Bali.
     */
    public const TIMEZONE = 'Asia/Makassar';

    public const ZONE_LABEL = 'WITA';

    /** Default: tanggal panjang + jam ("01 Mei 2026, 17:15"). */
    public const FORMAT_DEFAULT = 'd F Y, H:i';

    /** Tanggal saja ("01 Mei 2026"). */
    public const FORMAT_TANGGAL = 'd F Y';

    /** Tanggal pendek ("01/05/2026"). */
    public const FORMAT_TANGGAL_PENDEK = 'd/m/Y';

    /** Jam saja ("17:15"). */
    public const FORMAT_JAM = 'H:i';

    /**
     * Format absolute time dalam zona lokal (default: dengan suffix WITA).
     *
     * Cocok untuk timestamp full (created_at, updated_at, dll).
     *
     * @param  CarbonInterface|null  $waktu  Carbon instance (atau null → fallback)
     * @param  string  $format  Format date string (lihat konstanta FORMAT_*)
     * @param  bool  $denganZona  Append " WITA" suffix (default: true)
     * @param  string  $fallback  Output kalau $waktu null (default: empty)
     */
    public static function format(
        ?CarbonInterface $waktu,
        string $format = self::FORMAT_DEFAULT,
        bool $denganZona = true,
        string $fallback = '',
    ): string {
        if ($waktu === null) {
            return $fallback;
        }

        $hasil = $waktu->copy()
            ->setTimezone(self::TIMEZONE)
            ->locale('id')
            ->translatedFormat($format);

        return $denganZona ? "{$hasil} ".self::ZONE_LABEL : $hasil;
    }

    /**
     * Format date-only (tanpa suffix WITA).
     *
     * Cocok untuk field tanggal yang tidak bermakna sebagai "moment in time"
     * — mis. tanggal_publikasi, tanggal_kedaluwarsa, tanggal kegiatan.
     */
    public static function tanggal(
        ?CarbonInterface $waktu,
        string $format = self::FORMAT_TANGGAL,
        string $fallback = '-',
    ): string {
        return self::format($waktu, $format, false, $fallback);
    }

    /**
     * Format relatif terhadap sekarang (lokalisasi Indonesia).
     *
     * Contoh output: "2 jam yang lalu", "3 hari yang lalu", "1 menit lagi"
     */
    public static function relatif(?CarbonInterface $waktu, string $fallback = ''): string
    {
        if ($waktu === null) {
            return $fallback;
        }

        return $waktu->copy()
            ->setTimezone(self::TIMEZONE)
            ->locale('id')
            ->diffForHumans();
    }
}
