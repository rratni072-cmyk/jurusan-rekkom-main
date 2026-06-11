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

    public function datatable()
    {
        $akreditasis = Akreditasi::all();
        return response()->json(['data' => $akreditasis]);
    }

    public function edit(Akreditasi $akreditasi)
    {
        return view('admin.akreditasi.edit', compact('akreditasi'));
    }

    public function update(Request $request, Akreditasi $akreditasi)
    {
        $data = $request->only([
            'program_studi','no_sk','peringkat',
            'tahun_sk','tanggal_kedaluwarsa','status'
        ]);

        if ($request->hasFile('file_sertifikat')) {
            if ($akreditasi->file_sertifikat) {
                Storage::delete('public/' . $akreditasi->file_sertifikat);
            }
            $path = $request->file('file_sertifikat')->store('akreditasi', 'public');
            $data['file_sertifikat'] = $path;
        }

        $akreditasi->update($data);
        return redirect()->route('admin.akreditasi.index')
            ->with('success', 'Data berhasil diupdate');
    }
}