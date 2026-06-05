{{-- Form fields berita — dipakai di create.blade.php dan edit.blade.php --}}
{{-- Integrasi: TinyMCE 7 (WYSIWYG) + Tom Select (kategori multi-select) --}}

<div class="row">
    {{-- Kolom Kiri: Konten Utama --}}
    <div class="col-md-8">
        {{-- Judul --}}
        <div class="card mb-3">
            <div class="card-body">
                <label for="judul" class="form-label">Judul Berita <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('judul') is-invalid @enderror"
                    id="judul" name="judul"
                    value="{{ old('judul', $berita->judul ?? '') }}"
                    placeholder="Masukkan judul berita" required>
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
                <label for="konten" class="form-label">Konten Berita <span class="text-danger">*</span></label>
                <textarea class="form-control @error('konten') is-invalid @enderror"
                    id="konten" name="konten"
                    rows="15">{{ old('konten', $berita->konten ?? '') }}</textarea>
                @error('konten')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>
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
                        alt="{{ isset($berita) && $berita->gambar ? 'Pratinjau gambar utama: ' . $berita->judul : 'Pratinjau gambar utama berita' }}"
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

        {{-- Kategori (Tom Select) --}}
        <div class="card mb-3">
            <div class="card-header">
                <label for="kategori_ids" class="mb-0"><strong>Kategori</strong></label>
            </div>
            <div class="card-body">
                <select id="kategori_ids"
                        name="kategori_ids[]"
                        multiple
                        placeholder="Pilih kategori..."
                        aria-label="Pilih satu atau lebih kategori berita">
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}"
                            {{ in_array($kategori->id, old('kategori_ids', $selectedKategoris ?? [])) ? 'selected' : '' }}>
                            {{ $kategori->nama }}
                        </option>
                    @endforeach
                </select>
                <div class="form-text">Pilih lebih dari satu kategori dengan klik tag.</div>
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
    'uploadUrl' => route('admin.berita.upload-image'),
])

@include('admin.partials._hapus-gambar-assets')

@push('scripts')
    {{-- Tom Select (jQuery-free!) --}}
    <script src="{{ asset('admin/vendor/tom-select/tom-select.complete.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inisialisasi Tom Select (multi-select kategori)
            if (document.getElementById('kategori_ids')) {
                new TomSelect('#kategori_ids', {
                    plugins: ['remove_button'],
                    create: false,
                    maxItems: null,
                });
            }

            // Image preview untuk field gambar utama (via event listener, tanpa inline onchange)
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
