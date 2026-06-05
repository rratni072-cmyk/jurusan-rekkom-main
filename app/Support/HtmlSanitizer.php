<?php

declare(strict_types=1);

namespace App\Support;

/**
 * HtmlSanitizer.
 *
 * Helper untuk sanitisasi HTML konten yang berasal dari rich-text editor (TinyMCE).
 * Saat ini fokus pada compliance a11y: pastikan setiap <img> punya `alt` attribute
 * walaupun admin lupa mengisi waktu upload. Fallback ke `alt=""` (decorative)
 * supaya screen reader skip image dan tidak melempar warning Lighthouse.
 *
 * Dipakai sebagai accessor di model:
 * - App\Models\ProfilJurusan (field `nilai`)
 * - App\Models\Berita (field `konten`)
 */
class HtmlSanitizer
{
    /**
     * Pastikan setiap tag <img> dalam HTML punya `alt` attribute.
     *
     * Algoritma:
     * 1. Match semua tag <img>.
     * 2. Untuk tiap tag, cek apakah sudah punya `alt=` (word boundary, supaya
     *    `data-alt=` atau attribute lain yang mengandung "alt" tidak false-positive).
     * 3. Kalau belum, sisipkan `alt=""` setelah `<img` (mark decorative).
     *
     * Approach regex dipilih (bukan DOMDocument) karena:
     * - Konten user-generated berupa HTML fragment (bukan dokumen lengkap).
     * - DOMDocument auto-wrap dengan <html><body> dan kadang merusak whitespace.
     *
     * @param  string|null  $html  Raw HTML dari editor.
     * @return string|null HTML dengan alt="" untuk img yang missing.
     */
    public static function ensureImgAlt(?string $html): ?string
    {
        if ($html === null || $html === '') {
            return $html;
        }

        $result = preg_replace_callback(
            '/<img\b[^>]*>/i',
            static function (array $matches): string {
                $tag = $matches[0];

                // Sudah punya alt= (word boundary, anti false-positive `data-alt=`)?
                if (preg_match('/\balt\s*=/i', $tag) === 1) {
                    return $tag;
                }

                // Sisipkan alt="" setelah `<img`.
                return preg_replace('/^<img\b/i', '<img alt=""', $tag, 1) ?? $tag;
            },
            $html
        );

        return $result ?? $html;
    }
}
