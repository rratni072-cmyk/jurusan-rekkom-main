<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Model ProgramStudi.
 *
 * Merepresentasikan data program studi di jurusan.
 *
 * @property int $id
 * @property string $nama Nama prodi
 * @property string $jenjang Jenjang (D3/D4/S1)
 * @property string $akreditasi Nilai akreditasi
 * @property string $no_sk Nomor SK akreditasi
 * @property int $tahun_sk Tahun SK
 * @property \Illuminate\Support\Carbon|null $tanggal_kedaluwarsa Tanggal kedaluwarsa akreditasi (cast: date)
 * @property string $sertifikat Path file sertifikat
 * @property string|null $verifikasi_url URL verifikasi eksternal (PDDikti/BAN-PT)
 * @property string|null $verifikasi_label Label tombol verifikasi
 * @property bool $is_active Status aktif
 */
class ProgramStudi extends Model
{
    use \App\Traits\LogsAdminActivity;

    protected string $logLabel = 'Program Studi';

    /** @var array<int, string> */
    protected array $logAttributes = ['nama', 'jenjang', 'akreditasi', 'is_active'];

    protected function logIdentifier(): string
    {
        return trim(($this->getAttribute('jenjang') ?? '').' '.($this->getAttribute('nama') ?? '#'.$this->getKey()));
    }

    /**
     * Kolom yang boleh diisi secara mass-assignment.
     *
     * @var array<string>
     */
    protected $fillable = [
        'nama',
        'jenjang',
        'akreditasi',
        'no_sk',
        'tahun_sk',
        'tanggal_kedaluwarsa',
        'sertifikat',
        'verifikasi_url',
        'verifikasi_label',
        'is_active',
    ];

    /**
     * Cast tipe data otomatis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_kedaluwarsa' => 'date',
    ];

    /**
     * Scope: hanya prodi yang aktif.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Mapping nilai akreditasi ke kelas CSS badge.
     *
     * Single source of truth untuk seluruh tampilan akreditasi
     * (home card, halaman akreditasi, dll). Case-insensitive dan
     * mendukung format lama (A/B/C) maupun baru (Unggul/Baik Sekali/Baik).
     *
     * @return string Kelas CSS, misal "akreditasi-unggul".
     */
    public function getAkreditasiBadgeClass(): string
    {
        $value = strtolower((string) $this->akreditasi);

        return match (true) {
            $value === '' => 'akreditasi-default',
            str_contains($value, 'unggul') || $value === 'a' => 'akreditasi-unggul',
            str_contains($value, 'baik sekali') || $value === 'b' => 'akreditasi-baik-sekali',
            str_contains($value, 'baik') || $value === 'c' => 'akreditasi-baik',
            default => 'akreditasi-default',
        };
    }

    /**
     * Mapping nilai akreditasi ke warna Bootstrap badge (bg-*).
     *
     * Dipakai di halaman akreditasi yang masih memakai komponen
     * Bootstrap badge native.
     *
     * @return string Nama warna Bootstrap, misal "success".
     */
    public function getAkreditasiBootstrapColor(): string
    {
        $value = strtolower((string) $this->akreditasi);

        return match (true) {
            $value === '' => 'secondary',
            str_contains($value, 'unggul') || $value === 'a' => 'success',
            str_contains($value, 'baik sekali') || $value === 'b' => 'primary',
            str_contains($value, 'baik') || $value === 'c' => 'warning',
            default => 'secondary',
        };
    }

    /**
     * Ikon Bootstrap Icons tematik per prodi.
     *
     * Dipakai sebagai visual identifier di card home + tabel akreditasi,
     * menggantikan placeholder foto generik. Mapping berbasis kata kunci
     * pada nama prodi agar tetap fleksibel untuk prodi baru.
     *
     * @return string Nama kelas icon (tanpa prefix "bi bi-").
     */
    public function getTematikIcon(): string
    {
        $nama = strtolower((string) $this->nama);

        return match (true) {
            str_contains($nama, 'geomatika') || str_contains($nama, 'survei') => 'geo-alt-fill',
            str_contains($nama, 'perangkat lunak') || str_contains($nama, 'software') => 'code-slash',
            str_contains($nama, 'akuntansi') || str_contains($nama, 'keuangan') => 'calculator-fill',
            str_contains($nama, 'jaringan') || str_contains($nama, 'komputer') => 'hdd-network-fill',
            str_contains($nama, 'informatika') || str_contains($nama, 'data') => 'cpu-fill',
            str_contains($nama, 'multimedia') || str_contains($nama, 'desain') => 'palette-fill',
            default => 'mortarboard-fill',
        };
    }

    /**
     * Background CSS untuk tile card prodi di halaman home.
     *
     * Compliance dengan `.agents/rules/anti-ai-generated.md` Section 1.2:
     * SATU gradient brand-aligned (navy dark → navy primary) untuk SEMUA
     * prodi — tidak pakai rainbow palette yang terkesan AI-generated.
     *
     * Diferensiasi antar-prodi mengandalkan:
     * 1. `getTematikIcon()` — icon Bootstrap tematik per bidang
     * 2. `getAkreditasiBadgeClass()` — warna badge akreditasi
     * 3. Label jenjang (D3/D4) & nama prodi
     *
     * @return string Nilai CSS linear-gradient(...) atau solid color.
     */
    public function getTematikGradient(): string
    {
        // Single gradient: navy dark (#0f1525) → navy primary (#1a2035).
        // Subtle, corporate, konsisten dengan topbar/footer website.
        return 'linear-gradient(135deg, #0f1525 0%, #1a2035 100%)';
    }

    /**
     * Status kedaluwarsa akreditasi (2-level).
     *
     * Dihitung otomatis dari `tanggal_kedaluwarsa` tanpa perlu input admin.
     * Mengembalikan null jika tanggal belum diisi — view dapat memakai
     * fallback "-" atau menyembunyikan badge.
     *
     * @return array{label: string, color: string}|null
     *                                                  - label: teks badge (misal "Masih Berlaku")
     *                                                  - color: warna Bootstrap (success/danger)
     */
    public function getStatusKedaluwarsa(): ?array
    {
        if (! $this->tanggal_kedaluwarsa) {
            return null;
        }

        $isExpired = $this->tanggal_kedaluwarsa->isPast();

        return [
            'label' => $isExpired ? 'Kedaluwarsa' : 'Masih Berlaku',
            'color' => $isExpired ? 'danger' : 'success',
        ];
    }

    /**
     * Label resolved untuk tombol verifikasi.
     *
     * Auto-detect dari `verifikasi_url` jika `verifikasi_label` kosong:
     * - pddikti.*           → "PDDikti"
     * - *banpt.or.id        → "BAN-PT"
     * - lam-infokom.*       → "LAM-INFOKOM"
     * - lam-teknik.*        → "LAM-Teknik"
     * - lamemba.* / lameemba → "LAMEMBA"
     * - lam-ptkes.*         → "LAM-PTKes"
     * - host lain ada "lam" → "LAM"
     * - lainnya             → "Verifikasi"
     *
     * @return string|null Label tombol, null jika `verifikasi_url` kosong.
     */
    public function getVerifikasiLabel(): ?string
    {
        if (! $this->verifikasi_url) {
            return null;
        }

        if (! empty($this->verifikasi_label)) {
            return $this->verifikasi_label;
        }

        $host = strtolower((string) parse_url($this->verifikasi_url, PHP_URL_HOST));

        return match (true) {
            str_contains($host, 'pddikti') => 'PDDikti',
            str_contains($host, 'banpt.or.id') => 'BAN-PT',
            str_contains($host, 'lam-infokom') => 'LAM-INFOKOM',
            str_contains($host, 'lam-teknik') => 'LAM-Teknik',
            str_contains($host, 'lamemba') || str_contains($host, 'lameemba') => 'LAMEMBA',
            str_contains($host, 'lam-ptkes') => 'LAM-PTKes',
            str_contains($host, 'lam') => 'LAM',
            default => 'Verifikasi',
        };
    }
}
