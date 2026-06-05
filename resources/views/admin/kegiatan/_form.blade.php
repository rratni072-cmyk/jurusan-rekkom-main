{{-- Form fields kegiatan — dipakai di create.blade.php dan edit.blade.php --}}
{{-- Layout 2-kolom konsisten dengan berita/_form.blade.php --}}
{{-- Integrasi: TinyMCE 7 (WYSIWYG) --}}

<div class="row">
    {{-- Kolom Kiri: Konten Utama --}}
    <div class="col-md-8">
        {{-- Judul --}}
        <div class="card mb-3">
            <div class="card-body">
                <label for="judul" class="form-label">Judul Kegiatan <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('judul') is-invalid @enderror"
                    id="judul" name="judul"
                    value="{{ old('judul', $kegiatan->judul ?? '') }}"
                    placeholder="Masukkan judul kegiatan" required>
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
                    placeholder="Ringkasan singkat (opsional, maks 500 karakter)">{{ old('ringkasan', $kegiatan->ringkasan ?? '') }}</textarea>
                @error('ringkasan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Konten (TinyMCE) --}}
        <div class="card mb-3">
            <div class="card-body">
                <label for="konten" class="form-label">Konten Kegiatan <span class="text-danger">*</span></label>
                <textarea class="form-control @error('konten') is-invalid @enderror"
                    id="konten" name="konten"
                    rows="15">{{ old('konten', $kegiatan->konten ?? '') }}</textarea>
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
            <div class="card-header"><strong>Gambar Kegiatan</strong></div>
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
                        src="{{ isset($kegiatan) && $kegiatan->gambar ? asset('storage/' . $kegiatan->gambar) : '' }}"
                        alt="{{ isset($kegiatan) && $kegiatan->gambar ? 'Pratinjau gambar kegiatan: ' . $kegiatan->judul : 'Pratinjau gambar kegiatan' }}"
                        loading="lazy"
                        decoding="async"
                        class="img-fluid rounded {{ isset($kegiatan) && $kegiatan->gambar ? '' : 'd-none' }}"
                        style="max-height: 200px;">
                    @if(isset($kegiatan) && $kegiatan->gambar)
                        <button type="button" class="btn-hapus-gambar-x" id="btn-hapus-gambar"
                            aria-label="Hapus gambar" title="Hapus gambar">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Publikasi --}}
        <div class="card mb-3">
            <div class="card-header"><strong>Publikasi</strong></div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal Kegiatan <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                        id="tanggal" name="tanggal"
                        value="{{ old('tanggal', isset($kegiatan) && $kegiatan->tanggal ? $kegiatan->tanggal->format('Y-m-d') : date('Y-m-d')) }}"
                        required>
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tipe Kegiatan: dropdown dari master tipe_kegiatans (editable lewat
                     /admin/tipe-kegiatan). Hanya tipe is_active=true yang muncul. --}}
                <div class="mb-3">
                    <label for="tipe_kegiatan_id" class="form-label">Tipe Kegiatan <span class="text-danger">*</span></label>
                    <select class="form-select @error('tipe_kegiatan_id') is-invalid @enderror"
                        id="tipe_kegiatan_id" name="tipe_kegiatan_id" required>
                        <option value="">-- Pilih Tipe --</option>
                        @foreach($tipeList ?? [] as $tipe)
                            @if($tipe->is_active || (isset($kegiatan) && $kegiatan->tipe_kegiatan_id === $tipe->id))
                                <option value="{{ $tipe->id }}"
                                    @selected(old('tipe_kegiatan_id', $kegiatan->tipe_kegiatan_id ?? null) == $tipe->id)>
                                    {{ $tipe->label }}@if(! $tipe->is_active) (non-aktif)@endif
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <div class="form-text">
                        Kelola daftar tipe di <a href="{{ route('admin.tipe-kegiatan.index') }}">Tipe Kegiatan</a>.
                    </div>
                    @error('tipe_kegiatan_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check form-switch">
                    {{-- Hidden 0 sebelum checkbox supaya unchecked tetap terkirim sebagai 0 --}}
                    <input type="hidden" name="is_published" value="0">
                    <input class="form-check-input" type="checkbox"
                        id="is_published" name="is_published" value="1"
                        {{ old('is_published', $kegiatan->is_published ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_published">Langsung Publikasi</label>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TinyMCE 7 (shared component) — konsisten dengan halaman lain.
     uploadUrl aktif → admin bisa upload gambar inline saat tulis kegiatan. --}}
@include('components.admin.tinymce-init', [
    'selector'  => '#konten',
    'height'    => 400,
    'menubar'   => true,
    'uploadUrl' => route('admin.tinymce.upload-image'),
])

@include('admin.partials._hapus-gambar-assets')

@push('scripts')
    <script>
        // Image preview untuk field gambar utama (via event listener, tanpa inline onchange)
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
