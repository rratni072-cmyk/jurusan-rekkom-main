@extends('layouts.frontend')
@section('title', 'Kegiatan')
@section('body_class', 'kemahasiswaan-page')
@section('meta_description', 'Dokumentasi kegiatan akademik dan non-akademik mahasiswa Jurusan Rekayasa dan Komputer Politeknik Pertanian Negeri Samarinda: workshop, seminar, lomba, dan kunjungan industri.')

@section('content')

    {{-- Page Title --}}
    <div class="page-title" data-aos="fade">
        <div class="heading">
            <div class="container">
                <div class="row d-flex justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1>Kegiatan</h1>
                        <p class="mb-0">Jurusan Rekayasa dan Komputer</p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><span class="breadcrumb-disabled">Kemahasiswaan</span></li>
                    <li class="current">Kegiatan</li>
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
                    {{-- Toolbar: Search + Filter Tipe + Filter Tahun --}}
                    @php
                        $hasFilter = request()->filled('q') || request()->filled('tipe') || request()->filled('tahun');
                    @endphp
                    <form method="GET" action="{{ route('kemahasiswaan.kegiatan') }}" class="page-toolbar mb-4" role="search" aria-label="Filter kegiatan">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label for="kegiatan-search" class="visually-hidden">Cari kegiatan</label>
                                <div class="input-group">
                                    <span class="input-group-text" aria-hidden="true">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="search"
                                           id="kegiatan-search"
                                           name="q"
                                           value="{{ request('q') }}"
                                           class="form-control"
                                           placeholder="Cari kegiatan…"
                                           autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="kegiatan-tipe" class="visually-hidden">Filter tipe</label>
                                <select id="kegiatan-tipe"
                                        name="tipe"
                                        class="form-select"
                                        onchange="this.form.submit()"
                                        aria-label="Filter tipe kegiatan">
                                    <option value="">Semua Tipe</option>
                                    @foreach($tipeList as $tipe)
                                        <option value="{{ $tipe->slug }}" @selected(request('tipe') === $tipe->slug)>{{ $tipe->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="kegiatan-tahun" class="visually-hidden">Filter tahun</label>
                                <select id="kegiatan-tahun"
                                        name="tahun"
                                        class="form-select"
                                        onchange="this.form.submit()"
                                        aria-label="Filter tahun kegiatan">
                                    <option value="">Semua Tahun</option>
                                    @foreach($tahunList as $tahun)
                                        <option value="{{ $tahun }}" @selected(request('tahun') == $tahun)>{{ $tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if($hasFilter)
                            <div class="page-toolbar-meta mt-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <small class="text-body-secondary">
                                    Menampilkan {{ $kegiatans->total() }} kegiatan
                                </small>
                                <a href="{{ route('kemahasiswaan.kegiatan') }}" class="page-toolbar-reset">
                                    <i class="bi bi-x-circle me-1" aria-hidden="true"></i>Reset filter
                                </a>
                            </div>
                        @endif
                    </form>

                    {{-- Grid: Compact Horizontal Cards --}}
                    <div class="event-grid">
                        @forelse($kegiatans as $kegiatan)
                            <article class="event-card position-relative">
                                <div class="event-thumb">
                                    @if($kegiatan->gambar)
                                        <img src="{{ asset('storage/'.$kegiatan->gambar) }}"
                                             alt="{{ $kegiatan->judul }}"
                                             loading="lazy">
                                    @else
                                        <div class="event-thumb-placeholder" aria-hidden="true">
                                            <i class="bi bi-calendar-event"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="event-body">
                                    @php($badge = $kegiatan->tipe_badge)
                                    <span class="event-tag">
                                        <i class="bi {{ $badge['icon'] }}" aria-hidden="true"></i>{{ $badge['label'] }}
                                    </span>
                                    <h2 class="event-title">
                                        <a href="{{ route('kemahasiswaan.kegiatan.show', $kegiatan->slug) }}"
                                           class="stretched-link"
                                           aria-label="Detail kegiatan: {{ $kegiatan->judul }}">{{ $kegiatan->judul }}</a>
                                    </h2>
                                    <p class="event-date">
                                        <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>@tanggal($kegiatan->tanggal, 'd F Y')
                                    </p>
                                    @if($kegiatan->ringkasan)
                                        <p class="event-excerpt">{{ Str::limit($kegiatan->ringkasan, 90) }}</p>
                                    @endif
                                </div>
                            </article>
                        @empty
                            <div class="event-empty">
                                <i class="bi bi-calendar-event" aria-hidden="true"></i>
                                @if($hasFilter)
                                    <p class="fw-semibold mb-1">Tidak ada kegiatan untuk filter ini.</p>
                                    <p class="small mb-2">Coba ubah kata kunci, tipe, atau tahun.</p>
                                    <a href="{{ route('kemahasiswaan.kegiatan') }}" class="btn btn-sm btn-outline-primary">Reset Filter</a>
                                @else
                                    <p class="fw-semibold mb-1">Belum ada data kegiatan.</p>
                                    <p class="small mb-0">Informasi kegiatan akan diumumkan secara berkala.</p>
                                @endif
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    @if($kegiatans->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $kegiatans->links() }}
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
