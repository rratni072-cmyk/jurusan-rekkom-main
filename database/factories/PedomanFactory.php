<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Pedoman;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pedoman>
 */
class PedomanFactory extends Factory
{
    protected $model = Pedoman::class;

    /**
     * Default state — pedoman PDF kategori akademik aktif.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_file' => fake()->sentence(4),
            'kategori' => fake()->randomElement(array_keys(Pedoman::KATEGORI)),
            'deskripsi' => fake()->optional()->paragraph(),
            'format_file' => 'PDF',
            'file_path' => 'pedoman/'.fake()->slug().'.pdf',
            'urutan' => fake()->numberBetween(0, 100),
            'is_active' => true,
        ];
    }

    public function nonaktif(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }

    public function excel(): static
    {
        return $this->state(fn () => [
            'format_file' => 'XLSX',
            'file_path' => 'pedoman/'.fake()->slug().'.xlsx',
        ]);
    }

    public function kategori(string $kategori): static
    {
        return $this->state(fn () => ['kategori' => $kategori]);
    }
}
