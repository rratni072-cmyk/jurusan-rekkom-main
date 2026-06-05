<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Http\Requests\Admin\ProfilJurusanRequest;
use App\Models\ProfilJurusan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Regression test: pastikan halaman edit Profil Jurusan benar-benar
 * dapat menyimpan perubahan (sebelumnya FormRequest selalu menolak
 * karena slug→key mismatch).
 *
 * @internal
 */
class ProfilJurusanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function sectionProvider(): array
    {
        return [
            'tentang' => ['tentang-jurusan', 'tentang_jurusan'],
            'visi-misi' => ['visi-misi', 'visi_misi'],
            'struktur' => ['struktur-organisasi', 'struktur_organisasi'],
        ];
    }

    #[DataProvider('sectionProvider')]
    public function test_admin_dapat_menyimpan_konten_profil(string $slug, string $kunci): void
    {
        $admin = User::factory()->create();

        $payload = [
            '_token' => csrf_token(),
            'profil' => [
                $kunci => [
                    'judul' => 'Judul Uji',
                    'nilai' => '<p>Konten baru section.</p>',
                ],
            ],
        ];

        $response = $this->actingAs($admin)
            ->put("/admin/profil/{$slug}", $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('profil_jurusans', [
            'kunci' => $kunci,
            'nilai' => '<p>Konten baru section.</p>',
        ]);
    }

    #[DataProvider('sectionProvider')]
    public function test_admin_dapat_upload_gambar_profil(string $slug, string $kunci): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();

        $file = UploadedFile::fake()->image('cover.jpg', 800, 600);

        $response = $this->actingAs($admin)->put("/admin/profil/{$slug}", [
            'profil' => [
                $kunci => [
                    'judul' => 'Judul Uji',
                    'nilai' => '<p>Dengan gambar.</p>',
                    'gambar' => $file,
                ],
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $profil = ProfilJurusan::where('kunci', $kunci)->firstOrFail();
        $this->assertNotNull($profil->gambar, 'Path gambar harus tersimpan.');
        Storage::disk('public')->assertExists($profil->gambar);
    }

    public function test_gambar_lebih_dari_5mb_ditolak(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();

        // 6 MB > batas 5 MB.
        $file = UploadedFile::fake()->image('big.jpg')->size(6144);

        $response = $this->actingAs($admin)->put('/admin/profil/tentang-jurusan', [
            'profil' => [
                'tentang_jurusan' => [
                    'judul' => 'Tentang',
                    'nilai' => '<p>X</p>',
                    'gambar' => $file,
                ],
            ],
        ]);

        $response->assertSessionHasErrors('profil.tentang_jurusan.gambar');
    }

    public function test_gambar_tepat_5mb_diterima(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();

        // 5 MB = tepat di batas, harus diterima.
        $file = UploadedFile::fake()->image('edge.jpg')->size(5120);

        $response = $this->actingAs($admin)->put('/admin/profil/tentang-jurusan', [
            'profil' => [
                'tentang_jurusan' => [
                    'judul' => 'Tentang',
                    'nilai' => '<p>X</p>',
                    'gambar' => $file,
                ],
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_format_gambar_tidak_didukung_ditolak(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();

        $file = UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf');

        $response = $this->actingAs($admin)->put('/admin/profil/tentang-jurusan', [
            'profil' => [
                'tentang_jurusan' => [
                    'nilai' => '<p>X</p>',
                    'gambar' => $file,
                ],
            ],
        ]);

        $response->assertSessionHasErrors('profil.tentang_jurusan.gambar');
    }

    public function test_form_request_memetakan_slug_ke_kunci_db(): void
    {
        $this->assertSame('tentang_jurusan', ProfilJurusanRequest::keyFor('tentang-jurusan'));
        $this->assertSame('visi_misi', ProfilJurusanRequest::keyFor('visi-misi'));
        $this->assertSame('struktur_organisasi', ProfilJurusanRequest::keyFor('struktur-organisasi'));
        $this->assertNull(ProfilJurusanRequest::keyFor('section-tidak-ada'));
    }

    public function test_guest_tidak_bisa_update_profil(): void
    {
        $response = $this->put('/admin/profil/tentang-jurusan', [
            'profil' => ['tentang_jurusan' => ['nilai' => 'X']],
        ]);

        $response->assertRedirect('/login');
    }

    #[DataProvider('sectionProvider')]
    public function test_admin_dapat_menyimpan_judul(string $slug, string $kunci): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->put("/admin/profil/{$slug}", [
            'profil' => [
                $kunci => [
                    'judul' => 'Struktur Organisasi Jurusan Rekayasa dan Komputer',
                    'nilai' => '<p>Konten.</p>',
                ],
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('profil_jurusans', [
            'kunci' => $kunci,
            'judul' => 'Struktur Organisasi Jurusan Rekayasa dan Komputer',
        ]);
    }

    public function test_judul_wajib_diisi(): void
    {
        $admin = User::factory()->create();

        // Judul tidak dikirim sama sekali.
        $this->actingAs($admin)->put('/admin/profil/visi-misi', [
            'profil' => [
                'visi_misi' => [
                    'nilai' => '<p>X</p>',
                ],
            ],
        ])->assertSessionHasErrors('profil.visi_misi.judul');

        // Judul dikirim sebagai string kosong.
        $this->actingAs($admin)->put('/admin/profil/visi-misi', [
            'profil' => [
                'visi_misi' => [
                    'judul' => '',
                    'nilai' => '<p>X</p>',
                ],
            ],
        ])->assertSessionHasErrors('profil.visi_misi.judul');

        // Judul whitespace-only juga ditolak (Laravel `required` auto-trim).
        $this->actingAs($admin)->put('/admin/profil/visi-misi', [
            'profil' => [
                'visi_misi' => [
                    'judul' => '   ',
                    'nilai' => '<p>X</p>',
                ],
            ],
        ])->assertSessionHasErrors('profil.visi_misi.judul');
    }

    public function test_judul_lebih_dari_255_karakter_ditolak(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->put('/admin/profil/tentang-jurusan', [
            'profil' => [
                'tentang_jurusan' => [
                    'judul' => str_repeat('a', 256),
                    'nilai' => '<p>X</p>',
                ],
            ],
        ]);

        $response->assertSessionHasErrors('profil.tentang_jurusan.judul');
    }

    public function test_frontend_pakai_judul_kustom_saat_tersedia(): void
    {
        ProfilJurusan::create([
            'kunci' => 'struktur_organisasi',
            'judul' => 'Struktur Organisasi Jurusan Rekayasa dan Komputer',
            'nilai' => '<p>isi</p>',
        ]);

        $this->get('/profil/struktur-organisasi')
            ->assertOk()
            ->assertSee('Struktur Organisasi Jurusan Rekayasa dan Komputer', false);
    }

    public function test_frontend_fallback_ke_default_saat_judul_null(): void
    {
        ProfilJurusan::create([
            'kunci' => 'struktur_organisasi',
            'judul' => null,
            'nilai' => '<p>isi</p>',
        ]);

        // Default hardcoded di view harus tetap tampil.
        $this->get('/profil/struktur-organisasi')
            ->assertOk()
            ->assertSee('Struktur Organisasi', false);
    }

    public function test_admin_dapat_menghapus_gambar_profil(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();

        // Setup: bikin profil dengan gambar yang benar-benar ada di storage fake.
        $path = UploadedFile::fake()->image('existing.jpg')
            ->storeAs('profil-jurusan', 'existing.jpg', 'public');
        $profil = ProfilJurusan::create([
            'kunci' => 'tentang_jurusan',
            'judul' => 'Tentang',
            'nilai' => '<p>X</p>',
            'gambar' => $path,
        ]);
        Storage::disk('public')->assertExists($path);

        // Kirim flag hapus tanpa upload file baru.
        $response = $this->actingAs($admin)->put('/admin/profil/tentang-jurusan', [
            'profil' => [
                'tentang_jurusan' => [
                    'judul' => 'Tentang',
                    'nilai' => '<p>X</p>',
                    'hapus_gambar' => '1',
                ],
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $profil->refresh();
        $this->assertNull($profil->gambar, 'Kolom gambar harus null setelah hapus.');
        Storage::disk('public')->assertMissing($path);
    }

    public function test_upload_baru_menang_atas_flag_hapus(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();

        $oldPath = UploadedFile::fake()->image('old.jpg')
            ->storeAs('profil-jurusan', 'old.jpg', 'public');
        $profil = ProfilJurusan::create([
            'kunci' => 'tentang_jurusan',
            'judul' => 'Tentang',
            'nilai' => '<p>X</p>',
            'gambar' => $oldPath,
        ]);

        // Admin centang hapus DAN upload file baru → file baru harus menang.
        $newFile = UploadedFile::fake()->image('new.jpg', 800, 600);
        $response = $this->actingAs($admin)->put('/admin/profil/tentang-jurusan', [
            'profil' => [
                'tentang_jurusan' => [
                    'judul' => 'Tentang',
                    'nilai' => '<p>X</p>',
                    'hapus_gambar' => '1',
                    'gambar' => $newFile,
                ],
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $profil->refresh();
        $this->assertNotNull($profil->gambar, 'Gambar baru harus tersimpan (upload menang).');
        $this->assertNotSame($oldPath, $profil->gambar, 'Path harus berubah ke file baru.');
        Storage::disk('public')->assertExists($profil->gambar);
        Storage::disk('public')->assertMissing($oldPath); // file lama terhapus
    }

    public function test_post_too_large_exception_redirect_dengan_pesan_jelas(): void
    {
        // Pancing exception handler di bootstrap/app.php secara langsung
        // (tidak via HTTP — PHP rejects request sebelum framework jalan,
        // jadi feature test biasa tidak bisa mereproduksi skenario ini).
        $exception = new \Illuminate\Http\Exceptions\PostTooLargeException;

        $request = \Illuminate\Http\Request::create(
            '/admin/profil/struktur-organisasi',
            'PUT',
            server: ['CONTENT_LENGTH' => 20 * 1024 * 1024], // 20 MB
        );
        $request->setLaravelSession(app('session.store'));
        $request->session()->setPreviousUrl(url('/admin/profil/struktur-organisasi'));

        /** @var \Illuminate\Contracts\Debug\ExceptionHandler $handler */
        $handler = app(\Illuminate\Contracts\Debug\ExceptionHandler::class);
        $response = $handler->render($request, $exception);

        $this->assertSame(302, $response->getStatusCode(), 'Harus redirect, bukan tampilkan 413.');

        // Cek pesan error masuk ke session errors bag (akan ditampilkan
        // via SweetAlert modal di admin layout).
        $errors = $request->session()->get('errors');
        $this->assertNotNull($errors, 'Session errors harus terisi.');
        $this->assertTrue($errors->has('upload'));
        $msg = $errors->first('upload');
        $this->assertStringContainsString('Upload gagal', $msg);
        $this->assertStringContainsString('20', $msg); // ukuran terkirim 20 MB
    }

    public function test_flag_hapus_tanpa_gambar_eksisting_tidak_error(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();

        // Profil tanpa gambar sama sekali.
        ProfilJurusan::create([
            'kunci' => 'visi_misi',
            'judul' => 'Visi',
            'nilai' => '<p>X</p>',
            'gambar' => null,
        ]);

        // Admin kirim flag hapus → controller harus no-op, tidak throw error.
        $response = $this->actingAs($admin)->put('/admin/profil/visi-misi', [
            'profil' => [
                'visi_misi' => [
                    'judul' => 'Visi',
                    'nilai' => '<p>X</p>',
                    'hapus_gambar' => '1',
                ],
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $profil = ProfilJurusan::where('kunci', 'visi_misi')->firstOrFail();
        $this->assertNull($profil->gambar);
    }
}
