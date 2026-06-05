<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ProgramStudi;
use Illuminate\Support\Collection;

/**
 * Repository untuk akses data Program Studi.
 *
 * Mengabstraksi query Eloquent dari Controller sesuai pola Repository yang
 * ditetapkan di .agents/rules/arsitektur-proyek.md dan kualitas-kode.md.
 */
class ProgramStudiRepository
{
    /**
     * Ambil daftar program studi aktif untuk ditampilkan di homepage.
     *
     * Hanya mengambil kolom yang dibutuhkan oleh card di section Program Studi
     * untuk efisiensi query. Form prodi kini fokus pada data akreditasi,
     * sehingga kolom deskripsi/gambar/website sudah di-drop.
     *
     * @return Collection<int, ProgramStudi>
     */
    public function getActiveForHome(): Collection
    {
        return ProgramStudi::query()
            ->active()
            ->orderBy('nama')
            ->get([
                'id',
                'nama',
                'jenjang',
                'akreditasi',
            ]);
    }
}
