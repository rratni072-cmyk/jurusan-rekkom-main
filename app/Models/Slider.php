<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Slider.
 *
 * Merepresentasikan slider hero carousel di halaman beranda.
 *
 * @property int $id
 * @property string $judul Judul slider
 * @property string $deskripsi Deskripsi/subtitle
 * @property string $gambar Path gambar background
 * @property string $tombol_teks Teks tombol CTA
 * @property string $tombol_url URL tujuan tombol
 * @property int $urutan Urutan tampil
 * @property bool $is_active Status aktif
 */
class Slider extends Model
{
    use \App\Traits\LogsAdminActivity;

    protected string $logLabel = 'Slider Beranda';

    /** @var array<int, string> */
    protected array $logAttributes = ['judul', 'is_active', 'urutan'];

    /**
     * Kolom yang boleh diisi secara mass-assignment.
     *
     * @var array<string>
     */
    protected $fillable = [
        'judul',
        'deskripsi',
        'gambar',
        'tombol_teks',
        'tombol_url',
        'urutan',
        'is_active',
    ];

    /**
     * Cast tipe data otomatis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope: hanya slider yang aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: urutkan berdasarkan kolom urutan.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc');
    }
}
