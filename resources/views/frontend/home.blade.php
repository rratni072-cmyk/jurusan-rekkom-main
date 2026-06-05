{{--
|--------------------------------------------------------------------------
| Halaman Beranda (Home)
|--------------------------------------------------------------------------
| Layout: Hero → Featured → Stats Counter → Program Studi → Berita → CTA
| Referensi desain: TIK PNJ + Eterna template
|--------------------------------------------------------------------------
--}}
@extends('layouts.frontend')

@section('title', 'Beranda')
@section('meta_description', 'Jurusan Rekayasa dan Komputer, Politeknik Pertanian Negeri Samarinda. 4 program studi: TG, SIA (D3) dan TRPL, TRGS (D4) dengan akreditasi B & Baik Sekali.')
@section('meta_keywords', 'Jurusan Rekayasa Komputer, Politani Samarinda, TRPL, TRGS, Teknologi Geomatika, kuliah Samarinda, Kalimantan Timur')
@section('body_class', 'index-page')

@section('content')

    {{-- H1 untuk SEO (sr-only) — topik halaman beranda.
         Visual pakai carousel hero di bawah; h1 ini khusus untuk screen reader & search engine. --}}
    <h1 class="visually-hidden">Jurusan Rekayasa dan Komputer — Politeknik Pertanian Negeri Samarinda</h1>

    {{-- ==================== HERO SECTION ==================== --}}
    <section id="hero" class="hero section">
        <div id="hero-carousel" class="carousel slide carousel-fade"
             data-bs-ride="carousel"
             data-bs-interval="5000"
             data-bs-pause="false"
             data-bs-wrap="true"
             data-bs-touch="true">

            @forelse($sliders as $index => $slider)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <img src="{{ asset('storage/' . $slider->gambar) }}" alt="{{ $slider->judul }}">
                    <div class="carousel-container">
                        <h2>{{ $slider->judul }}</h2>
                        <p>{{ $slider->deskripsi }}</p>
                        @if($slider->tombol_teks && $slider->tombol_url)
                            <a href="{{ $slider->tombol_url }}" class="btn-get-started">{{ $slider->tombol_teks }}</a>
                        @endif
                    </div>
                </div>
            @empty
                {{-- Fallback 3 static slides --}}
                <div class="carousel-item active">
                    <img src="{{ asset('frontend/img/hero-carousel/hero-carousel-1.jpg') }}" alt="Kampus Politani Samarinda">
                    <div class="carousel-container">
                        <h2>Selamat Datang di <span>Jurusan Rekayasa dan Komputer</span></h2>
                        <p>Mencetak tenaga ahli di bidang teknologi rekayasa dan komputer yang kompeten dan berdaya saing global.</p>
                        <a href="#prodi" class="btn-get-started">Program Studi</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('frontend/img/hero-carousel/hero-carousel-2.jpg') }}" alt="Laboratorium">
                    <div class="carousel-container">
                        <h2>Fasilitas <span>Modern & Lengkap</span></h2>
                        <p>Laboratorium komputer, jaringan, dan multimedia terkini untuk mendukung pembelajaran praktis.</p>
                        <a href="{{ route('profil.tentang') }}" class="btn-get-started">Tentang Jurusan</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('frontend/img/hero-carousel/hero-carousel-3.jpg') }}" alt="Wisuda">
                    <div class="carousel-container">
                        <h2>Raih <span>Masa Depan Cerah</span></h2>
                        <p>Bergabunglah dengan ribuan alumni sukses di industri teknologi informasi dan komputer.</p>
                        <a href="{{ route('berita.index') }}" class="btn-get-started">Berita Terkini</a>
                    </div>
                </div>
            @endforelse

            <a class="carousel-control-prev" href="#hero-carousel" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
            </a>
            <a class="carousel-control-next" href="#hero-carousel" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
            </a>
        </div>

        {{-- Featured Highlights --}}
        <div class="featured container">
            <div class="row gy-4">
                <div class="col-lg-4 d-flex" data-aos="fade-up" data-aos-delay="100">
                    <div class="featured-item position-relative">
                        <div class="icon"><i class="bi bi-mortarboard icon"></i></div>
                        <h4><a href="{{ route('profil.akreditasi') }}" class="stretched-link">Program Studi</a></h4>
                        <p>{{ $prodiList->count() }} program studi yang dirancang untuk mencetak tenaga ahli di bidang rekayasa komputer.</p>
                    </div>
                </div>
                <div class="col-lg-4 d-flex" data-aos="fade-up" data-aos-delay="200">
                    <div class="featured-item position-relative">
                        <div class="icon"><i class="bi bi-people icon"></i></div>
                        <h4><a href="{{ route('kemahasiswaan.kegiatan') }}" class="stretched-link">Kemahasiswaan</a></h4>
                        <p>Kegiatan organisasi, prestasi mahasiswa, dan pengembangan soft skill di Jurusan Rekayasa dan Komputer.</p>
                    </div>
                </div>
                <div class="col-lg-4 d-flex" data-aos="fade-up" data-aos-delay="300">
                    <div class="featured-item position-relative">
                        <div class="icon"><i class="bi bi-pc-display icon"></i></div>
                        <h4><a href="{{ route('berita.index') }}" class="stretched-link">Berita Terkini</a></h4>
                        <p>Berita dan informasi terbaru seputar kegiatan Jurusan Rekayasa dan Komputer.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== PROGRAM STUDI SECTION ==================== --}}
    <section id="prodi" class="section prodi-section">
        <div class="container section-title" data-aos="fade-up">
            <h2>Program Studi</h2>
            <p>Program pendidikan vokasi terakreditasi yang mencetak lulusan siap kerja di industri teknologi & rekayasa.</p>
        </div>

        <div class="container" data-aos="fade-up" data-aos-delay="100">
            @if($prodiList->isNotEmpty())
                <div class="row g-4 justify-content-center">
                    @foreach($prodiList as $prodi)
                        @php
                            // Card prodi sekarang fokus akreditasi: klik selalu ke halaman
                            // akreditasi dengan anchor ke baris prodi yang dipilih.
                            $cardHref = route('profil.akreditasi') . '#prodi-' . \Illuminate\Support\Str::slug($prodi->nama);
                        @endphp

                        <div class="col-lg-4 col-md-6">
                            <a href="{{ $cardHref }}"
                               class="prodi-card"
                               aria-label="Lihat akreditasi {{ $prodi->nama }}">
                                <div class="prodi-card-img prodi-card-tile" style="background: {{ $prodi->getTematikGradient() }};">
                                    <i class="bi bi-{{ $prodi->getTematikIcon() }} prodi-tile-icon" aria-hidden="true"></i>
                                    @if($prodi->jenjang)
                                        <span class="prodi-jenjang-badge">{{ $prodi->jenjang }}</span>
                                    @endif
                                </div>

                                <div class="prodi-card-body">
                                    <h3 class="prodi-card-title">{{ $prodi->nama }}</h3>

                                    @if($prodi->akreditasi)
                                        <span class="prodi-akreditasi {{ $prodi->getAkreditasiBadgeClass() }}">
                                            <i class="bi bi-award-fill"></i>
                                            Akreditasi: {{ $prodi->akreditasi }}
                                        </span>
                                    @endif

                                    <span class="prodi-card-cta">
                                        <i class="bi bi-arrow-right-circle"></i> Lihat Detail Akreditasi
                                    </span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-mortarboard text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3 mb-0">Belum ada data program studi.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- ==================== BERITA SECTION ==================== --}}
    @include('frontend.partials.berita._section', ['beritas' => $beritas])

    {{-- ==================== CTA POLITANI SECTION ==================== --}}
    <section id="cta" class="section cta-politani">
        <div class="container" data-aos="fade-up">
            <div class="row align-items-center gy-4">
                <div class="col-lg-4 text-center">
                    <img src="{{ asset('frontend/img/logo-politani.png') }}" class="logo-politani"
                        alt="Logo Politani Samarinda" loading="lazy" decoding="async">
                </div>
                <div class="col-lg-8">
                    <h2>POLITANI SAMARINDA<br>KAMPUS YANG BANYAK PRESTASINYA!</h2>
                    <p class="mt-3 mb-4">Ayo gabung bersama kami di Politeknik Pertanian Negeri Samarinda.
                        Raih masa depan cerah dengan pendidikan vokasi berkualitas yang menghasilkan lulusan siap kerja.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="https://pmb.politanisamarinda.ac.id/" target="_blank" class="btn-cta-primary">
                            <i class="bi bi-mortarboard"></i> Daftar Sekarang
                        </a>
                        <a href="{{ route('profil.tentang') }}" class="btn-cta">
                            Tentang Jurusan <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
