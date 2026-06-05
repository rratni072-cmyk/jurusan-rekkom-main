<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Pedoman — dokumen panduan akademik (PDF / Excel) yang bisa di-unduh mahasiswa.
 *
 * Dokumen dikelompokkan per {@see self::KATEGORI} untuk memudahkan navigasi di
 * frontend (Akademik / Tugas Akhir / Wisuda). Format file di-derive otomatis
 * dari extension saat upload (lihat {@see self::resolveFormatFromPath()}).
 */
class Pedoman extends Model
{
    use \App\Traits\LogsAdminActivity;

    /** @use HasFactory<\Database\Factories\PedomanFactory> */
    use HasFactory;

    protected string $logLabel = 'Pedoman';

    /** @var array<int, string> */
    protected array $logAttributes = ['nama_file', 'kategori', 'is_active'];

    protected function logIdentifier(): string
    {
        return (string) ($this->getAttribute('nama_file') ?? '#'.$this->getKey());
    }

    // -------------------------------------------------------------------------
    // Constants — kategori & format whitelist.
    // -------------------------------------------------------------------------

    /** @var string Pedoman akademik umum (penyelenggaraan pendidikan, peraturan). */
    public const KATEGORI_AKADEMIK = 'akademik';

    /** @var string Pedoman tugas akhir, skripsi, magang. */
    public const KATEGORI_TUGAS_AKHIR = 'tugas-akhir';

    /** @var string Pedoman terkait kelulusan: SKPI, ijazah, transkrip. */
    public const KATEGORI_WISUDA = 'wisuda';

    /**
     * Mapping kategori → label manusia + icon Bootstrap Icons.
     * Dipakai frontend & admin UI untuk konsistensi tampilan.
     *
     * @var array<string, array{label: string, icon: string, color: string}>
     */
    public const KATEGORI = [
        self::KATEGORI_AKADEMIK => [
            'label' => 'Akademik',
            'icon' => 'bi-journal-bookmark',
            'color' => 'primary',
        ],
        self::KATEGORI_TUGAS_AKHIR => [
            'label' => 'Tugas Akhir & Magang',
            'icon' => 'bi-journal-text',
            'color' => 'success',
        ],
        self::KATEGORI_WISUDA => [
            'label' => 'Kelulusan & Wisuda',
            'icon' => 'bi-mortarboard',
            'color' => 'warning',
        ],
    ];

    /**
     * Format file yang didukung → icon + warna Bootstrap.
     *
     * @var array<string, array{icon: string, color: string, text: string}>
     */
    public const FORMAT_MAP = [
        'PDF' => ['icon' => 'bi-file-earmark-pdf-fill',   'color' => 'danger',  'text' => 'PDF'],
        'XLSX' => ['icon' => 'bi-file-earmark-excel-fill', 'color' => 'success', 'text' => 'Excel'],
        'XLS' => ['icon' => 'bi-file-earmark-excel-fill', 'color' => 'success', 'text' => 'Excel'],
        'DOCX' => ['icon' => 'bi-file-earmark-word-fill',  'color' => 'primary', 'text' => 'Word'],
        'DOC' => ['icon' => 'bi-file-earmark-word-fill',  'color' => 'primary', 'text' => 'Word'],
    ];

    /**
     * Laravel default-nya bikin plural "pedomans" → "pedomen" (salah).
     * Set manual untuk pastikan tetap "pedomans".
     */
    protected $table = 'pedomans';

    /** @var list<string> */
    protected $fillable = [
        'nama_file',
        'kategori',
        'deskripsi',
        'format_file',
        'file_path',
        'urutan',
        'is_active',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Scopes.
    // -------------------------------------------------------------------------

    /**
     * Hanya pedoman aktif (tampil di halaman publik).
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Filter berdasarkan kategori tertentu.
     */
    public function scopeKategori(Builder $query, string $kategori): Builder
    {
        return $query->where('kategori', $kategori);
    }

    // -------------------------------------------------------------------------
    // Accessors — presentation helpers (dipakai di blade).
    // -------------------------------------------------------------------------

    /**
     * URL publik untuk unduh/preview file.
     * Mengembalikan null kalau path belum di-set supaya blade bisa fallback.
     */
    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? asset('storage/'.$this->file_path) : null;
    }

    /**
     * Ukuran file dalam format manusia (contoh: "2.46 MB").
     * Mengembalikan null kalau file tidak ditemukan di disk.
     */
    public function getFileSizeHumanAttribute(): ?string
    {
        if (! $this->file_path) {
            return null;
        }

        $disk = Storage::disk('public');
        if (! $disk->exists($this->file_path)) {
            return null;
        }

        $bytes = $disk->size($this->file_path);

        return self::formatBytes($bytes);
    }

    /**
     * Label kategori manusia (contoh: "Tugas Akhir & Magang").
     */
    public function getKategoriLabelAttribute(): string
    {
        return self::KATEGORI[$this->kategori]['label'] ?? ucfirst((string) $this->kategori);
    }

    /**
     * Icon Bootstrap Icons untuk kategori.
     */
    public function getKategoriIconAttribute(): string
    {
        return self::KATEGORI[$this->kategori]['icon'] ?? 'bi-file-earmark';
    }

    /**
     * Warna Bootstrap (primary/success/warning) untuk badge kategori.
     */
    public function getKategoriColorAttribute(): string
    {
        return self::KATEGORI[$this->kategori]['color'] ?? 'secondary';
    }

    /**
     * Icon Bootstrap Icons untuk format file (PDF/Excel).
     */
    public function getFormatIconAttribute(): string
    {
        $key = strtoupper((string) $this->format_file);

        return self::FORMAT_MAP[$key]['icon'] ?? 'bi-file-earmark';
    }

    /**
     * Warna Bootstrap untuk badge format file (danger=PDF, success=Excel).
     */
    public function getFormatColorAttribute(): string
    {
        $key = strtoupper((string) $this->format_file);

        return self::FORMAT_MAP[$key]['color'] ?? 'secondary';
    }

    /**
     * Label format yang user-friendly ("PDF" / "Excel").
     */
    public function getFormatLabelAttribute(): string
    {
        $key = strtoupper((string) $this->format_file);

        return self::FORMAT_MAP[$key]['text'] ?? ($this->format_file ?: 'File');
    }

    // -------------------------------------------------------------------------
    // Helpers.
    // -------------------------------------------------------------------------

    /**
     * Ambil format file (uppercase) dari path/filename.
     * Contoh: "pedoman/Skripsi-D4.pdf" → "PDF".
     */
    public static function resolveFormatFromPath(string $path): string
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        return $ext !== '' ? strtoupper($ext) : 'FILE';
    }

    /**
     * Format bytes → human readable (KB / MB / GB).
     * Dipisah sebagai static supaya bisa dipakai di seeder / helper lain.
     */
    public static function formatBytes(int $bytes, int $precision = 2): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = (int) floor(log($bytes, 1024));
        $power = min($power, count($units) - 1);

        return round($bytes / (1024 ** $power), $precision).' '.$units[$power];
    }

    /**
     * Daftar semua kategori untuk dropdown admin.
     *
     * @return array<string, string> [kategori_slug => label]
     */
    public static function kategoriOptions(): array
    {
        $opts = [];
        foreach (self::KATEGORI as $slug => $meta) {
            $opts[$slug] = $meta['label'];
        }

        return $opts;
    }
}
