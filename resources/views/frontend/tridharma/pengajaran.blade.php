@extends('layouts.frontend')
@section('title', 'Pengajaran')
@section('body_class', 'tridharma-page')
@section('meta_description', 'Aktivitas pengajaran dan pembelajaran Jurusan Rekayasa dan Komputer Politeknik Pertanian Negeri Samarinda: kurikulum, metode pembelajaran, praktikum, dan capaian pembelajaran lulusan.')

@section('content')

    {{-- Page Title --}}
    <div class="page-title" data-aos="fade">
        <div class="heading">
            <div class="container">
                <div class="row d-flex justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1>Pengajaran</h1>
                        <p class="mb-0">Tridharma Perguruan Tinggi</p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><span class="breadcrumb-disabled">Tridharma</span></li>
                    <li class="current">Pengajaran</li>
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
                    {{-- Toolbar: Search + Filter Prodi + Sort --}}
                    @php
                        $hasFilter = request()->filled('q') || request()->filled('prodi') || request()->filled('sort');
                        $sortValue = request('sort', 'terbaru');
                    @endphp
                    <form method="GET" action="{{ route('tridharma.pengajaran') }}" class="page-toolbar mb-4" role="search" aria-label="Filter artikel pengajaran">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label for="pengajaran-search" class="visually-hidden">Cari artikel pengajaran</label>
                                <div class="input-group">
                                    <span class="input-group-text" aria-hidden="true">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="search"
                                           id="pengajaran-search"
                                           name="q"
                                           value="{{ request('q') }}"
                                           class="form-control"
                                           placeholder="Cari artikel pengajaran…"
                                           autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="pengajaran-prodi" class="visually-hidden">Filter program studi</label>
                                <select id="pengajaran-prodi"
                                        name="prodi"
                                        class="form-select"
                                        onchange="this.form.submit()"
                                        aria-label="Filter program studi">
                                    <option value="">Semua Prodi</option>
                                    @foreach($prodiList as $prodi)
                                        <option value="{{ $prodi->id }}" @selected(request('prodi') == $prodi->id)>{{ $prodi->jenjang }} {{ $prodi->nama }}</option>
                                    @endforeach
                                    <option value="lintas" @selected(request('prodi') === 'lintas')>Lintas Jurusan</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="pengajaran-sort" class="visually-hidden">Urutkan</label>
                                <select id="pengajaran-sort"
                                        name="sort"
                                        class="form-select"
                                        onchange="this.form.submit()"
                                        aria-label="Urutkan artikel">
                                    <option value="terbaru" @selected($sortValue === 'terbaru')>Urutkan: Terbaru</option>
                                    <option value="terlama" @selected($sortValue === 'terlama')>Urutkan: Terlama</option>
                                    <option value="judul" @selected($sortValue === 'judul')>Urutkan: A–Z</option>
                                </select>
                            </div>
                        </div>
                        @if($hasFilter)
                            <div class="page-toolbar-meta mt-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <small class="text-body-secondary">
                                    Menampilkan {{ $artikels->total() }} artikel
                                </small>
                                <a href="{{ route('tridharma.pengajaran') }}" class="page-toolbar-reset">
                                    <i class="bi bi-x-circle me-1" aria-hidden="true"></i>Reset filter
                                </a>
                            </div>
                        @endif
                    </form>

                    {{-- Teaching list (horizontal compact cards) --}}
                    <div class="teaching-list">
                        @forelse($artikels as $artikel)
                            <article class="teaching-card">
                                <a href="{{ route('berita.show', $artikel->slug) }}" class="teaching-image" aria-label="Buka artikel: {{ $artikel->judul }}">
                                    @if($artikel->gambar)
                                        <img src="{{ asset('storage/'.$artikel->gambar) }}"
                                             alt="{{ $artikel->judul }}"
                                             loading="lazy">
                                    @else
                                        <span class="teaching-image-placeholder" aria-hidden="true">
                                            <i class="bi bi-journal-bookmark"></i>
                                        </span>
                                    @endif
                                </a>
                                <div class="teaching-body">
                                    <div class="teaching-tags">
                                        <span class="teaching-tag teaching-tag--prodi">
                                            <i class="bi bi-mortarboard-fill" aria-hidden="true"></i>{{ $artikel->prodi_badge_label }}
                                        </span>
                                        @foreach($artikel->kategoris->take(1) as $kategori)
                                            <span class="teaching-tag">{{ $kategori->nama }}</span>
                                        @endforeach
                                    </div>
                                    <h2 class="teaching-title">
                                        <a href="{{ route('berita.show', $artikel->slug) }}">{{ $artikel->judul }}</a>
                                    </h2>
                                    <p class="teaching-meta">
                                        @if($artikel->penulis)
                                            <span class="teaching-meta-item author"><i class="bi bi-person-fill" aria-hidden="true"></i>{{ $artikel->penulis->name }}</span>
                                        @endif
                                        <span class="teaching-meta-item"><i class="bi bi-calendar3" aria-hidden="true"></i>@tanggal($artikel->tanggal_publikasi, 'd F Y')</span>
                                    </p>
                                    @if($artikel->ringkasan)
                                        <p class="teaching-excerpt">{{ Str::limit($artikel->ringkasan, 160) }}</p>
                                    @endif
                                    <a href="{{ route('berita.show', $artikel->slug) }}" class="teaching-cta">
                                        Lihat Selengkapnya <i class="bi bi-arrow-right" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </article>
                        @empty
                            <div class="teaching-empty">
                                <i class="bi bi-journal-bookmark" aria-hidden="true"></i>
                                @if($hasFilter)
                                    <p class="fw-semibold mb-1">Tidak ada artikel untuk filter ini.</p>
                                    <p class="small mb-2">Coba ubah kata kunci, prodi, atau urutan.</p>
                                    <a href="{{ route('tridharma.pengajaran') }}" class="btn btn-sm btn-outline-primary">Reset Filter</a>
                                @else
                                    <p class="fw-semibold mb-1">Belum ada artikel pengajaran.</p>
                                    <p class="small mb-0">Artikel pengajaran akan dipublikasikan secara berkala oleh tim dosen.</p>
                                @endif
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    @if($artikels->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $artikels->links() }}
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
