<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\LogsAdminActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Master tipe kegiatan — referensi untuk klasifikasi/filter di halaman publik.
 *
 * Editable lewat /admin/tipe-kegiatan. Default 6 tipe (workshop, seminar, lomba,
 * kunjungan, hima, akademik) di-seed via migration agar data kegiatan existing
 * tidak ter-orphan.
 *
 * @property int $id
 * @property string $slug Identifier unik mesin (snake_case)
 * @property string $label Teks tampil di badge & dropdown
 * @property string $icon Bootstrap Icon class (mis. "bi-trophy")
 * @property int $urutan Urutan tampil (kecil = atas)
 * @property bool $is_active
 */
class TipeKegiatan extends Model
{
    use HasFactory;
    use LogsAdminActivity;

    protected string $logLabel = 'Tipe Kegiatan';

    /** @var array<int, string> */
    protected array $logAttributes = ['slug', 'label', 'icon', 'urutan', 'is_active'];

    protected $fillable = [
        'slug',
        'label',
        'icon',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'urutan' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke kegiatan yang menggunakan tipe ini.
     *
     * @return HasMany<Kegiatan>
     */
    public function kegiatans(): HasMany
    {
        return $this->hasMany(Kegiatan::class);
    }

    /**
     * Scope: hanya tipe yang aktif (untuk dropdown publik).
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: ordered untuk dropdown (urutan asc, fallback alfabet).
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('label');
    }
}
