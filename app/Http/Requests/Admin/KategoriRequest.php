<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Kategori;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KategoriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'tipe' => ['required', Rule::in([Kategori::TIPE_EDITORIAL, Kategori::TIPE_TOPIK])],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama kategori wajib diisi.',
            'tipe.required' => 'Tipe kategori wajib dipilih.',
            'tipe.in' => 'Tipe kategori tidak valid.',
        ];
    }

    /**
     * Default tipe ke editorial bila form lama belum punya field tipe.
     */
    protected function prepareForValidation(): void
    {
        if (! $this->filled('tipe')) {
            $this->merge(['tipe' => Kategori::TIPE_EDITORIAL]);
        }
    }
}
