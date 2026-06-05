<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HtmlSanitizer;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * Model ProfilJurusan.
 *
 * Merepresentasikan data profil jurusan yang disimpan
 * secara key-value (visi, misi, sejarah, dll).
 *
 * @property int $id
 * @property string $kunci Key unik (visi, misi, sejarah, sambutan_ketua)
 * @property string|null $judul Judul heading utama card (editable admin, nullable = pakai default)
 * @property string $nilai Konten HTML
 * @property string|null $gambar Path gambar pendukung
 */
class ProfilJurusan extends Model
{
    use \App\Traits\LogsAdminActivity;

    protected string $logLabel = 'Profil Jurusan';

    /** @var array<int, string> */
    protected array $logAttributes = ['kunci', 'judul', 'nilai'];

    protected function logIdentifier(): string
    {
        return (string) ($this->getAttribute('judul') ?: $this->getAttribute('kunci') ?: '#'.$this->getKey());
    }

    /**
     * Kolom yang boleh diisi secara mass-assignment.
     *
     * @var array<string>
     */
    protected $fillable = [
        'kunci',
        'judul',
        'nilai',
        'gambar',
    ];

    /**
     * Accessor untuk field `nilai` (HTML konten dari TinyMCE).
     *
     * Pastikan setiap <img> punya `alt` attribute (a11y compliance) walaupun
     * admin lupa mengisi alt waktu insert image. Fallback ke `alt=""` (decorative).
     */
    protected function nilai(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): ?string => HtmlSanitizer::ensureImgAlt($value),
        );
    }
}
