@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><h5>Edit Akreditasi</h5></div>
        <div class="card-body">
            <form action="{{ route('admin.akreditasi.update', $akreditasi->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Program Studi</label>
                    <input type="text" name="program_studi" class="form-control" value="{{ $akreditasi->program_studi }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">No SK</label>
                    <input type="text" name="no_sk" class="form-control" value="{{ $akreditasi->no_sk }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Peringkat</label>
                    <input type="text" name="peringkat" class="form-control" value="{{ $akreditasi->peringkat }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Tahun SK</label>
                    <input type="text" name="tahun_sk" class="form-control" value="{{ $akreditasi->tahun_sk }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Kedaluwarsa</label>
                    <input type="date" name="tanggal_kedaluwarsa" class="form-control" value="{{ $akreditasi->tanggal_kedaluwarsa }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="Aktif" {{ $akreditasi->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Tidak Aktif" {{ $akreditasi->status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Upload File Sertifikat (PDF)</label>
                    <input type="file" name="file_sertifikat" class="form-control" accept=".pdf">
                    @if($akreditasi->file_sertifikat)
                        <small class="text-muted">File saat ini: 
                            <a href="{{ Storage::url($akreditasi->file_sertifikat) }}" target="_blank">Lihat PDF</a>
                        </small>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.akreditasi.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection