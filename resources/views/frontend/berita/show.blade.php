@extends('layouts.frontend')
@section('title', $berita->judul)
@section('body_class', 'article-page')
@section('meta_description', Str::limit(strip_tags($berita->ringkasan ?? ''), 160))
@section('og_image', $berita->gambar ? asset('storage/' . $berita->gambar) : asset('frontend/img/logo-politani.png'))

{{--
|--------------------------------------------------------------------------
| Halaman Detail Berita — Editorial Magazine Pattern
|--------------------------------------------------------------------------
| Layout single-column readable (max 760px) mengikuti pattern blog kampus
| modern (ITB, UI, UGM). Hero image dominant → tags → title besar → lead →
| meta bar → body → share → prev/next → related.
|
| Sidebar TIDAK dipakai (berbeda dgn halaman profil) agar konten panjang
| lebih enak dibaca. Untuk artikel Pengabdian, info lokasi ditampilkan
| inline di meta bar (bukan sebagai signature visual block terpisah).
| Field `dampak_singkat` masih ada di DB tapi tidak ditampilkan di publik.
|--------------------------------------------------------------------------
--}}

@php
    use Illuminate\Support\Str;

    $authorName = $berita->penulis?->name ?? 'Redaksi Jurusan R&K';
    $authorInitials = collect(explode(' ', $authorName))
        ->filter()
        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
        ->take(2)
        ->join('');

    // Share URL (public, absolute). Fallback ke route canonical bila behind proxy.
    $shareUrl = route('berita.show', $berita->slug);
    $shareTitle = $berita->judul;

    // Context flags ($isPengajaran / $isPengabdian sudah di-pass dari controller).
    $isTridharma = $isPengajaran || $isPengabdian;
    $tridharmaLabel = $isPengajaran ? 'Pengajaran' : ($isPengabdian ? 'Pengabdian' : '');
    $tridharmaRoute = $isPengajaran ? route('tridharma.pengajaran') : ($isPengabdian ? route('tridharma.pengabdian') : '');

    // Kategori utama (first) untuk sidebar info box.
    $kategoriUtama = $berita->kategoris->first();

    // Deskripsi kategori untuk sidebar — static map (tidak ada field di DB).
    // Fallback generik bila nama kategori tidak cocok.
    $kategoriDeskripsiMap = [
        'penelitian' => 'Aktivitas riset dosen dan mahasiswa Jurusan R&K untuk memajukan ilmu pengetahuan, publikasi ilmiah, dan hibah DRPM.',
        'pengabdian masyarakat' => 'Kontribusi tridharma perguruan tinggi melalui kegiatan pengabdian dan pembinaan masyarakat di Kalimantan Timur.',
        'pengabdian' => 'Kontribusi tridharma perguruan tinggi melalui kegiatan pengabdian dan pembinaan masyarakat di Kalimantan Timur.',
        'pengajaran' => 'Inovasi pembelajaran, kurikulum, dan pengembangan metode pengajaran di 4 program studi Jurusan R&K.',
        'akademik' => 'Informasi akademik resmi: jadwal perkuliahan, ujian, dan pengumuman kegiatan belajar.',
        'kemahasiswaan' => 'Organisasi, prestasi, kegiatan, dan pengembangan soft skill mahasiswa Jurusan R&K.',
        'workshop' => 'Kegiatan workshop dan pelatihan untuk peningkatan kompetensi civitas akademika.',
        'beasiswa' => 'Informasi beasiswa untuk mahasiswa aktif dan calon mahasiswa Jurusan R&K.',
        'prestasi' => 'Prestasi mahasiswa dan dosen di tingkat regional, nasional, maupun internasional.',
    ];
    $kategoriDeskripsi = $kategoriUtama
        ? ($kategoriDeskripsiMap[strtolower($kategoriUtama->nama)] ?? 'Artikel kategori ' . $kategoriUtama->nama . ' dari Jurusan Rekayasa dan Komputer Politeknik Pertanian Negeri Samarinda.')
        : null;
@endphp

@section('content')

    {{-- Breadcrumb compact — context-aware: Berita vs Tridharma (Pengajaran/Pengabdian) --}}
    <nav class="article-breadcrumb" aria-label="breadcrumb">
        <div class="container">
            <ol>
                <li><a href="{{ route('home') }}">Beranda</a></li>
                @if($isTridharma)
                    <li><span class="breadcrumb-disabled">Tridharma</span></li>
                    <li><a href="{{ $tridharmaRoute }}">{{ $tridharmaLabel }}</a></li>
                @else
                    <li><a href="{{ route('berita.index') }}">Berita</a></li>
                @endif
                <li class="current" aria-current="page">{{ Str::limit($berita->judul, 60) }}</li>
            </ol>
        </div>
    </nav>

    {{-- Article wrapper TANPA data-aos — AOS menambahkan CSS transform yang
         membuat sidebar position:sticky kehilangan viewport scroll context
         (sidebar jadi scroll dengan wrapper selama animasi). Gunakan AOS pada
         elemen spesifik di dalam .article-inner yang bukan ancestor sidebar. --}}
    <article class="article-wrapper">
        <div class="container">
            <div class="article-layout">
                {{-- Hero Image — sebagai grid child (bukan wrapper terpisah) agar
                     sidebar bisa sticky dari scroll=0 (natural top sidebar
                     setelah breadcrumb, bukan setelah hero). --}}
                @if($berita->gambar)
                    <figure class="article-hero-figure">
                        <img src="{{ asset('storage/' . $berita->gambar) }}"
                             alt="{{ $berita->judul }}"
                             class="article-hero-img"
                             fetchpriority="high">
                    </figure>
                @endif

                {{-- Kolom utama: konten artikel --}}
                <div class="article-inner">

                {{-- Tags: kategori + prodi badge (konsisten dgn teaching-card / community-card) --}}
                <div class="article-tags">
                    @foreach($berita->kategoris as $kat)
                        <span class="article-tag @if(str_contains(strtolower($kat->nama), 'pengabdian')) article-tag--community @endif">
                            {{ $kat->nama }}
                        </span>
                    @endforeach
                    <span class="article-tag article-tag--prodi">
                        <i class="bi bi-mortarboard-fill" aria-hidden="true"></i>{{ $berita->prodi_badge_label }}
                    </span>
                </div>

                {{-- Judul utama --}}
                <h1 class="article-title">{{ $berita->judul }}</h1>

                {{-- Lead paragraph (ringkasan) --}}
                @if($berita->ringkasan)
                    <p class="article-lead">{{ $berita->ringkasan }}</p>
                @endif

                {{-- Meta bar: author + tanggal + reading time + share inline --}}
                <div class="article-meta">
                    <div class="article-author-mini">
                        <span class="author-avatar" aria-hidden="true">{{ $authorInitials ?: 'RK' }}</span>
                        <div class="author-info">
                            <strong>{{ $authorName }}</strong>
                        </div>
                    </div>
                    <div class="article-meta-info">
                        <span class="meta-item" title="Tanggal publikasi">
                            <i class="bi bi-calendar3" aria-hidden="true"></i>
                            <time datetime="{{ $berita->tanggal_publikasi?->toDateString() }}">@tanggal($berita->tanggal_publikasi, 'd F Y')</time>
                        </span>
                        <span class="meta-item" title="Estimasi waktu baca">
                            <i class="bi bi-clock" aria-hidden="true"></i>
                            {{ $berita->reading_time }} menit baca
                        </span>
                        @if($isPengabdian && $berita->lokasi)
                            <span class="meta-item" title="Lokasi pengabdian">
                                <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
                                {{ $berita->lokasi }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Konten artikel (rich text dari TinyMCE) --}}
                <div class="article-body blog-content">
                    {!! $berita->konten !!}
                </div>

                {{-- Share Section (bottom) --}}
                <div class="article-share" role="group" aria-label="Bagikan artikel">
                    <p class="article-share-label">
                        <i class="bi bi-share-fill" aria-hidden="true"></i>
                        Bagikan artikel ini
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
                                aria-label="Salin link artikel">
                            <i class="bi bi-link-45deg" aria-hidden="true"></i>
                            <span class="share-btn-label">Salin Link</span>
                        </button>
                    </div>
                </div>

                {{-- Prev / Next Navigation --}}
                @if($prev || $next)
                    <nav class="article-nav" aria-label="Navigasi artikel">
                        @if($prev)
                            <a href="{{ route('berita.show', $prev->slug) }}" class="article-nav-item article-nav-item--prev">
                                <span class="nav-label">
                                    <i class="bi bi-arrow-left" aria-hidden="true"></i>Artikel Sebelumnya
                                </span>
                                <span class="nav-title">{{ Str::limit($prev->judul, 80) }}</span>
                            </a>
                        @else
                            <div class="article-nav-item article-nav-item--empty" aria-hidden="true"></div>
                        @endif

                        @if($next)
                            <a href="{{ route('berita.show', $next->slug) }}" class="article-nav-item article-nav-item--next">
                                <span class="nav-label">
                                    Artikel Berikutnya<i class="bi bi-arrow-right" aria-hidden="true"></i>
                                </span>
                                <span class="nav-title">{{ Str::limit($next->judul, 80) }}</span>
                            </a>
                        @else
                            <div class="article-nav-item article-nav-item--empty" aria-hidden="true"></div>
                        @endif
                    </nav>
                @endif

                </div>{{-- /.article-inner (kolom utama) --}}

                {{-- Sidebar kanan (desktop sticky, mobile stack) --}}
                <aside class="article-sidebar" aria-label="Informasi terkait">

                    {{-- Sidebar: Tentang Kategori --}}
                    @if($kategoriUtama && $kategoriDeskripsi)
                        <div class="sidebar-section">
                            <h4 class="sidebar-heading">Kategori: {{ $kategoriUtama->nama }}</h4>
                            <p class="sidebar-text">{{ $kategoriDeskripsi }}</p>
                        </div>
                    @endif

                    {{-- Sidebar: Artikel Terkini — heading & link sesuai konteks --}}
                    @if($beritaTerkini->isNotEmpty())
                        <div class="sidebar-section">
                            <h4 class="sidebar-heading">
                                {{ $isTridharma ? $tridharmaLabel . ' Terkini' : 'Berita Terkini' }}
                            </h4>
                            <ul class="sidebar-news-list">
                                @foreach($beritaTerkini as $tk)
                                    <li class="sidebar-news-item">
                                        <a href="{{ route('berita.show', $tk->slug) }}" class="sidebar-news-thumb" aria-label="Baca: {{ $tk->judul }}">
                                            @if($tk->gambar)
                                                <img src="{{ asset('storage/' . $tk->gambar) }}"
                                                     alt="{{ $tk->judul }}" loading="lazy">
                                            @else
                                                <span class="sidebar-news-placeholder" aria-hidden="true">
                                                    <i class="bi bi-newspaper"></i>
                                                </span>
                                            @endif
                                        </a>
                                        <div class="sidebar-news-content">
                                            <a href="{{ route('berita.show', $tk->slug) }}">{{ Str::limit($tk->judul, 70) }}</a>
                                            <span class="sidebar-news-date">
                                                <i class="bi bi-calendar3" aria-hidden="true"></i>
                                                @tanggal($tk->tanggal_publikasi, 'd M Y')
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ $isTridharma ? $tridharmaRoute : route('berita.index') }}" class="sidebar-lihat-semua">
                                Lihat Semua {{ $isTridharma ? $tridharmaLabel : 'Berita' }} <i class="bi bi-arrow-right" aria-hidden="true"></i>
                            </a>
                        </div>
                    @endif

                    {{-- Sidebar: Tag Populer --}}
                    @if($tagPopuler->isNotEmpty())
                        <div class="sidebar-section">
                            <h4 class="sidebar-heading">Tag Populer</h4>
                            <div class="sidebar-tags">
                                @foreach($tagPopuler as $tag)
                                    <a href="{{ route('berita.index', ['search' => $tag->nama]) }}"
                                       class="sidebar-tag"
                                       title="{{ $tag->beritas_count }} artikel">
                                        {{ $tag->nama }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </aside>

            </div>{{-- /.article-layout --}}
        </div>
    </article>

    {{-- Related articles — heading & link sesuai konteks --}}
    @if($terkait->isNotEmpty())
        <section class="article-related" aria-label="{{ $isTridharma ? $tridharmaLabel : 'Berita' }} terkait">
            <div class="container">
                <div class="article-related-header">
                    <h2>{{ $isTridharma ? $tridharmaLabel : 'Berita' }} Terkait</h2>
                    <a href="{{ $isTridharma ? $tridharmaRoute : route('berita.index') }}" class="article-related-link">
                        Lihat Semua <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="row gy-4">
                    @foreach($terkait as $t)
                        <div class="col-md-6 col-lg-4">
                            <article class="related-card">
                                <a href="{{ route('berita.show', $t->slug) }}" class="related-card-image" aria-label="Baca: {{ $t->judul }}">
                                    @if($t->gambar)
                                        <img src="{{ asset('storage/' . $t->gambar) }}"
                                             alt="{{ $t->judul }}"
                                             loading="lazy">
                                    @else
                                        <span class="related-card-placeholder" aria-hidden="true">
                                            <i class="bi bi-newspaper"></i>
                                        </span>
                                    @endif
                                </a>
                                <div class="related-card-body">
                                    @if($t->kategoris->isNotEmpty())
                                        <span class="related-card-tag">{{ $t->kategoris->first()->nama }}</span>
                                    @endif
                                    <h3 class="related-card-title">
                                        <a href="{{ route('berita.show', $t->slug) }}">{{ Str::limit($t->judul, 75) }}</a>
                                    </h3>
                                    <p class="related-card-meta">
                                        <i class="bi bi-calendar3" aria-hidden="true"></i>
                                        @tanggal($t->tanggal_publikasi, 'd F Y')
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
