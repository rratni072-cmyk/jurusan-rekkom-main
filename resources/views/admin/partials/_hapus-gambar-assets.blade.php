{{-- Shared CSS + JS untuk tombol X hapus gambar di pojok preview.
     Include sekali di form yang punya upload gambar + tombol #btn-hapus-gambar.
     Memakai @once agar kalau di-include >1x tidak duplikat. --}}
@once
    @push('styles')
        <style>
            /* Tombol X bulat di pojok kanan atas gambar preview */
            .btn-hapus-gambar-x {
                position: absolute;
                top: 6px;
                right: 6px;
                width: 26px;
                height: 26px;
                border-radius: 50%;
                border: 2px solid #fff;
                background-color: #dc3545;
                color: #fff;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0;
                font-size: 0.7rem;
                cursor: pointer;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
                transition: transform 0.15s ease, background-color 0.15s ease;
                z-index: 2;
                line-height: 1;
            }
            .btn-hapus-gambar-x:hover,
            .btn-hapus-gambar-x:focus-visible {
                background-color: #bb2d3b;
                transform: scale(1.15);
                outline: none;
            }
            /* Visual saat gambar ditandai untuk dihapus */
            .gambar-marked-delete img {
                opacity: 0.3;
                filter: grayscale(80%);
                outline: 2px dashed var(--bs-danger, #dc3545);
                outline-offset: 2px;
                transition: opacity 0.2s, filter 0.2s;
            }
            .gambar-marked-delete .btn-hapus-gambar-x {
                display: none;
            }
            .btn-undo-hapus {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: 3;
                font-size: 0.75rem;
                white-space: nowrap;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            /**
             * Handler generik untuk tombol X hapus gambar.
             * Memerlukan elemen: #btn-hapus-gambar, #hapus_gambar (hidden input),
             * #gambar-preview-wrapper (container position-relative).
             */
            document.addEventListener('DOMContentLoaded', function () {
                var btn = document.getElementById('btn-hapus-gambar');
                var flag = document.getElementById('hapus_gambar');
                var wrapper = document.getElementById('gambar-preview-wrapper');
                var preview = document.getElementById('gambar-preview');
                if (!btn || !flag || !wrapper) return;

                // Buat tombol undo
                var undoBtn = document.createElement('button');
                undoBtn.type = 'button';
                undoBtn.className = 'btn btn-sm btn-warning btn-undo-hapus d-none';
                undoBtn.setAttribute('aria-label', 'Batalkan hapus');
                undoBtn.innerHTML = '<i class="bi bi-arrow-counterclockwise me-1"></i>Batal';
                wrapper.appendChild(undoBtn);

                btn.addEventListener('click', function () {
                    flag.value = '1';
                    wrapper.classList.add('gambar-marked-delete');
                    undoBtn.classList.remove('d-none');
                });

                undoBtn.addEventListener('click', function () {
                    flag.value = '0';
                    wrapper.classList.remove('gambar-marked-delete');
                    undoBtn.classList.add('d-none');
                });

                // Kalau user upload file baru, reset state hapus
                var fileInput = document.getElementById('gambar');
                if (fileInput) {
                    fileInput.addEventListener('change', function () {
                        if (this.files && this.files[0]) {
                            flag.value = '0';
                            wrapper.classList.remove('gambar-marked-delete');
                            undoBtn.classList.add('d-none');
                        }
                    });
                }
            });
        </script>
    @endpush
@endonce
