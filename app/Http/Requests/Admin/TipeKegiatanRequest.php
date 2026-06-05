<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TipeKegiatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalisasi input sebelum validasi:
     * - Auto-slug dari label bila slug kosong.
     * - Checkbox is_active → boolean eksplisit.
     */
    protected function prepareForValidation(): void
    {
        $slug = $this->input('slug');
        $label = $this->input('label');

        if (empty($slug) && ! empty($label)) {
            $slug = Str::slug($label, '_');
        }

        $this->merge([
            'slug' => $slug ? Str::slug((string) $slug, '_') : $slug,
            'is_active' => $this->has('is_active'),
        ]);
    }

    public function rules(): array
    {
        $tipeId = $this->route('tipe_kegiatan')?->id;

        return [
            'slug' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique('tipe_kegiatans', 'slug')->ignore($tipeId),
            ],
            'label' => ['required', 'string', 'max:100'],
            'icon' => ['required', 'string', 'max:50', 'regex:/^bi-[a-z0-9-]+$/'],
            'urutan' => ['required', 'integer', 'min:0', 'max:65535'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.required' => 'Slug wajib diisi (akan di-auto-generate dari label kalau kosong).',
            'slug.regex' => 'Slug hanya boleh huruf kecil, angka, dan underscore (contoh: workshop_mahasiswa).',
            'slug.unique' => 'Slug ini sudah dipakai tipe kegiatan lain.',
            'label.required' => 'Label/nama tipe wajib diisi.',
            'icon.required' => 'Ikon wajib diisi.',
            'icon.regex' => 'Format ikon tidak valid. Gunakan kelas Bootstrap Icon, contoh: bi-trophy, bi-mic, bi-tools.',
            'urutan.required' => 'Urutan wajib diisi (0 = paling atas).',
            'urutan.integer' => 'Urutan harus berupa angka.',
        ];
    }
}
