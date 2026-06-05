<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ProfilJurusan;
use App\Models\ProgramStudi;

/**
 * Controller untuk halaman profil jurusan publik.
 *
 * Menampilkan tentang jurusan, visi/misi, struktur organisasi, dan akreditasi.
 */
class ProfilController extends Controller
{
    /**
     * Halaman Tentang Jurusan R&K.
     */
    public function tentang()
    {
        $tentang = ProfilJurusan::where('kunci', 'tentang_jurusan')->first();

        return view('frontend.profil.tentang', compact('tentang'));
    }

    /**
     * Halaman Visi & Misi.
     */
    public function visiMisi()
    {
        $visiMisi = ProfilJurusan::where('kunci', 'visi_misi')->first();

        return view('frontend.profil.visi-misi', compact('visiMisi'));
    }

    /**
     * Halaman Struktur Organisasi.
     */
    public function struktur()
    {
        $struktur = ProfilJurusan::where('kunci', 'struktur_organisasi')->first();

        return view('frontend.profil.struktur', compact('struktur'));
    }

    /**
     * Halaman Akreditasi Program Studi.
     */
    public function akreditasi()
    {
        $programStudi = ProgramStudi::orderBy('nama')->get();

        return view('frontend.profil.akreditasi', compact('programStudi'));
    }
}
