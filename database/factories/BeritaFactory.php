<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Berita;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Berita>
 *
 * Factory Berita dengan 3 state: regular(), pengajaran(), pengabdian().
 * Konten realistis tema Jurusan R&K Politeknik Pertanian Negeri Samarinda
 * (sesuai rule .windsurf/rules/anti-ai-generated.md - bukan Lorem Ipsum).
 */
class BeritaFactory extends Factory
{
    protected $model = Berita::class;

    public function definition(): array
    {
        $tpl = ContentPool::regular();

        return [
            'judul' => $tpl['judul'],
            'slug' => Str::slug($tpl['judul']).'-'.Str::random(4),
            'ringkasan' => $tpl['ringkasan'],
            'konten' => $tpl['konten'],
            'gambar' => null,
            'penulis_id' => User::where('email', 'admin@rekkom.ac.id')->value('id') ?? User::value('id'),
            'program_studi_id' => null,
            'tridharma_type' => null,
            'tanggal_publikasi' => fake()->dateTimeBetween('-180 days', '-1 day'),
            'is_published' => true,
        ];
    }

    public function pengajaran(): static
    {
        return $this->state(function () {
            $tpl = ContentPool::pengajaran();
            $prodi = ProgramStudi::inRandomOrder()->first();

            return [
                'judul' => $tpl['judul'],
                'slug' => Str::slug($tpl['judul']).'-'.Str::random(4),
                'ringkasan' => $tpl['ringkasan'],
                'konten' => $tpl['konten'],
                'tridharma_type' => 'pengajaran',
                'program_studi_id' => fake()->boolean(60) ? $prodi?->id : null,
                'tanggal_publikasi' => fake()->dateTimeBetween('-1 year', '-7 days'),
            ];
        });
    }

    public function pengabdian(): static
    {
        return $this->state(function () {
            $tpl = ContentPool::pengabdian();
            $prodi = ProgramStudi::inRandomOrder()->first();

            return [
                'judul' => $tpl['judul'],
                'slug' => Str::slug($tpl['judul']).'-'.Str::random(4),
                'ringkasan' => $tpl['ringkasan'],
                'konten' => $tpl['konten'],
                'tridharma_type' => 'pengabdian',
                'program_studi_id' => fake()->boolean(70) ? $prodi?->id : null,
                'lokasi' => fake()->randomElement(ContentPool::LOKASI_PENGABDIAN),
                'dampak_singkat' => fake()->randomElement(ContentPool::DAMPAK),
                'tanggal_publikasi' => fake()->dateTimeBetween('-1 year', '-14 days'),
            ];
        });
    }
}
