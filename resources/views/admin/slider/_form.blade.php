{{-- Form fields slider — dipakai di create.blade.php dan edit.blade.php --}}

<div class="row">
    <div class="col-md-8">
        {{-- Judul --}}
        <div class="mb-3">
            <label for="judul" class="form-label">Judul Slider <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('judul') is-invalid @enderror"
                id="judul" name="judul"
                value="{{ old('judul', $slider->judul ?? '') }}"
                placeholder="Masukkan judul slider" required>
            @error('judul')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Deskripsi --}}
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                id="deskripsi" name="deskripsi" rows="3"
                placeholder="Deskripsi singkat slider (opsional)">{{ old('deskripsi', $slider->deskripsi ?? '') }}</textarea>
            @error('deskripsi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tombol Teks & URL --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="tombol_teks" class="form-label">Teks Tombol</label>
                <input type="text" class="form-control @error('tombol_teks') is-invalid @enderror"
                    id="tombol_teks" name="tombol_teks"
                    value="{{ old('tombol_teks', $slider->tombol_teks ?? '') }}"
                    placeholder="Contoh: Selengkapnya">
                @error('tombol_teks')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="tombol_url" class="form-label">URL Tombol</label>
                <input type="text" class="form-control @error('tombol_url') is-invalid @enderror"
                    id="tombol_url" name="tombol_url"
                    value="{{ old('tombol_url', $slider->tombol_url ?? '') }}"
                    placeholder="Contoh: /profil/visi-misi">
                @error('tombol_url')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-md-4">
        {{-- Gambar --}}
        <div class="mb-3">
            <label for="gambar" class="form-label">
                Gambar Slider
                @if(!isset($slider)) <span class="text-danger">*</span> @endif
            </label>
            <input type="file" class="form-control @error('gambar') is-invalid @enderror"
                id="gambar" name="gambar" accept="image/*"
                data-preview-target="#gambar-preview">
            @error('gambar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">Format: JPG, PNG, WebP. Maks: 2MB. Rasio: 16:9</div>

            {{-- Preview --}}
            <input type="hidden" name="hapus_gambar" id="hapus_gambar" value="0">
            <div class="mt-2 position-relative d-inline-block" id="gambar-preview-wrapper">
                <img id="gambar-preview"
                    src="{{ isset($slider) && $slider->gambar ? asset('storage/' . $slider->gambar) : '' }}"
                    alt="{{ isset($slider) && $slider->gambar ? 'Pratinjau gambar slider: ' . $slider->judul : 'Pratinjau gambar slider' }}"
                    loading="lazy"
                    decoding="async"
                    class="img-fluid rounded {{ isset($slider) && $slider->gambar ? '' : 'd-none' }}"
                    style="max-height: 200px;">
                @if(isset($slider) && $slider->gambar)
                    <button type="button" class="btn-hapus-gambar-x" id="btn-hapus-gambar"
                        aria-label="Hapus gambar" title="Hapus gambar">
                        <i class="bi bi-x-lg"></i>
                    </button>
                @endif
            </div>
        </div>

        {{-- Urutan --}}
        <div class="mb-3">
            <label for="urutan" class="form-label">Urutan <span class="text-danger">*</span></label>
            <input type="number" class="form-control @error('urutan') is-invalid @enderror"
                id="urutan" name="urutan"
                value="{{ old('urutan', $slider->urutan ?? 0) }}"
                min="0" required>
            @error('urutan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox"
                    id="is_active" name="is_active" value="1"
                    {{ old('is_active', $slider->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Slider Aktif</label>
            </div>
        </div>
    </div>
</div>

@include('admin.partials._hapus-gambar-assets')

@push('scripts')
<script>
    // Image preview via event listener (tanpa inline onchange handler)
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('gambar');
        if (!fileInput) return;
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
    });
</script>
@endpush
