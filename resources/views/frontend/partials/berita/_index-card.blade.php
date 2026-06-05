{{--
    Partial: Berita Index Card (halaman /berita)
    Editorial horizontal card: thumbnail kiri + body kanan.
    Variables:
      $berita : App\Models\Berita (eager-loaded: kategoris, penulis)
--}}
@php
    $kategori = $berita->kategoris->first();
    $kategoriClass = $kategori?->color_class ?? 'news-category--umum';
    $kategoriLabel = $kategori?->nama ?? 'Umum';
    $kategoriSlug = $kategori?->slug ?? 'umum';
    $authorName = $berita->penulis?->name ?? 'Redaksi Jurusan R&K';
@endphp

<article class="berita-index-card"
         data-article-link="{{ $berita->slug }}"
         aria-labelledby="berita-idx-{{ $berita->id }}">
    <a href="{{ route('berita.show', $berita->slug) }}"
       class="berita-index-card-image {{ ! $berita->gambar ? 'news-fallback news-fallback--'.$kategoriSlug : '' }}"
       data-article-link="{{ $berita->slug }}"
       aria-label="Baca berita: {{ $berita->judul }}">
        @if($berita->gambar)
            <img src="{{ asset('storage/' . $berita->gambar) }}"
                 alt="{{ $berita->judul }}"
                 loading="lazy" decoding="async">
        @else
            <span class="news-fallback-icon" aria-hidden="true">
                <i class="bi bi-newspaper"></i>
            </span>
        @endif
    </a>

    <div class="berita-index-card-body">
        <div class="berita-index-card-meta">
            <span class="news-category {{ $kategoriClass }}">{{ $kategoriLabel }}</span>
            <span class="berita-index-card-date">
                <i class="bi bi-calendar3" aria-hidden="true"></i>
                <time datetime="{{ $berita->tanggal_publikasi?->toDateString() }}">
                    @tanggal($berita->tanggal_publikasi, 'd M Y')
                </time>
            </span>
        </div>

        <h3 class="berita-index-card-title" id="berita-idx-{{ $berita->id }}">
            <a href="{{ route('berita.show', $berita->slug) }}"
               data-article-link="{{ $berita->slug }}">{{ $berita->judul }}</a>
        </h3>

        @if($berita->ringkasan)
            <p class="berita-index-card-excerpt">
                {{ Str::limit(strip_tags($berita->ringkasan), 160) }}
            </p>
        @endif

        <div class="berita-index-card-footer">
            <span class="berita-index-card-author" title="Penulis">
                <i class="bi bi-person-fill" aria-hidden="true"></i>
                {{ $authorName }}
            </span>
            <span class="berita-index-card-stats">
                <span title="Estimasi waktu baca">
                    <i class="bi bi-clock" aria-hidden="true"></i>
                    {{ $berita->reading_time }} menit
                </span>
            </span>
        </div>
    </div>
</article>
