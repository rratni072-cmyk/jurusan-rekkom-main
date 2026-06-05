<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TridharmaRequest;
use App\Models\Berita;
use App\Models\ProgramStudi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

/**
 * CRUD konten Tridharma (Pengajaran & Pengabdian) di admin panel.
 *
 * 1 controller mengelola 2 tipe — type ditentukan via URL segment `{type}` di
 * route prefix `/admin/tridharma/{type}/...`. Constraint `where('type', '...')`
 * di route memastikan invalid value (mis. `penelitian`) langsung 404.
 *
 * Pengajaran & Pengabdian disimpan di tabel `beritas` yang sama (DRY) dengan
 * kolom enum `tridharma_type` sebagai pemisah.
 */
class TridharmaController extends Controller
{
    /**
     * Daftar tipe valid + label dinamis untuk breadcrumb/title.
     */
    private const TYPE_LABELS = [
        'pengajaran' => 'Pengajaran',
        'pengabdian' => 'Pengabdian Masyarakat',
    ];

    /**
     * Halaman list (DataTable) per tipe.
     */
    public function index(string $type): View
    {
        $label = self::TYPE_LABELS[$type];

        return view('admin.tridharma.index', compact('type', 'label'));
    }

    /**
     * DataTable JSON endpoint per tipe.
     */
    public function datatable(string $type): JsonResponse
    {
        $query = Berita::tridharma($type)
            ->with(['penulis:id,name', 'programStudi:id,nama,jenjang'])
            ->select('beritas.*');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('gambar_preview', function (Berita $b): string {
                $url = $b->gambar
                    ? asset('storage/'.$b->gambar)
                    : asset('admin/img/avatars/placeholder.jpg');

                return '<img src="'.e($url).'" alt="'.e($b->judul).'" height="50" class="rounded"/>';
            })
            ->addColumn('prodi_label', function (Berita $b): string {
                return '<span class="badge bg-primary-subtle text-primary-emphasis">'.e($b->prodi_badge_label).'</span>';
            })
            ->addColumn('status', function (Berita $b): string {
                return $b->is_published
                    ? '<span class="badge bg-success">Publish</span>'
                    : '<span class="badge bg-secondary">Draft</span>';
            })
            ->addColumn('tanggal', function (Berita $b): string {
                return $b->tanggal_publikasi
                    ? $b->tanggal_publikasi->format('d M Y')
                    : '-';
            })
            ->addColumn('aksi', function (Berita $b) use ($type): string {
                return view('admin.tridharma._aksi', ['berita' => $b, 'type' => $type])->render();
            })
            ->rawColumns(['gambar_preview', 'prodi_label', 'status', 'aksi'])
            ->toJson();
    }

    /**
     * Form tambah konten Tridharma per tipe.
     */
    public function create(string $type): View
    {
        $label = self::TYPE_LABELS[$type];
        $prodiList = ProgramStudi::where('is_active', true)
            ->orderBy('jenjang')
            ->orderBy('nama')
            ->get();

        return view('admin.tridharma.create', compact('type', 'label', 'prodiList'));
    }

    public function store(TridharmaRequest $request, string $type): RedirectResponse
    {
        $data = $request->validated();
        $data['penulis_id'] = (int) auth()->id();
        $data['tridharma_type'] = $type;

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('tridharma', 'public');
        }

        $berita = Berita::create($data);

        activity()->causedBy(auth()->user())->performedOn($berita)
            ->log('Menambahkan '.self::TYPE_LABELS[$type].': '.$berita->judul);

        return redirect()
            ->route('admin.tridharma.index', $type)
            ->with('success', self::TYPE_LABELS[$type].' berhasil ditambahkan!');
    }

    /**
     * Form edit konten Tridharma. Validate ownership: berita harus tridharma_type
     * sesuai $type — supaya admin tidak bisa edit Pengabdian via URL Pengajaran.
     */
    public function edit(string $type, Berita $berita): View
    {
        abort_if($berita->tridharma_type !== $type, Response::HTTP_NOT_FOUND);

        $label = self::TYPE_LABELS[$type];
        $prodiList = ProgramStudi::where('is_active', true)
            ->orderBy('jenjang')
            ->orderBy('nama')
            ->get();

        return view('admin.tridharma.edit', compact('type', 'label', 'berita', 'prodiList'));
    }

    public function update(TridharmaRequest $request, string $type, Berita $berita): RedirectResponse
    {
        abort_if($berita->tridharma_type !== $type, Response::HTTP_NOT_FOUND);

        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
                Storage::disk('public')->delete($berita->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('tridharma', 'public');
        } elseif ($request->boolean('hapus_gambar')) {
            if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
                Storage::disk('public')->delete($berita->gambar);
            }
            $data['gambar'] = null;
        }

        $berita->update($data);

        activity()->causedBy(auth()->user())->performedOn($berita)
            ->log('Mengubah '.self::TYPE_LABELS[$type].': '.$berita->judul);

        return redirect()
            ->route('admin.tridharma.index', $type)
            ->with('success', self::TYPE_LABELS[$type].' berhasil diperbarui!');
    }

    public function destroy(string $type, Berita $berita): JsonResponse
    {
        abort_if($berita->tridharma_type !== $type, Response::HTTP_NOT_FOUND);

        $judul = $berita->judul;
        $berita->delete(); // SoftDelete — gambar tetap disimpan

        activity()->causedBy(auth()->user())
            ->log('Menghapus '.self::TYPE_LABELS[$type].': '.$judul);

        return response()->json([
            'success' => true,
            'message' => self::TYPE_LABELS[$type].' berhasil dihapus!',
        ]);
    }
}
