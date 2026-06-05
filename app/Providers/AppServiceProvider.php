<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\KontakPesan;
use App\Observers\KontakPesanObserver;
use App\View\Composers\AdminLayoutComposer;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Pagination pakai Bootstrap 5 (default Laravel 11+ adalah Tailwind yang
        // tidak di-load di proyek ini — render SVG tanpa sizing = arrow raksasa).
        // Wajib di atas supaya ->links() di semua view konsisten pakai .pagination Bootstrap.
        Paginator::useBootstrapFive();

        // Observer: invalidate cache pesanBelumDibaca saat data berubah.
        KontakPesan::observe(KontakPesanObserver::class);

        // View Composer: bind pesanBelumDibaca ke sidebar & header admin.
        View::composer(
            ['components.admin.sidebar', 'components.admin.header'],
            AdminLayoutComposer::class,
        );

        // Blade directives untuk format waktu lokal (WITA).
        // Lihat \App\Support\WaktuLokal untuk dokumentasi lengkap penggunaan.
        Blade::directive('waktuLokal', function (string $expression): string {
            return "<?php echo e(\App\Support\WaktuLokal::format({$expression})); ?>";
        });

        Blade::directive('tanggal', function (string $expression): string {
            return "<?php echo e(\App\Support\WaktuLokal::tanggal({$expression})); ?>";
        });

        Blade::directive('waktuRelatif', function (string $expression): string {
            return "<?php echo e(\App\Support\WaktuLokal::relatif({$expression})); ?>";
        });
    }
}
