<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request untuk update satu section Profil Jurusan.
 *
 * Kunci section dipass dari controller via `merge()` agar
 * rule `profil.{kunci}.*` tetap eksplisit dan dapat diuji.
 */
class ProfilJurusanRequest extends FormRequest
{
    /**
     * Mapping slug URL (route default `section`) → kunci kolom DB.
     *
     * Tetapkan di sini sebagai single source of truth — controller boleh
     * konsumsi via {@see self::keyFor()} agar tidak ada duplikasi mapping.
     *
     * @var array<string, string>
     */
    public const SLUG_TO_KEY = [
        'tentang-jurusan' => 'tentang_jurusan',
        'visi-misi' => 'visi_misi',
        'struktur-organisasi' => 'struktur_organisasi',
    ];

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Resolusi slug section → kunci DB. Kembalikan null kalau tidak valid.
     */
    public static function keyFor(string $slug): ?string
    {
        return self::SLUG_TO_KEY[$slug] ?? null;
    }

    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        $kunci = self::keyFor((string) $this->route('section', ''));

        if ($kunci === null) {
            // Slug tidak valid → reject semua data (defense-in-depth;
            // controller juga sudah abort(404) lewat SECTION_MAP-nya).
            return [
                '_invalid_section' => 'required',
            ];
        }

        return [
            "profil.{$kunci}.judul" => 'required|string|max:255',
            "profil.{$kunci}.nilai" => 'nullable|string',
            "profil.{$kunci}.gambar" => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            "profil.{$kunci}.hapus_gambar" => 'nullable|boolean',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        $kunci = self::keyFor((string) $this->route('section', '')) ?? '';

        return [
            "profil.{$kunci}.judul.required" => 'Judul halaman wajib diisi.',
            "profil.{$kunci}.judul.max" => 'Judul halaman maksimal 255 karakter.',
            "profil.{$kunci}.gambar.image" => 'File harus berupa gambar.',
            "profil.{$kunci}.gambar.mimes" => 'Format gambar harus JPG, PNG, atau WebP.',
            "profil.{$kunci}.gambar.max" => 'Ukuran gambar maksimal 5MB. File Anda terlalu besar — silakan kompres atau ubah ke JPG/WebP.',
            '_invalid_section.required' => 'Section tidak valid.',
        ];
    }

    /**
     * Slug section yang sedang divalidasi.
     */
    public function section(): string
    {
        return (string) $this->route('section', '');
    }

    /**
     * Kunci DB yang sedang divalidasi (atau null jika slug tidak valid).
     */
    public function sectionKey(): ?string
    {
        return self::keyFor($this->section());
    }
}
