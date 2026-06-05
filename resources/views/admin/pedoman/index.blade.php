@extends('layouts.admin')
@section('title', 'Kelola Pedoman')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/vendor/datatables/datatables.min.css') }}">
    <style>
        /* Quick Stats — konsisten dengan jadwal (theme-aware, light+dark mode OK). */
        .pedoman-stat-card {
            border: 0;
            border-left: 4px solid var(--cui-primary, #1b6cb0);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            transition: box-shadow 0.15s ease;
        }
        .pedoman-stat-card:hover { box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); }
        .pedoman-stat-card.is-primary { border-left-color: var(--bs-primary, #0d6efd); }
        .pedoman-stat-card.is-success { border-left-color: var(--bs-success, #198754); }
        .pedoman-stat-card.is-warning { border-left-color: var(--bs-warning, #ffc107); }
        .pedoman-stat-card.is-info    { border-left-color: var(--bs-info, #0dcaf0); }

        .pedoman-stat-label {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--bs-secondary-color, #6c757d);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .pedoman-stat-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--bs-emphasis-color, #1a1d24);
            line-height: 1.2;
        }
        .pedoman-stat-hint {
            font-size: 0.78rem;
            color: var(--bs-secondary-color, #6c757d);
        }

        /* Filter bar */
        .pedoman-filter-bar label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--bs-secondary-color, #6c757d);
            margin-bottom: 4px;
        }
        .pedoman-filter-bar .form-select-sm,
        .pedoman-filter-bar .form-control-sm { font-size: 0.85rem; }

        /* Mobile touch targets — admin action buttons minimal 38px (tablet+ OK) */
        @media (max-width: 767.98px) {
            .pedoman-filter-bar .form-select-sm,
            .pedoman-filter-bar .btn-sm {
                min-height: 38px;
                font-size: 0.88rem;
            }
            /* Action buttons di DataTable — ruang tap cukup */
            #pedoman-table .btn-sm {
                min-width: 38px;
                min-height: 38px;
                padding: 0.3rem 0.5rem;
            }
            /* Quick stats: value sedikit lebih kecil di mobile sempit */
            .pedoman-stat-value { font-size: 1.4rem; }
            .pedoman-stat-card .card-body { padding: 0.85rem !important; }
        }

        /* Search box di datatable — full width di mobile agar tidak overflow */
        @media (max-width: 575.98px) {
            #pedoman-table_filter {
                width: 100%;
                text-align: left !important;
            }
            #pedoman-table_filter label {
                display: block;
                width: 100%;
            }
            #pedoman-table_filter input {
                width: 100% !important;
                margin-left: 0 !important;
                margin-top: 0.35rem;
            }
            #pedoman-table_length { margin-bottom: 0.5rem; }
        }
    </style>
@endpush

@section('content')
    <div class="container-lg px-0">
        <nav aria-label="breadcrumb" class="mb-4 mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Pedoman</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Kelola Pedoman</h1>
            <a href="{{ route('admin.pedoman.create') }}" class="btn btn-primary">
                <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-plus') }}"></use></svg>
                Tambah Pedoman
            </a>
        </div>

        {{-- ====== QUICK STATS (4 cards) ====== --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card pedoman-stat-card is-info h-100">
                    <div class="card-body p-3">
                        <div class="pedoman-stat-label">Total Pedoman</div>
                        <div class="pedoman-stat-value">{{ $stats['total'] }}</div>
                        <small class="pedoman-stat-hint">Semua kategori & status</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card pedoman-stat-card is-primary h-100">
                    <div class="card-body p-3">
                        <div class="pedoman-stat-label">Akademik</div>
                        <div class="pedoman-stat-value">{{ $stats['akademik'] }}</div>
                        <small class="pedoman-stat-hint">Pedoman umum</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card pedoman-stat-card is-success h-100">
                    <div class="card-body p-3">
                        <div class="pedoman-stat-label">Tugas Akhir</div>
                        <div class="pedoman-stat-value">{{ $stats['tugas_akhir'] }}</div>
                        <small class="pedoman-stat-hint">Skripsi, TA, magang</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card pedoman-stat-card is-warning h-100">
                    <div class="card-body p-3">
                        <div class="pedoman-stat-label">Wisuda</div>
                        <div class="pedoman-stat-value">{{ $stats['wisuda'] }}</div>
                        <small class="pedoman-stat-hint">SKPI, ijazah, transkrip</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====== FILTER BAR ====== --}}
        {{-- Layout responsif:
             - mobile (<576): kategori & status 50/50, reset full-width di bawah
             - tablet (≥576): kategori & status 6/6, reset full-width di bawah
             - desktop (≥992): 5/3/4 satu baris --}}
        <div class="card mb-3">
            <div class="card-body p-3 pedoman-filter-bar">
                <div class="row g-2 align-items-end">
                    <div class="col-6 col-lg-5">
                        <label for="filter-kategori" class="form-label">Kategori</label>
                        <select id="filter-kategori" class="form-select form-select-sm">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoriList as $slug => $label)
                                <option value="{{ $slug }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-lg-3">
                        <label for="filter-status" class="form-label">Status</label>
                        <select id="filter-status" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-4 d-grid">
                        <button type="button" id="filter-reset" class="btn btn-sm btn-outline-secondary">
                            <svg class="icon icon-sm me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-reload') }}"></use></svg>
                            Reset Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====== DATATABLE ====== --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="pedoman-table" class="table table-striped table-hover align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th width="4%">No</th>
                                <th>Nama Pedoman</th>
                                <th width="14%">Kategori</th>
                                <th width="10%">Format</th>
                                <th width="9%">Ukuran</th>
                                <th width="7%">File</th>
                                <th width="9%">Status</th>
                                <th width="13%">Aksi</th>
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

            var table = $('#pedoman-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.pedoman.datatable') }}',
                    data: function(d) {
                        d.kategori = $('#filter-kategori').val();
                        d.status   = $('#filter-status').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama_file', name: 'nama_file' },
                    { data: 'kategori_badge', name: 'kategori', orderable: true, searchable: true },
                    { data: 'format_badge', name: 'format_file', orderable: true, searchable: true },
                    { data: 'file_size', orderable: false, searchable: false },
                    { data: 'file_link', orderable: false, searchable: false },
                    { data: 'status', orderable: false, searchable: false },
                    { data: 'aksi', orderable: false, searchable: false },
                ],
                order: [[2, 'asc'], [1, 'asc']],
                autoWidth: false,
                language: {
                    url: '{{ asset("admin/vendor/datatables/id.json") }}',
                    emptyTable: '<div class="py-4 text-center text-body-secondary">' +
                        '<svg class="icon icon-3xl mb-2"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-file') }}"></use></svg>' +
                        '<div class="fw-semibold mb-1">Belum ada pedoman</div>' +
                        '<a href="{{ route('admin.pedoman.create') }}" class="btn btn-sm btn-primary mt-2">Tambah Pedoman Pertama</a>' +
                        '</div>'
                }
            });

            // === Filter bar: reload on change ===
            $('#filter-kategori, #filter-status').on('change', function() {
                table.ajax.reload();
            });

            $('#filter-reset').on('click', function() {
                $('#filter-kategori, #filter-status').val('');
                table.ajax.reload();
            });

            // === Delete ===
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                var url = $(this).data('url'), nama = $(this).data('nama');
                Swal.fire({
                    title: 'Hapus Pedoman?',
                    html: 'Pedoman <strong>"' + nama + '"</strong> akan dihapus beserta file-nya.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e55353',
                    cancelButtonColor: '#636f83',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            success: function(r) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: r.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                table.ajax.reload();
                            },
                            error: function() {
                                Swal.fire('Gagal', 'Terjadi kesalahan.', 'error');
                            }
                        });
                    }
                });
            });

            // === Toggle Aktif/Nonaktif (quick action) ===
            $(document).on('click', '.btn-toggle-active', function(e) {
                e.preventDefault();
                var $btn = $(this);
                var url  = $btn.data('url');
                var nama = $btn.data('nama');
                var isCurrentlyActive = $btn.data('current') === 1 || $btn.data('current') === '1';
                var actionLabel = isCurrentlyActive ? 'nonaktifkan' : 'aktifkan';

                Swal.fire({
                    title: isCurrentlyActive ? 'Nonaktifkan Pedoman?' : 'Aktifkan Pedoman?',
                    html: 'Anda akan ' + actionLabel + ' pedoman:<br><strong>' + nama + '</strong>',
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
                                    title: 'Berhasil',
                                    text: r.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                table.ajax.reload(null, false);
                            },
                            error: function() {
                                Swal.fire('Gagal', 'Terjadi kesalahan saat mengubah status.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
