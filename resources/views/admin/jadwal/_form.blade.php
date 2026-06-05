@php
    $thisYear    = (int) date('Y');
    $oldProdiId  = (int) old('program_studi_id', $jadwal->program_studi_id ?? 0);
    $oldProdiStr = old('program_studi', $jadwal->program_studi ?? '');

    // Split tahun_ajaran existing (format YYYY/YYYY) jadi 2 angka untuk edit mode.
    $existingTA = $jadwal->tahun_ajaran ?? '';
    $defaultTM  = $thisYear;
    $defaultTA  = $thisYear + 1;
    if (preg_match('/^(\d{4})\/(\d{4})$/', $existingTA, $m)) {
        $defaultTM = (int) $m[1];
        $defaultTA = (int) $m[2];
    }
    $oldTM = old('tahun_mulai', $defaultTM);
    $oldTA = old('tahun_akhir', $defaultTA);
@endphp

@push('styles')
<style>
    /* Form jadwal: spacing lebih rapat agar form tidak terlihat "berjarak" */
    .jadwal-form .mb-3       { margin-bottom: 0.85rem !important; }
    .jadwal-form .form-label { margin-bottom: 0.3rem; font-weight: 600; font-size: 0.9rem; }
    .jadwal-form .form-text  { margin-top: 0.2rem; font-size: 0.8rem; line-height: 1.35; }
    .jadwal-form .row        { --bs-gutter-y: 0; }
</style>
@endpush

<div class="card mb-4">
    <div class="card-header bg-body-tertiary border-bottom-0 py-2">
        <h6 class="mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-calendar-event text-primary"></i>
            <span>Detail Jadwal Perkuliahan</span>
        </h6>
    </div>
    <div class="card-body jadwal-form">
        {{-- Program Studi: dropdown dari DB --}}
        <div class="mb-3">
            <label for="program_studi_id" class="form-label">
                Program Studi <span class="text-danger">*</span>
            </label>
            <select name="program_studi_id" id="program_studi_id"
                    class="form-select @error('program_studi_id') is-invalid @enderror @error('program_studi') is-invalid @enderror"
                    required>
                <option value="">-- Pilih Program Studi --</option>
                @foreach($listProdi as $prodi)
                    <option value="{{ $prodi->id }}"
                            data-label="{{ $prodi->jenjang }} - {{ $prodi->nama }}"
                            {{ $oldProdiId === (int) $prodi->id ? 'selected' : '' }}>
                        {{ $prodi->jenjang }} - {{ $prodi->nama }}
                    </option>
                @endforeach
            </select>
            {{-- Hidden mirror: agar field `program_studi` (string) tetap terkirim untuk backward compat. --}}
            <input type="hidden" name="program_studi" id="program_studi_hidden" value="{{ $oldProdiStr }}">
            @error('program_studi_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            @error('program_studi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <small class="form-text">Hanya prodi aktif yang muncul.</small>
        </div>

        {{-- Periode: tahun ajaran (split 2 input) + semester --}}
        <div class="row">
            <div class="col-md-5 mb-3">
                <label class="form-label" for="tahun_mulai">
                    Tahun Ajaran <span class="text-danger">*</span>
                </label>
                {{-- 2 number inputs split, terhubung dengan separator "/".
                     Field "tahun_akhir" auto-fill dari "tahun_mulai" + 1 di JS,
                     tapi user bisa override manual. --}}
                <div class="d-flex align-items-center gap-2 jadwal-tahun-split">
                    <input type="number" name="tahun_mulai" id="tahun_mulai"
                           class="form-control text-center @error('tahun_mulai') is-invalid @enderror"
                           value="{{ $oldTM }}"
                           placeholder="{{ $thisYear }}"
                           min="2015" max="2050"
                           style="max-width: 110px;"
                           required>
                    <span class="fs-5 fw-bold text-body-secondary" aria-hidden="true">/</span>
                    <input type="number" name="tahun_akhir" id="tahun_akhir"
                           class="form-control text-center @error('tahun_akhir') is-invalid @enderror"
                           value="{{ $oldTA }}"
                           placeholder="{{ $thisYear + 1 }}"
                           min="2016" max="2051"
                           style="max-width: 110px;"
                           required>
                </div>
                @error('tahun_mulai') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                @error('tahun_akhir') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                @error('tahun_ajaran') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                <small class="form-text">Tahun akhir terisi otomatis, bisa diubah jika perlu.</small>
            </div>

            <div class="col-md-4 mb-3">
                @php
                    $currentSemester = old('semester', $jadwal->semester ?? '');
                @endphp
                <label for="semester" class="form-label">
                    Semester <span class="text-danger">*</span>
                </label>
                <select name="semester" id="semester"
                        class="form-select @error('semester') is-invalid @enderror"
                        required>
                    <option value="">-- Pilih --</option>
                    <option value="Ganjil" {{ $currentSemester === 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                    <option value="Genap"  {{ $currentSemester === 'Genap'  ? 'selected' : '' }}>Genap</option>
                </select>
                @error('semester') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- File jadwal (PDF) — input file native standar (tanpa icon prefix). --}}
        <div class="mb-3">
            <label for="file_path" class="form-label">
                File Jadwal (PDF)
                @if(!isset($jadwal) || !$jadwal->file_path) <span class="text-danger">*</span> @endif
            </label>
            <input type="file" name="file_path" id="file_path"
                   class="form-control @error('file_path') is-invalid @enderror"
                   accept="application/pdf,.pdf">
            @error('file_path') <div class="invalid-feedback">{{ $message }}</div> @enderror

            @if(isset($jadwal) && $jadwal->file_path)
                {{-- Preview file existing dengan border highlight --}}
                <input type="hidden" name="hapus_file" id="hapus_file" value="0">
                <div class="mt-2 p-2 rounded border border-success-subtle bg-success-subtle d-flex align-items-center gap-2 small"
                     id="file-existing-card">
                    <i class="bi bi-check-circle-fill text-success" aria-hidden="true"></i>
                    <span class="text-success-emphasis fw-medium">File saat ini tersimpan:</span>
                    <a href="{{ asset('storage/' . $jadwal->file_path) }}" target="_blank" rel="noopener" class="text-decoration-none">
                        <i class="bi bi-box-arrow-up-right me-1" aria-hidden="true"></i>Lihat
                    </a>
                    <span class="text-body-secondary ms-auto">Upload baru untuk mengganti.</span>
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
            @else
                <small class="form-text">Maksimal 10 MB. Hanya file PDF.</small>
            @endif
        </div>

        {{-- Status aktif --}}
        <div class="form-check form-switch">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" role="switch" value="1"
                   {{ old('is_active', $jadwal->is_active ?? true) ? 'checked' : '' }}>
            <label for="is_active" class="form-check-label">
                Aktif <span class="text-body-secondary small">— hanya yang aktif tampil di halaman publik.</span>
            </label>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Sinkronkan dropdown prodi → hidden input `program_studi` (string).
    // Ini menjaga backward compat dengan data lama yang pakai string bebas.
    (function () {
        const select = document.getElementById('program_studi_id');
        const hidden = document.getElementById('program_studi_hidden');
        if (!select || !hidden) return;

        const sync = () => {
            const opt = select.options[select.selectedIndex];
            hidden.value = (opt && opt.dataset.label) ? opt.dataset.label : '';
        };

        // Init pada load (penting kalau ada `selected` dari server-side)
        if (select.value && !hidden.value) sync();

        select.addEventListener('change', sync);
    })();

    // Auto-link tahun_mulai → tahun_akhir (+1).
    // Logika sticky: kalau user sudah override field 2 (nilai != mulai_lama+1),
    // jangan overwrite saat user ubah field 1 lagi. Fleksibel untuk kasus exception.
    (function () {
        const tm = document.getElementById('tahun_mulai');
        const ta = document.getElementById('tahun_akhir');
        if (!tm || !ta) return;

        // Track nilai tahun_mulai sebelumnya untuk deteksi "auto pattern" vs override
        let prevTM = parseInt(tm.value, 10);
        if (!Number.isFinite(prevTM)) prevTM = null;

        tm.addEventListener('input', function () {
            const newTM = parseInt(this.value, 10);
            if (!Number.isFinite(newTM)) return;

            const currentTA = parseInt(ta.value, 10);
            const expectedTA = (prevTM !== null) ? prevTM + 1 : null;

            // Auto-update field 2 hanya kalau:
            //   (a) field 2 kosong/invalid, ATAU
            //   (b) field 2 masih sesuai pattern auto (= prev_tahun_mulai + 1)
            //       → user belum override, jadi safe overwrite.
            if (!Number.isFinite(currentTA) || currentTA === expectedTA) {
                ta.value = newTM + 1;
            }

            prevTM = newTM;
        });

        // Edge case: kalau user clear field 1 lalu isi lagi
        tm.addEventListener('blur', function () {
            const v = parseInt(this.value, 10);
            if (Number.isFinite(v) && !ta.value) {
                ta.value = v + 1;
            }
        });
    })();

    // Handler tombol X hapus file
    (function () {
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
    })();
</script>
@endpush
