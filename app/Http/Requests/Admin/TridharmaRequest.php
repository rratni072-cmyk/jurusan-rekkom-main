<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi form Tridharma (Pengajaran & Pengabdian) di admin panel.
 *
 * Tipe ditentukan oleh segmen URL `/admin/tridharma/{type}/...` — bukan field input
 * (lebih aman, admin tidak bisa override type via form). Validasi `lokasi` &
 * `dampak_singkat` selalu nullable; field ini hanya muncul di form Pengabdian.
 */
class TridharmaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->has('is_published'),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'judul' => 'required|string|max:255',
            'ringkasan' => 'nullable|string|max:500',
            'konten' => 'required|string',
            'program_studi_id' => 'nullable|exists:program_studis,id',
            'tanggal_publikasi' => 'nullable|date',
            'is_published' => 'boolean',

            // Field khusus Pengabdian — opsional, hanya di-render di form Pengabdian.
            'lokasi' => 'nullable|string|max:150',
            'dampak_singkat' => 'nullable|string|max:100',
        ];

        // Gambar wajib saat create, opsional saat update.
        $rules['gambar'] = $this->isMethod('POST')
            ? 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
            : 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048';

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'judul.required' => 'Judul wajib diisi.',
            'judul.max' => 'Judul maksimal 255 karakter.',
            'konten.required' => 'Konten wajib diisi.',
            'gambar.required' => 'Gambar utama wajib diupload.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus JPG, PNG, atau WEBP.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
            'program_studi_id.exists' => 'Program studi yang dipilih tidak valid.',
            'lokasi.max' => 'Lokasi maksimal 150 karakter.',
            'dampak_singkat.max' => 'Dampak singkat maksimal 100 karakter.',
        ];
    }
}
