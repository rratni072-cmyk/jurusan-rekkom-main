<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Beasiswa;
use App\Models\Jadwal;
use App\Models\Kegiatan;
use App\Models\Pedoman;
use App\Models\TipeKegiatan;
use Illuminate\Http\Request;

/**
 * Controller untuk halaman Kemahasiswaan publik.
 *
 * Menampilkan jadwal perkuliahan, pedoman, beasiswa, dan kegiatan.
 */
class KemahasiswaanController extends Controller
{
    /**
     * Halaman Jadwal Perkuliahan — Year Tabs (PDDikti-style).
     *
     * UI: Tab navigation tahun ajaran di atas, dengan tahun terbaru ditandai
     * "Aktif". Setiap tab menampilkan tabel jadwal untuk tahun tersebut,
     * difilter lebih lanjut dengan toggle Semester (Semua/Ganjil/Genap)
     * dan input search client-side.
     *
     * Output ke view:
     *   - $jadwalsByTahun:  Collection<string, Collection<Jadwal>>
     *                       grouped by tahun_ajaran (latest first)
     *   - $listTahunAjaran: array<string> — tahun ajaran sorted desc (untuk tabs)
     *   - $tahunAktif:      string|null — tahun terbaru (= tab default + badge "Aktif")
     */
    public function jadwal()
    {
        // Ambil semua jadwal aktif + eager load prodi.
        // Default order: tahun desc, Genap dulu (biasanya semester berjalan), lalu alfabet.
        $jadwals = Jadwal::active()
            ->with('programStudi:id,nama,jenjang')
            ->orderByDesc('tahun_ajaran')
            ->orderByRaw("CASE semester WHEN 'Genap' THEN 1 WHEN 'Ganjil' THEN 2 ELSE 3 END")
            ->orderBy('program_studi')
            ->get();

        // Group by tahun_ajaran untuk efisiensi render per-tab di blade.
        $jadwalsByTahun = $jadwals->groupBy('tahun_ajaran');

        // List tahun ajaran sorted desc — jadi urutan tab kiri-ke-kanan (terbaru kiri).
        $listTahunAjaran = $jadwalsByTahun->keys()->values();

        // Tahun aktif = tahun pertama (terbaru) di list.
        $tahunAktif = $listTahunAjaran->first();

        return view('frontend.kemahasiswaan.jadwal', compact(
            'jadwalsByTahun',
            'listTahunAjaran',
            'tahunAktif',
        ));
    }

    /**
     * Halaman Pedoman — grid card + filter kategori + search.
     *
     * UI: Filter pills kategori di atas (Semua / Akademik / Tugas Akhir / Wisuda),
     * search input untuk filter nama file, dan card grid untuk setiap pedoman
     * dengan icon file type (PDF/Excel), deskripsi, ukuran, dan tombol unduh.
     *
     * Output ke view:
     *   - $pedomans:      Collection<Pedoman> — semua pedoman aktif (sorted)
     *   - $pedomansCount: array<string,int>   — hitungan per kategori + total
     *                                           (dipakai untuk count di filter pills)
     *   - $kategoriList:  array<string,string> — [slug => label] untuk render pills
     */
    public function pedoman()
    {
        // Ambil semua pedoman aktif, sorted by urutan (admin control) lalu alfabet.
        $pedomans = Pedoman::active()
            ->orderBy('urutan')
            ->orderBy('nama_file')
            ->get();

        // Hitung per kategori untuk badge count di filter pills.
        $pedomansCount = [
            'all' => $pedomans->count(),
        ];
        foreach (array_keys(Pedoman::KATEGORI) as $slug) {
            $pedomansCount[$slug] = $pedomans->where('kategori', $slug)->count();
        }

        return view('frontend.kemahasiswaan.pedoman', [
            'pedomans' => $pedomans,
            'pedomansCount' => $pedomansCount,
            'kategoriList' => Pedoman::kategoriOptions(),
        ]);
    }

    /**
     * Halaman Beasiswa — grid cards info beasiswa (paginated).
     */
    public function beasiswa()
    {
        $beasiswas = Beasiswa::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(8)
            ->withQueryString();

        return view('frontend.kemahasiswaan.beasiswa', compact('beasiswas'));
    }

    /**
     * Halaman Kegiatan — list compact horizontal card + filter.
     *
     * Query params:
     *   - q     : search keyword (judul + ringkasan, LIKE)
     *   - tipe  : filter slug tipe kegiatan (sumber: tabel `tipe_kegiatans`)
     *   - tahun : filter berdasarkan tahun di kolom `tanggal`
     *
     * Pagination: 8 per halaman (genap → 4 row × 2 col di desktop).
     * `withQueryString()` agar pagination preserve filter aktif.
     */
    public function kegiatan(Request $request)
    {
        // Eager-load relasi tipeKegiatan untuk anti N+1 saat render badge per card.
        $query = Kegiatan::with('tipeKegiatan')->where('is_published', true);

        if ($request->filled('q')) {
            $keyword = $request->string('q')->trim()->toString();
            $query->where(function ($sub) use ($keyword) {
                $sub->where('judul', 'like', "%{$keyword}%")
                    ->orWhere('ringkasan', 'like', "%{$keyword}%");
            });
        }

        // Filter tipe via slug → lookup ID master. Tipe non-aktif tidak bisa difilter.
        if ($request->filled('tipe')) {
            $tipeId = TipeKegiatan::active()->where('slug', $request->tipe)->value('id');
            if ($tipeId) {
                $query->where('tipe_kegiatan_id', $tipeId);
            }
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', (int) $request->tahun);
        }

        $kegiatans = $query->orderBy('tanggal', 'desc')
            ->paginate(8)
            ->withQueryString();

        // Daftar tahun untuk dropdown filter — diambil distinct dari data published.
        $tahunList = Kegiatan::where('is_published', true)
            ->selectRaw('DISTINCT YEAR(tanggal) as tahun')
            ->orderByDesc('tahun')
            ->pluck('tahun');

        // Daftar tipe aktif untuk dropdown filter (sumber: master DB).
        $tipeList = TipeKegiatan::active()->ordered()->get();

        return view('frontend.kemahasiswaan.kegiatan', compact('kegiatans', 'tahunList', 'tipeList'));
    }

    /**
     * Halaman Detail Kegiatan — full article view (2-col + sidebar) konsisten
     * dengan pattern berita.show.
     *
     * Mirip berita.show: judul, lead, meta (tanggal+tipe), konten, share,
     * prev/next, sidebar (Tentang Tipe + Kegiatan Terkini), related kegiatan.
     *
     * Output ke view:
     *   - $kegiatan:        Kegiatan       — kegiatan aktif yang ditampilkan
     *   - $prev:            Kegiatan|null  — kegiatan sebelumnya (tanggal lebih lama)
     *   - $next:            Kegiatan|null  — kegiatan berikutnya (tanggal lebih baru)
     *   - $kegiatanTerkini: Collection<Kegiatan>  — 5 latest, exclude current (sidebar)
     *   - $terkait:         Collection<Kegiatan>  — 6 kegiatan tipe sama, exclude current
     *   - $tipeDeskripsi:   string|null    — deskripsi static untuk sidebar "Tentang Tipe"
     */
    public function kegiatanShow(string $slug)
    {
        $kegiatan = Kegiatan::where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment view counter (session-deduped, skip admin login).
        $kegiatan->incrementViewOnce();

        // Prev = kegiatan published dengan tanggal lebih lama (urut DESC, ambil pertama).
        $prev = Kegiatan::where('is_published', true)
            ->where('tanggal', '<', $kegiatan->tanggal)
            ->orderByDesc('tanggal')
            ->first();

        // Next = kegiatan published dengan tanggal lebih baru (urut ASC, ambil pertama).
        $next = Kegiatan::where('is_published', true)
            ->where('tanggal', '>', $kegiatan->tanggal)
            ->orderBy('tanggal')
            ->first();

        // Eager load tipe untuk akses badge & slug tanpa N+1.
        $kegiatan->loadMissing('tipeKegiatan');

        // Sidebar: 5 kegiatan terkini terbaru (exclude current).
        $kegiatanTerkini = Kegiatan::with('tipeKegiatan')
            ->where('is_published', true)
            ->where('id', '!=', $kegiatan->id)
            ->orderByDesc('tanggal')
            ->limit(5)
            ->get();

        // Related: max 6 kegiatan dengan tipe sama (exclude current).
        $terkait = Kegiatan::with('tipeKegiatan')
            ->where('is_published', true)
            ->where('tipe_kegiatan_id', $kegiatan->tipe_kegiatan_id)
            ->where('id', '!=', $kegiatan->id)
            ->orderByDesc('tanggal')
            ->limit(6)
            ->get();

        // Deskripsi sidebar "Tentang Tipe Kegiatan" — static map by slug.
        // Sengaja static (bukan kolom DB) karena copy editorial yang panjang &
        // jarang berubah; kalau butuh lebih fleksibel, tambah kolom `deskripsi`
        // di tabel `tipe_kegiatans`.
        $tipeDeskripsiMap = [
            'workshop' => 'Pelatihan praktis hands-on untuk peningkatan kompetensi teknis mahasiswa Jurusan R&K.',
            'seminar' => 'Forum berbagi pengetahuan dengan narasumber ahli untuk wawasan akademik dan industri.',
            'lomba' => 'Kompetisi mahasiswa di tingkat lokal, nasional, atau internasional sebagai sarana pengembangan diri dan prestasi.',
            'kunjungan' => 'Kunjungan industri sebagai jembatan antara pendidikan vokasi dan dunia kerja.',
            'hima' => 'Kegiatan organisasi Himpunan Mahasiswa untuk pengembangan soft skill dan kebersamaan.',
            'akademik' => 'Kegiatan akademik resmi jurusan: orientasi, bimbingan, evaluasi pembelajaran.',
        ];
        $tipeSlug = $kegiatan->tipeKegiatan?->slug;
        $tipeDeskripsi = $tipeSlug ? ($tipeDeskripsiMap[$tipeSlug] ?? null) : null;

        return view('frontend.kemahasiswaan.kegiatan-show', compact(
            'kegiatan',
            'prev',
            'next',
            'kegiatanTerkini',
            'terkait',
            'tipeDeskripsi',
        ));
    }
}
