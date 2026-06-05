<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Rute-rute web untuk website Jurusan Rekayasa Komputer.
| Dibagi menjadi 3 grup: Frontend (publik), Admin (terautentikasi),
| dan Profil User (Breeze default).
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\BeasiswaController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\KegiatanController;
use App\Http\Controllers\Admin\KontakController;
use App\Http\Controllers\Admin\KontakPesanController;
use App\Http\Controllers\Admin\PedomanController;
use App\Http\Controllers\Admin\ProfilJurusanController;
use App\Http\Controllers\Admin\ProgramStudiController;
use App\Http\Controllers\Admin\AkreditasiController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\TinymceImageController;
use App\Http\Controllers\Admin\TipeKegiatanController;
use App\Http\Controllers\Admin\TridharmaController as AdminTridharmaController;
use App\Http\Controllers\Frontend\BeritaController as FrontendBeritaController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\KemahasiswaanController;
use App\Http\Controllers\Frontend\KontakController as FrontendKontakController;
use App\Http\Controllers\Frontend\ProfilController;
use App\Http\Controllers\Frontend\TridharmaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes (Publik - bisa diakses tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// Profil Jurusan
Route::prefix('profil')->name('profil.')->group(function () {
    Route::get('/tentang-jurusan', [ProfilController::class, 'tentang'])->name('tentang');
    Route::get('/visi-misi', [ProfilController::class, 'visiMisi'])->name('visi-misi');
    Route::get('/struktur-organisasi', [ProfilController::class, 'struktur'])->name('struktur');
    Route::get('/akreditasi', [ProfilController::class, 'akreditasi'])->name('akreditasi');
});

// Kemahasiswaan
Route::prefix('kemahasiswaan')->name('kemahasiswaan.')->group(function () {
    Route::get('/jadwal-perkuliahan', [KemahasiswaanController::class, 'jadwal'])->name('jadwal');
    Route::get('/pedoman', [KemahasiswaanController::class, 'pedoman'])->name('pedoman');
    Route::get('/beasiswa', [KemahasiswaanController::class, 'beasiswa'])->name('beasiswa');
    Route::get('/kegiatan', [KemahasiswaanController::class, 'kegiatan'])->name('kegiatan');
    Route::get('/kegiatan/{slug}', [KemahasiswaanController::class, 'kegiatanShow'])->name('kegiatan.show');
});

// Tridharma
Route::prefix('tridharma')->name('tridharma.')->group(function () {
    Route::get('/pengajaran', [TridharmaController::class, 'pengajaran'])->name('pengajaran');
    Route::get('/pengabdian', [TridharmaController::class, 'pengabdian'])->name('pengabdian');
});

// Berita
Route::get('/berita', [FrontendBeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [FrontendBeritaController::class, 'show'])->name('berita.show');

// Kontak & Kirim Pesan (POST di-throttle untuk cegah spam: maks 5 request per menit per IP)
Route::get('/kontak', [FrontendKontakController::class, 'index'])->name('kontak');
Route::post('/kontak', [FrontendKontakController::class, 'kirimPesan'])
    ->middleware('throttle:5,1')
    ->name('kontak.kirim');

/*
|--------------------------------------------------------------------------
| Admin Routes (Memerlukan autentikasi)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // === CRUD Resources ===
    Route::get('/slider/datatable', [SliderController::class, 'datatable'])->name('slider.datatable');
    Route::resource('slider', SliderController::class)->except(['show']);

    // Profil Jurusan (3 section: tentang, visi-misi, struktur).
    // Backward compat: URL + route names lama tetap, tapi map ke 2 controller method generic.
    Route::get('/profil/tentang-jurusan', [ProfilJurusanController::class, 'edit'])
        ->defaults('section', 'tentang-jurusan')->name('profil.tentang.edit');
    Route::put('/profil/tentang-jurusan', [ProfilJurusanController::class, 'update'])
        ->defaults('section', 'tentang-jurusan')->name('profil.tentang.update');
    Route::get('/profil/visi-misi', [ProfilJurusanController::class, 'edit'])
        ->defaults('section', 'visi-misi')->name('profil.visi-misi.edit');
    Route::put('/profil/visi-misi', [ProfilJurusanController::class, 'update'])
        ->defaults('section', 'visi-misi')->name('profil.visi-misi.update');
    Route::get('/profil/struktur-organisasi', [ProfilJurusanController::class, 'edit'])
        ->defaults('section', 'struktur-organisasi')->name('profil.struktur.edit');
    Route::put('/profil/struktur-organisasi', [ProfilJurusanController::class, 'update'])
        ->defaults('section', 'struktur-organisasi')->name('profil.struktur.update');

    Route::get('/berita/datatable', [BeritaController::class, 'datatable'])->name('berita.datatable');
   
    Route::post('/berita/upload-image', [TinymceImageController::class, 'upload'])
        ->middleware('throttle:30,1')
        ->name('berita.upload-image');
    Route::post('/tinymce/upload-image', [TinymceImageController::class, 'upload'])
        ->middleware('throttle:30,1')
        ->name('tinymce.upload-image');
    Route::resource('berita', BeritaController::class)->except(['show']);

    Route::get('/kategori/datatable', [KategoriController::class, 'datatable'])->name('kategori.datatable');
    Route::resource('kategori', KategoriController::class)->except(['show']);

    Route::get('/program-studi/datatable', [ProgramStudiController::class, 'datatable'])->name('program-studi.datatable');
    Route::resource('program-studi', ProgramStudiController::class)->except(['show']);

    Route::get('/akreditasi/datatable', [AkreditasiController::class, 'datatable'])->name('akreditasi.datatable');
    Route::resource('akreditasi', AkreditasiController::class)->except(['show']);

    Route::get('/jadwal/datatable', [JadwalController::class, 'datatable'])->name('jadwal.datatable');
    Route::patch('/jadwal/{jadwal}/toggle-active', [JadwalController::class, 'toggleActive'])->name('jadwal.toggle-active');
    Route::resource('jadwal', JadwalController::class)->except(['show']);

    // Pedoman
    Route::get('/pedoman/datatable', [PedomanController::class, 'datatable'])->name('pedoman.datatable');
    Route::patch('/pedoman/{pedoman}/toggle-active', [PedomanController::class, 'toggleActive'])->name('pedoman.toggle-active');
    Route::resource('pedoman', PedomanController::class)->except(['show']);

    // Beasiswa
    Route::get('/beasiswa/datatable', [BeasiswaController::class, 'datatable'])->name('beasiswa.datatable');
    Route::resource('beasiswa', BeasiswaController::class)->except(['show']);

    // Kegiatan
    Route::get('/kegiatan/datatable', [KegiatanController::class, 'datatable'])->name('kegiatan.datatable');
    Route::resource('kegiatan', KegiatanController::class)->except(['show']);

    // Tipe Kegiatan (master referensi untuk filter publik)
    Route::get('/tipe-kegiatan/datatable', [TipeKegiatanController::class, 'datatable'])->name('tipe-kegiatan.datatable');
    Route::resource('tipe-kegiatan', TipeKegiatanController::class)->except(['show']);

  
    Route::prefix('tridharma/{type}')
        ->where(['type' => 'pengajaran|pengabdian'])
        ->name('tridharma.')
        ->group(function () {
            Route::get('/datatable', [AdminTridharmaController::class, 'datatable'])->name('datatable');
            Route::get('/', [AdminTridharmaController::class, 'index'])->name('index');
            Route::get('/create', [AdminTridharmaController::class, 'create'])->name('create');
            Route::post('/', [AdminTridharmaController::class, 'store'])->name('store');
            Route::get('/{berita}/edit', [AdminTridharmaController::class, 'edit'])->name('edit');
            Route::put('/{berita}', [AdminTridharmaController::class, 'update'])->name('update');
            Route::delete('/{berita}', [AdminTridharmaController::class, 'destroy'])->name('destroy');
        });

    // Kontak Jurusan (single-page edit)
    Route::get('/kontak', [KontakController::class, 'edit'])->name('kontak.edit');
    Route::put('/kontak', [KontakController::class, 'update'])->name('kontak.update');

    // Pesan Masuk (inbox)
    Route::get('/pesan/datatable', [KontakPesanController::class, 'datatable'])->name('pesan.datatable');
    Route::get('/pesan', [KontakPesanController::class, 'index'])->name('pesan.index');
    Route::get('/pesan/{pesan}', [KontakPesanController::class, 'show'])->name('pesan.show');
    Route::post('/pesan/{pesan}/mark-read', [KontakPesanController::class, 'markRead'])->name('pesan.mark-read');
    Route::delete('/pesan/{pesan}', [KontakPesanController::class, 'destroy'])->name('pesan.destroy');
});

/*
|--------------------------------------------------------------------------
| User Profile Routes (Breeze Default)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
