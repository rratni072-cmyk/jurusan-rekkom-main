{{-- 
    Cover image partial untuk halaman profil (tentang, visi-misi, struktur).

    Render <figure> dengan aspect-ratio terjaga + lazy-load.
    Hanya tampil jika $profil memiliki `gambar` terisi.

    Required vars:
      - $profil : Model ProfilJurusan (atau null-able)
      - $alt    : string alt text (SEO + a11y)
--}}
@if(($profil->gambar ?? null))
    <figure class="profil-cover mb-0">
        <img src="{{ asset('storage/' . $profil->gambar) }}"
             alt="{{ $alt }}"
             class="profil-cover-img"
             loading="lazy"
             decoding="async"
             width="1200"
             height="675">
    </figure>
@endif
