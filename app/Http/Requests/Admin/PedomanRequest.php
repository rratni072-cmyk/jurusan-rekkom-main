<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Pedoman;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validasi form Tambah/Edit Pedoman.
 *
 * Mendukung PDF, Excel (xls/xlsx), Word (doc/docx). Format file di-isi otomatis
 * di controller dari extension file yang di-upload, jadi tidak perlu input
 * manual dari admin.
 */
class PedomanRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Route group sudah ada middleware 'auth'. Level authz cukup di situ.
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'nama_file' => 'required|string|max:255',
            'kategori' => ['required', 'string', Rule::in(array_keys(Pedoman::KATEGORI))],
            'deskripsi' => 'nullable|string|max:500',
            'urutan' => 'nullable|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
        ];

        // Allowed: pdf, xls, xlsx, doc, docx — max 10 MB per file.
        $fileRule = 'file|mimes:pdf,xls,xlsx,doc,docx|max:10240';

        $rules['file_path'] = $this->isMethod('post')
            ? 'required|'.$fileRule
            : 'nullable|'.$fileRule;

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_file.required' => 'Nama file wajib diisi.',
            'nama_file.max' => 'Nama file maksimal 255 karakter.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.in' => 'Kategori yang dipilih tidak valid.',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter.',
            'urutan.integer' => 'Urutan harus berupa angka.',
            'urutan.min' => 'Urutan tidak boleh negatif.',
            'file_path.required' => 'File pedoman wajib diunggah.',
            'file_path.file' => 'File yang diunggah tidak valid.',
            'file_path.mimes' => 'Format file harus PDF, Excel, atau Word.',
            'file_path.max' => 'Ukuran file maksimal 10 MB.',
        ];
    }

    /**
     * Normalisasi input sebelum validasi:
     * - Pastikan `is_active` jadi boolean (hidden + checkbox pattern).
     * - Default `urutan` = 0 kalau kosong.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'urutan' => $this->filled('urutan') ? (int) $this->input('urutan') : 0,
        ]);
    }
}
