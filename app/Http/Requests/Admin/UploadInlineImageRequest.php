<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi upload gambar inline dari TinyMCE.
 *
 * Field 'file' adalah nama default yang dikirim TinyMCE pada
 * images_upload_url request (multipart/form-data).
 */
class UploadInlineImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Route sudah dilindungi middleware 'auth'.
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp,gif',
                'max:2048', // 2 MB
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'File gambar wajib disertakan.',
            'file.image' => 'File harus berupa gambar.',
            'file.mimes' => 'Format gambar harus jpg, jpeg, png, webp, atau gif.',
            'file.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
