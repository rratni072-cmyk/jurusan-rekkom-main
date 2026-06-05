@extends('layouts.admin')
@section('title', 'Kelola Jadwal Perkuliahan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/vendor/datatables/datatables.min.css') }}">
    <style>
        /* Quick Stats — kartu statistik di atas tabel.
           Pakai CSS variables Bootstrap 5.3 yang adaptive ke light/dark theme. */
        .jadwal-stat-card {
            border: 0;
            border-left: 4px solid var(--cui-primary, #1b6cb0);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            transition: box-shadow 0.15s ease;
        }
        .jadwal-stat-card.is-active {
            border-left-color: var(--bs-success, #198754);
        }
        .jadwal-stat-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        .jadwal-stat-label {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--bs-secondary-color, #6c757d);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        /* Value — pakai emphasis-color yang otomatis adapt:
           light mode → hampir hitam, dark mode → hampir putih. */
        .jadwal-stat-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--bs-emphasis-color, #1a1d24);
            line-height: 1.2;
        }
        /* Periode Aktif — pakai success-text-emphasis yang adaptive:
           light → hijau gelap, dark → hijau terang. */
        .jadwal-stat-card.is-active .jadwal-stat-value {
            color: var(--bs-success-text-emphasis, #198754);
        }
        .jadwal-stat-hint {
            font-size: 0.78rem;
            color: var(--bs-secondary-color, #6c757d);
        }

        /* Filter bar — toolbar di atas DataTable */
        .jadwal-filter-bar label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--bs-secondary-color, #6c757d);
            margin-bottom: 4px;
        }
        .jadwal-filter-bar .form-select-sm,
        .jadwal-filter-bar .form-control-sm {
            font-size: 0.85rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-lg px-0">
        <nav aria-label="breadcrumb" class="mb-4 mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Jadwal Perkuliahan</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Kelola Jadwal Perkuliahan</h1>
            <a href="{{ route('admin.jadwal.create') }}" class="btn btn-primary">
                <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-plus') }}"></use></svg>
                Tambah Jadwal
            </a>
        </div>

        {{-- ====== QUICK STATS (4 cards) ====== --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card jadwal-stat-card is-active h-100">
                    <div class="card-body p-3">
                        <div class="jadwal-stat-label">Periode Aktif</div>
                        <div class="jadwal-stat-value">{{ $stats['tahun_aktif'] ?? '—' }}</div>
                        <small class="jadwal-stat-hint">Tahun ajaran terbaru</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card jadwal-stat-card h-100">
                    <div class="card-body p-3">
                        <div class="jadwal-stat-label">Total Jadwal</div>
                        <div class="jadwal-stat-value">{{ $stats['total'] }}</div>
                        <small class="jadwal-stat-hint">Semua status</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card jadwal-stat-card h-100">
                    <div class="card-body p-3">
                        <div class="jadwal-stat-label">Aktif</div>
                        <div class="jadwal-stat-value">{{ $stats['aktif'] }}</div>
                        <small class="jadwal-stat-hint">Tampil di frontend</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card jadwal-stat-card h-100">
                    <div class="card-body p-3">
                        <div class="jadwal-stat-label">Tahun Ajaran</div>
                        <div class="jadwal-stat-value">{{ $stats['tahun_unique'] }}</div>
                        <small class="jadwal-stat-hint">Jumlah tab di frontend</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====== FILTER BAR ====== --}}
        <div class="card mb-3">
            <div class="card-body p-3 jadwal-filter-bar">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3 col-sm-6">
                        <label for="filter-tahun" class="form-label">Tahun Ajaran</label>
                        <select id="filter-tahun" class="form-select form-select-sm">
                            <option value="">Semua Tahun</option>
                            @foreach($listTahunAjaran as $ta)
                                <option value="{{ $ta }}">{{ $ta }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <label for="filter-semester" class="form-label">Semester</label>
                        <select id="filter-semester" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <label for="filter-status" class="form-label">Status</label>
                        <select id="filter-status" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label for="filter-prodi" class="form-label">Program Studi</label>
                        <select id="filter-prodi" class="form-select form-select-sm">
                            <option value="">Semua Prodi</option>
                            @foreach($listProdi as $prodi)
                                <option value="{{ $prodi->id }}">{{ $prodi->jenjang }} - {{ $prodi->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-12 d-grid">
                        <button type="button" id="filter-reset" class="btn btn-sm btn-outline-secondary">
                            <svg class="icon icon-sm me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-reload') }}"></use></svg>
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====== DATATABLE ====== --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="jadwal-table" class="table table-striped table-hover align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th width="4%">No</th>
                                <th>Program Studi</th>
                                <th width="11%">Tahun Ajaran</th>
                                <th width="10%">Semester</th>
                                <th width="9%">Status</th>
                                <th width="9%">File</th>
                                <th width="14%">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('admin/vendor/datatables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Setup CSRF default untuk AJAX (toggle + delete)
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

            var table = $('#jadwal-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.jadwal.datatable') }}',
                    data: function(d) {
                        // Kirim filter dari toolbar ke server
                        d.tahun_ajaran      = $('#filter-tahun').val();
                        d.semester          = $('#filter-semester').val();
                        d.status            = $('#filter-status').val();
                        d.program_studi_id  = $('#filter-prodi').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    // `data: prodi_display` (custom column dari controller, sudah escaped),
                    // `name: program_studi` agar sort & search tetap pakai DB column string.
                    { data: 'prodi_display', name: 'program_studi' },
                    { data: 'tahun_ajaran', name: 'tahun_ajaran' },
                    { data: 'semester_badge', name: 'semester', orderable: true, searchable: true },
                    { data: 'status', orderable: false, searchable: false },
                    { data: 'file_link', orderable: false, searchable: false },
                    { data: 'aksi', orderable: false, searchable: false },
                ],
                order: [[2, 'desc'], [3, 'asc']],
                autoWidth: false,
                language: {
                    url: '{{ asset("admin/vendor/datatables/id.json") }}',
                    emptyTable: '<div class="py-4 text-center text-body-secondary">' +
                        '<svg class="icon icon-3xl mb-2"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-calendar-check') }}"></use></svg>' +
                        '<div class="fw-semibold mb-1">Belum ada jadwal perkuliahan</div>' +
                        '<a href="{{ route('admin.jadwal.create') }}" class="btn btn-sm btn-primary mt-2">Tambah Jadwal Pertama</a>' +
                        '</div>'
                }
            });

            // === Filter bar: reload table on change ===
            $('#filter-tahun, #filter-semester, #filter-status, #filter-prodi').on('change', function() {
                table.ajax.reload();
            });

            $('#filter-reset').on('click', function() {
                $('#filter-tahun, #filter-semester, #filter-status, #filter-prodi').val('');
                table.ajax.reload();
            });

            // === Delete (existing) ===
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                var url = $(this).data('url'), nama = $(this).data('nama');
                Swal.fire({
                    title: 'Hapus Jadwal?',
                    text: 'Jadwal "' + nama + '" akan dihapus.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e55353',
                    cancelButtonColor: '#636f83',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({ url: url, type: 'DELETE',
                            success: function(r) { Swal.fire('Berhasil!', r.message, 'success'); table.ajax.reload(); },
                            error: function() { Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error'); }
                        });
                    }
                });
            });

            // === Toggle Aktif/Nonaktif (quick action) ===
            $(document).on('click', '.btn-toggle-active', function(e) {
                e.preventDefault();
                var $btn = $(this);
                var url = $btn.data('url');
                var nama = $btn.data('nama');
                var isCurrentlyActive = $btn.data('current') === 1 || $btn.data('current') === '1';
                var actionLabel = isCurrentlyActive ? 'nonaktifkan' : 'aktifkan';

                Swal.fire({
                    title: isCurrentlyActive ? 'Nonaktifkan Jadwal?' : 'Aktifkan Jadwal?',
                    html: 'Anda akan ' + actionLabel + ' jadwal:<br><strong>' + nama + '</strong>',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: isCurrentlyActive ? '#636f83' : '#198754',
                    cancelButtonColor: '#9da5b1',
                    confirmButtonText: 'Ya, ' + actionLabel,
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'PATCH',
                            success: function(r) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: r.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                table.ajax.reload(null, false); // false = stay on page
                            },
                            error: function() {
                                Swal.fire('Gagal!', 'Terjadi kesalahan saat mengubah status.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
