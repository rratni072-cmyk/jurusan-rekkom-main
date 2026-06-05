{{-- Form fields tipe kegiatan — dipakai di create.blade.php dan edit.blade.php --}}
{{-- Layout 2-kolom konsisten dengan kegiatan/_form.blade.php --}}

@php
    $tipe = $tipe ?? null;
    // Galeri ikon Bootstrap Icons yang umum dipakai untuk kegiatan akademik.
    // Admin bisa pilih cepat dari sini, atau ketik manual class lain.
    $iconPresets = [
        'bi-tools', 'bi-mic', 'bi-trophy', 'bi-building', 'bi-people', 'bi-mortarboard',
        'bi-briefcase', 'bi-book', 'bi-laptop', 'bi-camera-video', 'bi-megaphone', 'bi-flag',
        'bi-globe', 'bi-heart', 'bi-lightbulb', 'bi-pencil-square', 'bi-tag',
    ];
@endphp

<div class="row">
    {{-- Kolom Kiri: Field Utama --}}
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <label for="label" class="form-label">Label / Nama Tipe <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('label') is-invalid @enderror"
                        id="label" name="label"
                        value="{{ old('label', $tipe->label ?? '') }}"
                        placeholder="Contoh: Workshop, Seminar, Lomba"
                        required maxlength="100">
                    <div class="form-text">Teks yang muncul di badge & dropdown publik.</div>
                    @error('label')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror"
                        id="slug" name="slug"
                        value="{{ old('slug', $tipe->slug ?? '') }}"
                        placeholder="Auto-generate dari label kalau dikosongkan"
                        maxlength="50"
                        pattern="[a-z0-9_]+">
                    <div class="form-text">
                        Identifier mesin (huruf kecil, angka, underscore).
                        @if($tipe)
                            <span class="text-warning">Mengubah slug saat tipe sudah dipakai kegiatan tetap aman karena relasi via FK ID.</span>
                        @endif
                    </div>
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="icon" class="form-label">Ikon Bootstrap <span class="text-danger">*</span></label>
                    <div class="input-group @error('icon') is-invalid @enderror">
                        <span class="input-group-text">
                            <i id="icon-preview" class="bi {{ old('icon', $tipe->icon ?? 'bi-tag') }}" aria-hidden="true"></i>
                        </span>
                        <input type="text" class="form-control @error('icon') is-invalid @enderror"
                            id="icon" name="icon"
                            value="{{ old('icon', $tipe->icon ?? 'bi-tag') }}"
                            placeholder="bi-trophy"
                            required maxlength="50"
                            pattern="bi-[a-z0-9-]+">
                    </div>
                    <div class="form-text">
                        Format: <code>bi-*</code>. Browse semua ikon di
                        <a href="https://icons.getbootstrap.com/" target="_blank" rel="noopener noreferrer">icons.getbootstrap.com</a>.
                    </div>
                    @error('icon')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror

                    {{-- Preset ikon klik untuk auto-fill --}}
                    <div class="mt-2 d-flex flex-wrap gap-1">
                        @foreach($iconPresets as $preset)
                            <button type="button"
                                class="btn btn-sm btn-outline-secondary icon-preset"
                                data-icon="{{ $preset }}"
                                title="{{ $preset }}">
                                <i class="bi {{ $preset }}" aria-hidden="true"></i>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Pengaturan --}}
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header"><strong>Pengaturan</strong></div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="urutan" class="form-label">Urutan <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('urutan') is-invalid @enderror"
                        id="urutan" name="urutan"
                        value="{{ old('urutan', $tipe->urutan ?? 0) }}"
                        min="0" max="65535"
                        required>
                    <div class="form-text">Angka kecil = tampil di atas. Selisih 10 (10, 20, 30) memudahkan sisip.</div>
                    @error('urutan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check form-switch">
                    <input type="hidden" name="is_active" value="0">
                    <input class="form-check-input" type="checkbox"
                        id="is_active" name="is_active" value="1"
                        {{ old('is_active', $tipe->is_active ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Aktif (tampil di filter publik)</label>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var iconInput = document.getElementById('icon');
            var iconPreview = document.getElementById('icon-preview');
            var labelInput = document.getElementById('label');
            var slugInput = document.getElementById('slug');

            // Live preview ikon saat input berubah.
            function updateIconPreview() {
                iconPreview.className = 'bi ' + (iconInput.value || 'bi-tag');
            }
            iconInput.addEventListener('input', updateIconPreview);

            // Klik preset → set value + preview.
            document.querySelectorAll('.icon-preset').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    iconInput.value = this.dataset.icon;
                    updateIconPreview();
                });
            });

            // Auto-slug saat label diketik (hanya kalau slug masih kosong / belum disentuh).
            var slugTouched = slugInput.value.length > 0;
            slugInput.addEventListener('input', function () { slugTouched = true; });
            labelInput.addEventListener('input', function () {
                if (!slugTouched) {
                    slugInput.value = labelInput.value
                        .toLowerCase()
                        .replace(/[^a-z0-9]+/g, '_')
                        .replace(/^_+|_+$/g, '')
                        .substring(0, 50);
                }
            });
        });
    </script>
@endpush
