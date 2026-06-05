{{--
    Partial: Berita List Item (sidebar pada home)
    Param:
      $berita    : App\Models\Berita
      $iteration : int (loop->iteration untuk fallback gambar)
--}}
@php
    $kategori = $berita->kategoris->first();
    $kategoriClass = $kategori?->color_class ?? 'news-category--umum';
    $kategoriLabel = $kategori?->nama ?? 'Umum';
    $kategoriSlug = $kategori?->slug ?? 'umum';
@endphp
<article class="news-list-card"
         data-article-link="{{ $berita->slug }}"
         aria-labelledby="berita-list-{{ $berita->id }}">
    <a href="{{ route('berita.show', $berita->slug) }}"
       class="news-list-image {{ ! $berita->gambar ? 'news-fallback news-fallback--'.$kategoriSlug : '' }}"
       data-article-link="{{ $berita->slug }}"
       aria-label="Baca berita: {{ $berita->judul }}">
        @if($berita->gambar)
            <img src="{{ asset('storage/' . $berita->gambar) }}"
                 alt="{{ $berita->judul }}"
                 loading="lazy"
                 decoding="async">
        @else
            <span class="news-fallback-icon" aria-hidden="true">
                <i class="bi bi-newspaper"></i>
            </span>
        @endif
    </a>
    <div class="news-list-content">
        <div class="news-meta">
            <span class="news-category {{ $kategoriClass }}">{{ $kategoriLabel }}</span>
            <span class="news-date">
                <i class="bi bi-calendar3" aria-hidden="true"></i>
                <time datetime="{{ $berita->tanggal_publikasi->toDateString() }}">
                    @tanggal($berita->tanggal_publikasi, 'd M Y')
                </time>
            </span>
        </div>
        <h4 id="berita-list-{{ $berita->id }}">
            <a href="{{ route('berita.show', $berita->slug) }}"
               data-article-link="{{ $berita->slug }}">{{ $berita->judul }}</a>
        </h4>
        <p>{{ Str::words(strip_tags($berita->ringkasan ?? ''), 18, '…') }}</p>
    </div>
</article>
