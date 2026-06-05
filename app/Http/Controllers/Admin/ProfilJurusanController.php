<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfilJurusanRequest;
use App\Models\ProfilJurusan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Controller Profil Jurusan.
 *
 * Mengelola 3 section profil (tentang, visi-misi, struktur-organisasi)
 * via 2 method generic. Section dipass via route default parameter.
 */
class ProfilJurusanController extends Controller
{
    /**
     * Mapping URL slug → kunci DB + label tampilan + view.
     *
     * @var array<string, array{key: string, label: string, view: string}>
     */
    private const SECTION_MAP = [
        'tentang-jurusan' => ['key' => 'tentang_jurusan', 'label' => 'Tentang Jurusan', 'view' => 'admin.profil.tentang'],
        'visi-misi' => ['key' => 'visi_misi', 'label' => 'Visi & Misi', 'view' => 'admin.profil.visi-misi'],
        'struktur-organisasi' => ['key' => 'struktur_organisasi', 'label' => 'Struktur Organisasi', 'view' => 'admin.profil.struktur'],
    ];

    /**
     * Halaman edit untuk section tertentu.
     */
    public function edit(string $section): View
    {
        $config = $this->config($section);
        $profil = ProfilJurusan::firstOrNew(
            ['kunci' => $config['key']],
            ['nilai' => '', 'gambar' => null],
        );

        return view($config['view'], compact('profil'));
    }

    /**
     * Simpan perubahan untuk section tertentu.
     */
    public function update(ProfilJurusanRequest $request, string $section): RedirectResponse
    {
        $config = $this->config($section);
        $kunci = $config['key'];
        $label = $config['label'];

        $data = $request->input("profil.{$kunci}", []);

        $profil = ProfilJurusan::firstOrNew(['kunci' => $kunci]);
        $profil->nilai = $data['nilai'] ?? '';

        // Judul heading WAJIB (validasi sudah menolak empty). Trim whitespace untuk hygiene.
        $profil->judul = trim((string) ($data['judul'] ?? ''));

        // Penanganan gambar (urutan prioritas):
        // 1. Upload file baru → replace gambar lama (upload menang kalau admin
        //    juga centang hapus — lebih aman, admin jelas mau gambar baru).
        // 2. Flag hapus_gambar=true → hapus file lama, set kolom jadi null.
        // 3. Tidak ada keduanya → gambar lama dibiarkan.
        if ($request->hasFile("profil.{$kunci}.gambar")) {
            if ($profil->gambar && Storage::disk('public')->exists($profil->gambar)) {
                Storage::disk('public')->delete($profil->gambar);
            }
            $profil->gambar = $request->file("profil.{$kunci}.gambar")
                ->store('profil-jurusan', 'public');
        } elseif ($request->boolean("profil.{$kunci}.hapus_gambar")) {
            if ($profil->gambar && Storage::disk('public')->exists($profil->gambar)) {
                Storage::disk('public')->delete($profil->gambar);
            }
            $profil->gambar = null;
        }

        $profil->save();

        activity()
            ->causedBy(auth()->user())
            ->performedOn($profil)
            ->log("Memperbarui {$label}");

        return redirect()
            ->route("admin.profil.{$this->routeNameFor($section)}.edit")
            ->with('success', "{$label} berhasil diperbarui.");
    }

    /**
     * Resolusi config section atau abort 404.
     *
     * @return array{key: string, label: string, view: string}
     */
    private function config(string $section): array
    {
        return self::SECTION_MAP[$section] ?? abort(404, 'Section profil tidak dikenal');
    }

    /**
     * URL slug → suffix nama route (tentang-jurusan → tentang).
     */
    private function routeNameFor(string $section): string
    {
        return match ($section) {
            'tentang-jurusan' => 'tentang',
            'visi-misi' => 'visi-misi',
            'struktur-organisasi' => 'struktur',
            default => abort(404),
        };
    }
}
