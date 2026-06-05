<?php

declare(strict_types=1);

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request untuk menyimpan pesan kontak dari pengunjung publik.
 *
 * Termasuk proteksi honeypot anti-bot: field `website_url` adalah
 * field trap yang disembunyikan via CSS — manusia tidak akan mengisi,
 * tapi bot otomatis sering mengisinya. Jika field tersebut terisi,
 * request dianggap spam dan harus ditolak diam-diam (lihat controller).
 */
class StoreKontakPesanRequest extends FormRequest
{
    /**
     * Form ini terbuka untuk publik (tidak butuh autentikasi).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subjek' => ['required', 'string', 'max:255'],
            'pesan' => ['required', 'string', 'max:5000'],

            // Honeypot trap: TIDAK divalidasi via Form Request. Tujuan: validation
            // tetap PASS meski bot mengisi field, agar logic bot detection di
            // controller (isLikelyBot()) yang menentukan response — diam-diam
            // tampil "success" tanpa simpan DB. Lihat KontakController@kirimPesan.
            'website_url' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Pesan error kustom (Bahasa Indonesia).
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'subjek.required' => 'Subjek wajib diisi.',
            'pesan.required' => 'Pesan wajib diisi.',
            'pesan.max' => 'Pesan terlalu panjang (maksimal 5000 karakter).',
        ];
    }

    /**
     * Cek apakah honeypot terisi (indikasi bot).
     */
    public function isLikelyBot(): bool
    {
        return $this->filled('website_url');
    }
}
