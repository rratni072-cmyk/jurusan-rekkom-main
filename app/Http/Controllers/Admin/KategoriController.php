<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\KategoriRequest;
use App\Models\Kategori;
use Yajra\DataTables\Facades\DataTables;

class KategoriController extends Controller
{
    public function index()
    {
        return view('admin.kategori.index');
    }

    public function datatable()
    {
        return DataTables::of(Kategori::withCount('beritas')->get())
            ->addIndexColumn()
            ->addColumn('tipe_badge', function ($kategori) {
                if ($kategori->isEditorial()) {
                    return '<span class="badge bg-primary">Editorial</span>';
                }

                return '<span class="badge bg-secondary">Topik</span>';
            })
            ->addColumn('aksi', function ($kategori) {
                return view('admin.kategori._aksi', compact('kategori'))->render();
            })
            ->rawColumns(['tipe_badge', 'aksi'])
            ->toJson();
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(KategoriRequest $request)
    {
        Kategori::create($request->validated());

        activity()->causedBy(auth()->user())->log('Menambahkan kategori: '.$request->nama);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(KategoriRequest $request, Kategori $kategori)
    {
        $kategori->update($request->validated());

        activity()->causedBy(auth()->user())->performedOn($kategori)
            ->log('Mengubah kategori: '.$kategori->nama);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Kategori $kategori)
    {
        $nama = $kategori->nama;
        $kategori->delete();

        activity()->causedBy(auth()->user())->log('Menghapus kategori: '.$nama);

        return response()->json(['success' => true, 'message' => 'Kategori berhasil dihapus!']);
    }
}
