@extends('layouts.frontend')
@section('title', 'Pengabdian Masyarakat')
@section('body_class', 'tridharma-page')
@section('meta_description', 'Pengabdian masyarakat Jurusan Rekayasa dan Komputer Politeknik Pertanian Negeri Samarinda: pelatihan teknologi, pendampingan UMKM, dan kontribusi sosial dosen serta mahasiswa.')

@section('content')

    {{-- Page Title --}}
    <div class="page-title" data-aos="fade">
        <div class="heading">
            <div class="container">
                <div class="row d-flex justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1>Pengabdian Masyarakat</h1>
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
                    <li class="current">Pengabdian Masyarakat</li>
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
                    {{-- Toolbar: 2-row (search + 3 filter) --}}
                    @php
                        $hasFilter = request()->filled('q') || request()->filled('prodi') || request()->filled('tahun') || request()->filled('sort');
                        $sortValue = request('sort', 'terbaru');
                    @endphp
                    <form method="GET" action="{{ route('tridharma.pengabdian') }}" class="page-toolbar mb-4" role="search" aria-label="Filter pengabdian masyarakat">
                        <div class="row g-2">
                            <div class="col-12">
                                <label for="pengabdian-search" class="visually-hidden">Cari pengabdian masyarakat</label>
                                <div class="input-group">
                                    <span class="input-group-text" aria-hidden="true">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="search"
                                           id="pengabdian-search"
                                           name="q"
                                           value="{{ request('q') }}"
                                           class="form-control"
                                           placeholder="Cari pengabdian masyarakat…"
                                           autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 mt-2">
                            <div class="col-md-4">
                                <label for="pengabdian-prodi" class="visually-hidden">Filter program studi</label>
                                <select id="pengabdian-prodi"
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
                            <div class="col-md-4">
                                <label for="pengabdian-tahun" class="visually-hidden">Filter tahun</label>
                                <select id="pengabdian-tahun"
                                        name="tahun"
                                        class="form-select"
                                        onchange="this.form.submit()"
                                        aria-label="Filter tahun">
                                    <option value="">Semua Tahun</option>
                                    @foreach($tahunList as $tahun)
                                        <option value="{{ $tahun }}" @selected(request('tahun') == $tahun)>{{ $tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="pengabdian-sort" class="visually-hidden">Urutkan</label>
                                <select id="pengabdian-sort"
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
                                    Menampilkan {{ $artikels->total() }} pengabdian
                                </small>
                                <a href="{{ route('tridharma.pengabdian') }}" class="page-toolbar-reset">
                                    <i class="bi bi-x-circle me-1" aria-hidden="true"></i>Reset filter
                                </a>
                            </div>
                        @endif
                    </form>

                    {{-- Community list (visual storytelling) --}}
                    <div class="community-list">
                        @forelse($artikels as $artikel)
                            <article class="community-card">
                                <div class="community-image">
                                    @if($artikel->lokasi)
                                        <span class="community-location">
                                            <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>{{ $artikel->lokasi }}
                                        </span>
                                    @endif
                                    @if($artikel->gambar)
                                        <img src="{{ asset('storage/'.$artikel->gambar) }}"
                                             alt="{{ $artikel->judul }}"
                                             loading="lazy">
                                    @else
                                        <div class="community-image-placeholder" aria-hidden="true">
                                            <i class="bi bi-people"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="community-body">
                                    <div class="community-tags">
                                        <span class="community-tag community-tag--prodi">
                                            <i class="bi bi-mortarboard-fill" aria-hidden="true"></i>{{ $artikel->prodi_badge_label }}
                                        </span>
                                        @foreach($artikel->kategoris->take(2) as $kategori)
                                            @if(! str_contains(strtolower($kategori->nama), 'pengabdian'))
                                                <span class="community-tag">{{ $kategori->nama }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                    <h2 class="community-title">
                                        <a href="{{ route('berita.show', $artikel->slug) }}">{{ $artikel->judul }}</a>
                                    </h2>
                                    @if($artikel->ringkasan)
                                        <p class="community-excerpt">{{ Str::limit($artikel->ringkasan, 220) }}</p>
                                    @endif
                                    <p class="community-meta">
                                        @if($artikel->penulis)
                                            <span class="community-meta-item author"><i class="bi bi-person-fill" aria-hidden="true"></i>{{ $artikel->penulis->name }}</span>
                                        @endif
                                        <span class="community-meta-item"><i class="bi bi-calendar3" aria-hidden="true"></i>@tanggal($artikel->tanggal_publikasi, 'd F Y')</span>
                                    </p>
                                    <a href="{{ route('berita.show', $artikel->slug) }}" class="community-cta">
                                        Baca Selengkapnya <i class="bi bi-arrow-right" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </article>
                        @empty
                            <div class="community-empty">
                                <i class="bi bi-people" aria-hidden="true"></i>
                                @if($hasFilter)
                                    <p class="fw-semibold mb-1">Tidak ada pengabdian untuk filter ini.</p>
                                    <p class="small mb-2">Coba ubah kata kunci, prodi, tahun, atau urutan.</p>
                                    <a href="{{ route('tridharma.pengabdian') }}" class="btn btn-sm btn-outline-primary">Reset Filter</a>
                                @else
                                    <p class="fw-semibold mb-1">Belum ada data pengabdian masyarakat.</p>
                                    <p class="small mb-0">Dokumentasi pengabdian akan dipublikasikan setelah kegiatan terlaksana.</p>
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
