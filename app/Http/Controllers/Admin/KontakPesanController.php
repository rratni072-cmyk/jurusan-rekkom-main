<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KontakPesan;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Utilities\Request as DataTablesRequest;

/**
 * Controller Pesan Masuk (KontakPesan).
 *
 * Menampilkan pesan dari pengunjung website.
 * Read-only + mark as read (POST) + delete.
 */
class KontakPesanController extends Controller
{
    /**
     * Tampilkan daftar pesan masuk.
     */
    public function index(): View
    {
        return view('admin.pesan.index');
    }

    /**
     * DataTable server-side (real eloquent builder, paginate di SQL).
     */
    public function datatable(DataTablesRequest $request): JsonResponse
    {
        $query = KontakPesan::query()->select('kontak_pesans.*');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('status', fn (KontakPesan $pesan) => $pesan->is_read
                ? '<span class="badge bg-secondary">Dibaca</span>'
                : '<span class="badge bg-warning text-dark">Belum Dibaca</span>')
            ->addColumn('aksi', fn (KontakPesan $pesan) => view('admin.pesan._aksi', compact('pesan'))->render())
            ->editColumn('nama', function (KontakPesan $pesan) {
                $bold = $pesan->is_read ? '' : 'fw-bold';

                return "<span class=\"{$bold}\">{$pesan->nama}</span>";
            })
            ->editColumn('subjek', function (KontakPesan $pesan) {
                $bold = $pesan->is_read ? '' : 'fw-bold';

                return "<span class=\"{$bold}\">{$pesan->subjek}</span>";
            })
            ->editColumn('created_at', fn (KontakPesan $pesan) => $pesan->created_at->format('d M Y H:i'))
            ->rawColumns(['status', 'aksi', 'nama', 'subjek'])
            ->toJson();
    }

    /**
     * Tampilkan detail pesan (READ-ONLY, tidak mutate state).
     *
     * Mark-as-read sekarang via endpoint POST terpisah {@see markRead()}.
     * Dipanggil otomatis oleh JS di halaman detail (lihat pesan/show.blade.php).
     */
    public function show(KontakPesan $pesan): View
    {
        return view('admin.pesan.show', compact('pesan'));
    }

    /**
     * Tandai pesan sudah dibaca (mutasi state — POST, idempotent).
     */
    public function markRead(KontakPesan $pesan): JsonResponse
    {
        if (! $pesan->is_read) {
            $pesan->update(['is_read' => true]);
            \App\Support\AdminCache::forgetUnreadMessageCount();
        }

        return response()->json([
            'success' => true,
            'is_read' => true,
        ]);
    }

    /**
     * Hapus pesan.
     */
    public function destroy(KontakPesan $pesan): JsonResponse
    {
        $nama = $pesan->nama;
        $pesan->delete();

        \App\Support\AdminCache::forgetUnreadMessageCount();

        activity()
            ->causedBy(auth()->user())
            ->log("Menghapus pesan dari {$nama}");

        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil dihapus.',
        ]);
    }
}
