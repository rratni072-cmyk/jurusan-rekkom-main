<?php

declare(strict_types=1);

namespace App\View\Composers;

use App\Support\AdminCache;
use Illuminate\View\View;

/**
 * View Composer untuk komponen sidebar & header admin.
 *
 * Bind variabel `pesanBelumDibaca` agar view tidak perlu query
 * langsung ke model (anti-pattern). Cache via {@see AdminCache}.
 */
class AdminLayoutComposer
{
    public function compose(View $view): void
    {
        $view->with('pesanBelumDibaca', AdminCache::unreadMessageCount());
    }
}
