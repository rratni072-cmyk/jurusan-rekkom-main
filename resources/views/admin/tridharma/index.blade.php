@extends('layouts.admin')

@section('title', 'Kelola '.$label)

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/vendor/datatables/datatables.min.css') }}">
@endpush

@section('content')
    <div class="container-lg px-0">
        <nav aria-label="breadcrumb" class="mb-4 mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Tridharma</li>
                <li class="breadcrumb-item active">{{ $label }}</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">{{ $label }}</h1>
            <a href="{{ route('admin.tridharma.create', $type) }}" class="btn btn-primary">
                <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-plus') }}"></use></svg>
                Tambah {{ $label }}
            </a>
        </div>

        {{-- Banner info konteks Tridharma --}}
        <div class="alert alert-light border d-flex align-items-start gap-2 mb-3" role="note">
            <svg class="icon text-primary flex-shrink-0 mt-1">
                <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-info') }}"></use>
            </svg>
            <div class="small">
                <strong>{{ $label }}</strong> tampil di halaman publik
                <a href="{{ url('/tridharma/'.$type) }}" target="_blank" rel="noopener noreferrer">
                    /tridharma/{{ $type }}
                    <svg class="icon icon-sm" aria-hidden="true">
                        <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-external-link') }}"></use>
                    </svg>
                </a>.
                @if($type === 'pengabdian')
                    Pengabdian punya field tambahan <em>Lokasi</em> dan <em>Dampak Singkat</em> untuk konteks akademik.
                @else
                    Konten Pengajaran adalah laporan kegiatan akademik internal (workshop dosen, kurikulum, akreditasi).
                @endif
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <table id="tridharma-table" class="table table-striped table-hover align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="8%">Gambar</th>
                            <th>Judul</th>
                            <th width="12%">Prodi</th>
                            <th width="10%">Status</th>
                            <th width="12%">Tanggal</th>
                            <th width="12%">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('admin/vendor/datatables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var table = $('#tridharma-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.tridharma.datatable', $type) }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'gambar_preview', name: 'gambar_preview', orderable: false, searchable: false },
                    { data: 'judul', name: 'judul' },
                    { data: 'prodi_label', name: 'prodi_label', orderable: false, searchable: false },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'tanggal', name: 'tanggal' },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
                ],
                order: [[5, 'desc']],
                language: {
                    url: '{{ asset("admin/vendor/datatables/id.json") }}',
                    emptyTable: '<div class="py-4 text-center text-body-secondary">' +
                        '<svg class="icon icon-3xl mb-2"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-book') }}"></use></svg>' +
                        '<div class="fw-semibold mb-1">Belum ada {{ Str::lower($label) }}</div>' +
                        '<a href="{{ route('admin.tridharma.create', $type) }}" class="btn btn-sm btn-primary mt-2">Tambah {{ $label }} Pertama</a>' +
                        '</div>'
                }
            });

            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                var url = $(this).data('url');
                var nama = $(this).data('nama');

                Swal.fire({
                    title: 'Hapus {{ $label }}?',
                    html: '"' + nama + '" akan dihapus.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e55353',
                    cancelButtonColor: '#636f83',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            success: function(response) {
                                Swal.fire('Berhasil!', response.message, 'success');
                                table.ajax.reload();
                            },
                            error: function() {
                                Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
