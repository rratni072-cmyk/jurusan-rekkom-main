@extends('layouts.frontend')
@section('title', 'Pedoman Akademik')
@section('body_class', 'kemahasiswaan-page pedoman-page')
@section('meta_description', 'Unduh pedoman akademik Jurusan Rekayasa dan Komputer Politeknik Pertanian Negeri Samarinda: pedoman tugas akhir, skripsi, magang, SKPI, dan wisuda.')

@push('styles')
<style>
    /* ============================================================
       PEDOMAN PAGE — TABEL RESPONSIVE HYBRID
       Mirror pattern halaman Jadwal:
       - Desktop (≥768px): tabel klasik dengan badge & icon
       - Mobile (<768px): collapse jadi card list
       Mobile-first + touch ≥44px + hover wrapped + reduced-motion
       ============================================================ */

    /* Section padding — reduce di mobile (Eterna default 80px terlalu tebal) */
    .pedoman-page .section { padding: 40px 0; }
    @media (min-width: 768px) { .pedoman-page .section { padding: 60px 0; } }
    @media (min-width: 992px) { .pedoman-page .section { padding: 80px 0; } }

    /* ===== Toolbar (filter + search) — Opsi D: stack <992px, side-by-side ≥992px ===== */
    .pedoman-toolbar {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }
    /* Mobile & tablet (<992px): search DI ATAS, pills DI BAWAH full-width */
    .pedoman-search-wrapper { order: 1; }
    .pedoman-filters-wrapper { order: 2; }

    @media (min-width: 992px) {
        .pedoman-toolbar {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        /* Desktop: pills kiri, search kanan (order balik) */
        .pedoman-filters-wrapper { order: 1; }
        .pedoman-search-wrapper  { order: 2; }
    }

    /* ===== Filter Pills — horizontal scroll + fade hint + scroll buttons + drag-to-scroll ===== */
    .pedoman-filters-wrapper {
        position: relative;
        min-width: 0;
    }
    /* Fade gradient kiri & kanan — opacity 0 default, toggle via has-prev/has-next */
    .pedoman-filters-wrapper::before,
    .pedoman-filters-wrapper::after {
        content: '';
        position: absolute;
        top: 0;
        bottom: 6px;
        width: 48px;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.2s ease;
        z-index: 1;
    }
    .pedoman-filters-wrapper::before {
        left: 0;
        background: linear-gradient(to right, #fff 40%, rgba(255,255,255,0));
    }
    .pedoman-filters-wrapper::after {
        right: 0;
        background: linear-gradient(to left, #fff 40%, rgba(255,255,255,0));
    }
    .pedoman-filters-wrapper.has-prev::before { opacity: 1; }
    .pedoman-filters-wrapper.has-next::after  { opacity: 1; }

    /* Scroll buttons (prev + next) — clickable chevron, auto-show saat ada overflow */
    .pedoman-scroll-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 32px;
        height: 32px;
        padding: 0;
        border: 1px solid var(--bs-border-color, #dee2e6);
        border-radius: 50%;
        background: #fff;
        color: var(--rk-primary, #1a2035);
        display: none;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        cursor: pointer;
        z-index: 2;
        font-size: 1rem;
        transition: background-color 0.15s ease, color 0.15s ease, transform 0.15s ease;
    }
    .pedoman-scroll-btn.visible { display: inline-flex; }
    .pedoman-scroll-prev { left: -6px; }
    .pedoman-scroll-next { right: -6px; }

    @media (hover: hover) {
        .pedoman-scroll-btn:hover {
            background: var(--rk-primary, #1a2035);
            color: #fff;
            border-color: var(--rk-primary, #1a2035);
            transform: translateY(-50%) scale(1.08);
        }
    }
    .pedoman-scroll-btn:focus-visible {
        outline: 0;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.35);
    }

    .pedoman-filters {
        display: flex;
        flex-wrap: nowrap;
        gap: 0.5rem;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        padding: 2px 0 6px;
        scroll-snap-type: x proximity;
        scroll-behavior: smooth;
        /* Drag-to-scroll: cursor grab di desktop, normal di touch device */
        cursor: grab;
        user-select: none;
        -webkit-user-select: none;
    }
    .pedoman-filters::-webkit-scrollbar { display: none; }
    .pedoman-filters > * { scroll-snap-align: start; }

    /* State saat drag — matikan smooth scroll & scroll-snap supaya drag instan responsif */
    .pedoman-filters.is-dragging {
        cursor: grabbing;
        scroll-behavior: auto;
        scroll-snap-type: none;
    }

    /* Touch device: cursor grab tidak relevan (native swipe scroll) */
    @media (hover: none) {
        .pedoman-filters { cursor: default; }
    }

    .pedoman-filter-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        min-height: 44px;          /* WCAG touch target */
        padding: 0.55rem 1rem;
        border: 1px solid var(--bs-border-color, #dee2e6);
        border-radius: 999px;
        background: #fff;
        color: var(--bs-body-color, #212529);
        font-size: 0.875rem;
        font-weight: 500;
        white-space: nowrap;
        cursor: pointer;
        transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
    }
    @media (hover: hover) {
        .pedoman-filter-pill:hover {
            border-color: var(--rk-accent, #0d6efd);
            color: var(--rk-accent, #0d6efd);
        }
    }
    .pedoman-filter-pill:focus-visible {
        outline: 0;
        border-color: var(--rk-accent, #0d6efd);
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
    }
    .pedoman-filter-pill.is-active {
        background: var(--rk-primary, #1a2035);
        border-color: var(--rk-primary, #1a2035);
        color: #fff;
    }
    .pedoman-filter-pill .count {
        display: inline-block;
        min-width: 22px;
        padding: 1px 7px;
        border-radius: 10px;
        background: rgba(0, 0, 0, 0.08);
        font-size: 0.72rem;
        font-weight: 700;
        line-height: 18px;
        text-align: center;
    }
    .pedoman-filter-pill.is-active .count {
        background: rgba(255, 255, 255, 0.25);
        color: #fff;
    }

    /* ===== Search Input ===== */
    .pedoman-search-wrapper { width: 100%; }
    @media (min-width: 992px) {
        .pedoman-search-wrapper { flex: 0 1 320px; max-width: 320px; }
    }
    .pedoman-search-wrapper .input-group-text {
        background: #fff;
        border-right: 0;
        color: var(--bs-secondary-color, #6c757d);
    }
    .pedoman-search-wrapper .form-control {
        border-left: 0;
        padding-left: 0;
        font-size: 1rem;            /* 16px — cegah iOS auto-zoom */
        min-height: 44px;
    }
    .pedoman-search-wrapper .form-control:focus {
        box-shadow: none;
        border-color: var(--rk-accent, #0d6efd);
    }
    .pedoman-search-wrapper .input-group:focus-within .input-group-text {
        border-color: var(--rk-accent, #0d6efd);
    }

    /* ===== DESKTOP TABLE ===== */
    .pedoman-table {
        margin-bottom: 0;
        font-size: 0.92rem;
    }
    .pedoman-table thead th {
        background: var(--rk-bg-light, #f8f9fa);
        color: var(--rk-primary, #1a2035);
        font-weight: 600;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border-bottom: 2px solid var(--bs-border-color, #dee2e6);
        white-space: nowrap;
    }
    .pedoman-table tbody td {
        vertical-align: middle;
        padding-top: 0.85rem;
        padding-bottom: 0.85rem;
    }
    @media (hover: hover) {
        .pedoman-table tbody tr:hover {
            background-color: var(--bs-tertiary-bg, #f8f9fa);
        }
    }
    .pedoman-table .pedoman-name {
        font-weight: 500;
        color: var(--rk-primary, #1a2035);
        line-height: 1.4;
    }
    .pedoman-table .pedoman-desc {
        display: block;
        font-size: 0.8rem;
        font-weight: 400;
        color: var(--bs-secondary-color, #6c757d);
        margin-top: 2px;
        line-height: 1.4;
        /* 1-line clamp di tabel — ekspansi penuh di mobile card */
        display: -webkit-box;
        -webkit-line-clamp: 1;
        line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Format icon di sebelah badge — color cue tambahan */
    .pedoman-format-icon {
        font-size: 1.05rem;
        vertical-align: -2px;
    }
    .pedoman-format-icon.is-pdf   { color: #dc3545; }
    .pedoman-format-icon.is-excel { color: #198754; }
    .pedoman-format-icon.is-word  { color: #0d6efd; }
    .pedoman-format-icon.is-file  { color: #6c757d; }

    /* Tombol unduh di tabel — selalu min 44px untuk konsistensi touch */
    .pedoman-table .btn-unduh {
        min-height: 38px;
        font-size: 0.85rem;
    }

    /* ===== MOBILE CARD LIST ===== */
    .pedoman-mobile-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .pedoman-mobile-card {
        padding: 0.95rem;
        border: 1px solid var(--bs-border-color, #dee2e6);
        border-radius: 10px;
        background: #fff;
        transition: border-color 0.18s ease;
    }
    @media (hover: hover) {
        .pedoman-mobile-card:hover {
            border-color: var(--rk-accent, #0d6efd);
        }
    }
    .pedoman-mobile-card-header {
        display: flex;
        align-items: flex-start;
        gap: 0.7rem;
        margin-bottom: 0.6rem;
    }
    .pedoman-mobile-icon {
        flex-shrink: 0;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 1.4rem;
    }
    .pedoman-mobile-icon.is-pdf   { background: rgba(220, 53, 69, 0.1);   color: #dc3545; }
    .pedoman-mobile-icon.is-excel { background: rgba(25, 135, 84, 0.1);   color: #198754; }
    .pedoman-mobile-icon.is-word  { background: rgba(13, 110, 253, 0.1);  color: #0d6efd; }
    .pedoman-mobile-icon.is-file  { background: rgba(108, 117, 125, 0.1); color: #6c757d; }

    .pedoman-mobile-body { flex: 1 1 0; min-width: 0; }
    .pedoman-mobile-title {
        margin: 0 0 0.25rem;
        font-size: 0.95rem;
        font-weight: 600;
        line-height: 1.35;
        color: var(--rk-primary, #1a2035);
    }
    .pedoman-mobile-desc {
        margin: 0 0 0.5rem;
        font-size: 0.82rem;
        line-height: 1.5;
        color: var(--bs-secondary-color, #6c757d);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .pedoman-mobile-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.4rem;
        margin-bottom: 0.7rem;
        font-size: 0.75rem;
        color: var(--bs-secondary-color, #6c757d);
    }
    .pedoman-mobile-meta .badge { font-weight: 500; font-size: 0.7rem; }

    .pedoman-mobile-card .btn-unduh {
        min-height: 44px;     /* WCAG touch target */
        font-size: 0.9rem;
    }

    /* ===== Empty state ===== */
    .pedoman-empty {
        text-align: center;
        padding: 2rem 1rem;
        border: 2px dashed var(--bs-border-color, #dee2e6);
        border-radius: 12px;
        color: var(--bs-secondary-color, #6c757d);
    }
    @media (min-width: 768px) {
        .pedoman-empty { padding: 3rem 1.5rem; }
    }
    .pedoman-empty i {
        font-size: 2.5rem;
        opacity: 0.4;
        display: block;
        margin-bottom: 0.75rem;
    }

    /* ===== Reduced motion ===== */
    @media (prefers-reduced-motion: reduce) {
        .pedoman-filter-pill,
        .pedoman-mobile-card,
        .pedoman-table tbody tr,
        .pedoman-filters-wrapper::before,
        .pedoman-filters-wrapper::after,
        .pedoman-scroll-btn,
        .pedoman-filters {
            transition: none !important;
            scroll-behavior: auto !important;
        }
    }
</style>
@endpush

@section('content')

    {{-- Page Title --}}
    <div class="page-title" data-aos="fade">
        <div class="heading">
            <div class="container">
                <div class="row d-flex justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1>Pedoman Akademik</h1>
                        <p class="mb-0">Panduan resmi studi, tugas akhir, dan kelulusan mahasiswa</p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="breadcrumbs" aria-label="breadcrumb">
            <div class="container">
                <ol>
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><span class="breadcrumb-disabled">Kemahasiswaan</span></li>
                    <li class="current">Pedoman</li>
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
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-3 p-md-4">

                            {{-- Heading --}}
                            <h2 class="h4 mb-1" style="color: var(--rk-primary); font-weight: 700;">
                                <i class="bi bi-journal-bookmark me-2" aria-hidden="true"></i>Unduh Pedoman
                            </h2>
                            <p class="text-body-secondary mb-4" style="font-size: 0.9rem;">
                                Pilih kategori atau cari nama pedoman yang Anda butuhkan, lalu klik <strong>Unduh</strong>.
                            </p>

                            @if($pedomans->isNotEmpty())
                                {{-- ===== Toolbar: filter pills + search ===== --}}
                                <div class="pedoman-toolbar">
                                    <div class="pedoman-filters-wrapper" id="pedoman-filters-wrapper">
                                        {{-- Tombol prev (kiri) — visible saat sudah scroll dari awal --}}
                                        <button type="button"
                                                class="pedoman-scroll-btn pedoman-scroll-prev"
                                                id="pedoman-scroll-prev"
                                                aria-label="Gulirkan daftar kategori ke kiri"
                                                tabindex="-1">
                                            <i class="bi bi-chevron-left" aria-hidden="true"></i>
                                        </button>
                                        {{-- Tombol next (kanan) — visible saat masih bisa scroll ke kanan --}}
                                        <button type="button"
                                                class="pedoman-scroll-btn pedoman-scroll-next"
                                                id="pedoman-scroll-next"
                                                aria-label="Gulirkan daftar kategori ke kanan"
                                                tabindex="-1">
                                            <i class="bi bi-chevron-right" aria-hidden="true"></i>
                                        </button>

                                        <div class="pedoman-filters" role="tablist" aria-label="Filter kategori pedoman">
                                            <button type="button"
                                                    class="pedoman-filter-pill is-active"
                                                    role="tab"
                                                    aria-selected="true"
                                                    data-filter="all">
                                                <i class="bi bi-grid" aria-hidden="true"></i>
                                                Semua
                                                <span class="count" aria-label="{{ $pedomansCount['all'] }} pedoman">{{ $pedomansCount['all'] }}</span>
                                            </button>
                                            @foreach($kategoriList as $slug => $label)
                                                @php $count = $pedomansCount[$slug] ?? 0; @endphp
                                                @if($count > 0)
                                                    <button type="button"
                                                            class="pedoman-filter-pill"
                                                            role="tab"
                                                            aria-selected="false"
                                                            data-filter="{{ $slug }}">
                                                        <i class="bi {{ \App\Models\Pedoman::KATEGORI[$slug]['icon'] ?? 'bi-file' }}" aria-hidden="true"></i>
                                                        {{ $label }}
                                                        <span class="count" aria-label="{{ $count }} pedoman">{{ $count }}</span>
                                                    </button>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="pedoman-search-wrapper">
                                        <label for="pedoman-search" class="visually-hidden">Cari pedoman</label>
                                        <div class="input-group">
                                            <span class="input-group-text" aria-hidden="true">
                                                <i class="bi bi-search"></i>
                                            </span>
                                            <input type="search" id="pedoman-search"
                                                   class="form-control"
                                                   placeholder="Cari nama pedoman..."
                                                   autocomplete="off"
                                                   inputmode="search">
                                        </div>
                                    </div>
                                </div>

                                {{-- ====== DESKTOP TABLE (≥768px) ====== --}}
                                <div class="table-responsive d-none d-md-block" id="pedoman-table-wrapper">
                                    <table class="table table-bordered table-hover pedoman-table">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="width: 5%;" class="text-center">No</th>
                                                <th scope="col" style="width: 50%;">Nama Pedoman</th>
                                                <th scope="col" style="width: 16%;">Kategori</th>
                                                <th scope="col" style="width: 11%;" class="text-center">Format</th>
                                                <th scope="col" style="width: 8%;" class="text-center">Ukuran</th>
                                                <th scope="col" style="width: 10%;" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pedomans as $index => $pedoman)
                                                @php
                                                    $formatUpper = strtoupper((string) $pedoman->format_file);
                                                    $iconClass   = match (true) {
                                                        $formatUpper === 'PDF'                       => 'is-pdf',
                                                        in_array($formatUpper, ['XLS','XLSX'], true) => 'is-excel',
                                                        in_array($formatUpper, ['DOC','DOCX'], true) => 'is-word',
                                                        default                                       => 'is-file',
                                                    };
                                                    $searchText = mb_strtolower($pedoman->nama_file.' '.$pedoman->deskripsi);
                                                @endphp
                                                <tr class="pedoman-row"
                                                    data-kategori="{{ $pedoman->kategori }}"
                                                    data-search="{{ $searchText }}">
                                                    <td class="text-center text-body-secondary">{{ $index + 1 }}</td>
                                                    <td>
                                                        <div class="pedoman-name">{{ $pedoman->nama_file }}</div>
                                                        @if($pedoman->deskripsi)
                                                            <small class="pedoman-desc">{{ $pedoman->deskripsi }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge rounded-pill bg-{{ $pedoman->kategori_color }}-subtle text-{{ $pedoman->kategori_color }}-emphasis border border-{{ $pedoman->kategori_color }}-subtle">
                                                            <i class="bi {{ $pedoman->kategori_icon }} me-1" aria-hidden="true"></i>{{ $pedoman->kategori_label }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <i class="bi {{ $pedoman->format_icon }} pedoman-format-icon {{ $iconClass }} me-1" aria-hidden="true"></i>
                                                        <span class="badge bg-{{ $pedoman->format_color }}-subtle text-{{ $pedoman->format_color }}-emphasis">
                                                            {{ $pedoman->format_label }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center small text-body-secondary">
                                                        {{ $pedoman->file_size_human ?: '—' }}
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ $pedoman->file_url }}"
                                                           target="_blank"
                                                           rel="noopener"
                                                           download
                                                           class="btn btn-sm btn-primary btn-unduh"
                                                           aria-label="Unduh {{ $pedoman->nama_file }}">
                                                            <i class="bi bi-download" aria-hidden="true"></i>
                                                            <span class="d-none d-lg-inline ms-1">Unduh</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                {{-- ====== MOBILE CARD LIST (<768px) ====== --}}
                                <div class="d-block d-md-none pedoman-mobile-list" id="pedoman-mobile-list">
                                    @foreach($pedomans as $pedoman)
                                        @php
                                            $formatUpper = strtoupper((string) $pedoman->format_file);
                                            $iconClass   = match (true) {
                                                $formatUpper === 'PDF'                       => 'is-pdf',
                                                in_array($formatUpper, ['XLS','XLSX'], true) => 'is-excel',
                                                in_array($formatUpper, ['DOC','DOCX'], true) => 'is-word',
                                                default                                       => 'is-file',
                                            };
                                            $searchText = mb_strtolower($pedoman->nama_file.' '.$pedoman->deskripsi);
                                        @endphp
                                        <article class="pedoman-mobile-card pedoman-row"
                                                 data-kategori="{{ $pedoman->kategori }}"
                                                 data-search="{{ $searchText }}">
                                            <div class="pedoman-mobile-card-header">
                                                <div class="pedoman-mobile-icon {{ $iconClass }}" aria-hidden="true">
                                                    <i class="bi {{ $pedoman->format_icon }}"></i>
                                                </div>
                                                <div class="pedoman-mobile-body">
                                                    <h3 class="pedoman-mobile-title">{{ $pedoman->nama_file }}</h3>
                                                    @if($pedoman->deskripsi)
                                                        <p class="pedoman-mobile-desc">{{ $pedoman->deskripsi }}</p>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="pedoman-mobile-meta">
                                                <span class="badge rounded-pill bg-{{ $pedoman->kategori_color }}-subtle text-{{ $pedoman->kategori_color }}-emphasis">
                                                    <i class="bi {{ $pedoman->kategori_icon }} me-1" aria-hidden="true"></i>{{ $pedoman->kategori_label }}
                                                </span>
                                                <span class="badge bg-{{ $pedoman->format_color }}-subtle text-{{ $pedoman->format_color }}-emphasis">
                                                    {{ $pedoman->format_label }}
                                                </span>
                                                @if($pedoman->file_size_human)
                                                    <span><i class="bi bi-hdd" aria-hidden="true"></i> {{ $pedoman->file_size_human }}</span>
                                                @endif
                                            </div>

                                            <a href="{{ $pedoman->file_url }}"
                                               target="_blank"
                                               rel="noopener"
                                               download
                                               class="btn btn-primary btn-unduh w-100"
                                               aria-label="Unduh {{ $pedoman->nama_file }}">
                                                <i class="bi bi-download me-1" aria-hidden="true"></i>Unduh
                                            </a>
                                        </article>
                                    @endforeach
                                </div>

                                {{-- Empty state — filter/search match 0 --}}
                                <div class="pedoman-empty mt-3 d-none" id="pedoman-no-results" role="status" aria-live="polite">
                                    <i class="bi bi-search" aria-hidden="true"></i>
                                    <p class="mb-1 fw-semibold">Tidak ada pedoman yang cocok</p>
                                    <p class="mb-0 small">Coba kata kunci lain atau pilih kategori "Semua".</p>
                                </div>
                            @else
                                {{-- Empty state — belum ada data --}}
                                <div class="pedoman-empty">
                                    <i class="bi bi-journal-x" aria-hidden="true"></i>
                                    <p class="mb-1 fw-semibold">Belum ada pedoman</p>
                                    <p class="mb-0 small">Pedoman akan tersedia segera. Silakan kembali lagi nanti.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Sidebar (30%) --}}
                <div class="col-lg-4">
                    @include('components.frontend.sidebar-artikel')
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
    // =========================================================
    // PEDOMAN PAGE — Filter + Search + Scroll Hint (vanilla JS)
    // =========================================================
    // Strategi: state { kategori, search } unified untuk DESKTOP table rows
    // dan MOBILE card list. Toggle inline `display` per row, biarkan CSS
    // responsive class (.d-none .d-md-block) tetap intact.
    (function () {
        'use strict';

        const tableWrap   = document.getElementById('pedoman-table-wrapper');
        const mobileWrap  = document.getElementById('pedoman-mobile-list');
        if (! tableWrap && ! mobileWrap) return;

        const rowsTable   = tableWrap   ? tableWrap.querySelectorAll('.pedoman-row')   : [];
        const rowsMobile  = mobileWrap  ? mobileWrap.querySelectorAll('.pedoman-row') : [];
        const pills       = Array.from(document.querySelectorAll('.pedoman-filter-pill'));
        const pillScroll  = document.querySelector('.pedoman-filters');
        const filtersWrap = document.getElementById('pedoman-filters-wrapper');
        const searchEl    = document.getElementById('pedoman-search');
        const noResults   = document.getElementById('pedoman-no-results');

        const state = { kategori: 'all', search: '' };

        function matchRow(row) {
            if (state.kategori !== 'all' && row.dataset.kategori !== state.kategori) return false;
            if (state.search && ! (row.dataset.search || '').includes(state.search)) return false;
            return true;
        }

        function applyFilter() {
            let visible = 0;

            rowsTable.forEach((r) => {
                const ok = matchRow(r);
                r.style.display = ok ? '' : 'none';
                if (ok) visible++;
            });
            rowsMobile.forEach((r) => {
                r.style.display = matchRow(r) ? '' : 'none';
            });

            // Empty state — toggle hanya kalau ada filter aktif
            const hasFilter = state.kategori !== 'all' || state.search;
            const showEmpty = visible === 0 && hasFilter;
            if (noResults)  noResults.classList.toggle('d-none', ! showEmpty);
            if (tableWrap)  tableWrap.style.display  = showEmpty ? 'none' : '';
            if (mobileWrap) mobileWrap.style.display = showEmpty ? 'none' : '';
        }

        // === Filter pills: click + keyboard arrows (tablist a11y) ===
        function activatePill(pill) {
            pills.forEach((p) => {
                p.classList.remove('is-active');
                p.setAttribute('aria-selected', 'false');
                p.setAttribute('tabindex', '-1');
            });
            pill.classList.add('is-active');
            pill.setAttribute('aria-selected', 'true');
            pill.setAttribute('tabindex', '0');
            state.kategori = pill.dataset.filter || 'all';
            applyFilter();

            if (pillScroll && pill.scrollIntoView) {
                pill.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'nearest' });
            }
        }

        pills.forEach((pill, idx) => {
            pill.setAttribute('tabindex', idx === 0 ? '0' : '-1');
            pill.addEventListener('click', function () { activatePill(this); });

            pill.addEventListener('keydown', function (e) {
                const key = e.key;
                if (! ['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(key)) return;
                e.preventDefault();
                const cur = pills.indexOf(this);
                let next = cur;
                if (key === 'ArrowLeft')  next = cur === 0 ? pills.length - 1 : cur - 1;
                if (key === 'ArrowRight') next = cur === pills.length - 1 ? 0 : cur + 1;
                if (key === 'Home')       next = 0;
                if (key === 'End')        next = pills.length - 1;
                pills[next].focus();
                activatePill(pills[next]);
            });
        });

        // === Search debounced 100ms ===
        let searchTimer = null;
        if (searchEl) {
            searchEl.addEventListener('input', function () {
                clearTimeout(searchTimer);
                const val = this.value.toLowerCase().trim();
                searchTimer = setTimeout(() => {
                    state.search = val;
                    applyFilter();
                }, 100);
            });
        }

        // === Scroll state: fade gradient kiri/kanan + tombol prev/next + drag-to-scroll ===
        if (pillScroll && filtersWrap) {
            const btnPrev = document.getElementById('pedoman-scroll-prev');
            const btnNext = document.getElementById('pedoman-scroll-next');

            // -- State indicators (fade + button visibility) --
            let scrollRaf = null;
            const updateScrollState = () => {
                const hasPrev = pillScroll.scrollLeft > 5;
                const hasNext = pillScroll.scrollLeft < (pillScroll.scrollWidth - pillScroll.clientWidth - 5);
                filtersWrap.classList.toggle('has-prev', hasPrev);
                filtersWrap.classList.toggle('has-next', hasNext);
                if (btnPrev) btnPrev.classList.toggle('visible', hasPrev);
                if (btnNext) btnNext.classList.toggle('visible', hasNext);
            };
            const onScroll = () => {
                if (scrollRaf) return;
                scrollRaf = requestAnimationFrame(() => {
                    updateScrollState();
                    scrollRaf = null;
                });
            };
            pillScroll.addEventListener('scroll', onScroll, { passive: true });
            window.addEventListener('resize', onScroll, { passive: true });
            requestAnimationFrame(updateScrollState);

            // -- Click handlers: prev/next scroll 200px --
            if (btnPrev) {
                btnPrev.addEventListener('click', () => {
                    pillScroll.scrollBy({ left: -200, behavior: 'smooth' });
                });
            }
            if (btnNext) {
                btnNext.addEventListener('click', () => {
                    pillScroll.scrollBy({ left: 200, behavior: 'smooth' });
                });
            }

            // -- Drag-to-scroll (mouse click + drag) --
            // Pattern: track mousedown→mousemove delta, scrollLeft -= delta.
            // Touch device pakai native swipe scroll (sudah didukung browser).
            const dragState = { isDown: false, startX: 0, startScroll: 0, moved: false };
            const DRAG_THRESHOLD = 5; // px — jarak min untuk dianggap drag (bedakan dari klik)

            pillScroll.addEventListener('mousedown', (e) => {
                if (e.button !== 0) return; // hanya tombol kiri
                dragState.isDown      = true;
                dragState.moved       = false;
                dragState.startX      = e.pageX;
                dragState.startScroll = pillScroll.scrollLeft;
                pillScroll.classList.add('is-dragging');
            });

            // Global mousemove — drag tetap responsif meski cursor keluar dari scroll area
            document.addEventListener('mousemove', (e) => {
                if (! dragState.isDown) return;
                e.preventDefault();
                const dx = e.pageX - dragState.startX;
                if (Math.abs(dx) > DRAG_THRESHOLD) dragState.moved = true;
                pillScroll.scrollLeft = dragState.startScroll - dx;
            });

            // Global mouseup — handle release di mana saja
            document.addEventListener('mouseup', () => {
                if (! dragState.isDown) return;
                dragState.isDown = false;
                pillScroll.classList.remove('is-dragging');
                // Reset moved flag SETELAH click event sempat terbaca (lihat capture handler)
                setTimeout(() => { dragState.moved = false; }, 0);
            });

            // Cegah klik pill ter-trigger kalau user melakukan drag (capture phase)
            pillScroll.addEventListener('click', (e) => {
                if (dragState.moved) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            }, true);

            // Cegah drag image/text default browser
            pillScroll.addEventListener('dragstart', (e) => e.preventDefault());
        }
    })();
</script>
@endpush
