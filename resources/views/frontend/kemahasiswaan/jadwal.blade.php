@extends('layouts.frontend')
@section('title', 'Jadwal Perkuliahan')
@section('body_class', 'kemahasiswaan-page')
@section('meta_description', 'Jadwal perkuliahan Jurusan Rekayasa dan Komputer Politeknik Pertanian Negeri Samarinda untuk 4 program studi (TG, SIA, TRPL, TRGS) per semester dan tahun akademik.')

@section('content')

    {{-- Page Title --}}
    <div class="page-title" data-aos="fade">
        <div class="heading">
            <div class="container">
                <div class="row d-flex justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1>Jadwal Perkuliahan</h1>
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
                    <li class="current">Jadwal Perkuliahan</li>
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
                        <div class="card-body p-4">

                            {{-- HEADER --}}
                            <h4 class="mb-2" style="color: var(--rk-primary); font-weight: 700;">
                                <i class="bi bi-calendar-week me-2" aria-hidden="true"></i>Jadwal Perkuliahan
                            </h4>
                            <p class="text-muted small mb-4">
                                @if($listTahunAjaran->isEmpty())
                                    Belum ada data jadwal perkuliahan yang dipublikasikan.
                                @else
                                    Pilih tahun ajaran untuk melihat jadwal per program studi.
                                @endif
                            </p>

                            @if($listTahunAjaran->isNotEmpty())
                                {{-- ====== YEAR TABS (scalable, scroll horizontal) ====== --}}
                                <div class="jadwal-tabs-wrapper" id="jadwalTabsWrapper" role="tablist" aria-label="Tahun Ajaran">
                                    <button type="button"
                                            class="jadwal-scroll-btn jadwal-scroll-prev"
                                            id="jadwalScrollPrev"
                                            aria-label="Tahun sebelumnya"
                                            tabindex="-1">
                                        <i class="bi bi-chevron-left" aria-hidden="true"></i>
                                    </button>
                                    <button type="button"
                                            class="jadwal-scroll-btn jadwal-scroll-next"
                                            id="jadwalScrollNext"
                                            aria-label="Tahun berikutnya"
                                            tabindex="-1">
                                        <i class="bi bi-chevron-right" aria-hidden="true"></i>
                                    </button>

                                    <div class="jadwal-tabs-scroll" id="jadwalTabsScroll">
                                        @foreach($listTahunAjaran as $idx => $ta)
                                            @php
                                                $isActive = $ta === $tahunAktif;
                                                $count    = $jadwalsByTahun[$ta]->count();
                                                $tabId    = 'jadwal-tab-' . \Illuminate\Support\Str::slug($ta);
                                            @endphp
                                            <button type="button"
                                                    class="jadwal-tab {{ $isActive ? 'is-active' : '' }}"
                                                    data-tahun="{{ $ta }}"
                                                    role="tab"
                                                    aria-selected="{{ $isActive ? 'true' : 'false' }}"
                                                    aria-controls="{{ $tabId }}"
                                                    id="{{ $tabId }}-trigger">
                                                {{-- Tahun: format pendek di mobile (25/26), lengkap di desktop (2025/2026) --}}
                                                <span class="d-none d-md-inline">{{ $ta }}</span>
                                                <span class="d-inline d-md-none">{{ \Illuminate\Support\Str::substr($ta, 2, 2) }}/{{ \Illuminate\Support\Str::substr($ta, 7, 2) }}</span>
                                                @if($isActive)
                                                    <span class="badge jadwal-badge-active ms-1">Aktif</span>
                                                @else
                                                    <span class="badge jadwal-badge-inactive ms-1">{{ $count }}</span>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- ====== TAB PANELS — satu per tahun ajaran ====== --}}
                                @foreach($listTahunAjaran as $ta)
                                    @php
                                        $isActive   = $ta === $tahunAktif;
                                        $tabPanelId = 'jadwal-tab-' . \Illuminate\Support\Str::slug($ta);
                                        $jadwalsTA  = $jadwalsByTahun[$ta];
                                    @endphp
                                    <div class="jadwal-tab-panel {{ $isActive ? '' : 'd-none' }}"
                                         id="{{ $tabPanelId }}"
                                         role="tabpanel"
                                         aria-labelledby="{{ $tabPanelId }}-trigger"
                                         data-tahun="{{ $ta }}">

                                        {{-- Semester toolbar + search --}}
                                        <div class="jadwal-semester-toolbar">
                                            <div class="d-flex align-items-center gap-3 flex-grow-1">
                                                <span class="small fw-semibold text-body-secondary d-none d-sm-inline">Semester:</span>
                                                <div class="jadwal-semester-group" role="group" aria-label="Filter semester">
                                                    <button type="button" class="is-active" data-semester="">Semua</button>
                                                    <button type="button" data-semester="Ganjil">Ganjil</button>
                                                    <button type="button" data-semester="Genap">Genap</button>
                                                </div>
                                            </div>
                                            <div class="input-group input-group-sm jadwal-search-input">
                                                <span class="input-group-text bg-white border-end-0" aria-hidden="true">
                                                    <i class="bi bi-search text-body-secondary"></i>
                                                </span>
                                                <input type="search"
                                                       class="form-control border-start-0 ps-0 jadwal-search"
                                                       placeholder="Cari prodi..."
                                                       autocomplete="off"
                                                       aria-label="Cari prodi di tahun {{ $ta }}">
                                            </div>
                                        </div>

                                        {{-- Info banner periode (hanya untuk tab aktif) --}}
                                        @if($isActive)
                                            <div class="alert alert-info border-0 d-flex align-items-center gap-2 py-2 mb-3 jadwal-info-banner">
                                                <i class="bi bi-broadcast-pin" aria-hidden="true"></i>
                                                <small class="mb-0">
                                                    Menampilkan jadwal <strong>Tahun Ajaran {{ $ta }}</strong>. Periode ini sedang berjalan.
                                                </small>
                                            </div>
                                        @endif

                                        {{-- ====== DESKTOP TABLE (≥768px) ====== --}}
                                        <div class="table-responsive table-styled-wrapper d-none d-md-block">
                                            <table class="table table-bordered table-hover mb-0 jadwal-table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" style="width: 50%;">Program Studi</th>
                                                        <th scope="col" class="text-center" style="width: 14%;">Semester</th>
                                                        <th scope="col" class="text-center" style="width: 18%;">Diperbarui</th>
                                                        <th scope="col" class="text-center" style="width: 18%;">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($jadwalsTA as $jadwal)
                                                        @php
                                                            $semColor   = $jadwal->semester === 'Genap' ? 'info' : 'warning';
                                                            // Icon numerik kontekstual akademik:
                                                            // Ganjil → 1-circle-fill, Genap → 2-circle-fill
                                                            $semIcon    = $jadwal->semester === 'Genap' ? '2-circle-fill' : '1-circle-fill';
                                                            $searchText = mb_strtolower($jadwal->nama_prodi);
                                                        @endphp
                                                        <tr class="jadwal-row"
                                                            data-semester="{{ $jadwal->semester }}"
                                                            data-search="{{ $searchText }}">
                                                            <td>
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <i class="bi bi-mortarboard-fill text-primary" aria-hidden="true"></i>
                                                                    <span class="fw-medium">{{ $jadwal->nama_prodi }}</span>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge rounded-pill bg-{{ $semColor }}-subtle text-{{ $semColor }}-emphasis border border-{{ $semColor }}-subtle">
                                                                    <i class="bi bi-{{ $semIcon }} me-1" aria-hidden="true"></i>{{ $jadwal->semester }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center small text-muted">{{ $jadwal->updated_at->translatedFormat('d M Y') }}</td>
                                                            <td class="text-center">
                                                                <a href="{{ asset('storage/' . $jadwal->file_path) }}"
                                                                   target="_blank"
                                                                   rel="noopener"
                                                                   class="btn btn-sm btn-primary"
                                                                   aria-label="Unduh jadwal {{ $jadwal->nama_prodi }} Semester {{ $jadwal->semester }} {{ $ta }}">
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
                                        <div class="d-block d-md-none jadwal-mobile-list">
                                            @foreach($jadwalsTA as $jadwal)
                                                @php
                                                    $semColor   = $jadwal->semester === 'Genap' ? 'info' : 'warning';
                                                    // Icon numerik kontekstual akademik (sama dengan desktop table)
                                                    $semIcon    = $jadwal->semester === 'Genap' ? '2-circle-fill' : '1-circle-fill';
                                                    $searchText = mb_strtolower($jadwal->nama_prodi);
                                                @endphp
                                                <article class="jadwal-mobile-card jadwal-row"
                                                         data-semester="{{ $jadwal->semester }}"
                                                         data-search="{{ $searchText }}">
                                                    <div class="d-flex align-items-start gap-2 mb-2">
                                                        <i class="bi bi-mortarboard-fill text-primary fs-4 flex-shrink-0" aria-hidden="true"></i>
                                                        <div class="min-w-0 flex-grow-1">
                                                            <h5 class="h6 mb-1 fw-semibold text-body">{{ $jadwal->nama_prodi }}</h5>
                                                            <span class="badge rounded-pill bg-{{ $semColor }}-subtle text-{{ $semColor }}-emphasis border border-{{ $semColor }}-subtle">
                                                                <i class="bi bi-{{ $semIcon }} me-1" aria-hidden="true"></i>{{ $jadwal->semester }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted">
                                                            <i class="bi bi-file-earmark-pdf-fill text-danger" aria-hidden="true"></i>
                                                            {{ $jadwal->updated_at->translatedFormat('d M Y') }}
                                                        </small>
                                                        <a href="{{ asset('storage/' . $jadwal->file_path) }}"
                                                           target="_blank"
                                                           rel="noopener"
                                                           class="btn btn-sm btn-primary"
                                                           aria-label="Unduh jadwal {{ $jadwal->nama_prodi }} Semester {{ $jadwal->semester }} {{ $ta }}">
                                                            <i class="bi bi-download me-1" aria-hidden="true"></i>Unduh
                                                        </a>
                                                    </div>
                                                </article>
                                            @endforeach
                                        </div>

                                        {{-- Empty state (muncul saat filter semester+search tidak match) --}}
                                        <div class="jadwal-no-results text-center py-4 d-none" role="status" aria-live="polite">
                                            <i class="bi bi-search text-body-tertiary" style="font-size: 2.5rem;" aria-hidden="true"></i>
                                            <h5 class="h6 mt-3 mb-1 text-body">Tidak ada hasil</h5>
                                            <p class="small text-muted mb-0">Coba kata kunci lain atau ubah filter semester.</p>
                                        </div>
                                    </div>
                                @endforeach
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
// JADWAL PERKULIAHAN — Year Tabs (PDDikti-style)
// Pure JS (no jQuery), per library-standard.md.
//
// Fitur:
// 1. Year tab switching (klik tab → tampilkan panel tahun tsb)
// 2. Tab horizontal scroll dengan tombol prev/next (desktop)
// 3. Touch swipe + scroll snap (mobile, native)
// 4. Per-panel: semester filter + search (independent state per tahun)
// 5. Empty state otomatis muncul saat filter tidak menemukan match
// =========================================================
(function () {
    'use strict';

    const wrapper     = document.getElementById('jadwalTabsWrapper');
    const scrollArea  = document.getElementById('jadwalTabsScroll');
    const btnPrev     = document.getElementById('jadwalScrollPrev');
    const btnNext     = document.getElementById('jadwalScrollNext');
    const tabs        = document.querySelectorAll('.jadwal-tab');
    const panels      = document.querySelectorAll('.jadwal-tab-panel');

    // Tidak ada data → bail out
    if (!wrapper || !scrollArea) return;

    // ============================================
    // 1. TAB SWITCHING (klik tab → fade transition panel)
    // ============================================
    // Sequential transition (PDDikti-style):
    //   Phase A: panel lama fade-out (opacity 0 + slight translateY)
    //   Phase B: setelah selesai, hide & swap → panel baru muncul fade-in
    // Dipandu oleh prefers-reduced-motion: tanpa animasi kalau user prefer.
    const TRANSITION_MS = 220;
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let isTransitioning = false;

    function activateTab(tabEl, tahun) {
        tabs.forEach((t) => {
            t.classList.remove('is-active');
            t.setAttribute('aria-selected', 'false');
        });
        tabEl.classList.add('is-active');
        tabEl.setAttribute('aria-selected', 'true');
    }

    /**
     * Swap panel dengan animasi (atau instan jika reduced-motion).
     * Guard `isTransitioning` di-release via callback `onComplete` di SEMUA code path
     * — penting agar tab tetap responsif setelah switch.
     *
     * Bug history: sebelumnya guard di-release di dalam swapPanels untuk path animasi
     * saja, sehingga path reduced-motion (Windows "Show animations" off, dsb) bikin
     * `isTransitioning` stuck = true → tab tidak bisa diklik lagi setelah 1x switch.
     */
    function swapPanels(currentPanel, nextPanel, onComplete) {
        const done = () => { if (typeof onComplete === 'function') onComplete(); };

        // Mode reduced-motion atau tidak ada current panel: instan tanpa animasi
        if (reducedMotion || !currentPanel) {
            panels.forEach((p) => p.classList.toggle('d-none', p !== nextPanel));
            done();
            return;
        }

        // Phase A — leave: fade out current panel
        currentPanel.classList.add('is-leaving');

        setTimeout(() => {
            // Hide current setelah fade-out selesai
            currentPanel.classList.add('d-none');
            currentPanel.classList.remove('is-leaving');

            // Phase B — enter: tampilkan next panel dengan starting state (opacity 0)
            nextPanel.classList.add('is-entering');
            nextPanel.classList.remove('d-none');

            // Double rAF: pastikan browser commit starting state SEBELUM transisi ke state final.
            // Tanpa ini, browser akan batch dan skip animasi.
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    nextPanel.classList.remove('is-entering');
                });
            });

            // Release guard setelah enter selesai
            setTimeout(done, TRANSITION_MS);
        }, TRANSITION_MS);
    }

    tabs.forEach((tab) => {
        tab.addEventListener('click', function () {
            // Cegah klik beruntun saat transisi sedang berjalan
            if (isTransitioning) return;

            const tahun = this.dataset.tahun;
            const nextPanel    = document.querySelector('.jadwal-tab-panel[data-tahun="' + tahun + '"]');
            const currentPanel = document.querySelector('.jadwal-tab-panel:not(.d-none)');

            // No-op kalau tab yang sama atau panel target tidak ada
            if (!nextPanel || nextPanel === currentPanel) {
                this.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                return;
            }

            isTransitioning = true;
            activateTab(this, tahun);
            swapPanels(currentPanel, nextPanel, () => {
                // Guard release via callback — terjamin reset baik path animasi maupun reduced-motion
                isTransitioning = false;
            });

            // Auto-scroll tab into view (paralel dengan transisi panel)
            this.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        });
    });

    // ============================================
    // 2. SCROLL BUTTONS + STATE INDICATORS
    // ============================================
    function updateScrollState() {
        const isDesktop = window.matchMedia('(min-width: 768px)').matches;
        const hasPrev = scrollArea.scrollLeft > 5;
        const hasNext = scrollArea.scrollLeft <
                        (scrollArea.scrollWidth - scrollArea.clientWidth - 5);

        // Gradient fade indicators (CSS)
        wrapper.classList.toggle('has-prev', hasPrev);
        wrapper.classList.toggle('has-next', hasNext);

        // Tombol prev/next hanya muncul di desktop + ketika ada overflow
        if (isDesktop) {
            btnPrev.classList.toggle('visible', hasPrev);
            btnNext.classList.toggle('visible', hasNext);
        } else {
            btnPrev.classList.remove('visible');
            btnNext.classList.remove('visible');
        }
    }

    if (btnPrev) {
        btnPrev.addEventListener('click', () =>
            scrollArea.scrollBy({ left: -200, behavior: 'smooth' })
        );
    }
    if (btnNext) {
        btnNext.addEventListener('click', () =>
            scrollArea.scrollBy({ left: 200, behavior: 'smooth' })
        );
    }
    scrollArea.addEventListener('scroll', updateScrollState, { passive: true });
    window.addEventListener('resize', updateScrollState);
    updateScrollState();

    // ============================================
    // 2b. DRAG-TO-SCROLL (cursor click & drag, desktop)
    // ============================================
    // Pattern: track mousedown→mousemove delta, scrollLeft -= delta.
    // Touch device pakai native swipe scroll (sudah didukung browser).
    const dragState = {
        isDown:      false,
        startX:      0,
        startScroll: 0,
        moved:       false,
    };
    const DRAG_THRESHOLD = 5; // px — jarak min untuk dianggap drag (bedakan dr klik)

    scrollArea.addEventListener('mousedown', (e) => {
        // Hanya tombol kiri mouse
        if (e.button !== 0) return;
        dragState.isDown      = true;
        dragState.moved       = false;
        dragState.startX      = e.pageX;
        dragState.startScroll = scrollArea.scrollLeft;
        scrollArea.classList.add('is-dragging');
    });

    // Mouse move global — agar drag tetap responsif meski cursor keluar dari scrollArea
    document.addEventListener('mousemove', (e) => {
        if (!dragState.isDown) return;
        e.preventDefault();
        const dx = e.pageX - dragState.startX;
        if (Math.abs(dx) > DRAG_THRESHOLD) {
            dragState.moved = true;
        }
        scrollArea.scrollLeft = dragState.startScroll - dx;
    });

    // Mouse up global — handle release di mana saja (luar scrollArea juga)
    document.addEventListener('mouseup', () => {
        if (!dragState.isDown) return;
        dragState.isDown = false;
        scrollArea.classList.remove('is-dragging');
        // Reset moved flag setelah click event sempat terbaca (lihat capture handler di bawah)
        setTimeout(() => { dragState.moved = false; }, 0);
    });

    // Cegah klik tab yang ter-trigger karena drag (capture phase agar
    // intercept SEBELUM listener tab.click yang switch panel).
    scrollArea.addEventListener('click', (e) => {
        if (dragState.moved) {
            e.preventDefault();
            e.stopPropagation();
        }
    }, true);

    // Cegah drag image/text default
    scrollArea.addEventListener('dragstart', (e) => e.preventDefault());

    // ============================================
    // 3. PER-PANEL FILTER (semester + search)
    // ============================================
    panels.forEach((panel) => {
        const semBtns      = panel.querySelectorAll('.jadwal-semester-group button');
        const searchInput  = panel.querySelector('.jadwal-search');
        const rowsTable    = panel.querySelectorAll('.jadwal-table .jadwal-row');
        const rowsMobile   = panel.querySelectorAll('.jadwal-mobile-list .jadwal-row');
        const noResultsEl  = panel.querySelector('.jadwal-no-results');
        const tableWrapper = panel.querySelector('.table-responsive');
        const mobileWrap   = panel.querySelector('.jadwal-mobile-list');

        const state = { semester: '', search: '' };

        function normalize(s) { return (s || '').toString().toLowerCase().trim(); }

        function matchRow(row) {
            if (state.semester && row.dataset.semester !== state.semester) return false;
            if (state.search && !row.dataset.search.includes(state.search)) return false;
            return true;
        }

        function applyFilter() {
            let visibleCount = 0;

            rowsTable.forEach((r) => {
                const ok = matchRow(r);
                r.style.display = ok ? '' : 'none';
                if (ok) visibleCount++;
            });
            rowsMobile.forEach((r) => {
                r.style.display = matchRow(r) ? '' : 'none';
            });

            // Empty state visibility (toggle inline `display`, biarkan CSS responsive
            // class tetap intact: `d-none d-md-block` desktop, `d-block d-md-none` mobile)
            const hasFilter = state.semester || state.search;
            const showEmpty = visibleCount === 0 && hasFilter;
            if (noResultsEl)  noResultsEl.classList.toggle('d-none', !showEmpty);
            if (tableWrapper) tableWrapper.style.display = showEmpty ? 'none' : '';
            if (mobileWrap)   mobileWrap.style.display   = showEmpty ? 'none' : '';
        }

        // Semester filter buttons
        semBtns.forEach((btn) => {
            btn.addEventListener('click', function () {
                semBtns.forEach((b) => b.classList.remove('is-active'));
                this.classList.add('is-active');
                state.semester = this.dataset.semester || '';
                applyFilter();
            });
        });

        // Search (debounced 150ms)
        if (searchInput) {
            let timer;
            searchInput.addEventListener('input', function () {
                clearTimeout(timer);
                const v = normalize(this.value);
                timer = setTimeout(() => {
                    state.search = v;
                    applyFilter();
                }, 150);
            });
        }
    });
})();
</script>
@endpush
