<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

/**
 * Controller untuk halaman Tridharma publik.
 *
 * Pengajaran & Pengabdian dipisahkan dari Berita biasa via kolom enum
 * `tridharma_type` di tabel `beritas`. Sebelum refactor pakai whereHas kategori
 * yang rentan kalau admin hapus kategori sistem; sekarang filter via single
 * indexed column (lebih cepat & robust).
 *
 * Filter Prodi via FK `program_studi_id` (NULL = lintas jurusan).
 */
class TridharmaController extends Controller
{
    /**
     * Halaman Pengajaran — teaching-card horizontal compact (text-first).
     *
     * Query params:
     *   - q     : search keyword (judul + ringkasan, LIKE)
     *   - prodi : filter program_studi_id (numeric ID, atau "lintas" untuk NULL)
     *   - sort  : terbaru | terlama | judul (A-Z)
     */
    public function pengajaran(Request $request)
    {
        $artikels = $this->buildBeritaQuery($request, 'pengajaran')
            ->paginate(6)
            ->withQueryString();

        $prodiList = ProgramStudi::where('is_active', true)
            ->orderBy('jenjang')
            ->orderBy('nama')
            ->get();

        return view('frontend.tridharma.pengajaran', compact('artikels', 'prodiList'));
    }

    /**
     * Halaman Pengabdian Masyarakat — community-card visual storytelling.
     *
     * Query params:
     *   - q     : search keyword
     *   - prodi : filter program_studi_id atau "lintas"
     *   - tahun : filter tahun di kolom tanggal_publikasi
     *   - sort  : terbaru | terlama | judul
     */
    public function pengabdian(Request $request)
    {
        $artikels = $this->buildBeritaQuery($request, 'pengabdian')
            ->paginate(4)
            ->withQueryString();

        $prodiList = ProgramStudi::where('is_active', true)
            ->orderBy('jenjang')
            ->orderBy('nama')
            ->get();

        // Tahun list dari berita published tridharma_type=pengabdian saja.
        $tahunList = Berita::tridharma('pengabdian')
            ->where('is_published', true)
            ->selectRaw('DISTINCT YEAR(tanggal_publikasi) as tahun')
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('frontend.tridharma.pengabdian', compact('artikels', 'prodiList', 'tahunList'));
    }

    /**
     * Builder umum untuk Pengajaran & Pengabdian.
     *
     * Eager-load relasi penulis + programStudi untuk menghindari N+1 di view
     * (badge prodi, label penulis). Kategoris tidak di-eager-load karena
     * Tridharma tidak pakai kategori (kategori sistem sudah dihapus saat refactor).
     *
     * @param  string  $tridharmaType  'pengajaran' | 'pengabdian'
     */
    private function buildBeritaQuery(Request $request, string $tridharmaType)
    {
        $query = Berita::tridharma($tridharmaType)
            ->with(['penulis:id,name', 'programStudi:id,nama,jenjang'])
            ->where('is_published', true);

        // Search keyword
        if ($request->filled('q')) {
            $keyword = $request->string('q')->trim()->toString();
            $query->where(function ($sub) use ($keyword) {
                $sub->where('judul', 'like', "%{$keyword}%")
                    ->orWhere('ringkasan', 'like', "%{$keyword}%");
            });
        }

        // Filter prodi: numeric ID atau "lintas" (NULL)
        if ($request->filled('prodi')) {
            $prodi = $request->string('prodi')->toString();
            if ($prodi === 'lintas') {
                $query->whereNull('program_studi_id');
            } elseif (is_numeric($prodi)) {
                $query->where('program_studi_id', (int) $prodi);
            }
        }

        // Filter tahun (Pengabdian only — Pengajaran tidak pakai)
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_publikasi', (int) $request->tahun);
        }

        // Sort: terbaru (default) | terlama | judul (A-Z)
        $sort = $request->string('sort')->toString() ?: 'terbaru';
        match ($sort) {
            'terlama' => $query->orderBy('tanggal_publikasi', 'asc'),
            'judul' => $query->orderBy('judul', 'asc'),
            default => $query->orderBy('tanggal_publikasi', 'desc'),
        };

        return $query;
    }
}
