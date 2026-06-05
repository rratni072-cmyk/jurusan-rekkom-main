@extends('layouts.admin')

@section('title', 'Kelola Tipe Kegiatan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/vendor/datatables/datatables.min.css') }}">
@endpush

@section('content')
    <div class="container-lg px-0">
        <nav aria-label="breadcrumb" class="mb-4 mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Tipe Kegiatan</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Kelola Tipe Kegiatan</h1>
                <p class="text-body-secondary mb-0 small">Master kategori untuk filter di halaman publik <code>/kemahasiswaan/kegiatan</code>.</p>
            </div>
            <a href="{{ route('admin.tipe-kegiatan.create') }}" class="btn btn-primary">
                <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-plus') }}"></use></svg>
                Tambah Tipe
            </a>
        </div>

        <div class="alert alert-info d-flex align-items-start gap-2 mb-4" role="alert">
            <i class="bi bi-info-circle-fill fs-5 mt-1" aria-hidden="true"></i>
            <div class="small">
                <strong>Catatan:</strong> Tipe yang masih dipakai oleh kegiatan tidak dapat dihapus. Untuk menyembunyikan tipe dari publik tanpa menghapus, set status menjadi <em>Non-aktif</em>.
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <table id="tipe-kegiatan-table" class="table table-striped table-hover align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="8%">Ikon</th>
                            <th>Label</th>
                            <th width="15%">Slug</th>
                            <th width="10%">Urutan</th>
                            <th width="13%">Dipakai</th>
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
            var table = $('#tipe-kegiatan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.tipe-kegiatan.datatable') }}',
                order: [[4, 'asc']],
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'icon_preview', name: 'icon', orderable: false, searchable: false },
                    { data: 'label', name: 'label' },
                    { data: 'slug', name: 'slug' },
                    { data: 'urutan', name: 'urutan' },
                    { data: 'kegiatan_count', name: 'kegiatans_count', orderable: false, searchable: false },
                    { data: 'status', name: 'is_active', orderable: false, searchable: false },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
                ],
                language: {
                    url: '{{ asset("admin/vendor/datatables/id.json") }}',
                    emptyTable: '<div class="py-4 text-center text-body-secondary">' +
                        '<svg class="icon icon-3xl mb-2"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-tags') }}"></use></svg>' +
                        '<div class="fw-semibold mb-1">Belum ada tipe kegiatan</div>' +
                        '<a href="{{ route('admin.tipe-kegiatan.create') }}" class="btn btn-sm btn-primary mt-2">Tambah Tipe Pertama</a>' +
                        '</div>'
                }
            });

            $(document).on('click', '.btn-delete', function (e) {
                e.preventDefault();
                var url = $(this).data('url');
                var nama = $(this).data('nama');

                Swal.fire({
                    title: 'Hapus Tipe Kegiatan?',
                    text: '"' + nama + '" akan dihapus permanen.',
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
                            error: function (xhr) {
                                var msg = xhr.responseJSON && xhr.responseJSON.message
                                    ? xhr.responseJSON.message
                                    : 'Terjadi kesalahan saat menghapus.';
                                Swal.fire('Tidak Bisa Dihapus', msg, 'warning');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
