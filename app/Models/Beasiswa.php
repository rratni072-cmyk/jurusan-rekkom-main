<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Beasiswa — info beasiswa yang tampil di halaman publik
 * {@see \App\Http\Controllers\Frontend\KemahasiswaanController::beasiswa()}.
 *
 * Field `url_info` opsional: bila diisi, card frontend render tombol
 * "Kunjungi Website Resmi" yang membuka tab baru. Jika null, fallback
 * ke tombol "Info di Bagian Akademik" yang mengarah ke halaman kontak.
 */
class Beasiswa extends Model
{
    use \App\Traits\LogsAdminActivity;

    protected string $logLabel = 'Beasiswa';

    /** @var array<int, string> */
    protected array $logAttributes = ['nama', 'penyelenggara', 'is_active'];

    /** @var list<string> */
    protected $fillable = [
        'nama',
        'penyelenggara',
        'deskripsi',
        'url_info',
        'is_active',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * URL info beasiswa dengan fallback protocol.
     *
     * Admin kadang input URL tanpa `https://` (mis. "kip-kuliah.kemdikbud.go.id").
     * Accessor ini normalize supaya link tetap berfungsi di browser.
     */
    public function getUrlInfoLengkapAttribute(): ?string
    {
        if (empty($this->url_info)) {
            return null;
        }

        $url = trim($this->url_info);

        // Sudah punya protocol (http/https/mailto/tel) — return as-is.
        if (preg_match('#^(https?|mailto|tel):#i', $url)) {
            return $url;
        }

        // Tambah https:// sebagai default.
        return 'https://'.$url;
    }
}
