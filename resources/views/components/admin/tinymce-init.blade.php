{{-- Shared TinyMCE 7 initialization component --}}
{{-- Usage:
     @include('components.admin.tinymce-init', ['selector' => '#konten'])
     @include('components.admin.tinymce-init', [
         'selector'  => '#konten',
         'uploadUrl' => route('admin.berita.upload-image'),
     ])
--}}

@push('scripts')
<script src="{{ asset('admin/vendor/tinymce/tinymce.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const uploadUrl = @json($uploadUrl ?? null);
    const enableStrukturTemplate = @json($enableStrukturTemplate ?? false);

    /**
     * Custom upload handler — TinyMCE 7 expects a Promise that resolves
     * with the public URL. Kita pakai fetch() agar bisa kirim CSRF header
     * dan parse error message dari server (Laravel ValidationException).
     */
    const imagesUploadHandler = (blobInfo, progress) => new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());

        const xhr = new XMLHttpRequest();
        xhr.open('POST', uploadUrl);
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.upload.onprogress = (e) => {
            if (e.lengthComputable) progress((e.loaded / e.total) * 100);
        };

        xhr.onload = () => {
            if (xhr.status === 413) {
                reject({ message: 'File terlalu besar untuk diupload.', remove: true });
                return;
            }
            if (xhr.status === 422) {
                // Validation error — ambil pesan pertama.
                try {
                    const errors = JSON.parse(xhr.responseText).errors || {};
                    const firstError = Object.values(errors).flat()[0] || 'Validasi gagal.';
                    reject({ message: firstError, remove: true });
                } catch (_) {
                    reject({ message: 'Validasi gagal.', remove: true });
                }
                return;
            }
            if (xhr.status < 200 || xhr.status >= 300) {
                reject({ message: 'Upload gagal: HTTP ' + xhr.status, remove: true });
                return;
            }
            try {
                const json = JSON.parse(xhr.responseText);
                if (!json || typeof json.location !== 'string') {
                    reject({ message: 'Response server tidak valid.', remove: true });
                    return;
                }
                resolve(json.location);
            } catch (_) {
                reject({ message: 'Response server tidak valid.', remove: true });
            }
        };

        xhr.onerror = () => reject({ message: 'Network error saat upload.', remove: true });
        xhr.send(formData);
    });

    /**
     * CSS yang di-apply di dalam editor (iframe TinyMCE).
     * Mirror dengan style frontend agar admin lihat preview yang akurat
     * sebelum simpan — "WYSIWYG" beneran.
     */
    const editorContentStyle = `
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; font-size: 14px; line-height: 1.6; }
        img { max-width: 100%; height: auto; }
        table.tabel-struktur-organisasi { width: 100%; border-collapse: collapse; margin: 1rem 0; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
        table.tabel-struktur-organisasi thead th { background: #002147; color: #fff; padding: 0.6rem; text-align: center; font-weight: 600; border: 1px solid #001a38; }
        table.tabel-struktur-organisasi td { padding: 0.75rem; border: 1px solid #dee2e6; vertical-align: middle; }
        table.tabel-struktur-organisasi tbody tr:nth-child(even) { background: #f8f9fa; }
        table.tabel-struktur-organisasi td:first-child { text-align: center; font-weight: 600; }
        table.tabel-struktur-organisasi td:nth-child(2) { text-align: center; }
        table.tabel-struktur-organisasi td img { max-width: 100px; border-radius: 4px; }
    `;

    const config = {
        selector: '{{ $selector ?? ".tinymce-editor" }}',
        height: {{ $height ?? 400 }},
        menubar: {{ isset($menubar) && $menubar ? 'true' : 'false' }},
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
            'preview', 'anchor', 'searchreplace', 'visualblocks', 'code',
            'fullscreen', 'insertdatetime', 'media', 'table', 'wordcount'
        ],
        // Toolbar: image & table dipindah ke awal supaya tidak masuk overflow ('...').
        toolbar: 'undo redo | blocks | bold italic underline | ' +
            'image table' + (enableStrukturTemplate ? ' strukturTemplate' : '') + ' | ' +
            'alignleft aligncenter alignright | bullist numlist outdent indent | ' +
            'link media | removeformat | code fullscreen',
        content_style: editorContentStyle,
        branding: false,
        promotion: false,
        license_key: 'gpl',
        // Image options — agar dialog Insert/Edit Image tampak proper.
        image_advtab: true,
        image_caption: true,
        image_dimensions: true,
        image_class_list: [
            { title: 'Responsive (default)', value: 'img-fluid rounded' },
            { title: 'Bordered', value: 'img-fluid rounded border' },
        ],
    };

    // Hanya aktifkan upload feature kalau uploadUrl di-pass dari Blade.
    if (uploadUrl) {
        config.images_upload_handler = imagesUploadHandler;
        config.automatic_uploads = true;
        config.paste_data_images = true;            // paste screenshot dari clipboard
        config.images_reuse_filename = false;
        config.file_picker_types = 'image';         // upload file hanya untuk image (video pakai embed YouTube/Vimeo URL)
        config.images_file_types = 'jpg,jpeg,png,webp,gif';
        config.image_uploadtab = false;             // hilangkan tab "Upload" (redundan dengan file picker di tab General)

        /**
         * File picker callback — WAJIB untuk menampilkan tombol "Browse/Upload"
         * di dialog Insert/Edit Image (di sebelah kanan input "Source").
         *
         * Tanpa ini, user hanya bisa mengetik URL manual. Dengan ini, user
         * bisa klik icon upload → pilih file dari laptop → otomatis ter-cache
         * sebagai blob. TinyMCE kemudian meng-upload lewat images_upload_handler
         * (karena automatic_uploads: true) saat form disimpan.
         */
        config.file_picker_callback = (callback, value, meta) => {
            if (meta.filetype !== 'image') return;

            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/jpeg,image/png,image/webp,image/gif');

            input.addEventListener('change', (event) => {
                const file = event.target.files && event.target.files[0];
                if (!file) return;

                // Guard client-side: max 2MB (sinkron dengan backend validation).
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB.');
                    return;
                }

                const reader = new FileReader();
                reader.addEventListener('load', () => {
                    // Daftarkan file sebagai blob di cache TinyMCE.
                    // id unik supaya tidak collide kalau user insert beberapa gambar.
                    const id = 'blobid-' + Date.now();
                    const blobCache = tinymce.activeEditor.editorUpload.blobCache;
                    const base64 = reader.result.split(',')[1];
                    const blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);

                    // Callback: isi Source dengan blob URI sementara + judul gambar.
                    // Saat form di-save, TinyMCE otomatis upload & ganti URI ke URL asli.
                    callback(blobInfo.blobUri(), { title: file.name });
                });
                reader.readAsDataURL(file);
            });

            input.click();
        };
    }

    /**
     * Custom toolbar button: "Sisipkan Template Struktur Organisasi".
     * Hanya aktif kalau `enableStrukturTemplate` di-pass dari Blade (true).
     * Klik tombol → insert tabel HTML siap-isi (4 kolom: No, Foto, Jabatan, Nama & NIP).
     * Admin tinggal ganti placeholder text & upload foto via toolbar Image.
     */
    if (enableStrukturTemplate) {
        config.setup = (editor) => {
            editor.ui.registry.addButton('strukturTemplate', {
                text: 'Template Struktur',
                tooltip: 'Sisipkan tabel struktur organisasi',
                onAction: () => {
                    const blankRow = (no) => `
                        <tr>
                            <td>${no}</td>
                            <td><em style="color:#999;">[Upload foto via toolbar Image]</em></td>
                            <td>[Jabatan]</td>
                            <td><strong>[Nama Lengkap, Gelar]</strong><br><span style="color:#6c757d;font-size:0.9em;">NIP. xxxxxxxxxxxxxxxxxx</span></td>
                        </tr>`;

                    const template = `
                        <table class="tabel-struktur-organisasi">
                            <thead>
                                <tr>
                                    <th style="width:60px;">No</th>
                                    <th style="width:140px;">Foto</th>
                                    <th>Jabatan</th>
                                    <th>Nama &amp; NIP</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${blankRow(1)}
                                ${blankRow(2)}
                                ${blankRow(3)}
                                ${blankRow(4)}
                            </tbody>
                        </table>
                        <p>&nbsp;</p>`;

                    editor.insertContent(template);
                },
            });
        };
    }

    tinymce.init(config);
});
</script>
@endpush
