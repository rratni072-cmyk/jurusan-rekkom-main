{{--
    Partial: Berita Section (homepage)
    Param:
      $beritas : Collection<App\Models\Berita>
--}}
<section id="berita" class="section berita-section" aria-labelledby="berita-heading">
    <div class="container section-title" data-aos="fade-up">
        <span class="section-kicker">Berita Jurusan</span>
        <h2 id="berita-heading">Berita Terbaru Jurusan</h2>
        <p>
            Informasi terkini seputar kegiatan akademik, prestasi, dan pengumuman
            Jurusan Rekayasa &amp; Komputer.
        </p>
    </div>

    <div class="container" data-aos="fade-up" data-aos-delay="100">
        @if($beritas->isNotEmpty())
            @php
                $beritaUtama   = $beritas->first();
                $beritaLainnya = $beritas->skip(1);
            @endphp

            <div class="row g-4 align-items-stretch berita-highlight-grid">
                <div class="{{ $beritaLainnya->isNotEmpty() ? 'col-lg-6' : 'col-lg-8 mx-auto' }}">
                    @include('frontend.partials.berita._featured', ['berita' => $beritaUtama])
                </div>

                @if($beritaLainnya->isNotEmpty())
                    <div class="col-lg-6">
                        <div class="news-list-panel h-100">
                            <header class="news-list-header">
                                <div>
                                    <span class="news-list-kicker">Update Terkini</span>
                                    <h3>Informasi terbaru</h3>
                                </div>
                                <a href="{{ route('berita.index') }}" class="news-list-archive">
                                    Arsip Berita <i class="bi bi-arrow-right" aria-hidden="true"></i>
                                </a>
                            </header>
                            <div class="news-list-items" role="list">
                                @foreach($beritaLainnya as $berita)
                                    <div role="listitem">
                                        @include('frontend.partials.berita._list-item', [
                                            'berita'    => $berita,
                                            'iteration' => $loop->iteration,
                                        ])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @else
            @include('frontend.partials.berita._empty')
        @endif

        <div class="news-section-footer">
            <a href="{{ route('berita.index') }}"
               class="btn btn-outline-primary px-5 py-2 rounded-pill fw-semibold"
               aria-label="Lihat semua arsip berita">
                <i class="bi bi-newspaper me-1" aria-hidden="true"></i> Lihat Semua Berita
            </a>
        </div>
    </div>
</section>
