<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\Kegiatan;
use App\Models\KontakPesan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

/**
 * Feature test untuk halaman Dashboard Admin.
 *
 * @internal
 */
class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_redirected_to_login(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard', false);
        $response->assertSee('Selamat datang', false);
        $response->assertSee('Tulis Berita', false);
        $response->assertSee('Pesan Belum Dibaca', false);
        $response->assertSee('Aktivitas Terkini', false);
        $response->assertSee('Statistik Konten', false);
    }

    public function test_dashboard_shows_unread_messages_when_present(): void
    {
        $admin = User::factory()->create();

        KontakPesan::create([
            'nama' => 'Mahasiswa Demo',
            'email' => 'demo@example.com',
            'subjek' => 'Pertanyaan tentang prodi',
            'pesan' => 'Halo admin, saya ingin tahu...',
            'is_read' => false,
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Mahasiswa Demo');
        $response->assertSee('Pertanyaan tentang prodi');
    }

    public function test_dashboard_shows_upcoming_kegiatan_only(): void
    {
        $admin = User::factory()->create();

        // Pakai factory agar tipe_kegiatan_id otomatis ter-resolve via firstOrCreate.
        Kegiatan::factory()->past()->create([
            'judul' => 'Kegiatan Lampau',
            'slug' => 'kegiatan-lampau',
            'ringkasan' => 'Sudah lewat',
            'konten' => '...',
            'tanggal' => now()->subDays(10),
            'is_published' => true,
        ]);

        Kegiatan::factory()->upcoming()->create([
            'judul' => 'Workshop Mendatang',
            'slug' => 'workshop-mendatang',
            'ringkasan' => 'Belum terjadi',
            'konten' => '...',
            'tanggal' => now()->addDays(7),
            'is_published' => true,
        ]);

        Activity::query()->delete();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Workshop Mendatang');
        $response->assertDontSee('Kegiatan Lampau');
    }

    public function test_dashboard_chart_filter_accepts_only_allowed_values(): void
    {
        $admin = User::factory()->create();

        // Nilai tidak valid → fallback ke 6.
        $response = $this->actingAs($admin)->get('/admin/dashboard?months=999');
        $response->assertStatus(200);
        $response->assertSee('aria-pressed="true"', false);

        // Nilai 12 valid.
        $response = $this->actingAs($admin)->get('/admin/dashboard?months=12');
        $response->assertStatus(200);
        $response->assertSee('?months=12', false);
    }

    public function test_dashboard_creates_recent_berita_section(): void
    {
        $admin = User::factory()->create();

        Berita::create([
            'judul' => 'Berita Dashboard Test',
            'slug' => 'berita-dashboard-test',
            'ringkasan' => 'Ringkasan singkat.',
            'konten' => '<p>Konten.</p>',
            'penulis_id' => $admin->id,
            'tanggal_publikasi' => now(),
            'is_published' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Berita Dashboard Test');
        $response->assertSee('Published');
    }
}
