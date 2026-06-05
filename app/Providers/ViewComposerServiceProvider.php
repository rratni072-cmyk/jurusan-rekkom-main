<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Kontak;
use App\Repositories\BeritaRepository;
use App\Repositories\ProgramStudiRepository;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

/**
 * Service Provider untuk berbagi data global ke view frontend.
 *
 * Menyediakan:
 * - Data kontak jurusan ke topbar dan footer.
 * - Data program studi ke footer (via Repository).
 * - Daftar berita terkini ke widget sidebar (via Repository).
 *
 * Mengikuti pola Repository sesuai .agents/rules/arsitektur-proyek.md.
 */
class ViewComposerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Share kontak data ke topbar dan footer.
        View::composer(
            ['components.frontend.topbar', 'components.frontend.footer'],
            function ($view) {
                $view->with('kontak', Kontak::first());
            }
        );

        // Share daftar prodi aktif ke footer (via Repository).
        View::composer('components.frontend.footer', function ($view) {
            $view->with('prodiList', app(ProgramStudiRepository::class)->getActiveForHome());
        });

        // Share daftar artikel terkini ke widget sidebar (via Repository).
        View::composer('components.frontend.sidebar-artikel', function ($view) {
            $view->with('artikelTerkini', app(BeritaRepository::class)->getRecentForSidebar(5));
        });
    }
}
