@extends('layouts.admin')
@section('title', 'Kelola Program Studi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/vendor/datatables/datatables.min.css') }}">
@endpush

@section('content')
    <div class="container-lg px-0">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-4 mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Program Studi</li>
            </ol>
        </nav>

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Kelola Program Studi</h1>
            <a href="{{ route('admin.program-studi.create') }}" class="btn btn-primary">
                <svg class="icon me-1">
                    <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-plus') }}"></use>
                </svg>
                Tambah Prodi
            </a>
        </div>

        {{-- Tabel --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="prodi-table" class="table table-striped table-hover align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th width="4%">No</th>
                                <th>Nama</th>
                                <th width="7%">Jenjang</th>
                                <th width="11%">Akreditasi</th>
                                <th>No. SK</th>
                                <th width="13%">Kedaluwarsa</th>
                                <th width="12%">Bukti</th>
                                <th width="8%">Status</th>
                                <th width="10%">Aksi</th>
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
        $(document).ready(function () {
            var table = $('#prodi-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.program-studi.datatable') }}',
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama', name: 'nama' },
                    { data: 'jenjang', name: 'jenjang' },
                    { data: 'akreditasi', name: 'akreditasi' },
                    { data: 'no_sk', name: 'no_sk' },
                    { data: 'kedaluwarsa', name: 'tanggal_kedaluwarsa' },
                    { data: 'bukti', orderable: false, searchable: false },
                    { data: 'status', orderable: false, searchable: false },
                    { data: 'aksi', orderable: false, searchable: false },
                ],
                order: [[1, 'asc']],
                autoWidth: false,
                language: {
                    url: '{{ asset("admin/vendor/datatables/id.json") }}',
                    emptyTable: '<div class="py-4 text-center text-body-secondary">' +
                        '<svg class="icon icon-3xl mb-2"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-school') }}"></use></svg>' +
                        '<div class="fw-semibold mb-1">Belum ada program studi</div>' +
                        '<a href="{{ route('admin.program-studi.create') }}" class="btn btn-sm btn-primary mt-2">Tambah Program Studi Pertama</a>' +
                        '</div>'
                }
            });

            $(document).on('click', '.btn-delete', function (e) {
                e.preventDefault();
                var url = $(this).data('url');
                var nama = $(this).data('nama');

                Swal.fire({
                    title: 'Hapus Prodi?',
                    text: 'Data "' + nama + '" akan dihapus.',
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
                            success: function (r) {
                                Swal.fire('Berhasil!', r.message, 'success');
                                table.ajax.reload();
                            },
                            error: function () {
                                Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
