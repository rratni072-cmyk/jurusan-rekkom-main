{{--
    Partial: Berita Card Featured (homepage)
    Param:
      $berita : App\Models\Berita
--}}
@php
    $kategori = $berita->kategoris->first();
    $kategoriClass = $kategori?->color_class ?? 'news-category--umum';
    $kategoriLabel = $kategori?->nama ?? 'Umum';
    $kategoriSlug = $kategori?->slug ?? 'umum';
@endphp
<article class="news-featured-card h-100"
         data-article-link="{{ $berita->slug }}"
         aria-labelledby="berita-utama-{{ $berita->id }}">
    <a href="{{ route('berita.show', $berita->slug) }}"
       class="news-featured-media {{ ! $berita->gambar ? 'news-fallback news-fallback--'.$kategoriSlug : '' }}"
       data-article-link="{{ $berita->slug }}"
       aria-label="Baca berita utama: {{ $berita->judul }}">
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
        <span class="news-featured-overlay" aria-hidden="true"></span>
        <span class="news-featured-flag" aria-hidden="true">
            <i class="bi bi-star-fill"></i> Berita Utama
        </span>
    </a>
    <div class="news-featured-body">
        <div class="news-meta">
            <span class="news-category {{ $kategoriClass }}">{{ $kategoriLabel }}</span>
            <span class="news-date">
                <i class="bi bi-calendar3" aria-hidden="true"></i>
                <time datetime="{{ $berita->tanggal_publikasi->toDateString() }}">
                    @tanggal($berita->tanggal_publikasi, 'd F Y')
                </time>
            </span>
            <span class="news-reading-time" aria-label="Estimasi waktu baca">
                <i class="bi bi-clock" aria-hidden="true"></i>
                {{ $berita->reading_time }} menit baca
            </span>
        </div>
        <h3 id="berita-utama-{{ $berita->id }}">
            <a href="{{ route('berita.show', $berita->slug) }}"
               data-article-link="{{ $berita->slug }}">{{ $berita->judul }}</a>
        </h3>
        <p class="news-featured-excerpt">
            {{ Str::words(strip_tags($berita->ringkasan ?? ''), 38, '…') }}
        </p>
        <div class="news-featured-footer">
            @if($berita->penulis)
                <span class="news-author" aria-label="Penulis berita">
                    <i class="bi bi-person-circle" aria-hidden="true"></i>
                    Oleh <strong>{{ $berita->penulis->name }}</strong>
                </span>
            @else
                <span class="news-author">
                    <i class="bi bi-building" aria-hidden="true"></i>
                    Humas Jurusan R&amp;K
                </span>
            @endif
            <a href="{{ route('berita.show', $berita->slug) }}"
               class="btn btn-primary btn-sm news-featured-cta"
               data-article-link="{{ $berita->slug }}"
               aria-label="Baca selengkapnya: {{ $berita->judul }}">
                Baca Berita Utama <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</article>
