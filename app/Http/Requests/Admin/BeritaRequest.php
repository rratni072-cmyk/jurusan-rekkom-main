<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BeritaRequest extends FormRequest
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

    public function rules(): array
    {
        $rules = [
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'ringkasan' => 'nullable|string|max:500',
            'kategori_ids' => 'nullable|array',
            'kategori_ids.*' => 'exists:kategoris,id',
            'program_studi_id' => 'nullable|exists:program_studis,id',
            'lokasi' => 'nullable|string|max:150',
            'dampak_singkat' => 'nullable|string|max:100',
            'is_published' => 'boolean',
            'tanggal_publikasi' => 'nullable|date',
        ];

        if ($this->isMethod('POST')) {
            $rules['gambar'] = 'required|image|mimes:jpg,jpeg,png,webp|max:2048';
        } else {
            $rules['gambar'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul berita wajib diisi.',
            'konten.required' => 'Konten berita wajib diisi.',
            'gambar.required' => 'Gambar utama wajib diupload.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
            'program_studi_id.exists' => 'Program studi yang dipilih tidak valid.',
        ];
    }
}
