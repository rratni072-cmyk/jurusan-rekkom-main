{{--
    Form fields Tridharma — dipakai di create.blade.php dan edit.blade.php.
    Variabel dari controller:
      $type      : 'pengajaran' | 'pengabdian'
      $label     : 'Pengajaran' | 'Pengabdian Masyarakat'
      $prodiList : Collection ProgramStudi (untuk dropdown)
      $berita    : (opsional, hanya saat edit) instance Berita

    Field khusus Pengabdian (lokasi, dampak_singkat) hanya muncul saat $type='pengabdian'.
--}}

<div class="row">
    {{-- Kolom Kiri: Konten Utama --}}
    <div class="col-md-8">
        {{-- Judul --}}
        <div class="card mb-3">
            <div class="card-body">
                <label for="judul" class="form-label">Judul {{ $label }} <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('judul') is-invalid @enderror"
                    id="judul" name="judul"
                    value="{{ old('judul', $berita->judul ?? '') }}"
                    placeholder="Masukkan judul {{ Str::lower($label) }}" required>
                @error('judul')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Ringkasan --}}
        <div class="card mb-3">
            <div class="card-body">
                <label for="ringkasan" class="form-label">Ringkasan</label>
                <textarea class="form-control @error('ringkasan') is-invalid @enderror"
                    id="ringkasan" name="ringkasan" rows="2"
                    placeholder="Ringkasan singkat (opsional, maks 500 karakter)">{{ old('ringkasan', $berita->ringkasan ?? '') }}</textarea>
                @error('ringkasan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Konten (TinyMCE) --}}
        <div class="card mb-3">
            <div class="card-body">
                <label for="konten" class="form-label">Konten {{ $label }} <span class="text-danger">*</span></label>
                <textarea class="form-control @error('konten') is-invalid @enderror"
                    id="konten" name="konten"
                    rows="15">{{ old('konten', $berita->konten ?? '') }}</textarea>
                @error('konten')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Field khusus Pengabdian: Lokasi & Dampak Singkat --}}
        @if($type === 'pengabdian')
            <div class="card mb-3 border-success-subtle">
                <div class="card-header bg-success-subtle text-success-emphasis">
                    <strong>
                        <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-location-pin') }}"></use></svg>
                        Detail Pengabdian
                    </strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="lokasi" class="form-label">Lokasi Kegiatan</label>
                            <input type="text" class="form-control @error('lokasi') is-invalid @enderror"
                                id="lokasi" name="lokasi"
                                value="{{ old('lokasi', $berita->lokasi ?? '') }}"
                                placeholder="contoh: Kec. Samarinda Seberang"
                                maxlength="150">
                            @error('lokasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Tempat kegiatan pengabdian dilakukan.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="dampak_singkat" class="form-label">Dampak Singkat</label>
                            <input type="text" class="form-control @error('dampak_singkat') is-invalid @enderror"
                                id="dampak_singkat" name="dampak_singkat"
                                value="{{ old('dampak_singkat', $berita->dampak_singkat ?? '') }}"
                                placeholder="contoh: 30 UMKM binaan"
                                maxlength="100">
                            @error('dampak_singkat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Hasil/dampak kegiatan dalam frasa singkat.</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Kolom Kanan: Meta & Settings --}}
    <div class="col-md-4">
        {{-- Gambar --}}
        <div class="card mb-3">
            <div class="card-header"><strong>Gambar Utama</strong></div>
            <div class="card-body">
                <input type="file" class="form-control @error('gambar') is-invalid @enderror"
                    id="gambar" name="gambar" accept="image/*"
                    data-preview-target="#gambar-preview">
                @error('gambar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Format: JPG, PNG, WebP. Maks: 2MB</div>
                <input type="hidden" name="hapus_gambar" id="hapus_gambar" value="0">
                <div class="mt-2 position-relative d-inline-block" id="gambar-preview-wrapper">
                    <img id="gambar-preview"
                        src="{{ isset($berita) && $berita->gambar ? asset('storage/' . $berita->gambar) : '' }}"
                        alt="{{ isset($berita) && $berita->gambar ? 'Pratinjau gambar: ' . $berita->judul : 'Pratinjau gambar utama' }}"
                        loading="lazy"
                        decoding="async"
                        class="img-fluid rounded {{ isset($berita) && $berita->gambar ? '' : 'd-none' }}"
                        style="max-height: 200px;">
                    @if(isset($berita) && $berita->gambar)
                        <button type="button" class="btn-hapus-gambar-x" id="btn-hapus-gambar"
                            aria-label="Hapus gambar" title="Hapus gambar">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Program Studi --}}
        <div class="card mb-3">
            <div class="card-header">
                <label for="program_studi_id" class="mb-0"><strong>Program Studi</strong></label>
            </div>
            <div class="card-body">
                <select id="program_studi_id" name="program_studi_id"
                    class="form-select @error('program_studi_id') is-invalid @enderror">
                    <option value="">— Lintas Jurusan —</option>
                    @foreach($prodiList as $prodi)
                        <option value="{{ $prodi->id }}"
                            {{ old('program_studi_id', $berita->program_studi_id ?? null) == $prodi->id ? 'selected' : '' }}>
                            {{ $prodi->jenjang }} {{ $prodi->nama }}
                        </option>
                    @endforeach
                </select>
                @error('program_studi_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Kosongkan jika kegiatan lintas jurusan.</div>
            </div>
        </div>

        {{-- Publikasi --}}
        <div class="card mb-3">
            <div class="card-header"><strong>Publikasi</strong></div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="tanggal_publikasi" class="form-label">Tanggal Publikasi</label>
                    <input type="date" class="form-control @error('tanggal_publikasi') is-invalid @enderror"
                        id="tanggal_publikasi" name="tanggal_publikasi"
                        value="{{ old('tanggal_publikasi', isset($berita) && $berita->tanggal_publikasi ? $berita->tanggal_publikasi->format('Y-m-d') : date('Y-m-d')) }}">
                    @error('tanggal_publikasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox"
                        id="is_published" name="is_published" value="1"
                        {{ old('is_published', $berita->is_published ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_published">Langsung Publikasi</label>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TinyMCE 7 (shared component, dengan upload gambar inline aktif) --}}
@include('components.admin.tinymce-init', [
    'selector'  => '#konten',
    'height'    => 500,
    'menubar'   => true,
    'uploadUrl' => route('admin.tinymce.upload-image'),
])

@include('admin.partials._hapus-gambar-assets')

@push('scripts')
    <script>
        // Image preview untuk field gambar utama
        document.addEventListener('DOMContentLoaded', function () {
            const fileInput = document.getElementById('gambar');
            if (fileInput) {
                fileInput.addEventListener('change', function (event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    const targetSelector = event.target.getAttribute('data-preview-target') || '#gambar-preview';
                    const preview = document.querySelector(targetSelector);
                    if (!preview) return;
                    const reader = new FileReader();
                    reader.onload = function () {
                        preview.src = reader.result;
                        preview.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    </script>
@endpush
