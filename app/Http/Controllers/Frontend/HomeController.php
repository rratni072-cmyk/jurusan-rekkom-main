<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Repositories\BeritaRepository;
use App\Repositories\ProgramStudiRepository;
use Illuminate\View\View;

/**
 * Controller untuk halaman beranda website publik.
 *
 * Tetap ringan sesuai .agents/rules/kualitas-kode.md:
 * data berita & program studi di-fetch via Repository.
 */
class HomeController extends Controller
{
    public function __construct(
        private readonly BeritaRepository $beritaRepository,
        private readonly ProgramStudiRepository $programStudiRepository,
    ) {}

    /**
     * Tampilkan halaman beranda.
     */
    public function index(): View
    {
        $sliders = Slider::where('is_active', true)
            ->orderBy('urutan')
            ->get();

        $beritas = $this->beritaRepository->getHomepageHighlights(5);

        $prodiList = $this->programStudiRepository->getActiveForHome();

        return view('frontend.home', compact(
            'sliders',
            'beritas',
            'prodiList',
        ));
    }
}
