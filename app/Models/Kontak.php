<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kontak extends Model
{
    use \App\Traits\LogsAdminActivity;

    protected string $logLabel = 'Kontak Jurusan';

    /** @var array<int, string> */
    protected array $logAttributes = ['alamat', 'email', 'telepon'];

    protected function logIdentifier(): string
    {
        return 'informasi kontak';
    }

    protected $fillable = [
        'alamat',
        'email',
        'telepon',
        'koordinat',
        'google_maps_embed',
        'tiktok',
        'facebook',
        'instagram',
        'youtube',
        'linkedin',
    ];
}
