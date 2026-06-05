@php
    $currentKategori = old('kategori', $pedoman->kategori ?? \App\Models\Pedoman::KATEGORI_AKADEMIK);
@endphp

@push('styles')
<style>
    /* Form pedoman: spacing rapat, label bold, konsisten dengan form jadwal */
    .pedoman-form .mb-3       { margin-bottom: 0.85rem !important; }
    .pedoman-form .form-label { margin-bottom: 0.3rem; font-weight: 600; font-size: 0.9rem; }
    .pedoman-form .form-text  { margin-top: 0.2rem; font-size: 0.8rem; line-height: 1.35; }
    .pedoman-form .row        { --bs-gutter-y: 0; }
</style>
@endpush

<div class="card mb-4">
    <div class="card-header bg-body-tertiary border-bottom-0 py-2">
        <h6 class="mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-journal-bookmark text-primary"></i>
            <span>Detail Pedoman</span>
        </h6>
    </div>
    <div class="card-body pedoman-form">
        {{-- Nama File --}}
        <div class="mb-3">
            <label for="nama_file" class="form-label">
                Nama Pedoman <span class="text-danger" aria-label="wajib">*</span>
            </label>
            <input type="text" name="nama_file" id="nama_file"
                   class="form-control @error('nama_file') is-invalid @enderror"
                   value="{{ old('nama_file', $pedoman->nama_file ?? '') }}"
                   placeholder="Contoh: Pedoman Penulisan Tugas Akhir D3"
                   maxlength="255"
                   required>
            @error('nama_file') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <small class="form-text">Judul yang akan tampil di halaman publik.</small>
        </div>

        {{-- Kategori + Urutan (2 kolom di md+) --}}
        <div class="row">
            <div class="col-md-8 mb-3">
                <label for="kategori" class="form-label">
                    Kategori <span class="text-danger" aria-label="wajib">*</span>
                </label>
                <select name="kategori" id="kategori"
                        class="form-select @error('kategori') is-invalid @enderror"
                        required>
                    @foreach($kategoriList as $slug => $label)
                        <option value="{{ $slug }}" {{ $currentKategori === $slug ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <small class="form-text">Menentukan pengelompokan di halaman publik.</small>
            </div>

            <div class="col-md-4 mb-3">
                <label for="urutan" class="form-label">Urutan</label>
                <input type="number" name="urutan" id="urutan"
                       class="form-control @error('urutan') is-invalid @enderror"
                       value="{{ old('urutan', $pedoman->urutan ?? 0) }}"
                       min="0" max="9999"
                       placeholder="0">
                @error('urutan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <small class="form-text">Makin kecil, makin di atas.</small>
            </div>
        </div>

        {{-- Deskripsi (opsional) --}}
        <div class="mb-3">
            <label for="deskripsi" class="form-label">
                Deskripsi Singkat
                <span class="text-body-secondary fw-normal small">(opsional)</span>
            </label>
            <textarea name="deskripsi" id="deskripsi" rows="2"
                      class="form-control @error('deskripsi') is-invalid @enderror"
                      maxlength="500"
                      placeholder="Contoh: Panduan format dan tata cara penulisan laporan tugas akhir untuk mahasiswa D3."
            >{{ old('deskripsi', $pedoman->deskripsi ?? '') }}</textarea>
            @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <small class="form-text">Maksimal 500 karakter. Akan tampil sebagai subtitle di card.</small>
        </div>

        {{-- File Upload (PDF / Excel / Word) --}}
        <div class="mb-3">
            <label for="file_path" class="form-label">
                File Pedoman
                @if(!isset($pedoman) || !$pedoman->file_path)
                    <span class="text-danger" aria-label="wajib">*</span>
                @endif
            </label>
            <input type="file" name="file_path" id="file_path"
                   class="form-control @error('file_path') is-invalid @enderror"
                   accept=".pdf,.xls,.xlsx,.doc,.docx,application/pdf,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
            @error('file_path') <div class="invalid-feedback">{{ $message }}</div> @enderror

            @if(isset($pedoman) && $pedoman->file_path)
                {{-- Preview file existing — border hijau sebagai status 'tersimpan' --}}
                <input type="hidden" name="hapus_file" id="hapus_file" value="0">
                <div class="mt-2 p-2 rounded border border-success-subtle bg-success-subtle d-flex align-items-center gap-2 small flex-wrap"
                     id="file-existing-card">
                    <i class="bi {{ $pedoman->format_icon }} text-{{ $pedoman->format_color }}" aria-hidden="true"></i>
                    <span class="text-success-emphasis fw-medium">File tersimpan:</span>
                    <span class="badge bg-{{ $pedoman->format_color }}-subtle text-{{ $pedoman->format_color }}-emphasis">
                        {{ $pedoman->format_label }}
                    </span>
                    @if($pedoman->file_size_human)
                        <span class="text-body-secondary">{{ $pedoman->file_size_human }}</span>
                    @endif
                    <a href="{{ $pedoman->file_url }}" target="_blank" rel="noopener"
                       class="text-decoration-none ms-auto">
                        <i class="bi bi-box-arrow-up-right me-1" aria-hidden="true"></i>Lihat
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-danger border-0 p-0 ms-1"
                        id="btn-hapus-file" title="Hapus file"
                        style="width:24px;height:24px;line-height:1;font-size:.75rem;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="mt-1 d-none" id="file-hapus-notice">
                    <span class="badge bg-warning text-dark">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>File akan dihapus saat disimpan
                    </span>
                    <button type="button" class="btn btn-sm btn-link text-decoration-underline p-0 ms-2"
                        id="btn-batal-hapus-file">Batalkan</button>
                </div>
                <small class="form-text">Unggah baru untuk mengganti file yang ada.</small>
            @else
                <small class="form-text">Format: PDF, Excel, atau Word. Maksimal 10 MB.</small>
            @endif
        </div>

        {{-- Status aktif toggle --}}
        <div class="form-check form-switch">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" id="is_active"
                   class="form-check-input" role="switch" value="1"
                   {{ old('is_active', $pedoman->is_active ?? true) ? 'checked' : '' }}>
            <label for="is_active" class="form-check-label">
                Aktif
                <span class="text-body-secondary small">— hanya yang aktif tampil di halaman publik.</span>
            </label>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var btnHapus = document.getElementById('btn-hapus-file');
        var btnBatal = document.getElementById('btn-batal-hapus-file');
        var flag = document.getElementById('hapus_file');
        var card = document.getElementById('file-existing-card');
        var notice = document.getElementById('file-hapus-notice');
        if (!btnHapus || !flag) return;

        btnHapus.addEventListener('click', function () {
            flag.value = '1';
            if (card) card.classList.add('d-none');
            if (notice) notice.classList.remove('d-none');
        });

        if (btnBatal) {
            btnBatal.addEventListener('click', function () {
                flag.value = '0';
                if (card) card.classList.remove('d-none');
                if (notice) notice.classList.add('d-none');
            });
        }
    });
</script>
@endpush
