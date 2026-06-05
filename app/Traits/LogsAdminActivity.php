<?php

declare(strict_types=1);

namespace App\Traits;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Wrapper Spatie LogsActivity dengan konvensi proyek Jurusan R&K:
 *
 * - Deskripsi event otomatis dalam Bahasa Indonesia
 *   ("Berita ditambahkan: Pendaftaran Mahasiswa Baru")
 * - Logged attributes dideklarasikan via property `$logAttributes`
 * - Label manusiawi via property `$logLabel` (fallback: class basename)
 * - Hanya log attribute yang berubah (`logOnlyDirty`)
 * - Tidak submit log kosong (`dontSubmitEmptyLogs`)
 *
 * Dipakai oleh model yang di-CRUD dari admin panel sesuai aturan proyek:
 * Activity Log wajib untuk setiap mutasi admin (create/update/delete).
 * Lihat: `.agents/rules/activity-log.md` & `.agents/rules/kualitas-kode.md`.
 *
 * Contoh penggunaan di model:
 * ```
 * use App\Traits\LogsAdminActivity;
 *
 * class Berita extends Model {
 *     use LogsAdminActivity;
 *
 *     protected array $logAttributes = ['judul', 'is_published'];
 *     protected string $logLabel = 'Berita';
 * }
 * ```
 *
 * Identifier record otomatis dipilih dari field pertama yang terisi di antara:
 * `judul`, `nama`, `kunci`, `name` — atau fallback `#<id>` jika tidak ada.
 * Override `logIdentifier()` di model kalau butuh format spesifik.
 */
trait LogsAdminActivity
{
    use LogsActivity;

    /**
     * Konfigurasi activity log standar Bahasa Indonesia.
     */
    public function getActivitylogOptions(): LogOptions
    {
        $attributes = property_exists($this, 'logAttributes') ? $this->logAttributes : [];
        $label = property_exists($this, 'logLabel') ? $this->logLabel : class_basename($this);

        return LogOptions::defaults()
            ->logOnly($attributes)
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $event): string => sprintf(
                '%s %s: %s',
                $label,
                match ($event) {
                    'created' => 'ditambahkan',
                    'updated' => 'diperbarui',
                    'deleted' => 'dihapus',
                    default => $event,
                },
                $this->logIdentifier()
            ));
    }

    /**
     * Identifier manusiawi untuk record (fallback urutan: judul → nama → kunci → name → #id).
     * Model boleh override untuk format custom.
     */
    protected function logIdentifier(): string
    {
        foreach (['judul', 'nama', 'kunci', 'name'] as $field) {
            $value = $this->getAttribute($field);
            if (! empty($value)) {
                return (string) $value;
            }
        }

        return '#'.$this->getKey();
    }
}
