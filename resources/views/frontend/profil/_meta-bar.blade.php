{{--
    Meta-bar partial untuk halaman profil.

    Render baris meta (URL + tanggal update) yang rapi:
    - Pakai flex-wrap → kalau item kedua tidak muat, otomatis pindah baris BARU
      sebagai satu kesatuan, BUKAN word-wrap dalam kolom yang sempit.
    - column-gap & row-gap diatur kecil supaya rapi saat stacking.

    Required vars:
      - $profil : Model ProfilJurusan (atau null)
--}}
<div class="profil-meta d-flex flex-wrap align-items-center text-muted small mb-4">
    <span class="profil-meta-item">
        <i class="bi bi-globe me-1" aria-hidden="true"></i>rekkom.politanisamarinda.ac.id
    </span>
    @if($profil->updated_at ?? false)
        <span class="profil-meta-item">
            <i class="bi bi-clock me-1" aria-hidden="true"></i>Diperbarui @waktuLokal($profil->updated_at)
        </span>
    @endif
</div>
