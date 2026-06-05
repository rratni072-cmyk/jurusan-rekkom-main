<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Jadwal Perkuliahan.
 *
 * Merepresentasikan file PDF jadwal kuliah per program studi per semester.
 *
 * @property int $id
 * @property int|null $program_studi_id FK ke program_studis (nullable, backward compat)
 * @property string $program_studi Nama prodi (display string, fallback jika FK null)
 * @property string $tahun_ajaran Format "2024/2025"
 * @property string $semester 'Ganjil' atau 'Genap'
 * @property string $file_path Path file di storage
 * @property bool $is_active Status aktif (hanya yang aktif tampil di frontend)
 */
class Jadwal extends Model
{
    use \App\Traits\LogsAdminActivity;

    protected string $logLabel = 'Jadwal Perkuliahan';

    /** @var array<int, string> */
    protected array $logAttributes = ['program_studi_id', 'tahun_ajaran', 'semester', 'is_active'];

    protected function logIdentifier(): string
    {
        return trim(sprintf(
            '%s - %s %s',
            $this->getAttribute('program_studi') ?: ($this->programStudi?->nama ?? '-'),
            $this->getAttribute('semester') ?? '',
            $this->getAttribute('tahun_ajaran') ?? ''
        ));
    }

    /** Konstanta semester untuk konsistensi pemakaian. */
    public const SEMESTER_GANJIL = 'Ganjil';

    public const SEMESTER_GENAP = 'Genap';

    protected $fillable = [
        'program_studi_id',
        'program_studi',
        'tahun_ajaran',
        'semester',
        'file_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke ProgramStudi (untuk dropdown admin & display konsisten).
     * Nullable — data lama mungkin hanya punya string `program_studi`.
     */
    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_id');
    }

    /**
     * Scope: hanya jadwal aktif (untuk frontend).
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Filter scope berdasarkan tahun ajaran & semester (chainable).
     *
     * @param  string|null  $tahunAjaran  Format "2024/2025", null = semua
     * @param  string|null  $semester  'Ganjil'/'Genap', null = semua
     */
    public function scopeFilter(Builder $query, ?string $tahunAjaran = null, ?string $semester = null): Builder
    {
        return $query
            ->when($tahunAjaran, fn ($q) => $q->where('tahun_ajaran', $tahunAjaran))
            ->when($semester, fn ($q) => $q->where('semester', $semester));
    }

    /**
     * Display name prodi: prioritaskan FK, fallback ke string lama.
     */
    public function getNamaProdiAttribute(): string
    {
        if ($this->programStudi) {
            return $this->programStudi->jenjang.' - '.$this->programStudi->nama;
        }

        return $this->program_studi ?? '-';
    }
}
