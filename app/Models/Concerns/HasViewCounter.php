<?php

declare(strict_types=1);

namespace App\Models\Concerns;

/**
 * Trait HasViewCounter
 *
 * Menambahkan kemampuan tracking views untuk model Eloquent dengan:
 * - Session-based deduplication: 1 visitor = 1 view per browser session
 *   (saat session expire, mereka di-hitung lagi). Mencegah refresh spam.
 * - Skip admin login: user yang authenticated tidak menambah counter
 *   (mencegah admin inflate angka dengan buka halaman sendiri).
 * - Atomic increment: pakai Eloquent `increment()` agar aman dari race condition.
 *
 * SYARAT: model harus punya kolom `views` (unsignedInteger, default 0).
 *
 * CARA PAKAI:
 *   class Berita extends Model {
 *       use HasViewCounter;
 *   }
 *
 *   // Di controller show:
 *   $berita->incrementViewOnce();
 *
 * TIDAK di-handle (intentional): bot filter (butuh UA parser), IP tracking
 * (privacy concern), global view analytics (scope out MVP).
 */
trait HasViewCounter
{
    /**
     * Format views untuk tampilan: < 1K = "N views", >= 1K = "1.2K views",
     * >= 1M = "1.2M views". Bahasa Indonesia pakai label "tayangan".
     *
     * Usage di view: {{ $kegiatan->views_formatted }}
     */
    public function getViewsFormattedAttribute(): string
    {
        $count = (int) ($this->views ?? 0);

        return match (true) {
            $count >= 1_000_000 => number_format($count / 1_000_000, 1, ',', '.').'M',
            $count >= 1_000 => number_format($count / 1_000, 1, ',', '.').'K',
            default => (string) $count,
        };
    }

    /**
     * Increment views dengan session dedup + skip admin login.
     *
     * Dipanggil dari controller show(). Aman dipanggil multiple kali
     * di satu request — hanya increment sekali per session.
     */
    public function incrementViewOnce(): void
    {
        // Skip kalau admin login — mencegah inflate self-view.
        if (auth()->check()) {
            return;
        }

        $sessionKey = sprintf('viewed_%s_%d', class_basename($this), $this->getKey());

        // Sudah di-view di session ini? Skip increment.
        if (session()->has($sessionKey)) {
            return;
        }

        // Mark viewed + atomic increment di DB.
        session()->put($sessionKey, true);
        $this->increment('views');
    }
}
