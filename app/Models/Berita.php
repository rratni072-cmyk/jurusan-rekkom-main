<?php

namespace App\Models;

use App\Models\Concerns\HasViewCounter;
use App\Support\HtmlSanitizer;
use App\Traits\LogsAdminActivity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Model Berita.
 *
 * Merepresentasikan berita/artikel jurusan.
 * Memiliki relasi ke User (penulis) dan menggunakan SoftDeletes.
 *
 * @property int $id
 * @property string $judul Judul berita
 * @property string $slug URL-friendly judul
 * @property string $ringkasan Ringkasan singkat
 * @property string $konten Konten lengkap (HTML)
 * @property string $gambar Path gambar utama
 * @property int $penulis_id FK ke users
 * @property \DateTime $tanggal_publikasi Tanggal terbit
 * @property bool $is_published Status publikasi
 */
class Berita extends Model
{
    use HasFactory;
    use HasViewCounter;
    use LogsAdminActivity;
    use SoftDeletes;

    protected string $logLabel = 'Berita';

    /** @var array<int, string> */
    protected array $logAttributes = ['judul', 'is_published', 'tridharma_type'];

    /**
     * Kolom yang boleh diisi secara mass-assignment.
     *
     * @var array<string>
     */
    protected $fillable = [
        'judul',
        'slug',
        'ringkasan',
        'konten',
        'gambar',
        'penulis_id',
        'program_studi_id',
        'tridharma_type',
        'lokasi',
        'dampak_singkat',
        'tanggal_publikasi',
        'is_published',
        'views',
    ];

    /**
     * Cast tipe data otomatis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_publikasi' => 'datetime',
        'is_published' => 'boolean',
        'views' => 'integer',
    ];

    /**
     * Auto-generate slug dari judul saat membuat berita baru.
     */
    protected static function booted(): void
    {
        static::creating(function (Berita $berita) {
            if (empty($berita->slug)) {
                $berita->slug = Str::slug($berita->judul);
            }
        });
    }

    /**
     * Relasi: berita ditulis oleh satu user (penulis).
     */
    public function penulis(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penulis_id');
    }

    /**
     * Relasi: berita terkait satu program studi (opsional).
     *
     * Nullable — NULL berarti artikel lintas jurusan / tidak terkait
     * prodi spesifik. Dipakai untuk filter Prodi di Tridharma.
     */
    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_id');
    }

    /**
     * Accessor: label prodi singkat untuk badge card.
     *
     * Mengambil singkatan dari nama prodi (huruf kapital pertama setiap kata)
     * dengan prefix jenjang. Contoh: "D4 TRPL", "D3 TG", "Lintas Jurusan".
     *
     * Single source of truth untuk label prodi di card Pengajaran/Pengabdian.
     */
    public function getProdiBadgeLabelAttribute(): string
    {
        if (! $this->program_studi_id || ! $this->relationLoaded('programStudi')) {
            $this->loadMissing('programStudi');
        }

        $prodi = $this->programStudi;

        if (! $prodi) {
            return 'Lintas Jurusan';
        }

        // Mapping nama → singkatan untuk display compact.
        $akronim = match (true) {
            str_contains(strtolower($prodi->nama), 'rekayasa perangkat lunak') => 'TRPL',
            str_contains(strtolower($prodi->nama), 'rekayasa geomatika') => 'TRGS',
            str_contains(strtolower($prodi->nama), 'sistem informasi akuntansi') => 'SIA',
            str_contains(strtolower($prodi->nama), 'teknologi geomatika') => 'TG',
            default => Str::upper(Str::limit(preg_replace('/[^A-Z]/', '', ucwords($prodi->nama)), 4, '')),
        };

        return trim($prodi->jenjang.' '.$akronim);
    }

    /**
     * Scope: hanya berita yang sudah dipublikasi.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where('tanggal_publikasi', '<=', now());
    }

    /**
     * Scope: berita biasa (bukan konten Tridharma).
     * Dipakai di Admin\BeritaController dan Frontend\BeritaController agar
     * konten Tridharma tidak ikut tampil di list/feed berita publik.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRegular($query)
    {
        return $query->whereNull('tridharma_type');
    }

    /**
     * Scope: filter konten Tridharma berdasarkan tipe.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type  'pengajaran' | 'pengabdian'
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTridharma($query, string $type)
    {
        return $query->where('tridharma_type', $type);
    }

    /**
     * Relasi many-to-many ke Kategori.
     */
    public function kategoris(): BelongsToMany
    {
        return $this->belongsToMany(Kategori::class, 'berita_kategori');
    }

    /**
     * Accessor untuk field `konten` (HTML berita dari TinyMCE).
     *
     * Pastikan setiap <img> punya `alt` attribute (a11y compliance) walaupun
     * admin lupa mengisi alt waktu insert image. Fallback ke `alt=""` (decorative).
     */
    protected function konten(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): ?string => HtmlSanitizer::ensureImgAlt($value),
        );
    }

    /**
     * Estimasi waktu baca dalam menit.
     * Asumsi rata-rata pembaca: 200 kata/menit.
     */
    public function getReadingTimeAttribute(): int
    {
        $words = str_word_count(strip_tags((string) $this->konten));

        return max(1, (int) ceil($words / 200));
    }
}
