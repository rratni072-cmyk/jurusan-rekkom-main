@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Data Akreditasi</h4>

    <a href="{{ route('admin.akreditasi.create') }}" class="btn btn-primary">
        Tambah Akreditasi
    </a>
</div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Program Studi</th>
                        <th>No SK</th>
                        <th>Peringkat</th>
                        <th>Tahun SK</th>
                        <th>Kedaluwarsa</th>
                        <th>Status</th>
                        <th>File</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($akreditasis as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->program_studi }}</td>
                        <td>{{ $item->no_sk }}</td>
                        <td>{{ $item->peringkat }}</td>
                        <td>{{ $item->tahun_sk }}</td>
                        <td>{{ $item->tanggal_kedaluwarsa }}</td>
                        <td>{{ $item->status }}</td>
                        <td>
                            @if($item->file_sertifikat)
                                <a href="{{ Storage::url($item->file_sertifikat) }}" target="_blank" class="btn btn-sm btn-info">Lihat PDF</a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.akreditasi.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection