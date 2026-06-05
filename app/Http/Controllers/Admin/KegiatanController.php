<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\KegiatanRequest;
use App\Models\Kegiatan;
use App\Models\TipeKegiatan;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class KegiatanController extends Controller
{
    public function index()
    {
        return view('admin.kegiatan.index');
    }

    public function datatable()
    {
        $query = Kegiatan::query()->select('kegiatans.*');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('gambar_preview', function ($k) {
                $url = $k->gambar
                    ? asset('storage/'.$k->gambar)
                    : asset('admin/img/avatars/placeholder.jpg');

                return '<img src="'.$url.'" alt="'.$k->judul.'" height="50" class="rounded"/>';
            })
            ->addColumn('status', function ($k) {
                return $k->is_published
                    ? '<span class="badge bg-success">Publish</span>'
                    : '<span class="badge bg-secondary">Draft</span>';
            })
            ->addColumn('tanggal_formatted', function ($k) {
                return $k->tanggal->format('d M Y');
            })
            ->addColumn('aksi', function ($k) {
                return view('admin.kegiatan._aksi', ['kegiatan' => $k])->render();
            })
            ->rawColumns(['gambar_preview', 'status', 'aksi'])
            ->toJson();
    }

    public function create()
    {
        $tipeList = TipeKegiatan::ordered()->get();

        return view('admin.kegiatan.create', compact('tipeList'));
    }

    public function store(KegiatanRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('kegiatan', 'public');
        }

        $kegiatan = Kegiatan::create($data);

        activity()->causedBy(auth()->user())->performedOn($kegiatan)
            ->log('Menambahkan kegiatan: '.$kegiatan->judul);

        return redirect()->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil ditambahkan!');
    }

    public function edit(Kegiatan $kegiatan)
    {
        $tipeList = TipeKegiatan::ordered()->get();

        return view('admin.kegiatan.edit', compact('kegiatan', 'tipeList'));
    }

    public function update(KegiatanRequest $request, Kegiatan $kegiatan)
    {
        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            if ($kegiatan->gambar && Storage::disk('public')->exists($kegiatan->gambar)) {
                Storage::disk('public')->delete($kegiatan->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('kegiatan', 'public');
        } elseif ($request->boolean('hapus_gambar')) {
            if ($kegiatan->gambar && Storage::disk('public')->exists($kegiatan->gambar)) {
                Storage::disk('public')->delete($kegiatan->gambar);
            }
            $data['gambar'] = null;
        }

        $kegiatan->update($data);

        activity()->causedBy(auth()->user())->performedOn($kegiatan)
            ->log('Mengubah kegiatan: '.$kegiatan->judul);

        return redirect()->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil diperbarui!');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $judul = $kegiatan->judul;

        if ($kegiatan->gambar && Storage::disk('public')->exists($kegiatan->gambar)) {
            Storage::disk('public')->delete($kegiatan->gambar);
        }

        $kegiatan->delete();

        activity()->causedBy(auth()->user())->log('Menghapus kegiatan: '.$judul);

        return response()->json(['success' => true, 'message' => 'Kegiatan berhasil dihapus!']);
    }
}
