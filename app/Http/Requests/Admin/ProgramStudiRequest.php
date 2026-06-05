<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProgramStudiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->has('is_active'),
            'hapus_sertifikat' => $this->boolean('hapus_sertifikat'),
        ]);
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'jenjang' => 'required|string|max:10',
            'akreditasi' => 'nullable|string|max:50',
            'no_sk' => 'nullable|string|max:255',
            'tahun_sk' => 'nullable|integer|min:1900|max:2100',
            'tanggal_kedaluwarsa' => 'nullable|date',
            'sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'verifikasi_url' => 'nullable|url|max:500',
            'verifikasi_label' => 'nullable|string|max:50',
            'hapus_sertifikat' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama program studi wajib diisi.',
            'jenjang.required' => 'Jenjang wajib diisi (D3/D4/S1).',
            'sertifikat.mimes' => 'Sertifikat harus berformat PDF, JPG, atau PNG.',
            'sertifikat.max' => 'Ukuran sertifikat maksimal 5MB.',
            'verifikasi_url.url' => 'URL verifikasi harus berupa link valid (mis. https://pddikti.kemdiktisaintek.go.id/...).',
            'verifikasi_url.max' => 'URL verifikasi terlalu panjang (maks 500 karakter).',
            'verifikasi_label.max' => 'Label verifikasi maksimal 50 karakter.',
        ];
    }
}
