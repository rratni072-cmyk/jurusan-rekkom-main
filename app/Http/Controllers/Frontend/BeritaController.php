<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Kategori;
use App\Repositories\BeritaRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller untuk halaman berita publik.
 *
 * Menampilkan daftar berita dan detail berita.
 * Akses data via {@see BeritaRepository} sesuai pola Repository.
 */
class BeritaController extends Controller
{
    public function __construct(
        private readonly BeritaRepository $beritaRepository,
    ) {}

    /**
     * Tampilkan daftar berita (paginated, dengan filter search + kategori + sort).
     *
     * Query params:
     *   - search   : keyword pencarian (judul + ringkasan, LIKE)
     *   - kategori : slug kategori (filter whereHas)
     *   - sort     : terbaru (default) | terlama | judul (A-Z) | populer (views desc)
     */
    public function index(Request $request): View
    {
        $filters = [
            'search' => trim((string) $request->query('search', '')),
            'kategori' => trim((string) $request->query('kategori', '')),
            'sort' => $request->query('sort', 'terbaru'),
        ];

        $beritas = $this->beritaRepository->paginateWithFilters($filters, 8);

        // Sidebar widget Kategori — HANYA tipe editorial dan punya berita published.
        // Kategori "topik" (Pengajaran/Pengabdian/Penelitian/Kegiatan) sengaja
        // disembunyikan: sudah punya halaman/menu sendiri (Tridharma, Kemahasiswaan).
        $kategoris = Kategori::editorial()
            ->withCount(['beritas' => function ($q) {
                $q->where('is_published', true);
            }])
            ->having('beritas_count', '>', 0)
            ->orderBy('nama')
            ->get();

        // Total semua berita publik (untuk kategori "Semua" di widget sidebar).
        // Eksklusi konten Tridharma — sudah ada di halaman dedicated.
        $totalPublished = Berita::regular()->where('is_published', true)->count();

        // Kategori aktif: lookup ke seluruh kategori (termasuk topik), agar
        // deep-link `?kategori=pengajaran` dari halaman lain tetap aktif &
        // ter-label di info bar — meski tidak muncul di sidebar widget.
        $kategoriActive = $filters['kategori'] !== ''
            ? Kategori::where('slug', $filters['kategori'])->first()
            : null;

        return view('frontend.berita.index', compact(
            'beritas', 'kategoris', 'filters', 'kategoriActive', 'totalPublished'
        ));
    }

    /**
     * Tampilkan detail berita.
     *
     * Eager-load penulis + programStudi + kategoris untuk hindari N+1
     * di meta header, prodi badge, dan kategori tag.
     *
     * Data yang dikirim ke view:
     * - $berita          — artikel utama
     * - $terkait         — 3 berita kategori sama (untuk section bawah)
     * - $prev / $next    — navigasi artikel sebelum/sesudah (kronologis)
     * - $beritaTerkini   — 5 berita terbaru (untuk sidebar kanan)
     * - $tagPopuler      — 8 kategori dgn berita terbanyak (tag cloud sidebar)
     * - $isPengajaran    — bool, apakah artikel tridharma pengajaran
     * - $isPengabdian    — bool, apakah artikel tridharma pengabdian
     */
    public function show(string $slug): View
    {
        $berita = Berita::with(['penulis:id,name', 'programStudi:id,nama,jenjang', 'kategoris:id,nama,slug'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Increment view counter (session-deduped, skip admin login).
        $berita->incrementViewOnce();

        // Context-aware navigation: navigasi prev/next/terkait/sidebar harus
        // tetap di "ekosistem" yang sama dengan artikel saat ini supaya mental
        // model konsisten — Tridharma navigasi antar Tridharma sejenis,
        // berita biasa navigasi antar berita biasa.
        $applyContext = function ($query) use ($berita) {
            return $berita->tridharma_type
                ? $query->tridharma($berita->tridharma_type)
                : $query->regular();
        };

        // Berita terkait: kategori sama, kecuali diri sendiri.
        // Tridharma tidak pakai kategori → fallback ke artikel terbaru tipe sama.
        $kategoriIds = $berita->kategoris->pluck('id');

        $terkait = $applyContext(Berita::query())
            ->published()
            ->with(['penulis:id,name', 'kategoris:id,nama'])
            ->where('id', '!=', $berita->id)
            ->when($kategoriIds->isNotEmpty(), function ($q) use ($kategoriIds) {
                $q->whereHas('kategoris', function ($sub) use ($kategoriIds) {
                    $sub->whereIn('kategoris.id', $kategoriIds);
                });
            })
            ->latest('tanggal_publikasi')
            ->take(3)
            ->get();

        // Artikel sebelumnya (dipublish LEBIH LAMA dari current article).
        $prev = $applyContext(Berita::query())
            ->published()
            ->where('id', '!=', $berita->id)
            ->where('tanggal_publikasi', '<', $berita->tanggal_publikasi)
            ->orderByDesc('tanggal_publikasi')
            ->first(['id', 'slug', 'judul', 'tanggal_publikasi', 'gambar']);

        // Artikel berikutnya (dipublish LEBIH BARU dari current article).
        $next = $applyContext(Berita::query())
            ->published()
            ->where('id', '!=', $berita->id)
            ->where('tanggal_publikasi', '>', $berita->tanggal_publikasi)
            ->orderBy('tanggal_publikasi', 'asc')
            ->first(['id', 'slug', 'judul', 'tanggal_publikasi', 'gambar']);

        // Sidebar — Artikel Terkini (5 artikel terbaru, exclude current, sesuai konteks).
        $beritaTerkini = $applyContext(Berita::query())
            ->published()
            ->where('id', '!=', $berita->id)
            ->latest('tanggal_publikasi')
            ->take(5)
            ->get(['id', 'slug', 'judul', 'tanggal_publikasi', 'gambar']);

        // Sidebar — Tag Populer (kategori dgn jumlah berita terbanyak).
        $tagPopuler = Kategori::withCount([
            'beritas' => fn ($q) => $q->where('is_published', true),
        ])
            ->having('beritas_count', '>', 0)
            ->orderByDesc('beritas_count')
            ->take(8)
            ->get();

        // Context flags untuk breadcrumb, sidebar heading, dan link "Lihat Semua".
        $isPengajaran = $berita->tridharma_type === 'pengajaran';
        $isPengabdian = $berita->tridharma_type === 'pengabdian';

        return view('frontend.berita.show', compact(
            'berita', 'terkait', 'prev', 'next', 'beritaTerkini', 'tagPopuler',
            'isPengajaran', 'isPengabdian'
        ));
    }
}
