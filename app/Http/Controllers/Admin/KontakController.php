<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\KontakRequest;
use App\Models\Kontak;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Controller Kontak Jurusan.
 *
 * Mengelola informasi kontak jurusan (alamat, email, telepon, sosmed).
 * Single record — hanya ada 1 baris di tabel kontaks.
 */
class KontakController extends Controller
{
    /**
     * Tampilkan form edit kontak.
     */
    public function edit(): View
    {
        $kontak = Kontak::first() ?? new Kontak;

        return view('admin.kontak.edit', compact('kontak'));
    }

    /**
     * Simpan/update kontak jurusan.
     * Validasi via {@see KontakRequest} (sesuai .agents/rules/keamanan.md).
     */
    public function update(KontakRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $kontak = Kontak::first();

        if ($kontak) {
            $kontak->update($validated);
        } else {
            $kontak = Kontak::create($validated);
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($kontak)
            ->log('Memperbarui informasi kontak jurusan');

        return redirect()
            ->route('admin.kontak.edit')
            ->with('success', 'Informasi kontak berhasil diperbarui.');
    }
}
