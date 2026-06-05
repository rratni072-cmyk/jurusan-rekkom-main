@extends('layouts.admin')
@section('title', 'Detail Pesan')

@section('content')
    <div class="container-lg px-0">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-4 mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.pesan.index') }}">Pesan Masuk</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>

        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <h1 class="h3 mb-0">Detail Pesan</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.pesan.index') }}" class="btn btn-secondary">
                    <svg class="icon me-1">
                        <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-arrow-left') }}"></use>
                    </svg>
                    Kembali
                </a>
                <a href="mailto:{{ $pesan->email }}?subject=Re:%20{{ rawurlencode($pesan->subjek) }}"
                    class="btn btn-primary">
                    <svg class="icon me-1">
                        <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-envelope-letter') }}"></use>
                    </svg>
                    Balas via Email
                </a>
                <button type="button" class="btn btn-danger" id="btn-delete-pesan"
                    data-url="{{ route('admin.pesan.destroy', $pesan->id) }}">
                    <svg class="icon me-1">
                        <use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-trash') }}"></use>
                    </svg>
                    Hapus
                </button>
            </div>
        </div>

        <div class="row">
            {{-- Konten utama --}}
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-body-tertiary">
                        <h5 class="mb-0">{{ $pesan->subjek }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-md bg-primary-subtle text-primary me-3 rounded-circle d-flex align-items-center justify-content-center"
                                style="width:48px;height:48px;font-weight:600;">
                                {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($pesan->nama, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $pesan->nama }}</div>
                                <a href="mailto:{{ $pesan->email }}" class="text-decoration-none small text-body-secondary">
                                    {{ $pesan->email }}
                                </a>
                            </div>
                            <div class="ms-auto small text-body-secondary text-end">
                                <div>@tanggal($pesan->created_at)</div>
                                <div>@waktuLokal($pesan->created_at, 'H:i')</div>
                            </div>
                        </div>

                        <hr>

                        <div class="pesan-isi">
                            {!! nl2br(e($pesan->pesan)) !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar info --}}
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header bg-body-tertiary">
                        <strong>Informasi</strong>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0 small">
                            <dt class="col-5 text-body-secondary">Status</dt>
                            <dd class="col-7" id="pesan-status-badge">
                                @if($pesan->is_read)
                                    <span class="badge bg-secondary">Sudah Dibaca</span>
                                @else
                                    <span class="badge bg-warning text-dark">Belum Dibaca</span>
                                @endif
                            </dd>

                            <dt class="col-5 text-body-secondary">Diterima</dt>
                            <dd class="col-7">@waktuRelatif($pesan->created_at)</dd>

                            <dt class="col-5 text-body-secondary">Tanggal</dt>
                            <dd class="col-7">@waktuLokal($pesan->created_at, 'd M Y H:i')</dd>

                            <dt class="col-5 text-body-secondary">Pengirim</dt>
                            <dd class="col-7">{{ $pesan->nama }}</dd>

                            <dt class="col-5 text-body-secondary">Email</dt>
                            <dd class="col-7 text-truncate"><a href="mailto:{{ $pesan->email }}">{{ $pesan->email }}</a></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Auto-mark as read jika belum dibaca (idempotent di server).
            @if(! $pesan->is_read)
                $.ajax({
                    url: '{{ route('admin.pesan.mark-read', $pesan->id) }}',
                    type: 'POST',
                    success: function () {
                        $('#pesan-status-badge').html('<span class="badge bg-secondary">Sudah Dibaca</span>');
                    }
                });
            @endif

            // Hapus pesan dengan konfirmasi.
            $('#btn-delete-pesan').on('click', function () {
                var url = $(this).data('url');
                Swal.fire({
                    title: 'Hapus Pesan?',
                    text: 'Pesan dari "{{ addslashes($pesan->nama) }}" akan dihapus permanen.',
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
                                Swal.fire('Berhasil!', r.message, 'success').then(() => {
                                    window.location.href = '{{ route('admin.pesan.index') }}';
                                });
                            },
                            error: function () {
                                Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus pesan.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
