@extends('layouts.admin')
@section('title', 'Pesan Masuk')
@push('styles')<link rel="stylesheet" href="{{ asset('admin/vendor/datatables/datatables.min.css') }}">@endpush
@section('content')
<div class="container-lg px-0">
    <nav aria-label="breadcrumb" class="mb-4 mt-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Pesan Masuk</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Pesan Masuk</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <table id="pesan-table" class="table table-striped table-hover align-middle" style="width:100%">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Pengirim</th>
                        <th>Subjek</th>
                        <th width="10%">Status</th>
                        <th width="12%">Tanggal</th>
                        <th width="10%">Aksi</th>
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
    var table = $('#pesan-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.pesan.datatable") }}',
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'nama' },
            { data: 'subjek' },
            { data: 'status', orderable: false, searchable: false },
            { data: 'created_at' },
            { data: 'aksi', orderable: false, searchable: false }
        ],
        order: [[4, 'desc']],
        language: {
            url: '{{ asset("admin/vendor/datatables/id.json") }}',
            emptyTable: '<div class="py-4 text-center text-body-secondary">' +
                '<svg class="icon icon-3xl mb-2"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-envelope-open') }}"></use></svg>' +
                '<div class="fw-semibold mb-1">Belum ada pesan masuk</div>' +
                '<small class="text-body-tertiary">Pesan dari pengunjung akan muncul di sini.</small>' +
                '</div>'
        }
    });

    // Delete handler
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        var nama = $(this).data('nama');

        Swal.fire({
            title: 'Hapus Pesan?',
            text: 'Pesan dari "' + nama + '" akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e55353',
            cancelButtonColor: '#636f83',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success: function(response) {
                        Swal.fire('Berhasil!', response.message, 'success');
                        table.ajax.reload();
                    },
                    error: function() {
                        Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endpush
