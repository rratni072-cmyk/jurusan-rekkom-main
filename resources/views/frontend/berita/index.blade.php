@extends('layouts.frontend')
@section('title', 'Berita')
@section('body_class', 'berita-page')
@section('meta_description', 'Berita dan informasi terbaru Jurusan Rekayasa dan Komputer Politeknik Pertanian Negeri Samarinda: kegiatan akademik, prestasi mahasiswa, kerjasama industri, dan pengumuman jurusan.')

{{--
|--------------------------------------------------------------------------
| Halaman Daftar Berita — Editorial Magazine Pattern
|--------------------------------------------------------------------------
| Layout 2-kolom (8/4) seperti tridharma/pengajaran & kemahasiswaan/kegiatan.
| Main: toolbar filter + horizontal card list (berita-index-card).
| Sidebar: widget kategori (clickable filter) + berita terkini.
|--------------------------------------------------------------------------
--}}

@section('content')

    <div class="page-title" data-aos="fade">
        <div class="heading">
            <div class="container">
                <div class="row d-flex justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1>Berita &amp; Informasi</h1>
                        <p class="mb-0">Berita terbaru seputar Jurusan Rekayasa dan Komputer</p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li class="current">Berita</li>
                </ol>
            </div>
        </nav>
    </div>

    <section class="section">
        <div class="container" data-aos="fade-up">
            @php
                $hasFilter = $filters['search'] !== '' || $filters['kategori'] !== '' || ($filters['sort'] ?? 'terbaru') !== 'terbaru';
                $sortValue = $filters['sort'] ?? 'terbaru';
            @endphp

            <div class="row gy-4">
                {{-- ============================================================
                     Main content (col-lg-8) — toolbar + list + pagination
                     ============================================================ --}}
                <div class="col-lg-8">

                    {{-- Toolbar: Search + Sort + Reset (selaras dgn pengajaran/kegiatan) --}}
                    <form method="GET" action="{{ route('berita.index') }}" class="page-toolbar mb-4" role="search" aria-label="Filter berita">
                        <input type="hidden" name="kategori" value="{{ $filters['kategori'] }}">
                        <div class="row g-2">
                            <div class="col-md-8">
                                <label for="berita-search" class="visually-hidden">Cari berita</label>
                                <div class="input-group">
                                    <span class="input-group-text" aria-hidden="true">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="search"
                                           id="berita-search"
                                           name="search"
                                           value="{{ $filters['search'] }}"
                                           class="form-control"
                                           placeholder="Cari berita berdasarkan judul atau ringkasan…"
                                           autocomplete="off"
                                           aria-label="Kata kunci pencarian berita">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="berita-sort" class="visually-hidden">Urutkan</label>
                                <select id="berita-sort"
                                        name="sort"
                                        class="form-select"
                                        onchange="this.form.submit()"
                                        aria-label="Urutkan berita">
                                    <option value="terbaru" @selected($sortValue === 'terbaru')>Urutkan: Terbaru</option>
                                    <option value="terlama" @selected($sortValue === 'terlama')>Urutkan: Terlama</option>
                                    <option value="judul" @selected($sortValue === 'judul')>Urutkan: A–Z</option>
                                    <option value="populer" @selected($sortValue === 'populer')>Urutkan: Terpopuler</option>
                                </select>
                            </div>
                        </div>

                        {{-- Info bar: filter aktif + total + reset --}}
                        @if($hasFilter || $kategoriActive)
                            <div class="page-toolbar-meta mt-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <small class="text-body-secondary">
                                    @if($kategoriActive)
                                        Kategori: <strong>{{ $kategoriActive->nama }}</strong> •
                                    @endif
                                    @if($filters['search'])
                                        Pencarian: <strong>"{{ $filters['search'] }}"</strong> •
                                    @endif
                                    Menampilkan {{ $beritas->total() }} berita
                                </small>
                                <a href="{{ route('berita.index') }}" class="page-toolbar-reset">
                                    <i class="bi bi-x-circle me-1" aria-hidden="true"></i>Reset filter
                                </a>
                            </div>
                        @endif
                    </form>

                    {{-- News list (horizontal berita-index-card) --}}
                    <div class="berita-index-list">
                        @forelse($beritas as $berita)
                            @include('frontend.partials.berita._index-card', ['berita' => $berita])
                        @empty
                            <div class="berita-index-empty">
                                <i class="bi bi-newspaper" aria-hidden="true"></i>
                                @if($hasFilter || $kategoriActive)
                                    <p class="fw-semibold mb-1">Tidak ada berita untuk filter ini.</p>
                                    <p class="small mb-2">Coba ubah kata kunci, kategori, atau urutan.</p>
                                    <a href="{{ route('berita.index') }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Reset filter
                                    </a>
                                @else
                                    <p class="fw-semibold mb-1">Belum ada berita yang dipublikasikan.</p>
                                    <p class="small mb-0">Berita akan dipublikasikan secara berkala oleh tim redaksi.</p>
                                @endif
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    @if($beritas->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $beritas->links() }}
                        </div>
                    @endif
                </div>

                {{-- ============================================================
                     Sidebar (col-lg-4) — Kategori filter + Berita Terkini
                     ============================================================ --}}
                <div class="col-lg-4">

                    {{-- Widget Kategori — CLICKABLE filter dgn active state --}}
                    <div class="sidebar-section">
                        <h4 class="sidebar-heading">Kategori</h4>
                        <ul class="sidebar-category-list">
                            @php
                                $baseParams = array_filter([
                                    'search' => $filters['search'] ?: null,
                                    'sort' => ($filters['sort'] ?? 'terbaru') !== 'terbaru' ? $filters['sort'] : null,
                                ]);
                            @endphp
                            <li>
                                <a href="{{ route('berita.index', $baseParams) }}"
                                   class="sidebar-category-item @if(!$kategoriActive) sidebar-category-item--active @endif">
                                    <span>Semua Berita</span>
                                    <span class="sidebar-category-count">{{ $totalPublished }}</span>
                                </a>
                            </li>
                            @forelse($kategoris as $kategori)
                                <li>
                                    <a href="{{ route('berita.index', array_merge($baseParams, ['kategori' => $kategori->slug])) }}"
                                       class="sidebar-category-item @if($kategoriActive && $kategoriActive->id === $kategori->id) sidebar-category-item--active @endif">
                                        <span>{{ $kategori->nama }}</span>
                                        <span class="sidebar-category-count">{{ $kategori->beritas_count }}</span>
                                    </a>
                                </li>
                            @empty
                                <li class="sidebar-category-empty text-muted small px-2 py-1">Belum ada kategori</li>
                            @endforelse
                        </ul>
                    </div>

                    {{-- Widget Tautan Cepat — utility links ke modul terkait
                         (TIDAK include sidebar-artikel: duplikat dgn main list yg
                         memang sudah menampilkan berita terkini secara default). --}}
                    <div class="sidebar-section">
                        <h4 class="sidebar-heading">Tautan Cepat</h4>
                        <ul class="sidebar-quicklinks">
                            <li>
                                <a href="{{ route('kemahasiswaan.beasiswa') }}" class="sidebar-quicklink">
                                    <span class="sidebar-quicklink-icon" aria-hidden="true">
                                        <i class="bi bi-cash-coin"></i>
                                    </span>
                                    <span class="sidebar-quicklink-text">
                                        <strong>Beasiswa</strong>
                                        <small>Pendanaan studi mahasiswa aktif</small>
                                    </span>
                                    <i class="bi bi-arrow-right sidebar-quicklink-arrow" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('kemahasiswaan.kegiatan') }}" class="sidebar-quicklink">
                                    <span class="sidebar-quicklink-icon" aria-hidden="true">
                                        <i class="bi bi-calendar-event"></i>
                                    </span>
                                    <span class="sidebar-quicklink-text">
                                        <strong>Kegiatan Mahasiswa</strong>
                                        <small>Agenda &amp; dokumentasi kegiatan</small>
                                    </span>
                                    <i class="bi bi-arrow-right sidebar-quicklink-arrow" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('tridharma.pengabdian') }}" class="sidebar-quicklink">
                                    <span class="sidebar-quicklink-icon" aria-hidden="true">
                                        <i class="bi bi-people-fill"></i>
                                    </span>
                                    <span class="sidebar-quicklink-text">
                                        <strong>Pengabdian Masyarakat</strong>
                                        <small>Kontribusi tridharma jurusan</small>
                                    </span>
                                    <i class="bi bi-arrow-right sidebar-quicklink-arrow" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('kemahasiswaan.pedoman') }}" class="sidebar-quicklink">
                                    <span class="sidebar-quicklink-icon" aria-hidden="true">
                                        <i class="bi bi-journal-bookmark-fill"></i>
                                    </span>
                                    <span class="sidebar-quicklink-text">
                                        <strong>Pedoman Akademik</strong>
                                        <small>Panduan resmi mahasiswa</small>
                                    </span>
                                    <i class="bi bi-arrow-right sidebar-quicklink-arrow" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('kontak') }}" class="sidebar-quicklink">
                                    <span class="sidebar-quicklink-icon" aria-hidden="true">
                                        <i class="bi bi-envelope-fill"></i>
                                    </span>
                                    <span class="sidebar-quicklink-text">
                                        <strong>Kontak Redaksi</strong>
                                        <small>Saran &amp; informasi berita</small>
                                    </span>
                                    <i class="bi bi-arrow-right sidebar-quicklink-arrow" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
