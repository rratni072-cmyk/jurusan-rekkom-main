<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TipeKegiatanRequest;
use App\Models\TipeKegiatan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

/**
 * Admin CRUD untuk master Tipe Kegiatan.
 *
 * Pola sejajar dengan KegiatanController (DataTable server-side + SweetAlert
 * delete). Aksi delete dilindungi: tidak bisa hapus tipe yang masih dipakai
 * oleh kegiatan (restrict on delete FK).
 */
class TipeKegiatanController extends Controller
{
    public function index(): View
    {
        return view('admin.tipe-kegiatan.index');
    }

    public function datatable(): JsonResponse
    {
        $query = TipeKegiatan::query()->withCount('kegiatans')->select('tipe_kegiatans.*');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('icon_preview', function (TipeKegiatan $tipe) {
                return '<i class="bi '.e($tipe->icon).' fs-4 text-primary" aria-hidden="true"></i>';
            })
            ->addColumn('status', function (TipeKegiatan $tipe) {
                return $tipe->is_active
                    ? '<span class="badge bg-success">Aktif</span>'
                    : '<span class="badge bg-secondary">Non-aktif</span>';
            })
            ->addColumn('kegiatan_count', function (TipeKegiatan $tipe) {
                return '<span class="badge bg-info">'.$tipe->kegiatans_count.' kegiatan</span>';
            })
            ->addColumn('aksi', function (TipeKegiatan $tipe) {
                return view('admin.tipe-kegiatan._aksi', ['tipe' => $tipe])->render();
            })
            ->rawColumns(['icon_preview', 'status', 'kegiatan_count', 'aksi'])
            ->toJson();
    }

    public function create(): View
    {
        return view('admin.tipe-kegiatan.create');
    }

    public function store(TipeKegiatanRequest $request): RedirectResponse
    {
        $tipe = TipeKegiatan::create($request->validated());

        activity()->causedBy(auth()->user())->performedOn($tipe)
            ->log('Menambahkan tipe kegiatan: '.$tipe->label);

        return redirect()->route('admin.tipe-kegiatan.index')
            ->with('success', 'Tipe kegiatan berhasil ditambahkan!');
    }

    public function edit(TipeKegiatan $tipeKegiatan): View
    {
        return view('admin.tipe-kegiatan.edit', ['tipe' => $tipeKegiatan]);
    }

    public function update(TipeKegiatanRequest $request, TipeKegiatan $tipeKegiatan): RedirectResponse
    {
        $tipeKegiatan->update($request->validated());

        activity()->causedBy(auth()->user())->performedOn($tipeKegiatan)
            ->log('Mengubah tipe kegiatan: '.$tipeKegiatan->label);

        return redirect()->route('admin.tipe-kegiatan.index')
            ->with('success', 'Tipe kegiatan berhasil diperbarui!');
    }

    public function destroy(TipeKegiatan $tipeKegiatan): JsonResponse
    {
        // Proteksi: kalau masih dipakai kegiatan, tolak hapus (FK restrict juga
        // akan throw exception, tapi kita cek lebih awal untuk pesan ramah).
        $usedCount = $tipeKegiatan->kegiatans()->count();
        if ($usedCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Tipe \"{$tipeKegiatan->label}\" masih dipakai oleh {$usedCount} kegiatan. Ubah tipe kegiatan tersebut dulu, atau non-aktifkan tipe ini.",
            ], 422);
        }

        $label = $tipeKegiatan->label;
        $tipeKegiatan->delete();

        activity()->causedBy(auth()->user())
            ->log('Menghapus tipe kegiatan: '.$label);

        return response()->json([
            'success' => true,
            'message' => 'Tipe kegiatan berhasil dihapus!',
        ]);
    }
}
