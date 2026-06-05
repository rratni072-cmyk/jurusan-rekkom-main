<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BeritaRequest;
use App\Models\Berita;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class BeritaController extends Controller
{
    /**
     * Halaman list berita admin.
     *
     * Hanya tampil berita biasa (tridharma_type IS NULL). Konten Tridharma
     * (Pengajaran & Pengabdian) sudah pindah ke menu admin terpisah
     * `/admin/tridharma/{type}`.
     */
    public function index()
    {
        return view('admin.berita.index');
    }

    /**
     * DataTable JSON endpoint untuk berita biasa saja.
     */
    public function datatable()
    {
        $query = Berita::regular()
            ->with(['penulis:id,name', 'kategoris:id,nama'])
            ->select('beritas.*');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('gambar_preview', function ($b) {
                $url = $b->gambar
                    ? asset('storage/'.$b->gambar)
                    : asset('admin/img/avatars/placeholder.jpg');

                return '<img src="'.$url.'" alt="'.$b->judul.'" height="50" class="rounded"/>';
            })
            ->addColumn('kategori_list', function ($b) {
                return $b->kategoris->map(function ($k) {
                    return '<span class="badge bg-primary me-1">'.$k->nama.'</span>';
                })->implode('');
            })
            ->addColumn('status', function ($b) {
                return $b->is_published
                    ? '<span class="badge bg-success">Publish</span>'
                    : '<span class="badge bg-secondary">Draft</span>';
            })
            ->addColumn('tanggal', function ($b) {
                return $b->tanggal_publikasi
                    ? $b->tanggal_publikasi->format('d M Y')
                    : '-';
            })
            ->addColumn('aksi', function ($b) {
                return view('admin.berita._aksi', ['berita' => $b])->render();
            })
            ->rawColumns(['gambar_preview', 'kategori_list', 'status', 'aksi'])
            ->toJson();
    }

    public function create()
    {
        $kategoris = Kategori::orderBy('nama')->get();

        return view('admin.berita.create', compact('kategoris'));
    }

    public function store(BeritaRequest $request)
    {
        $data = $request->validated();
        $data['penulis_id'] = auth()->id();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        $berita = Berita::create($data);

        // Sync kategori (many-to-many)
        if ($request->has('kategori_ids')) {
            $berita->kategoris()->sync($request->kategori_ids);
        }

        activity()->causedBy(auth()->user())->performedOn($berita)
            ->log('Menambahkan berita: '.$berita->judul);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil ditambahkan!');
    }

    public function edit(Berita $beritum)
    {
        $berita = $beritum;
        $kategoris = Kategori::orderBy('nama')->get();
        $selectedKategoris = $berita->kategoris->pluck('id')->toArray();

        return view('admin.berita.edit', compact('berita', 'kategoris', 'selectedKategoris'));
    }

    public function update(BeritaRequest $request, Berita $beritum)
    {
        $berita = $beritum;
        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
                Storage::disk('public')->delete($berita->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        } elseif ($request->boolean('hapus_gambar')) {
            if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
                Storage::disk('public')->delete($berita->gambar);
            }
            $data['gambar'] = null;
        }

        $berita->update($data);

        // Sync kategori
        $berita->kategoris()->sync($request->kategori_ids ?? []);

        activity()->causedBy(auth()->user())->performedOn($berita)
            ->log('Mengubah berita: '.$berita->judul);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil diperbarui!');
    }

    public function destroy(Berita $beritum)
    {
        $berita = $beritum;
        $judul = $berita->judul;

        // SoftDelete — gambar tetap disimpan
        $berita->delete();

        activity()->causedBy(auth()->user())->log('Menghapus berita: '.$judul);

        return response()->json(['success' => true, 'message' => 'Berita berhasil dihapus!']);
    }
}
