<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Akreditasi extends Model
{
    protected $fillable = [
        'program_studi',
        'no_sk',
        'peringkat',
        'tahun_sk',
        'tanggal_kedaluwarsa',
        'status',
        'file_sertifikat'
    ];
}