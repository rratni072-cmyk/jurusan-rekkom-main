{{--
|--------------------------------------------------------------------------
| Sidebar "Berita Terkini" (single source of truth)
|--------------------------------------------------------------------------
| Widget menampilkan berita terbaru di sidebar inner pages.
|
| Konsisten dengan pattern article-detail sidebar (.sidebar-section + .sidebar-news-*)
| yang dipakai di berita.show & kegiatan-show. Single styling, single markup.
|
| Data $artikelTerkini di-inject via App\Providers\ViewComposerServiceProvider
| memakai BeritaRepository. Default 5 items.
|--------------------------------------------------------------------------
--}}

<div class="sidebar-section">
    <h4 class="sidebar-heading">Berita Terkini</h4>

    @if(! empty($artikelTerkini) && count($artikelTerkini) > 0)
        <ul class="sidebar-news-list">
            @foreach($artikelTerkini as $artikel)
                <li class="sidebar-news-item">
                    <a href="{{ route('berita.show', $artikel->slug) }}"
                       class="sidebar-news-thumb"
                       aria-label="Baca: {{ $artikel->judul }}">
                        @if($artikel->gambar)
                            <img src="{{ asset('storage/' . $artikel->gambar) }}"
                                 alt="{{ $artikel->judul }}"
                                 loading="lazy">
                        @else
                            <span class="sidebar-news-placeholder" aria-hidden="true">
                                <i class="bi bi-newspaper"></i>
                            </span>
                        @endif
                    </a>
                    <div class="sidebar-news-content">
                        <a href="{{ route('berita.show', $artikel->slug) }}">{{ Str::limit($artikel->judul, 60) }}</a>
                        <span class="sidebar-news-date">
                            <i class="bi bi-calendar3" aria-hidden="true"></i>
                            @tanggal($artikel->tanggal_publikasi, 'd M Y')
                        </span>
                    </div>
                </li>
            @endforeach
        </ul>

        <a href="{{ route('berita.index') }}" class="sidebar-lihat-semua">
            Lihat Semua Berita <i class="bi bi-arrow-right" aria-hidden="true"></i>
        </a>
    @else
        <p class="sidebar-text text-muted">Belum ada artikel.</p>
    @endif
</div>
