@extends('layouts.frontend')
@section('title', $kegiatan->judul)
@section('body_class', 'article-page')
@section('meta_description', Str::limit(strip_tags($kegiatan->ringkasan ?? $kegiatan->konten), 160))
@section('og_image', $kegiatan->gambar ? asset('storage/' . $kegiatan->gambar) : asset('frontend/img/logo-politani.png'))

@php
    use Illuminate\Support\Str;

    $badge = $kegiatan->tipe_badge;

    // Share URL (public, absolute) untuk WhatsApp/FB/Twitter/LinkedIn/Copy.
    $shareUrl = route('kemahasiswaan.kegiatan.show', $kegiatan->slug);
    $shareTitle = $kegiatan->judul;
@endphp

@section('content')

    {{-- Breadcrumb compact --}}
    <nav class="article-breadcrumb" aria-label="breadcrumb">
        <div class="container">
            <ol>
                <li><a href="{{ route('home') }}">Beranda</a></li>
                <li><span class="breadcrumb-disabled">Kemahasiswaan</span></li>
                <li><a href="{{ route('kemahasiswaan.kegiatan') }}">Kegiatan</a></li>
                <li class="current">{{ Str::limit($kegiatan->judul, 60) }}</li>
            </ol>
        </div>
    </nav>

    <article class="article-wrapper">
        <div class="container">
            <div class="article-layout">
                {{-- Hero Image (kalau ada) --}}
                @if($kegiatan->gambar)
                    <figure class="article-hero-figure">
                        <img src="{{ asset('storage/' . $kegiatan->gambar) }}"
                             alt="{{ $kegiatan->judul }}"
                             class="article-hero-img"
                             fetchpriority="high">
                    </figure>
                @endif

                {{-- Kolom utama --}}
                <div class="article-inner">

                    {{-- Tags: tipe kegiatan --}}
                    <div class="article-tags">
                        <span class="article-tag">
                            <i class="bi {{ $badge['icon'] }} me-1" aria-hidden="true"></i>{{ $badge['label'] }}
                        </span>
                    </div>

                    {{-- Judul --}}
                    <h1 class="article-title">{{ $kegiatan->judul }}</h1>

                    {{-- Lead paragraph (ringkasan) --}}
                    @if($kegiatan->ringkasan)
                        <p class="article-lead">{{ $kegiatan->ringkasan }}</p>
                    @endif

                    {{-- Meta bar: author institusional + tanggal kegiatan + views.
                         Author hardcoded "Jurusan R&K" karena kegiatan secara semantik
                         institusional (acara jurusan), bukan tulisan personal seperti berita.
                         Konsisten dengan pattern berita.show (article-author-mini + meta-info). --}}
                    <div class="article-meta">
                        <div class="article-author-mini">
                            <span class="author-avatar" aria-hidden="true">RK</span>
                            <div class="author-info">
                                <strong>Jurusan R&amp;K</strong>
                            </div>
                        </div>
                        <div class="article-meta-info">
                            <span class="meta-item" title="Tanggal kegiatan">
                                <i class="bi bi-calendar3" aria-hidden="true"></i>
                                <time datetime="{{ $kegiatan->tanggal?->toDateString() }}">@tanggal($kegiatan->tanggal, 'd F Y')</time>
                            </span>
                        </div>
                    </div>

                    {{-- Konten artikel --}}
                    <div class="article-body blog-content">
                        {!! $kegiatan->konten !!}
                    </div>

                    {{-- Share Section (bottom) --}}
                    <div class="article-share" role="group" aria-label="Bagikan kegiatan">
                        <p class="article-share-label">
                            <i class="bi bi-share-fill" aria-hidden="true"></i>
                            Bagikan kegiatan ini
                        </p>
                        <div class="article-share-buttons">
                            <a href="https://wa.me/?text={{ urlencode($shareTitle . ' — ' . $shareUrl) }}"
                               target="_blank" rel="noopener"
                               class="share-btn share-btn--whatsapp"
                               aria-label="Bagikan ke WhatsApp">
                                <i class="bi bi-whatsapp" aria-hidden="true"></i>
                                <span>WhatsApp</span>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}"
                               target="_blank" rel="noopener"
                               class="share-btn share-btn--facebook"
                               aria-label="Bagikan ke Facebook">
                                <i class="bi bi-facebook" aria-hidden="true"></i>
                                <span>Facebook</span>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode($shareTitle) }}"
                               target="_blank" rel="noopener"
                               class="share-btn share-btn--twitter"
                               aria-label="Bagikan ke Twitter/X">
                                <i class="bi bi-twitter-x" aria-hidden="true"></i>
                                <span>Twitter</span>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($shareUrl) }}"
                               target="_blank" rel="noopener"
                               class="share-btn share-btn--linkedin"
                               aria-label="Bagikan ke LinkedIn">
                                <i class="bi bi-linkedin" aria-hidden="true"></i>
                                <span>LinkedIn</span>
                            </a>
                            <button type="button"
                                    class="share-btn share-btn--copy"
                                    data-copy-url="{{ $shareUrl }}"
                                    aria-label="Salin link kegiatan">
                                <i class="bi bi-link-45deg" aria-hidden="true"></i>
                                <span class="share-btn-label">Salin Link</span>
                            </button>
                        </div>
                    </div>

                    {{-- Prev / Next Navigation --}}
                    @if($prev || $next)
                        <nav class="article-nav" aria-label="Navigasi kegiatan">
                            @if($prev)
                                <a href="{{ route('kemahasiswaan.kegiatan.show', $prev->slug) }}" class="article-nav-item article-nav-item--prev">
                                    <span class="nav-label">
                                        <i class="bi bi-arrow-left" aria-hidden="true"></i>Kegiatan Sebelumnya
                                    </span>
                                    <span class="nav-title">{{ Str::limit($prev->judul, 80) }}</span>
                                </a>
                            @else
                                <div class="article-nav-item article-nav-item--empty" aria-hidden="true"></div>
                            @endif

                            @if($next)
                                <a href="{{ route('kemahasiswaan.kegiatan.show', $next->slug) }}" class="article-nav-item article-nav-item--next">
                                    <span class="nav-label">
                                        Kegiatan Berikutnya<i class="bi bi-arrow-right" aria-hidden="true"></i>
                                    </span>
                                    <span class="nav-title">{{ Str::limit($next->judul, 80) }}</span>
                                </a>
                            @else
                                <div class="article-nav-item article-nav-item--empty" aria-hidden="true"></div>
                            @endif
                        </nav>
                    @endif

                </div>{{-- /.article-inner --}}

                {{-- Sidebar kanan (desktop sticky-ish, mobile stack) --}}
                <aside class="article-sidebar" aria-label="Informasi terkait">

                    {{-- Sidebar: Tentang Tipe Kegiatan --}}
                    @if($tipeDeskripsi)
                        <div class="sidebar-section">
                            <h4 class="sidebar-heading">Tipe: {{ $badge['label'] }}</h4>
                            <p class="sidebar-text">{{ $tipeDeskripsi }}</p>
                        </div>
                    @endif

                    {{-- Sidebar: Kegiatan Terkini --}}
                    @if($kegiatanTerkini->isNotEmpty())
                        <div class="sidebar-section">
                            <h4 class="sidebar-heading">Kegiatan Terkini</h4>
                            <ul class="sidebar-news-list">
                                @foreach($kegiatanTerkini as $tk)
                                    @php($tkBadge = $tk->tipe_badge)
                                    <li class="sidebar-news-item">
                                        <a href="{{ route('kemahasiswaan.kegiatan.show', $tk->slug) }}" class="sidebar-news-thumb" aria-label="Buka: {{ $tk->judul }}">
                                            @if($tk->gambar)
                                                <img src="{{ asset('storage/' . $tk->gambar) }}"
                                                     alt="{{ $tk->judul }}" loading="lazy">
                                            @else
                                                <span class="sidebar-news-placeholder" aria-hidden="true">
                                                    <i class="bi {{ $tkBadge['icon'] }}"></i>
                                                </span>
                                            @endif
                                        </a>
                                        <div class="sidebar-news-content">
                                            <a href="{{ route('kemahasiswaan.kegiatan.show', $tk->slug) }}">{{ Str::limit($tk->judul, 70) }}</a>
                                            <span class="sidebar-news-date">
                                                <i class="bi bi-calendar3" aria-hidden="true"></i>
                                                @tanggal($tk->tanggal, 'd M Y')
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ route('kemahasiswaan.kegiatan') }}" class="sidebar-lihat-semua">
                                Lihat Semua Kegiatan <i class="bi bi-arrow-right" aria-hidden="true"></i>
                            </a>
                        </div>
                    @endif

                </aside>

            </div>{{-- /.article-layout --}}
        </div>
    </article>

    {{-- Related kegiatan (tipe sama) --}}
    @if($terkait->isNotEmpty())
        <section class="article-related" aria-label="Kegiatan terkait">
            <div class="container">
                <div class="article-related-header">
                    <h2>Kegiatan {{ $badge['label'] }} Lainnya</h2>
                    <a href="{{ route('kemahasiswaan.kegiatan', ['tipe' => $kegiatan->tipeKegiatan?->slug]) }}" class="article-related-link">
                        Lihat Semua <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="row gy-4">
                    @foreach($terkait as $t)
                        @php($tBadge = $t->tipe_badge)
                        <div class="col-md-6 col-lg-4">
                            <article class="related-card">
                                <a href="{{ route('kemahasiswaan.kegiatan.show', $t->slug) }}" class="related-card-image" aria-label="Buka: {{ $t->judul }}">
                                    @if($t->gambar)
                                        <img src="{{ asset('storage/' . $t->gambar) }}"
                                             alt="{{ $t->judul }}"
                                             loading="lazy">
                                    @else
                                        <span class="related-card-placeholder" aria-hidden="true">
                                            <i class="bi {{ $tBadge['icon'] }}"></i>
                                        </span>
                                    @endif
                                </a>
                                <div class="related-card-body">
                                    <span class="related-card-tag">{{ $tBadge['label'] }}</span>
                                    <h3 class="related-card-title">
                                        <a href="{{ route('kemahasiswaan.kegiatan.show', $t->slug) }}">{{ Str::limit($t->judul, 75) }}</a>
                                    </h3>
                                    <p class="related-card-meta">
                                        <i class="bi bi-calendar3" aria-hidden="true"></i>
                                        @tanggal($t->tanggal, 'd F Y')
                                    </p>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection

@push('scripts')
    <script>
        // Copy link ke clipboard + feedback kecil tanpa mengubah layout tombol.
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('[data-copy-url]');
            if (!btn) return;
            e.preventDefault();

            const url = btn.dataset.copyUrl;

            const showCopied = () => {
                let feedback = document.querySelector('.copy-feedback-popover');

                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'copy-feedback-popover';
                    feedback.setAttribute('role', 'status');
                    feedback.setAttribute('aria-live', 'polite');
                    document.body.appendChild(feedback);
                }

                feedback.textContent = 'Link disalin';

                const rect = btn.getBoundingClientRect();
                const showAbove = rect.top > 56;

                feedback.dataset.placement = showAbove ? 'top' : 'bottom';
                feedback.style.left = `${rect.left + (rect.width / 2)}px`;
                feedback.style.top = `${showAbove ? rect.top - 8 : rect.bottom + 8}px`;
                feedback.classList.remove('is-visible');

                window.clearTimeout(feedback.hideTimer);
                window.requestAnimationFrame(() => feedback.classList.add('is-visible'));
                feedback.hideTimer = window.setTimeout(() => {
                    feedback.classList.remove('is-visible');
                }, 1800);
            };

            const fallbackCopy = () => {
                const ta = document.createElement('textarea');
                ta.value = url;
                ta.style.position = 'fixed';
                ta.style.opacity = '0';
                document.body.appendChild(ta);
                ta.select();
                try { document.execCommand('copy'); showCopied(); } catch (_) {}
                document.body.removeChild(ta);
            };

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(showCopied).catch(fallbackCopy);
            } else {
                fallbackCopy();
            }
        });
    </script>
@endpush
