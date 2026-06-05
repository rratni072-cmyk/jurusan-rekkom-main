<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PedomanRequest;
use App\Models\Pedoman;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

/**
 * Admin CRUD Pedoman Akademik.
 *
 * Fitur:
 * - CRUD via resource route + DataTables server-side
 * - Filter kategori via query string
 * - Toggle aktif/nonaktif cepat (AJAX) — sinkron dengan pola JadwalController
 * - Activity log manual di tiap mutasi (sesuai activity-log.md)
 * - Format file otomatis dari extension upload (tidak perlu input manual)
 */
class PedomanController extends Controller
{
    /**
     * Halaman index + quick stats untuk dashboard admin.
     */
    public function index(): View
    {
        $stats = [
            'total' => Pedoman::count(),
            'aktif' => Pedoman::active()->count(),
            'akademik' => Pedoman::kategori(Pedoman::KATEGORI_AKADEMIK)->count(),
            'tugas_akhir' => Pedoman::kategori(Pedoman::KATEGORI_TUGAS_AKHIR)->count(),
            'wisuda' => Pedoman::kategori(Pedoman::KATEGORI_WISUDA)->count(),
        ];

        return view('admin.pedoman.index', [
            'stats' => $stats,
            'kategoriList' => Pedoman::kategoriOptions(),
        ]);
    }

    /**
     * DataTable server-side dengan filter kategori + status aktif.
     */
    public function datatable(Request $request): JsonResponse
    {
        $query = Pedoman::query()->orderBy('urutan')->orderBy('nama_file');

        if ($request->filled('kategori')) {
            $query->kategori((string) $request->input('kategori'));
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'aktif') {
                $query->where('is_active', true);
            } elseif ($status === 'nonaktif') {
                $query->where('is_active', false);
            }
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('nama_file', function (Pedoman $p): string {
                $desc = $p->deskripsi
                    ? '<div class="small text-body-secondary text-truncate" style="max-width: 380px;">'.e((string) $p->deskripsi).'</div>'
                    : '';

                return '<div class="fw-medium">'.e($p->nama_file).'</div>'.$desc;
            })
            ->addColumn('kategori_badge', fn (Pedoman $p) => '<span class="badge bg-'.$p->kategori_color.'-subtle text-'.$p->kategori_color.'-emphasis">'
                .'<i class="bi '.$p->kategori_icon.' me-1"></i>'.e($p->kategori_label)
                .'</span>'
            )
            ->addColumn('format_badge', fn (Pedoman $p) => '<span class="badge bg-'.$p->format_color.'-subtle text-'.$p->format_color.'-emphasis">'
                .'<i class="bi '.$p->format_icon.' me-1"></i>'.e($p->format_label)
                .'</span>'
            )
            ->addColumn('file_size', fn (Pedoman $p) => '<span class="small text-body-secondary">'.($p->file_size_human ?? '—').'</span>'
            )
            ->addColumn('file_link', fn (Pedoman $p) => $p->file_url
                    ? '<a href="'.$p->file_url.'" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary" title="Buka / Unduh file">'
                        .'<i class="bi bi-box-arrow-up-right"></i></a>'
                    : '<span class="text-body-secondary small">—</span>'
            )
            ->addColumn('status', fn (Pedoman $p) => $p->is_active
                    ? '<span class="badge bg-success-subtle text-success-emphasis"><i class="bi bi-check-circle-fill me-1"></i>Aktif</span>'
                    : '<span class="badge bg-secondary-subtle text-secondary-emphasis"><i class="bi bi-dash-circle me-1"></i>Nonaktif</span>'
            )
            ->addColumn('aksi', function (Pedoman $p): string {
                return view('admin.pedoman._aksi', ['pedoman' => $p])->render();
            })
            ->rawColumns(['nama_file', 'kategori_badge', 'format_badge', 'file_size', 'file_link', 'status', 'aksi'])
            ->toJson();
    }

    public function create(): View
    {
        return view('admin.pedoman.create', [
            'kategoriList' => Pedoman::kategoriOptions(),
        ]);
    }

    public function store(PedomanRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $data['file_path'] = $file->store('pedoman', 'public');
            $data['format_file'] = Pedoman::resolveFormatFromPath($data['file_path']);
        }

        $pedoman = Pedoman::create($data);

        activity()->causedBy(auth()->user())->performedOn($pedoman)
            ->log('Tambah Pedoman: '.$pedoman->nama_file);

        return redirect()->route('admin.pedoman.index')
            ->with('success', 'Pedoman berhasil ditambahkan.');
    }

    public function edit(Pedoman $pedoman): View
    {
        return view('admin.pedoman.edit', [
            'pedoman' => $pedoman,
            'kategoriList' => Pedoman::kategoriOptions(),
        ]);
    }

    public function update(PedomanRequest $request, Pedoman $pedoman): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('file_path')) {
            // Cleanup file lama sebelum overwrite (anti orphan files).
            if ($pedoman->file_path && Storage::disk('public')->exists($pedoman->file_path)) {
                Storage::disk('public')->delete($pedoman->file_path);
            }

            $file = $request->file('file_path');
            $data['file_path'] = $file->store('pedoman', 'public');
            $data['format_file'] = Pedoman::resolveFormatFromPath($data['file_path']);
        } elseif ($request->boolean('hapus_file')) {
            if ($pedoman->file_path && Storage::disk('public')->exists($pedoman->file_path)) {
                Storage::disk('public')->delete($pedoman->file_path);
            }
            $data['file_path'] = null;
            $data['format_file'] = null;
        }

        $pedoman->update($data);

        activity()->causedBy(auth()->user())->performedOn($pedoman)
            ->log('Edit Pedoman: '.$pedoman->nama_file);

        return redirect()->route('admin.pedoman.index')
            ->with('success', 'Pedoman berhasil diperbarui.');
    }

    public function destroy(Pedoman $pedoman): JsonResponse
    {
        $nama = $pedoman->nama_file;

        if ($pedoman->file_path && Storage::disk('public')->exists($pedoman->file_path)) {
            Storage::disk('public')->delete($pedoman->file_path);
        }

        $pedoman->delete();

        activity()->causedBy(auth()->user())->log('Hapus Pedoman: '.$nama);

        return response()->json([
            'success' => true,
            'message' => 'Pedoman berhasil dihapus.',
        ]);
    }

    /**
     * Toggle aktif/nonaktif cepat via AJAX (dipakai switch di DataTable).
     */
    public function toggleActive(Pedoman $pedoman): JsonResponse
    {
        $pedoman->update(['is_active' => ! $pedoman->is_active]);

        $aksi = $pedoman->is_active ? 'Aktifkan' : 'Nonaktifkan';
        activity()->causedBy(auth()->user())->performedOn($pedoman)
            ->log($aksi.' Pedoman: '.$pedoman->nama_file);

        return response()->json([
            'success' => true,
            'is_active' => $pedoman->is_active,
            'message' => $pedoman->is_active
                ? 'Pedoman berhasil diaktifkan.'
                : 'Pedoman berhasil dinonaktifkan.',
        ]);
    }
}
