<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\DashboardRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller untuk halaman dashboard admin.
 *
 * Tetap ringan sesuai .agents/rules/kualitas-kode.md.
 * Semua agregasi data didelegasikan ke {@see DashboardRepository}.
 */
class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardRepository $dashboard,
    ) {}

    /**
     * Tampilkan halaman dashboard admin.
     */
    public function index(Request $request): View
    {
        $months = (int) $request->integer('months', 6);
        $months = in_array($months, [3, 6, 12], true) ? $months : 6;

        return view('admin.dashboard', [
            'stats' => $this->dashboard->getStats(),
            'beritaTerbaru' => $this->dashboard->getRecentBerita(5),
            'kegiatanMendatang' => $this->dashboard->getUpcomingKegiatan(5),
            'pesanBelumDibalas' => $this->dashboard->getUnreadMessages(5),
            'recentActivities' => $this->dashboard->getRecentActivities(8),
            'chart' => $this->dashboard->getMonthlyChart($months),
            'chartMonths' => $months,
        ]);
    }
}
