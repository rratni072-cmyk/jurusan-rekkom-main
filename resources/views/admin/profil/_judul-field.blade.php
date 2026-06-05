{{-- Reusable judul/heading input untuk Profil Jurusan.
     Pakai di admin tentang/visi-misi/struktur. Field WAJIB diisi.
     Kalau data belum pernah disimpan, input di-prefill dengan `defaultJudul`
     agar admin tinggal tweak — bukan ngisi dari nol. --}}
@props(['kunci', 'item', 'placeholder' => 'mis. Tentang Jurusan Rekayasa dan Komputer', 'defaultJudul' => ''])

<div class="mb-3">
    <label for="profil_{{ $kunci }}_judul" class="form-label">
        Judul Halaman <span class="text-danger">*</span>
    </label>
    <input type="text"
           class="form-control @error('profil.'.$kunci.'.judul') is-invalid @enderror"
           id="profil_{{ $kunci }}_judul"
           name="profil[{{ $kunci }}][judul]"
           value="{{ old('profil.'.$kunci.'.judul', $item->judul ?? $defaultJudul) }}"
           maxlength="255"
           placeholder="{{ $placeholder }}"
           required>
    @error('profil.'.$kunci.'.judul')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <div class="form-text">
        Heading utama yang tampil di dalam card halaman publik (wajib diisi, maks. 255 karakter).
        <strong class="text-body-secondary">Tidak mempengaruhi</strong> navbar, breadcrumb, dan judul tab browser.
    </div>
</div>
