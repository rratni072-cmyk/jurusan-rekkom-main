<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Kategori extends Model
{
    use \App\Traits\LogsAdminActivity;

    protected string $logLabel = 'Kategori Berita';

    /** @var array<int, string> */
    protected array $logAttributes = ['nama', 'tipe'];

    /**
     * Tipe kategori: editorial (tampil di sidebar /berita) atau topik
     * (sudah punya halaman/menu sendiri, tidak ditampilkan di sidebar
     * widget). Lihat migration `add_tipe_to_kategoris_table` untuk konteks.
     */
    public const TIPE_EDITORIAL = 'editorial';

    public const TIPE_TOPIK = 'topik';

    public const TIPE_LABELS = [
        self::TIPE_EDITORIAL => 'Editorial',
        self::TIPE_TOPIK => 'Topik (Tridharma/Menu Lain)',
    ];

    protected $fillable = [
        'nama',
        'slug',
        'tipe',
    ];

    protected $attributes = [
        'tipe' => self::TIPE_EDITORIAL,
    ];

    /**
     * Auto-generate slug dari nama saat create/update.
     */
    protected static function booted(): void
    {
        static::creating(function (Kategori $kategori) {
            if (empty($kategori->slug)) {
                $kategori->slug = Str::slug($kategori->nama);
            }
        });

        static::updating(function (Kategori $kategori) {
            if ($kategori->isDirty('nama')) {
                $kategori->slug = Str::slug($kategori->nama);
            }
        });
    }

    /**
     * Scope: hanya kategori editorial — tampil di sidebar widget /berita.
     */
    public function scopeEditorial(Builder $query): Builder
    {
        return $query->where('tipe', self::TIPE_EDITORIAL);
    }

    /**
     * Helper: apakah kategori ini bertipe editorial.
     */
    public function isEditorial(): bool
    {
        return $this->tipe === self::TIPE_EDITORIAL;
    }

    /**
     * Relasi many-to-many ke Berita.
     */
    public function beritas(): BelongsToMany
    {
        return $this->belongsToMany(Berita::class, 'berita_kategori');
    }

    /**
     * Kelas warna badge berdasarkan nama kategori.
     *
     * Mapping deterministik (case-insensitive); kategori di luar map
     * akan memakai variasi netral. Konvensi mengikuti portal berita
     * kampus modern: tiap kategori punya warna distinct untuk
     * membantu scan-ability.
     */
    public function getColorClassAttribute(): string
    {
        $key = Str::slug((string) $this->nama);

        return match (true) {
            str_contains($key, 'akademik') => 'news-category--akademik',
            str_contains($key, 'kegiatan'), str_contains($key, 'event') => 'news-category--kegiatan',
            str_contains($key, 'prestasi'), str_contains($key, 'penghargaan') => 'news-category--prestasi',
            str_contains($key, 'pengumuman'), str_contains($key, 'penting') => 'news-category--pengumuman',
            str_contains($key, 'kerjasama'), str_contains($key, 'mitra') => 'news-category--kerjasama',
            str_contains($key, 'penelitian'), str_contains($key, 'riset') => 'news-category--penelitian',
            default => 'news-category--umum',
        };
    }
}
