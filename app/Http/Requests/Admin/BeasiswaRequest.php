<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BeasiswaRequest extends FormRequest
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

        // Normalize URL: trim spasi + tambah https:// kalau user lupa protocol.
        // Validator `url` butuh protocol eksplisit.
        $url = trim((string) $this->input('url_info', ''));
        if ($url !== '' && ! preg_match('#^(https?|mailto|tel):#i', $url)) {
            $url = 'https://'.$url;
        }
        $this->merge(['url_info' => $url !== '' ? $url : null]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'penyelenggara' => 'nullable|string|max:150',
            'deskripsi' => 'required|string',
            'url_info' => 'nullable|url|max:500',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Pesan error Bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama beasiswa wajib diisi.',
            'nama.max' => 'Nama beasiswa maksimal 255 karakter.',
            'penyelenggara.max' => 'Nama penyelenggara maksimal 150 karakter.',
            'deskripsi.required' => 'Deskripsi beasiswa wajib diisi.',
            'url_info.url' => 'Tautan info harus berupa URL yang valid (contoh: https://kip-kuliah.kemdikbud.go.id).',
            'url_info.max' => 'Tautan info maksimal 500 karakter.',
        ];
    }
}
