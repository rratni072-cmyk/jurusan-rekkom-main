<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class KegiatanRequest extends FormRequest
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
        return [
            'judul' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'ringkasan' => 'nullable|string',
            'konten' => 'required|string',
            'tanggal' => 'required|date',
            'tipe_kegiatan_id' => 'required|integer|exists:tipe_kegiatans,id',
            'is_published' => 'boolean',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul kegiatan wajib diisi.',
            'konten.required' => 'Konten kegiatan wajib diisi.',
            'tanggal.required' => 'Tanggal kegiatan wajib diisi.',
            'tanggal.date' => 'Tanggal harus berformat tanggal valid.',
            'tipe_kegiatan_id.required' => 'Tipe kegiatan wajib dipilih.',
            'tipe_kegiatan_id.exists' => 'Tipe kegiatan yang dipilih tidak valid.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Gambar harus berformat JPG, JPEG, PNG, atau WebP.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
