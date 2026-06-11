@extends('layouts.admin')

@section('title', 'Kelola Akreditasi')

@section('content')
    <div class="container-lg px-0">
        <nav aria-label="breadcrumb" class="mb-4 mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Akreditasi</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Kelola Akreditasi Program Studi</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-coreui-dismiss="alert"></button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Program Studi</th>
                            <th width="20%">No. SK</th>
                            <th width="10%">Peringkat</th>
                            <th width="10%">Tahun SK</th>
                            <th width="15%">Tanggal Kedaluwarsa</th>
                            <th width="10%">Status</th>
                            <th width="10%">Sertifikat</th>
                            <th width="8%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($akreditasis as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->program_studi }}</td>
                            <td>{{ $item->no_sk ?? '-' }}</td>
                            <td><span class="badge bg-primary">{{ $item->peringkat }}</span></td>
                            <td>{{ $item->tahun_sk ?? '-' }}</td>
                            <td>{{ $item->tanggal_kedaluwarsa ? \Carbon\Carbon::parse($item->tanggal_kedaluwarsa)->format('d M Y') : '-' }}</td>
                            <td>{{ $item->status ?? '-' }}</td>
                            <td>
                                @if($item->file_sertifikat)
                                    <a href="{{ Storage::url($item->file_sertifikat) }}" target="_blank" class="btn btn-sm btn-success">
                                        <svg class="icon"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-file') }}"></use></svg>
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-muted small">Belum ada</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.akreditasi.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                    <svg class="icon"><use xlink:href="{{ asset('admin/icons/sprites/free.svg#cil-pencil') }}"></use></svg>
                                    Edit
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection