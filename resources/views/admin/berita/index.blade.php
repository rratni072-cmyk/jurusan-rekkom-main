@extends('layouts.admin')

@section('title', 'Kelola Berita')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/vendor/datatables/datatables.min.css') }}">
@endpush

@section('content')
    <div class="container-lg px-0">
        <nav aria-label="breadcrumb" class="mb-4 mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Berita</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Kelola Berita</h1>
            <div>
                <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline-secondary me-2">
                    <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-tags') }}"></use></svg>
                    Kategori
                </a>
                <a href="{{ route('admin.berita.create') }}" class="btn btn-primary">
                    <svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-plus') }}"></use></svg>
                    Tambah Berita
                </a>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <table id="berita-table" class="table table-striped table-hover align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="8%">Gambar</th>
                            <th>Judul</th>
                            <th width="15%">Kategori</th>
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
            var table = $('#berita-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.berita.datatable') }}',
                    // Pass query param ?kategori= dari URL ke server (untuk shortcut Tridharma)
                    data: function(d) {
                        var urlParams = new URLSearchParams(window.location.search);
                        var kategori = urlParams.get('kategori');
                        if (kategori) {
                            d.kategori = kategori;
                        }
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'gambar_preview', name: 'gambar_preview', orderable: false, searchable: false },
                    { data: 'judul', name: 'judul' },
                    { data: 'kategori_list', name: 'kategori_list', orderable: false, searchable: false },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'tanggal', name: 'tanggal' },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
                ],
                language: {
                    url: '{{ asset("admin/vendor/datatables/id.json") }}',
                    emptyTable: '<div class="py-4 text-center text-body-secondary">' +
                        '<svg class="icon icon-3xl mb-2"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-newspaper') }}"></use></svg>' +
                        '<div class="fw-semibold mb-1">Belum ada berita</div>' +
                        '<a href="{{ route('admin.berita.create') }}" class="btn btn-sm btn-primary mt-2">Tulis Berita Pertama</a>' +
                        '</div>'
                }
            });

            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                var url = $(this).data('url');
                var nama = $(this).data('nama');

                Swal.fire({
                    title: 'Hapus Berita?',
                    text: 'Berita "' + nama + '" akan dihapus.',
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
