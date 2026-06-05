<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasViewCounter;
use App\Traits\LogsAdminActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $judul
 * @property string $slug
 * @property string|null $ringkasan
 * @property string $konten
 * @property string|null $gambar
 * @property \Illuminate\Support\Carbon $tanggal
 * @property int $tipe_kegiatan_id FK ke tipe_kegiatans
 * @property bool $is_published
 * @property int $views
 * @property-read TipeKegiatan $tipeKegiatan
 */
class Kegiatan extends Model
{
    use HasFactory;
    use HasViewCounter;
    use LogsAdminActivity;

    protected string $logLabel = 'Kegiatan';

    /** @var array<int, string> */
    protected array $logAttributes = ['judul', 'tanggal', 'tipe_kegiatan_id', 'is_published'];

    protected $fillable = [
        'judul',
        'slug',
        'ringkasan',
        'konten',
        'gambar',
        'tanggal',
        'tipe_kegiatan_id',
        'is_published',
        'views',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'is_published' => 'boolean',
        'views' => 'integer',
        'tipe_kegiatan_id' => 'integer',
    ];

    /**
     * Auto-generate slug dari judul.
     */
    protected static function booted(): void
    {
        static::creating(function (Kegiatan $kegiatan) {
            if (empty($kegiatan->slug)) {
                $kegiatan->slug = Str::slug($kegiatan->judul);
            }
        });
    }

    /**
     * Relasi ke master tipe kegiatan.
     *
     * @return BelongsTo<TipeKegiatan, Kegiatan>
     */
    public function tipeKegiatan(): BelongsTo
    {
        return $this->belongsTo(TipeKegiatan::class);
    }

    /**
     * Accessor: badge tipe (icon + label) untuk tampilan card.
     *
     * Sumber data dari relasi `tipeKegiatan` (master DB). Pastikan eager-load
     * dengan `->with('tipeKegiatan')` sebelum loop card untuk menghindari N+1.
     * Fallback aman jika relasi gagal di-load (mis. data orphan).
     *
     * @return array{icon: string, label: string}
     */
    public function getTipeBadgeAttribute(): array
    {
        $tipe = $this->tipeKegiatan;

        if (! $tipe) {
            return ['icon' => 'bi-tag', 'label' => 'Lainnya'];
        }

        return ['icon' => $tipe->icon, 'label' => $tipe->label];
    }
}
