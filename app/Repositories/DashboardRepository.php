<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Berita;
use App\Models\Jadwal;
use App\Models\Kegiatan;
use App\Models\KontakPesan;
use App\Models\ProgramStudi;
use App\Models\Slider;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

/**
 * Repository agregator data untuk halaman Dashboard Admin.
 *
 * Memisahkan logika query dari Controller sesuai pola Repository
 * yang ditetapkan di .agents/rules/arsitektur-proyek.md.
 */
class DashboardRepository
{
    /**
     * Statistik ringkas untuk stat-cards.
     *
     * @return array<string, int>
     */
    public function getStats(): array
    {
        return [
            'berita' => Berita::count(),
            'berita_draft' => Berita::where('is_published', false)->count(),
            'jadwal' => Jadwal::count(),
            'kegiatan' => Kegiatan::count(),
            'kegiatan_mendatang' => Kegiatan::whereDate('tanggal', '>=', now())->count(),
            'pesan' => KontakPesan::count(),
            'pesan_unread' => KontakPesan::unread()->count(),
            'slider' => Slider::count(),
            'slider_aktif' => Slider::where('is_active', true)->count(),
            'prodi' => ProgramStudi::count(),
        ];
    }

    /**
     * @return Collection<int, Berita>
     */
    public function getRecentBerita(int $limit = 5): Collection
    {
        return Berita::latest('created_at')->take($limit)->get();
    }

    /**
     * Kegiatan yang tanggal pelaksanaannya >= hari ini, urut paling dekat.
     *
     * @return Collection<int, Kegiatan>
     */
    public function getUpcomingKegiatan(int $limit = 5): Collection
    {
        return Kegiatan::whereDate('tanggal', '>=', now())
            ->orderBy('tanggal')
            ->take($limit)
            ->get();
    }

    /**
     * Pesan kontak yang belum dibaca admin.
     *
     * @return Collection<int, KontakPesan>
     */
    public function getUnreadMessages(int $limit = 5): Collection
    {
        return KontakPesan::unread()
            ->latest('created_at')
            ->take($limit)
            ->get();
    }

    /**
     * Data agregat untuk chart "konten per bulan".
     * Dioptimalkan: 2 query GROUP BY (sebelumnya N×2 = 12+ query).
     *
     * @return array{labels: array<int,string>, berita: array<int,int>, kegiatan: array<int,int>}
     */
    public function getMonthlyChart(int $months = 6): array
    {
        $start = Carbon::now()->subMonths($months - 1)->startOfMonth();

        $labels = [];
        $beritaSeries = [];
        $kegiatanSeries = [];

        // Kerangka bulan (untuk fill 0 di bulan tanpa data)
        $bucket = [];
        for ($i = 0; $i < $months; $i++) {
            $date = (clone $start)->addMonths($i);
            $key = $date->format('Y-m');
            $bucket[$key] = 0;
            $labels[] = $date->translatedFormat('M Y');
        }

        $beritaCounts = $this->groupCountByMonth(Berita::query(), $start);
        $kegiatanCounts = $this->groupCountByMonth(Kegiatan::query(), $start);

        foreach ($bucket as $key => $_) {
            $beritaSeries[] = (int) ($beritaCounts[$key] ?? 0);
            $kegiatanSeries[] = (int) ($kegiatanCounts[$key] ?? 0);
        }

        return [
            'labels' => $labels,
            'berita' => $beritaSeries,
            'kegiatan' => $kegiatanSeries,
        ];
    }

    /**
     * Helper: GROUP BY YEAR-MONTH agar 1 query saja per tabel.
     * Pakai DATE_FORMAT (MySQL) — sesuai tech-stack (MySQL 8.x).
     * Untuk test pakai SQLite, fallback ke strftime.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<\Illuminate\Database\Eloquent\Model>  $query
     * @return array<string, int>
     */
    private function groupCountByMonth($query, Carbon $start): array
    {
        $driver = DB::connection()->getDriverName();
        $expr = $driver === 'sqlite'
            ? "strftime('%Y-%m', created_at)"
            : "DATE_FORMAT(created_at, '%Y-%m')";

        return $query
            ->where('created_at', '>=', $start)
            ->selectRaw("{$expr} as ym, COUNT(*) as total")
            ->groupBy('ym')
            ->pluck('total', 'ym')
            ->toArray();
    }

    /**
     * Activity log terbaru (spatie/laravel-activitylog).
     *
     * @return Collection<int, Activity>
     */
    public function getRecentActivities(int $limit = 8): Collection
    {
        return Activity::query()
            ->with('causer:id,name')
            ->latest('id')
            ->take($limit)
            ->get();
    }
}
