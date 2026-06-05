<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @internal
 */
class HomePageBeritaSectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_returns_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Berita Terbaru Jurusan', false);
        $response->assertSee('id="berita"', false);
    }

    public function test_homepage_shows_empty_state_when_no_published_berita(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Berita belum tersedia', false);
    }

    public function test_homepage_renders_featured_and_list_when_berita_exist(): void
    {
        $penulis = User::factory()->create();

        // Buat 5 berita terpublikasi.
        for ($i = 1; $i <= 5; $i++) {
            Berita::create([
                'judul' => "Berita Demo {$i}",
                'slug' => "berita-demo-{$i}",
                'ringkasan' => "Ringkasan berita demo nomor {$i}.",
                'konten' => "<p>Konten berita demo nomor {$i}.</p>",
                'gambar' => null,
                'penulis_id' => $penulis->id,
                'tanggal_publikasi' => now()->subDays($i),
                'is_published' => true,
            ]);
        }

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('news-featured-card', false);
        $response->assertSee('news-list-panel', false);
        $response->assertSee('Berita Demo 1');
        $response->assertSee('Lihat Semua Berita');
    }

    public function test_featured_card_shows_author_byline_and_reading_time(): void
    {
        $penulis = User::factory()->create(['name' => 'Dosen Andi Wijaya']);

        Berita::create([
            'judul' => 'Berita dengan Penulis',
            'slug' => 'berita-dengan-penulis',
            'ringkasan' => 'Ringkasan singkat.',
            'konten' => str_repeat('kata ', 410), // ~410 kata → 3 menit baca.
            'penulis_id' => $penulis->id,
            'tanggal_publikasi' => now(),
            'is_published' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Dosen Andi Wijaya', false);
        $response->assertSee('news-author', false);
        $response->assertSee('news-reading-time', false);
        $response->assertSee('3 menit baca', false);
    }

    public function test_word_boundary_truncation_does_not_cut_mid_word(): void
    {
        $penulis = User::factory()->create();

        Berita::create([
            'judul' => 'Berita Truncate',
            'slug' => 'berita-truncate',
            // Ringkasan dengan kata "Indonesia" yang gampang terpotong jika pakai char-limit.
            'ringkasan' => 'Mahasiswa Jurusan R&K mengikuti workshop IoT selama tiga hari dengan narasumber dari Telkom Indonesia di Bandung pekan ini.',
            'konten' => '<p>Konten singkat.</p>',
            'penulis_id' => $penulis->id,
            'tanggal_publikasi' => now(),
            'is_published' => true,
        ]);

        $response = $this->get('/');
        $content = $response->getContent();

        $response->assertStatus(200);
        // Tidak boleh ada potongan parsial seperti "Indone..." atau "Ind...".
        $this->assertStringNotContainsString('Indone…', $content);
        $this->assertStringNotContainsString('Indone...', $content);
    }
}
