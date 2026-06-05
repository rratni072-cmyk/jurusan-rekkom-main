<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SliderRequest;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class SliderController extends Controller
{
    /**
     * Halaman index — DataTables server-side.
     */
    public function index()
    {
        return view('admin.slider.index');
    }

    /**
     * API endpoint untuk DataTables AJAX.
     */
    public function datatable()
    {
        $query = Slider::query()->select('sliders.*');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('gambar_preview', function ($slider) {
                $url = $slider->gambar
                    ? asset('storage/'.$slider->gambar)
                    : asset('admin/img/avatars/placeholder.jpg');

                return '<img src="'.$url.'" alt="'.$slider->judul.'" height="60" class="rounded"/>';
            })
            ->addColumn('status', function ($slider) {
                return $slider->is_active
                    ? '<span class="badge bg-success">Aktif</span>'
                    : '<span class="badge bg-danger">Nonaktif</span>';
            })
            ->addColumn('aksi', function ($slider) {
                return view('admin.slider._aksi', compact('slider'))->render();
            })
            ->rawColumns(['gambar_preview', 'status', 'aksi'])
            ->toJson();
    }

    /**
     * Form tambah slider baru.
     */
    public function create()
    {
        return view('admin.slider.create');
    }

    /**
     * Simpan slider baru ke database.
     */
    public function store(SliderRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('sliders', 'public');
        }

        Slider::create($data);

        activity()
            ->causedBy(auth()->user())
            ->log('Menambahkan slider: '.$data['judul']);

        return redirect()
            ->route('admin.slider.index')
            ->with('success', 'Slider berhasil ditambahkan!');
    }

    /**
     * Form edit slider.
     */
    public function edit(Slider $slider)
    {
        return view('admin.slider.edit', compact('slider'));
    }

    /**
     * Update slider di database.
     */
    public function update(SliderRequest $request, Slider $slider)
    {
        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($slider->gambar && Storage::disk('public')->exists($slider->gambar)) {
                Storage::disk('public')->delete($slider->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('sliders', 'public');
        } elseif ($request->boolean('hapus_gambar')) {
            if ($slider->gambar && Storage::disk('public')->exists($slider->gambar)) {
                Storage::disk('public')->delete($slider->gambar);
            }
            $data['gambar'] = null;
        }

        $slider->update($data);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($slider)
            ->log('Mengubah slider: '.$slider->judul);

        return redirect()
            ->route('admin.slider.index')
            ->with('success', 'Slider berhasil diperbarui!');
    }

    /**
     * Hapus slider dari database.
     */
    public function destroy(Slider $slider)
    {
        // Hapus gambar
        if ($slider->gambar && Storage::disk('public')->exists($slider->gambar)) {
            Storage::disk('public')->delete($slider->gambar);
        }

        $judul = $slider->judul;
        $slider->delete();

        activity()
            ->causedBy(auth()->user())
            ->log('Menghapus slider: '.$judul);

        return response()->json(['success' => true, 'message' => 'Slider berhasil dihapus!']);
    }
}
