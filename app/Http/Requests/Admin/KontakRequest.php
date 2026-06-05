<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request untuk update Kontak Jurusan (single record).
 */
class KontakRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'alamat' => 'nullable|string|max:500',
            'email' => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'koordinat' => 'nullable|string|max:100',
            'google_maps_embed' => 'nullable|url|max:2000',
            'tiktok' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.email' => 'Format email tidak valid.',
            'google_maps_embed.url' => 'Embed Google Maps harus berupa URL valid.',
            'tiktok.url' => 'URL TikTok tidak valid.',
            'facebook.url' => 'URL Facebook tidak valid.',
            'instagram.url' => 'URL Instagram tidak valid.',
            'youtube.url' => 'URL YouTube tidak valid.',
            'linkedin.url' => 'URL LinkedIn tidak valid.',
        ];
    }
}
