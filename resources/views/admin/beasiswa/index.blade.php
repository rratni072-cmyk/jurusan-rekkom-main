@extends('layouts.admin')
@section('title', 'Kelola Beasiswa')
@push('styles')<link rel="stylesheet" href="{{ asset('admin/vendor/datatables/datatables.min.css') }}">@endpush
@section('content')
    <div class="container-lg px-0">
        <nav aria-label="breadcrumb" class="mb-4 mt-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li><li class="breadcrumb-item active">Beasiswa</li></ol></nav>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Kelola Beasiswa</h1>
            <a href="{{ route('admin.beasiswa.create') }}" class="btn btn-primary"><svg class="icon me-1"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-plus') }}"></use></svg> Tambah Beasiswa</a>
        </div>
        <div class="card mb-4"><div class="card-body">
            <table id="beasiswa-table" class="table table-striped table-hover align-middle" style="width:100%">
                <thead><tr><th width="5%">No</th><th>Nama Beasiswa</th><th>Deskripsi</th><th width="10%">Status</th><th width="12%">Aksi</th></tr></thead>
            </table>
        </div></div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('admin/vendor/datatables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var table = $('#beasiswa-table').DataTable({
                processing: true, serverSide: true, ajax: '{{ route('admin.beasiswa.datatable') }}',
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama', name: 'nama' },
                    { data: 'deskripsi_short', name: 'deskripsi', orderable: false },
                    { data: 'status', orderable: false, searchable: false },
                    { data: 'aksi', orderable: false, searchable: false },
                ],
                language: {
                    url: '{{ asset("admin/vendor/datatables/id.json") }}',
                    emptyTable: '<div class="py-4 text-center text-body-secondary">' +
                        '<svg class="icon icon-3xl mb-2"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-graduation-cap') }}"></use></svg>' +
                        '<div class="fw-semibold mb-1">Belum ada beasiswa</div>' +
                        '<a href="{{ route('admin.beasiswa.create') }}" class="btn btn-sm btn-primary mt-2">Tambah Beasiswa Pertama</a>' +
                        '</div>'
                }
            });
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault(); var url = $(this).data('url'), nama = $(this).data('nama');
                Swal.fire({ title: 'Hapus Beasiswa?', text: '"' + nama + '" akan dihapus.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e55353', cancelButtonColor: '#636f83', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
                }).then((r) => { if (r.isConfirmed) { $.ajax({ url: url, type: 'DELETE', success: function(r) { Swal.fire('Berhasil!', r.message, 'success'); table.ajax.reload(); }, error: function() { Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error'); } }); } });
            });
        });
    </script>
@endpush
