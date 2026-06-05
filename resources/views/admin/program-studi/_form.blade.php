{{-- Form Program Studi --}}
{{-- Form ini fokus pada aspek AKREDITASI prodi.
     Field non-akreditasi (deskripsi/visi/misi/gambar/website) sudah
     dihapus karena halaman frontend hanya butuh data akreditasi. --}}
<div class="row">
    <div class="col-md-8">
        <div class="card mb-3"><div class="card-body">
            {{-- Identitas prodi --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nama" class="form-label">Nama Program Studi <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $prodi->nama ?? '') }}" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="jenjang" class="form-label">Jenjang <span class="text-danger">*</span></label>
                    <select class="form-select @error('jenjang') is-invalid @enderror" id="jenjang" name="jenjang" required>
                        <option value="">Pilih</option>
                        @foreach(['D3','D4','S1'] as $j)
                            <option value="{{ $j }}" {{ old('jenjang', $prodi->jenjang ?? '') == $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                    @error('jenjang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="akreditasi" class="form-label">Peringkat Akreditasi</label>
                    <input type="text" class="form-control @error('akreditasi') is-invalid @enderror" id="akreditasi" name="akreditasi" value="{{ old('akreditasi', $prodi->akreditasi ?? '') }}" placeholder="A/B/C/Unggul">
                    @error('akreditasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Detail SK Akreditasi --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="no_sk" class="form-label">No. SK Akreditasi</label>
                    <input type="text" class="form-control @error('no_sk') is-invalid @enderror" id="no_sk" name="no_sk" value="{{ old('no_sk', $prodi->no_sk ?? '') }}" placeholder="011/SK/LAM-INFOKOM/...">
                    @error('no_sk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="tahun_sk" class="form-label">Tahun SK</label>
                    <input type="number" class="form-control @error('tahun_sk') is-invalid @enderror" id="tahun_sk" name="tahun_sk" value="{{ old('tahun_sk', $prodi->tahun_sk ?? '') }}" min="1900" max="2100" placeholder="2024">
                    @error('tahun_sk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="tanggal_kedaluwarsa" class="form-label">Kedaluwarsa</label>
                    <input type="date" class="form-control @error('tanggal_kedaluwarsa') is-invalid @enderror" id="tanggal_kedaluwarsa" name="tanggal_kedaluwarsa" value="{{ old('tanggal_kedaluwarsa', isset($prodi) && $prodi->tanggal_kedaluwarsa ? $prodi->tanggal_kedaluwarsa->format('Y-m-d') : '') }}">
                    @error('tanggal_kedaluwarsa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Verifikasi Eksternal (PDDikti / BAN-PT / sumber resmi lain) --}}
            <hr class="my-3">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-shield-check text-primary"></i>
                <strong class="small">Verifikasi Eksternal</strong>
                <span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle small">Opsional</span>
            </div>

            {{-- Info box: setara sertifikat fisik (singkat & informatif) --}}
            <div class="alert alert-info py-2 px-3 mb-3 small d-flex align-items-start gap-2">
                <i class="bi bi-info-circle-fill flex-shrink-0 mt-1"></i>
                <div>
                    Tautan dari sumber resmi (<strong>PDDikti</strong>, <strong>BAN-PT</strong>, <strong>LAM-INFOKOM</strong>, dll.)
                    setara dengan sertifikat fisik — <strong>cukup salah satu</strong>.
                </div>
            </div>

            <div class="row">
                <div class="col-md-9 mb-3">
                    <label for="verifikasi_url" class="form-label">URL Verifikasi</label>
                    <input type="url"
                           class="form-control @error('verifikasi_url') is-invalid @enderror"
                           id="verifikasi_url"
                           name="verifikasi_url"
                           value="{{ old('verifikasi_url', $prodi->verifikasi_url ?? '') }}"
                           placeholder="https://pddikti.kemdiktisaintek.go.id/detail-prodi/...">
                    <small class="form-text">Paste URL halaman akreditasi prodi dari salah satu sumber resmi di atas.</small>
                    @error('verifikasi_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label for="verifikasi_label" class="form-label">Label Tombol</label>
                    <input type="text"
                           class="form-control @error('verifikasi_label') is-invalid @enderror"
                           id="verifikasi_label"
                           name="verifikasi_label"
                           value="{{ old('verifikasi_label', $prodi->verifikasi_label ?? '') }}"
                           placeholder="Otomatis dari URL"
                           maxlength="50">
                    <small class="form-text">Biarkan kosong → label diisi otomatis sesuai sumber URL.</small>
                    @error('verifikasi_label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div></div>
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header"><strong>Sertifikat Akreditasi</strong></div>
            <div class="card-body">
                @php
                    $sertifikatPath = $prodi->sertifikat ?? null;
                    $sertifikatExt  = $sertifikatPath ? strtolower(pathinfo($sertifikatPath, PATHINFO_EXTENSION)) : null;
                    $isPdf          = $sertifikatExt === 'pdf';
                @endphp

                {{-- Preview file existing — inline action pattern, no filename overflow --}}
                <div id="sertifikat-existing" @class(['d-none' => ! $sertifikatPath])>
                    @if($sertifikatPath)
                        @php
                            $fileSize  = is_file(public_path('storage/' . $sertifikatPath))
                                ? @filesize(public_path('storage/' . $sertifikatPath))
                                : null;
                            $fileSizeLabel = $fileSize
                                ? ($fileSize < 1024 * 1024
                                    ? round($fileSize / 1024, 1) . ' KB'
                                    : round($fileSize / (1024 * 1024), 2) . ' MB')
                                : null;
                        @endphp
                        <div class="sertifikat-existing-card">
                            {{-- Thumbnail / icon --}}
                            @if($isPdf)
                                <div class="sertifikat-existing-thumb sertifikat-existing-thumb--pdf">
                                    <i class="bi bi-file-earmark-pdf-fill"></i>
                                </div>
                            @else
                                <img src="{{ asset('storage/' . $sertifikatPath) }}" alt="" class="sertifikat-existing-thumb">
                            @endif

                            {{-- Meta (semantic label, bukan hash filename) --}}
                            <div class="sertifikat-existing-meta">
                                <div class="sertifikat-existing-title">Sertifikat tersimpan</div>
                                <div class="sertifikat-existing-subtitle">
                                    <span class="badge sertifikat-existing-badge">{{ strtoupper($sertifikatExt) }}</span>
                                    @if($fileSizeLabel)
                                        <span class="text-separator">·</span>
                                        <span>{{ $fileSizeLabel }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Inline actions --}}
                            <div class="sertifikat-existing-actions">
                                <a href="{{ asset('storage/' . $sertifikatPath) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-ghost-primary"
                                   title="Lihat sertifikat">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-sm btn-ghost-danger"
                                        id="btn-hapus-sertifikat"
                                        title="Hapus sertifikat">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Drop zone (compact saat ada file existing, full saat kosong) --}}
                <label for="sertifikat"
                       class="sertifikat-dropzone @error('sertifikat') is-invalid @enderror {{ $sertifikatPath ? 'sertifikat-dropzone-compact' : '' }}"
                       id="sertifikat-dropzone">
                    <i class="bi bi-cloud-arrow-up-fill dropzone-icon"></i>
                    <div class="dropzone-text">
                        <strong>{{ $sertifikatPath ? 'Ganti dengan file lain' : 'Klik atau tarik file ke sini' }}</strong>
                        <small class="d-block">PDF, JPG, PNG · Maks 5MB</small>
                    </div>
                </label>
                <input type="file" class="d-none @error('sertifikat') is-invalid @enderror" id="sertifikat" name="sertifikat" accept=".pdf,.jpg,.jpeg,.png">

                {{-- Preview file baru yang dipilih (neutral surface + hint hijau di accent-left) --}}
                <div id="sertifikat-new-preview" class="d-none mt-2">
                    <div class="sertifikat-preview-card d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-check-fill sertifikat-preview-icon"></i>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold text-truncate" id="sertifikat-new-name"></div>
                            <small id="sertifikat-new-size"></small>
                        </div>
                        <button type="button" class="btn-close sertifikat-preview-close" id="btn-batal-upload" aria-label="Batal upload"></button>
                    </div>
                </div>

                @error('sertifikat')<div class="invalid-feedback d-block mt-2">{{ $message }}</div>@enderror

                {{-- Hidden flag untuk hapus sertifikat --}}
                <input type="hidden" name="hapus_sertifikat" id="hapus_sertifikat" value="0">

                {{-- Notifikasi hapus --}}
                <div id="sertifikat-akan-dihapus" class="alert alert-warning d-none mt-2 mb-0 py-2 px-3 d-flex justify-content-between align-items-center">
                    <small><i class="bi bi-exclamation-triangle-fill me-1"></i> Sertifikat akan dihapus saat disimpan</small>
                    <button type="button" class="btn btn-sm btn-link p-0 text-decoration-underline" id="btn-batal-hapus-sertifikat">Batal</button>
                </div>
            </div>
        </div>

        <div class="card mb-3"><div class="card-header"><strong>Status</strong></div><div class="card-body">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $prodi->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Aktif</label>
            </div>
            <small class="form-text text-muted">Hanya prodi aktif yang ditampilkan di halaman publik.</small>
        </div></div>
    </div>
</div>

@push('styles')
<style>
    /* Dropzone theme-aware: mengikuti CoreUI token agar readable di light & dark mode */
    .sertifikat-dropzone {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        min-height: 140px;
        padding: 1.25rem 1rem;
        border: 2px dashed var(--cui-border-color, #ced4da);
        border-radius: 0.5rem;
        background: var(--cui-tertiary-bg, #f8f9fa);
        color: var(--cui-body-color, #212529);
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        margin-bottom: 0;
    }
    .sertifikat-dropzone:hover,
    .sertifikat-dropzone:focus-visible {
        border-color: var(--cui-primary, #5856d6);
        background: var(--cui-secondary-bg, #f0f1ff);
    }
    .sertifikat-dropzone.is-dragover {
        border-color: var(--cui-primary, #5856d6);
        background: rgba(88, 86, 214, 0.12);
        transform: scale(1.01);
    }
    .sertifikat-dropzone.is-invalid {
        border-color: var(--cui-danger, #e55353);
        background: rgba(229, 83, 83, 0.08);
    }
    .dropzone-icon {
        font-size: 2rem;
        color: var(--cui-secondary-color, #6c757d);
        transition: color 0.2s ease;
    }
    .sertifikat-dropzone:hover .dropzone-icon,
    .sertifikat-dropzone.is-dragover .dropzone-icon {
        color: var(--cui-primary, #5856d6);
    }
    .dropzone-text strong {
        font-size: 0.9rem;
        color: var(--cui-body-color, #212529);   /* primer, tegas */
    }
    .dropzone-text small {
        font-size: 0.75rem;
        color: var(--cui-secondary-color, #6c757d);  /* pakai token muted resmi CoreUI */
    }

    /* Existing file card — inline action pattern (icon + meta + actions), theme-aware */
    .sertifikat-existing-card {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.625rem 0.75rem;
        border-radius: 0.5rem;
        background: var(--cui-tertiary-bg, #f8f9fa);
        border: 1px solid var(--cui-border-color, #dee2e6);
    }

    /* Thumbnail uniform 40px */
    .sertifikat-existing-thumb {
        width: 40px;
        height: 40px;
        flex-shrink: 0;
        border-radius: 6px;
        object-fit: cover;
        border: 1px solid var(--cui-border-color, #dee2e6);
    }
    .sertifikat-existing-thumb--pdf {
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(229, 83, 83, 0.12);
        border-color: rgba(229, 83, 83, 0.25);
    }
    .sertifikat-existing-thumb--pdf i {
        font-size: 1.4rem;
        color: var(--cui-danger, #e55353);
    }

    /* Meta block — flex grow, no overflow karena tidak menampilkan filename hash */
    .sertifikat-existing-meta {
        flex: 1 1 auto;
        min-width: 0;     /* allow truncate jika kelak ada teks panjang */
    }
    .sertifikat-existing-title {
        font-size: 0.875rem;
        font-weight: 600;
        line-height: 1.3;
        color: var(--cui-body-color, #212529);
    }
    .sertifikat-existing-subtitle {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        font-size: 0.75rem;
        color: var(--cui-secondary-color, #6c757d);
        margin-top: 2px;
    }
    .sertifikat-existing-subtitle .text-separator {
        opacity: 0.6;
    }
    .sertifikat-existing-badge {
        font-size: 0.65rem;
        padding: 2px 6px;
        background: var(--cui-secondary-bg, #e9ecef);
        color: var(--cui-body-color, #212529);
        border: 1px solid var(--cui-border-color, #dee2e6);
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    /* Inline actions — kompak, icon-only, ghost style (tidak menarik perhatian berlebih) */
    .sertifikat-existing-actions {
        display: flex;
        gap: 0.25rem;
        flex-shrink: 0;
    }
    .sertifikat-existing-actions .btn {
        --cui-btn-padding-y: 0.375rem;
        --cui-btn-padding-x: 0.5rem;
        --cui-btn-font-size: 0.875rem;
        line-height: 1;
        min-width: 36px;
        min-height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid transparent;
    }
    .btn-ghost-primary {
        color: var(--cui-primary, #5856d6);
        background: transparent;
    }
    .btn-ghost-primary:hover,
    .btn-ghost-primary:focus-visible {
        background: rgba(88, 86, 214, 0.10);
        border-color: rgba(88, 86, 214, 0.20);
        color: var(--cui-primary, #5856d6);
    }
    .btn-ghost-danger {
        color: var(--cui-danger, #e55353);
        background: transparent;
    }
    .btn-ghost-danger:hover,
    .btn-ghost-danger:focus-visible {
        background: rgba(229, 83, 83, 0.10);
        border-color: rgba(229, 83, 83, 0.20);
        color: var(--cui-danger, #e55353);
    }

    /* Compact variant — saat ada file existing, dropzone jadi baris ringkas
       ("Ganti dengan file lain") agar tidak mendominasi visual. */
    .sertifikat-dropzone-compact {
        min-height: 60px;
        padding: 0.75rem 1rem;
        margin-top: 0.75rem;
        flex-direction: row;
        gap: 0.75rem;
        border-width: 1px;
        border-style: dashed;
    }
    .sertifikat-dropzone-compact .dropzone-icon {
        font-size: 1.25rem;
    }
    .sertifikat-dropzone-compact .dropzone-text {
        text-align: left;
    }
    .sertifikat-dropzone-compact .dropzone-text strong {
        font-size: 0.82rem;
    }
    .sertifikat-dropzone-compact .dropzone-text small {
        font-size: 0.7rem;
    }

    /* Preview card file baru — neutral surface + subtle green accent-left.
       Hindari bg-success-subtle yang terlalu "cerah" di dark mode. */
    .sertifikat-preview-card {
        padding: 0.625rem 0.75rem;
        border-radius: 0.5rem;
        background: var(--cui-tertiary-bg, #f8f9fa);
        border: 1px solid var(--cui-border-color, #dee2e6);
        border-left: 3px solid var(--cui-success, #2eb85c);
    }
    .sertifikat-preview-icon {
        font-size: 1.5rem;
        color: var(--cui-success, #2eb85c);
        opacity: 0.85;
    }
    .sertifikat-preview-card .fw-semibold {
        color: var(--cui-body-color, #212529);
        font-size: 0.875rem;
    }
    .sertifikat-preview-card small {
        color: var(--cui-secondary-color, #6c757d);
        font-size: 0.75rem;
    }
    .sertifikat-preview-close {
        opacity: 0.55;
        transition: opacity 0.15s ease;
    }
    .sertifikat-preview-close:hover {
        opacity: 0.9;
    }
</style>
@endpush

@push('scripts')
<script>
(function() {
    const input       = document.getElementById('sertifikat');
    const dropzone    = document.getElementById('sertifikat-dropzone');
    const existing    = document.getElementById('sertifikat-existing');
    const newPreview  = document.getElementById('sertifikat-new-preview');
    const newName     = document.getElementById('sertifikat-new-name');
    const newSize     = document.getElementById('sertifikat-new-size');
    const btnBatal    = document.getElementById('btn-batal-upload');
    const btnHapus    = document.getElementById('btn-hapus-sertifikat');
    const btnBatalHapus = document.getElementById('btn-batal-hapus-sertifikat');
    const flagHapus   = document.getElementById('hapus_sertifikat');
    const noticeHapus = document.getElementById('sertifikat-akan-dihapus');

    if (! input || ! dropzone) return;

    const MAX_BYTES = 5 * 1024 * 1024;
    const ALLOWED   = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
    const LABEL_STRONG = dropzone.querySelector('.dropzone-text strong');

    // Mode dropzone: 'full' (kosong, hero look) vs 'compact' (ada existing, row kecil)
    function setDropzoneMode(mode) {
        if (mode === 'compact') {
            dropzone.classList.add('sertifikat-dropzone-compact');
            if (LABEL_STRONG) LABEL_STRONG.textContent = 'Ganti dengan file lain';
        } else {
            dropzone.classList.remove('sertifikat-dropzone-compact');
            if (LABEL_STRONG) LABEL_STRONG.textContent = 'Klik atau tarik file ke sini';
        }
    }

    function formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
    }

    function showAlert(title, text) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'error', title, text, confirmButtonColor: '#5856d6' });
        } else {
            alert(title + '\n' + text);
        }
    }

    function handleFile(file) {
        if (! file) return;

        if (! ALLOWED.includes(file.type)) {
            showAlert('Format tidak didukung', 'Hanya PDF, JPG, atau PNG yang diterima.');
            input.value = '';
            return;
        }
        if (file.size > MAX_BYTES) {
            showAlert('Ukuran terlalu besar', 'Maks 5MB. File Anda: ' + formatSize(file.size) + '.');
            input.value = '';
            return;
        }

        newName.textContent = file.name;
        newSize.textContent = formatSize(file.size) + ' · ' + (file.type.split('/')[1] || 'file').toUpperCase();
        newPreview.classList.remove('d-none');
        dropzone.classList.add('d-none');

        // Jika user upload baru, batalkan flag hapus
        if (flagHapus) flagHapus.value = '0';
        if (noticeHapus) noticeHapus.classList.add('d-none');
    }

    input.addEventListener('change', (e) => handleFile(e.target.files[0]));

    // Drag & drop
    ['dragenter', 'dragover'].forEach(ev => {
        dropzone.addEventListener(ev, (e) => {
            e.preventDefault();
            dropzone.classList.add('is-dragover');
        });
    });
    ['dragleave', 'drop'].forEach(ev => {
        dropzone.addEventListener(ev, (e) => {
            e.preventDefault();
            dropzone.classList.remove('is-dragover');
        });
    });
    dropzone.addEventListener('drop', (e) => {
        const file = e.dataTransfer.files[0];
        if (file) {
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            handleFile(file);
        }
    });

    // Batal upload (sebelum submit)
    btnBatal?.addEventListener('click', () => {
        input.value = '';
        newPreview.classList.add('d-none');
        dropzone.classList.remove('d-none');
    });

    // Hapus sertifikat existing (dengan konfirmasi)
    btnHapus?.addEventListener('click', () => {
        const doDelete = () => {
            flagHapus.value = '1';
            existing.classList.add('d-none');
            noticeHapus.classList.remove('d-none');
            // Existing hilang → dropzone kembali ke mode full (hero) supaya user tahu slot kosong
            setDropzoneMode('full');
        };

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus sertifikat?',
                text: 'File akan dihapus saat form disimpan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e55353',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
            }).then(r => { if (r.isConfirmed) doDelete(); });
        } else if (confirm('Hapus sertifikat? File akan dihapus saat form disimpan.')) {
            doDelete();
        }
    });

    // Batalkan niat menghapus → existing muncul lagi, dropzone balik ke compact
    btnBatalHapus?.addEventListener('click', () => {
        flagHapus.value = '0';
        noticeHapus.classList.add('d-none');
        existing.classList.remove('d-none');
        setDropzoneMode('compact');
    });
})();
</script>
@endpush
