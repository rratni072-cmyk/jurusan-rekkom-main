<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Akreditasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AkreditasiController extends Controller
{
    public function index()
    {
        $akreditasis = Akreditasi::all();
        return view('admin.akreditasi.index', compact('akreditasis'));
    }

    public function create()
    {
        return view('admin.akreditasi.create');
    }

    public function store(Request $request)
    {
        $data = $request->only([
            'program_studi',
            'no_sk',
            'peringkat',
            'tahun_sk',
            'tanggal_kedaluwarsa',
            'status'
        ]);

        if ($request->hasFile('file_sertifikat')) {
            $path = $request->file('file_sertifikat')
                            ->store('akreditasi', 'public');

            $data['file_sertifikat'] = $path;
        }

        Akreditasi::create($data);

        return redirect()
            ->route('admin.akreditasi.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $akreditasi = Akreditasi::findOrFail($id);
        return view('admin.akreditasi.edit', compact('akreditasi'));
    }

    public function update(Request $request, $id)
    {
        $akreditasi = Akreditasi::findOrFail($id);

        $data = $request->only([
            'program_studi',
            'no_sk',
            'peringkat',
            'tahun_sk',
            'tanggal_kedaluwarsa',
            'status'
        ]);

        if ($request->hasFile('file_sertifikat')) {
            // Hapus file lama jika ada
            if ($akreditasi->file_sertifikat) {
                Storage::disk('public')->delete($akreditasi->file_sertifikat);
            }

            $path = $request->file('file_sertifikat')
                            ->store('akreditasi', 'public');

            $data['file_sertifikat'] = $path;
        }

        $akreditasi->update($data);

        return redirect()
            ->route('admin.akreditasi.index')
            ->with('success', 'Data berhasil diperbarui');
    }
}