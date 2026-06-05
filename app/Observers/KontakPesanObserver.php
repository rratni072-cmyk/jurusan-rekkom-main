<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\KontakPesan;
use App\Support\AdminCache;

/**
 * Observer untuk model KontakPesan.
 *
 * Mengnvalidasi cache jumlah pesan belum dibaca setiap kali ada
 * perubahan data yang mempengaruhi count.
 */
class KontakPesanObserver
{
    public function created(KontakPesan $pesan): void
    {
        AdminCache::forgetUnreadMessageCount();
    }

    public function updated(KontakPesan $pesan): void
    {
        // Hanya invalidate kalau status is_read berubah.
        if ($pesan->wasChanged('is_read')) {
            AdminCache::forgetUnreadMessageCount();
        }
    }

    public function deleted(KontakPesan $pesan): void
    {
        AdminCache::forgetUnreadMessageCount();
    }
}
