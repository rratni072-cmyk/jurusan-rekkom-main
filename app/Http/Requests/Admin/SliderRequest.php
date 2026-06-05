<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->has('is_active'),
        ]);
    }

    public function rules(): array
    {
        $rules = [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
            'tombol_teks' => 'nullable|string|max:100',
            'tombol_url' => 'nullable|string|max:255',
            'urutan' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ];

        // Gambar wajib saat create, opsional saat update
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
            'judul.required' => 'Judul slider wajib diisi.',
            'gambar.required' => 'Gambar slider wajib diupload.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
            'urutan.required' => 'Urutan wajib diisi.',
        ];
    }
}
