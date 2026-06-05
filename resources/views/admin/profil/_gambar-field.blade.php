{{-- Reusable gambar upload field for Profil Jurusan.
     Fitur:
     - Upload gambar baru (replace kalau sudah ada)
     - Preview gambar saat ini
     - Tombol X bulat di pojok kanan atas gambar → konfirmasi SweetAlert2 →
       tandai untuk dihapus (belum commit; klik Simpan untuk apply, bisa undo) --}}
<div class="mb-3" data-gambar-field="{{ $kunci }}">
    <label for="profil_{{ $kunci }}_gambar" class="form-label">Gambar Pendukung</label>
    <input type="file"
           class="form-control @error("profil.{$kunci}.gambar") is-invalid @enderror"
           id="profil_{{ $kunci }}_gambar"
           name="profil[{{ $kunci }}][gambar]"
           accept="image/*">
    @error("profil.{$kunci}.gambar")
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <div class="form-text">JPG, PNG, WebP. Maks: 5 MB. Untuk PNG besar, disarankan kompres dulu (mis. TinyPNG).</div>

    {{-- Hidden flag: di-set ke "1" oleh tombol X, dikirim saat submit form. --}}
    <input type="hidden"
           name="profil[{{ $kunci }}][hapus_gambar]"
           value="0"
           data-hapus-gambar-flag>

    @if($item->gambar)
        <div class="mt-2 gambar-preview-wrapper position-relative d-inline-block"
             data-gambar-preview>
            <img src="{{ asset('storage/' . $item->gambar) }}"
                 alt="Gambar pendukung profil ({{ str_replace('_', ' ', $kunci) }})"
                 loading="lazy"
                 decoding="async"
                 class="img-fluid rounded gambar-preview-img"
                 style="max-height:150px;">

            {{-- Tombol X bulat di pojok kanan atas gambar --}}
            <button type="button"
                    class="btn-hapus-gambar-x"
                    data-hapus-gambar-btn
                    aria-label="Hapus gambar"
                    title="Hapus gambar">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </button>

            {{-- Overlay badge "Akan dihapus" — hanya tampil saat state hapus aktif --}}
            <span class="badge bg-danger gambar-preview-overlay d-none position-absolute top-50 start-50 translate-middle"
                  data-hapus-overlay>
                Akan dihapus saat disimpan
            </span>

            {{-- Tombol undo — muncul saat state hapus aktif, menggantikan X --}}
            <button type="button"
                    class="btn-undo-hapus-gambar d-none"
                    data-batal-hapus-btn
                    aria-label="Batalkan penghapusan"
                    title="Batalkan">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                    <path d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                    <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                </svg>
            </button>
        </div>

        <div class="mt-2">
            <span class="badge bg-success" data-gambar-status-ada>Gambar tersedia</span>
            <span class="badge bg-warning text-dark d-none" data-gambar-status-dihapus>Ditandai untuk dihapus</span>
        </div>
    @endif
</div>

@once
    @push('styles')
        <style>
            /* Tombol X bulat di pojok kanan atas gambar preview. */
            .btn-hapus-gambar-x,
            .btn-undo-hapus-gambar {
                position: absolute;
                top: 6px;
                right: 6px;
                width: 28px;
                height: 28px;
                border-radius: 50%;
                border: 2px solid #fff;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0;
                cursor: pointer;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
                transition: transform .15s ease, background-color .15s ease;
                z-index: 2;
            }
            .btn-hapus-gambar-x {
                background-color: #dc3545;
                color: #fff;
            }
            .btn-hapus-gambar-x:hover,
            .btn-hapus-gambar-x:focus-visible {
                background-color: #bb2d3b;
                transform: scale(1.1);
                outline: none;
            }
            .btn-undo-hapus-gambar {
                background-color: #6c757d;
                color: #fff;
            }
            .btn-undo-hapus-gambar:hover,
            .btn-undo-hapus-gambar:focus-visible {
                background-color: #565e64;
                transform: scale(1.1);
                outline: none;
            }

            /* Visual feedback saat gambar ditandai untuk dihapus */
            .gambar-preview-dihapus .gambar-preview-img {
                opacity: 0.35;
                filter: grayscale(80%);
                outline: 2px dashed var(--bs-danger, #dc3545);
                outline-offset: 2px;
                transition: opacity .2s, filter .2s;
            }
            .gambar-preview-overlay {
                font-size: 0.75rem;
                white-space: nowrap;
                pointer-events: none;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            /**
             * Handler untuk tombol hapus gambar (X bulat) di admin profil.
             * Memakai SweetAlert2 untuk konfirmasi & toast.
             * Skrip ini generik — menangani SEMUA instance gambar-field di halaman.
             */
            document.addEventListener('DOMContentLoaded', function () {
                const fields = document.querySelectorAll('[data-gambar-field]');
                if (!fields.length) return;

                // Konstanta validasi — harus SAMA dengan server-side (ProfilJurusanRequest).
                const MAX_SIZE_MB = 5;
                const MAX_SIZE_BYTES = MAX_SIZE_MB * 1024 * 1024;
                const ALLOWED_MIME = ['image/jpeg', 'image/png', 'image/webp'];
                const ALLOWED_EXT  = ['jpg', 'jpeg', 'png', 'webp'];

                const showError = function (title, text) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: title,
                            html: text,
                            confirmButtonColor: '#321fdb',
                        });
                    } else {
                        alert(title + '\n\n' + text.replace(/<[^>]+>/g, ''));
                    }
                };

                // Format bytes -> human readable
                const formatSize = function (bytes) {
                    if (bytes < 1024) return bytes + ' B';
                    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
                    return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
                };

                fields.forEach(function (wrapper) {
                    const fileInput    = wrapper.querySelector('input[type="file"]');
                    const flag         = wrapper.querySelector('[data-hapus-gambar-flag]');
                    const preview      = wrapper.querySelector('[data-gambar-preview]');
                    const overlay      = wrapper.querySelector('[data-hapus-overlay]');
                    const statusAda    = wrapper.querySelector('[data-gambar-status-ada]');
                    const statusHapus  = wrapper.querySelector('[data-gambar-status-dihapus]');
                    const btnHapus     = wrapper.querySelector('[data-hapus-gambar-btn]');
                    const btnBatal     = wrapper.querySelector('[data-batal-hapus-btn]');

                    // ============ Client-side validation saat pilih file ============
                    if (fileInput) {
                        fileInput.addEventListener('change', function () {
                            const file = fileInput.files && fileInput.files[0];
                            if (!file) return;

                            // Cek ekstensi (case-insensitive)
                            const ext = (file.name.split('.').pop() || '').toLowerCase();
                            const mimeOk = ALLOWED_MIME.includes(file.type);
                            const extOk = ALLOWED_EXT.includes(ext);

                            if (!mimeOk && !extOk) {
                                showError(
                                    'Format file tidak didukung',
                                    'Silakan pilih file <strong>JPG, PNG, atau WebP</strong>.<br>' +
                                    'File Anda: <code>' + file.name + '</code>'
                                );
                                fileInput.value = '';
                                return;
                            }

                            // Cek ukuran
                            if (file.size > MAX_SIZE_BYTES) {
                                showError(
                                    'File terlalu besar',
                                    'Ukuran maksimal <strong>' + MAX_SIZE_MB + ' MB</strong>.<br>' +
                                    'File Anda: <strong>' + formatSize(file.size) + '</strong><br><br>' +
                                    '<small class="text-body-secondary">Tips: kompres dulu di ' +
                                    '<a href="https://tinypng.com" target="_blank" rel="noopener">TinyPNG</a> atau ' +
                                    '<a href="https://squoosh.app" target="_blank" rel="noopener">Squoosh</a>, ' +
                                    'atau simpan sebagai JPG kualitas 80-90%.</small>'
                                );
                                fileInput.value = '';
                                return;
                            }

                            // Sukses — toast info singkat
                            if (window.AdminToast) {
                                window.AdminToast.fire({
                                    icon: 'success',
                                    title: 'File siap: ' + file.name + ' (' + formatSize(file.size) + ')'
                                });
                            }
                        });
                    }

                    // Kalau tidak ada gambar eksisting, btn akan null — skip bagian hapus.
                    if (!btnHapus || !btnBatal) return;

                    const setStateHapus = function () {
                        flag.value = '1';
                        preview.classList.add('gambar-preview-dihapus');
                        overlay.classList.remove('d-none');
                        statusAda.classList.add('d-none');
                        statusHapus.classList.remove('d-none');
                        btnHapus.classList.add('d-none');
                        btnBatal.classList.remove('d-none');
                    };

                    const setStateNormal = function () {
                        flag.value = '0';
                        preview.classList.remove('gambar-preview-dihapus');
                        overlay.classList.add('d-none');
                        statusAda.classList.remove('d-none');
                        statusHapus.classList.add('d-none');
                        btnHapus.classList.remove('d-none');
                        btnBatal.classList.add('d-none');
                    };

                    btnHapus.addEventListener('click', function () {
                        // Fallback ke confirm native kalau SweetAlert2 gagal load.
                        if (typeof Swal === 'undefined') {
                            if (confirm('Yakin ingin menghapus gambar ini? Perubahan akan disimpan saat Anda klik tombol Simpan.')) {
                                setStateHapus();
                            }
                            return;
                        }

                        Swal.fire({
                            title: 'Hapus gambar ini?',
                            text: 'Gambar akan ditandai untuk dihapus. Perubahan baru disimpan saat Anda klik "Simpan Perubahan".',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc3545',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Ya, hapus',
                            cancelButtonText: 'Batal',
                            reverseButtons: true,
                            focusCancel: true,
                        }).then(function (result) {
                            if (!result.isConfirmed) return;
                            setStateHapus();

                            if (window.AdminToast) {
                                window.AdminToast.fire({
                                    icon: 'info',
                                    title: 'Gambar ditandai untuk dihapus. Klik Simpan untuk menerapkan.'
                                });
                            }
                        });
                    });

                    btnBatal.addEventListener('click', function () {
                        setStateNormal();
                        if (window.AdminToast) {
                            window.AdminToast.fire({
                                icon: 'success',
                                title: 'Penghapusan dibatalkan.'
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
@endonce
