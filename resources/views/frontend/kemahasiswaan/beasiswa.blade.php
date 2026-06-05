@extends('layouts.frontend')
@section('title', 'Beasiswa')
@section('body_class', 'kemahasiswaan-page')
@section('meta_description', 'Informasi beasiswa untuk mahasiswa Jurusan Rekayasa dan Komputer Politeknik Pertanian Negeri Samarinda: KIP Kuliah, PPA, Bank Indonesia, dan beasiswa mitra industri.')

@section('content')

    {{-- Page Title --}}
    <div class="page-title" data-aos="fade">
        <div class="heading">
            <div class="container">
                <div class="row d-flex justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1>Beasiswa</h1>
                        <p class="mb-0">Informasi beasiswa untuk mahasiswa Jurusan Rekayasa dan Komputer</p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><span class="breadcrumb-disabled">Kemahasiswaan</span></li>
                    <li class="current">Beasiswa</li>
                </ol>
            </div>
        </nav>
    </div>

    {{-- Content --}}
    <section class="section">
        <div class="container" data-aos="fade-up">
            <div class="row">
                {{-- Konten Utama (70%) --}}
                <div class="col-lg-8">
                    <div class="beasiswa-grid">
                        @forelse($beasiswas as $beasiswa)
                            @php
                                $urlInfo = $beasiswa->url_info_lengkap;
                                $isExternal = (bool) $urlInfo;
                                $ariaLabel = $isExternal
                                    ? 'Kunjungi website resmi '.$beasiswa->nama.' (membuka tab baru)'
                                    : 'Hubungi bagian akademik untuk info '.$beasiswa->nama;
                            @endphp
                            <article class="beasiswa-card {{ $isExternal ? '' : 'is-internal' }}">
                                <div class="beasiswa-mark" aria-hidden="true">
                                    <i class="bi {{ $isExternal ? 'bi-mortarboard-fill' : 'bi-building' }}"></i>
                                </div>
                                <div class="beasiswa-body">
                                    <h2 class="beasiswa-title">{{ $beasiswa->nama }}</h2>

                                    @if($beasiswa->penyelenggara)
                                        <p class="beasiswa-penyelenggara">{{ $beasiswa->penyelenggara }}</p>
                                    @endif

                                    <p class="beasiswa-desc">
                                        {{ Str::limit(strip_tags((string) $beasiswa->deskripsi), 140) }}
                                    </p>

                                    @if($isExternal)
                                        <a href="{{ $urlInfo }}"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="beasiswa-cta"
                                           aria-label="{{ $ariaLabel }}">
                                            Kunjungi Website Resmi
                                            <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('kontak') }}"
                                           class="beasiswa-cta"
                                           aria-label="{{ $ariaLabel }}">
                                            Info di Bagian Akademik
                                            <i class="bi bi-arrow-right" aria-hidden="true"></i>
                                        </a>
                                    @endif
                                </div>
                            </article>
                        @empty
                            <div class="beasiswa-empty">
                                <i class="bi bi-mortarboard" aria-hidden="true"></i>
                                <p class="fw-semibold mb-1">Belum ada informasi beasiswa saat ini.</p>
                                <p class="small mb-0">Silakan hubungi bagian akademik untuk informasi terbaru.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    @if($beasiswas->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $beasiswas->links() }}
                        </div>
                    @endif
                </div>

                {{-- Sidebar (30%) --}}
                <div class="col-lg-4">
                    @include('components.frontend.sidebar-artikel')
                </div>
            </div>
        </div>
    </section>

@endsection
