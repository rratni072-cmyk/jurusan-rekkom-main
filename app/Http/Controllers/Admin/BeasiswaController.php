<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BeasiswaRequest;
use App\Models\Beasiswa;
use Yajra\DataTables\Facades\DataTables;

class BeasiswaController extends Controller
{
    public function index()
    {
        return view('admin.beasiswa.index');
    }

    public function datatable()
    {
        $beasiswas = Beasiswa::latest()->get();

        return DataTables::of($beasiswas)
            ->addIndexColumn()
            ->addColumn('deskripsi_short', function ($b) {
                return \Str::limit(strip_tags($b->deskripsi), 80);
            })
            ->addColumn('status', function ($b) {
                return $b->is_active
                    ? '<span class="badge bg-success">Aktif</span>'
                    : '<span class="badge bg-secondary">Nonaktif</span>';
            })
            ->addColumn('aksi', function ($b) {
                return view('admin.beasiswa._aksi', ['beasiswa' => $b])->render();
            })
            ->rawColumns(['status', 'aksi'])
            ->toJson();
    }

    public function create()
    {
        return view('admin.beasiswa.create');
    }

    public function store(BeasiswaRequest $request)
    {
        $data = $request->validated();

        $beasiswa = Beasiswa::create($data);

        activity()->causedBy(auth()->user())->performedOn($beasiswa)
            ->log('Menambahkan beasiswa: '.$beasiswa->nama);

        return redirect()->route('admin.beasiswa.index')
            ->with('success', 'Beasiswa berhasil ditambahkan!');
    }

    public function edit(Beasiswa $beasiswa)
    {
        return view('admin.beasiswa.edit', compact('beasiswa'));
    }

    public function update(BeasiswaRequest $request, Beasiswa $beasiswa)
    {
        $data = $request->validated();

        $beasiswa->update($data);

        activity()->causedBy(auth()->user())->performedOn($beasiswa)
            ->log('Mengubah beasiswa: '.$beasiswa->nama);

        return redirect()->route('admin.beasiswa.index')
            ->with('success', 'Beasiswa berhasil diperbarui!');
    }

    public function destroy(Beasiswa $beasiswa)
    {
        $nama = $beasiswa->nama;

        $beasiswa->delete();

        activity()->causedBy(auth()->user())->log('Menghapus beasiswa: '.$nama);

        return response()->json(['success' => true, 'message' => 'Beasiswa berhasil dihapus!']);
    }
}
