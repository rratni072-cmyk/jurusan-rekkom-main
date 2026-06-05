<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\KontakPesan;
use Illuminate\Support\Facades\Cache;

/**
 * Helper cache untuk data ringkas yang dipakai di sidebar/header admin.
 *
 * Tujuan:
 *   - Hindari query duplikat per request (sidebar + header sama-sama
 *     menampilkan jumlah pesan belum dibaca).
 *   - Cache pendek (60 detik) agar request bertubi-tubi tetap ringan.
 *   - Auto-invalidate via Observer saat KontakPesan create/update/delete.
 */
class AdminCache
{
    private const KEY_UNREAD_MESSAGES = 'admin.cache.unread_messages_count';

    private const TTL_SECONDS = 60;

    public static function unreadMessageCount(): int
    {
        return (int) Cache::remember(
            self::KEY_UNREAD_MESSAGES,
            self::TTL_SECONDS,
            fn () => KontakPesan::unread()->count(),
        );
    }

    public static function forgetUnreadMessageCount(): void
    {
        Cache::forget(self::KEY_UNREAD_MESSAGES);
    }
}
