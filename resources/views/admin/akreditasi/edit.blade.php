@extends('layouts.admin')

@section('title', 'Edit Akreditasi')

@section('content')
    <div class="container-lg px-0">
        <nav aria-label="breadcrumb" class="mb-4 mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.akreditasi.index') }}">Akreditasi</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Edit Akreditasi</h1>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.akreditasi.update', $akreditasi->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Program Studi</label>
                        <input type="text" name="program_studi" class="form-control" value="{{ old('program_studi', $akreditasi->program_studi) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">No. SK</label>
                        <input type="text" name="no_sk" class="form-control" value="{{ old('no_sk', $akreditasi->no_sk) }}">
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Peringkat</label>
                            <input type="text" name="peringkat" class="form-control" value="{{ old('peringkat', $akreditasi->peringkat) }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Tahun SK</label>
                            <input type="text" name="tahun_sk" class="form-control" value="{{ old('tahun_sk', $akreditasi->tahun_sk) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Tanggal Kedaluwarsa</label>
                            <input type="date" name="tanggal_kedaluwarsa" class="form-control" value="{{ old('tanggal_kedaluwarsa', $akreditasi->tanggal_kedaluwarsa) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <input type="text" name="status" class="form-control" value="{{ old('status', $akreditasi->status) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Upload Sertifikat (PDF)</label>
                        @if($akreditasi->file_sertifikat)
                            <div class="mb-2">
                                <a href="{{ Storage::url($akreditasi->file_sertifikat) }}" target="_blank" class="btn btn-sm btn-success">
                                    Lihat Sertifikat Saat Ini
                                </a>
                            </div>
                        @endif
                        <input type="file" name="file_sertifikat" class="form-control" accept=".pdf">
                        <small class="text-muted">Format PDF. Kosongkan jika tidak ingin mengganti.</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('admin.akreditasi.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection