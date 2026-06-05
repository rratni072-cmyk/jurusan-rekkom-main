@extends('layouts.admin')

@section('title', 'Kelola Kegiatan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/vendor/datatables/datatables.min.css') }}">
@endpush

@section('content')
    <div class="container-lg px-0">
        <nav aria-label="breadcrumb" class="mb-4 mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Kegiatan</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Kelola Kegiatan</h1>
            <a href="{{ route('admin.kegiatan.create') }}" class="btn btn-primary">
                <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-plus') }}"></use></svg>
                Tambah Kegiatan
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <table id="kegiatan-table" class="table table-striped table-hover align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="8%">Gambar</th>
                            <th>Judul</th>
                            <th width="12%">Tanggal</th>
                            <th width="10%">Status</th>
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
        $(document).ready(function () {
            var table = $('#kegiatan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.kegiatan.datatable') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'gambar_preview', name: 'gambar_preview', orderable: false, searchable: false },
                    { data: 'judul', name: 'judul' },
                    { data: 'tanggal_formatted', name: 'tanggal' },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
                ],
                language: {
                    url: '{{ asset("admin/vendor/datatables/id.json") }}',
                    emptyTable: '<div class="py-4 text-center text-body-secondary">' +
                        '<svg class="icon icon-3xl mb-2"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-calendar') }}"></use></svg>' +
                        '<div class="fw-semibold mb-1">Belum ada kegiatan</div>' +
                        '<a href="{{ route('admin.kegiatan.create') }}" class="btn btn-sm btn-primary mt-2">Tambah Kegiatan Pertama</a>' +
                        '</div>'
                }
            });

            $(document).on('click', '.btn-delete', function (e) {
                e.preventDefault();
                var url = $(this).data('url');
                var nama = $(this).data('nama');

                Swal.fire({
                    title: 'Hapus Kegiatan?',
                    text: '"' + nama + '" akan dihapus.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e55353',
                    cancelButtonColor: '#636f83',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            success: function (response) {
                                Swal.fire('Berhasil!', response.message, 'success');
                                table.ajax.reload();
                            },
                            error: function () {
                                Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
