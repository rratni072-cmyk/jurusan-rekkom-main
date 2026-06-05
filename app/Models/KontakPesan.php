<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model KontakPesan.
 *
 * Merepresentasikan pesan masuk dari pengunjung via form kontak.
 *
 * @property int $id
 * @property string $nama Nama pengirim
 * @property string $email Email pengirim
 * @property string $subjek Subjek pesan
 * @property string $pesan Isi pesan
 * @property bool $is_read Status sudah dibaca
 */
class KontakPesan extends Model
{
    /**
     * Kolom yang boleh diisi secara mass-assignment.
     *
     * @var array<string>
     */
    protected $fillable = [
        'nama',
        'email',
        'subjek',
        'pesan',
        'is_read',
    ];

    /**
     * Cast tipe data otomatis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Scope: hanya pesan yang belum dibaca.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
