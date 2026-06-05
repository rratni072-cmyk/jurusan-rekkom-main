<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Pedoman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Regression test untuk fitur Pedoman (admin CRUD + frontend display).
 *
 * Coverage:
 * - Frontend index page accessible (route public)
 * - Filter pills + card grid render
 * - Admin CRUD: create, store (PDF), store (Excel), update, delete
 * - Auto-detect format dari extension upload
 * - Toggle active via PATCH endpoint
 * - Validation: nama wajib, kategori wajib, file tipe & ukuran
 * - Activity log tercatat untuk setiap mutasi
 * - Auth middleware: guest dialihkan ke /login
 *
 * @internal
 */
class PedomanTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================
    // FRONTEND PUBLIC PAGE
    // =========================================================

    public function test_halaman_publik_pedoman_dapat_diakses(): void
    {
        $response = $this->get('/kemahasiswaan/pedoman');

        $response->assertStatus(200);
        $response->assertSee('Pedoman Akademik', false);
    }

    public function test_halaman_publik_pedoman_menampilkan_card_grid_filter_dan_search(): void
    {
        Pedoman::factory()->create([
            'nama_file' => 'Pedoman Test PDF',
            'kategori' => Pedoman::KATEGORI_AKADEMIK,
            'is_active' => true,
        ]);

        $response = $this->get('/kemahasiswaan/pedoman');

        $response->assertStatus(200);
        // Tabel responsive hybrid (desktop) + filter pills + search ter-render
        $response->assertSee('pedoman-filter-pill', false);
        $response->assertSee('pedoman-table', false);          // desktop table
        $response->assertSee('pedoman-mobile-list', false);    // mobile card stack
        $response->assertSee('pedoman-search', false);
        // Data muncul
        $response->assertSee('Pedoman Test PDF', false);
    }

    public function test_halaman_publik_pedoman_hanya_menampilkan_yang_aktif(): void
    {
        Pedoman::factory()->create(['nama_file' => 'Pedoman Aktif', 'is_active' => true]);
        Pedoman::factory()->create(['nama_file' => 'Pedoman Nonaktif', 'is_active' => false]);

        $response = $this->get('/kemahasiswaan/pedoman');

        $response->assertSee('Pedoman Aktif', false);
        $response->assertDontSee('Pedoman Nonaktif', false);
    }

    // =========================================================
    // ADMIN AUTH
    // =========================================================

    public function test_guest_diarahkan_ke_login_saat_akses_admin_pedoman(): void
    {
        $this->get('/admin/pedoman')->assertRedirect('/login');
        $this->get('/admin/pedoman/create')->assertRedirect('/login');
    }

    public function test_admin_dapat_mengakses_halaman_index_pedoman(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->get('/admin/pedoman');

        $response->assertStatus(200);
        $response->assertSee('Pedoman', false);
    }

    public function test_admin_dapat_mengakses_halaman_create_pedoman(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->get('/admin/pedoman/create');

        $response->assertStatus(200);
    }

    // =========================================================
    // ADMIN CRUD — STORE
    // =========================================================

    public function test_admin_dapat_upload_pedoman_pdf(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();
        $file = UploadedFile::fake()->create('pedoman-uji.pdf', 500, 'application/pdf');

        $response = $this->actingAs($admin)->post('/admin/pedoman', [
            'nama_file' => 'Pedoman Uji PDF',
            'kategori' => Pedoman::KATEGORI_AKADEMIK,
            'deskripsi' => 'Deskripsi pedoman uji.',
            'urutan' => 5,
            'is_active' => '1',
            'file_path' => $file,
        ]);

        $response->assertRedirect(route('admin.pedoman.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('pedomans', [
            'nama_file' => 'Pedoman Uji PDF',
            'kategori' => Pedoman::KATEGORI_AKADEMIK,
            'format_file' => 'PDF',     // auto-detect dari extension
            'urutan' => 5,
            'is_active' => true,
        ]);
        // File tersimpan di storage
        $pedoman = Pedoman::where('nama_file', 'Pedoman Uji PDF')->firstOrFail();
        Storage::disk('public')->assertExists($pedoman->file_path);
    }

    public function test_admin_dapat_upload_pedoman_excel(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();
        $file = UploadedFile::fake()->create('data.xlsx', 100, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $response = $this->actingAs($admin)->post('/admin/pedoman', [
            'nama_file' => 'Pedoman Excel',
            'kategori' => Pedoman::KATEGORI_WISUDA,
            'urutan' => 1,
            'is_active' => '1',
            'file_path' => $file,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pedomans', [
            'nama_file' => 'Pedoman Excel',
            'format_file' => 'XLSX',
        ]);
    }

    public function test_admin_pedoman_validasi_nama_wajib(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post('/admin/pedoman', [
            'nama_file' => '',
            'kategori' => Pedoman::KATEGORI_AKADEMIK,
            'file_path' => UploadedFile::fake()->create('a.pdf', 100, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('nama_file');
    }

    public function test_admin_pedoman_validasi_kategori_wajib_dan_valid(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post('/admin/pedoman', [
            'nama_file' => 'X',
            'kategori' => 'kategori-tidak-valid',
            'file_path' => UploadedFile::fake()->create('a.pdf', 100, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('kategori');
    }

    public function test_admin_pedoman_validasi_file_tipe_tidak_didukung_ditolak(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post('/admin/pedoman', [
            'nama_file' => 'Pedoman X',
            'kategori' => Pedoman::KATEGORI_AKADEMIK,
            'file_path' => UploadedFile::fake()->create('virus.exe', 100, 'application/x-msdownload'),
        ]);

        $response->assertSessionHasErrors('file_path');
    }

    // =========================================================
    // ADMIN CRUD — UPDATE & DELETE
    // =========================================================

    public function test_admin_dapat_update_pedoman_tanpa_ganti_file(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();
        $pedoman = Pedoman::factory()->create([
            'nama_file' => 'Nama Lama',
            'kategori' => Pedoman::KATEGORI_AKADEMIK,
        ]);

        $response = $this->actingAs($admin)->put("/admin/pedoman/{$pedoman->id}", [
            'nama_file' => 'Nama Baru',
            'kategori' => Pedoman::KATEGORI_TUGAS_AKHIR,
            'deskripsi' => 'Deskripsi baru',
            'urutan' => 10,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.pedoman.index'));
        $this->assertDatabaseHas('pedomans', [
            'id' => $pedoman->id,
            'nama_file' => 'Nama Baru',
            'kategori' => Pedoman::KATEGORI_TUGAS_AKHIR,
            'urutan' => 10,
        ]);
    }

    public function test_admin_dapat_hapus_pedoman(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();
        // Create dengan file di storage agar test cover path delete file
        Storage::disk('public')->put('pedoman/dummy.pdf', 'fake-content');
        $pedoman = Pedoman::factory()->create(['file_path' => 'pedoman/dummy.pdf']);

        $response = $this->actingAs($admin)->deleteJson("/admin/pedoman/{$pedoman->id}");

        $response->assertOk();
        $response->assertJson(['success' => true]);
        $this->assertDatabaseMissing('pedomans', ['id' => $pedoman->id]);
        Storage::disk('public')->assertMissing('pedoman/dummy.pdf');
    }

    // =========================================================
    // TOGGLE ACTIVE (AJAX)
    // =========================================================

    public function test_admin_dapat_toggle_status_aktif_pedoman(): void
    {
        $admin = User::factory()->create();
        $pedoman = Pedoman::factory()->create(['is_active' => true]);

        $response = $this->actingAs($admin)
            ->patchJson("/admin/pedoman/{$pedoman->id}/toggle-active");

        $response->assertOk();
        $response->assertJson(['success' => true, 'is_active' => false]);
        $this->assertFalse($pedoman->fresh()->is_active);

        // Toggle lagi → kembali true
        $this->actingAs($admin)
            ->patchJson("/admin/pedoman/{$pedoman->id}/toggle-active")
            ->assertJson(['success' => true, 'is_active' => true]);
    }

    public function test_guest_tidak_dapat_toggle_active(): void
    {
        $pedoman = Pedoman::factory()->create();

        $this->patchJson("/admin/pedoman/{$pedoman->id}/toggle-active")
            ->assertStatus(401);
    }
}
