<div class="card mb-4"><div class="card-body">
    <div class="mb-3">
        <label for="nama" class="form-label">Nama Beasiswa <span class="text-danger">*</span></label>
        <input type="text" name="nama" id="nama"
               class="form-control @error('nama') is-invalid @enderror"
               value="{{ old('nama', $beasiswa->nama ?? '') }}"
               maxlength="255" required autocomplete="off">
        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="penyelenggara" class="form-label">Penyelenggara</label>
        <input type="text" name="penyelenggara" id="penyelenggara"
               class="form-control @error('penyelenggara') is-invalid @enderror"
               value="{{ old('penyelenggara', $beasiswa->penyelenggara ?? '') }}"
               placeholder="Contoh: Bank Indonesia"
               maxlength="150" autocomplete="off">
        <div class="form-text">Nama lembaga/instansi pemberi beasiswa. Akan tampil sebagai subtitle di card frontend.</div>
        @error('penyelenggara') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
        <textarea name="deskripsi" id="deskripsi" class="form-control tinymce-editor @error('deskripsi') is-invalid @enderror" rows="5" required>{{ old('deskripsi', $beasiswa->deskripsi ?? '') }}</textarea>
        @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="url_info" class="form-label">Tautan Info (Website / Sosmed)</label>
        <input type="url" name="url_info" id="url_info"
               class="form-control @error('url_info') is-invalid @enderror"
               value="{{ old('url_info', $beasiswa->url_info ?? '') }}"
               placeholder="https://kip-kuliah.kemdikbud.go.id"
               maxlength="500" inputmode="url" autocomplete="off">
        <div class="form-text">
            Opsional. Jika diisi, card frontend akan menampilkan tombol
            <strong>Kunjungi Website Resmi</strong> yang membuka tautan di tab baru.
            Bisa berupa website resmi atau tautan sosial media beasiswa.
        </div>
        @error('url_info') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-check mb-3">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $beasiswa->is_active ?? true) ? 'checked' : '' }}>
        <label for="is_active" class="form-check-label">Aktif</label>
    </div>
</div></div>
